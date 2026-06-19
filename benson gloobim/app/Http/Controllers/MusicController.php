<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\Response;
use Core\Database;
use Core\Auth;

class MusicController extends Controller
{
    // -----------------------------------------------------------------------
    // PAGES
    // -----------------------------------------------------------------------

    public function index(): Response
    {
        return $this->view('music.index', [
            'featured'    => $this->getFeatured(),
            'trending'    => $this->getTrending(),
            'playlists'   => $this->getPlaylists(),
            'genres'      => $this->getGenres(),
            'recent'      => $this->getRecent(),
            'top_artists' => $this->getTopArtists(),
            'made_for_you'=> $this->getMadeForYou(),
        ]);
    }

    public function upload(): Response
    {
        return $this->view('music.upload', ['genres' => $this->getAllGenres()]);
    }

    public function createPlaylist(): Response
    {
        return $this->view('music.playlist-create');
    }

    public function search(): Response
    {
        $q = trim($_GET['q'] ?? '');
        if (empty($q)) return $this->json(['tracks' => [], 'artists' => []]);

        $tracks = Database::query(
            "SELECT id, title, artist_name, artist_avatar, cover_url, audio_url, duration, plays, likes, is_verified
             FROM music_tracks WHERE status = 'published' AND (title LIKE ? OR artist_name LIKE ?)
             ORDER BY plays DESC LIMIT 20",
            ["%{$q}%", "%{$q}%"]
        ) ?: [];

        $artists = Database::query(
            "SELECT DISTINCT artist_name, artist_avatar FROM music_tracks
             WHERE status = 'published' AND artist_name LIKE ?
             ORDER BY plays DESC LIMIT 10",
            ["%{$q}%"]
        ) ?: [];

        return $this->json(['tracks' => $tracks, 'artists' => $artists]);
    }

    // -----------------------------------------------------------------------
    // ACTIONS (AJAX)
    // -----------------------------------------------------------------------

