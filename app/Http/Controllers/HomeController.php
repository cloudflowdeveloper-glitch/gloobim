<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\Response;
use Core\Database;
use Core\Auth;
use App\Http\Controllers\StoryController;

class HomeController extends Controller
{
    /**
     * Homepage - renders home/index view with all section data.
     */
    public function index(): Response
    {
        try {
            Database::connect();
        } catch (\Exception $e) {
            // Database not available – render with safe defaults
            return $this->view('home.index', $this->getDefaultData());
        }

        return $this->view('home.index', [
            'categories'       => $this->getCategories(),
            'topCreators'      => $this->getTopCreators(),
            'storyGroups'      => $this->getStoryGroups(),
            'quickActions'     => $this->getQuickActions(),
            'featuredContent'  => $this->getFeaturedContent(),
            'discoverGrid'     => $this->getDiscoverGrid(),
            'spotlightAds'     => $this->getSpotlightAds(),
            'liveNow'          => $this->getLiveStreams(),
            'trendingReels'    => $this->getTrendingReels(),
            'posts'            => $this->getPosts(),
            'trendingVideos'   => $this->getTrendingVideos(),
            'currentUser'      => $this->getCurrentUser(),
            'unreadCount'      => $this->getUnreadNotificationCount(),
        ]);
    }

    /* ====================================================================
     *  DATA FETCHING METHODS – each queries the database and returns array
     * ==================================================================== */

    /**
     * Category chips shown below the search bar.
     * Pulls unique categories from reels + videos, then merges with defaults.
     */
    protected function getCategories(): array
    {
        $dbCategories = [];

        try {
            $rows = Database::query(
                "SELECT DISTINCT category, COUNT(*) AS total
                 FROM (
                     SELECT category FROM reels WHERE status = 'published' AND category IS NOT NULL
                     UNION ALL
                     SELECT category FROM videos WHERE status = 'published' AND category IS NOT NULL
                 ) AS all_content
                 GROUP BY category
                 ORDER BY total DESC"
            );

            $iconMap = [
                'Music'   => 'music_note',
                'Gaming'  => 'sports_esports',
                'Tech'    => 'computer',
                'Comedy'  => 'sentiment_very_satisfied',
                'Food'    => 'restaurant',
                'Sports'  => 'sports_soccer',
                'Fashion' => 'checkroom',
                'Dance'   => 'music_note',
                'Travel'  => 'flight',
                'Lifestyle' => 'style',
                'Beauty'  => 'spa',
                'Education' => 'school',
                'Business' => 'business_center',
            ];

            $colorMap = [
                'Music'   => '#ec4899',
                'Gaming'  => '#22c55e',
                'Tech'    => '#3b82f6',
                'Comedy'  => '#f59e0b',
                'Food'    => '#ef4444',
                'Sports'  => '#06b6d4',
                'Fashion' => '#a855f7',
                'Dance'   => '#ec4899',
                'Travel'  => '#06b6d4',
                'Lifestyle' => '#834ae5',
                'Beauty'  => '#f472b6',
                'Education' => '#3b82f6',
                'Business' => '#f59e0b',
            ];

            foreach ($rows as $row) {
                $cat = $row['category'];
                $dbCategories[] = [
                    'name'  => $cat,
                    'icon'  => $iconMap[$cat] ?? 'category',
                    'color' => $colorMap[$cat] ?? '#834ae5',
                    'count' => (int) $row['total'],
                ];
            }
        } catch (\Exception $e) {
            // fall through to defaults
        }

        // Always prepend "All"
        array_unshift($dbCategories, [
            'name'  => 'All',
            'icon'  => 'grid_view',
            'color' => '#834ae5',
            'count' => 0,
        ]);

        return $dbCategories;
    }

