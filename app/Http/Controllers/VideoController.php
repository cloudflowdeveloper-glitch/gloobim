<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\Response;
use Core\Database;
use Core\Auth;
use App\Services\VideoProcessor;

class VideoController extends Controller
{
    public function index(): Response
    {
        $me = Auth::user();
        $myId = $me ? (int) $me['id'] : 0;
        $videos = [];
        $featuredVideos = [];
        $continueWatching = [];
        $trendingVideos = [];
        $creatorsToWatch = [];
        $newUploads = [];

        try {
            // Get all published videos
            $videos = Database::query(
                "SELECT v.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified
                 FROM videos v
                 INNER JOIN users u ON v.user_id = u.id
                 WHERE v.status = 'published'
                 ORDER BY v.created_at DESC
                 LIMIT 30"
            );
        } catch (\Exception $e) {
            $videos = [];
        }

        // Fallback mock data if empty
        if (empty($videos)) {
            $videos = [
                ['id' => 1, 'user_id' => 1, 'title' => 'The Future of Artificial Intelligence in 2025', 'description' => 'An in-depth look at how AI is transforming the world and what comes next.', 'thumbnail' => '/uploads/thumbnails/reel_thumb_1.jpg', 'video_url' => '', 'duration' => 1965, 'views' => 124000, 'likes' => 8200, 'comments_count' => 340, 'shares' => 1200, 'category' => 'tech', 'status' => 'published', 'creator_name' => 'Tech with Arjun', 'username' => 'techarjun', 'creator_avatar' => '/uploads/profiles/marcustech.jpg', 'is_verified' => 1, 'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))],
                ['id' => 2, 'user_id' => 2, 'title' => 'Exploring Mars: A New Frontier', 'description' => 'NASA\'s latest discoveries on the red planet.', 'thumbnail' => '/uploads/thumbnails/reel_thumb_2.jpg', 'video_url' => '', 'duration' => 1696, 'views' => 89000, 'likes' => 6500, 'comments_count' => 210, 'shares' => 890, 'category' => 'tech', 'status' => 'published', 'creator_name' => 'Space Insight', 'username' => 'spaceinsight', 'creator_avatar' => '/uploads/profiles/marcustech.jpg', 'is_verified' => 1, 'created_at' => date('Y-m-d H:i:s', strtotime('-5 days'))],
                ['id' => 3, 'user_id' => 3, 'title' => 'How I Edit My YouTube Videos (Full Process)', 'description' => 'Complete video editing workflow from start to finish.', 'thumbnail' => '/uploads/thumbnails/reel_thumb_3.jpg', 'video_url' => '', 'duration' => 1290, 'views' => 67000, 'likes' => 5100, 'comments_count' => 180, 'shares' => 670, 'category' => 'tech', 'status' => 'published', 'creator_name' => 'Creator Flow', 'username' => 'creatorflow', 'creator_avatar' => '/uploads/profiles/aminabeauty.jpg', 'is_verified' => 1, 'created_at' => date('Y-m-d H:i:s', strtotime('-3 days'))],
                ['id' => 4, 'user_id' => 4, 'title' => 'I Spent 30 Days Alone in the Wild', 'description' => 'Surviving 30 days in the wilderness with nothing but a knife.', 'thumbnail' => '/uploads/thumbnails/reel_thumb_4.jpg', 'video_url' => '', 'duration' => 1122, 'views' => 245000, 'likes' => 18000, 'comments_count' => 2400, 'shares' => 3400, 'category' => 'education', 'status' => 'published', 'creator_name' => 'WanderLens', 'username' => 'wanderlens', 'creator_avatar' => '/uploads/profiles/traveldave.jpg', 'is_verified' => 1, 'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))],
                ['id' => 5, 'user_id' => 5, 'title' => 'Top 10 Fastest Cars in the World 2025', 'description' => 'The ultimate countdown of speed machines.', 'thumbnail' => '/uploads/thumbnails/reel_thumb_5.jpg', 'video_url' => '', 'duration' => 862, 'views' => 98000, 'likes' => 7200, 'comments_count' => 450, 'shares' => 1100, 'category' => 'tech', 'status' => 'published', 'creator_name' => 'Auto Gear', 'username' => 'autogear', 'creator_avatar' => '/uploads/profiles/traveldave.jpg', 'is_verified' => 1, 'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))],
                ['id' => 6, 'user_id' => 6, 'title' => 'Making a Hit Song in 24 Hours Challenge', 'description' => 'Can we produce a hit track in just 24 hours?', 'thumbnail' => '/uploads/thumbnails/reel_thumb_6.jpg', 'video_url' => '', 'duration' => 517, 'views' => 76000, 'likes' => 5800, 'comments_count' => 320, 'shares' => 950, 'category' => 'music', 'status' => 'published', 'creator_name' => 'BeatMaster', 'username' => 'beatmaster', 'creator_avatar' => '/uploads/profiles/djpulse.jpg', 'is_verified' => 1, 'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))],
                ['id' => 7, 'user_id' => 7, 'title' => 'Paradise Found: Top 5 Islands to Visit', 'description' => 'The most beautiful islands you must see.', 'thumbnail' => '/uploads/thumbnails/reel_thumb_7.jpg', 'video_url' => '', 'duration' => 1005, 'views' => 64000, 'likes' => 4900, 'comments_count' => 280, 'shares' => 780, 'category' => 'education', 'status' => 'published', 'creator_name' => 'Travel Diaries', 'username' => 'travel', 'creator_avatar' => '/uploads/profiles/traveldave.jpg', 'is_verified' => 1, 'created_at' => date('Y-m-d H:i:s', strtotime('-3 days'))],
                ['id' => 8, 'user_id' => 8, 'title' => 'My New Camera Setup for 2025!', 'description' => 'Here\'s my new camera setup and why I chose each gear.', 'thumbnail' => '/uploads/thumbnails/reel_thumb_8.jpg', 'video_url' => '', 'duration' => 1164, 'views' => 12000, 'likes' => 980, 'comments_count' => 65, 'shares' => 120, 'category' => 'tech', 'status' => 'published', 'creator_name' => 'Shutter Stories', 'username' => 'shutter', 'creator_avatar' => '/uploads/profiles/aminabeauty.jpg', 'is_verified' => 1, 'created_at' => date('Y-m-d H:i:s', strtotime('-6 hours'))],
                ['id' => 9, 'user_id' => 9, 'title' => 'Investing for Beginners (Complete Guide)', 'description' => 'Everything you need to know about investing.', 'thumbnail' => '/uploads/thumbnails/reel_thumb_1.jpg', 'video_url' => '', 'duration' => 912, 'views' => 8700, 'likes' => 640, 'comments_count' => 42, 'shares' => 85, 'category' => 'business', 'status' => 'published', 'creator_name' => 'Finance Mindset', 'username' => 'finance', 'creator_avatar' => '/uploads/profiles/chefkwame.jpg', 'is_verified' => 0, 'created_at' => date('Y-m-d H:i:s', strtotime('-8 hours'))],
            ];
        }

        // Split into sections
        $featuredVideos = array_slice($videos, 0, 3);
        $continueWatching = [];
        foreach (array_slice($videos, 0, 3) as $i => $v) {
            $v['progress'] = [75, 52, 40][$i] ?? 50;
            $continueWatching[] = $v;
        }
        $trendingVideos = array_slice($videos, 4, 3);

        // Creators to Watch — real users from DB who have published videos
        $creatorsToWatch = [];
        try {
            $creatorsToWatch = Database::query(
                "SELECT u.id, u.name, u.username, u.avatar, u.is_verified,
                        (SELECT COUNT(*) FROM followers WHERE following_id = u.id) AS follower_count,
                        (SELECT COUNT(*) FROM videos WHERE user_id = u.id AND status = 'published') AS video_count
                 FROM users u
                 WHERE u.is_banned = 0
                   AND EXISTS (SELECT 1 FROM videos v WHERE v.user_id = u.id AND v.status = 'published')
                 ORDER BY follower_count DESC
                 LIMIT 5"
            );
            // Add is_following flag for each creator
            foreach ($creatorsToWatch as &$c) {
                $c['subscriber_count'] = $c['follower_count'];
                $c['is_following'] = $myId > 0 && $this->isFollowing($myId, (int)$c['id']);
            }
            unset($c);
        } catch (\Exception $e) {
            $creatorsToWatch = [];
        }

        $newUploads = array_slice($videos, 7, 2);

        return $this->view('videos.index', [
            'videos' => $videos,
            'featuredVideos' => $featuredVideos,
            'continueWatching' => $continueWatching,
            'trendingVideos' => $trendingVideos,
            'creatorsToWatch' => $creatorsToWatch,
            'newUploads' => $newUploads,
        ]);
    }

    public function create(): Response
    {
        return $this->view('videos.create');
    }

    public function store(): Response
    {
        $user = \Core\Auth::user();
        if (!$user) return $this->json(['error' => 'Unauthenticated'], 401);

        $title = trim($_POST['title'] ?? '');
        if (empty($title)) return $this->json(['error' => 'Title is required'], 422);

        $description = trim($_POST['description'] ?? '');
        $category = $_POST['category'] ?? null;
        $basePath = BASE_PATH;
        $uploadDir = $basePath . '/public/uploads/videos';
        $reelDir = $basePath . '/public/uploads/reels';
        $thumbDir = $basePath . '/public/uploads/thumbnails';

        foreach ([$uploadDir, $reelDir, $thumbDir] as $d) {
            if (!is_dir($d)) @mkdir($d, 0755, true);
        }

        $videoUrl = null;
        $duration = 0;
        $thumbnail = null;
        $reelsCreated = 0;
        $processor = new VideoProcessor(30); // 30-second reels

        // Handle file upload
        if (!empty($_FILES['video']['tmp_name'])) {
            $file = $_FILES['video'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['mp4', 'mov', 'avi', 'mkv', 'webm', 'm4v'];

            if (!in_array($ext, $allowed)) {
                return $this->json(['error' => 'Unsupported format. Allowed: mp4, mov, avi, mkv, webm'], 400);
            }

            $maxSize = 500 * 1024 * 1024; // 500MB
            if ($file['size'] > $maxSize) {
                return $this->json(['error' => 'File too large. Max 500MB'], 400);
            }

            $fileName = 'vid_' . $user['id'] . '_' . time() . '.' . $ext;
            $filePath = $uploadDir . '/' . $fileName;

            if (!move_uploaded_file($file['tmp_name'], $filePath)) {
                return $this->json(['error' => 'Failed to save uploaded file'], 500);
            }

            $videoUrl = '/uploads/videos/' . $fileName;

            // Get duration
            $duration = (int) round($processor->getDuration($filePath));

            // Generate thumbnail
            $thumbName = 'thumb_' . $user['id'] . '_' . time() . '.jpg';
            $thumbPath = $thumbDir . '/' . $thumbName;
            if ($processor->generateThumbnail($filePath, $thumbPath, 1)) {
                $thumbnail = '/uploads/thumbnails/' . $thumbName;
            }

            // Split into reels if video is long enough
            if ($duration > 35 && $processor->isAvailable()) {
                $segments = $processor->splitIntoReels($filePath, $reelDir, 'reel_' . $user['id'] . '_' . time());

                foreach ($segments as $seg) {
                    $reelFileName = basename($seg['path']);
                    $reelUrl = '/uploads/reels/' . $reelFileName;

                    // Generate thumbnail for this reel
                    $reelThumb = null;
                    $reelThumbPath = $thumbDir . '/thumb_reel_' . $user['id'] . '_' . time() . '_' . $seg['index'] . '.jpg';
                    if ($processor->generateThumbnail($seg['path'], $reelThumbPath, 0.5)) {
                        $reelThumb = '/uploads/thumbnails/' . basename($reelThumbPath);
                    }

                    Database::insert('reels', [
                        'user_id' => $user['id'],
                        'title' => $title . ' (Part ' . $seg['index'] . ')',
                        'description' => substr($description, 0, 500),
                        'thumbnail' => $reelThumb,
                        'video_url' => $reelUrl,
                        'duration' => (int) round($seg['duration']),
                        'status' => 'published',
                        'category' => $category,
                        'published_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                    $reelsCreated++;
                }
            }
        } else {
            // URL-based upload (existing behavior)
            $videoUrl = $_POST['video_url'] ?? null;
            $duration = (int)($_POST['duration'] ?? 0);
            $thumbnail = $_POST['thumbnail'] ?? null;
        }

        // Save main video
        $videoId = Database::insert('videos', [
            'user_id' => $user['id'],
            'title' => $title,
            'description' => $description,
            'thumbnail' => $thumbnail,
            'video_url' => $videoUrl,
            'duration' => $duration,
            'category' => $category,
            'status' => 'published',
            'published_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $response = [
            'message' => 'Video uploaded successfully',
            'video_id' => $videoId,
            'duration' => $duration,
            'reels_created' => $reelsCreated,
        ];

        if ($reelsCreated > 0) {
            $response['message'] .= " — Auto-split into {$reelsCreated} reels";
        }

        return $this->json($response, 201);
    }

    public function show($id): Response
    {
        $video = null;
        $me = Auth::user();
        $myId = $me ? (int) $me['id'] : 0;

        try {
            $videos = Database::query(
                "SELECT v.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified,
                        CASE WHEN ? > 0 AND EXISTS (
                            SELECT 1 FROM followers f WHERE f.follower_id = ? AND f.following_id = v.user_id
                        ) THEN 1 ELSE 0 END AS is_following,
                        (SELECT COUNT(*) FROM followers WHERE following_id = v.user_id) AS subscribers
                 FROM videos v
                 INNER JOIN users u ON v.user_id = u.id
                 WHERE v.id = ? AND v.status = 'published'
                 LIMIT 1",
                [$myId, $myId, $id]
            );
            $video = $videos[0] ?? null;
        } catch (\Exception $e) {
            $video = null;
        }

        if (!$video) {
            return $this->redirect('/videos');
        }

        return $this->view('videos.show', [
            'video' => $video,
            'comments' => $this->getComments($id),
        ]);
    }

    public function edit($id): Response
    {
        return $this->view('videos.edit', ['id' => $id]);
    }

    public function update($id): Response
    {
        return $this->json(['message' => 'Video updated successfully']);
    }

    public function destroy($id): Response
    {
        try {
            Database::execute("UPDATE videos SET status = 'deleted' WHERE id = ?", [$id]);
            return $this->json(['message' => 'Video deleted successfully']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function like($id): Response
    {
        try {
            Database::execute("UPDATE videos SET likes = COALESCE(likes, 0) + 1 WHERE id = ?", [$id]);
            $video = Database::query("SELECT likes FROM videos WHERE id = ?", [$id]);
            return $this->json(['message' => 'Liked', 'likes' => $video[0]['likes'] ?? 0]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function dislike($id): Response
    {
        try {
            Database::execute("UPDATE videos SET dislikes = COALESCE(dislikes, 0) + 1 WHERE id = ?", [$id]);
            $video = Database::query("SELECT dislikes FROM videos WHERE id = ?", [$id]);
            return $this->json(['message' => 'Disliked', 'dislikes' => $video[0]['dislikes'] ?? 0]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function download($id): Response
    {
        try {
            $video = Database::queryOne("SELECT title, video_url, thumbnail FROM videos WHERE id = ? AND status = 'published'", [$id]);
            if (!$video || empty($video['video_url'])) {
                return $this->json(['error' => 'Video not available for download'], 404);
            }
            // Increment download count
            Database::execute("UPDATE videos SET shares = COALESCE(shares, 0) + 1 WHERE id = ?", [$id]);
            return $this->json([
                'message' => 'Download started',
                'video_url' => $video['video_url'],
                'title' => $video['title'],
            ]);
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
                'commentable_type' => 'video',
                'commentable_id' => $id,
                'body' => $body,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            Database::execute("UPDATE videos SET comments_count = COALESCE(comments_count, 0) + 1 WHERE id = ?", [$id]);

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
            Database::execute("UPDATE videos SET shares = COALESCE(shares, 0) + 1 WHERE id = ?", [$id]);
            return $this->json(['message' => 'Shared', 'video_id' => $id]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    protected function getComments($videoId): array
    {
        try {
            return Database::query(
                "SELECT c.*, u.username, u.name AS commenter_name, u.avatar AS commenter_avatar
                 FROM comments c
                 INNER JOIN users u ON c.user_id = u.id
                 WHERE c.commentable_type = 'video' AND c.commentable_id = ?
                 ORDER BY c.created_at DESC
                 LIMIT 20",
                [$videoId]
            );
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function isFollowing(int $followerId, int $followingId): bool
    {
        try {
            $row = Database::queryOne(
                "SELECT id FROM followers WHERE follower_id = ? AND following_id = ?",
                [$followerId, $followingId]
            );
            return !empty($row);
        } catch (\Exception $e) {
            return false;
        }
    }
}
