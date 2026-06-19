<?php

namespace App\Models;

use Core\Database;

class User
{
    protected string $table = 'users';

    public static function find(int $id): ?array
    {
        return Database::queryOne("SELECT * FROM users WHERE id = ? LIMIT 1", [$id]);
    }

    public static function findByEmail(string $email): ?array
    {
        return Database::queryOne("SELECT * FROM users WHERE email = ? LIMIT 1", [$email]);
    }

    public static function findByUsername(string $username): ?array
    {
        return Database::queryOne("SELECT * FROM users WHERE username = ? LIMIT 1", [$username]);
    }

    public static function create(array $data): int
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['created_at'] = date('Y-m-d H:i:s');
        return Database::insert('users', $data);
    }

    public static function update(int $id, array $data): int
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return Database::update('users', $data, 'id = ?', [$id]);
    }

    public static function delete(int $id): int
    {
        return Database::delete('users', 'id = ?', [$id]);
    }

    public static function followers(int $userId): array
    {
        return Database::query(
            "SELECT u.* FROM users u INNER JOIN followers f ON u.id = f.follower_id WHERE f.following_id = ?",
            [$userId]
        );
    }

    public static function following(int $userId): array
    {
        return Database::query(
            "SELECT u.* FROM users u INNER JOIN followers f ON u.id = f.following_id WHERE f.follower_id = ?",
            [$userId]
        );
    }
}