    /**
     * Top creators – used for Stories row AND Suggested Creators section.
     * Selects creators/admins with their follower count, ordered by popularity.
     * Includes is_following flag so the view can show the correct button state.
     */
    protected function getTopCreators(): array
    {
        try {
            $me = Auth::user();
            $myId = $me ? (int) $me['id'] : 0;

            return Database::query(
                "SELECT
                    u.id,
                    u.name,
                    u.username,
                    u.avatar,
                    u.bio,
                    u.is_verified,
                    u.role,
                    COUNT(DISTINCT f.id) AS follower_count,
                    COALESCE(SUM(r.views), 0) AS total_views,
                    CASE WHEN ? > 0 AND EXISTS (
                        SELECT 1 FROM followers f2
                        WHERE f2.follower_id = ? AND f2.following_id = u.id
                    ) THEN 1 ELSE 0 END AS is_following
                FROM users u
                LEFT JOIN followers f ON f.following_id = u.id
                LEFT JOIN reels r ON r.user_id = u.id AND r.status = 'published'
                WHERE u.role IN ('creator', 'admin')
                  AND u.is_banned = 0
                GROUP BY u.id
                ORDER BY follower_count DESC, total_views DESC
                LIMIT 10",
                [$myId, $myId]
            );
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Active story groups for the Stories row on the homepage.
     * Shows current user's stories first, then other users' stories.
     * Each group contains user info + an array of their active stories + has_unseen flag.
     */
    protected function getStoryGroups(): array
    {
        try {
            $user = Auth::user();
            $myId = $user ? (int) $user['id'] : 0;
            return StoryController::getActiveStories($myId);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Quick-action buttons (Market, Music, Live, Videos).
     * Static navigation config – not stored in DB.
     */
    protected function getQuickActions(): array
    {
        return [
            [
                'name'     => 'Market',
                'icon'     => 'storefront',
                'url'      => '/marketplace',
                'color'    => 'linear-gradient(135deg, #22c55e, #15803d)',
                'icon_url' => '/uploads/home/icon_5.jpg',
            ],
            [
                'name'     => 'Music',
                'icon'     => 'music_note',
                'url'      => '/music',
                'color'    => 'linear-gradient(135deg, #ec4899, #be185d)',
                'icon_url' => '/uploads/home/icon_2.jpg',
            ],
            [
                'name'     => 'Live',
                'icon'     => 'sensors',
                'url'      => '/livestream',
                'color'    => 'linear-gradient(135deg, #ef4444, #b91c1c)',
                'icon_url' => '/uploads/home/icon_3.jpg',
            ],
            [
                'name'     => 'Videos',
                'icon'     => 'play_circle',
                'url'      => '/videos',
                'color'    => 'linear-gradient(135deg, #3b82f6, #1d4ed8)',
                'icon_url' => '/uploads/home/icon_4.jpg',
            ],
        ];
    }

    /**
     * Featured hero banner – pulls real platform stats from the DB.
     */
    protected function getFeaturedContent(): array
    {
        $creatorsOnline = '0';
        $dailyViews     = '0';

        try {
            // Count creators who logged in today or have a live stream
            $row = Database::queryOne(
                "SELECT
                    (SELECT COUNT(DISTINCT user_id) FROM livestreams WHERE status = 'live') +
                    (SELECT COUNT(*) FROM users WHERE role = 'creator' AND is_banned = 0
                     AND last_login_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)) AS creators_online"
            );
            $onlineCount = (int) ($row['creators_online'] ?? 0);
            $creatorsOnline = $onlineCount >= 1000
                ? round($onlineCount / 1000, 1) . 'K+'
                : $onlineCount . '+';

            // Sum views from today across reels, videos, and livestreams
            $row2 = Database::queryOne(
                "SELECT
                    COALESCE((SELECT SUM(views) FROM reels), 0) +
                    COALESCE((SELECT SUM(views) FROM videos), 0) AS total_views"
            );
            $totalViews = (int) ($row2['total_views'] ?? 0);
            if ($totalViews >= 1000000) {
                $dailyViews = round($totalViews / 1000000, 1) . 'M+';
            } elseif ($totalViews >= 1000) {
                $dailyViews = round($totalViews / 1000, 1) . 'K+';
            } else {
                $dailyViews = (string) $totalViews;
            }
        } catch (\Exception $e) {
            // use defaults
        }

        return [
            'title'           => 'Discover, Create & Share',
            'subtitle'        => 'Your creative universe awaits.',
            'cover_url'       => '/uploads/home/featured_banner.jpg',
            'creators_online' => $creatorsOnline,
            'daily_views'     => $dailyViews,
        ];
    }

    /**
     * Discover grid – top reels for the spotlight carousel's featured card.
     * Returns reels sorted by views (most popular first).
     */
    protected function getDiscoverGrid(): array
    {
        try {
            return Database::query(
                "SELECT
                    r.id,
                    r.title,
                    r.thumbnail AS cover_url,
                    r.views,
                    r.likes,
                    r.duration,
                    r.category,
                    'reel' AS type,
                    u.username,
                    u.name AS creator_name,
                    u.avatar AS creator_avatar,
                    u.is_verified
                FROM reels r
                INNER JOIN users u ON r.user_id = u.id
                WHERE r.status = 'published'
                ORDER BY r.views DESC
                LIMIT 8"
            );
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Spotlight carousel ad cards from the spotlight_ads table.
     * Falls back to hardcoded defaults if the table is empty or query fails.
     */
    protected function getSpotlightAds(): array
    {
        try {
            $ads = Database::query(
                "SELECT
                    id,
                    title,
                    subtitle,
                    image_url,
                    link_url,
                    badge,
                    badge_color,
                    sort_order
                FROM spotlight_ads
                WHERE is_active = 1
                  AND (starts_at IS NULL OR starts_at <= NOW())
                  AND (ends_at IS NULL OR ends_at >= NOW())
                ORDER BY sort_order ASC
                LIMIT 3"
            );

            if (!empty($ads)) {
                return $ads;
            }
        } catch (\Exception $e) {
            // fall through to defaults
        }

        return [
            [
                'id'         => 1,
                'title'      => 'Shop the Latest Drops',
                'subtitle'   => 'Exclusive deals on trending products',
                'image_url'  => '/uploads/home/card_1.jpg',
                'link_url'   => '/marketplace',
                'badge'      => 'Shop',
                'badge_color'=> '#22c55e',
            ],
            [
                'id'         => 2,
                'title'      => 'Go Live & Earn',
                'subtitle'   => 'Start streaming and receive gifts from fans',
                'image_url'  => '/uploads/home/card_2.jpg',
                'link_url'   => '/livestream/start',
                'badge'      => 'Stream',
                'badge_color'=> '#ef4444',
            ],
            [
                'id'         => 3,
                'title'      => 'Discover New Music',
                'subtitle'   => 'Stream the hottest Afrobeats tracks',
                'image_url'  => '/uploads/home/card_3.jpg',
                'link_url'   => '/music',
                'badge'      => 'Music',
                'badge_color'=> '#ec4899',
            ],
        ];
    }

    /**
     * Currently live streams – for the "Live Now" horizontal scroll.
     */
    protected function getLiveStreams(): array
    {
        try {
            return Database::query(
                "SELECT
                    l.id,
                    l.title,
                    l.description,
                    l.thumbnail,
                    l.viewers,
                    l.peak_viewers,
                    l.status,
                    l.started_at,
                    l.total_gifts,
                    l.gift_earnings,
                    u.username,
                    u.name AS creator_name,
                    u.avatar AS creator_avatar,
                    u.is_verified
                FROM livestreams l
                INNER JOIN users u ON l.user_id = u.id
                WHERE l.status = 'live'
                ORDER BY l.viewers DESC"
            );
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Trending reels – horizontal scroll of clip cards.
     * Sorted by views descending; pulls creator info via JOIN.
     */
    protected function getTrendingReels(): array
    {
        try {
            return Database::query(
                "SELECT
                    r.id,
                    r.title,
                    r.thumbnail,
                    r.video_url,
                    r.duration,
                    r.views,
                    r.likes,
                    r.comments_count,
                    r.shares,
                    r.song_name,
                    r.category,
                    r.is_featured,
                    r.viral_score,
                    r.published_at,
                    r.created_at,
                    u.username,
                    u.name AS creator_name,
                    u.avatar AS creator_avatar,
                    u.is_verified
                FROM reels r
                INNER JOIN users u ON r.user_id = u.id
                WHERE r.status = 'published'
                ORDER BY r.views DESC
                LIMIT 8"
            );
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Posts feed – main content feed with author info.
     * Returns published posts with comment count and share count.
     * Includes is_following so the view can show correct button state.
     */
    protected function getPosts(): array
    {
        try {
            $me = Auth::user();
            $myId = $me ? (int) $me['id'] : 0;

            return Database::query(
                "SELECT
                    p.id,
                    p.user_id,
                    p.content,
                    p.image_url,
                    p.likes,
                    p.comments_count,
                    p.shares,
                    p.status,
                    p.created_at,
                    p.updated_at,
                    u.username,
                    u.name AS creator_name,
                    u.avatar AS creator_avatar,
                    u.is_verified,
                    u.role AS creator_role,
                    CASE WHEN ? > 0 AND EXISTS (
                        SELECT 1 FROM followers f2
                        WHERE f2.follower_id = ? AND f2.following_id = p.user_id
                    ) THEN 1 ELSE 0 END AS is_following
                FROM posts p
                INNER JOIN users u ON p.user_id = u.id
                WHERE p.status = 'published'
                ORDER BY p.created_at DESC
                LIMIT 10",
                [$myId, $myId]
            );
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Trending videos – vertical list of video cards.
     * Sorted by views descending; includes creator info.
     */
    protected function getTrendingVideos(): array
    {
        try {
            return Database::query(
                "SELECT
                    v.id,
                    v.title,
                    v.description,
                    v.thumbnail,
                    v.video_url,
                    v.duration,
                    v.views,
                    v.likes,
                    v.comments_count,
                    v.shares,
                    v.category,
                    v.is_featured,
                    v.is_monetized,
                    v.viral_score,
                    v.published_at,
                    v.created_at,
                    u.username,
                    u.name AS creator_name,
                    u.avatar AS creator_avatar,
                    u.is_verified
                FROM videos v
                INNER JOIN users u ON v.user_id = u.id
                WHERE v.status = 'published'
                ORDER BY v.views DESC
                LIMIT 8"
            );
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Current authenticated user – for header avatar and profile link.
     * Returns null if not logged in.
     */
    protected function getCurrentUser(): ?array
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return null;
            }

            $row = Database::queryOne(
                "SELECT id, name, username, email, avatar, bio, role, is_verified
                 FROM users
                 WHERE id = ?",
                [(int) $user['id']]
            );

            return $row;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Unread notification count – for the notification badge in header.
     */
    protected function getUnreadNotificationCount(): int
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return 0;
            }

            $row = Database::queryOne(
                "SELECT COUNT(*) AS cnt
                 FROM notifications
                 WHERE user_id = ? AND is_read = 0",
                [(int) $user['id']]
            );

            return (int) ($row['cnt'] ?? 0);
        } catch (\Exception $e) {
            return 0;
        }
    }

    /* ====================================================================
     *  FALLBACK DATA – used when database is not reachable
     * ==================================================================== */

    /**
     * Safe default data so the page still renders without a DB connection.
     */
    protected function getDefaultData(): array
    {
        return [
            'categories'      => $this->getCategories(),
            'topCreators'     => [],
            'quickActions'    => $this->getQuickActions(),
            'featuredContent' => [
                'title'           => 'Discover, Create & Share',
                'subtitle'        => 'Your creative universe awaits.',
                'cover_url'       => '/uploads/home/featured_banner.jpg',
                'creators_online' => '0',
                'daily_views'     => '0',
            ],
            'discoverGrid'    => [],
            'spotlightAds'    => $this->getSpotlightAds(),
            'liveNow'         => [],
            'trendingReels'   => [],
            'posts'           => [],
            'trendingVideos'  => [],
            'currentUser'     => null,
            'unreadCount'     => 0,
        ];
    }

    /* ====================================================================
     *  OTHER PAGES
     * ==================================================================== */

    /**
     * Side menu / drawer page.
     */
    public function menu(): Response
    {
        return $this->view('menu.index', [
            'currentUser' => $this->getCurrentUser(),
        ]);
    }

    /**
     * Notifications page.
     */
    public function notifications(): Response
    {
        try {
            Database::connect();
            $notifications = Database::query(
                "SELECT
                    n.*,
                    u.name AS actor_name,
                    u.username AS actor_username,
                    u.avatar AS actor_avatar
                FROM notifications n
                LEFT JOIN users u ON n.actor_id = u.id
                WHERE n.user_id = ?
                ORDER BY n.created_at DESC
                LIMIT 30",
                [Auth::user()['id'] ?? 0]
            );
        } catch (\Exception $e) {
            $notifications = [];
        }

        return $this->view('notifications.index', [
            'notifications' => $notifications,
        ]);
    }

    /**
     * All creators listing – linked from "See all" in Suggested Creators.
     * Shows all creator/admins except the current admin account.
     */
    public function creators(): Response
    {
        try {
            Database::connect();
            $me = Auth::user();
            $myId = $me ? (int) $me['id'] : 0;

            $creators = Database::query(
                "SELECT
                    u.id,
                    u.name,
                    u.username,
                    u.avatar,
                    u.bio,
                    u.is_verified,
                    u.role,
                    COUNT(DISTINCT f.id) AS follower_count,
                    CASE WHEN ? > 0 AND EXISTS (
                        SELECT 1 FROM followers f2
                        WHERE f2.follower_id = ? AND f2.following_id = u.id
                    ) THEN 1 ELSE 0 END AS is_following
                FROM users u
                LEFT JOIN followers f ON f.following_id = u.id
                WHERE u.role IN ('creator', 'admin')
                  AND u.is_banned = 0
                  AND u.id != 1
                GROUP BY u.id
                ORDER BY follower_count DESC
                LIMIT 50",
                [$myId, $myId]
            );
        } catch (\Exception $e) {
            $creators = [];
        }

        return $this->view('home.creators', [
            'creators' => $creators,
            'currentUser' => $this->getCurrentUser(),
        ]);
    }
}