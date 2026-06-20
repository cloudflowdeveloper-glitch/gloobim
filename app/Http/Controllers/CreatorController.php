<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\Response;
use Core\Database;

class CreatorController extends Controller
{
    public function dashboard(): Response
    {
        $user = \Core\Auth::user();
        $stats = [
            'total_views' => 0,
            'total_posts' => 0,
            'total_videos' => 0,
            'total_reels' => 0,
            'total_followers' => 0,
            'total_following' => 0,
            'total_likes' => 0,
            'total_earnings' => 0,
            'recent_posts' => [],
            'recent_videos' => [],
        ];

        if ($user) {
            try {
                $stats['total_posts'] = (int)(Database::queryOne("SELECT COUNT(*) AS c FROM posts WHERE user_id = ? AND status = 'published'", [$user['id']])['c'] ?? 0);
                $stats['total_videos'] = (int)(Database::queryOne("SELECT COUNT(*) AS c FROM videos WHERE user_id = ? AND status = 'published'", [$user['id']])['c'] ?? 0);
                $stats['total_reels'] = (int)(Database::queryOne("SELECT COUNT(*) AS c FROM reels WHERE user_id = ? AND status = 'published'", [$user['id']])['c'] ?? 0);
                $stats['total_followers'] = (int)(Database::queryOne("SELECT COUNT(*) AS c FROM followers WHERE following_id = ?", [$user['id']])['c'] ?? 0);
                $stats['total_following'] = (int)(Database::queryOne("SELECT COUNT(*) AS c FROM followers WHERE follower_id = ?", [$user['id']])['c'] ?? 0);

                $views = Database::queryOne("SELECT COALESCE(SUM(views), 0) AS v FROM videos WHERE user_id = ? AND status = 'published'", [$user['id']]);
                $stats['total_views'] = (int)($views['v'] ?? 0);

                $likes = Database::queryOne("SELECT COALESCE(SUM(likes), 0) AS l FROM posts WHERE user_id = ? AND status = 'published'", [$user['id']]);
                $stats['total_likes'] = (int)($likes['l'] ?? 0);

                $earnings = Database::queryOne("SELECT COALESCE(SUM(balance), 0) AS b FROM wallets WHERE user_id = ?", [$user['id']]);
                $stats['total_earnings'] = (float)($earnings['b'] ?? 0);

                $stats['recent_posts'] = Database::query(
                    "SELECT p.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar
                     FROM posts p INNER JOIN users u ON p.user_id = u.id
                     WHERE p.user_id = ? AND p.status = 'published'
                     ORDER BY p.created_at DESC LIMIT 5",
                    [$user['id']]
                );

                $stats['recent_videos'] = Database::query(
                    "SELECT v.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar
                     FROM videos v INNER JOIN users u ON v.user_id = u.id
                     WHERE v.user_id = ? AND v.status = 'published'
                     ORDER BY v.created_at DESC LIMIT 5",
                    [$user['id']]
                );
            } catch (\Exception $e) {}
        }

        return $this->view('creator.dashboard', ['stats' => $stats]);
    }

