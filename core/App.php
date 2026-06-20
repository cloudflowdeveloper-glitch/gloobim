<?php

namespace Core;

class App
{
    protected static string $basePath;

    public static function setBasePath(string $path): void
    {
        static::$basePath = rtrim($path, '/\\');
    }

    public static function getBasePath(): string
    {
        return static::$basePath;
    }

    public static function bootstrap(): void
    {
        Session::start();
        Config::load(static::$basePath . '/config');

        static::autoImportDatabase();

        try {
            $dbConfig = Config::get('database');
            if ($dbConfig) {
                Database::connect($dbConfig['connections'][$dbConfig['default']] ?? []);
            }
        } catch (\Exception $e) {
            error_log('Database connection failed: ' . $e->getMessage());
        }

        // Run data migrations to fix any remaining issues in existing databases
        static::runDataMigrations();

        static::registerMiddleware();
    }

    protected static function autoImportDatabase(): void
    {
        @set_time_limit(120);

        $flagFile = static::$basePath . '/storage/database.imported';
        $dbName = getenv('DB_DATABASE') ?: 'dttube';
        $dbHost = getenv('DB_HOST') ?: '127.0.0.1';
        $dbPort = (int) (getenv('DB_PORT') ?: '3306');
        $dbUser = getenv('DB_USERNAME') ?: 'root';
        $dbPass = getenv('DB_PASSWORD') ?: '';

        if (file_exists($flagFile)) {
            $testConn = @new \mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
            if (!$testConn->connect_error) {
                $testResult = $testConn->query("SELECT COUNT(*) AS cnt FROM information_schema.tables WHERE table_schema = '{$dbName}'");
                if ($testResult) {
                    $testRow = $testResult->fetch_assoc();
                    if ((int)$testRow['cnt'] > 0) {
                        $testConn->close();
                        return;
                    }
                }
            }
            $testConn->close();
        }

        $conn = null;
        try {
            $conn = new \mysqli($dbHost, $dbUser, $dbPass, '', $dbPort);
            if ($conn->connect_error) {
                throw new \Exception("MySQL connection failed: " . $conn->connect_error);
            }
            $conn->set_charset('utf8mb4');

            $conn->query("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            $result = $conn->query("SELECT COUNT(*) AS cnt FROM information_schema.tables WHERE table_schema = '{$dbName}'");
            $row = $result->fetch_assoc();
            $tableCount = (int) $row['cnt'];

            if ($tableCount === 0) {
                $conn->query("DROP DATABASE IF EXISTS `{$dbName}`");
                $conn->query("CREATE DATABASE `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            }

            $conn->select_db($dbName);

            if ($tableCount === 0) {
                $sqlFile = static::$basePath . '/database/dttube.sql';
                if (file_exists($sqlFile)) {
                    $sql = file_get_contents($sqlFile);
                    $sql = preg_replace('/^CREATE DATABASE.*?;/ms', '', $sql);
                    $sql = preg_replace('/^USE.*?;/ms', '', $sql);

                    $statements = array_filter(
                        array_map('trim', explode(';', $sql)),
                        fn($s) => !empty($s) && $s !== ';'
                    );

                    foreach ($statements as $statement) {
                        try {
                            $conn->query($statement);
                        } catch (\Exception $e) {
                            error_log("SQL Import Warning: " . $e->getMessage());
                        }
                    }

                    error_log("DTTube: Database imported successfully ({$dbName})");
                }

                $seedFile = static::$basePath . '/database/dttube_seed.sql';
                if (file_exists($seedFile)) {
                    $seedSql = file_get_contents($seedFile);
                    $seedSql = preg_replace('/^USE.*?;/ms', '', $seedSql);
                    $seedStatements = array_filter(
                        array_map('trim', explode(';', $seedSql)),
                        fn($s) => !empty($s) && $s !== ';'
                    );
                    foreach ($seedStatements as $stmt) {
                        try {
                            $conn->query($stmt);
                        } catch (\Exception $e) {
                            error_log("SQL Seed Warning: " . $e->getMessage());
                        }
                    }
                    error_log("DTTube: Seed data imported successfully");
                }
            }

            $storageDir = dirname($flagFile);
            if (!is_dir($storageDir)) {
                @mkdir($storageDir, 0755, true);
            }

            try {
                $colCheck = $conn->query("SHOW COLUMNS FROM `{$dbName}`.`users` LIKE 'country_code'");
                if ($colCheck && $colCheck->num_rows === 0) {
                    $conn->query("ALTER TABLE `users` ADD COLUMN `country_code` VARCHAR(5) DEFAULT NULL AFTER `is_banned`");
                }
            } catch (\Exception $e) {
                error_log("Migration warning: " . $e->getMessage());
            }

            // Auto-create spotlight_ads table if missing
            try {
                $tblCheck = $conn->query("SHOW TABLES LIKE 'spotlight_ads'");
                if (!$tblCheck || $tblCheck->num_rows === 0) {
                    $conn->query("CREATE TABLE `spotlight_ads` (
                        `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                        `title` varchar(255) NOT NULL,
                        `subtitle` text DEFAULT NULL,
                        `image_url` varchar(500) NOT NULL,
                        `link_url` varchar(500) DEFAULT NULL,
                        `badge` varchar(50) DEFAULT 'Ad',
                        `badge_color` varchar(20) DEFAULT '#834ae5',
                        `sort_order` int(11) DEFAULT 0,
                        `is_active` tinyint(1) DEFAULT 1,
                        `starts_at` datetime DEFAULT NULL,
                        `ends_at` datetime DEFAULT NULL,
                        `created_at` datetime DEFAULT current_timestamp(),
                        `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                        PRIMARY KEY (`id`),
                        KEY `idx_active_sort` (`is_active`,`sort_order`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

                    $conn->query("INSERT INTO `spotlight_ads` (`title`, `subtitle`, `image_url`, `link_url`, `badge`, `badge_color`, `sort_order`, `is_active`) VALUES
                        ('Shop the Latest Drops', 'Exclusive deals on trending products', '/uploads/home/card_1.jpg', '/marketplace', 'Shop', '#22c55e', 1, 1),
                        ('Go Live & Earn', 'Start streaming and receive gifts from fans', '/uploads/home/card_2.jpg', '/livestream/start', 'Stream', '#ef4444', 2, 1),
                        ('Discover New Music', 'Stream the hottest Afrobeats tracks', '/uploads/home/card_3.jpg', '/music', 'Music', '#ec4899', 3, 1)");
                    error_log("Globiim: spotlight_ads table created and seeded");
                }
            } catch (\Exception $e) {
                error_log("Spotlight ads migration warning: " . $e->getMessage());
            }

            $uploadDirs = [
                static::$basePath . '/public/uploads',
                static::$basePath . '/public/uploads/reels',
                static::$basePath . '/public/uploads/videos',
                static::$basePath . '/public/uploads/avatars',
                static::$basePath . '/public/uploads/thumbnails',
                static::$basePath . '/public/uploads/posts',
                static::$basePath . '/public/uploads/gifts',
                static::$basePath . '/public/uploads/marketplace',
                static::$basePath . '/public/uploads/market',
                static::$basePath . '/public/uploads/market/files',
            ];
            foreach ($uploadDirs as $dir) {
                if (!is_dir($dir)) {
                    @mkdir($dir, 0755, true);
                }
            }

            @file_put_contents($flagFile, date('Y-m-d H:i:s'));

        } catch (\Exception $e) {
            error_log("DTTube: Database auto-import failed: " . $e->getMessage());
        } finally {
            if ($conn) {
                $conn->close();
            }
        }
    }

    /**
     * Run data-level migrations on existing databases (e.g. fix stale URLs).
     * Uses a per-migration flag so each runs at most once.
     */
    protected static function runDataMigrations(): void
    {
        $migrationsDir = static::$basePath . '/storage/migrations';
        if (!is_dir($migrationsDir)) {
            @mkdir($migrationsDir, 0755, true);
        }

        // Migration: Replace any remaining placehold.co URLs with local /uploads/ paths
        static::runMigration('fix_placehold_urls_v2', function () {
            $tables = [
                ['table' => 'marketplace_listings', 'column' => 'image_url', 'map' => [
                    'https://placehold.co/400x400/e2e8f0/64748b?text=iPhone' => '/uploads/marketplace/product_iphone.jpg',
                    'https://placehold.co/400x400/e2e8f0/64748b?text=Headphones' => '/uploads/marketplace/product_headphones.jpg',
                    'https://placehold.co/400x400/e2e8f0/64748b?text=Laptop' => '/uploads/marketplace/product_laptop.jpg',
                    'https://placehold.co/400x400/e2e8f0/64748b?text=Watch' => '/uploads/marketplace/product_watch.jpg',
                    'https://placehold.co/400x400/e2e8f0/64748b?text=Sneakers' => '/uploads/marketplace/product_sneakers.jpg',
                    'https://placehold.co/400x400/e2e8f0/64748b?text=Camera' => '/uploads/marketplace/product_camera.jpg',
                    'https://placehold.co/400x400/e2e8f0/64748b?text=Sunglasses' => '/uploads/marketplace/product_sunglasses.jpg',
                    'https://placehold.co/400x400/e2e8f0/64748b?text=Speaker' => '/uploads/marketplace/product_speaker.jpg',
                    'https://placehold.co/400x400/e2e8f0/64748b?text=Jacket' => '/uploads/marketplace/product_jacket.jpg',
                    'https://placehold.co/400x400/e2e8f0/64748b?text=Tablet' => '/uploads/marketplace/product_tablet.jpg',
                    'https://placehold.co/400x400/e2e8f0/64748b?text=Chair' => '/uploads/marketplace/product_chair.jpg',
                    'https://placehold.co/400x400/e2e8f0/64748b?text=Backpack' => '/uploads/marketplace/product_backpack.jpg',
                ]],
                ['table' => 'users', 'column' => 'avatar', 'map' => [
                    'https://placehold.co/200x200/8b5cf6/ffffff?text=A' => '/uploads/profiles/admin.jpg',
                    'https://placehold.co/200x200/8b5cf6/ffffff?text=Z' => '/uploads/profiles/zarake.jpg',
                    'https://placehold.co/200x200/8b5cf6/ffffff?text=M' => '/uploads/profiles/marcustech.jpg',
                    'https://placehold.co/200x200/8b5cf6/ffffff?text=N' => '/uploads/profiles/aminabeauty.jpg',
                    'https://placehold.co/200x200/8b5cf6/ffffff?text=C' => '/uploads/profiles/chefkwame.jpg',
                    'https://placehold.co/200x200/8b5cf6/ffffff?text=D' => '/uploads/profiles/djpulse.jpg',
                    'https://placehold.co/200x200/8b5cf6/ffffff?text=S' => '/uploads/profiles/fitsarah.jpg',
                    'https://placehold.co/200x200/8b5cf6/ffffff?text=T' => '/uploads/profiles/traveldave.jpg',
                ]],
                ['table' => 'reels', 'column' => 'thumbnail', 'map' => [
                    'https://placehold.co/400x700/e2e8f0/64748b?text=Reel+1' => '/uploads/thumbnails/reel_thumb_1.jpg',
                    'https://placehold.co/400x700/e2e8f0/64748b?text=Reel+2' => '/uploads/thumbnails/reel_thumb_2.jpg',
                    'https://placehold.co/400x700/e2e8f0/64748b?text=Reel+3' => '/uploads/thumbnails/reel_thumb_3.jpg',
                    'https://placehold.co/400x700/e2e8f0/64748b?text=Reel+4' => '/uploads/thumbnails/reel_thumb_4.jpg',
                    'https://placehold.co/400x700/e2e8f0/64748b?text=Reel+5' => '/uploads/thumbnails/reel_thumb_5.jpg',
                    'https://placehold.co/400x700/e2e8f0/64748b?text=Reel+6' => '/uploads/thumbnails/reel_thumb_6.jpg',
                    'https://placehold.co/400x700/e2e8f0/64748b?text=Reel+7' => '/uploads/thumbnails/reel_thumb_7.jpg',
                    'https://placehold.co/400x700/e2e8f0/64748b?text=Reel+8' => '/uploads/thumbnails/reel_thumb_8.jpg',
                ]],
                ['table' => 'videos', 'column' => 'thumbnail', 'map' => [
                    'https://placehold.co/640x360/e2e8f0/64748b?text=Video+1' => '/uploads/thumbnails/reel_thumb_1.jpg',
                    'https://placehold.co/640x360/e2e8f0/64748b?text=Video+2' => '/uploads/thumbnails/reel_thumb_2.jpg',
                    'https://placehold.co/640x360/e2e8f0/64748b?text=Video+3' => '/uploads/thumbnails/reel_thumb_3.jpg',
                    'https://placehold.co/640x360/e2e8f0/64748b?text=Video+4' => '/uploads/thumbnails/reel_thumb_4.jpg',
                    'https://placehold.co/640x360/e2e8f0/64748b?text=Video+5' => '/uploads/thumbnails/reel_thumb_5.jpg',
                    'https://placehold.co/640x360/e2e8f0/64748b?text=Video+6' => '/uploads/thumbnails/reel_thumb_6.jpg',
                    'https://placehold.co/640x360/e2e8f0/64748b?text=Video+7' => '/uploads/thumbnails/reel_thumb_7.jpg',
                    'https://placehold.co/640x360/e2e8f0/64748b?text=Video+8' => '/uploads/thumbnails/reel_thumb_8.jpg',
                ]],
                ['table' => 'livestreams', 'column' => 'thumbnail', 'map' => [
                    'https://placehold.co/400x225/e2e8f0/64748b?text=Live+1' => '/uploads/livestreams/live_1.jpg',
                    'https://placehold.co/400x225/e2e8f0/64748b?text=Live+2' => '/uploads/livestreams/live_2.jpg',
                    'https://placehold.co/400x225/e2e8f0/64748b?text=Live+3' => '/uploads/livestreams/live_3.jpg',
                    'https://placehold.co/400x225/e2e8f0/64748b?text=Live+4' => '/uploads/livestreams/live_4.jpg',
                    'https://placehold.co/400x225/e2e8f0/64748b?text=Live+5' => '/uploads/livestreams/live_5.jpg',
                    'https://placehold.co/400x225/e2e8f0/64748b?text=Live+6' => '/uploads/livestreams/live_6.jpg',
                ]],
                ['table' => 'marketplace_categories', 'column' => 'cover_url', 'map' => [
                    'https://placehold.co/200x200/e2e8f0/64748b?text=Electronics' => '/uploads/marketplace/electronics.jpg',
                    'https://placehold.co/200x200/e2e8f0/64748b?text=Fashion' => '/uploads/marketplace/fashion.jpg',
                    'https://placehold.co/200x200/e2e8f0/64748b?text=Home' => '/uploads/marketplace/home.jpg',
                    'https://placehold.co/200x200/e2e8f0/64748b?text=Beauty' => '/uploads/marketplace/beauty.jpg',
                    'https://placehold.co/200x200/e2e8f0/64748b?text=Sports' => '/uploads/marketplace/sports.jpg',
                    'https://placehold.co/200x200/e2e8f0/64748b?text=Gaming' => '/uploads/marketplace/gaming.jpg',
                    'https://placehold.co/200x200/e2e8f0/64748b?text=Books' => '/uploads/marketplace/books.jpg',
                    'https://placehold.co/200x200/e2e8f0/64748b?text=Auto' => '/uploads/marketplace/auto.jpg',
                ]],
                ['table' => 'posts', 'column' => 'image_url', 'map' => [
                    'https://placehold.co/600x400/e2e8f0/64748b?text=Post+1' => '/uploads/posts/post_1.jpg',
                    'https://placehold.co/600x400/e2e8f0/64748b?text=Post+2' => '/uploads/posts/post_2.jpg',
                    'https://placehold.co/600x400/e2e8f0/64748b?text=Post+3' => '/uploads/posts/post_3.jpg',
                    'https://placehold.co/600x400/e2e8f0/64748b?text=Post+4' => '/uploads/posts/post_4.jpg',
                    'https://placehold.co/600x400/e2e8f0/64748b?text=Post+5' => '/uploads/posts/post_5.jpg',
                    'https://placehold.co/600x400/e2e8f0/64748b?text=Post+6' => '/uploads/posts/post_6.jpg',
                ]],
            ];

            $totalFixed = 0;
            foreach ($tables as $item) {
                $table = $item['table'];
                $col = $item['column'];
                // Check if table and column exist
                try {
                    $colCheck = Database::query("SHOW COLUMNS FROM `{$table}` LIKE '{$col}'");
                    if (empty($colCheck)) continue;
                } catch (\Exception $e) {
                    continue; // Table doesn't exist, skip
                }

                foreach ($item['map'] as $oldUrl => $newUrl) {
                    try {
                        $result = Database::execute(
                            "UPDATE `{$table}` SET `{$col}` = ? WHERE `{$col}` = ?",
                            [$newUrl, $oldUrl]
                        );
                        $totalFixed++;
                    } catch (\Exception $e) {
                        // Skip individual failures
                    }
                }

                // Also catch any generic placehold.co URLs we didn't map specifically
                try {
                    $genericRows = Database::query(
                        "SELECT id, `{$col}` FROM `{$table}` WHERE `{$col}` LIKE '%placehold.co%' AND `{$col}` NOT LIKE '/uploads/%' LIMIT 50"
                    );
                    if (!empty($genericRows)) {
                        // For marketplace_listings, map by ID to our generated images
                        if ($table === 'marketplace_listings') {
                            $productImages = [
                                1 => '/uploads/marketplace/product_iphone.jpg',
                                2 => '/uploads/marketplace/product_headphones.jpg',
                                3 => '/uploads/marketplace/product_laptop.jpg',
                                4 => '/uploads/marketplace/product_watch.jpg',
                                5 => '/uploads/marketplace/product_sneakers.jpg',
                                6 => '/uploads/marketplace/product_camera.jpg',
                                7 => '/uploads/marketplace/product_sunglasses.jpg',
                                8 => '/uploads/marketplace/product_speaker.jpg',
                                9 => '/uploads/marketplace/product_jacket.jpg',
                                10 => '/uploads/marketplace/product_tablet.jpg',
                                11 => '/uploads/marketplace/product_chair.jpg',
                                12 => '/uploads/marketplace/product_backpack.jpg',
                            ];
                            foreach ($genericRows as $row) {
                                $id = (int)$row['id'];
                                $localImg = $productImages[$id] ?? '/uploads/marketplace/product_iphone.jpg';
                                Database::execute("UPDATE `{$table}` SET `{$col}` = ? WHERE id = ?", [$localImg, $id]);
                                $totalFixed++;
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // Skip
                }
            }

            error_log("Globiim: placehold.co URL migration completed ({$totalFixed} replacements checked)");
            return true;
        });

        // Migration: Seed more videos and reels from different users
        static::runMigration('seed_videos_reels_v2', function () {
            // Check if videos from other users already exist
            $existingOther = Database::queryOne("SELECT COUNT(*) AS c FROM videos WHERE user_id > 2 AND status = 'published'");
            if ($existingOther && (int)$existingOther['c'] > 0) return true;

            $now = date('Y-m-d H:i:s');
            $videos = [
                // Marcus Tech (user 3) - Tech reviews
                [3, 'Samsung Galaxy S25 Ultra Review - Is It Worth It?', 'Full review of Samsung\'s latest flagship after 30 days of daily use', '/uploads/thumbnails/reel_thumb_3.jpg', '', NULL, 1120, 340000, 18200, 890, 4200, 'Tech', 'published', 1, 1, 78.2],
                [3, 'Top 5 Gadgets Under KES 10,000 in 2025', 'Budget tech that punches way above its price', '/uploads/thumbnails/reel_thumb_1.jpg', '', NULL, 845, 560000, 28700, 1200, 6300, 'Tech', 'published', 1, 1, 81.5],
                // Amina Beauty (user 4) - Beauty
                [4, 'Get This Glow: 5-Minute Makeup Routine', 'Quick makeup routine for busy African women', '/uploads/thumbnails/reel_thumb_7.jpg', '', NULL, 420, 890000, 45600, 2300, 8900, 'Beauty', 'published', 0, 1, 72.8],
                [4, 'Natural Hair Care Mistakes You Are Making', 'Stop damaging your hair with these common mistakes', '/uploads/thumbnails/reel_thumb_4.jpg', '', NULL, 680, 1200000, 62300, 3400, 12000, 'Beauty', 'published', 1, 1, 85.3],
                // Chef Kwame (user 5) - Food
                [5, 'How to Make Perfect Jollof Rice Every Time', 'The ultimate Jollof rice recipe - no more debates needed!', '/uploads/thumbnails/reel_thumb_2.jpg', '', NULL, 920, 2800000, 145000, 8900, 34000, 'Food', 'published', 1, 1, 93.7],
                [5, 'Nigerian Suya vs Ghanaian Chichinga - Taste Test', 'Which street food wins? We try both!', '/uploads/thumbnails/reel_thumb_5.jpg', '', NULL, 780, 1900000, 89000, 5600, 18000, 'Food', 'published', 0, 1, 79.4],
                // DJ Pulse (user 6) - Music
                [6, 'Making a Hit Afrobeats Track in 30 Minutes', 'Watch me produce a complete track from scratch', '/uploads/thumbnails/reel_thumb_5.jpg', '', NULL, 1800, 4200000, 210000, 12000, 45000, 'Music', 'published', 1, 1, 95.1],
                [6, 'Top 10 Afrobeats Songs of 2025 So Far', 'The biggest Afrobeats bangers this year', '/uploads/thumbnails/reel_thumb_8.jpg', '', NULL, 2400, 5600000, 320000, 18000, 67000, 'Music', 'published', 1, 1, 97.8],
                // Fit Sarah (user 7) - Fitness
                [7, 'Full Body Workout - No Equipment Needed', 'Perfect home workout for all fitness levels', '/uploads/thumbnails/reel_thumb_6.jpg', '', NULL, 1200, 1500000, 78000, 4500, 21000, 'Education', 'published', 1, 1, 84.6],
                [7, '30-Day Transformation Challenge Results', 'Real results from our community challenge', '/uploads/thumbnails/reel_thumb_3.jpg', '', NULL, 900, 2100000, 112000, 6700, 28000, 'Education', 'published', 0, 1, 89.2],
                // Travel Dave (user 8) - Travel
                [8, 'Zanzibar on a Budget - Complete Travel Guide', 'Everything you need to know before visiting Zanzibar', '/uploads/thumbnails/reel_thumb_1.jpg', '', NULL, 1560, 890000, 45000, 2300, 12000, 'Travel', 'published', 1, 1, 76.3],
                [8, 'Safari in Maasai Mara - What They Don\'t Tell You', 'The truth about going on safari in Kenya', '/uploads/thumbnails/reel_thumb_7.jpg', '', NULL, 2100, 1200000, 67000, 3400, 18000, 'Travel', 'published', 0, 1, 82.1],
                // Zara Ke (user 2) - More content
                [2, 'Behind the Scenes of My Latest Music Video', 'The making of my biggest video yet', '/uploads/thumbnails/reel_thumb_8.jpg', '', NULL, 1450, 780000, 42000, 2100, 9800, 'Music', 'published', 1, 1, 71.6],
                [2, 'Day in My Life as a Kenyan Content Creator', 'Follow me for a full day in Nairobi', '/uploads/thumbnails/reel_thumb_6.jpg', '', NULL, 1100, 1500000, 78000, 4500, 21000, 'Lifestyle', 'published', 0, 1, 78.9],
            ];

            foreach ($videos as $v) {
                Database::insert('videos', [
                    'user_id' => $v[0],
                    'title' => $v[1],
                    'description' => $v[2],
                    'thumbnail' => $v[3],
                    'video_url' => $v[4],
                    'duration' => $v[6],
                    'views' => $v[7],
                    'likes' => $v[8],
                    'comments_count' => $v[9],
                    'shares' => $v[10],
                    'category' => $v[11],
                    'status' => $v[12],
                    'is_featured' => $v[13],
                    'is_monetized' => $v[14],
                    'viral_score' => $v[15],
                    'published_at' => $now,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days')),
                    'updated_at' => $now,
                ]);
            }

            // Seed more reels from different users
            $reels = [
                // Marcus Tech
                [3, 'Unboxing the New MacBook Pro M4', 'Apple just dropped this beast!', '/uploads/thumbnails/reel_thumb_1.jpg', '', 25, 450000, 23000, 1200, 4500, 'Tech Review - Audio', NULL, 'Tech', 'published', 1, 76.4],
                [3, 'This Phone Camera is INSANE', 'You won\'t believe these sample photos', '/uploads/thumbnails/reel_thumb_4.jpg', '', 35, 780000, 41000, 2800, 9200, 'Original Sound', NULL, 'Tech', 'published', 0, 71.2],
                // Amina Beauty
                [4, 'Healthy Skin in 7 Days Challenge', 'Day 1 of my skincare challenge', '/uploads/thumbnails/reel_thumb_7.jpg', '', 40, 620000, 32000, 1800, 5600, 'Soft Vibes', NULL, 'Beauty', 'published', 1, 68.5],
                [4, 'Makeup Transformation for African Skin', 'From bare face to GLAM in 60 seconds', '/uploads/thumbnails/reel_thumb_2.jpg', '', 60, 980000, 51000, 3200, 11000, 'Trending Sound', NULL, 'Beauty', 'published', 1, 82.1],
                // Chef Kwame
                [5, 'Street Food in Lagos - Best Spots!', 'Found the BEST amala and ewedu in Lagos', '/uploads/thumbnails/reel_thumb_5.jpg', '', 45, 1800000, 95000, 5600, 21000, 'Afro Kitchen Vibes', NULL, 'Food', 'published', 1, 88.7],
                [5, 'Making Fufu from Scratch', 'My grandmother\'s recipe that never fails', '/uploads/thumbnails/reel_thumb_3.jpg', '', 50, 1200000, 62000, 3400, 15000, 'Original Sound', NULL, 'Food', 'published', 0, 75.3],
                // DJ Pulse
                [6, 'DJ Set at Nairobi Carnival 2025', 'The crowd went CRAZY when this dropped', '/uploads/thumbnails/reel_thumb_8.jpg', '', 30, 3200000, 165000, 8900, 34000, 'My Mix - DJ Pulse', NULL, 'Music', 'published', 1, 92.4],
                [6, 'Teaching My Grandma to DJ', 'She actually killed it!', '/uploads/thumbnails/reel_thumb_6.jpg', '', 28, 4500000, 230000, 12000, 56000, 'Comedy Mix', NULL, 'Comedy', 'published', 1, 96.1],
                // Fit Sarah
                [7, '100 Squats a Day for 30 Days', 'Watch my legs transform over a month', '/uploads/thumbnails/reel_thumb_6.jpg', '', 22, 890000, 46000, 2800, 12000, 'Workout Mix', NULL, 'Education', 'published', 0, 69.8],
                [7, 'African Dance Workout - Burn 500 Calories', 'Fun workout with Afrobeat music', '/uploads/thumbnails/reel_thumb_1.jpg', '', 35, 2100000, 110000, 6700, 28000, 'Afro Dance Mix', NULL, 'Education', 'published', 1, 86.5],
                // Travel Dave
                [8, 'Victoria Falls from Zimbabwe Side', 'This view is UNREAL', '/uploads/thumbnails/reel_thumb_7.jpg', '', 40, 1500000, 78000, 4500, 18000, 'Nature Sounds', NULL, 'Travel', 'published', 1, 81.2],
                [8, 'Cheapest Flight to Dubai - How I Did It', 'Travel hacking tips for Africans', '/uploads/thumbnails/reel_thumb_3.jpg', '', 30, 980000, 51000, 3200, 14000, 'Travel Vlog Sound', NULL, 'Travel', 'published', 0, 74.6],
                // More from Zara Ke
                [2, 'Amapiano Dance Tutorial - Beginner Friendly', 'Learn the basics in under a minute', '/uploads/thumbnails/reel_thumb_4.jpg', '', 45, 1800000, 95000, 5600, 21000, 'Amapiano Beat', NULL, 'Dance', 'published', 1, 84.9],
                [2, 'Morning Routine as a Creator in Nairobi', 'How I start my day for maximum productivity', '/uploads/thumbnails/reel_thumb_2.jpg', '', 55, 780000, 41000, 2100, 8900, 'Chill Morning Vibes', NULL, 'Lifestyle', 'published', 0, 67.3],
            ];

            foreach ($reels as $r) {
                Database::insert('reels', [
                    'user_id' => $r[0],
                    'title' => $r[1],
                    'description' => $r[2],
                    'thumbnail' => $r[3],
                    'video_url' => $r[4],
                    'duration' => $r[5],
                    'views' => $r[6],
                    'likes' => $r[7],
                    'comments_count' => $r[8],
                    'shares' => $r[9],
                    'song_name' => $r[10],
                    'song_url' => $r[11],
                    'category' => $r[12],
                    'status' => $r[13],
                    'is_featured' => $r[14],
                    'viral_score' => $r[15],
                    'published_at' => $now,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-' . rand(1, 20) . ' days')),
                    'updated_at' => $now,
                ]);
            }

            // Seed wallet transactions for revenue data (for user 1 - admin)
            $wallet = Database::queryOne("SELECT id FROM wallets WHERE user_id = 1 LIMIT 1");
            if ($wallet) {
                $wid = $wallet['id'];
                $transactions = [
                    ['gift_received', 5000.00, 'Gift from @zarake on reel'],
                    ['gift_received', 3200.00, 'Gift from @djpulse on stream'],
                    ['gift_received', 1800.00, 'Gift from @marcustech'],
                    ['earnings', 8500.00, 'Ad revenue for June 2025'],
                    ['earnings', 6200.00, 'Ad revenue for May 2025'],
                    ['tip', 2100.00, 'Tip from @chefkwame on video'],
                    ['tip', 1500.00, 'Tip from @fitsarah'],
                    ['subscription', 4000.00, 'Subscription revenue'],
                ];
                foreach ($transactions as $i => $tx) {
                    Database::insert('wallet_transactions', [
                        'wallet_id' => $wid,
                        'type' => $tx[0],
                        'amount' => $tx[1],
                        'currency' => 'KES',
                        'status' => 'completed',
                        'reference' => 'REV-' . strtoupper(substr(md5(uniqid()), 0, 10)),
                        'description' => $tx[2],
                        'created_at' => date('Y-m-d H:i:s', strtotime('-' . ($i * 2 + 1) . ' days')),
                    ]);
                }
                // Update wallet balance to match total revenue
                Database::execute("UPDATE wallets SET balance = 32300.00, updated_at = NOW() WHERE id = ?", [$wid]);
            }

            // Also add some revenue for user 2 (Zara Ke)
            $wallet2 = Database::queryOne("SELECT id FROM wallets WHERE user_id = 2 LIMIT 1");
            if ($wallet2) {
                $wid = $wallet2['id'];
                $transactions2 = [
                    ['gift_received', 12500.00, 'Gift from fan on dance reel'],
                    ['gift_received', 8900.00, 'Gift from @traveldave on live'],
                    ['earnings', 15000.00, 'Video ad revenue June 2025'],
                    ['earnings', 11200.00, 'Video ad revenue May 2025'],
                    ['tip', 4500.00, 'Tip from @aminabeauty'],
                    ['subscription', 6000.00, 'Subscription earnings'],
                ];
                foreach ($transactions2 as $i => $tx) {
                    Database::insert('wallet_transactions', [
                        'wallet_id' => $wid,
                        'type' => $tx[0],
                        'amount' => $tx[1],
                        'currency' => 'KES',
                        'status' => 'completed',
                        'reference' => 'ZK-REV-' . strtoupper(substr(md5(uniqid()), 0, 10)),
                        'description' => $tx[2],
                        'created_at' => date('Y-m-d H:i:s', strtotime('-' . ($i * 3 + 1) . ' days')),
                    ]);
                }
                Database::execute("UPDATE wallets SET balance = 78140.00, updated_at = NOW() WHERE id = ?", [$wid]);
            }

            error_log("Globiim: Videos/reels/wallet_transactions seeded successfully");
            return true;
        });

        // Migration: Add actor_id and link_url columns to notifications table
        static::runMigration('add_notification_actor_id', function () {
            // Add actor_id column if it doesn't exist
            try {
                Database::execute("ALTER TABLE notifications ADD COLUMN actor_id BIGINT UNSIGNED NULL AFTER user_id");
            } catch (\Exception $e) {
                // Column may already exist, ignore
            }

            // Add link_url column if it doesn't exist
            try {
                Database::execute("ALTER TABLE notifications ADD COLUMN link_url VARCHAR(500) NULL AFTER data");
            } catch (\Exception $e) {
                // Column may already exist, ignore
            }

            // Backfill actor_id from JSON data column
            $rows = Database::query("SELECT id, data FROM notifications WHERE actor_id IS NULL");
            foreach ($rows as $row) {
                $actorId = null;
                $data = $row['data'] ?? null;
                if ($data) {
                    $parsed = json_decode($data, true);
                    if (is_array($parsed)) {
                        $actorId = $parsed['follower_id'] ?? $parsed['from_user_id'] ?? $parsed['actor_id'] ?? null;
                    }
                }
                if ($actorId) {
                    Database::execute("UPDATE notifications SET actor_id = ? WHERE id = ?", [(int)$actorId, (int)$row['id']]);
                }
            }

            error_log("Globiim: add_notification_actor_id migration completed");
            return true;
        });

        // Migration: Fix currency symbols that were corrupted
        static::runMigration('fix_currency_symbols', function () {
            $fixes = [
                'EU' => '€',
                'GB' => '£',
                'GH' => 'GH₵',
                'IN' => '₹',
                'NG' => '₦',
            ];
            foreach ($fixes as $code => $symbol) {
                Database::execute(
                    "UPDATE country_currencies SET currency_symbol = ? WHERE country_code = ?",
                    [$symbol, $code]
                );
            }
            error_log("Globiim: fix_currency_symbols migration completed");
            return true;
        });
    }

    /**
     * Run a named migration only once (tracked by flag file).
     */
    protected static function runMigration(string $name, callable $callback): void
    {
        $flagFile = static::$basePath . '/storage/migrations/' . $name . '.done';
        if (file_exists($flagFile)) {
            return;
        }

        try {
            $result = $callback();
            if ($result) {
                $dir = dirname($flagFile);
                if (!is_dir($dir)) {
                    @mkdir($dir, 0755, true);
                }
                @file_put_contents($flagFile, date('Y-m-d H:i:s') . "\n");
                error_log("Globiim: Migration '{$name}' completed successfully");
            }
        } catch (\Exception $e) {
            error_log("Globiim: Migration '{$name}' failed: " . $e->getMessage());
        }
    }

    protected static function registerMiddleware(): void
    {
        Router::registerMiddleware('auth', \App\Http\Middleware\AuthMiddleware::class);
        Router::registerMiddleware('guest', \App\Http\Middleware\GuestMiddleware::class);
        Router::registerMiddleware('admin', \App\Http\Middleware\AdminMiddleware::class);
        Router::registerMiddleware('rate.limit', \App\Http\Middleware\RateLimitMiddleware::class);
    }

    public static function run(): void
    {
        static::bootstrap();

        $routesPath = static::$basePath . '/routes/web.php';
        if (file_exists($routesPath)) {
            require $routesPath;
        }

        $apiRoutesPath = static::$basePath . '/routes/api.php';
        if (file_exists($apiRoutesPath)) {
            require $apiRoutesPath;
        }

        $request = new Request();
        $response = Router::dispatch($request);

        session_write_close();

        $response->send();
    }
}
