<?php

namespace App\Models;

use Core\Database;

class Comment
{
    protected string $table = 'comments';

    public static function findByContent(string $type, int $id, int $limit = 50): array
    {
        return Database::query(
            "SELECT c.*, u.username, u.name FROM comments c INNER JOIN users u ON c.user_id = u.id WHERE c.commentable_type = ? AND c.commentable_id = ? ORDER BY c.created_at DESC LIMIT ?",
            [$type, $id, $limit]
        );
    }

    public static function create(array $data): int
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return Database::insert('comments', $data);
    }

    public static function delete(int $id): int
    {
        return Database::delete('comments', 'id = ?', [$id]);
    }
}
