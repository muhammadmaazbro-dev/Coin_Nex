<?php
declare(strict_types=1);

final class Helper
{
    public static function url(string $path = ''): string
    {
        $base = rtrim(BASE_URL, '/');
        $trimmed = ltrim($path, '/');
        if ($trimmed === '') {
            return $base === '' ? '/' : $base;
        }
        return ($base === '' ? '' : $base) . '/' . $trimmed;
    }

    public static function asset(string $path): string
    {
        $base = rtrim(BASE_URL, '/');
        $clean = ltrim($path, '/');
        $root = defined('ROOT_PATH') ? ROOT_PATH : (defined('ROOT_DIR') ? ROOT_DIR : dirname(__DIR__, 2));
        $fullPath = $root . '/public/' . $clean;
        $ver = is_file($fullPath) ? (string) filemtime($fullPath) : (defined('APP_VERSION') ? APP_VERSION : '1.2.0');
        return ($base === '' ? '' : $base) . '/public/' . $clean . '?v=' . $ver;
    }

    public static function icon(string $name, string $size = '20'): string
    {
        static $icons = [
            'home' => '<path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/><path d="M9.5 21v-6h5v6"/>',
            'swap' => '<path d="M7 3 3 7l4 4"/><path d="M3 7h14"/><path d="M17 21l4-4-4-4"/><path d="M21 17H7"/>',
            'chart' => '<path d="M4 20V10"/><path d="M10 20V4"/><path d="M16 20v-6"/><path d="M22 20H2"/>',
            'target' => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1"/>',
            'tags' => '<path d="M20 13.5 10.5 3H5v5.5L14.5 18l5.5-5.5z"/><circle cx="8" cy="8" r="1.1"/><path d="M7 16h5"/>',
            'brain' => '<path d="M12 3a3 3 0 0 0-3 3v6a3 3 0 0 1-6 0v-1a3 3 0 0 1 3-3h.5A3 3 0 0 0 9 5V.8"/>',
            'bulb' => '<path d="M9 18h6"/><path d="M10 22h4"/><path d="M12 3a6.5 6.5 0 0 0-4 11.6c.8.7 1 1.8 1 2.4h6c0-.6.2-1.7 1-2.4A6.5 6.5 0 0 0 12 3z"/>',
            'shield' => '<path d="M12 3l7 3v6c0 4.5-3 8-7 9-4-1-7-4.5-7-9V6z"/><path d="M9 12l2 2 4-4"/>',
            'bell' => '<path d="M6 9a6 6 0 0 1 12 0c0 5 2 6 2 6H4s2-1 2-6"/><path d="M10 20a2 2 0 0 0 4 0"/>',
            'gear' => '<circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M4.9 4.9l2.1 2.1M17 17l2.1 2.1M19.1 4.9 17 7M7 17l-2.1 2.1"/>',
            'user' => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-6 8-6s8 2 8 6"/>',
            'logout' => '<path d="M9 4H4v16h5"/><path d="M16 8l4 4-4 4"/><path d="M20 12H9"/>',
            'plus' => '<path d="M12 5v14M5 12h14"/>',
            'x' => '<path d="M6 6l12 12M18 6 6 18"/>',
            'search' => '<circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/>',
            'download' => '<path d="M12 3v12M7 10l5 5 5-5"/><path d="M4 21h16"/>',
            'upload' => '<path d="M12 15V3M7 8l5-5 5 5"/><path d="M4 21h16"/>',
            'edit' => '<path d="M4 20h4L20 8a2.8 2.8 0 0 0-4-4L4 16z"/><path d="M14 6l4 4"/>',
            'trash' => '<path d="M4 7h16M9 7V4h6v3M6 7l1 14h10l1-14"/><path d="M9 11v6M15 11v6"/>',
            'check' => '<path d="M4 12.5 9.5 18 20 6.5"/>',
            'check2' => '<path d="M20 6 9 17l-5-5"/>',
            'alert' => '<path d="M12 3 2 21h20z"/><path d="M12 9v5M12 17.5v.5"/>',
            'info' => '<circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8v-.5"/>',
            'wallet' => '<rect x="3" y="6" width="18" height="14" rx="3"/><path d="M3 10h18"/><path d="M16 14h2"/>',
            'income' => '<path d="M12 19V5"/><path d="M6 11l6-6 6 6"/>',
            'expense' => '<path d="M12 5v14"/><path d="M6 13l6 6 6-6"/>',
            'balance' => '<path d="M12 3v18"/><path d="M12 21c-3 0-4.5-2-6-2"/><path d="M7 8H3c1 2 4 2 4 4s-3 2-4 4c-1 0-1 1 0 1h4"/><path d="M14 8h7c-1 2-4 2-4 4s3 2 4 4c1 0 1 1 0 1h-7"/>',
            'calendar' => '<rect x="3" y="5" width="18" height="16" rx="3"/><path d="M8 3v4M16 3v4M3 10h18"/>',
            'refresh' => '<path d="M21 12a9 9 0 1 1-3-6.7"/><path d="M21 3v6h-6"/>',
            'bookmark' => '<path d="M6 3h12v18l-6-4-6 4z"/>',
            'filter' => '<path d="M4 5h16M7 12h10M10 19h4"/>',
            'graph' => '<path d="M3 21V10M9.5 21V3M16 21v-8M22 21H1"/>',
            'trend-up' => '<path d="M3 17l6-6 4 4 8-8"/><path d="M15 7h6v6"/>',
            'trend-down' => '<path d="M3 7l6 6 4-4 8 8"/><path d="M15 17h6v-6"/>',
            'clipboard' => '<rect x="4" y="4" width="16" height="18" rx="3"/><path d="M9 4a3 3 0 0 1 6 0M9 10h6M9 14h6M9 18h4"/>',
            'lock' => '<rect x="5" y="10" width="14" height="11" rx="3"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>',
            'mail' => '<rect x="3" y="5" width="18" height="14" rx="3"/><path d="M3 7l9 6 9-6"/>',
            'eye' => '<path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>',
            'eye-off' => '<path d="M3 3l18 18M10.5 5.2A10.5 10.5 0 0 1 22 12s-1.5 2.6-3.9 4.4M6.5 6.8C3.9 8.4 2 12 2 12s3.5 6 10 6c1.6 0 3-.4 4.2-1"/>',
            'star' => '<path d="M12 3l2.7 5.6 6.1.8-4.4 4.3 1 6L12 17l-5.4 2.7 1-6L3.2 9.4l6.1-.8z"/>',
            'sparkle' => '<path d="M12 3c.6 3.6 2.4 5.4 6 6-3.6.6-5.4 2.4-6 6-.6-3.6-2.4-5.4-6-6 3.6-.6 5.4-2.4 6-6z"/>',
            'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/>',
            'layers' => '<path d="M12 2 2 7l10 5 10-5z"/><path d="M2 12l10 5 10-5M2 17l10 5 10-5"/>',
            'zap' => '<path d="M13 2 4 14h7l-1 8 9-12h-7z"/>',
            'sun' => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M2 12h2M20 12h2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M19.1 4.9l-1.4 1.4M6.3 17.7l-1.4 1.4"/>',
            'moon' => '<path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8z"/>',
            'bee' => '<ellipse cx="12" cy="9" rx="7" ry="5"/><path d="M7.5 5.5C7 3.5 6 2.5 4.5 2.5M16.5 5.5c.5-1.8 1.6-2.8 3-3"/><path d="M9.5 7l.5 2M13 7l.5 2"/><path d="M5 9c-2 .3-3 1.5-3 3.5 0 2.5 2 4 10 4s10-1.5 10-4c0-2-1-3.2-3-3.5"/>',
            'flag' => '<path d="M5 21V4"/><path d="M5 4c4-2 7 2 11 0v9c-4 2-7-2-11 0"/>',
            'chevron-left' => '<path d="M15 5 8 12l7 7"/>',
            'chevron-right' => '<path d="M9 5l7 7-7 7"/>',
            'dice' => '<rect x="3" y="3" width="18" height="18" rx="4"/><circle cx="9" cy="9" r="1"/><circle cx="15" cy="15" r="1"/><circle cx="15" cy="9" r="1"/><circle cx="9" cy="15" r="1"/>',
            'duplicate' => '<rect x="8" y="8" width="13" height="13" rx="3"/><path d="M4 16V5a2 2 0 0 1 2-2h11"/>',
            'list' => '<path d="M8 6h13M8 12h13M8 18h13M3.5 6h.5M3.5 12h.5M3.5 18h.5"/>',
            'reply' => '<path d="M9 17 4 12l5-5"/><path d="M4 12h10a6 6 0 0 1 6 6"/>',
            'briefcase' => '<rect x="3" y="7" width="18" height="13" rx="3"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M3 12h18M10 12v2h4v-2"/>',
            'gift' => '<rect x="3" y="8" width="18" height="5"/><path d="M5 13v7h14v-7M12 8v12"/><path d="M12 8c-5 0-6-2.5-4-4 1.7-1.4 4 .5 4 2 0-1.5 2.3-3.4 4-2 2 1.5 1 4-4 4z"/>',
            'car' => '<path d="M4 15l1.5-5A2 2 0 0 1 7.4 8.6h9.2a2 2 0 0 1 1.9 1.4L20 15"/><path d="M4 15h16v4H4z"/><circle cx="7" cy="17" r="1.2"/><circle cx="17" cy="17" r="1.2"/>',
            'book' => '<path d="M4 5a3 3 0 0 1 3-3h13v18H7a3 3 0 0 0-3 3z"/><path d="M4 23c0-1.5 1-3 3-3h13"/>',
            'tv' => '<rect x="3" y="7" width="18" height="12" rx="2"/><path d="M8 3l4 4 4-4"/>',
            'film' => '<rect x="4" y="4" width="16" height="16" rx="3"/><path d="M4 9h16M4 15h16M9 4v16M15 4v16"/>',
        ];
        $paths = $icons[$name] ?? '';
        if ($paths === '') return '';
        return '<svg class="cc-icon" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $paths . '</svg>';
    }

