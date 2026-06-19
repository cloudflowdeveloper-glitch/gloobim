<?php

namespace App\Models;

use Core\Database;

class Message
{
    protected string $table = 'messages';

    public static function conversations(int $userId): array
    {
        return Database::query(
            "SELECT c.*, u.username, u.name FROM conversations c INNER JOIN users u ON (CASE WHEN c.user_one = ? THEN c.user_two ELSE c.user_one END) = u.id WHERE c.user_one = ? OR c.user_two = ? ORDER BY c.updated_at DESC",
            [$userId, $userId, $userId]
        );
    }

    public static function findByConversation(int $conversationId, int $limit = 50): array
    {
        return Database::query(
            "SELECT m.*, u.username FROM messages m INNER JOIN users u ON m.sender_id = u.id WHERE m.conversation_id = ? ORDER BY m.created_at ASC LIMIT ?",
            [$conversationId, $limit]
        );
    }

    public static function send(int $conversationId, int $senderId, string $body): int
    {
        return Database::insert('messages', [
            'conversation_id' => $conversationId,
            'sender_id' => $senderId,
            'body' => $body,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
