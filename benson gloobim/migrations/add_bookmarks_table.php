-- Add bookmarks table for saving posts/reels/videos
CREATE TABLE IF NOT EXISTS `bookmarks` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `bookmarkable_type` varchar(50) NOT NULL COMMENT 'post, reel, video',
  `bookmarkable_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_bookmark` (`user_id`, `bookmarkable_type`, `bookmarkable_id`),
  KEY `idx_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;