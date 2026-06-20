<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\Response;
use Core\Database;
use Core\Auth;

class ReelController extends Controller
{
    public function index(): Response
    {
        try {
            $me = Auth::user();
            $myId = $me ? (int) $me['id'] : 0;

            $reels = Database::query(
                "SELECT r.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified,
                        CASE WHEN ? > 0 AND EXISTS (
                            SELECT 1 FROM followers f WHERE f.follower_id = ? AND f.following_id = r.user_id
                        ) THEN 1 ELSE 0 END AS is_following
                 FROM reels r
                 INNER JOIN users u ON r.user_id = u.id
                 WHERE r.status = 'published'
                 ORDER BY r.created_at DESC",
                [$myId, $myId]
            );
            // Decode JSON fields
            foreach ($reels as &$reel) {
                if (!empty($reel['tags']) && is_string($reel['tags'])) {
                    $decoded = json_decode($reel['tags'], true);
                    if (is_array($decoded)) $reel['tags'] = $decoded;
                }
            }
            unset($reel);
        } catch (\Exception $e) {
            $reels = [];
        }

        return $this->view('reels.index', ['reels' => $reels, 'gifts' => $this->getGifts()]);
    }

    protected function getGifts(): array
    {
        try {
            return Database::query(
                "SELECT id, name, description, icon, image_url, price_usd, color_class, is_animated FROM stream_gifts WHERE is_active = 1 ORDER BY sort_order ASC"
            );
        } catch (\Exception $e) {
            return [];
        }
    }

    public function create(): Response
    {
        return $this->view('reels.create');
    }

