<?php

namespace Core;

class Session
{
    protected static bool $started = false;

    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
            static::$started = true;
        }
    }

    public static function get(string $key, $default = null)
    {
        static::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function set(string $key, $value): void
    {
        static::start();
        $_SESSION[$key] = $value;
    }

    public static function has(string $key): bool
    {
        static::start();
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        static::start();
        unset($_SESSION[$key]);
    }

    public static function flash(string $key, $value): void
    {
        static::start();
        $_SESSION['_flash'][$key] = $value;
    }

    public static function getFlash(string $key, $default = null)
    {
        static::start();
        $value = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }

    public static function hasFlash(string $key): bool
    {
        static::start();
        return isset($_SESSION['_flash'][$key]);
    }

    public static function old(string $key, $default = ''): string
    {
        return static::getFlash('_old_input.' . $key, $default);
    }

    public static function flushOldInput(): void
    {
        static::start();
        unset($_SESSION['_flash']['_old_input']);
    }

    public static function regenerate(): void
    {
        static::start();
        session_regenerate_id(true);
    }

    public static function destroy(): void
    {
        static::start();
        session_destroy();
        $_SESSION = [];
    }

    public static function id(): string
    {
        static::start();
        return session_id();
    }

    public static function token(): string
    {
        if (!static::has('_token')) {
            static::set('_token', bin2hex(random_bytes(32)));
        }
        return static::get('_token');
    }

    public static function verifyToken(string $token): bool
    {
        return hash_equals(static::get('_token', ''), $token);
    }
}