    public static function redirect(string $path): never
    {
        header('Location: ' . self::url($path));
        exit;
    }

    public static function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    public static function csrfToken(): string
    {
        if (!Session::has('csrf_token')) {
            Session::set('csrf_token', bin2hex(random_bytes(32)));
        }
        return Session::get('csrf_token');
    }

    public static function csrfField(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . self::e(self::csrfToken()) . '">';
    }

    public static function csrfVerify(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!is_string($token) || !hash_equals(self::csrfToken(), $token)) {
            http_response_code(419);
            self::abort('Session expired. Please try again.', 419);
        }
    }

    public static function post(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    public static function old(string $key, mixed $default = ''): string
    {
        return self::e(Session::get('old_input')[$key] ?? $default);
    }

    public static function rememberOld(array $data): void
    {
        Session::set('old_input', $data);
    }

    public static function clearOld(): void
    {
        Session::delete('old_input');
    }

    public static function money(float $amount, ?string $symbol = null): string
    {
        $symbol ??= DEFAULT_CURRENCY_SYMBOL;
        $formatted = (floor($amount) == $amount) ? number_format($amount, 0) : number_format($amount, 2);
        return $symbol . ' ' . $formatted;
    }

    public static function success(string $message): void
    {
        Session::flashAdd('success', $message);
    }

    public static function error(string $message): void
    {
        Session::flashAdd('error', $message);
    }

    public static function info(string $message): void
    {
        Session::flashAdd('info', $message);
    }

    public static function abort(string $message = 'Page not found', int $code = 404): never
    {
        $reason = [
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            419 => 'Page Expired',
            500 => 'Internal Server Error',
        ][$code] ?? 'Error';
        header('HTTP/1.1 ' . $code . ' ' . $reason, true, (int) $code);
        $title = "Error $code";
        include VIEWS_PATH . '/pages/error.php';
        exit;
    }

    public static function currentUser(): ?array
    {
        $user = Session::get('user');
        return is_array($user) ? $user : null;
    }

    public static function userId(): ?int
    {
        $user = self::currentUser();
        return $user['user_id'] ?? null;
    }

    public static function isLoggedIn(): bool
    {
        return Session::has('user');
    }

    public static function isAdmin(): bool
    {
        $user = self::currentUser();
        return ($user['role'] ?? '') === 'admin';
    }

    public static function requireLogin(): void
    {
        if (!self::isLoggedIn()) {
            self::error('Please login to continue.');
            self::redirect('login');
        }
    }

    public static function requireAdmin(): void
    {
        self::requireLogin();
        if (!self::isAdmin()) {
            self::abort('Access denied. Admins only.', 403);
        }
    }

    public static function formatDate(string $value, string $format = 'M j, Y'): string
    {
        $ts = strtotime($value);
        return $ts === false ? $value : date($format, $ts);
    }

    public static function timeAgo(string $datetime): string
    {
        $ts  = strtotime($datetime);
        $diff = time() - $ts;
        if ($diff < 60) return 'just now';
        if ($diff < 3600) return floor($diff / 60) . ' min ago';
        if ($diff < 86400) return floor($diff / 3600) . ' hr ago';
        if ($diff < 604800) return floor($diff / 86400) . ' day(s) ago';
        return date('M j, Y', $ts);
    }

    public static function to2(float $n): float
    {
        return round($n, 2);
    }

    public static function json(array $data, int $code = 200): never
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    public static function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public static function isPost(): bool
    {
        return self::method() === 'POST';
    }

    public static function basePathUri(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $uri = rawurldecode($uri);
        $base = rawurldecode(rtrim(BASE_URL, '/'));
        if ($base !== '' && strncmp($uri, $base, strlen($base)) === 0) {
            $uri = substr($uri, strlen($base));
        }
        $clean = trim($uri, '/');
        if (str_starts_with($clean, 'public/')) {
            $clean = substr($clean, 7);
        } elseif ($clean === 'public') {
            $clean = '';
        }
        return $clean === '' ? '/' : '/' . $clean;
    }

    public static function breadcrumbs(array $crumbs = []): string
    {
        $html = '<div class="cc-breadcrumbs"><a href="' . self::url('dashboard') . '" class="crumb-link">' . self::icon('home', '14') . ' <span>Dashboard</span></a>';
        foreach ($crumbs as $label => $link) {
            $html .= '<span class="crumb-sep">/</span>';
            if ($link) {
                $html .= '<a href="' . self::e($link) . '" class="crumb-link">' . self::e((string) $label) . '</a>';
            } else {
                $html .= '<span class="crumb-current">' . self::e((string) $label) . '</span>';
            }
        }
        $html .= '</div>';
        return $html;
    }

    public static function pageTitle(): string
    {
        return APP_NAME . ' | ' . APP_TAGLINE;
    }

    public static function randomToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    public static function clamp(float $value, float $min, float $max): float
    {
        return max($min, min($max, $value));
    }
}