    /** Record a play/view */
    public function play($id): Response
    {
        try {
            Database::execute("UPDATE music_tracks SET plays = COALESCE(plays, 0) + 1 WHERE id = ?", [$id]);
            $t = Database::query("SELECT plays FROM music_tracks WHERE id = ?", [$id]);
            return $this->json(['plays' => (int)($t[0]['plays'] ?? 0)]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    /** Toggle like */
    public function like($id): Response
    {
        $user = Auth::user();
        if (!$user) return $this->json(['error' => 'Login required'], 401);

        try {
            $existing = Database::query(
                "SELECT id FROM likes WHERE user_id = ? AND likeable_type = 'track' AND likeable_id = ?",
                [$user['id'], $id]
            );

            if (!empty($existing)) {
                Database::execute("DELETE FROM likes WHERE id = ?", [$existing[0]['id']]);
                Database::execute("UPDATE music_tracks SET likes = GREATEST(COALESCE(likes, 0) - 1, 0) WHERE id = ?", [$id]);
                $liked = false;
            } else {
                Database::insert('likes', [
                    'user_id' => $user['id'],
                    'likeable_type' => 'track',
                    'likeable_id' => $id,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                Database::execute("UPDATE music_tracks SET likes = COALESCE(likes, 0) + 1 WHERE id = ?", [$id]);
                $liked = true;
            }

            $t = Database::query("SELECT likes FROM music_tracks WHERE id = ?", [$id]);
            return $this->json(['liked' => $liked, 'likes' => (int)($t[0]['likes'] ?? 0)]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    /** Record a share */
    public function share($id): Response
    {
        try {
            Database::execute("UPDATE music_tracks SET shares = COALESCE(shares, 0) + 1 WHERE id = ?", [$id]);
            $t = Database::query("SELECT shares FROM music_tracks WHERE id = ?", [$id]);
            return $this->json(['shares' => (int)($t[0]['shares'] ?? 0)]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    /** Store uploaded track */
    public function store(): Response
    {
        $user = Auth::user();
        if (!$user) return $this->json(['error' => 'Login required'], 401);

        $title     = trim($_POST['title'] ?? '');
        $artistName = trim($_POST['artist_name'] ?? $user['name']);
        $genreId   = (int)($_POST['genre_id'] ?? 0);
        $isExplicit = !empty($_POST['is_explicit']);

        if (empty($title)) return $this->json(['error' => 'Title is required'], 422);

        $basePath = BASE_PATH;
        $coverDir = $basePath . '/public/uploads/music/covers';
        $audioDir = $basePath . '/public/uploads/music/audio';
        foreach ([$coverDir, $audioDir] as $d) { if (!is_dir($d)) @mkdir($d, 0755, true); }

        $coverUrl = null;
        $audioUrl = null;
        $videoUrl = null;

        // Cover image
        if (!empty($_FILES['cover']['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));
            $fname = 'cover_' . $user['id'] . '_' . time() . '.' . $ext;
            $path = $coverDir . '/' . $fname;
            if (move_uploaded_file($_FILES['cover']['tmp_name'], $path)) {
                $coverUrl = '/uploads/music/covers/' . $fname;
            }
        }

        // Audio file
        if (!empty($_FILES['audio']['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES['audio']['name'], PATHINFO_EXTENSION));
            $fname = 'audio_' . $user['id'] . '_' . time() . '.' . $ext;
            $path = $audioDir . '/' . $fname;
            if (move_uploaded_file($_FILES['audio']['tmp_name'], $path)) {
                $audioUrl = '/uploads/music/audio/' . $fname;
            }
        }

        // Duration from ffprobe if available
        $duration = 0;
        if ($audioUrl && file_exists($basePath . '/public' . $audioUrl)) {
            $processor = new \App\Services\VideoProcessor();
            $duration = (int)round($processor->getDuration($basePath . '/public' . $audioUrl));
        }

        try {
            $trackId = Database::insert('music_tracks', [
                'artist_id'    => $user['id'],
                'title'        => $title,
                'artist_name'  => $artistName,
                'artist_avatar' => $user['avatar'] ?? null,
                'cover_url'    => $coverUrl,
                'audio_url'    => $audioUrl,
                'video_url'    => $videoUrl,
                'duration'     => $duration,
                'genre_id'     => $genreId > 0 ? $genreId : null,
                'is_explicit'  => $isExplicit ? 1 : 0,
                'is_verified'  => 0,
                'plays'        => 0,
                'likes'        => 0,
                'shares'       => 0,
                'status'       => 'published',
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ]);

            // Update genre track count
            if ($genreId > 0) {
                Database::execute("UPDATE music_genres SET track_count = COALESCE(track_count, 0) + 1 WHERE id = ?", [$genreId]);
            }

            return $this->json(['message' => 'Track uploaded!', 'track_id' => $trackId], 201);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    /** Create playlist */
    public function storePlaylist(): Response
    {
        $user = Auth::user();
        if (!$user) return $this->json(['error' => 'Login required'], 401);

        $name = trim($_POST['name'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        $trackIds = $_POST['track_ids'] ?? '';

        if (empty($name)) return $this->json(['error' => 'Name is required'], 422);

        $coverUrl = null;
        if (!empty($_FILES['cover']['tmp_name'])) {
            $coverDir = BASE_PATH . '/public/uploads/music/covers';
            if (!is_dir($coverDir)) @mkdir($coverDir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));
            $fname = 'pl_' . $user['id'] . '_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['cover']['tmp_name'], $coverDir . '/' . $fname)) {
                $coverUrl = '/uploads/music/covers/' . $fname;
            }
        }

        // Parse track IDs
        $trackCount = 0;
        if (!empty($trackIds)) {
            $ids = array_filter(array_map('intval', explode(',', $trackIds)));
            $trackCount = count($ids);
        }

        try {
            $plId = Database::insert('music_playlists', [
                'user_id'    => $user['id'],
                'name'       => $name,
                'description'=> $desc,
                'cover_url'  => $coverUrl,
                'track_count'=> $trackCount,
                'track_ids'  => $trackIds,
                'author_name'=> $user['name'],
                'followers'  => 0,
                'status'     => 'public',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return $this->json(['message' => 'Playlist created!', 'playlist_id' => $plId], 201);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    /** Get trending tracks JSON */
    public function trendingJson(): Response
    {
        return $this->json(['trending' => $this->getTrending()]);
    }

    /** Get track detail — renders show page */
    public function show($id): Response
    {
        try {
            $t = Database::query("SELECT * FROM music_tracks WHERE id = ? AND status = 'published'", [$id]);
            if (empty($t)) return $this->redirect('/music');
            return $this->view('music.show', ['track' => $t[0]]);
        } catch (\Exception $e) {
            return $this->redirect('/music');
        }
    }

    /** Show genre page with filtered tracks */
    public function genre($slug): Response
    {
        try {
            $genre = Database::query("SELECT id, name, color, icon FROM music_genres WHERE slug = ?", [$slug]);
            if (empty($genre)) return $this->redirect('/music');

            $tracks = Database::query(
                "SELECT * FROM music_tracks WHERE genre_id = ? AND status = 'published' ORDER BY plays DESC LIMIT 50",
                [$genre[0]['id']]
            ) ?: [];

            $allGenres = $this->getGenres();

            return $this->view('music.index', [
                'featured'    => $this->getFeatured(),
                'trending'    => $tracks,
                'recent'      => $tracks,
                'playlists'   => $this->getPlaylists(),
                'genres'      => $allGenres,
                'top_artists' => $this->getTopArtists(),
                'made_for_you'=> $tracks,
                'genre_filter'=> $genre[0]['name'],
            ]);
        } catch (\Exception $e) {
            return $this->redirect('/music');
        }
    }

    // -----------------------------------------------------------------------
    // DATA FETCHERS
    // -----------------------------------------------------------------------

    protected function getFeatured(): array
    {
        try {
            $results = Database::query("SELECT * FROM music_featured WHERE is_active = 1 ORDER BY created_at DESC LIMIT 1");
            return $results[0] ?? [
                'title' => 'African Heat 2025', 'description' => 'Hottest African tracks right now',
                'cover_url' => '/uploads/music/covers/featured_banner.jpg', 'author' => 'GLOOBIM Music', 'listeners' => 12400,
            ];
        } catch (\Exception $e) { return []; }
    }

    protected function getTrending(): array
    {
        try {
            return Database::query(
                "SELECT id, title, artist_name, artist_avatar, cover_url, audio_url, video_url,
                        duration, plays, likes, shares, is_verified, is_explicit
                 FROM music_tracks WHERE status = 'published'
                 ORDER BY plays DESC LIMIT 10"
            ) ?: [];
        } catch (\Exception $e) { return []; }
    }

    protected function getPlaylists(): array
    {
        try {
            return Database::query(
                "SELECT id, name, description, cover_url, track_count, author_name, followers
                 FROM music_playlists WHERE status = 'public' ORDER BY followers DESC LIMIT 6"
            ) ?: [];
        } catch (\Exception $e) { return []; }
    }

    protected function getGenres(): array
    {
        try {
            return Database::query(
                "SELECT id, name, slug, color, icon, cover_url, track_count
                 FROM music_genres WHERE is_active = 1 ORDER BY track_count DESC LIMIT 8"
            ) ?: [];
        } catch (\Exception $e) { return []; }
    }

    protected function getAllGenres(): array
    {
        try {
            return Database::query("SELECT id, name, slug, color, icon FROM music_genres WHERE is_active = 1 ORDER BY name") ?: [];
        } catch (\Exception $e) { return []; }
    }

    protected function getRecent(): array
    {
        try {
            return Database::query(
                "SELECT id, title, artist_name, artist_avatar, cover_url, audio_url, video_url,
                        duration, plays, likes, shares, is_verified, is_explicit
                 FROM music_tracks WHERE status = 'published'
                 ORDER BY created_at DESC LIMIT 10"
            ) ?: [];
        } catch (\Exception $e) { return []; }
    }

    protected function getTopArtists(): array
    {
        try {
            return Database::query(
                "SELECT DISTINCT artist_name, artist_avatar, SUM(plays) as total_plays
                 FROM music_tracks WHERE status = 'published' AND artist_name IS NOT NULL
                 GROUP BY artist_name, artist_avatar ORDER BY total_plays DESC LIMIT 6"
            ) ?: [];
        } catch (\Exception $e) { return []; }
    }

    protected function getMadeForYou(): array
    {
        $user = Auth::user();
        if (!$user) {
            // Guest: return trending tracks as suggestions
            return $this->getTrending();
        }

        try {
            // Get tracks from genres the user has liked
            $liked = Database::query(
                "SELECT DISTINCT mt.genre_id FROM music_tracks mt
                 INNER JOIN likes l ON l.likeable_id = mt.id AND l.likeable_type = 'track'
                 WHERE l.user_id = ? AND mt.genre_id IS NOT NULL LIMIT 5",
                [$user['id']]
            );

            if (!empty($liked)) {
                $genreIds = array_column($liked, 'genre_id');
                $placeholders = implode(',', array_fill(0, count($genreIds), '?'));
                return Database::query(
                    "SELECT id, title, artist_name, artist_avatar, cover_url, audio_url,
                            duration, plays, likes, shares, is_verified, is_explicit
                     FROM music_tracks WHERE status = 'published' AND genre_id IN ($placeholders)
                     ORDER BY plays DESC LIMIT 10",
                    $genreIds
                ) ?: $this->getTrending();
            }

            return $this->getTrending();
        } catch (\Exception $e) { return $this->getTrending(); }
    }
}
