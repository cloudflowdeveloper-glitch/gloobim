<?php

namespace App\Http\Controllers\Api;

use Core\Controller;
use Core\Response;
use Core\Database;

class LivestreamApiController extends Controller
{
    public function index(): Response
    {
        try {
            $streams = Database::query(
                "SELECT l.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified
                 FROM livestreams l INNER JOIN users u ON l.user_id = u.id
                 WHERE l.status = 'live'
                 ORDER BY l.viewers DESC LIMIT 30"
            );
            return $this->json(['livestreams' => $streams]);
        } catch (\Exception $e) {
            return $this->json(['livestreams' => [], 'error' => $e->getMessage()]);
        }
    }

    public function live(): Response
    {
        try {
            $streams = Database::query(
                "SELECT l.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified
                 FROM livestreams l INNER JOIN users u ON l.user_id = u.id
                 WHERE l.status = 'live'
                 ORDER BY l.viewers DESC LIMIT 30"
            );
            return $this->json(['livestreams' => $streams]);
        } catch (\Exception $e) {
            return $this->json(['livestreams' => []]);
        }
    }

    public function ended(): Response
    {
        try {
            $streams = Database::query(
                "SELECT l.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified
                 FROM livestreams l INNER JOIN users u ON l.user_id = u.id
                 WHERE l.status = 'ended'
                 ORDER BY l.ended_at DESC LIMIT 30"
            );
            return $this->json(['livestreams' => $streams]);
        } catch (\Exception $e) {
            return $this->json(['livestreams' => []]);
        }
    }

    public function scheduled(): Response
    {
        try {
            $streams = Database::query(
                "SELECT l.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified
                 FROM livestreams l INNER JOIN users u ON l.user_id = u.id
                 WHERE l.status = 'scheduled'
                 ORDER BY l.started_at ASC LIMIT 20"
            );
            return $this->json(['livestreams' => $streams]);
        } catch (\Exception $e) {
            return $this->json(['livestreams' => []]);
        }
    }

    public function featured(): Response
    {
        try {
            $streams = Database::query(
                "SELECT l.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified
                 FROM livestreams l INNER JOIN users u ON l.user_id = u.id
                 WHERE l.is_featured = 1 AND l.status = 'live'
                 ORDER BY l.viewers DESC LIMIT 10"
            );
            return $this->json(['livestreams' => $streams]);
        } catch (\Exception $e) {
            return $this->json(['livestreams' => []]);
        }
    }

