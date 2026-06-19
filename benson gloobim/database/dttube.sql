-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 05, 2026 at 02:29 PM
-- Server version: 10.11.14-MariaDB-cll-lve
-- PHP Version: 8.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uydirsqz_domain`
--

-- --------------------------------------------------------

--
-- Table structure for table `ads`
--

CREATE TABLE `ads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `advertiser_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `target_url` varchar(500) DEFAULT NULL,
  `type` enum('banner','video','native','sponsored') DEFAULT 'banner',
  `placement` enum('feed','reel','video','sidebar','story') DEFAULT 'feed',
  `impressions` bigint(20) UNSIGNED DEFAULT 0,
  `clicks` bigint(20) UNSIGNED DEFAULT 0,
  `budget` decimal(15,2) DEFAULT 0.00,
  `spent` decimal(15,2) DEFAULT 0.00,
  `status` enum('draft','active','paused','ended') DEFAULT 'draft',
  `starts_at` datetime DEFAULT NULL,
  `ends_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `commentable_type` varchar(50) NOT NULL,
  `commentable_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `body` text NOT NULL,
  `likes` bigint(20) UNSIGNED DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_one` bigint(20) UNSIGNED NOT NULL,
  `user_two` bigint(20) UNSIGNED NOT NULL,
  `last_message_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `followers`
--

CREATE TABLE `followers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `follower_id` bigint(20) UNSIGNED NOT NULL,
  `following_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `followers`
--

INSERT INTO `followers` (`id`, `follower_id`, `following_id`, `created_at`) VALUES
(1, 1, 2, '2026-05-21 14:52:18');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `likeable_type` varchar(50) NOT NULL,
  `likeable_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livestreams`
--

CREATE TABLE `livestreams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `thumbnail` varchar(500) DEFAULT NULL,
  `stream_key` varchar(100) DEFAULT NULL,
  `stream_url` varchar(500) DEFAULT NULL,
  `viewers` bigint(20) UNSIGNED DEFAULT 0,
  `peak_viewers` bigint(20) UNSIGNED DEFAULT 0,
  `total_likes` bigint(20) UNSIGNED DEFAULT 0,
  `total_gifts` bigint(20) UNSIGNED DEFAULT 0,
  `gift_earnings` decimal(15,2) DEFAULT 0.00,
  `status` enum('scheduled','live','ended') DEFAULT 'scheduled',
  `started_at` datetime DEFAULT NULL,
  `ended_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `livestreams`
--

INSERT INTO `livestreams` (`id`, `user_id`, `title`, `description`, `thumbnail`, `stream_key`, `stream_url`, `viewers`, `peak_viewers`, `total_likes`, `total_gifts`, `gift_earnings`, `status`, `started_at`, `ended_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'Late Night Music Session', 'Live music and chat 🎵', 'https://placehold.co/400x225/d97706/ffffff?text=LIVE+1', NULL, NULL, 4200, 8500, 0, 0, 0.00, 'live', '2026-05-21 14:52:18', NULL, '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(2, 2, 'Q&A: Starting Your Creator Journey', 'Ask me anything about content creation', 'https://placehold.co/400x225/6d28d9/ffffff?text=LIVE+2', NULL, NULL, 2800, 5100, 0, 0, 0.00, 'live', '2026-05-21 14:52:18', NULL, '2026-05-21 14:52:18', '2026-05-21 14:52:18');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `body` text NOT NULL,
  `attachment_url` varchar(500) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(10) UNSIGNED DEFAULT 1,
  `ran_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `body`, `data`, `is_read`, `created_at`) VALUES
(1, 1, 'welcome', 'Welcome to DTTube!', 'Your admin account is ready.', NULL, 0, '2026-05-21 14:52:18'),
(2, 2, 'verification', 'Account Verified!', 'Your creator account has been verified.', NULL, 0, '2026-05-21 14:52:18');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `likes` bigint(20) UNSIGNED DEFAULT 0,
  `comments_count` bigint(20) UNSIGNED DEFAULT 0,
  `shares` bigint(20) UNSIGNED DEFAULT 0,
  `status` enum('draft','published','deleted') DEFAULT 'published',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `content`, `image_url`, `likes`, `comments_count`, `shares`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'Just dropped a new dance challenge! Who can keep up? 🔥💃 #KukuaChallenge #DTTube', 'https://placehold.co/600x400/6d28d9/ffffff?text=Post+1', 12400, 843, 2100, 'published', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(2, 2, 'New afrobeats pack dropping this Friday on my creator store! 🎧🔥 Pre-order now', 'https://placehold.co/600x300/d97706/ffffff?text=Post+2', 3200, 198, 567, 'published', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(3, 2, 'When your African mom discovers you have a wallet balance 😂💰 #Comedy #Relatable', NULL, 24300, 3800, 8900, 'published', '2026-05-21 14:52:18', '2026-05-21 14:52:18');

-- --------------------------------------------------------

--
-- Table structure for table `reels`
--

CREATE TABLE `reels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `thumbnail` varchar(500) DEFAULT NULL,
  `video_url` varchar(500) DEFAULT NULL,
  `duration` int(10) UNSIGNED DEFAULT 0,
  `views` bigint(20) UNSIGNED DEFAULT 0,
  `likes` bigint(20) UNSIGNED DEFAULT 0,
  `comments_count` bigint(20) UNSIGNED DEFAULT 0,
  `shares` bigint(20) UNSIGNED DEFAULT 0,
  `song_name` varchar(255) DEFAULT NULL,
  `song_url` varchar(500) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `status` enum('draft','published','deleted') DEFAULT 'draft',
  `is_featured` tinyint(1) DEFAULT 0,
  `ai_captions` text DEFAULT NULL,
  `viral_score` float DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reels`
--

INSERT INTO `reels` (`id`, `user_id`, `title`, `description`, `thumbnail`, `video_url`, `duration`, `views`, `likes`, `comments_count`, `shares`, `song_name`, `song_url`, `category`, `tags`, `status`, `is_featured`, `ai_captions`, `viral_score`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'Dance Challenge #Kukua', 'Can you keep up with the Kukua challenge? 🔥💃', 'https://placehold.co/300x500/6d28d9/ffffff?text=Reel+1', '/uploads/reels/reel1.mp4', 30, 2450000, 24500, 1800, 5200, 'Kukua Beat - DJ MixMaster', NULL, 'Dance', NULL, 'published', 1, NULL, 87.5, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(2, 2, 'Cooking Jollof Rice', 'My secret recipe revealed! 🍚', 'https://placehold.co/300x500/dc2626/ffffff?text=Reel+2', '/uploads/reels/reel2.mp4', 45, 1800000, 18900, 3200, 8100, 'Afro Kitchen Vibes - BeatMaker', NULL, 'Food', NULL, 'published', 0, NULL, 72.3, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(3, 2, 'Nairobi Night Vibes', 'Night out in the city 🌃', 'https://placehold.co/300x500/059669/ffffff?text=Reel+3', '/uploads/reels/reel3.mp4', 22, 980000, 9800, 560, 1900, 'Original Sound - ZaraKe', NULL, 'Lifestyle', NULL, 'published', 0, NULL, 65.1, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(4, 2, 'AI Art is Crazy', 'Watch what AI created in 30 seconds 🤖', 'https://placehold.co/300x500/2563eb/ffffff?text=Reel+4', '/uploads/reels/reel4.mp4', 58, 3100000, 31200, 5600, 12400, 'Digital Dreams - SynthWave', NULL, 'Tech', NULL, 'published', 1, NULL, 91.2, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(5, 2, 'Afrobeats Studio Session', 'New track dropping this Friday 🎧🔥', 'https://placehold.co/300x500/d97706/ffffff?text=Reel+5', '/uploads/reels/reel5.mp4', 35, 1200000, 12100, 890, 3400, 'Afro Drop (Preview) - BeatMaker', NULL, 'Music', NULL, 'published', 0, NULL, 68.7, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(6, 2, 'Comedy: African Mom', 'When your African mom finds your wallet balance 😂💰', 'https://placehold.co/300x500/e11d48/ffffff?text=Reel+6', '/uploads/reels/reel6.mp4', 40, 5600000, 56300, 8900, 24100, 'Original Sound - ZaraKe', NULL, 'Comedy', NULL, 'published', 1, NULL, 95.8, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(7, 2, 'Skincare Routine', 'The routine that ACTUALLY works ✨', 'https://placehold.co/300x500/7c3aed/ffffff?text=Reel+7', '/uploads/reels/reel7.mp4', 55, 870000, 8700, 421, 1900, 'Soft Vibes - ChillBeats', NULL, 'Beauty', NULL, 'published', 0, NULL, 54.2, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(8, 2, 'Football Skills', 'Check these moves ⚽', 'https://placehold.co/300x500/0891b2/ffffff?text=Reel+8', '/uploads/reels/reel8.mp4', 28, 4200000, 42000, 3400, 11200, 'Goal Mix - DJ Sports', NULL, 'Sports', NULL, 'published', 0, NULL, 82.4, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reporter_id` bigint(20) UNSIGNED NOT NULL,
  `reportable_type` varchar(50) NOT NULL,
  `reportable_id` bigint(20) UNSIGNED NOT NULL,
  `reason` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','reviewed','resolved','dismissed') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subscriber_id` bigint(20) UNSIGNED NOT NULL,
  `creator_id` bigint(20) UNSIGNED NOT NULL,
  `plan` enum('free','basic','premium','vip') DEFAULT 'free',
  `amount` decimal(15,2) DEFAULT 0.00,
  `currency` varchar(10) DEFAULT 'KES',
  `status` enum('active','cancelled','expired') DEFAULT 'active',
  `starts_at` datetime DEFAULT current_timestamp(),
  `expires_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(500) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('user','creator','admin','super_admin') DEFAULT 'user',
  `provider` varchar(50) DEFAULT NULL,
  `provider_id` varchar(255) DEFAULT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `phone_verified_at` datetime DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `is_banned` tinyint(1) DEFAULT 0,
  `country_code` varchar(5) DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `avatar`, `bio`, `phone`, `role`, `provider`, `provider_id`, `email_verified_at`, `phone_verified_at`, `is_verified`, `is_banned`, `last_login_at`, `created_at`, `updated_at`) VALUES
(1, 'DTTube Admin', 'admin', 'admin@dttube.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'https://placehold.co/100x100/e11d48/ffffff?text=AD', 'Platform administrator', NULL, 'admin', NULL, NULL, '2026-05-21 14:52:18', NULL, 1, 0, NULL, '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(2, 'Zara Ke', 'zarake', 'zarake@dttube.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'https://placehold.co/100x100/6d28d9/ffffff?text=ZK', 'Dancer & content creator from Nairobi 💃🔥', NULL, 'creator', NULL, NULL, '2026-05-21 14:52:18', NULL, 1, 0, NULL, '2026-05-21 14:52:18', '2026-05-21 14:52:18');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `thumbnail` varchar(500) DEFAULT NULL,
  `video_url` varchar(500) DEFAULT NULL,
  `stream_key` varchar(100) DEFAULT NULL,
  `duration` int(10) UNSIGNED DEFAULT 0,
  `views` bigint(20) UNSIGNED DEFAULT 0,
  `likes` bigint(20) UNSIGNED DEFAULT 0,
  `comments_count` bigint(20) UNSIGNED DEFAULT 0,
  `shares` bigint(20) UNSIGNED DEFAULT 0,
  `category` varchar(100) DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `status` enum('draft','processing','published','unlisted','private','deleted') DEFAULT 'draft',
  `is_featured` tinyint(1) DEFAULT 0,
  `is_monetized` tinyint(1) DEFAULT 0,
  `ai_captions` text DEFAULT NULL,
  `viral_score` float DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `user_id`, `title`, `description`, `thumbnail`, `video_url`, `stream_key`, `duration`, `views`, `likes`, `comments_count`, `shares`, `category`, `tags`, `status`, `is_featured`, `is_monetized`, `ai_captions`, `viral_score`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'Building a Startup in Africa - Full Documentary', 'The complete journey of building a tech startup from Nairobi', 'https://placehold.co/400x225/6d28d9/ffffff?text=Video+1', '/uploads/videos/video1.mp4', NULL, 2720, 1200000, 45000, 2300, 8900, 'Tech', NULL, 'published', 1, 1, NULL, 78.5, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(2, 2, 'How to Make Money as a Creator in 2025', 'Complete guide to monetizing your content', 'https://placehold.co/400x225/dc2626/ffffff?text=Video+2', '/uploads/videos/video2.mp4', NULL, 1335, 890000, 32000, 1800, 6700, 'Business', NULL, 'published', 1, 1, NULL, 73.2, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(3, 2, 'Lagos to Nairobi: Road Trip Vlog', 'Cross-country adventure through East and West Africa', 'https://placehold.co/400x225/059669/ffffff?text=Video+3', '/uploads/videos/video3.mp4', NULL, 1905, 670000, 21000, 980, 4500, 'Travel', NULL, 'published', 0, 1, NULL, 61.8, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(4, 2, 'Learn Flutter in 2 Hours - Complete Course', 'Full Flutter development course for beginners', 'https://placehold.co/400x225/2563eb/ffffff?text=Video+4', '/uploads/videos/video4.mp4', NULL, 7290, 2100000, 89000, 5600, 23000, 'Education', NULL, 'published', 1, 1, NULL, 92.1, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(5, 2, 'Best Afrobeat Mix 2025', 'Non-stop afrobeats mix for your playlist', 'https://placehold.co/400x225/d97706/ffffff?text=Video+5', '/uploads/videos/video5.mp4', NULL, 4500, 3500000, 120000, 7800, 34000, 'Music', NULL, 'published', 1, 1, NULL, 96.3, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(6, 2, 'Street Food Tour: Accra Edition', 'Trying the best street food in Accra, Ghana', 'https://placehold.co/400x225/e11d48/ffffff?text=Video+6', '/uploads/videos/video6.mp4', NULL, 1110, 450000, 15000, 670, 2100, 'Food', NULL, 'published', 0, 1, NULL, 58.4, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18');

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `balance` decimal(15,2) DEFAULT 0.00,
  `currency` varchar(10) DEFAULT 'KES',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `user_id`, `balance`, `currency`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 0.00, 'KES', 1, '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(2, 2, 15000.00, 'KES', 1, '2026-05-21 14:52:18', '2026-05-21 14:52:18');

-- --------------------------------------------------------

--
-- Table structure for table `wallet_transactions`
--

CREATE TABLE `wallet_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `wallet_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('deposit','withdrawal','gift_sent','gift_received','tip','subscription','earnings','refund','mpesa_deposit','mpesa_withdrawal') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `fee` decimal(15,2) DEFAULT 0.00,
  `status` enum('pending','completed','failed','cancelled') DEFAULT 'pending',
  `reference` varchar(255) DEFAULT NULL,
  `mpesa_checkout_id` varchar(255) DEFAULT NULL,
  `mpesa_receipt` varchar(255) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `recipient_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `music_genres`
