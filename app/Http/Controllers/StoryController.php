<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\Response;
use Core\Database;
use Core\Auth;

class StoryController extends Controller
{
    /**
     * Get all active stories for the story viewer page.
     * Groups stories by user, shows own stories first, then followed users.
     */
    public function index(): Response
    {
        $user = Auth::user();
        if (!$user) {
            return $this->redirect('/login');
        }

        $stories = $this->getActiveStories($user['id']);

        return $this->view('stories.index', [
            'stories'      => $stories,
            'currentUser'  => $user,
            'activeTab'    => 'home',
        ]);
    }

    /**
     * Show story creation form.
     */
    public function create(): Response
    {
        $user = Auth::user();
        if (!$user) {
            return $this->redirect('/login');
        }

        return $this->view('stories.create', [
            'currentUser' => $user,
        ]);
    }

    /**
     * Store a new story (image + optional text overlay).
     */
    public function store(): Response
    {
        $user = Auth::user();
        if (!$user) {
            return $this->json(['error' => 'Please login first.'], 401);
        }

        // Check if user has an active story (limit 1 active story at a time)
        $existing = Database::queryOne(
            "SELECT id FROM stories WHERE user_id = ? AND expires_at > NOW() AND is_active = 1 LIMIT 1",
            [(int) $user['id']]
        );
        if ($existing) {
            return $this->json(['error' => 'You already have an active story. Delete it first before posting a new one.'], 400);
        }

        // Handle image upload
        $imageUrl = $this->handleImageUpload();
        if (!$imageUrl) {
            return $this->json(['error' => 'Please upload a valid image (jpg, png, gif, webp).'], 400);
        }

        $textContent = $this->request->input('text_content', '');
        $textPosition = $this->request->input('text_position', 'center');
        $textColor = $this->request->input('text_color', '#ffffff');
        $textSize = $this->request->input('text_size', '24');
        $fontStyle = $this->request->input('font_style', 'normal');

        // Validate text position
        if (!in_array($textPosition, ['top', 'center', 'bottom'])) {
            $textPosition = 'center';
        }

        // Validate text color (hex)
        if (!preg_match('/^#[a-fA-F0-9]{6}$/', $textColor)) {
            $textColor = '#ffffff';
        }

        $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));

        try {
            $storyId = Database::insert('stories', [
                'user_id'      => (int) $user['id'],
                'image_url'    => $imageUrl,
                'text_content' => $textContent,
                'text_position'=> $textPosition,
                'text_color'   => $textColor,
                'text_size'    => $textSize,
                'font_style'   => $fontStyle,
                'created_at'   => date('Y-m-d H:i:s'),
                'expires_at'   => $expiresAt,
                'is_active'    => 1,
            ]);

            return $this->json([
                'success' => true,
                'message' => 'Story posted! It will disappear in 24 hours.',
                'story_id'=> $storyId,
                'redirect'=> '/stories',
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Failed to save story.'], 500);
        }
    }

    /**
     * View a single story (used when tapping a story ring).
     * Also records the view.
     */
    public function show(string $id): Response
    {
        $user = Auth::user();
        if (!$user) {
            return $this->json(['error' => 'Please login first.'], 401);
        }

        $story = Database::queryOne(
            "SELECT s.*, u.name, u.username, u.avatar, u.is_verified
             FROM stories s
             INNER JOIN users u ON s.user_id = u.id
             WHERE s.id = ? AND s.is_active = 1
             LIMIT 1",
            [(int) $id]
        );

        if (!$story) {
            if ($this->request->headers['X-Requested-With'] ?? '' === 'XMLHttpRequest') {
                return $this->json(['error' => 'Story not found or expired.'], 404);
            }
            return $this->redirect('/stories');
        }

        // Record view (prevent double-counting with UNIQUE constraint)
        try {
            Database::insert('story_views', [
                'story_id'  => (int) $id,
                'viewer_id' => (int) $user['id'],
            ]);
            // Increment view count
            Database::execute(
                "UPDATE stories SET views_count = views_count + 1 WHERE id = ?",
                [(int) $id]
            );
        } catch (\Exception $e) {
            // Duplicate view — ignore
        }

        // Get all stories from the same user for the viewer carousel
        $userStories = Database::query(
            "SELECT s.*, u.name, u.username, u.avatar, u.is_verified
             FROM stories s
             INNER JOIN users u ON s.user_id = u.id
             WHERE s.user_id = ? AND s.is_active = 1 AND s.expires_at > NOW()
             ORDER BY s.created_at ASC",
            [(int) $story['user_id']]
        );

        return $this->view('stories.show', [
            'story'       => $story,
            'userStories' => $userStories,
            'currentUser' => $user,
        ]);
    }

    /**
     * Return story data as JSON for the popup viewer on the home page.
     * Also records the view.
     */
    public function viewJson(string $id): Response
    {
        $user = Auth::user();
        if (!$user) {
            return $this->json(['error' => 'Please login first.'], 401);
        }

        $story = Database::queryOne(
            "SELECT s.*, u.name, u.username, u.avatar, u.is_verified
             FROM stories s
             INNER JOIN users u ON s.user_id = u.id
             WHERE s.id = ? AND s.is_active = 1
             LIMIT 1",
            [(int) $id]
        );

        if (!$story) {
            return $this->json(['error' => 'Story not found or expired.'], 404);
        }

        // Record view
        try {
            Database::insert('story_views', [
                'story_id'  => (int) $id,
                'viewer_id' => (int) $user['id'],
            ]);
            Database::execute("UPDATE stories SET views_count = views_count + 1 WHERE id = ?", [(int) $id]);
        } catch (\Exception $e) {}

        // Get all stories from the same user for carousel
        $userStories = Database::query(
            "SELECT s.id, s.image_url, s.text_content, s.text_position, s.text_color, s.text_size, s.font_style, s.views_count, s.created_at,
                    u.name, u.username, u.avatar, u.is_verified
             FROM stories s
             INNER JOIN users u ON s.user_id = u.id
             WHERE s.user_id = ? AND s.is_active = 1 AND s.expires_at > NOW()
             ORDER BY s.created_at ASC",
            [(int) $story['user_id']]
        );

        // Find current story index
        $currentIndex = 0;
        foreach ($userStories as $i => $s) {
            if ((int)$s['id'] === (int)$id) {
                $currentIndex = $i;
                break;
            }
        }

        return $this->json([
            'story' => $story,
            'user_stories' => $userStories,
            'current_index' => $currentIndex,
        ]);
    }

    /**
     * Delete own story.
     */
    public function destroy(string $id): Response
    {
        $user = Auth::user();
        if (!$user) {
            return $this->json(['error' => 'Please login first.'], 401);
        }

        // Soft-delete
        $affected = Database::update(
            'stories',
            ['is_active' => 0],
            'id = ? AND user_id = ?',
            [(int) $id, (int) $user['id']]
        );

        if ($affected > 0) {
            return $this->json(['success' => true, 'message' => 'Story deleted.']);
        }

        return $this->json(['error' => 'Story not found.'], 404);
    }

    /**
     * Get all stories for a specific user (for story viewer carousel).
     */
    public function userStories(string $username): Response
    {
        $currentUser = Auth::user();

        $targetUser = Database::queryOne(
            "SELECT id, name, username, avatar, is_verified FROM users WHERE username = ? LIMIT 1",
            [$username]
        );

        if (!$targetUser) {
            if ($this->request->headers['X-Requested-With'] ?? '' === 'XMLHttpRequest') {
                return $this->json(['error' => 'User not found.'], 404);
            }
            return $this->redirect('/stories');
        }

        $stories = Database::query(
            "SELECT s.*, u.name, u.username, u.avatar, u.is_verified
             FROM stories s
             INNER JOIN users u ON s.user_id = u.id
             WHERE s.user_id = ? AND s.is_active = 1 AND s.expires_at > NOW()
             ORDER BY s.created_at ASC",
            [(int) $targetUser['id']]
        );

        return $this->view('stories.show', [
            'story'        => $stories[0] ?? null,
            'userStories'  => $stories,
            'currentUser'  => $currentUser,
        ]);
    }

    /* ====================================================================
     *  HELPER METHODS
     * ==================================================================== */

    /**
     * Get active (unexpired) stories, grouped by user.
     * Shows current user's stories first, then followed users, then others.
     */
    public static function getActiveStories(int $currentUserId): array
    {
        try {
            $rows = Database::query(
                "SELECT
                    s.id, s.user_id, s.image_url, s.text_content,
                    s.text_position, s.text_color, s.text_size, s.font_style,
                    s.views_count, s.created_at, s.expires_at,
                    u.name, u.username, u.avatar, u.is_verified,
                    CASE WHEN sv.id IS NOT NULL THEN 1 ELSE 0 END AS is_viewed
                 FROM stories s
                 INNER JOIN users u ON s.user_id = u.id
                 LEFT JOIN story_views sv ON sv.story_id = s.id AND sv.viewer_id = ?
                 WHERE s.is_active = 1 AND s.expires_at > NOW()
                   AND u.is_banned = 0
                 ORDER BY
                    CASE WHEN s.user_id = ? THEN 1 ELSE 2 END ASC,
                    s.created_at DESC",
                [$currentUserId, $currentUserId]
            );

            // Group stories by user
            $grouped = [];
            foreach ($rows as $row) {
                $uid = $row['user_id'];
                if (!isset($grouped[$uid])) {
                    $grouped[$uid] = [
                        'user_id'    => $row['user_id'],
                        'name'       => $row['name'],
                        'username'   => $row['username'],
                        'avatar'     => $row['avatar'],
                        'is_verified'=> $row['is_verified'],
                        'stories'    => [],
                        'has_unseen' => false,
                    ];
                }
                $grouped[$uid]['stories'][] = $row;
                if (!$row['is_viewed']) {
                    $grouped[$uid]['has_unseen'] = true;
                }
            }

            return array_values($grouped);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Handle image upload for story creation.
     * Returns the public URL path or false on failure.
     */
    protected function handleImageUpload(): string|false
    {
        $files = $_FILES;

        if (empty($files['image'])) {
            return false;
        }

        $file = $files['image'];

        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            return false;
        }

        // Validate file size (max 10MB)
        if ($file['size'] > 10 * 1024 * 1024) {
            return false;
        }

        // Generate unique filename
        $ext = match ($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif',
            'image/webp' => 'webp',
            default      => 'jpg',
        };

        $filename = 'story_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;

        // Ensure upload directory exists
        $uploadDir = __DIR__ . '/../../../public/uploads/stories';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $destPath = $uploadDir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            return '/uploads/stories/' . $filename;
        }

        return false;
    }
}