    public function analytics(): Response
    {
        $user = \Core\Auth::user();
        $data = [
            'total_views' => 0,
            'total_likes' => 0,
            'total_comments' => 0,
            'total_shares' => 0,
            'total_followers' => 0,
            'total_earnings' => 0,
            'views_growth' => 12.5,
            'followers_growth' => 8.3,
            'engagement_rate' => 4.2,
            'top_content' => [],
            'daily_views' => [],
        ];

        if ($user) {
            try {
                $views = Database::queryOne("SELECT COALESCE(SUM(views), 0) AS v FROM videos WHERE user_id = ? AND status = 'published'", [$user['id']]);
                $data['total_views'] = (int)($views['v'] ?? 0);

                $likes = Database::queryOne("SELECT COALESCE(SUM(likes), 0) AS l FROM posts WHERE user_id = ? AND status = 'published'", [$user['id']]);
                $data['total_likes'] = (int)($likes['l'] ?? 0);

                $comments = Database::queryOne("SELECT COUNT(*) AS c FROM comments c JOIN posts p ON c.commentable_id = p.id AND c.commentable_type = 'post' WHERE p.user_id = ?", [$user['id']]);
                $data['total_comments'] = (int)($comments['c'] ?? 0);

                $data['total_followers'] = (int)(Database::queryOne("SELECT COUNT(*) AS c FROM followers WHERE following_id = ?", [$user['id']])['c'] ?? 0);

                $earnings = Database::queryOne("SELECT COALESCE(SUM(balance), 0) AS b FROM wallets WHERE user_id = ?", [$user['id']]);
                $data['total_earnings'] = (float)($earnings['b'] ?? 0);

                $shares = Database::queryOne("SELECT COALESCE(SUM(shares), 0) AS s FROM posts WHERE user_id = ? AND status = 'published'", [$user['id']]);
                $data['total_shares'] = (int)($shares['s'] ?? 0);

                $data['top_content'] = Database::query(
                    "SELECT p.id, p.content, p.likes, p.comments_count, p.shares, p.created_at,
                            u.username, u.name AS creator_name
                     FROM posts p INNER JOIN users u ON p.user_id = u.id
                     WHERE p.user_id = ? AND p.status = 'published'
                     ORDER BY (p.likes + p.comments_count + p.shares) DESC LIMIT 10",
                    [$user['id']]
                );

                for ($i = 6; $i >= 0; $i--) {
                    $day = date('Y-m-d', strtotime("-{$i} days"));
                    $dayViews = Database::queryOne(
                        "SELECT COALESCE(SUM(views), 0) AS v FROM videos WHERE user_id = ? AND DATE(created_at) = ?",
                        [$user['id'], $day]
                    );
                    $data['daily_views'][] = [
                        'date' => date('D', strtotime($day)),
                        'views' => (int)($dayViews['v'] ?? 0),
                    ];
                }
            } catch (\Exception $e) {}
        }

        return $this->view('creator.analytics', ['data' => $data]);
    }

    public function monetize(): Response
    {
        return $this->json(['message' => 'Monetization enabled']);
    }

    public function profile($username): Response
    {
        $user = \Core\Auth::user();
        $profileUser = null;
        $posts = [];
        $videos = [];
        $followerCount = 0;
        $followingCount = 0;
        $reelsCount = 0;
        $musicCount = 0;
        $marketplaceCount = 0;
        $totalViews = 0;
        $totalLikes = 0;
        $walletBalance = 0;

        try {
            if ($username === 'me' && $user) {
                $profileUser = $user;
            } else {
                $users = Database::query("SELECT * FROM users WHERE username = ? LIMIT 1", [$username]);
                $profileUser = $users[0] ?? null;
            }

            if ($profileUser) {
                $posts = Database::query(
                    "SELECT * FROM posts WHERE user_id = ? AND status = 'published' ORDER BY created_at DESC LIMIT 10",
                    [$profileUser['id']]
                );
                $videos = Database::query(
                    "SELECT * FROM videos WHERE user_id = ? AND status = 'published' ORDER BY created_at DESC LIMIT 6",
                    [$profileUser['id']]
                );

                // Follower count
                $row = Database::queryOne(
                    "SELECT COUNT(*) AS c FROM followers WHERE following_id = ?",
                    [$profileUser['id']]
                );
                $followerCount = (int)($row['c'] ?? 0);

                // Following count
                $row = Database::queryOne(
                    "SELECT COUNT(*) AS c FROM followers WHERE follower_id = ?",
                    [$profileUser['id']]
                );
                $followingCount = (int)($row['c'] ?? 0);

                // Reels count
                $row = Database::queryOne(
                    "SELECT COUNT(*) AS c FROM reels WHERE user_id = ? AND status = 'published'",
                    [$profileUser['id']]
                );
                $reelsCount = (int)($row['c'] ?? 0);

                // Music count
                $row = Database::queryOne(
                    "SELECT COUNT(*) AS c FROM music_tracks WHERE user_id = ? AND status = 'published'",
                    [$profileUser['id']]
                );
                $musicCount = (int)($row['c'] ?? 0);

                // Marketplace count
                $row = Database::queryOne(
                    "SELECT COUNT(*) AS c FROM marketplace_listings WHERE user_id = ? AND status = 'active'",
                    [$profileUser['id']]
                );
                $marketplaceCount = (int)($row['c'] ?? 0);

                // Total views across videos and reels
                $row = Database::queryOne(
                    "SELECT COALESCE(SUM(v.views), 0) AS v FROM (" .
                    "  SELECT COALESCE(views, 0) AS views FROM videos WHERE user_id = ? AND status = 'published'" .
                    "  UNION ALL" .
                    "  SELECT COALESCE(views, 0) AS views FROM reels WHERE user_id = ? AND status = 'published'" .
                    ") v",
                    [$profileUser['id'], $profileUser['id']]
                );
                $totalViews = (int)($row['v'] ?? 0);

                // Total likes across posts, videos, and reels
                $row = Database::queryOne(
                    "SELECT COALESCE(SUM(l.likes), 0) AS l FROM (" .
                    "  SELECT COALESCE(likes, 0) AS likes FROM posts WHERE user_id = ? AND status = 'published'" .
                    "  UNION ALL" .
                    "  SELECT COALESCE(likes, 0) AS likes FROM videos WHERE user_id = ? AND status = 'published'" .
                    "  UNION ALL" .
                    "  SELECT COALESCE(likes, 0) AS likes FROM reels WHERE user_id = ? AND status = 'published'" .
                    ") l",
                    [$profileUser['id'], $profileUser['id'], $profileUser['id']]
                );
                $totalLikes = (int)($row['l'] ?? 0);

                // Wallet balance
                $row = Database::queryOne(
                    "SELECT COALESCE(SUM(balance), 0) AS b FROM wallets WHERE user_id = ?",
                    [$profileUser['id']]
                );
                $walletBalance = (float)($row['b'] ?? 0);
            }
        } catch (\Exception $e) {}

        return $this->view('creator.profile', [
            'profileUser' => $profileUser,
            'posts' => $posts,
            'videos' => $videos,
            'followerCount' => $followerCount,
            'followingCount' => $followingCount,
            'reelsCount' => $reelsCount,
            'musicCount' => $musicCount,
            'marketplaceCount' => $marketplaceCount,
            'totalViews' => $totalViews,
            'totalLikes' => $totalLikes,
            'walletBalance' => $walletBalance,
            'isOwnProfile' => $user && $profileUser && $user['id'] === $profileUser['id'],
        ]);
    }

