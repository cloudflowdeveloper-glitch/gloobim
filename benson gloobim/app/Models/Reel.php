<?php

namespace App\Models;

use Core\Database;

class Reel
{
    protected string $table = 'reels';

    public static function find(int $id): ?array
    {
        return Database::queryOne("SELECT * FROM reels WHERE id = ? LIMIT 1", [$id]);
    }

    public static function trending(int $limit = 20): array
    {
        return Database::query(
            "SELECT r.*, u.username, u.name as creator_name FROM reels r INNER JOIN users u ON r.user_id = u.id WHERE r.status = 'published' ORDER BY r.views DESC LIMIT ?",
            [$limit]
        );
    }

    public static function byUser(int $userId, int $limit = 20): array
    {
        return Database::query(
            "SELECT * FROM reels WHERE user_id = ? AND status = 'published' ORDER BY created_at DESC LIMIT ?",
            [$userId, $limit]
        );
    }

    public static function create(array $data): int
    {
        return Database::insert('reels', $data);
    }

    public static function incrementViews(int $id): void
    {
        Database::execute("UPDATE reels SET views = views + 1 WHERE id = ?", [$id]);
    }
}
