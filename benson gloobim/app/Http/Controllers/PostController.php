<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\Response;
use Core\Database;
use Core\Auth;

class PostController extends Controller
{
    public function index(): Response
    {
        return $this->view('posts.index', [
            'posts'             => $this->getPosts(),
            'stories'           => $this->getStories(),
            'suggestedCreators' => $this->getSuggestedCreators(),
        ]);
    }

    public function create(): Response
    {
        return $this->view('posts.create');
    }

    public function store(): Response
    {
        $user = Auth::user();
        if (!$user) {
            return $this->json(['error' => 'Unauthenticated'], 401);
        }

        $input   = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $content = trim($input['content'] ?? '');

        if (empty($content)) {
            return $this->json(['error' => 'Content is required'], 422);
        }

        try {
            $postId = Database::insert('posts', [
                'user_id'    => $user['id'],
                'content'    => $content,
                'image_url'  => $input['image_url'] ?? null,
                'status'     => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return $this->json([
                'message' => 'Post created successfully',
                'post_id' => $postId,
            ], 201);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id): Response
    {
        $post = $this->getPostById($id);
        if (!$post) {
            return $this->redirect('/');
        }
        return $this->view('posts.show', [
            'post'     => $post,
            'comments' => $this->getComments($id),
        ]);
    }

    public function edit($id): Response
    {
        return $this->view('posts.edit', ['id' => $id]);
    }

    public function update($id): Response
    {
        return $this->json(['message' => 'Post updated successfully']);
    }

    public function destroy($id): Response
    {
        try {
            Database::execute("UPDATE posts SET status = 'deleted' WHERE id = ?", [$id]);
            return $this->json(['message' => 'Post deleted successfully']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    /* ==================================================================
     *  LIKE – toggle using the `likes` table (prevents double-likes)
     * ================================================================== */

    public function like($id): Response
    {
        $me = Auth::user();
        if (!$me) {
            return $this->json(['error' => 'Login required'], 401);
        }

        $myId  = (int) $me['id'];
        $postId = (int) $id;

        try {
            // Check if already liked
            $existing = Database::queryOne(
                "SELECT id FROM likes
                 WHERE user_id = ? AND likeable_type = 'post' AND likeable_id = ?",
                [$myId, $postId]
            );

            if ($existing) {
                // UNLIKE
                Database::delete('likes', 'id = ?', [$existing['id']]);
                Database::execute(
                    "UPDATE posts SET likes = GREATEST(COALESCE(likes, 0) - 1, 0) WHERE id = ?",
                    [$postId]
                );
                $liked = false;
                $message = 'Unliked';
            } else {
                // LIKE
                Database::insert('likes', [
                    'user_id'        => $myId,
                    'likeable_type'  => 'post',
                    'likeable_id'    => $postId,
                    'created_at'     => date('Y-m-d H:i:s'),
                ]);
                Database::execute(
                    "UPDATE posts SET likes = COALESCE(likes, 0) + 1 WHERE id = ?",
                    [$postId]
                );
                $liked = true;
                $message = 'Liked!';

                // Notify post author
                $post = Database::queryOne("SELECT user_id FROM posts WHERE id = ?", [$postId]);
                if ($post && (int) $post['user_id'] !== $myId) {
                    $this->notify((int) $post['user_id'], 'like', 'Post Liked',
                        $me['name'] . ' liked your post.', ['from_user_id' => $myId, 'post_id' => $postId]);
                }
            }

            $row = Database::queryOne("SELECT likes FROM posts WHERE id = ?", [$postId]);
            $likeCount = (int) ($row['likes'] ?? 0);

            return $this->json([
                'liked'   => $liked,
                'likes'   => $likeCount,
                'message' => $message,
            ]);

        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    /* ==================================================================
     *  COMMENT – insert into `comments` table, increment counter
     * ================================================================== */

    public function comment($id): Response
    {
        $user = Auth::user();
        if (!$user) {
            return $this->json(['error' => 'Login required'], 401);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $body  = trim($input['body'] ?? '');

        if (empty($body)) {
            return $this->json(['error' => 'Comment body is required'], 422);
        }

        try {
            $parentId = (int) ($input['parent_id'] ?? 0);

            Database::insert('comments', [
                'user_id'          => (int) $user['id'],
                'commentable_type' => 'post',
                'commentable_id'   => (int) $id,
                'parent_id'        => $parentId ?: null,
                'body'             => $body,
                'created_at'       => date('Y-m-d H:i:s'),
            ]);

            Database::execute(
                "UPDATE posts SET comments_count = COALESCE(comments_count, 0) + 1 WHERE id = ?",
                [(int) $id]
            );

            $row = Database::queryOne("SELECT comments_count FROM posts WHERE id = ?", [(int) $id]);
            $commentsCount = (int) ($row['comments_count'] ?? 0);

            // Notify post author
            $post = Database::queryOne("SELECT user_id FROM posts WHERE id = ?", [(int) $id]);
            if ($post && (int) $post['user_id'] !== (int) $user['id']) {
                $this->notify((int) $post['user_id'], 'comment', 'New Comment',
                    $user['name'] . ' commented: ' . mb_strimwidth($body, 0, 60, '…'),
                    ['from_user_id' => (int) $user['id'], 'post_id' => (int) $id, 'body' => $body]);
            }

            return $this->json([
                'message'        => 'Comment added',
                'post_id'        => $id,
                'comments_count' => $commentsCount,
                'user_name'      => $user['name'],
                'user_avatar'    => $user['avatar'] ?? null,
            ]);

        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    /* ==================================================================
     *  SHARE – increment share counter, create notification
     * ================================================================== */

    public function share($id): Response
    {
        try {
            Database::execute(
                "UPDATE posts SET shares = COALESCE(shares, 0) + 1 WHERE id = ?",
                [(int) $id]
            );

            $row = Database::queryOne("SELECT shares, user_id FROM posts WHERE id = ?", [(int) $id]);
            $shareCount = (int) ($row['shares'] ?? 0);

            // Optionally notify
            $me = Auth::user();
            if ($me && (int) ($row['user_id'] ?? 0) !== (int) $me['id']) {
                $this->notify((int) $row['user_id'], 'share', 'Post Shared',
                    $me['name'] . ' shared your post.',
                    ['from_user_id' => (int) $me['id'], 'post_id' => (int) $id]);
            }

            return $this->json([
                'message' => 'Shared',
                'post_id' => $id,
                'shares'  => $shareCount,
            ]);

        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function uploadImage(): Response
    {
        return $this->json(['message' => 'Image uploaded', 'url' => 'https://picsum.photos/id/1/600/400']);
    }

    /* ==================================================================
     *  DATA HELPERS
     * ================================================================== */

    protected function getPosts(): array
    {
        try {
            return Database::query(
                "SELECT p.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified
                 FROM posts p
                 INNER JOIN users u ON p.user_id = u.id
                 WHERE p.status = 'published'
                 ORDER BY p.created_at DESC
                 LIMIT 20"
            );
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function getPostById($id): ?array
    {
        try {
            $posts = Database::query(
                "SELECT p.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified
                 FROM posts p
                 INNER JOIN users u ON p.user_id = u.id
                 WHERE p.id = ? AND p.status = 'published'",
                [$id]
            );
            return $posts[0] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function getComments($postId): array
    {
        try {
            return Database::query(
                "SELECT c.*, u.username, u.name AS commenter_name, u.avatar AS commenter_avatar
                 FROM comments c
                 INNER JOIN users u ON c.user_id = u.id
                 WHERE c.commentable_type = 'post' AND c.commentable_id = ?
                 ORDER BY c.created_at DESC
                 LIMIT 20",
                [$postId]
            );
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function getStories(): array
    {
        try {
            return Database::query(
                "SELECT u.id, u.name, u.username, u.avatar, u.is_verified
                 FROM users u
                 WHERE u.role IN ('creator', 'admin')
                 ORDER BY RAND()
                 LIMIT 8"
            );
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function getSuggestedCreators(): array
    {
        try {
            return Database::query(
                "SELECT u.id, u.name, u.username, u.avatar, u.is_verified, COUNT(f.id) AS follower_count
                 FROM users u
                 LEFT JOIN followers f ON f.following_id = u.id
                 WHERE u.role IN ('creator', 'admin')
                 GROUP BY u.id
                 ORDER BY RAND()
                 LIMIT 6"
            );
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Fire-and-forget notification helper.
     */
    protected function notify(int $userId, string $type, string $title, string $body, array $data = []): void
    {
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
            // non-critical
        }
    }
}