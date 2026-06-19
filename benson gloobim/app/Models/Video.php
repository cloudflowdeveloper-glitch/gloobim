<?php

namespace App\Models;

use Core\Database;

class Video
{
    protected string $table = 'videos';

    public static function find(int $id): ?array
    {
        return Database::queryOne("SELECT * FROM videos WHERE id = ? LIMIT 1", [$id]);
    }

    public static function trending(int $limit = 20): array
    {
        return Database::query(
            "SELECT v.*, u.username, u.name as creator_name FROM videos v INNER JOIN users u ON v.user_id = u.id WHERE v.status = 'published' ORDER BY v.views DESC LIMIT ?",
            [$limit]
        );
    }

    public static function byUser(int $userId, int $limit = 20): array
    {
        return Database::query(
            "SELECT * FROM videos WHERE user_id = ? AND status = 'published' ORDER BY created_at DESC LIMIT ?",
            [$userId, $limit]
        );
    }

    public static function create(array $data): int
    {
        return Database::insert('videos', $data);
    }

    public static function incrementViews(int $id): void
    {
        Database::execute("UPDATE videos SET views = views + 1 WHERE id = ?", [$id]);
    }
}
