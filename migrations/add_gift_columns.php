<?php
require_once __DIR__ . '/../core/Config.php';

\Core\Config::load(__DIR__ . '/../config');

require_once __DIR__ . '/../core/Database.php';

echo "Running migration: Add gift columns...\n";

try {
    $conn = \Core\Database::connection();

    $columns = ['description', 'image_url', 'is_animated'];

    foreach ($columns as $col) {
        $check = $conn->query("SHOW COLUMNS FROM stream_gifts LIKE '{$col}'");
        if ($check && $check->num_rows === 0) {
            switch ($col) {
                case 'description':
                    $conn->query("ALTER TABLE stream_gifts ADD COLUMN `description` VARCHAR(500) DEFAULT NULL AFTER `name`");
                    echo "  - Added `description` column\n";
                    break;
                case 'image_url':
                    $conn->query("ALTER TABLE stream_gifts ADD COLUMN `image_url` VARCHAR(500) DEFAULT NULL AFTER `icon`");
                    echo "  - Added `image_url` column\n";
                    break;
                case 'is_animated':
                    $conn->query("ALTER TABLE stream_gifts ADD COLUMN `is_animated` TINYINT(1) DEFAULT 0 AFTER `is_active`");
                    echo "  - Added `is_animated` column\n";
                    break;
            }
        } else {
            echo "  - Column `{$col}` already exists\n";
        }
    }

    echo "Migration completed successfully!\n";
} catch (\Exception $e) {
    echo "Migration error: " . $e->getMessage() . "\n";
    exit(1);
}