--

CREATE TABLE `music_genres` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `cover_url` varchar(500) DEFAULT NULL,
  `track_count` int(10) UNSIGNED DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `music_genres`
--

INSERT INTO `music_genres` (`id`, `name`, `slug`, `color`, `icon`, `cover_url`, `track_count`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Afrobeats', 'afrobeats', '#f97316', 'whatshot', '/uploads/music/covers/genre_1.jpg', 1240, 1, '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(2, 'Bongo Flava', 'bongo-flava', '#22c55e', 'music_note', '/uploads/music/covers/genre_2.jpg', 890, 1, '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(3, 'Hip Hop', 'hip-hop', '#a855f7', 'headset', '/uploads/music/covers/genre_3.jpg', 2100, 1, '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(4, 'RnB / Soul', 'rnb-soul', '#ec4899', 'favorite', '/uploads/music/covers/genre_4.jpg', 1560, 1, '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(5, 'Gospel', 'gospel', '#f59e0b', 'church', '/uploads/music/covers/genre_5.jpg', 670, 1, '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(6, 'Dancehall', 'dancehall', '#06b6d4', 'celebration', '/uploads/music/covers/genre_6.jpg', 780, 1, '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(7, 'Amapiano', 'amapiano', '#ef4444', 'nightlife', '/uploads/music/covers/genre_7.jpg', 1340, 1, '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(8, 'Gengetone', 'gengetone', '#6366f1', 'album', '/uploads/music/covers/genre_8.jpg', 420, 1, '2026-05-21 14:52:18', '2026-05-21 14:52:18');

-- --------------------------------------------------------

--
-- Table structure for table `music_tracks`
--

CREATE TABLE `music_tracks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `artist_name` varchar(255) DEFAULT NULL,
  `artist_id` bigint(20) UNSIGNED DEFAULT NULL,
  `artist_avatar` varchar(500) DEFAULT NULL,
  `cover_url` varchar(500) DEFAULT NULL,
  `audio_url` varchar(500) DEFAULT NULL,
  `video_url` varchar(500) DEFAULT NULL,
  `duration` int(10) UNSIGNED DEFAULT 0,
  `genre_id` bigint(20) UNSIGNED DEFAULT NULL,
  `plays` bigint(20) UNSIGNED DEFAULT 0,
  `likes` bigint(20) UNSIGNED DEFAULT 0,
  `shares` bigint(20) UNSIGNED DEFAULT 0,
  `downloads` bigint(20) UNSIGNED DEFAULT 0,
  `is_verified` tinyint(1) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_explicit` tinyint(1) DEFAULT 0,
  `status` enum('draft','published','unlisted','deleted') DEFAULT 'draft',
  `published_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `music_tracks`
--

INSERT INTO `music_tracks` (`id`, `title`, `artist_name`, `artist_id`, `artist_avatar`, `cover_url`, `audio_url`, `video_url`, `duration`, `genre_id`, `plays`, `likes`, `shares`, `downloads`, `is_verified`, `is_featured`, `is_explicit`, `status`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 'Kukua (Remix)', 'Zara Ke', 2, '/uploads/music/artists/avatar_1.jpg', '/uploads/music/covers/track_1.jpg', '/uploads/music/audio/kukua_remix.mp3', '/uploads/music/video/kukua_remix.mp4', 214, 1, 2450000, 89000, 12400, 45000, 1, 1, 0, 'published', '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(2, 'Sauti Ya Nairobi', 'DJ MixMaster', 2, '/uploads/music/artists/avatar_2.jpg', '/uploads/music/covers/track_2.jpg', '/uploads/music/audio/sauti_ya_nairobi.mp3', '/uploads/music/video/sauti_ya_nairobi.mp4', 198, 1, 1850000, 67000, 8900, 32000, 1, 1, 0, 'published', '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(3, 'Late Night Vibes', 'Neo Soundz', 2, '/uploads/music/artists/avatar_3.jpg', '/uploads/music/covers/track_3.jpg', '/uploads/music/audio/late_night_vibes.mp3', '/uploads/music/video/late_night_vibes.mp4', 247, 3, 1200000, 45000, 5600, 21000, 1, 0, 0, 'published', '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(4, 'African Dream', 'Kilele', 2, '/uploads/music/artists/avatar_4.jpg', '/uploads/music/covers/track_4.jpg', '/uploads/music/audio/african_dream.mp3', '/uploads/music/video/african_dream.mp4', 232, 1, 980000, 34000, 4300, 18000, 0, 1, 0, 'published', '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(5, 'Sherehe', 'Fiesta Band', 2, '/uploads/music/artists/avatar_5.jpg', '/uploads/music/covers/track_5.jpg', '/uploads/music/audio/sherehe.mp3', '/uploads/music/video/sherehe.mp4', 189, 2, 890000, 28000, 3200, 14000, 1, 0, 0, 'published', '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(6, 'Nakupenda', 'Aisha Flow', 2, '/uploads/music/artists/avatar_6.jpg', '/uploads/music/covers/track_6.jpg', '/uploads/music/audio/nakupenda.mp3', '/uploads/music/video/nakupenda.mp4', 205, 4, 760000, 22000, 2100, 9800, 1, 0, 0, 'published', '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(7, 'Mambo Bora', 'MC Rasta', 2, '/uploads/music/artists/avatar_7.jpg', '/uploads/music/covers/track_7.jpg', '/uploads/music/audio/mambo_bora.mp3', '/uploads/music/video/mambo_bora.mp4', 176, 8, 540000, 15000, 1800, 7600, 0, 0, 1, 'published', '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(8, 'Safari Sounds', 'WildBeats', 2, '/uploads/music/artists/avatar_8.jpg', '/uploads/music/covers/track_8.jpg', '/uploads/music/audio/safari_sounds.mp3', '/uploads/music/video/safari_sounds.mp4', 263, 7, 430000, 12000, 1500, 6200, 0, 0, 0, 'published', '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(9, 'Leo Niko Ready', 'Bravo Kid', 2, '/uploads/music/artists/avatar_9.jpg', '/uploads/music/covers/track_9.jpg', '/uploads/music/audio/leo_niko_ready.mp3', '/uploads/music/video/leo_niko_ready.mp4', 192, 6, 380000, 11000, 1200, 5400, 1, 0, 1, 'published', '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(10, 'Ulimi Wangu', 'Lena Moon', 2, '/uploads/music/artists/avatar_10.jpg', '/uploads/music/covers/track_10.jpg', '/uploads/music/audio/ulimi_wangu.mp3', '/uploads/music/video/ulimi_wangu.mp4', 228, 4, 310000, 9800, 900, 4200, 0, 1, 0, 'published', '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18');

-- --------------------------------------------------------

--
-- Table structure for table `music_featured`
--

CREATE TABLE `music_featured` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_url` varchar(500) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `track_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`track_ids`)),
  `listeners` bigint(20) UNSIGNED DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `starts_at` datetime DEFAULT NULL,
  `ends_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `music_featured`
--

INSERT INTO `music_featured` (`id`, `title`, `description`, `cover_url`, `author`, `track_ids`, `listeners`, `is_active`, `starts_at`, `ends_at`, `created_at`, `updated_at`) VALUES
(1, 'African Heat 2025', 'Hottest African tracks right now', '/uploads/music/covers/featured_banner.jpg', 'DTTube Music', '[1,2,3,4,5]', 12400, 1, '2026-05-01 00:00:00', '2026-06-30 23:59:59', '2026-05-21 14:52:18', '2026-05-21 14:52:18');

-- --------------------------------------------------------

--
-- Table structure for table `music_playlists`
--

CREATE TABLE `music_playlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_url` varchar(500) DEFAULT NULL,
  `track_count` int(10) UNSIGNED DEFAULT 0,
  `author_name` varchar(255) DEFAULT NULL,
  `author_id` bigint(20) UNSIGNED DEFAULT NULL,
  `followers` bigint(20) UNSIGNED DEFAULT 0,
  `is_curated` tinyint(1) DEFAULT 0,
  `status` enum('public','private','unlisted') DEFAULT 'public',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `music_playlists`
--

INSERT INTO `music_playlists` (`id`, `name`, `description`, `cover_url`, `track_count`, `author_name`, `author_id`, `followers`, `is_curated`, `status`, `created_at`, `updated_at`) VALUES
(1, 'African Heat', 'Hottest African tracks right now', '/uploads/music/playlists/playlist_1.jpg', 45, 'DTTube Music', 1, 32000, 1, 'public', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(2, 'Chill Vibes', 'Relax and unwind', '/uploads/music/playlists/playlist_2.jpg', 32, 'Zara Ke', 2, 18500, 1, 'public', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(3, 'Workout Energy', 'High energy for your workout', '/uploads/music/playlists/playlist_3.jpg', 28, 'DTTube Music', 1, 14200, 1, 'public', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(4, 'Karaoke Classics', 'Sing along favorites', '/uploads/music/playlists/playlist_4.jpg', 50, 'DTTube Music', 1, 45000, 1, 'public', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(5, 'New Releases KE', 'Fresh from Kenya', '/uploads/music/playlists/playlist_5.jpg', 18, 'Curated', NULL, 8900, 1, 'public', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(6, 'Bongo Flava Mix', 'Best of Bongo Flava', '/uploads/music/playlists/playlist_6.jpg', 38, 'DTTube Music', 1, 27600, 1, 'public', '2026-05-21 14:52:18', '2026-05-21 14:52:18');

-- --------------------------------------------------------

--
-- Table structure for table `marketplace_listings`
--

CREATE TABLE `marketplace_listings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `currency` varchar(10) DEFAULT 'KES',
  `image_url` varchar(500) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `category` varchar(100) DEFAULT 'Other',
  `condition` enum('new','like_new','good','fair','used') DEFAULT 'good',
  `location` varchar(255) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `views` bigint(20) UNSIGNED DEFAULT 0,
  `likes` bigint(20) UNSIGNED DEFAULT 0,
  `sold` tinyint(1) DEFAULT 0,
  `status` enum('active','sold','deleted','expired') DEFAULT 'active',
  `expires_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `marketplace_listings`
--

INSERT INTO `marketplace_listings` (`id`, `user_id`, `title`, `description`, `price`, `currency`, `image_url`, `category`, `condition`, `location`, `phone`, `views`, `sold`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'iPhone 15 Pro Max 256GB', 'Brand new, box sealed. Natural Titanium color.', 1199.00, 'USD', '/uploads/marketplace/product_iphone.jpg', 'Electronics', 'new', 'Nairobi', '+254712345678', 2340, 0, 'active', DATE_SUB(NOW(), INTERVAL 1 DAY), NOW()),
(2, 2, 'Sony WH-1000XM5 Headphones', 'Noise-cancelling, premium audio quality. Used 2 weeks.', 349.00, 'USD', '/uploads/marketplace/product_headphones.jpg', 'Electronics', 'like_new', 'Nairobi', '+254712345678', 5120, 0, 'active', DATE_SUB(NOW(), INTERVAL 2 DAY), NOW()),
(3, 2, 'MacBook Air M3 - 16GB RAM', 'Lightly used, battery cycle: 12. Starlight finish.', 1099.00, 'USD', '/uploads/marketplace/product_laptop.jpg', 'Electronics', 'good', 'Nairobi', '+254712345678', 1890, 0, 'active', DATE_SUB(NOW(), INTERVAL 3 DAY), NOW()),
(4, 2, 'Apple Watch Ultra 2', '49mm Titanium, GPS + Cellular. Brand new.', 799.00, 'USD', '/uploads/marketplace/product_watch.jpg', 'Electronics', 'new', 'Nairobi', '+254712345678', 980, 0, 'active', DATE_SUB(NOW(), INTERVAL 4 DAY), NOW()),
(5, 2, 'Nike Air Max 90 OG', 'Classic infrared colorway, DS (Deadstock).', 130.00, 'USD', '/uploads/marketplace/product_sneakers.jpg', 'Fashion', 'new', 'Mombasa', '+254723456789', 3210, 0, 'active', DATE_SUB(NOW(), INTERVAL 1 DAY), NOW()),
(6, 2, 'Canon EOS R6 Mark II', 'Full-frame mirrorless, 24.2MP, with RF 24-105mm lens kit.', 2499.00, 'USD', '/uploads/marketplace/product_camera.jpg', 'Electronics', 'like_new', 'Nairobi', '+254734567890', 420, 0, 'active', DATE_SUB(NOW(), INTERVAL 2 DAY), NOW()),
(7, 2, 'Ray-Ban Aviator Classic', 'Original polarized G-15 lenses. Gold frame.', 163.00, 'USD', '/uploads/marketplace/product_sunglasses.jpg', 'Fashion', 'new', 'Kisumu', '+254745678901', 1560, 0, 'active', DATE_SUB(NOW(), INTERVAL 5 DAY), NOW()),
(8, 2, 'JBL Flip 6 Portable Speaker', 'IP67 waterproof, 12 hours playtime, black.', 129.00, 'USD', '/uploads/marketplace/product_speaker.jpg', 'Electronics', 'good', 'Nairobi', '+254756789012', 2890, 0, 'active', DATE_SUB(NOW(), INTERVAL 6 DAY), NOW()),
(9, 2, 'North Face Nuptse 1996 Jacket', 'Retro Nuptse, size L, black. Excellent condition.', 280.00, 'USD', '/uploads/marketplace/product_jacket.jpg', 'Fashion', 'good', 'Nairobi', '+254767890123', 780, 0, 'active', DATE_SUB(NOW(), INTERVAL 3 DAY), NOW()),
(10, 2, 'iPad Air M2 256GB', '11-inch, Wi-Fi + Cellular, Space Gray.', 599.00, 'USD', '/uploads/marketplace/product_tablet.jpg', 'Electronics', 'like_new', 'Mombasa', '+254778901234', 1340, 0, 'active', DATE_SUB(NOW(), INTERVAL 4 DAY), NOW()),
(11, 2, 'Herman Miller Aeron Chair', 'Size B, fully loaded, graphite frame.', 450.00, 'USD', '/uploads/marketplace/product_chair.jpg', 'Home', 'good', 'Nairobi', '+254789012345', 670, 0, 'active', DATE_SUB(NOW(), INTERVAL 7 DAY), NOW()),
(12, 2, 'Vintage Leather Backpack', 'Hand-stitched genuine leather, 15" laptop fits.', 89.00, 'USD', '/uploads/marketplace/product_backpack.jpg', 'Fashion', 'good', 'Kisumu', '+254790123456', 540, 0, 'active', DATE_SUB(NOW(), INTERVAL 8 DAY), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `marketplace_categories`
--

CREATE TABLE `marketplace_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `cover_url` varchar(500) DEFAULT NULL,
  `product_count` int(10) UNSIGNED DEFAULT 0,
  `sort_order` int(10) UNSIGNED DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `marketplace_categories` (`id`, `name`, `slug`, `icon`, `cover_url`, `product_count`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Electronics', 'electronics', 'electronics', '/uploads/marketplace/electronics.jpg', 24, 1, 1, NOW(), NOW()),
(2, 'Fashion', 'fashion', 'fashion', '/uploads/marketplace/fashion.jpg', 18, 2, 1, NOW(), NOW()),
(3, 'Home', 'home', 'home', '/uploads/marketplace/home.jpg', 12, 3, 1, NOW(), NOW()),
(4, 'Beauty', 'beauty', 'beauty', '/uploads/marketplace/beauty.jpg', 9, 4, 1, NOW(), NOW()),
(5, 'Sports', 'sports', 'sports', '/uploads/marketplace/sports.jpg', 7, 5, 1, NOW(), NOW()),
(6, 'Gaming', 'gaming', 'gaming', '/uploads/marketplace/gaming.jpg', 15, 6, 1, NOW(), NOW()),
(7, 'Books', 'books', 'books', '/uploads/marketplace/books.jpg', 5, 7, 1, NOW(), NOW()),
(8, 'Auto', 'auto', 'auto', '/uploads/marketplace/auto.jpg', 3, 8, 1, NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `marketplace_wishlist`
--

CREATE TABLE `marketplace_wishlist` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `listing_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_wishlist` (`user_id`,`listing_id`),
  KEY `idx_wishlist_user` (`user_id`),
  KEY `idx_wishlist_listing` (`listing_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marketplace_reviews`
--

CREATE TABLE `marketplace_reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `listing_id` bigint(20) UNSIGNED NOT NULL,
  `reviewer_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED DEFAULT 5,
  `comment` text DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spotlight_ads`
--

CREATE TABLE `spotlight_ads` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `spotlight_ads` (`id`, `title`, `subtitle`, `image_url`, `link_url`, `badge`, `badge_color`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Shop the Latest Drops', 'Exclusive deals on trending products', '/uploads/home/card_1.jpg', '/marketplace', 'Shop', '#22c55e', 1, 1, NOW(), NOW()),
(2, 'Go Live & Earn', 'Start streaming and receive gifts from fans', '/uploads/home/card_2.jpg', '/livestream/start', 'Stream', '#ef4444', 2, 1, NOW(), NOW()),
(3, 'Discover New Music', 'Stream the hottest Afrobeats tracks', '/uploads/home/card_3.jpg', '/music', 'Music', '#ec4899', 3, 1, NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `stream_gifts`
--

CREATE TABLE `stream_gifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `price_usd` decimal(10,2) NOT NULL DEFAULT 0.00,
  `color_class` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_animated` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stream_gifts`
--

INSERT INTO `stream_gifts` (`id`, `name`, `icon`, `price_usd`, `color_class`, `is_active`, `sort_order`, `created_at`) VALUES
(1, 'Heart', 'favorite', 0.50, 'text-red-400', 1, 1, NOW()),
(2, 'Fire', 'local_fire_department', 1.00, 'text-orange-400', 1, 2, NOW()),
(3, 'Star', 'star', 2.50, 'text-yellow-400', 1, 3, NOW()),
(4, 'Diamond', 'diamond', 5.00, 'text-cyan-400', 1, 4, NOW()),
(5, 'Crown', 'crown', 10.00, 'text-amber-400', 1, 5, NOW()),
(6, 'Rocket', 'rocket_launch', 20.00, 'text-purple-400', 1, 6, NOW()),
(7, 'Party', 'celebration', 50.00, 'text-pink-400', 1, 7, NOW()),
(8, 'Super', 'bolt', 100.00, 'text-brand-400', 1, 8, NOW());

-- --------------------------------------------------------

--
-- Table structure for table `country_currencies`
--

CREATE TABLE `country_currencies` (
  `country_code` varchar(5) NOT NULL,
  `country_name` varchar(100) NOT NULL,
  `currency_code` varchar(10) NOT NULL,
  `currency_symbol` varchar(20) DEFAULT NULL,
  `exchange_rate_usd` decimal(15,6) DEFAULT 1.000000,
  PRIMARY KEY (`country_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `country_currencies`
--

INSERT INTO `country_currencies` (`country_code`, `country_name`, `currency_code`, `currency_symbol`, `exchange_rate_usd`) VALUES
('US', 'United States', 'USD', '$', 1.000000),
('KE', 'Kenya', 'KES', 'KES', 129.500000),
('NG', 'Nigeria', 'NGN', '₦', 1540.000000),
('GH', 'Ghana', 'GHS', '¢', 14.500000),
('ZA', 'South Africa', 'ZAR', 'R', 18.200000),
('TZ', 'Tanzania', 'TZS', 'TSh', 2580.000000),
('UG', 'Uganda', 'UGX', 'USh', 3700.000000),
('RW', 'Rwanda', 'RWF', 'FRw', 1300.000000),
('ET', 'Ethiopia', 'ETB', 'Br', 55.000000),
('GB', 'United Kingdom', 'GBP', '£', 0.790000),
('EU', 'Europe', 'EUR', '€', 0.920000),
('CA', 'Canada', 'CAD', 'C$', 1.360000),
('AU', 'Australia', 'AUD', 'A$', 1.530000),
('IN', 'India', 'INR', '₹', 83.000000);

-- --------------------------------------------------------

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ads`
--
ALTER TABLE `ads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ads_status` (`status`,`placement`),
  ADD KEY `advertiser_id` (`advertiser_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_comments_user` (`user_id`),
  ADD KEY `idx_comments_commentable` (`commentable_type`,`commentable_id`),
  ADD KEY `idx_comments_parent` (`parent_id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_conversation` (`user_one`,`user_two`),
  ADD KEY `idx_conv_users` (`user_one`,`user_two`),
  ADD KEY `user_two` (`user_two`);

--
-- Indexes for table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_follow` (`follower_id`,`following_id`),
  ADD KEY `idx_followers_following` (`following_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`user_id`,`likeable_type`,`likeable_id`),
  ADD KEY `idx_likes_likeable` (`likeable_type`,`likeable_id`);

--
-- Indexes for table `livestreams`
--
ALTER TABLE `livestreams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_livestreams_user` (`user_id`),
  ADD KEY `idx_livestreams_status` (`status`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_messages_conv` (`conversation_id`,`created_at`),
  ADD KEY `idx_messages_sender` (`sender_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notifs_user` (`user_id`,`is_read`),
  ADD KEY `idx_notifs_type` (`type`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_posts_user` (`user_id`),
  ADD KEY `idx_posts_status` (`status`,`created_at`);

--
-- Indexes for table `reels`
--
ALTER TABLE `reels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reels_user` (`user_id`),
  ADD KEY `idx_reels_status` (`status`,`published_at`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reports_status` (`status`),
  ADD KEY `reporter_id` (`reporter_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_subscription` (`subscriber_id`,`creator_id`),
  ADD KEY `idx_subs_creator` (`creator_id`,`status`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_username` (`username`),
  ADD KEY `idx_users_email` (`email`),
  ADD KEY `idx_users_provider` (`provider`,`provider_id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_videos_user` (`user_id`),
  ADD KEY `idx_videos_status` (`status`,`published_at`),
  ADD KEY `idx_videos_views` (`views`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `idx_wallets_user` (`user_id`);

--
-- Indexes for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_wt_wallet` (`wallet_id`),
  ADD KEY `idx_wt_type` (`type`,`status`);

--
-- Indexes for table `music_genres`
--
ALTER TABLE `music_genres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `music_tracks`
--
ALTER TABLE `music_tracks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tracks_artist` (`artist_id`),
  ADD KEY `idx_tracks_genre` (`genre_id`),
  ADD KEY `idx_tracks_status` (`status`,`published_at`),
  ADD KEY `idx_tracks_plays` (`plays`);

--
-- Indexes for table `music_featured`
--
ALTER TABLE `music_featured`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_featured_active` (`is_active`,`starts_at`);

--
-- Indexes for table `music_playlists`
--
ALTER TABLE `music_playlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_playlists_author` (`author_id`),
  ADD KEY `idx_playlists_status` (`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ads`
--
ALTER TABLE `ads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `livestreams`
--
ALTER TABLE `livestreams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reels`
--
ALTER TABLE `reels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `music_genres`
--
ALTER TABLE `music_genres`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `music_tracks`
--
ALTER TABLE `music_tracks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `music_featured`
--
ALTER TABLE `music_featured`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `music_playlists`
--
ALTER TABLE `music_playlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Indexes for table `marketplace_listings`
--
ALTER TABLE `marketplace_listings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ml_user` (`user_id`),
  ADD KEY `idx_ml_category` (`category`),
  ADD KEY `idx_ml_status` (`status`,`created_at`),
  ADD KEY `idx_ml_price` (`price`),
  ADD KEY `idx_ml_views` (`views`);

--
-- Indexes for table `marketplace_categories`
--
ALTER TABLE `marketplace_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- AUTO_INCREMENT for table `marketplace_listings`
--
ALTER TABLE `marketplace_listings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `marketplace_categories`
--
ALTER TABLE `marketplace_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `marketplace_wishlist`
--
ALTER TABLE `marketplace_wishlist`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marketplace_reviews`
--
ALTER TABLE `marketplace_reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spotlight_ads`
--
ALTER TABLE `spotlight_ads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Indexes for table `stream_gifts`
--
ALTER TABLE `stream_gifts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `stream_gifts`
--
ALTER TABLE `stream_gifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `bookmarks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `marketplace_listings`
--
ALTER TABLE `marketplace_listings`
  ADD CONSTRAINT `ml_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `marketplace_wishlist`
--
ALTER TABLE `marketplace_wishlist`
  ADD CONSTRAINT `mw_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mw_ibfk_2` FOREIGN KEY (`listing_id`) REFERENCES `marketplace_listings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `marketplace_reviews`
--
ALTER TABLE `marketplace_reviews`
  ADD CONSTRAINT `mr_ibfk_1` FOREIGN KEY (`listing_id`) REFERENCES `marketplace_listings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mr_ibfk_2` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ads`
--
ALTER TABLE `ads`
  ADD CONSTRAINT `ads_ibfk_1` FOREIGN KEY (`advertiser_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_ibfk_1` FOREIGN KEY (`user_one`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversations_ibfk_2` FOREIGN KEY (`user_two`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `followers`
--
ALTER TABLE `followers`
  ADD CONSTRAINT `followers_ibfk_1` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `followers_ibfk_2` FOREIGN KEY (`following_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `livestreams`
--
ALTER TABLE `livestreams`
  ADD CONSTRAINT `livestreams_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reels`
--
ALTER TABLE `reels`
  ADD CONSTRAINT `reels_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`subscriber_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscriptions_ibfk_2` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD CONSTRAINT `wallet_transactions_ibfk_1` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `music_tracks`
--
ALTER TABLE `music_tracks`
  ADD CONSTRAINT `music_tracks_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `music_tracks_ibfk_2` FOREIGN KEY (`genre_id`) REFERENCES `music_genres` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `music_playlists`
--
ALTER TABLE `music_playlists`
  ADD CONSTRAINT `music_playlists_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
