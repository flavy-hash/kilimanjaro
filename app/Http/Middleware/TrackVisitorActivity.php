<?php

namespace App\Http\Middleware;

use App\Models\VisitorActivity;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

/**
 * Records one anonymous row per public page view for the admin "Visitor
 * Activity" report. Never stores an IP address, a cookie, or anything that
 * identifies a person — see VisitorActivity's docblock for the privacy model.
 */
class TrackVisitorActivity
{
    private const BOT_PATTERN = '/bot|crawl|spider|slurp|bingpreview|facebookexternalhit|whatsapp|telegram|curl|wget|python-requests|axios|headless|monitor|pingdom|uptime/i';

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldRecord($request, $response)) {
            $this->record($request);
        }

        return $response;
    }

    private function shouldRecord(Request $request, Response $response): bool
    {
        if (! $request->isMethod('GET') || $response->getStatusCode() >= 400) {
            return false;
        }

        if (! $request->acceptsHtml() || $request->ajax()) {
            return false;
        }

        if (auth()->check()) {
            return false;
        }

        if ($request->header('DNT') === '1' || $request->header('Sec-GPC') === '1') {
            return false;
        }

        $userAgent = (string) $request->userAgent();

        if ($userAgent === '' || preg_match(self::BOT_PATTERN, $userAgent)) {
            return false;
        }

        return true;
    }

    private function record(Request $request): void
    {
        $route = $request->route();
        $routeName = $route?->getName();
        $trip = $route?->parameter('trip');

        $referrerHost = null;
        if ($referrer = $request->header('referer')) {
            $host = parse_url($referrer, PHP_URL_HOST);
            if ($host && $host !== $request->getHost()) {
                $referrerHost = $host;
            }
        }

        [$countryCode, $countryName] = $this->lookupCountry($request->ip());

        VisitorActivity::create([
            'visitor_hash' => $this->visitorHash($request),
            'page_path' => '/'.ltrim($request->path(), '/'),
            'page_label' => $this->pageLabel($routeName, $request->path()),
            'package_slug' => $routeName === 'trip.show' ? $trip : null,
            'device_type' => $this->deviceType((string) $request->userAgent()),
            'country_code' => $countryCode,
            'country_name' => $countryName,
            'referrer_host' => $referrerHost,
            'visited_at' => now(),
        ]);
    }

    /**
     * Salted hash of IP + user agent + today's date. The date makes it
     * rotate daily by construction — the same visitor gets a different hash
     * tomorrow, so rows can never be joined across days.
     */
    private function visitorHash(Request $request): string
    {
        return hash('sha256', implode('|', [
            $request->ip(),
            $request->userAgent(),
            now()->toDateString(),
            config('app.key'),
        ]));
    }

    private function deviceType(string $userAgent): string
    {
        if (preg_match('/iPad|Tablet|Kindle|PlayBook/i', $userAgent)) {
            return 'tablet';
        }

        if (preg_match('/Mobi|Android|iPhone|iPod|Windows Phone/i', $userAgent)) {
            return 'mobile';
        }

        return 'desktop';
    }

    private function pageLabel(?string $routeName, string $path): string
    {
        return match (true) {
            $path === '/' => 'home',
            $routeName === 'trip.show' => 'trip',
            $routeName === 'blog.show' => 'blog post',
            default => str_replace('-', ' ', trim($path, '/')) ?: 'home',
        };
    }

    /**
     * Looks up the country for an IP via DB-IP's free API. The raw IP is
     * never written to the database — only here, transiently, to make the
     * lookup, and in a short-lived cache keyed by a hash of the IP rather
     * than the IP itself.
     *
     * @return array{0: ?string, 1: ?string}
     */
    private function lookupCountry(?string $ip): array
    {
        if (! $ip || in_array($ip, ['127.0.0.1', '::1'], true)) {
            return [null, null];
        }

        $cacheKey = 'geoip:'.hash('sha256', $ip);

        return Cache::remember($cacheKey, now()->addHours(12), function () use ($ip) {
            try {
                $res = Http::timeout(3)->get("https://api.db-ip.com/v2/free/{$ip}");

                if (! $res->successful()) {
                    return [null, null];
                }

                $data = $res->json();

                return [$data['countryCode'] ?? null, $data['countryName'] ?? null];
            } catch (\Throwable) {
                return [null, null];
            }
        });
    }
}
