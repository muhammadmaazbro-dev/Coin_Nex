<?php
declare(strict_types=1);

final class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => SESSION_LIFETIME,
                'path'     => '/',
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }
        if (self::isExpired()) {
            self::destroy();
            session_start();
        }
        self::refresh();
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function delete(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function regenerate(): void
    {
        session_regenerate_id(true);
        $_SESSION['last_activity'] = time();
    }

    public static function destroy(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }

    public static function flashAdd(string $type, string $message): void
    {
        $_SESSION['flash_messages'][] = ['type' => $type, 'message' => $message];
    }

    public static function flashGet(): array
    {
        $messages = $_SESSION['flash_messages'] ?? [];
        unset($_SESSION['flash_messages']);
        return $messages;
    }

    public static function flashHas(): bool
    {
        return !empty($_SESSION['flash_messages']);
    }

    private static function refresh(): void
    {
        if (isset($_SESSION['last_activity'])) {
            $_SESSION['last_activity'] = time();
        }
    }

    private static function isExpired(): bool
    {
        if (!isset($_SESSION['last_activity'])) {
            return false;
        }
        return (time() - (int) $_SESSION['last_activity']) > SESSION_LIFETIME;
    }
}