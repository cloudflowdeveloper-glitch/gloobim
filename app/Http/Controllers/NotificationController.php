<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\Response;
use Core\Database;
use Core\Auth;

class NotificationController extends Controller
{
    // Index - same as HomeController::notifications() but using actor_id properly
    public function index(): Response
    {
        $user = Auth::user();
        $userId = $user ? (int) $user['id'] : 0;
        $notifications = [];

        if ($userId) {
            try {
                $notifications = Database::query(
                    "SELECT n.*, u.name AS actor_name, u.username AS actor_username, u.avatar AS actor_avatar
                     FROM notifications n
                     LEFT JOIN users u ON n.actor_id = u.id
                     WHERE n.user_id = ?
                     ORDER BY n.created_at DESC
                     LIMIT 50",
                    [$userId]
                );
            } catch (\Exception $e) {
                $notifications = [];
            }
        }

        return $this->view('notifications.index', ['notifications' => $notifications]);
    }

    // POST /notifications/mark-read/{id} - Mark single notification as read
    public function markRead($id): Response
    {
        $user = Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        Database::execute(
            "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?",
            [(int)$id, (int)$user['id']]
        );

        return $this->json(['message' => 'Marked as read']);
    }

    // POST /notifications/mark-all-read - Mark all as read
    public function markAllRead(): Response
    {
        $user = Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        Database::execute(
            "UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0",
            [(int)$user['id']]
        );

        return $this->json(['message' => 'All marked as read']);
    }

    // POST /notifications/delete/{id} - Delete a notification
    public function delete($id): Response
    {
        $user = Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        Database::delete('notifications', 'id = ? AND user_id = ?', [(int)$id, (int)$user['id']]);

        return $this->json(['message' => 'Deleted']);
    }

    // GET /notifications/unread-count - Get unread count (JSON)
    public function unreadCount(): Response
    {
        $user = Auth::user();
        $count = 0;
        if ($user) {
            try {
                $row = Database::queryOne(
                    "SELECT COUNT(*) AS cnt FROM notifications WHERE user_id = ? AND is_read = 0",
                    [(int)$user['id']]
                );
                $count = (int)($row['cnt'] ?? 0);
            } catch (\Exception $e) {}
        }
        return $this->json(['count' => $count]);
    }
}
