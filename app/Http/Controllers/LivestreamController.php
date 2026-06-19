<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\Response;
use Core\Database;

class LivestreamController extends Controller
{
    // ===== WEB PAGES =====

    public function index(): Response
    {
        return $this->view('livestream.index', [
            'liveStreams' => $this->getLiveStreams(),
            'scheduledStreams' => $this->getScheduledStreams(),
            'endedStreams' => $this->getEndedStreams(4),
            'featuredStreams' => $this->getFeaturedStreams(),
        ]);
    }

    public function show($id): Response
    {
        $stream = $this->getStreamById($id);
        if (!$stream) {
            return $this->redirect('/livestream');
        }

        $currentUser = \Core\Auth::user();
        $wallet = $currentUser ? $this->getWallet($currentUser['id']) : null;
        $currencyInfo = $currentUser ? $this->getUserCurrency($currentUser) : ['code' => 'USD', 'symbol' => '$', 'rate' => 1];
        $comments = $this->getComments($id);
        $gifts = $this->getGifts();
        $isSaved = false;
        $isFollowing = false;

        if ($currentUser) {
            try {
                $saved = Database::query("SELECT id FROM saved_streams WHERE user_id = ? AND stream_id = ?", [$currentUser['id'], $id]);
                $isSaved = !empty($saved);
                $follow = Database::query("SELECT id FROM followers WHERE follower_id = ? AND following_id = ?", [$currentUser['id'], $stream['user_id']]);
                $isFollowing = !empty($follow);
            } catch (\Exception $e) {}
        }

        $viewerCount = Database::query("SELECT COUNT(*) AS c FROM stream_viewers WHERE stream_id = ? AND is_banned = 0 AND last_heartbeat > DATE_SUB(NOW(), INTERVAL 30 SECOND)", [$id]);
        $activeViewers = $viewerCount[0]['c'] ?? 0;

        return $this->view('livestream.show', [
            'stream' => $stream,
            'wallet' => $wallet,
            'currencyInfo' => $currencyInfo,
            'comments' => $comments,
            'gifts' => $gifts,
            'isSaved' => $isSaved,
            'isFollowing' => $isFollowing,
            'activeViewers' => $activeViewers,
            'relatedStreams' => $this->getRelatedStreams($stream),
        ]);
    }

    public function start(): Response
    {
        return $this->view('livestream.start');
    }

    public function schedule(): Response
    {
        return $this->view('livestream.schedule');
    }

    public function my(): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->redirect('/login');

        $liveStreams = [];
        $scheduledStreams = [];
        $endedStreams = [];
        $savedStreams = [];

        try {
            $liveStreams = Database::query(
                "SELECT * FROM livestreams WHERE user_id = ? AND status = 'live' ORDER BY started_at DESC",
                [$user['id']]
            );
            $scheduledStreams = Database::query(
                "SELECT * FROM livestreams WHERE user_id = ? AND status = 'scheduled' ORDER BY started_at ASC",
                [$user['id']]
            );
            $endedStreams = Database::query(
                "SELECT * FROM livestreams WHERE user_id = ? AND status = 'ended' ORDER BY started_at DESC LIMIT 20",
                [$user['id']]
            );
            $savedStreams = Database::query(
                "SELECT ls.*, l.id AS stream_id, l.title, l.thumbnail, l.status, l.viewers, l.started_at,
                        u.name AS creator_name, u.username, u.avatar AS creator_avatar, u.is_verified
                 FROM saved_streams ls
                 INNER JOIN livestreams l ON ls.stream_id = l.id
                 INNER JOIN users u ON l.user_id = u.id
                 WHERE ls.user_id = ?
                 ORDER BY ls.created_at DESC LIMIT 10",
                [$user['id']]
            );
        } catch (\Exception $e) {}

