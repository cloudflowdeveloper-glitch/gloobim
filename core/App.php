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
