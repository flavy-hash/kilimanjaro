<?php

/**
 * Deployment helper — upload to public_html, open
 *   https://your-domain/server-manifest.php?token=<TOKEN>
 * and it downloads manifest.json: every site file on the server with a content
 * hash. Feed that file to deploy/make_patch.py locally to build a zip of only
 * what changed. DELETE THIS FILE FROM THE SERVER AS SOON AS YOU'RE DONE.
 *
 * Optional: &root=folder-name  if several Laravel copies live in your home dir.
 */

const TOKEN = '8c75742ebad5100af4ec66f415b7c55eb48e3e42';

if (! hash_equals(TOKEN, (string) ($_GET['token'] ?? ''))) {
    http_response_code(404);
    exit;
}

set_time_limit(600);

// Never read or list these — secrets, dependencies, runtime data.
const SKIP_DIRS = ['vendor', 'node_modules', 'storage', '.git', 'bootstrap/cache'];
const SKIP_FILES = ['.env'];
const TEXT_EXT = ['php', 'js', 'css', 'json', 'md', 'txt', 'xml', 'yml', 'yaml', 'svg', 'html', 'htaccess', 'lock', 'webmanifest'];

$here = realpath(__DIR__);
$home = dirname($here);

// Find the Laravel folder: the one containing artisan + app/.
$candidates = array_unique(array_merge([$here, $home], glob($home.'/*', GLOB_ONLYDIR) ?: []));
$roots = array_values(array_filter($candidates, fn ($d) => is_file("$d/artisan") && is_dir("$d/app")));

if (isset($_GET['root'])) {
    $roots = array_values(array_filter($roots, fn ($d) => basename($d) === $_GET['root']));
}

header('Content-Type: text/plain; charset=utf-8');
if (count($roots) !== 1) {
    echo "Could not pick a single Laravel folder. Found:\n";
    foreach ($roots ?: $candidates as $r) echo " - ".basename($r)."\n";
    echo "\nRe-open this URL adding &root=<folder-name> for the right one.\n";
    exit;
}

$root = realpath($roots[0]);
$rootPublic = realpath("$root/public");
$samePublic = $rootPublic !== false && $rootPublic === $here;

function fingerprint(string $path): string
{
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION) ?: ltrim(basename($path), '.'));
    $data = file_get_contents($path);
    if (in_array($ext, TEXT_EXT, true)) {
        $data = str_replace("\r\n", "\n", $data); // ignore Windows vs Linux line endings
    }
    return md5($data);
}

function scan(string $base, string $prefix, array $skipDirs, array &$out): void
{
    $it = new RecursiveIteratorIterator(
        new RecursiveCallbackFilterIterator(
            new RecursiveDirectoryIterator($base, FilesystemIterator::SKIP_DOTS),
            function ($file) use ($base, $skipDirs) {
                $rel = str_replace('\\', '/', substr($file->getPathname(), strlen($base) + 1));
                if ($file->isLink()) return false;
                if ($file->isDir()) return ! in_array($rel, $skipDirs, true);
                return ! in_array($rel, SKIP_FILES, true) && $rel !== 'server-manifest.php';
            }
        )
    );
    foreach ($it as $file) {
        if (! $file->isFile()) continue;
        $rel = str_replace('\\', '/', substr($file->getPathname(), strlen($base) + 1));
        $out[$prefix.$rel] = fingerprint($file->getPathname());
    }
}

$files = [];
// When public_html is separate, the server's own "public" copy is ignored and
// public_html is reported as "public/" so paths line up with the local project.
scan($root, '', $samePublic ? SKIP_DIRS : array_merge(SKIP_DIRS, ['public']), $files);
if (! $samePublic) {
    scan($here, 'public/', [], $files);
}
ksort($files);

$manifest = [
    'generated_at' => date('c'),
    'php_version' => PHP_VERSION,
    'root_dir' => $root === $home ? '' : basename($root),
    'public_dir' => $samePublic ? null : basename($here),
    'file_count' => count($files),
    'files' => $files,
];

header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="manifest.json"');
echo json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