        return $this->view('livestream.my', [
            'liveStreams' => $liveStreams,
            'scheduledStreams' => $scheduledStreams,
            'endedStreams' => $endedStreams,
            'savedStreams' => $savedStreams,
        ]);
    }

    public function ended(): Response
    {
        $category = $_GET['category'] ?? '';
        $search = trim($_GET['search'] ?? '');

        $streams = [];
        try {
            $sql = "SELECT l.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified
                    FROM livestreams l INNER JOIN users u ON l.user_id = u.id
                    WHERE l.status = 'ended'";
            $params = [];

            if ($category && $category !== 'all') {
                $sql .= " AND l.category = ?";
                $params[] = $category;
            }
            if ($search) {
                $sql .= " AND (l.title LIKE ? OR l.description LIKE ?)";
                $params[] = "%{$search}%";
                $params[] = "%{$search}%";
            }
            $sql .= " ORDER BY l.ended_at DESC LIMIT 30";
            $streams = Database::query($sql, $params);
        } catch (\Exception $e) {}

        return $this->view('livestream.ended', [
            'streams' => $streams,
            'activeCategory' => $category,
            'search' => $search,
        ]);
    }

    // ===== STREAM CRUD =====

    public function createStream(): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $title = trim($input['title'] ?? '');

        if (empty($title)) return $this->json(['error' => 'Title is required'], 422);

        $streamKey = 'live_' . bin2hex(random_bytes(16));
        $streamUrl = 'rtmp://localhost:1935/live/' . $streamKey;
        $thumbnail = $input['thumbnail'] ?? 'https://picsum.photos/id/' . (rand(30, 50)) . '/400/225';

        try {
            $streamId = Database::insert('livestreams', [
                'user_id' => $user['id'],
                'title' => $title,
                'description' => trim($input['description'] ?? ''),
                'thumbnail' => $thumbnail,
                'category' => $input['category'] ?? null,
                'stream_key' => $streamKey,
                'stream_url' => $streamUrl,
                'is_private' => !empty($input['is_private']) ? 1 : 0,
                'access_password' => $input['access_password'] ?? null,
                'restricted_to' => $input['restricted_to'] ?? 'everyone',
                'viewers' => 0,
                'peak_viewers' => 0,
                'total_likes' => 0,
                'total_shares' => 0,
                'total_comments' => 0,
                'total_gifts' => 0,
                'gift_earnings' => 0.00,
                'status' => 'live',
                'started_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return $this->json([
                'message' => 'Livestream started',
                'id' => $streamId,
                'title' => $title,
                'stream_key' => $streamKey,
                'stream_url' => $streamUrl,
                'redirect_url' => '/livestream/' . $streamId . '?auto=1',
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Failed to create stream: ' . $e->getMessage()], 500);
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
        if (empty($scheduledAt)) return $this->json(['error' => 'Schedule date/time is required'], 422);

        try {
            $streamId = Database::insert('livestreams', [
                'user_id' => $user['id'],
                'title' => $title,
                'description' => trim($input['description'] ?? ''),
                'thumbnail' => $input['thumbnail'] ?? null,
                'category' => $input['category'] ?? null,
                'is_private' => !empty($input['is_private']) ? 1 : 0,
                'restricted_to' => $input['restricted_to'] ?? 'everyone',
                'status' => 'scheduled',
                'started_at' => $scheduledAt,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return $this->json([
                'message' => 'Livestream scheduled!',
                'id' => $streamId,
                'redirect_url' => '/livestream/my',
            ], 201);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateStream($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

        try {
            $updates = [];
            $allowed = ['title', 'description', 'thumbnail', 'category', 'is_private', 'access_password', 'restricted_to'];

            foreach ($allowed as $field) {
                if (isset($input[$field])) {
                    if ($field === 'is_private') {
                        $updates[$field] = !empty($input[$field]) ? 1 : 0;
                    } else {
                        $updates[$field] = $input[$field];
                    }
                }
            }

            if (!empty($updates)) {
                $sets = [];
                $params = [];
                foreach ($updates as $key => $val) {
                    $sets[] = "$key = ?";
                    $params[] = $val;
                }
                $params[] = $id;
                $params[] = $user['id'];

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

    public function deleteStream($id): Response
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
            $stream = Database::query("SELECT started_at FROM livestreams WHERE id = ? AND user_id = ?", [$id, $user['id']]);
            $duration = 0;
            if (!empty($stream) && $stream[0]['started_at']) {
                $duration = time() - strtotime($stream[0]['started_at']);
            }

            Database::execute(
                "UPDATE livestreams SET status = 'ended', ended_at = NOW(), duration_seconds = ? WHERE id = ? AND user_id = ?",
                [$duration, $id, $user['id']]
            );
            return $this->json(['message' => 'Livestream ended', 'id' => $id, 'duration' => $duration]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function pauseStream($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        try {
            Database::execute(
                "UPDATE livestreams SET is_paused = 1 WHERE id = ? AND user_id = ?",
                [$id, $user['id']]
            );
            return $this->json(['message' => 'Stream paused']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function unpauseStream($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        try {
            Database::execute(
                "UPDATE livestreams SET is_paused = 0 WHERE id = ? AND user_id = ?",
                [$id, $user['id']]
            );
            return $this->json(['message' => 'Stream resumed']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getBannedViewers($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        try {
            $viewers = Database::query(
                "SELECT id, user_id, viewer_sid, username, avatar, joined_at
                 FROM stream_viewers WHERE stream_id = ? AND is_banned = 1
                 ORDER BY joined_at DESC LIMIT 20",
                [$id]
            );
            return $this->json(['viewers' => $viewers, 'count' => count($viewers)]);
        } catch (\Exception $e) {
            return $this->json(['viewers' => [], 'count' => 0]);
        }
    }

    public function setFeatured($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $featured = !empty($input['is_featured']) ? 1 : 0;

        try {
            Database::execute(
                "UPDATE livestreams SET is_featured = ? WHERE id = ? AND user_id = ?",
                [$featured, $id, $user['id']]
            );
            return $this->json(['message' => $featured ? 'Stream featured' : 'Stream unfeatured', 'is_featured' => $featured]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getViewerCount($id): Response
    {
        try {
            $count = Database::query(
                "SELECT COUNT(*) AS c FROM stream_viewers WHERE stream_id = ? AND is_banned = 0 AND last_heartbeat > DATE_SUB(NOW(), INTERVAL 30 SECOND)",
                [$id]
            );
            $stream = Database::query("SELECT viewers, peak_viewers FROM livestreams WHERE id = ?", [$id]);
            return $this->json([
                'count' => (int)($count[0]['c'] ?? 0),
                'total_viewers' => (int)($stream[0]['viewers'] ?? 0),
                'peak_viewers' => (int)($stream[0]['peak_viewers'] ?? 0),
            ]);
        } catch (\Exception $e) {
            return $this->json(['count' => 0, 'total_viewers' => 0, 'peak_viewers' => 0]);
        }
    }

    // ===== INTERACTION =====

    public function like($id): Response
    {
        $user = \Core\Auth::user();
        try {
            Database::execute(
                "UPDATE livestreams SET total_likes = COALESCE(total_likes, 0) + 1 WHERE id = ?",
                [$id]
            );
            $stream = Database::query("SELECT total_likes FROM livestreams WHERE id = ?", [$id]);

            if ($user) {
                Database::execute(
                    "INSERT IGNORE INTO likes (user_id, likeable_type, likeable_id, created_at) VALUES (?, 'livestream', ?, NOW())",
                    [$user['id'], $id]
                );
            }

            return $this->json(['message' => 'Liked', 'total_likes' => (int)($stream[0]['total_likes'] ?? 0)]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function share($id): Response
    {
        try {
            Database::execute(
                "UPDATE livestreams SET total_shares = COALESCE(total_shares, 0) + 1 WHERE id = ?",
                [$id]
            );
            $stream = Database::query("SELECT total_shares FROM livestreams WHERE id = ?", [$id]);
            return $this->json(['message' => 'Shared', 'total_shares' => (int)($stream[0]['total_shares'] ?? 0)]);
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
            Database::execute(
                "UPDATE livestreams SET total_comments = COALESCE(total_comments, 0) + 1 WHERE id = ?",
                [$id]
            );
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
            $comment = Database::query(
                "SELECT c.id, c.commentable_id FROM comments c
                 INNER JOIN livestreams l ON c.commentable_id = l.id AND c.commentable_type = 'livestream'
                 WHERE c.id = ? AND (c.user_id = ? OR l.user_id = ?)",
                [$commentId, $user['id'], $user['id']]
            );

            if (!empty($comment)) {
                Database::execute("DELETE FROM comments WHERE id = ?", [$commentId]);
                Database::execute(
                    "UPDATE livestreams SET total_comments = GREATEST(COALESCE(total_comments, 0) - 1, 0) WHERE id = ?",
                    [$comment[0]['commentable_id']]
                );
                return $this->json(['message' => 'Comment deleted']);
            }
            return $this->json(['error' => 'Comment not found'], 404);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getLiveComments($id): Response
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

    // ===== GIFTS & WALLET =====

    public function sendGift($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        $input = json_decode(file_get_contents('php://input'), true);
        $giftId = (int)($input['gift_id'] ?? 0);
        $quantity = max(1, (int)($input['quantity'] ?? 1));
        $currencyInfo = $this->getUserCurrency($user);

        try {
            $gifts = Database::query("SELECT id, name, icon, price_usd, color_class FROM stream_gifts WHERE id = ? AND is_active = 1", [$giftId]);
            if (empty($gifts)) return $this->json(['error' => 'Invalid gift'], 422);
            $gift = $gifts[0];

            $priceUsd = (float)$gift['price_usd'] * $quantity;
            $priceLocal = round($priceUsd * $currencyInfo['rate'], 2);

            $wallet = $this->getWallet($user['id']);
            if (!$wallet || (float)$wallet['balance'] < $priceLocal) {
                return $this->json(['error' => 'Insufficient balance. Need ' . $currencyInfo['symbol'] . ' ' . $priceLocal], 422);
            }

            Database::execute(
                "UPDATE wallets SET balance = balance - ? WHERE user_id = ? AND balance >= ?",
                [$priceLocal, $user['id'], $priceLocal]
            );
            Database::execute(
                "UPDATE livestreams SET total_gifts = COALESCE(total_gifts, 0) + ?, gift_earnings = COALESCE(gift_earnings, 0) + ? WHERE id = ?",
                [$quantity, $priceUsd, $id]
            );

            $walletRow = Database::query("SELECT id FROM wallets WHERE user_id = ?", [$user['id']]);
            if (!empty($walletRow)) {
                Database::insert('wallet_transactions', [
                    'wallet_id' => $walletRow[0]['id'],
                    'type' => 'gift_sent',
                    'amount' => -$priceLocal,
                    'fee' => 0.00,
                    'status' => 'completed',
                    'description' => "Sent {$quantity}x {$gift['name']} to stream #{$id}",
                    'recipient_id' => null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            $stream = Database::query("SELECT user_id FROM livestreams WHERE id = ?", [$id]);
            if (!empty($stream)) {
                $earningsLocal = round($priceUsd * $currencyInfo['rate'] * 0.9, 2);
                Database::execute(
                    "UPDATE wallets SET balance = balance + ? WHERE user_id = ?",
                    [$earningsLocal, $stream[0]['user_id']]
                );
            }

            return $this->json([
                'message' => "Sent {$quantity}x {$gift['name']}",
                'gift' => $gift['name'],
                'gift_id' => $giftId,
                'quantity' => $quantity,
                'price_usd' => $priceUsd,
                'price_local' => $priceLocal,
                'currency' => $currencyInfo['code'],
                'symbol' => $currencyInfo['symbol'],
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    // ===== SIGNALING (WebRTC) =====

    public function sendSignal($id): Response
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $sender = $input['sender'] ?? 'viewer';
        $viewerSid = $input['viewer_sid'] ?? '';
        $type = $input['type'] ?? '';
        $data = $input['data'] ?? '';

        if (empty($type) || empty($data)) return $this->json(['error' => 'type and data required'], 422);

        try {
            Database::insert('stream_signals', [
                'stream_id' => $id,
                'sender' => $sender,
                'viewer_sid' => $viewerSid ?: null,
                'type' => $type,
                'data' => is_string($data) ? $data : json_encode($data),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            return $this->json(['message' => 'Signal stored']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function pollSignals($id): Response
    {
        $viewerSid = trim($_GET['viewer_sid'] ?? '');
        $sender = trim($_GET['sender'] ?? 'viewer');
        $since = (int)($_GET['since'] ?? 0);

        try {
            $query = "SELECT id, sender, viewer_sid, type, data, created_at FROM stream_signals WHERE stream_id = ? AND sender = ? AND id > ? ORDER BY id ASC";
            $params = [$id, $sender, $since];

            $signals = Database::query($query, $params);
            return $this->json(['signals' => $signals]);
        } catch (\Exception $e) {
            return $this->json(['signals' => [], 'error' => $e->getMessage()]);
        }
    }

    // ===== VIEWERS =====

    public function joinStream($id): Response
    {
        $user = \Core\Auth::user();
        $viewerSid = $_GET['viewer_sid'] ?? $_POST['viewer_sid'] ?? ('v_' . bin2hex(random_bytes(8)));

        try {
            Database::execute(
                "UPDATE livestreams SET viewers = COALESCE(viewers, 0) + 1, peak_viewers = GREATEST(COALESCE(peak_viewers, 0), COALESCE(viewers, 0) + 1) WHERE id = ?",
                [$id]
            );

            $existing = Database::query(
                "SELECT id FROM stream_viewers WHERE stream_id = ? AND (user_id = ? OR viewer_sid = ?)",
                [$id, $user['id'] ?? 0, $viewerSid]
            );

            if (empty($existing)) {
                Database::insert('stream_viewers', [
                    'stream_id' => $id,
                    'user_id' => $user['id'] ?? null,
                    'viewer_sid' => $viewerSid,
                    'username' => $user['username'] ?? 'guest',
                    'avatar' => $user['avatar'] ?? null,
                    'last_heartbeat' => date('Y-m-d H:i:s'),
                    'joined_at' => date('Y-m-d H:i:s'),
                ]);
            } else {
                Database::execute(
                    "UPDATE stream_viewers SET last_heartbeat = NOW(), is_banned = 0 WHERE id = ?",
                    [$existing[0]['id']]
                );
            }

            $stream = Database::query("SELECT viewers, peak_viewers FROM livestreams WHERE id = ?", [$id]);
            return $this->json([
                'message' => 'Joined',
                'viewer_sid' => $viewerSid,
                'viewers' => (int)($stream[0]['viewers'] ?? 0),
                'peak_viewers' => (int)($stream[0]['peak_viewers'] ?? 0),
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function leaveStream($id): Response
    {
        $user = \Core\Auth::user();
        $viewerSid = $_GET['viewer_sid'] ?? '';

        try {
            Database::execute(
                "UPDATE livestreams SET viewers = GREATEST(COALESCE(viewers, 0) - 1, 0) WHERE id = ?",
                [$id]
            );

            if ($user) {
                Database::execute(
                    "DELETE FROM stream_viewers WHERE stream_id = ? AND user_id = ?",
                    [$id, $user['id']]
                );
            } elseif ($viewerSid) {
                Database::execute(
                    "DELETE FROM stream_viewers WHERE stream_id = ? AND viewer_sid = ?",
                    [$id, $viewerSid]
                );
            }

            return $this->json(['message' => 'Left']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function heartbeat($id): Response
    {
        $user = \Core\Auth::user();
        $viewerSid = $_GET['viewer_sid'] ?? '';

        try {
            if ($user) {
                Database::execute(
                    "UPDATE stream_viewers SET last_heartbeat = NOW() WHERE stream_id = ? AND user_id = ?",
                    [$id, $user['id']]
                );
            } elseif ($viewerSid) {
                Database::execute(
                    "UPDATE stream_viewers SET last_heartbeat = NOW() WHERE stream_id = ? AND viewer_sid = ?",
                    [$id, $viewerSid]
                );
            }
            return $this->json(['message' => 'OK']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getViewers($id): Response
    {
        try {
            $viewers = Database::query(
                "SELECT sv.id, sv.user_id, sv.viewer_sid, sv.username, sv.avatar, sv.is_moderator, sv.is_muted, sv.joined_at
                 FROM stream_viewers sv
                 WHERE sv.stream_id = ? AND sv.is_banned = 0 AND sv.last_heartbeat > DATE_SUB(NOW(), INTERVAL 30 SECOND)
                 ORDER BY sv.joined_at ASC",
                [$id]
            );

            $count = count($viewers);
            return $this->json([
                'viewers' => $viewers,
                'count' => $count,
            ]);
        } catch (\Exception $e) {
            return $this->json(['viewers' => [], 'count' => 0]);
        }
    }

    public function muteViewer($id, $viewerId): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        try {
            $stream = Database::query("SELECT user_id FROM livestreams WHERE id = ?", [$id]);
            if (empty($stream) || (int)$stream[0]['user_id'] !== (int)$user['id']) {
                return $this->json(['error' => 'Not authorized'], 403);
            }

            Database::execute(
                "UPDATE stream_viewers SET is_muted = 1 WHERE id = ? AND stream_id = ?",
                [$viewerId, $id]
            );
            return $this->json(['message' => 'Viewer muted']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function unmuteViewer($id, $viewerId): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        try {
            $stream = Database::query("SELECT user_id FROM livestreams WHERE id = ?", [$id]);
            if (empty($stream) || (int)$stream[0]['user_id'] !== (int)$user['id']) {
                return $this->json(['error' => 'Not authorized'], 403);
            }

            Database::execute(
                "UPDATE stream_viewers SET is_muted = 0 WHERE id = ? AND stream_id = ?",
                [$viewerId, $id]
            );
            return $this->json(['message' => 'Viewer unmuted']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function banViewer($id, $viewerId): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        try {
            $stream = Database::query("SELECT user_id FROM livestreams WHERE id = ?", [$id]);
            if (empty($stream) || (int)$stream[0]['user_id'] !== (int)$user['id']) {
                return $this->json(['error' => 'Not authorized'], 403);
            }

            Database::execute(
                "UPDATE stream_viewers SET is_banned = 1 WHERE id = ? AND stream_id = ?",
                [$viewerId, $id]
            );
            Database::execute(
                "UPDATE livestreams SET viewers = GREATEST(COALESCE(viewers, 0) - 1, 0) WHERE id = ?",
                [$id]
            );
            return $this->json(['message' => 'Viewer banned']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function unbanViewer($id, $viewerId): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        try {
            $stream = Database::query("SELECT user_id FROM livestreams WHERE id = ?", [$id]);
            if (empty($stream) || (int)$stream[0]['user_id'] !== (int)$user['id']) {
                return $this->json(['error' => 'Not authorized'], 403);
            }

            Database::execute(
                "UPDATE stream_viewers SET is_banned = 0 WHERE id = ? AND stream_id = ?",
                [$viewerId, $id]
            );
            return $this->json(['message' => 'Viewer unbanned']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    // ===== SAVE / FOLLOW =====

    public function saveStream($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        try {
            Database::execute(
                "INSERT IGNORE INTO saved_streams (user_id, stream_id, created_at) VALUES (?, ?, NOW())",
                [$user['id'], $id]
            );
            return $this->json(['message' => 'Stream saved']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function unsaveStream($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        try {
            Database::execute(
                "DELETE FROM saved_streams WHERE user_id = ? AND stream_id = ?",
                [$user['id'], $id]
            );
            return $this->json(['message' => 'Stream unsaved']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    // ===== REPORTS =====

    public function report($id): Response
    {
        $user = \Core\Auth::user();
        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $reason = trim($input['reason'] ?? '');

        if (empty($reason)) return $this->json(['error' => 'Reason is required'], 422);

        try {
            Database::insert('stream_reports', [
                'stream_id' => $id,
                'reporter_id' => $user['id'] ?? null,
                'reason' => $reason,
                'description' => trim($input['description'] ?? ''),
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            return $this->json(['message' => 'Stream reported. We will review it.']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    // ===== STATS =====

    public function getStreamStats($id): Response
    {
        try {
            $stream = Database::query("SELECT * FROM livestreams WHERE id = ?", [$id]);
            if (empty($stream)) return $this->json(['error' => 'Stream not found'], 404);

            $s = $stream[0];

            $viewersCount = Database::query(
                "SELECT COUNT(*) AS c FROM stream_viewers WHERE stream_id = ? AND is_banned = 0 AND last_heartbeat > DATE_SUB(NOW(), INTERVAL 30 SECOND)",
                [$id]
            );

            return $this->json([
                'stats' => [
                    'current_viewers' => (int)$s['viewers'],
                    'peak_viewers' => (int)$s['peak_viewers'],
                    'active_viewers_count' => (int)($viewersCount[0]['c'] ?? 0),
                    'total_likes' => (int)$s['total_likes'],
                    'total_shares' => (int)$s['total_shares'],
                    'total_comments' => (int)$s['total_comments'],
                    'total_gifts' => (int)$s['total_gifts'],
                    'gift_earnings' => (float)$s['gift_earnings'],
                    'duration_seconds' => (int)$s['duration_seconds'],
                    'status' => $s['status'],
                    'started_at' => $s['started_at'],
                    'ended_at' => $s['ended_at'],
                ],
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    // ===== SEARCH & DISCOVER =====

    public function search(): Response
    {
        $query = trim($_GET['q'] ?? '');
        $category = trim($_GET['category'] ?? '');
        $status = trim($_GET['status'] ?? 'live');

        if (empty($query) && empty($category)) {
            return $this->json(['streams' => []]);
        }

        try {
            $sql = "SELECT l.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified
                    FROM livestreams l INNER JOIN users u ON l.user_id = u.id WHERE";
            $conditions = [];
            $params = [];

            if ($query) {
                $conditions[] = "(l.title LIKE ? OR l.description LIKE ? OR u.name LIKE ? OR u.username LIKE ?)";
                $params[] = "%{$query}%";
                $params[] = "%{$query}%";
                $params[] = "%{$query}%";
                $params[] = "%{$query}%";
            }

            if ($category && $category !== 'all') {
                $conditions[] = "l.category = ?";
                $params[] = $category;
            }

            $validStatuses = ['live', 'scheduled', 'ended'];
            if ($status && in_array($status, $validStatuses)) {
                $conditions[] = "l.status = ?";
                $params[] = $status;
            } else {
                $conditions[] = "l.status != 'cancelled'";
            }

            $sql .= " " . implode(' AND ', $conditions);
            $sql .= " ORDER BY l.viewers DESC LIMIT 30";

            $streams = Database::query($sql, $params);
            return $this->json(['streams' => $streams]);
        } catch (\Exception $e) {
            return $this->json(['streams' => [], 'error' => $e->getMessage()]);
        }
    }

    // ===== USER SEARCH (for co-host, etc.) =====

    public function searchUsers(): Response
    {
        $query = trim($_GET['q'] ?? '');
        if (empty($query)) return $this->json([]);

        try {
            $users = Database::query(
                "SELECT id, name, username, avatar, is_verified FROM users
                 WHERE username LIKE ? OR name LIKE ?
                 LIMIT 10",
                ["{$query}%", "{$query}%"]
            );
            return $this->json($users);
        } catch (\Exception $e) {
            return $this->json([]);
        }
    }

    // ===== CO-HOST & RAID =====

    public function addCoHost($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $coHostId = (int)($input['co_host_id'] ?? 0);

        if (!$coHostId) return $this->json(['error' => 'co_host_id required'], 422);

        try {
            $stream = Database::query("SELECT user_id FROM livestreams WHERE id = ?", [$id]);
            if (empty($stream) || (int)$stream[0]['user_id'] !== (int)$user['id']) {
                return $this->json(['error' => 'Not authorized'], 403);
            }

            Database::execute(
                "UPDATE livestreams SET co_host_id = ? WHERE id = ?",
                [$coHostId, $id]
            );
            return $this->json(['message' => 'Co-host added']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function removeCoHost($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        try {
            $stream = Database::query("SELECT user_id FROM livestreams WHERE id = ?", [$id]);
            if (empty($stream) || (int)$stream[0]['user_id'] !== (int)$user['id']) {
                return $this->json(['error' => 'Not authorized'], 403);
            }

            Database::execute(
                "UPDATE livestreams SET co_host_id = NULL WHERE id = ?",
                [$id]
            );
            return $this->json(['message' => 'Co-host removed']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function raid($id): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $targetId = (int)($input['target_stream_id'] ?? 0);

        if (!$targetId) return $this->json(['error' => 'target_stream_id required'], 422);

        try {
            $stream = Database::query("SELECT user_id FROM livestreams WHERE id = ? AND status = 'live'", [$id]);
            if (empty($stream) || (int)$stream[0]['user_id'] !== (int)$user['id']) {
                return $this->json(['error' => 'Not authorized'], 403);
            }

            $target = Database::query("SELECT id FROM livestreams WHERE id = ? AND status = 'live'", [$targetId]);
            if (empty($target)) return $this->json(['error' => 'Target stream not found or not live'], 404);

            Database::execute(
                "UPDATE livestreams SET raid_target_id = ?, status = 'ended', ended_at = NOW() WHERE id = ?",
                [$targetId, $id]
            );
            return $this->json(['message' => 'Raiding stream #' . $targetId, 'target_id' => $targetId]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    // ===== PROTECTED HELPERS =====

    protected function getLiveStreams(): array
    {
        try {
            return Database::query(
                "SELECT l.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified
                 FROM livestreams l INNER JOIN users u ON l.user_id = u.id
                 WHERE l.status = 'live'
                 ORDER BY l.viewers DESC"
            );
        } catch (\Exception $e) { return []; }
    }

    protected function getScheduledStreams(): array
    {
        try {
            return Database::query(
                "SELECT l.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified
                 FROM livestreams l INNER JOIN users u ON l.user_id = u.id
                 WHERE l.status = 'scheduled'
                 ORDER BY l.started_at ASC LIMIT 10"
            );
        } catch (\Exception $e) { return []; }
    }

    protected function getEndedStreams(int $limit = 10): array
    {
        try {
            return Database::query(
                "SELECT l.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified
                 FROM livestreams l INNER JOIN users u ON l.user_id = u.id
                 WHERE l.status = 'ended'
                 ORDER BY l.ended_at DESC LIMIT ?",
                [$limit]
            );
        } catch (\Exception $e) { return []; }
    }

    protected function getFeaturedStreams(): array
    {
        try {
            return Database::query(
                "SELECT l.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified
                 FROM livestreams l INNER JOIN users u ON l.user_id = u.id
                 WHERE l.is_featured = 1 AND l.status = 'live'
                 ORDER BY l.viewers DESC LIMIT 6"
            );
        } catch (\Exception $e) { return []; }
    }

    protected function getStreamById($id): ?array
    {
        try {
            $streams = Database::query(
                "SELECT l.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified
                 FROM livestreams l INNER JOIN users u ON l.user_id = u.id
                 WHERE l.id = ?",
                [$id]
            );
            return $streams[0] ?? null;
        } catch (\Exception $e) { return null; }
    }

    protected function getRelatedStreams(array $stream): array
    {
        try {
            return Database::query(
                "SELECT l.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified
                 FROM livestreams l INNER JOIN users u ON l.user_id = u.id
                 WHERE l.status = 'live' AND l.id != ?
                 ORDER BY l.viewers DESC LIMIT 4",
                [$stream['id']]
            );
        } catch (\Exception $e) { return []; }
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

    protected function getWallet($userId): ?array
    {
        try {
            $wallets = Database::query("SELECT id, balance, currency FROM wallets WHERE user_id = ?", [$userId]);
            return $wallets[0] ?? null;
        } catch (\Exception $e) { return null; }
    }

    protected function getUserCurrency($user): array
    {
        $countryCode = $user['country_code'] ?? 'KE';
        try {
            $currencies = Database::query(
                "SELECT currency_code, currency_symbol, exchange_rate_usd FROM country_currencies WHERE country_code = ?",
                [$countryCode]
            );
            if (!empty($currencies)) {
                return [
                    'code' => $currencies[0]['currency_code'],
                    'symbol' => $currencies[0]['currency_symbol'],
                    'rate' => (float)$currencies[0]['exchange_rate_usd'],
                ];
            }
        } catch (\Exception $e) {}
        return ['code' => 'KES', 'symbol' => 'KES', 'rate' => 129.50];
    }

    protected function getComments($streamId): array
    {
        try {
            return Database::query(
                "SELECT c.id, c.body, c.created_at, u.id AS user_id, u.name, u.username, u.avatar, u.is_verified
                 FROM comments c INNER JOIN users u ON c.user_id = u.id
                 WHERE c.commentable_type = 'livestream' AND c.commentable_id = ?
                 ORDER BY c.created_at ASC LIMIT 50",
                [$streamId]
            );
        } catch (\Exception $e) { return []; }
    }

}
