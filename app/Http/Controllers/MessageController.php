<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\Response;
use Core\Database;

class MessageController extends Controller
{
    public function index(): Response
    {
        $user = \Core\Auth::user();
        $conversations = [];

        if ($user) {
            try {
                $conversations = Database::query(
                    "SELECT c.*,
                            IF(c.user_one = ?, u2.name, u1.name) AS other_name,
                            IF(c.user_one = ?, u2.username, u1.username) AS other_username,
                            IF(c.user_one = ?, u2.avatar, u1.avatar) AS other_avatar,
                            IF(c.user_one = ?, u2.is_verified, u1.is_verified) AS other_verified,
                            m.body AS last_message,
                            m.created_at AS last_message_at,
                            m.sender_id AS last_sender_id,
                            (SELECT COUNT(*) FROM messages WHERE conversation_id = c.id AND sender_id != ? AND is_read = 0) AS unread_count
                     FROM conversations c
                     INNER JOIN users u1 ON c.user_one = u1.id
                     INNER JOIN users u2 ON c.user_two = u2.id
                     LEFT JOIN messages m ON c.id = m.conversation_id AND m.created_at = (
                         SELECT MAX(created_at) FROM messages WHERE conversation_id = c.id
                     )
                     WHERE c.user_one = ? OR c.user_two = ?
                     ORDER BY COALESCE(m.created_at, c.created_at) DESC
                     LIMIT 20",
                    [$user['id'], $user['id'], $user['id'], $user['id'], $user['id'], $user['id'], $user['id']]
                );
            } catch (\Exception $e) {
                $conversations = [];
            }
        }

        return $this->view('messages.index', [
            'conversations' => $conversations,
            'user' => $user,
        ]);
    }

    public function show($id): Response
    {
        $user = \Core\Auth::user();
        $messages = [];
        $conversation = null;

        if ($user) {
            try {
                $conversations = Database::query(
                    "SELECT c.*,
                            IF(c.user_one = ?, u2.name, u1.name) AS other_name,
                            IF(c.user_one = ?, u2.username, u1.username) AS other_username,
                            IF(c.user_one = ?, u2.avatar, u1.avatar) AS other_avatar,
                            IF(c.user_one = ?, u2.is_verified, u1.is_verified) AS other_verified
                     FROM conversations c
                     INNER JOIN users u1 ON c.user_one = u1.id
                     INNER JOIN users u2 ON c.user_two = u2.id
                     WHERE c.id = ? AND (c.user_one = ? OR c.user_two = ?)
                     LIMIT 1",
                    [$user['id'], $user['id'], $user['id'], $user['id'], $id, $user['id'], $user['id']]
                );
                $conversation = $conversations[0] ?? null;

                if ($conversation) {
                    $messages = Database::query(
                        "SELECT m.*, u.name AS sender_name, u.avatar AS sender_avatar
                         FROM messages m
                         INNER JOIN users u ON m.sender_id = u.id
                         WHERE m.conversation_id = ?
                         ORDER BY m.created_at ASC
                         LIMIT 50",
                        [$id]
                    );

                    Database::execute(
                        "UPDATE messages SET is_read = 1 WHERE conversation_id = ? AND sender_id != ?",
                        [$id, $user['id']]
                    );
                }
            } catch (\Exception $e) {
                $messages = [];
            }
        }

        return $this->view('messages.show', [
            'messages' => $messages,
            'conversation' => $conversation,
            'userId' => $user['id'] ?? 0,
        ]);
    }

