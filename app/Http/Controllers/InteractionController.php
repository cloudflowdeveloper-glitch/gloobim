<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\Response;
use Core\Database;
use Core\Auth;

/**
 * Handles user interaction actions: follow / unfollow / bookmark.
 * These are called via AJAX from the home page and other views.
 */
class InteractionController extends Controller
{
    /* ==================================================================
     *  FOLLOW / UNFOLLOW
     * ================================================================== */

    /**
     * Toggle follow – if already following, unfollow; otherwise follow.
     * POST /follow/{id}
     */
    public function toggleFollow(int $id): Response
    {
        $me = Auth::user();
        if (!$me) {
            return $this->json(['error' => 'Login required'], 401);
        }

        $myId  = (int) $me['id'];
        $targetId = $id;

        if ($myId === $targetId) {
            return $this->json(['error' => 'Cannot follow yourself'], 422);
        }

        // Verify target user exists
        $target = Database::queryOne(
            "SELECT id, name, username FROM users WHERE id = ? AND is_banned = 0",
            [$targetId]
        );
        if (!$target) {
            return $this->json(['error' => 'User not found'], 404);
        }

        try {
            // Check if already following
            $existing = Database::queryOne(
                "SELECT id FROM followers WHERE follower_id = ? AND following_id = ?",
                [$myId, $targetId]
            );

            if ($existing) {
                // UNFOLLOW
                Database::delete('followers', 'id = ?', [$existing['id']]);
                $following = false;
                $message = 'Unfollowed @' . $target['username'];
            } else {
                // FOLLOW
                Database::insert('followers', [
                    'follower_id'  => $myId,
                    'following_id' => $targetId,
                    'created_at'   => date('Y-m-d H:i:s'),
                ]);
                $following = true;
                $message = 'Following @' . $target['username'];

                // Create notification for the target user
                $this->createNotification(
                    $targetId,
                    'follow',
                    'New Follower',
                    $me['name'] . ' started following you.',
                    ['follower_id' => $myId, 'follower_name' => $me['name']]
                );
            }

            // Get updated follower count
            $countRow = Database::queryOne(
                "SELECT COUNT(*) AS cnt FROM followers WHERE following_id = ?",
                [$targetId]
            );
            $followerCount = (int) ($countRow['cnt'] ?? 0);

            return $this->json([
                'following'      => $following,
                'follower_count' => $followerCount,
                'message'        => $message,
            ]);

        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Check if current user follows a target user.
     * GET /follow/check/{id}
     */
    public function checkFollow(int $id): Response
    {
        $me = Auth::user();
        if (!$me) {
            return $this->json(['following' => false]);
        }

        $existing = Database::queryOne(
            "SELECT id FROM followers WHERE follower_id = ? AND following_id = ?",
            [(int) $me['id'], $id]
        );

        return $this->json(['following' => (bool) $existing]);
    }

    /* ==================================================================
     *  BOOKMARK / UNBOOKMARK
     * ================================================================== */

    /**
     * Toggle bookmark on a post, reel, or video.
     * POST /bookmark/{type}/{id}   e.g. /bookmark/post/5
     */
    public function toggleBookmark(string $type, int $id): Response
    {
        $me = Auth::user();
        if (!$me) {
            return $this->json(['error' => 'Login required'], 401);
        }

        $allowedTypes = ['post', 'reel', 'video'];
        if (!in_array($type, $allowedTypes)) {
            return $this->json(['error' => 'Invalid type. Use post, reel, or video.'], 422);
        }

        try {
            $existing = Database::queryOne(
                "SELECT id FROM bookmarks
                 WHERE user_id = ? AND bookmarkable_type = ? AND bookmarkable_id = ?",
                [(int) $me['id'], $type, $id]
            );

            if ($existing) {
                // UNBOOKMARK
                Database::delete('bookmarks', 'id = ?', [$existing['id']]);
                $bookmarked = false;
                $message = 'Removed from saved';
            } else {
                // BOOKMARK
                Database::insert('bookmarks', [
                    'user_id'          => (int) $me['id'],
                    'bookmarkable_type' => $type,
                    'bookmarkable_id'  => $id,
                    'created_at'       => date('Y-m-d H:i:s'),
                ]);
                $bookmarked = true;
                $message = 'Saved!';
            }

            return $this->json([
                'bookmarked' => $bookmarked,
                'message'    => $message,
            ]);

        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    /* ==================================================================
     *  TIP / GIFT (placeholder – wallet integration later)
     * ================================================================== */

    /**
     * Send a tip to a post/reel/video creator.
     * POST /tip/{type}/{id}
     */
    public function sendTip(string $type, int $id): Response
    {
        $me = Auth::user();
        if (!$me) {
            return $this->json(['error' => 'Login required'], 401);
        }

        $input  = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $amount = (float) ($input['amount'] ?? 0);

        if ($amount <= 0) {
            return $this->json(['error' => 'Invalid tip amount'], 422);
        }

        try {
            // Find the content's creator
            $table = match($type) {
                'post' => 'posts',
                'reel' => 'reels',
                'video' => 'videos',
                default => null,
            };

            if (!$table) {
                return $this->json(['error' => 'Invalid content type'], 422);
            }

            $content = Database::queryOne(
                "SELECT user_id FROM {$table} WHERE id = ?",
                [$id]
            );

            if (!$content) {
                return $this->json(['error' => 'Content not found'], 404);
            }

            $receiverId = (int) $content['user_id'];
            if ($receiverId === (int) $me['id']) {
                return $this->json(['error' => 'Cannot tip yourself'], 422);
            }

            // TODO: Deduct from tipper wallet, add to receiver wallet via wallet_transactions
            // For now just record the notification
            $this->createNotification(
                $receiverId,
                'tip',
                'You received a tip!',
                $me['name'] . ' sent you KES ' . number_format($amount, 0),
                [
                    'from_user_id' => (int) $me['id'],
                    'from_user_name' => $me['name'],
                    'amount' => $amount,
                    'type' => $type,
                    'content_id' => $id,
                ]
            );

            return $this->json([
                'message' => 'Tip of KES ' . number_format($amount, 0) . ' sent!',
                'amount'  => $amount,
            ]);

        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    /* ==================================================================
     *  HELPER
     * ================================================================== */

    protected function createNotification(
        int    $userId,
        string $type,
        string $title,
        string $body,
        array  $data = []
    ): void {
        try {
            Database::insert('notifications', [
                'user_id'    => $userId,
                'type'       => $type,
                'title'      => $title,
                'body'       => $body,
                'data'       => json_encode($data),
                'is_read'    => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            // Notification creation is non-critical – don't throw
        }
    }
}