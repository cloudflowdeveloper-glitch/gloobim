<?php

namespace Core;

class Auth
{
    protected static ?array $user = null;

    public static function attempt(array $credentials): bool
    {
        $user = Database::queryOne(
            "SELECT * FROM users WHERE email = ? LIMIT 1",
            [$credentials['email'] ?? '']
        );

        if (!$user) {
            return false;
        }

        if (password_verify($credentials['password'] ?? '', $user['password'] ?? '')) {
            static::login($user);
            return true;
        }

        return false;
    }

    public static function login(array $user): void
    {
        Session::set('auth_user_id', $user['id']);
        static::$user = $user;
        Session::regenerate();
    }

    public static function logout(): void
    {
        Session::remove('auth_user_id');
        static::$user = null;
        Session::regenerate();
    }

    public static function user(): ?array
    {
        if (static::$user !== null) {
            return static::$user;
        }

        $userId = Session::get('auth_user_id');
        if (!$userId) {
            return null;
        }

        static::$user = Database::queryOne(
            "SELECT * FROM users WHERE id = ? LIMIT 1",
            [$userId]
        );

        return static::$user;
    }

    public static function id(): ?int
    {
        $user = static::user();
        return $user ? (int) $user['id'] : null;
    }

    public static function check(): bool
    {
        return static::user() !== null;
    }

    public static function guest(): bool
    {
        return !static::check();
    }

    public static function createUser(array $data): int
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return Database::insert('users', $data);
    }
}