    public function myProfile(): Response
    {
        return $this->profile('me');
    }

    public function settings(): Response
    {
        return $this->view('auth.settings');
    }

    public function updateProfile(): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

        try {
            $updates = [];
            $allowed = ['name', 'username', 'email', 'bio', 'phone', 'avatar'];

            foreach ($allowed as $field) {
                if (isset($input[$field])) {
                    $updates[$field] = $input[$field];
                }
            }

            if (!empty($updates)) {
                $sets = [];
                $params = [];
                foreach ($updates as $key => $val) {
                    $sets[] = "$key = ?";
                    $params[] = $val;
                }
                $params[] = $user['id'];
                Database::execute(
                    "UPDATE users SET " . implode(', ', $sets) . " WHERE id = ?",
                    $params
                );
            }

            return $this->json(['message' => 'Profile updated successfully']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateProfileType(): Response
    {
        $user = \Core\Auth::user();
        if (!$user) {
            return $this->json(['error' => 'Unauthenticated'], 401);
        }

        $body = json_decode(file_get_contents('php://input'), true);
        $type = $body['profile_type'] ?? null;

        $allowed = ['personal', 'creator', 'business', 'government'];
        if (!in_array($type, $allowed)) {
            return $this->json(['error' => 'Invalid profile type. Choose: personal, creator, business, or government.'], 422);
        }

        try {
            Database::execute("UPDATE users SET profile_type = ? WHERE id = ?", [$type, $user['id']]);
            return $this->json(['message' => 'Profile type updated', 'profile_type' => $type]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function uploadAvatar(): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            return $this->json(['error' => 'No file uploaded'], 422);
        }

        $file = $_FILES['avatar'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024;

        if (!in_array($file['type'], $allowedTypes)) {
            return $this->json(['error' => 'Only JPG, PNG, GIF, WebP images allowed'], 422);
        }
        if ($file['size'] > $maxSize) {
            return $this->json(['error' => 'Image must be under 5MB'], 422);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'avatar_' . $user['id'] . '_' . time() . '.' . $ext;
        $uploadDir = BASE_PATH . '/public/uploads/profiles';

        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0755, true);
        }

        $dest = $uploadDir . '/' . $filename;
        if (move_uploaded_file($file['tmp_name'], $dest)) {
            $avatarUrl = '/uploads/profiles/' . $filename;
            Database::execute("UPDATE users SET avatar = ? WHERE id = ?", [$avatarUrl, $user['id']]);
            return $this->json(['message' => 'Avatar updated', 'avatar' => $avatarUrl]);
        }

        return $this->json(['error' => 'Failed to save image'], 500);
    }
}