    public function show($id): Response
    {
        try {
            $streams = Database::query(
                "SELECT l.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified
                 FROM livestreams l INNER JOIN users u ON l.user_id = u.id
                 WHERE l.id = ?",
                [$id]
            );
            if (empty($streams)) return $this->json(['error' => 'Stream not found'], 404);

            $viewerCount = Database::query(
                "SELECT COUNT(*) AS c FROM stream_viewers WHERE stream_id = ? AND is_banned = 0 AND last_heartbeat > DATE_SUB(NOW(), INTERVAL 30 SECOND)",
                [$id]
            );
            $streams[0]['active_viewers_count'] = (int)($viewerCount[0]['c'] ?? 0);

            return $this->json(['livestream' => $streams[0]]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function start(): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $title = trim($input['title'] ?? '');
        if (empty($title)) return $this->json(['error' => 'Title is required'], 422);

        $streamKey = 'live_' . bin2hex(random_bytes(16));

        try {
            $streamId = Database::insert('livestreams', [
                'user_id' => $user['id'],
                'title' => $title,
                'description' => trim($input['description'] ?? ''),
                'thumbnail' => $input['thumbnail'] ?? null,
                'category' => $input['category'] ?? null,
                'stream_key' => $streamKey,
                'stream_url' => 'rtmp://localhost:1935/live/' . $streamKey,
                'status' => 'live',
                'started_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return $this->json([
                'message' => 'Livestream started',
                'livestream' => ['id' => $streamId, 'stream_key' => $streamKey],
            ], 201);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createSchedule(): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $title = trim($input['title'] ?? '');
        $scheduledAt = $input['scheduled_at'] ?? '';
        if (empty($title)) return $this->json(['error' => 'Title is required'], 422);
        if (empty($scheduledAt)) return $this->json(['error' => 'scheduled_at is required'], 422);

        try {
            $streamId = Database::insert('livestreams', [
                'user_id' => $user['id'],
                'title' => $title,
                'description' => trim($input['description'] ?? ''),
                'thumbnail' => $input['thumbnail'] ?? null,
                'category' => $input['category'] ?? null,
                'status' => 'scheduled',
                'started_at' => $scheduledAt,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            return $this->json(['message' => 'Livestream scheduled', 'id' => $streamId], 201);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        try {
            $allowed = ['title', 'description', 'thumbnail', 'category', 'is_private', 'restricted_to'];
            $updates = [];
            foreach ($allowed as $f) {
                if (isset($input[$f])) $updates[$f] = $input[$f];
            }
            if (!empty($updates)) {
                $sets = []; $params = [];
                foreach ($updates as $k => $v) { $sets[] = "$k = ?"; $params[] = $v; }
                $params[] = $id; $params[] = $user['id'];
                Database::execute(
                    "UPDATE livestreams SET " . implode(', ', $sets) . " WHERE id = ? AND user_id = ?",
                    $params
                );
            }
            return $this->json(['message' => 'Stream updated']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);
        try {
            Database::execute(
                "UPDATE livestreams SET status = 'cancelled', ended_at = NOW() WHERE id = ? AND user_id = ?",
                [$id, $user['id']]
            );
            return $this->json(['message' => 'Stream cancelled']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function end($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);
        try {
            $s = Database::query("SELECT started_at FROM livestreams WHERE id = ? AND user_id = ?", [$id, $user['id']]);
            $duration = (!empty($s) && $s[0]['started_at']) ? time() - strtotime($s[0]['started_at']) : 0;
            Database::execute(
                "UPDATE livestreams SET status = 'ended', ended_at = NOW(), duration_seconds = ? WHERE id = ? AND user_id = ?",
                [$duration, $id, $user['id']]
            );
            return $this->json(['message' => 'Livestream ended', 'duration' => $duration]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function like($id): Response
    {
        try {
            Database::execute("UPDATE livestreams SET total_likes = COALESCE(total_likes, 0) + 1 WHERE id = ?", [$id]);
            $s = Database::query("SELECT total_likes FROM livestreams WHERE id = ?", [$id]);
            $user = \Core\Auth::user();
            if ($user) {
                Database::execute(
                    "INSERT IGNORE INTO likes (user_id, likeable_type, likeable_id, created_at) VALUES (?, 'livestream', ?, NOW())",
                    [$user['id'], $id]
                );
            }
            return $this->json(['message' => 'Liked', 'total_likes' => (int)($s[0]['total_likes'] ?? 0)]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function share($id): Response
    {
        try {
            Database::execute("UPDATE livestreams SET total_shares = COALESCE(total_shares, 0) + 1 WHERE id = ?", [$id]);
            $s = Database::query("SELECT total_shares FROM livestreams WHERE id = ?", [$id]);
            return $this->json(['message' => 'Shared', 'total_shares' => (int)($s[0]['total_shares'] ?? 0)]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function comment($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);
        $input = json_decode(file_get_contents('php://input'), true);
        $message = trim($input['message'] ?? '');
        if (empty($message)) return $this->json(['error' => 'Message is required'], 422);

        try {
            Database::execute(
                "INSERT INTO comments (user_id, commentable_type, commentable_id, body, created_at) VALUES (?, 'livestream', ?, ?, NOW())",
                [$user['id'], $id, $message]
            );
            Database::execute("UPDATE livestreams SET total_comments = COALESCE(total_comments, 0) + 1 WHERE id = ?", [$id]);
            return $this->json([
                'message' => 'Comment added',
                'user' => $user['name'],
                'username' => $user['username'],
                'avatar' => $user['avatar'] ?? null,
                'is_verified' => $user['is_verified'] ?? false,
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteComment($commentId): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);
        try {
            $c = Database::query(
                "SELECT c.id, c.commentable_id FROM comments c INNER JOIN livestreams l ON c.commentable_id = l.id AND c.commentable_type = 'livestream' WHERE c.id = ? AND (c.user_id = ? OR l.user_id = ?)",
                [$commentId, $user['id'], $user['id']]
            );
            if (!empty($c)) {
                Database::execute("DELETE FROM comments WHERE id = ?", [$commentId]);
                Database::execute("UPDATE livestreams SET total_comments = GREATEST(COALESCE(total_comments, 0) - 1, 0) WHERE id = ?", [$c[0]['commentable_id']]);
                return $this->json(['message' => 'Comment deleted']);
            }
            return $this->json(['error' => 'Not found'], 404);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getComments($id): Response
    {
        try {
            $comments = Database::query(
                "SELECT c.id, c.body, c.created_at, u.id AS user_id, u.name, u.username, u.avatar, u.is_verified
                 FROM comments c INNER JOIN users u ON c.user_id = u.id
                 WHERE c.commentable_type = 'livestream' AND c.commentable_id = ?
                 ORDER BY c.created_at DESC LIMIT 50",
                [$id]
            );
            return $this->json(['comments' => $comments]);
        } catch (\Exception $e) {
            return $this->json(['comments' => []]);
        }
    }

    public function sendGift($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        $input = json_decode(file_get_contents('php://input'), true);
        $giftId = (int)($input['gift_id'] ?? 0);
        $quantity = max(1, (int)($input['quantity'] ?? 1));

        try {
            $gifts = Database::query("SELECT id, name, icon, price_usd FROM stream_gifts WHERE id = ? AND is_active = 1", [$giftId]);
            if (empty($gifts)) return $this->json(['error' => 'Invalid gift'], 422);
            $gift = $gifts[0];
            $price = (float)$gift['price_usd'] * $quantity;

            $wallets = Database::query("SELECT id, balance FROM wallets WHERE user_id = ?", [$user['id']]);
            if (empty($wallets) || (float)$wallets[0]['balance'] < $price) {
                return $this->json(['error' => 'Insufficient balance'], 422);
            }

            Database::execute("UPDATE wallets SET balance = balance - ? WHERE user_id = ? AND balance >= ?", [$price, $user['id'], $price]);
            Database::execute("UPDATE livestreams SET total_gifts = COALESCE(total_gifts, 0) + ?, gift_earnings = COALESCE(gift_earnings, 0) + ? WHERE id = ?", [$quantity, $price, $id]);

            $stream = Database::query("SELECT user_id FROM livestreams WHERE id = ?", [$id]);
            if (!empty($stream)) {
                $earnings = $price * 0.9;
                Database::execute("UPDATE wallets SET balance = balance + ? WHERE user_id = ?", [$earnings, $stream[0]['user_id']]);
            }

            return $this->json(['message' => "Sent {$quantity}x {$gift['name']}", 'gift_id' => $giftId, 'quantity' => $quantity]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function search(): Response
    {
        $query = trim($_GET['q'] ?? '');
        $category = trim($_GET['category'] ?? '');
        $status = trim($_GET['status'] ?? 'live');

        try {
            $conditions = ["l.status != 'cancelled'"];
            $params = [];
            if ($query) {
                $conditions[] = "(l.title LIKE ? OR l.description LIKE ?)";
                $params[] = "%{$query}%";
                $params[] = "%{$query}%";
            }
            if ($category && $category !== 'all') {
                $conditions[] = "l.category = ?";
                $params[] = $category;
            }
            if (in_array($status, ['live', 'ended', 'scheduled'])) {
                $conditions[] = "l.status = ?";
                $params[] = $status;
            }

            $sql = "SELECT l.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified
                    FROM livestreams l INNER JOIN users u ON l.user_id = u.id WHERE " . implode(' AND ', $conditions) . " ORDER BY l.viewers DESC LIMIT 30";
            $streams = Database::query($sql, $params);
            return $this->json(['livestreams' => $streams]);
        } catch (\Exception $e) {
            return $this->json(['livestreams' => [], 'error' => $e->getMessage()]);
        }
    }

    public function save($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);
        try {
            Database::execute("INSERT IGNORE INTO saved_streams (user_id, stream_id, created_at) VALUES (?, ?, NOW())", [$user['id'], $id]);
            return $this->json(['message' => 'Stream saved']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function unsave($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);
        try {
            Database::execute("DELETE FROM saved_streams WHERE user_id = ? AND stream_id = ?", [$user['id'], $id]);
            return $this->json(['message' => 'Stream unsaved']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function report($id): Response
    {
        $user = \Core\Auth::user();
        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $reason = trim($input['reason'] ?? '');
        if (empty($reason)) return $this->json(['error' => 'Reason required'], 422);
        try {
            Database::insert('stream_reports', [
                'stream_id' => $id,
                'reporter_id' => $user['id'] ?? null,
                'reason' => $reason,
                'description' => trim($input['description'] ?? ''),
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            return $this->json(['message' => 'Stream reported']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function stats($id): Response
    {
        try {
            $s = Database::query("SELECT * FROM livestreams WHERE id = ?", [$id]);
            if (empty($s)) return $this->json(['error' => 'Not found'], 404);
            $vc = Database::query("SELECT COUNT(*) AS c FROM stream_viewers WHERE stream_id = ? AND is_banned = 0 AND last_heartbeat > DATE_SUB(NOW(), INTERVAL 30 SECOND)", [$id]);
            return $this->json(['stats' => [
                'current_viewers' => (int)$s[0]['viewers'],
                'peak_viewers' => (int)$s[0]['peak_viewers'],
                'active_viewers' => (int)($vc[0]['c'] ?? 0),
                'likes' => (int)$s[0]['total_likes'],
                'shares' => (int)$s[0]['total_shares'],
                'comments' => (int)$s[0]['total_comments'],
                'gifts' => (int)$s[0]['total_gifts'],
                'earnings' => (float)$s[0]['gift_earnings'],
                'duration' => (int)$s[0]['duration_seconds'],
                'status' => $s[0]['status'],
                'started_at' => $s[0]['started_at'],
                'ended_at' => $s[0]['ended_at'],
            ]]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getViewers($id): Response
    {
        try {
            $viewers = Database::query(
                "SELECT id, user_id, viewer_sid, username, avatar, is_moderator, is_muted, joined_at
                 FROM stream_viewers WHERE stream_id = ? AND is_banned = 0 AND last_heartbeat > DATE_SUB(NOW(), INTERVAL 30 SECOND)
                 ORDER BY joined_at ASC",
                [$id]
            );
            return $this->json(['viewers' => $viewers, 'count' => count($viewers)]);
        } catch (\Exception $e) {
            return $this->json(['viewers' => [], 'count' => 0]);
        }
    }

    public function join($id): Response
    {
        $user = \Core\Auth::user();
        try {
            Database::execute("UPDATE livestreams SET viewers = COALESCE(viewers, 0) + 1, peak_viewers = GREATEST(COALESCE(peak_viewers, 0), COALESCE(viewers, 0) + 1) WHERE id = ?", [$id]);
            $sid = 'v_' . bin2hex(random_bytes(8));
            Database::insert('stream_viewers', [
                'stream_id' => $id, 'user_id' => $user['id'] ?? null, 'viewer_sid' => $sid,
                'username' => $user['username'] ?? 'guest', 'avatar' => $user['avatar'] ?? null,
                'last_heartbeat' => date('Y-m-d H:i:s'), 'joined_at' => date('Y-m-d H:i:s'),
            ]);
            $s = Database::query("SELECT viewers, peak_viewers FROM livestreams WHERE id = ?", [$id]);
            return $this->json(['message' => 'Joined', 'viewer_sid' => $sid, 'viewers' => (int)($s[0]['viewers'] ?? 0)]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function leave($id): Response
    {
        $user = \Core\Auth::user();
        try {
            Database::execute("UPDATE livestreams SET viewers = GREATEST(COALESCE(viewers, 0) - 1, 0) WHERE id = ?", [$id]);
            if ($user) Database::execute("DELETE FROM stream_viewers WHERE stream_id = ? AND user_id = ?", [$id, $user['id']]);
            return $this->json(['message' => 'Left']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function heartbeat($id): Response
    {
        $user = \Core\Auth::user();
        if ($user) Database::execute("UPDATE stream_viewers SET last_heartbeat = NOW() WHERE stream_id = ? AND user_id = ?", [$id, $user['id']]);
        return $this->json(['message' => 'OK']);
    }

    public function muteViewer($id, $viewerId): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);
        $s = Database::query("SELECT user_id FROM livestreams WHERE id = ?", [$id]);
        if (empty($s) || (int)$s[0]['user_id'] !== (int)$user['id']) return $this->json(['error' => 'Not authorized'], 403);
        Database::execute("UPDATE stream_viewers SET is_muted = 1 WHERE id = ? AND stream_id = ?", [$viewerId, $id]);
        return $this->json(['message' => 'Muted']);
    }

    public function unmuteViewer($id, $viewerId): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);
        $s = Database::query("SELECT user_id FROM livestreams WHERE id = ?", [$id]);
        if (empty($s) || (int)$s[0]['user_id'] !== (int)$user['id']) return $this->json(['error' => 'Not authorized'], 403);
        Database::execute("UPDATE stream_viewers SET is_muted = 0 WHERE id = ? AND stream_id = ?", [$viewerId, $id]);
        return $this->json(['message' => 'Unmuted']);
    }

    public function banViewer($id, $viewerId): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);
        $s = Database::query("SELECT user_id FROM livestreams WHERE id = ?", [$id]);
        if (empty($s) || (int)$s[0]['user_id'] !== (int)$user['id']) return $this->json(['error' => 'Not authorized'], 403);
        Database::execute("UPDATE stream_viewers SET is_banned = 1 WHERE id = ? AND stream_id = ?", [$viewerId, $id]);
        Database::execute("UPDATE livestreams SET viewers = GREATEST(COALESCE(viewers, 0) - 1, 0) WHERE id = ?", [$id]);
        return $this->json(['message' => 'Banned']);
    }

    public function unbanViewer($id, $viewerId): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);
        $s = Database::query("SELECT user_id FROM livestreams WHERE id = ?", [$id]);
        if (empty($s) || (int)$s[0]['user_id'] !== (int)$user['id']) return $this->json(['error' => 'Not authorized'], 403);
        Database::execute("UPDATE stream_viewers SET is_banned = 0 WHERE id = ? AND stream_id = ?", [$viewerId, $id]);
        return $this->json(['message' => 'Unbanned']);
    }

    public function addCoHost($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);
        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $coHostId = (int)($input['co_host_id'] ?? 0);
        if (!$coHostId) return $this->json(['error' => 'co_host_id required'], 422);
        $s = Database::query("SELECT user_id FROM livestreams WHERE id = ?", [$id]);
        if (empty($s) || (int)$s[0]['user_id'] !== (int)$user['id']) return $this->json(['error' => 'Not authorized'], 403);
        Database::execute("UPDATE livestreams SET co_host_id = ? WHERE id = ?", [$coHostId, $id]);
        return $this->json(['message' => 'Co-host added']);
    }

    public function removeCoHost($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);
        $s = Database::query("SELECT user_id FROM livestreams WHERE id = ?", [$id]);
        if (empty($s) || (int)$s[0]['user_id'] !== (int)$user['id']) return $this->json(['error' => 'Not authorized'], 403);
        Database::execute("UPDATE livestreams SET co_host_id = NULL WHERE id = ?", [$id]);
        return $this->json(['message' => 'Co-host removed']);
    }

    public function raid($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);
        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $targetId = (int)($input['target_stream_id'] ?? 0);
        if (!$targetId) return $this->json(['error' => 'target_stream_id required'], 422);
        $s = Database::query("SELECT user_id FROM livestreams WHERE id = ? AND status = 'live'", [$id]);
        if (empty($s) || (int)$s[0]['user_id'] !== (int)$user['id']) return $this->json(['error' => 'Not authorized'], 403);
        $t = Database::query("SELECT id FROM livestreams WHERE id = ? AND status = 'live'", [$targetId]);
        if (empty($t)) return $this->json(['error' => 'Target not found or not live'], 404);
        Database::execute("UPDATE livestreams SET raid_target_id = ?, status = 'ended', ended_at = NOW() WHERE id = ?", [$targetId, $id]);
        return $this->json(['message' => 'Raiding stream #' . $targetId, 'target_id' => $targetId]);
    }

    public function pollSignals($id): Response
    {
        $since = (int)($_GET['since'] ?? 0);
        $sender = trim($_GET['sender'] ?? 'host');
        try {
            $signals = Database::query(
                "SELECT id, sender, viewer_sid, type, data, created_at FROM stream_signals WHERE stream_id = ? AND sender = ? AND id > ? ORDER BY id ASC",
                [$id, $sender, $since]
            );
            return $this->json(['signals' => $signals]);
        } catch (\Exception $e) {
            return $this->json(['signals' => []]);
        }
    }

    public function sendSignal($id): Response
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $type = $input['type'] ?? '';
        $data = $input['data'] ?? '';
        if (empty($type) || empty($data)) return $this->json(['error' => 'type and data required'], 422);

        try {
            Database::insert('stream_signals', [
                'stream_id' => $id,
                'sender' => $input['sender'] ?? 'viewer',
                'viewer_sid' => $input['viewer_sid'] ?? null,
                'type' => $type,
                'data' => is_string($data) ? $data : json_encode($data),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            return $this->json(['message' => 'Signal stored']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
}
