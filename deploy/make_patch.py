"""
Build a zip of only the files that differ from the live server.

    python deploy/make_patch.py path/to/manifest.json

manifest.json comes from deploy/server-manifest.php. The zip's paths are laid
out relative to your cPanel home folder, so upload it there and Extract —
code lands in the Laravel folder and public files land in public_html.
"""

import hashlib
import json
import os
import sys
import zipfile
from datetime import datetime

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))

INCLUDE_DIRS = ["app", "bootstrap", "config", "database", "lang", "public", "resources", "routes"]
INCLUDE_FILES = ["artisan", "composer.json", "composer.lock"]
EXCLUDE_EXACT = {"public/index.php", "public/.htaccess", "public/hot"}
EXCLUDE_PREFIXES = ("bootstrap/cache/", "public/storage")
TEXT_EXT = {"php", "js", "css", "json", "md", "txt", "xml", "yml", "yaml", "svg",
            "html", "htaccess", "lock", "webmanifest"}


def fingerprint(path: str) -> str:
    name = os.path.basename(path)
    ext = (os.path.splitext(name)[1][1:] or name.lstrip(".")).lower()
    with open(path, "rb") as f:
        data = f.read()
    if ext in TEXT_EXT:
        data = data.replace(b"\r\n", b"\n")
    return hashlib.md5(data).hexdigest()


def local_files() -> dict:
    out = {}
    for name in INCLUDE_FILES:
        out[name] = os.path.join(ROOT, name)
    for d in INCLUDE_DIRS:
        for dirpath, _, files in os.walk(os.path.join(ROOT, d)):
            for f in files:
                full = os.path.join(dirpath, f)
                rel = os.path.relpath(full, ROOT).replace("\\", "/")
                if rel in EXCLUDE_EXACT or rel.startswith(EXCLUDE_PREFIXES):
                    continue
                out[rel] = full
    return out


def main() -> None:
    if len(sys.argv) != 2:
        sys.exit(__doc__)

    with open(sys.argv[1], encoding="utf-8") as f:
        manifest = json.load(f)

    server = manifest["files"]
    root_dir = manifest.get("root_dir") or ""
    public_dir = manifest.get("public_dir")

    def target(rel: str) -> str:
        """Where a local project path lives relative to the cPanel home folder."""
        if public_dir and rel.startswith("public/"):
            return public_dir + "/" + rel[len("public/"):]
        return (root_dir + "/" + rel) if root_dir else rel

    local = local_files()
    new, changed = [], []
    for rel, full in sorted(local.items()):
        if rel not in server:
            new.append(rel)
        elif server[rel] != fingerprint(full):
            changed.append(rel)

    scanned_code = ("app/", "config/", "database/", "resources/", "routes/")
    only_on_server = sorted(r for r in server if r.startswith(scanned_code) and r not in local)

    print(f"Server: PHP {manifest.get('php_version')}, {manifest['file_count']} files, "
          f"Laravel folder '{root_dir or '(home)'}', public folder '{public_dir or root_dir + '/public'}'")
    print(f"New files:     {len(new)}")
    print(f"Changed files: {len(changed)}")

    if "composer.lock" in changed:
        print("\n!! composer.lock differs from the server - its vendor/ folder is out of date."
              "\n   Run `composer install --no-dev` on the server, or ask for a vendor zip.")

    if only_on_server:
        print(f"\nOn the server but not in this project ({len(only_on_server)}) - not touched, review manually:")
        for r in only_on_server:
            print("   " + r)

    todo = new + changed
    if not todo:
        print("\nNothing to deploy - the server is up to date.")
        return

    stamp = datetime.now().strftime("%Y%m%d-%H%M")
    out = os.path.join(os.path.dirname(ROOT), f"kilimanjaro-patch-{stamp}.zip")
    listing = ["NEW " + target(r) for r in new] + ["CHANGED " + target(r) for r in changed]
    with zipfile.ZipFile(out, "w", zipfile.ZIP_DEFLATED) as z:
        for rel in todo:
            z.write(local[rel], target(rel))
    with open(out.replace(".zip", "-files.txt"), "w", encoding="utf-8") as f:
        f.write("\n".join(listing) + "\n")

    print(f"\nPatch zip: {out} ({os.path.getsize(out) / 1024 / 1024:.1f} MB)")
    print(f"File list: {out.replace('.zip', '-files.txt')}")


if __name__ == "__main__":
    main()
