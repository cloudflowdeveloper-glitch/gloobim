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