    public function send($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) {
            return $this->json(['error' => 'Unauthenticated'], 401);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $body = trim($input['body'] ?? '');

        if (empty($body)) {
            return $this->json(['error' => 'Message body is required'], 422);
        }

        try {
            Database::insert('messages', [
                'conversation_id' => $id,
                'sender_id' => $user['id'],
                'body' => $body,
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            Database::execute(
                "UPDATE conversations SET last_message_at = NOW() WHERE id = ?",
                [$id]
            );

            return $this->json(['message' => 'Message sent', 'conversation_id' => $id]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function create(): Response
    {
        $user = \Core\Auth::user();
        if (!$user) {
            return $this->json(['error' => 'Unauthenticated'], 401);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $userId = (int)($input['user_id'] ?? 0);
        $username = trim($input['username'] ?? '');

        if ($username && !$userId) {
            $targetUser = Database::queryOne("SELECT id FROM users WHERE username = ? LIMIT 1", [$username]);
            if (!$targetUser) {
                return $this->json(['error' => 'User not found'], 404);
            }
            $userId = (int)$targetUser['id'];
        }

        if ($userId === (int)$user['id']) {
            return $this->json(['error' => 'Cannot message yourself'], 422);
        }

        if (!$userId) {
            return $this->json(['error' => 'User ID or username is required'], 422);
        }

        try {
            $existing = Database::query(
                "SELECT id FROM conversations WHERE (user_one = ? AND user_two = ?) OR (user_one = ? AND user_two = ?) LIMIT 1",
                [$user['id'], $userId, $userId, $user['id']]
            );

            if (!empty($existing)) {
                return $this->json(['conversation_id' => $existing[0]['id']]);
            }

            $conversationId = Database::insert('conversations', [
                'user_one' => $user['id'],
                'user_two' => $userId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return $this->json([
                'message' => 'Conversation created',
                'conversation_id' => $conversationId,
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createWithMessage(): Response
    {
        $user = \Core\Auth::user();
        if (!$user) {
            return $this->json(['error' => 'Unauthenticated'], 401);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $userId = (int)($input['user_id'] ?? 0);
        $body = trim($input['body'] ?? '');

        if ($userId === (int)$user['id']) {
            return $this->json(['error' => 'Cannot message yourself'], 422);
        }

        if (!$userId) {
            return $this->json(['error' => 'User ID is required'], 422);
        }

        if (empty($body)) {
            return $this->json(['error' => 'Message is required'], 422);
        }

        try {
            // Check if conversation exists
            $existing = Database::query(
                "SELECT id FROM conversations WHERE (user_one = ? AND user_two = ?) OR (user_one = ? AND user_two = ?) LIMIT 1",
                [$user['id'], $userId, $userId, $user['id']]
            );

            $conversationId;
            if (!empty($existing)) {
                $conversationId = (int)$existing[0]['id'];
            } else {
                $conversationId = Database::insert('conversations', [
                    'user_one' => $user['id'],
                    'user_two' => $userId,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            // Send the first message
            Database::insert('messages', [
                'conversation_id' => $conversationId,
                'sender_id' => $user['id'],
                'body' => $body,
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            Database::execute(
                "UPDATE conversations SET last_message_at = NOW() WHERE id = ?",
                [$conversationId]
            );

            return $this->json([
                'message' => 'Message sent',
                'conversation_id' => $conversationId,
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function groupRequests(): Response
    {
        // Groups feature coming soon - return empty for now
        return $this->json(['requests' => [], 'groups' => []]);
    }

    public function searchUsers(): Response
    {
        $user = \Core\Auth::user();
        if (!$user) {
            return $this->json(['error' => 'Unauthenticated'], 401);
        }

        $query = trim($_GET['q'] ?? '');

        try {
            if (strlen($query) < 1) {
                // Empty query → return people you follow first, then random users
                $users = Database::query(
                    "SELECT u.id, u.name, u.username, u.avatar, u.bio, u.is_verified, 1 AS is_following
                     FROM users u
                     INNER JOIN followers f ON f.following_id = u.id AND f.follower_id = ?
                     WHERE u.id != ?
                     ORDER BY u.name ASC
                     LIMIT 10",
                    [$user['id'], $user['id']]
                );
                if (empty($users)) {
                    $users = Database::query(
                        "SELECT u.id, u.name, u.username, u.avatar, u.bio, u.is_verified,
                               (SELECT COUNT(*) FROM followers WHERE follower_id = ? AND following_id = u.id) AS is_following
                         FROM users u
                         WHERE u.id != ? AND u.is_banned = 0
                         ORDER BY RAND()
                         LIMIT 8",
                        [$user['id'], $user['id']]
                    );
                }
            } else {
                $users = Database::query(
                    "SELECT u.id, u.name, u.username, u.avatar, u.bio, u.is_verified,
                           (SELECT COUNT(*) FROM followers WHERE follower_id = ? AND following_id = u.id) AS is_following
                     FROM users u
                     WHERE u.id != ?
                       AND (u.username LIKE ? OR u.name LIKE ?)
                     ORDER BY
                       CASE WHEN u.username LIKE ? THEN 0 ELSE 1 END,
                       CASE WHEN u.name LIKE ? THEN 0 ELSE 1 END,
                       u.name ASC
                     LIMIT 15",
                    [$user['id'], $user['id'], "%{$query}%", "%{$query}%", "{$query}%", "{$query}%"]
                );
            }
            return $this->json($users);
        } catch (\Exception $e) {
            return $this->json([]);
        }
    }

    public function poll($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) {
            return $this->json([], 200);
        }

        try {
            $messages = Database::query(
                "SELECT m.*, u.name AS sender_name, u.avatar AS sender_avatar
                 FROM messages m
                 INNER JOIN users u ON m.sender_id = u.id
                 WHERE m.conversation_id = ?
                 ORDER BY m.created_at ASC",
                [$id]
            );
            return $this->json($messages);
        } catch (\Exception $e) {
            return $this->json([]);
        }
    }
}
