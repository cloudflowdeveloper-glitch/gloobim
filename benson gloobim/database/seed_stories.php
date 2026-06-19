<?php
/**
 * Seed stories with test data
 * Run: php database/seed_stories.php
 */

$basePath = dirname(__DIR__);
define('BASE_PATH', $basePath);

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

$autoloadPath = $basePath . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require $autoloadPath;
}

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

    // Clean existing test stories
    Database::raw("DELETE FROM story_views");
    Database::raw("DELETE FROM stories");
    echo "✓ Cleared existing stories.\n";

    $now = date('Y-m-d H:i:s');
    $expires = date('Y-m-d H:i:s', strtotime('+24 hours'));

    // Story templates — using existing placeholder images from /uploads/
    $storyData = [
        [
            'user_id' => 2,         // Zara Ke
            'image_url' => '/uploads/story_1.jpg',
            'text_content' => 'Good morning Nairobi! ☀️',
            'text_position' => 'top',
            'text_color' => '#ffffff',
            'text_size' => '28',
            'font_style' => 'bold',
            'views_count' => 1240,
        ],
        [
            'user_id' => 2,
            'image_url' => '/uploads/story_2.jpg',
            'text_content' => 'New dance challenge drops tonight 🔥',
            'text_position' => 'bottom',
            'text_color' => '#f59e0b',
            'text_size' => '22',
            'font_style' => 'bold',
            'views_count' => 890,
        ],
        [
            'user_id' => 2,
            'image_url' => '/uploads/story_3.jpg',
            'text_content' => 'Studio session vibes 🎵',
            'text_position' => 'center',
            'text_color' => '#ec4899',
            'text_size' => '32',
            'font_style' => 'normal',
            'views_count' => 560,
        ],
        [
            'user_id' => 1,         // Admin
            'image_url' => '/uploads/story_4.jpg',
            'text_content' => 'Platform update coming soon! 🚀',
            'text_position' => 'center',
            'text_color' => '#ffffff',
            'text_size' => '26',
            'font_style' => 'bold',
            'views_count' => 3400,
        ],
        [
            'user_id' => 1,
            'image_url' => '/uploads/story_5.jpg',
            'text_content' => 'Welcome to Globiim! 💜',
            'text_position' => 'bottom',
            'text_color' => '#834ae5',
            'text_size' => '30',
            'font_style' => 'bold',
            'views_count' => 2100,
        ],
        [
            'user_id' => 2,
            'image_url' => '/uploads/story_6.jpg',
            'text_content' => 'Behind the scenes 📸',
            'text_position' => 'top',
            'text_color' => '#22c55e',
            'text_size' => '24',
            'font_style' => 'italic',
            'views_count' => 430,
        ],
    ];

    $inserted = 0;
    foreach ($storyData as $story) {
        $story['created_at'] = $now;
        $story['expires_at'] = $expires;
        $story['is_active'] = 1;

        $id = Database::insert('stories', $story);
        echo "  ✓ Story #{$id}: " . substr($story['text_content'], 0, 40) . "...\n";
        $inserted++;
    }

    echo "\n✓ Inserted {$inserted} seed stories (expires: {$expires}).\n";
    echo "\nStories will appear on the homepage and /stories page.\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
