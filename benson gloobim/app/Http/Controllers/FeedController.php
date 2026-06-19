<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\Response;
use Core\Database;

class FeedController extends Controller
{
    public function index(): Response
    {
        $user = \Core\Auth::user();
        $posts = [];
        $reels = [];

        try {
            if ($user) {
                $posts = Database::query(
                    "SELECT p.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified
                     FROM posts p
                     INNER JOIN users u ON p.user_id = u.id
                     WHERE p.status = 'published'
                     ORDER BY p.created_at DESC
                     LIMIT 20"
                );
                $reels = Database::query(
                    "SELECT r.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified
                     FROM reels r
                     INNER JOIN users u ON r.user_id = u.id
                     WHERE r.status = 'published'
                     ORDER BY r.viral_score DESC
                     LIMIT 10"
                );
            }
        } catch (\Exception $e) {
            $posts = [];
            $reels = [];
        }

        return $this->view('feed.index', [
            'posts' => $posts,
            'reels' => $reels,
            'user' => $user,
        ]);
    }

    public function trending(): Response
    {
        $user = \Core\Auth::user();
        $posts = [];
        $reels = [];

        try {
            $posts = Database::query(
                "SELECT p.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified
                 FROM posts p
                 INNER JOIN users u ON p.user_id = u.id
                 WHERE p.status = 'published'
                 ORDER BY (p.likes + p.comments_count + p.shares) DESC
                 LIMIT 20"
            );
            $reels = Database::query(
                "SELECT r.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified
                 FROM reels r
                 INNER JOIN users u ON r.user_id = u.id
                 WHERE r.status = 'published'
                 ORDER BY r.views DESC
                 LIMIT 10"
            );
        } catch (\Exception $e) {
            $posts = [];
            $reels = [];
        }

        return $this->view('feed.index', [
            'posts' => $posts,
            'reels' => $reels,
            'user' => $user,
            'trending' => true,
        ]);
    }

    public function subscriptions(): Response
    {
        $user = \Core\Auth::user();
        $posts = [];
        $reels = [];

        if ($user) {
            try {
                $posts = Database::query(
                    "SELECT p.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified
                     FROM posts p
                     INNER JOIN users u ON p.user_id = u.id
                     INNER JOIN followers f ON f.following_id = p.user_id
                     WHERE p.status = 'published' AND f.follower_id = ?
                     ORDER BY p.created_at DESC
                     LIMIT 20",
                    [$user['id']]
                );
                $reels = Database::query(
                    "SELECT r.*, u.username, u.name AS creator_name, u.avatar AS creator_avatar, u.is_verified
                     FROM reels r
                     INNER JOIN users u ON r.user_id = u.id
                     INNER JOIN followers f ON f.following_id = r.user_id
                     WHERE r.status = 'published' AND f.follower_id = ?
                     ORDER BY r.created_at DESC
                     LIMIT 10",
                    [$user['id']]
                );
            } catch (\Exception $e) {
                $posts = [];
                $reels = [];
            }
        }

        return $this->view('feed.index', [
            'posts' => $posts,
            'reels' => $reels,
            'user' => $user,
            'subscriptions' => true,
        ]);
    }
}