    public function store(): Response
    {
        $user = \Core\Auth::user();
        if (!$user) {
            return $this->json(['error' => 'Unauthenticated'], 401);
        }

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $title = trim($input['title'] ?? '');

        if (empty($title)) {
            return $this->json(['error' => 'Title is required'], 422);
        }

        try {
            $reelId = Database::insert('reels', [
                'user_id' => $user['id'],
                'title' => $title,
                'description' => trim($input['description'] ?? ''),
                'thumbnail' => $input['thumbnail'] ?? null,
                'video_url' => $input['video_url'] ?? null,
                'duration' => (int)($input['duration'] ?? 0),
                'song_name' => $input['song_name'] ?? null,
                'category' => $input['category'] ?? null,
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return $this->json(['message' => 'Reel created successfully', 'reel_id' => $reelId], 201);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id): Response
    {
        $reel = null;
        $me = Auth::user();
        $myId = $me ? (int) $me['id'] : 0;

        try {
            $reels = Database::query(
                "SELECT r.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified,
                        CASE WHEN ? > 0 AND EXISTS (
                            SELECT 1 FROM followers f WHERE f.follower_id = ? AND f.following_id = r.user_id
                        ) THEN 1 ELSE 0 END AS is_following
                 FROM reels r
                 INNER JOIN users u ON r.user_id = u.id
                 WHERE r.id = ? AND r.status = 'published'
                 LIMIT 1",
                [$myId, $myId, $id]
            );
            $reel = $reels[0] ?? null;
        } catch (\Exception $e) {
            $reel = null;
        }

        if (!$reel) {
            return $this->redirect('/reels');
        }

        return $this->view('reels.show', [
            'reel' => $reel,
            'comments' => $this->fetchComments($id),
            'gifts' => $this->getGifts(),
        ]);
    }

    public function edit($id): Response
    {
        return $this->view('reels.edit', ['id' => $id]);
    }

    public function update($id): Response
    {
        return $this->json(['message' => 'Reel updated successfully']);
    }

    public function destroy($id): Response
    {
        try {
            Database::execute("UPDATE reels SET status = 'deleted' WHERE id = ?", [$id]);
            return $this->json(['message' => 'Reel deleted successfully']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function like($id): Response
    {
        try {
            Database::execute("UPDATE reels SET likes = COALESCE(likes, 0) + 1 WHERE id = ?", [$id]);
            $reel = Database::query("SELECT likes FROM reels WHERE id = ?", [$id]);
            return $this->json(['message' => 'Liked', 'likes' => $reel[0]['likes'] ?? 0]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function comment($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) {
            return $this->json(['error' => 'Unauthenticated'], 401);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $body = trim($input['body'] ?? '');

        if (empty($body)) {
            return $this->json(['error' => 'Comment body is required'], 422);
        }

        try {
            Database::insert('comments', [
                'user_id' => $user['id'],
                'commentable_type' => 'reel',
                'commentable_id' => $id,
                'body' => $body,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            Database::execute("UPDATE reels SET comments_count = COALESCE(comments_count, 0) + 1 WHERE id = ?", [$id]);

            return $this->json([
                'message' => 'Comment added',
                'user_name' => $user['name'],
                'user_avatar' => $user['avatar'] ?? null,
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function share($id): Response
    {
        try {
            Database::execute("UPDATE reels SET shares = COALESCE(shares, 0) + 1 WHERE id = ?", [$id]);
            return $this->json(['message' => 'Shared', 'reel_id' => $id]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function sendGift($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) {
            return $this->json(['error' => 'Login required'], 401);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $giftId = (int)($input['gift_id'] ?? 0);

        if ($giftId <= 0) {
            return $this->json(['error' => 'Invalid gift'], 422);
        }

        try {
            $gifts = Database::query("SELECT id, name, price_usd FROM stream_gifts WHERE id = ? AND is_active = 1", [$giftId]);
            if (empty($gifts)) {
                return $this->json(['error' => 'Gift not found'], 404);
            }
            $gift = $gifts[0];
            $priceUsd = (float)$gift['price_usd'];

            $wallets = Database::query("SELECT id, balance, currency FROM wallets WHERE user_id = ?", [$user['id']]);
            if (empty($wallets) || (float)$wallets[0]['balance'] < $priceUsd) {
                return $this->json(['error' => 'Insufficient balance'], 422);
            }
            $wallet = $wallets[0];

            Database::execute(
                "UPDATE wallets SET balance = balance - ? WHERE user_id = ? AND balance >= ?",
                [$priceUsd, $user['id'], $priceUsd]
            );

            Database::insert('wallet_transactions', [
                'wallet_id' => $wallet['id'],
                'type' => 'gift_sent',
                'amount' => -$priceUsd,
                'currency' => $wallet['currency'],
                'description' => 'Sent gift "' . $gift['name'] . '" on reel #' . $id,
                'status' => 'completed',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            return $this->json(['message' => 'Gift sent!', 'gift' => $gift['name']]);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Failed to send gift: ' . $e->getMessage()], 500);
        }
    }

    public function getComments($id): Response
    {
        $comments = $this->fetchComments($id);

        // For each top-level comment, fetch replies
        foreach ($comments as &$c) {
            $c['replies'] = $this->fetchReplies($c['id']);
        }
        unset($c);

        return $this->json($comments);
    }

    public function replyComment($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        $input = json_decode(file_get_contents('php://input'), true);
        $body = trim($input['body'] ?? '');
        $parentId = (int)($input['parent_id'] ?? 0);

        if (empty($body)) return $this->json(['error' => 'Reply is required'], 422);
        if ($parentId <= 0) return $this->json(['error' => 'Parent comment ID is required'], 422);

        try {
            Database::insert('comments', [
                'user_id' => $user['id'],
                'commentable_type' => 'reel',
                'commentable_id' => $id,
                'parent_id' => $parentId,
                'body' => $body,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            Database::execute("UPDATE reels SET comments_count = COALESCE(comments_count, 0) + 1 WHERE id = ?", [$id]);

            return $this->json([
                'message' => 'Reply added',
                'user_name' => $user['name'],
                'user_avatar' => $user['avatar'] ?? null,
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function likeComment($commentId): Response
    {
        try {
            Database::execute("UPDATE comments SET likes = COALESCE(likes, 0) + 1 WHERE id = ?", [$commentId]);
            $row = Database::queryOne("SELECT likes FROM comments WHERE id = ?", [$commentId]);
            return $this->json(['likes' => (int)($row['likes'] ?? 0)]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function repost($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        try {
            Database::execute("UPDATE reels SET shares = COALESCE(shares, 0) + 1 WHERE id = ?", [$id]);
            $row = Database::queryOne("SELECT shares FROM reels WHERE id = ?", [$id]);
            return $this->json(['message' => 'Reposted', 'shares' => (int)($row['shares'] ?? 0)]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    protected function fetchComments($reelId): array
    {
        try {
            return Database::query(
                "SELECT c.*, u.username, u.name AS commenter_name, u.avatar AS commenter_avatar
                 FROM comments c
                 INNER JOIN users u ON c.user_id = u.id
                 WHERE c.commentable_type = 'reel' AND c.commentable_id = ? AND (c.parent_id IS NULL OR c.parent_id = 0)
                 ORDER BY c.likes DESC, c.created_at DESC
                 LIMIT 30",
                [$reelId]
            );
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function fetchReplies($parentId): array
    {
        try {
            return Database::query(
                "SELECT c.*, u.username, u.name AS commenter_name, u.avatar AS commenter_avatar
                 FROM comments c
                 INNER JOIN users u ON c.user_id = u.id
                 WHERE c.parent_id = ?
                 ORDER BY c.created_at ASC
                 LIMIT 10",
                [$parentId]
            );
        } catch (\Exception $e) {
            return [];
        }
    }
}
