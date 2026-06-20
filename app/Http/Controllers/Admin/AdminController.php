<?php

namespace App\Http\Controllers\Admin;

use Core\Controller;
use Core\Response;
use Core\Database;
use Core\Auth;

class AdminController extends Controller
{
    /* ==================================================================
     *  DASHBOARD – Platform overview stats
     * ================================================================== */

    public function dashboard(): Response
    {
        $stats = $this->getPlatformStats();
        $recentUsers = $this->getRecentUsers(5);
        $recentReports = $this->getRecentReports(5);
        $recentContent = $this->getRecentContent(5);

        return $this->view('admin.dashboard', [
            'stats'          => $stats,
            'recentUsers'    => $recentUsers,
            'recentReports'  => $recentReports,
            'recentContent'  => $recentContent,
        ]);
    }

    /* ==================================================================
     *  USERS – List, ban, unban, search
     * ================================================================== */

    public function users(): Response
    {
        $search = $_GET['q'] ?? '';
        $role   = $_GET['role'] ?? '';
        $status = $_GET['status'] ?? '';
        $page   = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 20;

        $where = "WHERE 1=1";
        $params = [];

        if ($search) {
            $where .= " AND (u.name LIKE ? OR u.username LIKE ? OR u.email LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        if ($role) {
            $where .= " AND u.role = ?";
            $params[] = $role;
        }
        if ($status === 'banned') {
            $where .= " AND u.is_banned = 1";
        } elseif ($status === 'active') {
            $where .= " AND u.is_banned = 0";
        }

        $total = Database::queryOne(
            "SELECT COUNT(*) AS cnt FROM users u {$where}",
            $params
        )['cnt'] ?? 0;

        $offset = ($page - 1) * $perPage;

        $users = Database::query(
            "SELECT
                u.id, u.name, u.username, u.email, u.avatar, u.role,
                u.is_verified, u.is_banned, u.created_at, u.last_login_at,
                u.profile_type,
                (SELECT COUNT(*) FROM followers f WHERE f.following_id = u.id) AS follower_count,
                (SELECT COUNT(*) FROM posts p WHERE p.user_id = u.id AND p.status = 'published') AS post_count,
                (SELECT COUNT(*) FROM reels r WHERE r.user_id = u.id AND r.status = 'published') AS reel_count
            FROM users u
            {$where}
            ORDER BY u.created_at DESC
            LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        return $this->view('admin.users', [
            'users'   => $users,
            'search'  => $search,
            'role'    => $role,
            'status'  => $status,
            'page'    => $page,
            'perPage' => $perPage,
            'total'   => (int) $total,
        ]);
    }

    public function toggleBan(int $id): Response
    {
        $user = Database::queryOne("SELECT id, name, is_banned FROM users WHERE id = ?", [$id]);
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        $newBanned = $user['is_banned'] ? 0 : 1;
        Database::update('users', ['is_banned' => $newBanned], 'id = ?', [$id]);

        return $this->json([
            'success'  => true,
            'is_banned' => $newBanned,
            'message'  => $newBanned ? $user['name'] . ' has been banned' : $user['name'] . ' has been unbanned',
        ]);
    }

    public function toggleVerify(int $id): Response
    {
        $user = Database::queryOne("SELECT id, name, is_verified FROM users WHERE id = ?", [$id]);
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        $newVerified = $user['is_verified'] ? 0 : 1;
        Database::update('users', ['is_verified' => $newVerified], 'id = ?', [$id]);

        return $this->json([
            'success'     => true,
            'is_verified' => $newVerified,
            'message'     => $newVerified ? $user['name'] . ' is now verified' : $user['name'] . ' verification removed',
        ]);
    }

    public function changeRole(int $id): Response
    {
        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $role = $input['role'] ?? '';

        $allowed = ['user', 'creator', 'admin'];
        if (!in_array($role, $allowed)) {
            return $this->json(['error' => 'Invalid role'], 422);
        }

        Database::update('users', ['role' => $role], 'id = ?', [$id]);

        return $this->json([
            'success' => true,
            'role'    => $role,
            'message' => 'Role updated to ' . $role,
        ]);
    }

    /* ==================================================================
     *  CONTENT MODERATION – Posts, Reels, Videos
     * ================================================================== */

    public function content(): Response
    {
        $type = $_GET['type'] ?? 'posts';
        $status = $_GET['status'] ?? '';
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 20;

        $allowedTypes = ['posts', 'reels', 'videos'];
        if (!in_array($type, $allowedTypes)) {
            $type = 'posts';
        }

        $table = $type;
        $where = "WHERE 1=1";
        $params = [];

        if ($status && in_array($status, ['published', 'pending', 'flagged', 'deleted'])) {
            $where .= " AND c.status = ?";
            $params[] = $status;
        }

        $total = Database::queryOne(
            "SELECT COUNT(*) AS cnt FROM {$table} c {$where}",
            $params
        )['cnt'] ?? 0;

        $offset = ($page - 1) * $perPage;

        $rows = Database::query(
            "SELECT
                c.id, c.user_id, c.title, c.content, c.status, c.created_at,
                c.views, c.likes, c.comments_count, c.shares,
                c.thumbnail, c.image_url, c.video_url,
                u.username, u.name AS creator_name, u.avatar AS creator_avatar
            FROM {$table} c
            INNER JOIN users u ON c.user_id = u.id
            {$where}
            ORDER BY c.created_at DESC
            LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        return $this->view('admin.content', [
            'items'   => $rows,
            'type'    => $type,
            'status'  => $status,
            'page'    => $page,
            'perPage' => $perPage,
            'total'   => (int) $total,
        ]);
    }

    public function deleteContent(string $type, int $id): Response
    {
        $allowed = ['posts', 'reels', 'videos'];
        if (!in_array($type, $allowed)) {
            return $this->json(['error' => 'Invalid content type'], 422);
        }

        if ($type === 'posts') {
            Database::update($type, ['status' => 'deleted'], 'id = ?', [$id]);
        } else {
            Database::update($type, ['status' => 'deleted'], 'id = ?', [$id]);
        }

        return $this->json(['success' => true, 'message' => ucfirst($type) . ' deleted']);
    }

    public function restoreContent(string $type, int $id): Response
    {
        $allowed = ['posts', 'reels', 'videos'];
        if (!in_array($type, $allowed)) {
            return $this->json(['error' => 'Invalid content type'], 422);
        }

        Database::update($type, ['status' => 'published'], 'id = ?', [$id]);

        return $this->json(['success' => true, 'message' => ucfirst($type) . ' restored']);
    }

    public function featureContent(string $type, int $id): Response
    {
        $allowed = ['reels', 'videos'];
        if (!in_array($type, $allowed)) {
            return $this->json(['error' => 'Only reels and videos can be featured'], 422);
        }

        $current = Database::queryOne(
            "SELECT is_featured FROM {$type} WHERE id = ?", [$id]
        );

        if (!$current) {
            return $this->json(['error' => 'Not found'], 404);
        }

        $newVal = $current['is_featured'] ? 0 : 1;
        Database::update($type, ['is_featured' => $newVal], 'id = ?', [$id]);

        return $this->json([
            'success'     => true,
            'is_featured' => $newVal,
            'message'     => $newVal ? 'Content featured' : 'Content unfeatured',
        ]);
    }

    /* ==================================================================
     *  REPORTS – View, resolve, dismiss
     * ================================================================== */

    public function reports(): Response
    {
        $status = $_GET['status'] ?? 'pending';
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 20;

        $allowed = ['pending', 'reviewed', 'resolved', 'dismissed', ''];
        if (!in_array($status, $allowed)) {
            $status = 'pending';
        }

        $where = "WHERE 1=1";
        $params = [];

        if ($status) {
            $where .= " AND r.status = ?";
            $params[] = $status;
        }

        $total = Database::queryOne(
            "SELECT COUNT(*) AS cnt FROM reports r {$where}",
            $params
        )['cnt'] ?? 0;

        $offset = ($page - 1) * $perPage;

        $reports = Database::query(
            "SELECT
                r.id, r.reporter_id, r.reportable_type, r.reportable_id,
                r.reason, r.description, r.status, r.created_at,
                u1.name AS reporter_name, u1.username AS reporter_username, u1.avatar AS reporter_avatar,
                u2.name AS target_name, u2.username AS target_username
            FROM reports r
            LEFT JOIN users u1 ON r.reporter_id = u1.id
            LEFT JOIN users u2 ON CASE
                WHEN r.reportable_type = 'user' THEN r.reportable_id
                ELSE (SELECT user_id FROM (
                    SELECT user_id FROM posts WHERE id = r.reportable_id
                    UNION SELECT user_id FROM reels WHERE id = r.reportable_id
                    UNION SELECT user_id FROM videos WHERE id = r.reportable_id
                ) AS content_owners LIMIT 1)
            END = u2.id
            {$where}
            ORDER BY r.created_at DESC
            LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        return $this->view('admin.reports', [
            'reports' => $reports,
            'status'  => $status,
            'page'    => $page,
            'perPage' => $perPage,
            'total'   => (int) $total,
        ]);
    }

    public function updateReport(int $id): Response
    {
        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $status = $input['status'] ?? '';

        $allowed = ['reviewed', 'resolved', 'dismissed'];
        if (!in_array($status, $allowed)) {
            return $this->json(['error' => 'Invalid status'], 422);
        }

        Database::update('reports', ['status' => $status], 'id = ?', [$id]);

        return $this->json([
            'success' => true,
            'status'  => $status,
            'message' => 'Report ' . $status,
        ]);
    }

    /* ==================================================================
     *  DATA HELPERS
     * ================================================================== */

    protected function getPlatformStats(): array
    {
        try {
            $stats = [];

            $stats['total_users'] = (int) (Database::queryOne("SELECT COUNT(*) AS cnt FROM users")['cnt'] ?? 0);
            $stats['active_users'] = (int) (Database::queryOne("SELECT COUNT(*) AS cnt FROM users WHERE last_login_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND is_banned = 0")['cnt'] ?? 0);
            $stats['banned_users'] = (int) (Database::queryOne("SELECT COUNT(*) AS cnt FROM users WHERE is_banned = 1")['cnt'] ?? 0);
            $stats['total_creators'] = (int) (Database::queryOne("SELECT COUNT(*) AS cnt FROM users WHERE role = 'creator' AND is_banned = 0")['cnt'] ?? 0);
            $stats['total_posts'] = (int) (Database::queryOne("SELECT COUNT(*) AS cnt FROM posts WHERE status = 'published'")['cnt'] ?? 0);
            $stats['total_reels'] = (int) (Database::queryOne("SELECT COUNT(*) AS cnt FROM reels WHERE status = 'published'")['cnt'] ?? 0);
            $stats['total_videos'] = (int) (Database::queryOne("SELECT COUNT(*) AS cnt FROM videos WHERE status = 'published'")['cnt'] ?? 0);
            $stats['total_comments'] = (int) (Database::queryOne("SELECT COUNT(*) AS cnt FROM comments")['cnt'] ?? 0);
            $stats['total_likes'] = (int) (Database::queryOne("SELECT COUNT(*) AS cnt FROM likes")['cnt'] ?? 0);
            $stats['pending_reports'] = (int) (Database::queryOne("SELECT COUNT(*) AS cnt FROM reports WHERE status = 'pending'")['cnt'] ?? 0);

            // Revenue
            $walletStats = Database::queryOne(
                "SELECT
                    COALESCE(SUM(balance), 0) AS total_wallet_balance,
                    COUNT(*) AS wallet_count
                FROM wallets"
            );
            $stats['total_wallet_balance'] = (float) ($walletStats['total_wallet_balance'] ?? 0);
            $stats['active_wallets'] = (int) ($walletStats['wallet_count'] ?? 0);

            // Marketplace
            $stats['total_listings'] = (int) (Database::queryOne("SELECT COUNT(*) AS cnt FROM marketplace_listings WHERE status = 'active'")['cnt'] ?? 0);
            $stats['total_orders'] = (int) (Database::queryOne("SELECT COUNT(*) AS cnt FROM orders")['cnt'] ?? 0);

            // Livestreams
            $liveNow = (int) (Database::queryOne("SELECT COUNT(*) AS cnt FROM livestreams WHERE status = 'live'")['cnt'] ?? 0);
            $stats['live_now'] = $liveNow;

            // Views
            $viewStats = Database::queryOne(
                "SELECT
                    COALESCE(SUM(views), 0) AS total_views
                FROM (
                    SELECT COALESCE(SUM(views), 0) AS views FROM reels
                    UNION ALL
                    SELECT COALESCE(SUM(views), 0) FROM videos
                ) AS v"
            );
            $stats['total_views'] = (int) ($viewStats['total_views'] ?? 0);

            return $stats;
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function getRecentUsers(int $limit): array
    {
        try {
            return Database::query(
                "SELECT id, name, username, avatar, role, is_verified, created_at
                 FROM users
                 ORDER BY created_at DESC
                 LIMIT {$limit}"
            );
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function getRecentReports(int $limit): array
    {
        try {
            return Database::query(
                "SELECT r.id, r.reportable_type, r.reason, r.status, r.created_at,
                        u.name AS reporter_name, u.username AS reporter_username
                 FROM reports r
                 LEFT JOIN users u ON r.reporter_id = u.id
                 ORDER BY r.created_at DESC
                 LIMIT {$limit}"
            );
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function getRecentContent(int $limit): array
    {
        try {
            $content = [];

            // Recent posts
            $posts = Database::query(
                "SELECT 'post' AS type, id, user_id, NULL AS title, content, 'published' AS status, created_at
                 FROM posts
                 WHERE status = 'published'
                 ORDER BY created_at DESC
                 LIMIT 3"
            );

            // Recent reels
            $reels = Database::query(
                "SELECT 'reel' AS type, id, user_id, title, NULL AS content, status, created_at
                 FROM reels
                 WHERE status = 'published'
                 ORDER BY created_at DESC
                 LIMIT 3"
            );

            // Recent videos
            $videos = Database::query(
                "SELECT 'video' AS type, id, user_id, title, NULL AS content, status, created_at
                 FROM videos
                 WHERE status = 'published'
                 ORDER BY created_at DESC
                 LIMIT 3"
            );

            return array_merge($posts, $reels, $videos);
        } catch (\Exception $e) {
            return [];
        }
    }
}