<?php
/**
 * Migration: Create stories table
 * Stories auto-expire after 24 hours (enforced by expires_at column).
 * Run: php migrations/create_stories_table.php
 */

$basePath = dirname(__DIR__);
define('BASE_PATH', $basePath);

// Bootstrap core classes
require $basePath . '/core/Config.php';
require $basePath . '/core/Session.php';
require $basePath . '/core/Database.php';
require $basePath . '/core/Request.php';
require $basePath . '/core/Response.php';
require $basePath . '/core/Router.php';
require $basePath . '/core/View.php';
require $basePath . '/core/Controller.php';
require $basePath . '/core/Auth.php';
require $basePath . '/core/App.php';

// Composer autoloader
$autoloadPath = $basePath . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require $autoloadPath;
}

// Load .env
$envPath = $basePath . '/.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        if (!empty($key)) {
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
        }
    }
}

use Core\Database;

try {
    Database::connect();
    echo "✓ Connected to database.\n";

    $sql = "CREATE TABLE IF NOT EXISTS `stories` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `user_id` BIGINT UNSIGNED NOT NULL,
        `image_url` VARCHAR(500) NOT NULL COMMENT 'Path to uploaded story image',
        `text_content` VARCHAR(500) DEFAULT NULL COMMENT 'Text overlay on the story',
        `text_position` VARCHAR(20) DEFAULT 'center' COMMENT 'text position: top, center, bottom',
        `text_color` VARCHAR(7) DEFAULT '#ffffff' COMMENT 'Hex color of text overlay',
        `text_size` VARCHAR(10) DEFAULT '24' COMMENT 'Font size in px',
        `font_style` VARCHAR(20) DEFAULT 'normal' COMMENT 'normal, bold, italic',
        `background_color` VARCHAR(7) DEFAULT NULL COMMENT 'Optional background color',
        `views_count` INT UNSIGNED DEFAULT 0,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `expires_at` DATETIME NOT NULL COMMENT 'Auto-expire 24h after creation',
        `is_active` TINYINT(1) DEFAULT 1 COMMENT 'Soft flag for manual deletion',

        INDEX `idx_user` (`user_id`),
        INDEX `idx_expires_active` (`expires_at`, `is_active`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    Database::raw($sql);
    echo "✓ `stories` table created successfully.\n";

    // story_views table to track who viewed (prevents double-counting)
    $sql2 = "CREATE TABLE IF NOT EXISTS `story_views` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `story_id` BIGINT UNSIGNED NOT NULL,
        `viewer_id` BIGINT UNSIGNED NOT NULL,
        `viewed_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

        UNIQUE KEY `uq_story_viewer` (`story_id`, `viewer_id`),
        INDEX `idx_story` (`story_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    Database::raw($sql2);
    echo "✓ `story_views` table created successfully.\n";
    echo "\nMigration complete! Your story feature is ready.\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
