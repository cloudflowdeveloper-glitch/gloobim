-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2026 at 08:38 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
-- Table structure for table `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `bookmarkable_type` varchar(50) NOT NULL COMMENT 'post, reel, video',
  `bookmarkable_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `listing_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `commentable_type`, `commentable_id`, `parent_id`, `body`, `likes`, `created_at`, `updated_at`) VALUES
(0, 2, 'video', 0, NULL, 'yhtguiyi', 0, '2026-06-16 18:34:21', '2026-06-16 21:34:21'),
(1, 2, 'post', 3, NULL, 'cbvfb', 0, '2026-06-05 15:45:31', '2026-06-05 21:15:31'),
(2, 2, 'video', 1, NULL, 'httg', 0, '2026-06-09 10:08:15', '2026-06-09 15:38:15'),
(3, 2, 'post', 2, NULL, 'fgfgffff', 0, '2026-06-12 20:50:15', '2026-06-13 02:20:15');

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
-- Table structure for table `country_currencies`
--

CREATE TABLE `country_currencies` (
  `country_code` varchar(5) NOT NULL,
  `country_name` varchar(100) NOT NULL,
  `currency_code` varchar(10) NOT NULL,
  `currency_symbol` varchar(20) DEFAULT NULL,
  `exchange_rate_usd` decimal(15,6) DEFAULT 1.000000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `country_currencies`
--

INSERT INTO `country_currencies` (`country_code`, `country_name`, `currency_code`, `currency_symbol`, `exchange_rate_usd`) VALUES
('AU', 'Australia', 'AUD', 'A$', 1.530000),
('CA', 'Canada', 'CAD', 'C$', 1.360000),
('ET', 'Ethiopia', 'ETB', 'Br', 55.000000),
('EU', 'Europe', 'EUR', '???', 0.920000),
('GB', 'United Kingdom', 'GBP', '??', 0.790000),
('GH', 'Ghana', 'GHS', '??', 14.500000),
('IN', 'India', 'INR', '???', 83.000000),
('KE', 'Kenya', 'KES', 'KES', 129.500000),
('NG', 'Nigeria', 'NGN', '???', 1540.000000),
('RW', 'Rwanda', 'RWF', 'FRw', 1300.000000),
('TZ', 'Tanzania', 'TZS', 'TSh', 2580.000000),
('UG', 'Uganda', 'UGX', 'USh', 3700.000000),
('US', 'United States', 'USD', '$', 1.000000),
('ZA', 'South Africa', 'ZAR', 'R', 18.200000);

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
(1, 1, 2, '2026-05-21 14:52:18'),
(0, 2, 4, '2026-06-16 19:05:56'),
(0, 2, 5, '2026-06-16 19:05:59'),
(0, 2, 3, '2026-06-16 19:22:04'),
(0, 2, 6, '2026-06-16 19:22:11'),
(0, 2, 7, '2026-06-16 19:22:14');

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

INSERT INTO `livestreams` (`id`, `user_id`, `title`, `description`, `thumbnail`, `stream_key`, `stream_url`, `viewers`, `peak_viewers`, `total_gifts`, `gift_earnings`, `status`, `started_at`, `ended_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'Late Night Music Session', 'Live music and chat ????', '/uploads/livestreams/live_1.jpg', NULL, NULL, 4200, 8500, 0, 0.00, 'live', '2026-05-21 14:52:18', NULL, '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(2, 2, 'Q&A: Starting Your Creator Journey', 'Ask me anything about content creation', '/uploads/livestreams/live_2.jpg', NULL, NULL, 2800, 5100, 0, 0.00, 'live', '2026-05-21 14:52:18', NULL, '2026-05-21 14:52:18', '2026-05-21 14:52:18');

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

--
-- Dumping data for table `marketplace_categories`
--

INSERT INTO `marketplace_categories` (`id`, `name`, `slug`, `icon`, `cover_url`, `product_count`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Electronics', 'electronics', 'electronics', '/uploads/marketplace/electronics.jpg', 24, 1, 1, '2026-06-05 15:39:58', '2026-06-05 15:39:58'),
(2, 'Fashion', 'fashion', 'fashion', '/uploads/marketplace/fashion.jpg', 18, 2, 1, '2026-06-05 15:39:58', '2026-06-05 15:39:58'),
(3, 'Home', 'home', 'home', '/uploads/marketplace/home.jpg', 12, 3, 1, '2026-06-05 15:39:58', '2026-06-05 15:39:58'),
(4, 'Beauty', 'beauty', 'beauty', '/uploads/marketplace/beauty.jpg', 9, 4, 1, '2026-06-05 15:39:58', '2026-06-05 15:39:58'),
(5, 'Sports', 'sports', 'sports', '/uploads/marketplace/sports.jpg', 7, 5, 1, '2026-06-05 15:39:58', '2026-06-05 15:39:58'),
(6, 'Gaming', 'gaming', 'gaming', '/uploads/marketplace/gaming.jpg', 15, 6, 1, '2026-06-05 15:39:58', '2026-06-05 15:39:58'),
(7, 'Books', 'books', 'books', '/uploads/marketplace/books.jpg', 5, 7, 1, '2026-06-05 15:39:58', '2026-06-05 15:39:58'),
(8, 'Auto', 'auto', 'auto', '/uploads/marketplace/auto.jpg', 3, 8, 1, '2026-06-05 15:39:58', '2026-06-05 15:39:58');

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

INSERT INTO `marketplace_listings` (`id`, `user_id`, `title`, `description`, `price`, `currency`, `image_url`, `images`, `category`, `condition`, `location`, `phone`, `views`, `likes`, `sold`, `status`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'iPhone 15 Pro Max 256GB', 'Brand new, box sealed. Natural Titanium color.', 1199.00, 'USD', '/uploads/marketplace/product_iphone.jpg', NULL, 'Electronics', 'new', 'Nairobi', '+254712345678', 2340, 0, 0, 'active', NULL, '2026-06-04 15:39:58', '2026-06-05 15:39:58'),
(2, 2, 'Sony WH-1000XM5 Headphones', 'Noise-cancelling, premium audio quality. Used 2 weeks.', 349.00, 'USD', '/uploads/marketplace/product_headphones.jpg', NULL, 'Electronics', 'like_new', 'Nairobi', '+254712345678', 5123, 0, 1, 'sold', NULL, '2026-06-03 15:39:58', '2026-06-06 04:09:51'),
(3, 2, 'MacBook Air M3 - 16GB RAM', 'Lightly used, battery cycle: 12. Starlight finish.', 1099.00, 'USD', '/uploads/marketplace/product_laptop.jpg', NULL, 'Electronics', 'good', 'Nairobi', '+254712345678', 1892, 0, 0, 'active', NULL, '2026-06-02 15:39:58', '2026-06-09 00:27:33'),
(4, 2, 'Apple Watch Ultra 2', '49mm Titanium, GPS + Cellular. Brand new.', 799.00, 'USD', '/uploads/marketplace/product_watch.jpg', NULL, 'Electronics', 'new', 'Nairobi', '+254712345678', 980, 0, 0, 'active', NULL, '2026-06-01 15:39:58', '2026-06-05 15:39:58'),
(5, 2, 'Nike Air Max 90 OG', 'Classic infrared colorway, DS (Deadstock).', 130.00, 'USD', '/uploads/marketplace/product_sneakers.jpg', NULL, 'Fashion', 'new', 'Mombasa', '+254723456789', 3212, 0, 0, 'active', NULL, '2026-06-04 15:39:58', '2026-06-16 14:48:11'),
(6, 2, 'Canon EOS R6 Mark II', 'Full-frame mirrorless, 24.2MP, with RF 24-105mm lens kit.', 2499.00, 'USD', '/uploads/marketplace/product_camera.jpg', NULL, 'Electronics', 'like_new', 'Nairobi', '+254734567890', 421, 0, 0, 'active', NULL, '2026-06-03 15:39:58', '2026-06-16 13:24:07'),
(7, 2, 'Ray-Ban Aviator Classic', 'Original polarized G-15 lenses. Gold frame.', 163.00, 'USD', '/uploads/marketplace/product_sunglasses.jpg', NULL, 'Fashion', 'new', 'Kisumu', '+254745678901', 1560, 0, 0, 'active', NULL, '2026-05-31 15:39:58', '2026-06-05 15:39:58'),
(8, 2, 'JBL Flip 6 Portable Speaker', 'IP67 waterproof, 12 hours playtime, black.', 129.00, 'USD', '/uploads/marketplace/product_speaker.jpg', NULL, 'Electronics', 'good', 'Nairobi', '+254756789012', 2890, 0, 0, 'active', NULL, '2026-05-30 15:39:58', '2026-06-05 15:39:58'),
(9, 2, 'North Face Nuptse 1996 Jacket', 'Retro Nuptse, size L, black. Excellent condition.', 280.00, 'USD', '/uploads/marketplace/product_jacket.jpg', NULL, 'Fashion', 'good', 'Nairobi', '+254767890123', 781, 0, 0, 'active', NULL, '2026-06-02 15:39:58', '2026-06-06 12:40:18'),
(10, 2, 'iPad Air M2 256GB', '11-inch, Wi-Fi + Cellular, Space Gray.', 599.00, 'USD', '/uploads/marketplace/product_tablet.jpg', NULL, 'Electronics', 'like_new', 'Mombasa', '+254778901234', 1340, 0, 0, 'active', NULL, '2026-06-01 15:39:58', '2026-06-05 15:39:58'),
(11, 2, 'Herman Miller Aeron Chair', 'Size B, fully loaded, graphite frame.', 450.00, 'USD', '/uploads/marketplace/product_chair.jpg', NULL, 'Home', 'good', 'Nairobi', '+254789012345', 670, 0, 0, 'active', NULL, '2026-05-29 15:39:58', '2026-06-05 15:39:58'),
(12, 2, 'Vintage Leather Backpack', 'Hand-stitched genuine leather, 15\" laptop fits.', 89.00, 'USD', '/uploads/marketplace/product_backpack.jpg', NULL, 'Fashion', 'good', 'Kisumu', '+254790123456', 542, 0, 0, 'active', NULL, '2026-05-28 15:39:58', '2026-06-16 14:51:36');

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
-- Table structure for table `marketplace_wishlist`
--

CREATE TABLE `marketplace_wishlist` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `listing_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `marketplace_wishlist`
--

INSERT INTO `marketplace_wishlist` (`id`, `user_id`, `listing_id`, `created_at`) VALUES
(0, 2, 12, '2026-06-16 14:51:59');

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
(1, 'Afrobeats', 'afrobeats', '#f97316', 'whatshot', '/uploads/music/covers/genre_1.jpg', 6, 1, '2026-05-21 14:52:18', '2026-06-16 21:21:05'),
(2, 'Bongo Flava', 'bongo-flava', '#22c55e', 'music_note', '/uploads/music/covers/genre_2.jpg', 1, 1, '2026-05-21 14:52:18', '2026-06-16 21:21:05'),
(3, 'Hip Hop', 'hip-hop', '#a855f7', 'headset', '/uploads/music/covers/genre_3.jpg', 1, 1, '2026-05-21 14:52:18', '2026-06-16 21:21:05'),
(4, 'RnB / Soul', 'rnb-soul', '#ec4899', 'favorite', '/uploads/music/covers/genre_4.jpg', 2, 1, '2026-05-21 14:52:18', '2026-06-16 21:21:05'),
(5, 'Gospel', 'gospel', '#f59e0b', 'church', '/uploads/music/covers/genre_5.jpg', 0, 1, '2026-05-21 14:52:18', '2026-06-16 21:21:05'),
(6, 'Dancehall', 'dancehall', '#06b6d4', 'celebration', '/uploads/music/covers/genre_6.jpg', 2, 1, '2026-05-21 14:52:18', '2026-06-16 21:21:05'),
(7, 'Amapiano', 'amapiano', '#ef4444', 'nightlife', '/uploads/music/covers/genre_7.jpg', 2, 1, '2026-05-21 14:52:18', '2026-06-16 21:21:05'),
(8, 'Gengetone', 'gengetone', '#6366f1', 'album', '/uploads/music/covers/genre_8.jpg', 1, 1, '2026-05-21 14:52:18', '2026-06-16 21:21:05');

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
(1, 'Kukua (Remix)', 'Zara Ke', 2, '/uploads/music/artists/avatar_1.jpg', '/uploads/music/covers/track_1.jpg', '/uploads/music/audio/kukua_remix.mp3', '/uploads/music/video/kukua_remix.mp4', 214, 1, 2450004, 89001, 12400, 45000, 1, 1, 0, 'published', '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-06-16 20:25:32'),
(2, 'Sauti Ya Nairobi', 'DJ MixMaster', 2, '/uploads/music/artists/avatar_2.jpg', '/uploads/music/covers/track_2.jpg', '/uploads/music/audio/sauti_ya_nairobi.mp3', '/uploads/music/video/sauti_ya_nairobi.mp4', 198, 1, 1850002, 67000, 8900, 32000, 1, 1, 0, 'published', '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-06-16 19:47:00'),
(3, 'Late Night Vibes', 'Neo Soundz', 2, '/uploads/music/artists/avatar_3.jpg', '/uploads/music/covers/track_3.jpg', '/uploads/music/audio/late_night_vibes.mp3', '/uploads/music/video/late_night_vibes.mp4', 247, 3, 1200001, 45000, 5600, 21000, 1, 0, 0, 'published', '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-06-16 20:53:20'),
(4, 'African Dream', 'Kilele', 2, '/uploads/music/artists/avatar_4.jpg', '/uploads/music/covers/track_4.jpg', '/uploads/music/audio/african_dream.mp3', '/uploads/music/video/african_dream.mp4', 232, 1, 980000, 34000, 4300, 18000, 0, 1, 0, 'published', '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(5, 'Sherehe', 'Fiesta Band', 2, '/uploads/music/artists/avatar_5.jpg', '/uploads/music/covers/track_5.jpg', '/uploads/music/audio/sherehe.mp3', '/uploads/music/video/sherehe.mp4', 189, 2, 890000, 28000, 3200, 14000, 1, 0, 0, 'published', '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(6, 'Nakupenda', 'Aisha Flow', 2, '/uploads/music/artists/avatar_6.jpg', '/uploads/music/covers/track_6.jpg', '/uploads/music/audio/nakupenda.mp3', '/uploads/music/video/nakupenda.mp4', 205, 4, 760000, 22000, 2100, 9800, 1, 0, 0, 'published', '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(7, 'Mambo Bora', 'MC Rasta', 2, '/uploads/music/artists/avatar_7.jpg', '/uploads/music/covers/track_7.jpg', '/uploads/music/audio/mambo_bora.mp3', '/uploads/music/video/mambo_bora.mp4', 176, 8, 540000, 15000, 1800, 7600, 0, 0, 1, 'published', '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(8, 'Safari Sounds', 'WildBeats', 2, '/uploads/music/artists/avatar_8.jpg', '/uploads/music/covers/track_8.jpg', '/uploads/music/audio/safari_sounds.mp3', '/uploads/music/video/safari_sounds.mp4', 263, 7, 430001, 12000, 1500, 6200, 0, 0, 0, 'published', '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-06-16 19:46:53'),
(9, 'Leo Niko Ready', 'Bravo Kid', 2, '/uploads/music/artists/avatar_9.jpg', '/uploads/music/covers/track_9.jpg', '/uploads/music/audio/leo_niko_ready.mp3', '/uploads/music/video/leo_niko_ready.mp4', 192, 6, 380000, 11000, 1200, 5400, 1, 0, 1, 'published', '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(10, 'Ulimi Wangu', 'Lena Moon', 2, '/uploads/music/artists/avatar_10.jpg', '/uploads/music/covers/track_10.jpg', '/uploads/music/audio/ulimi_wangu.mp3', '/uploads/music/video/ulimi_wangu.mp4', 228, 4, 310000, 9800, 900, 4200, 0, 1, 0, 'published', '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(0, 'Lagos Nights', 'DJ Pulse', 6, NULL, '/uploads/music/covers/lagos_nights.jpg', '/uploads/music/audio/lagos_nights.mp3', NULL, 245, 1, 450000, 34000, 12000, 0, 1, 0, 0, 'published', NULL, '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(0, 'Sunset in Nairobi', 'DJ Pulse ft. Zara Ke', 6, NULL, '/uploads/music/covers/sunset_nairobi.jpg', '/uploads/music/audio/sunset_nairobi.mp3', NULL, 198, 7, 320000, 28000, 8900, 0, 1, 0, 0, 'published', NULL, '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(0, 'Afro Groove', 'DJ Pulse', 6, NULL, '/uploads/music/covers/afro_groove.jpg', '/uploads/music/audio/afro_groove.mp3', NULL, 210, 1, 210000, 18000, 6700, 0, 1, 0, 0, 'published', NULL, '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(0, 'Island Breeze', 'DJ Pulse', 6, NULL, '/uploads/music/covers/island_breeze.jpg', '/uploads/music/audio/island_breeze.mp3', NULL, 187, 6, 180000, 15000, 5400, 0, 1, 0, 0, 'published', NULL, '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(0, 'Kitchen Beats', 'Chef Kwame', 5, NULL, '/uploads/music/covers/kitchen_beats.jpg', '/uploads/music/audio/kitchen_beats.mp3', NULL, 156, NULL, 89000, 7800, 2100, 0, 1, 0, 0, 'published', NULL, '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(0, 'Workout Fire', 'Fit Sarah', 7, NULL, '/uploads/music/covers/workout_fire.jpg', '/uploads/music/audio/workout_fire.mp3', NULL, 225, NULL, 560000, 45000, 18900, 0, 1, 0, 0, 'published', NULL, '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(0, 'Morning Hustle', 'Fit Sarah', 7, NULL, '/uploads/music/covers/morning_hustle.jpg', '/uploads/music/audio/morning_hustle.mp3', NULL, 178, 1, 320000, 24000, 8700, 0, 1, 0, 0, 'published', NULL, '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(0, 'Road Trip Anthem', 'Travel Dave', 8, NULL, '/uploads/music/covers/road_trip.jpg', '/uploads/music/audio/road_trip.mp3', NULL, 234, NULL, 210000, 12000, 4500, 0, 1, 0, 0, 'published', NULL, '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(0, 'Ocean Waves', 'Travel Dave', 8, NULL, '/uploads/music/covers/ocean_waves.jpg', '/uploads/music/audio/ocean_waves.mp3', NULL, 267, NULL, 98000, 8700, 3200, 0, 1, 0, 0, 'published', NULL, '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(0, 'Digital Dreams', 'Marcus Tech', 3, NULL, '/uploads/music/covers/digital_dreams.jpg', '/uploads/music/audio/digital_dreams.mp3', NULL, 190, NULL, 145000, 12000, 4500, 0, 1, 0, 0, 'published', NULL, '2026-06-16 18:22:20', '2026-06-16 18:22:20');

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
(2, 2, 'verification', 'Account Verified!', 'Your creator account has been verified.', NULL, 0, '2026-05-21 14:52:18'),
(3, 1, 'follow', 'New Follower', 'Zara Ke started following you.', '{\"follower_id\":2,\"follower_name\":\"Zara Ke\"}', 0, '2026-06-12 20:49:42'),
(0, 1, 'follow', 'New Follower', 'Zara Ke started following you.', '{\"follower_id\":2,\"follower_name\":\"Zara Ke\"}', 0, '2026-06-16 18:02:35'),
(0, 1, 'follow', 'New Follower', 'Zara Ke started following you.', '{\"follower_id\":2,\"follower_name\":\"Zara Ke\"}', 0, '2026-06-16 18:17:45'),
(0, 3, 'follow', 'New Follower', 'Zara Ke started following you.', '{\"follower_id\":2,\"follower_name\":\"Zara Ke\"}', 0, '2026-06-16 18:24:15'),
(0, 3, 'like', 'Post Liked', 'Zara Ke liked your post.', '{\"from_user_id\":2,\"post_id\":0}', 0, '2026-06-16 18:24:18'),
(0, 4, 'follow', 'New Follower', 'Zara Ke started following you.', '{\"follower_id\":2,\"follower_name\":\"Zara Ke\"}', 0, '2026-06-16 18:24:24'),
(0, 4, 'follow', 'New Follower', 'Zara Ke started following you.', '{\"follower_id\":2,\"follower_name\":\"Zara Ke\"}', 0, '2026-06-16 18:24:32'),
(0, 6, 'follow', 'New Follower', 'Zara Ke started following you.', '{\"follower_id\":2,\"follower_name\":\"Zara Ke\"}', 0, '2026-06-16 18:24:52'),
(0, 5, 'follow', 'New Follower', 'Zara Ke started following you.', '{\"follower_id\":2,\"follower_name\":\"Zara Ke\"}', 0, '2026-06-16 18:24:56'),
(0, 3, 'follow', 'New Follower', 'Zara Ke started following you.', '{\"follower_id\":2,\"follower_name\":\"Zara Ke\"}', 0, '2026-06-16 18:28:37'),
(0, 7, 'follow', 'New Follower', 'Zara Ke started following you.', '{\"follower_id\":2,\"follower_name\":\"Zara Ke\"}', 0, '2026-06-16 18:28:49'),
(0, 3, 'share', 'Post Shared', 'Zara Ke shared your post.', '{\"from_user_id\":2,\"post_id\":0}', 0, '2026-06-16 18:30:26'),
(0, 8, 'follow', 'New Follower', 'Zara Ke started following you.', '{\"follower_id\":2,\"follower_name\":\"Zara Ke\"}', 0, '2026-06-16 18:31:20'),
(0, 3, 'follow', 'New Follower', 'Zara Ke started following you.', '{\"follower_id\":2,\"follower_name\":\"Zara Ke\"}', 0, '2026-06-16 19:05:52'),
(0, 4, 'follow', 'New Follower', 'Zara Ke started following you.', '{\"follower_id\":2,\"follower_name\":\"Zara Ke\"}', 0, '2026-06-16 19:05:56'),
(0, 5, 'follow', 'New Follower', 'Zara Ke started following you.', '{\"follower_id\":2,\"follower_name\":\"Zara Ke\"}', 0, '2026-06-16 19:05:59'),
(0, 3, 'follow', 'New Follower', 'Zara Ke started following you.', '{\"follower_id\":2,\"follower_name\":\"Zara Ke\"}', 0, '2026-06-16 19:22:04'),
(0, 6, 'follow', 'New Follower', 'Zara Ke started following you.', '{\"follower_id\":2,\"follower_name\":\"Zara Ke\"}', 0, '2026-06-16 19:22:11'),
(0, 7, 'follow', 'New Follower', 'Zara Ke started following you.', '{\"follower_id\":2,\"follower_name\":\"Zara Ke\"}', 0, '2026-06-16 19:22:14'),
(0, 3, 'like', 'Post Liked', 'Zara Ke liked your post.', '{\"from_user_id\":2,\"post_id\":0}', 0, '2026-06-18 08:26:54');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(20) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','confirmed','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `shipping_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(10) DEFAULT 'USD',
  `billing_name` varchar(255) DEFAULT NULL,
  `billing_email` varchar(255) DEFAULT NULL,
  `billing_phone` varchar(30) DEFAULT NULL,
  `billing_address` text DEFAULT NULL,
  `billing_city` varchar(100) DEFAULT NULL,
  `billing_state` varchar(100) DEFAULT NULL,
  `billing_zip` varchar(20) DEFAULT NULL,
  `billing_country` varchar(100) DEFAULT 'Kenya',
  `delivery_name` varchar(255) DEFAULT NULL,
  `delivery_phone` varchar(30) DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `delivery_city` varchar(100) DEFAULT NULL,
  `delivery_state` varchar(100) DEFAULT NULL,
  `delivery_zip` varchar(20) DEFAULT NULL,
  `delivery_country` varchar(100) DEFAULT NULL,
  `delivery_instructions` text DEFAULT NULL,
  `payment_method` varchar(30) DEFAULT NULL,
  `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `payment_reference` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `status`, `subtotal`, `shipping_cost`, `tax`, `total`, `currency`, `billing_name`, `billing_email`, `billing_phone`, `billing_address`, `billing_city`, `billing_state`, `billing_zip`, `billing_country`, `delivery_name`, `delivery_phone`, `delivery_address`, `delivery_city`, `delivery_state`, `delivery_zip`, `delivery_country`, `delivery_instructions`, `payment_method`, `payment_status`, `payment_reference`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'ORD-EC1C73B9E8', 2, 'pending', 2388.00, 0.00, 382.08, 2770.08, 'USD', 'Zara Ke', 'zarake@dttube.com', '', '', '', '', '', 'Kenya', 'Zara Ke', '', '', '', '', '', 'Kenya', '', 'mpesa', 'pending', NULL, NULL, '2026-06-16 14:22:49', '2026-06-16 14:22:49'),
(2, 'ORD-47DAB21066', 2, 'pending', 130.00, 0.00, 20.80, 150.80, 'USD', 'Zara Ke', 'zarake@dttube.com', '', '', '', '', '', 'Kenya', 'Zara Ke', '', '', '', '', '', 'Kenya', '', 'paystack', 'pending', NULL, NULL, '2026-06-16 14:31:00', '2026-06-16 14:31:00'),
(3, 'ORD-90ED7B17AD', 2, 'pending', 2499.00, 0.00, 399.84, 2898.84, 'USD', 'Zara Ke', 'zarake@dttube.com', '', 'rryjh', '', '', '', 'Kenya', 'Zara Ke', '', 'rryjh', '', '', '', 'Kenya', '', 'stripe', 'pending', NULL, NULL, '2026-06-16 14:31:34', '2026-06-16 14:31:34'),
(4, 'ORD-1FD4C279CE', 2, 'pending', 280.00, 0.00, 44.80, 324.80, 'USD', 'Zara Ke', 'zarake@dttube.com', '', '', '', '', '', 'Kenya', 'Zara Ke', '', '', '', '', '', 'Kenya', '', 'mpesa', 'pending', NULL, NULL, '2026-06-16 14:34:00', '2026-06-16 14:34:00');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `listing_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `image_url` varchar(500) DEFAULT NULL,
  `seller_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `listing_id`, `title`, `price`, `quantity`, `image_url`, `seller_id`, `created_at`) VALUES
(1, 1, 1, 'iPhone 15 Pro Max 256GB', 1199.00, 1, '/uploads/marketplace/product_iphone.jpg', 2, '2026-06-16 14:22:49'),
(2, 1, 4, 'Apple Watch Ultra 2', 799.00, 1, '/uploads/marketplace/product_watch.jpg', 2, '2026-06-16 14:22:49'),
(3, 1, 5, 'Nike Air Max 90 OG', 130.00, 3, '/uploads/marketplace/product_sneakers.jpg', 2, '2026-06-16 14:22:49'),
(4, 2, 5, 'Nike Air Max 90 OG', 130.00, 1, '/uploads/marketplace/product_sneakers.jpg', 2, '2026-06-16 14:31:00'),
(5, 3, 6, 'Canon EOS R6 Mark II', 2499.00, 1, '/uploads/marketplace/product_camera.jpg', 2, '2026-06-16 14:31:34'),
(6, 4, 9, 'North Face Nuptse 1996 Jacket', 280.00, 1, '/uploads/marketplace/product_jacket.jpg', 2, '2026-06-16 14:34:01');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(30) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(30) DEFAULT 'payments',
  `is_enabled` tinyint(1) DEFAULT 0,
  `config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`config`)),
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `slug`, `display_name`, `description`, `icon`, `is_enabled`, `config`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Paystack', 'paystack', 'Paystack', 'Pay with card, bank transfer, or USSD', 'credit_card', 1, NULL, 1, '2026-06-16 14:08:52', '2026-06-16 14:08:52'),
(2, 'M-Pesa', 'mpesa', 'M-Pesa', 'Pay via M-Pesa (Safaricom)', 'phone_android', 1, NULL, 2, '2026-06-16 14:08:52', '2026-06-16 14:08:52'),
(3, 'Stripe', 'stripe', 'Stripe', 'Pay with international cards', 'credit_card', 1, NULL, 3, '2026-06-16 14:08:52', '2026-06-16 14:08:52'),
(4, 'Binance Pay', 'binance', 'Binance Pay', 'Pay with cryptocurrency', 'currency_bitcoin', 0, NULL, 4, '2026-06-16 14:08:52', '2026-06-16 14:08:52'),
(5, 'PesaPal', 'pesapal', 'PesaPal', 'Pay via PesaPal (East Africa)', 'account_balance', 0, NULL, 5, '2026-06-16 14:08:52', '2026-06-16 14:08:52');

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
(1, 2, 'Just dropped a new dance challenge! Who can keep up? ???????? #KukuaChallenge #DTTube', '/uploads/posts/post_1.jpg', 12400, 843, 2100, 'published', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(2, 2, 'New afrobeats pack dropping this Friday on my creator store! ???????? Pre-order now', '/uploads/posts/post_2.jpg', 3200, 199, 567, 'published', '2026-05-21 14:52:18', '2026-06-13 02:20:15'),
(3, 2, 'When your African mom discovers you have a wallet balance ???????? #Comedy #Relatable', NULL, 24304, 3801, 8900, 'published', '2026-05-21 14:52:18', '2026-06-07 19:41:45'),
(0, 3, 'Just unboxed the new Samsung Galaxy S25 Ultra! This camera is INSANE 📱🔥 Full review dropping tomorrow. #TechReview #GalaxyS25', '/uploads/posts/post_5.jpg', 8900, 432, 1201, 'published', '2026-06-16 18:22:20', '2026-06-18 11:27:03'),
(0, 3, 'Built a gaming PC under $800 that runs Cyberpunk at 1440p. African tech is leveling up! 🎮💻 Link in bio for full parts list.', '/uploads/posts/post_6.jpg', 12400, 678, 3401, 'published', '2026-06-16 18:22:20', '2026-06-18 11:27:03'),
(0, 4, 'New makeup tutorial is LIVE! Soft glam for date night 💄✨ Products tagged in the video. #MakeupTutorial #SoftGlam', '/uploads/posts/post_3.jpg', 15600, 1200, 4501, 'published', '2026-06-16 18:22:20', '2026-06-18 11:27:03'),
(0, 4, 'Skincare routine for melanin-rich skin that ACTUALLY works 🧴✨ 3 months of testing — here is the result!', NULL, 21000, 2100, 7801, 'published', '2026-06-16 18:22:20', '2026-06-18 11:27:03'),
(0, 5, 'New recipe just dropped! Jollof rice — the Ghanaian way 🇬🇭🍚 Come fight me in the comments 😂 #JollofWars', '/uploads/posts/post_2.jpg', 18300, 3400, 8901, 'published', '2026-06-16 18:22:20', '2026-06-18 11:27:03'),
(0, 5, 'Behind the scenes at my Accra restaurant. We served 200 plates tonight! 🍽️ So grateful for this journey.', '/uploads/posts/post_4.jpg', 7800, 567, 1201, 'published', '2026-06-16 18:22:20', '2026-06-18 11:27:03'),
(0, 6, 'New mixtape OUT NOW! \"Lagos Nights Vol. 3\" — 45 minutes of pure fire 🔥🎧 Stream link in bio!', '/uploads/posts/post_1.jpg', 32200, 4100, 15601, 'published', '2026-06-16 18:22:20', '2026-06-18 11:27:03'),
(0, 6, 'Studio session with Burna Boy\'s producer last night. Something BIG is coming 🎵👀 #Afrobeats #NewMusic', NULL, 45000, 5600, 21001, 'published', '2026-06-16 18:22:20', '2026-06-18 11:27:03'),
(0, 7, 'Day 1 vs Day 90 transformation of my client James. Consistency beats motivation every time! 💪🔥 DM \"TRANSFORM\" to start.', '/uploads/posts/post_4.jpg', 28900, 3400, 12001, 'published', '2026-06-16 18:22:20', '2026-06-18 11:27:03'),
(0, 7, '5-minute morning stretch routine that changed my life. No equipment needed! Save this for tomorrow morning ☀️🧘‍♀️', NULL, 16700, 890, 8901, 'published', '2026-06-16 18:22:20', '2026-06-18 11:27:03'),
(0, 8, 'Arrived in Zanzibar! The water is impossibly blue 😍🏝️ First 24 hours vlog coming soon. #Zanzibar #Tanzania', '/uploads/posts/post_3.jpg', 13400, 890, 3401, 'published', '2026-06-16 18:22:20', '2026-06-18 11:27:03'),
(0, 8, 'Just crossed into Rwanda! The roads here are immaculate. Cleanest country in Africa, no debate 🇷🇼✨', '/uploads/posts/post_5.jpg', 9800, 670, 2101, 'published', '2026-06-16 18:22:20', '2026-06-18 11:27:03');

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
(1, 2, 'Dance Challenge #Kukua', 'Can you keep up with the Kukua challenge? ????????', '/uploads/thumbnails/reel_thumb_1.jpg', '/uploads/reels/reel1.mp4', 30, 2450000, 24501, 1800, 5200, 'Kukua Beat - DJ MixMaster', NULL, 'Dance', NULL, 'published', 1, NULL, 87.5, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-06-16 21:15:14'),
(2, 2, 'Cooking Jollof Rice', 'My secret recipe revealed! ????', '/uploads/thumbnails/reel_thumb_2.jpg', '/uploads/reels/reel2.mp4', 45, 1800000, 18900, 3200, 8100, 'Afro Kitchen Vibes - BeatMaker', NULL, 'Food', NULL, 'published', 0, NULL, 72.3, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(3, 2, 'Nairobi Night Vibes', 'Night out in the city ????', '/uploads/thumbnails/reel_thumb_3.jpg', '/uploads/reels/reel3.mp4', 22, 980000, 9800, 560, 1900, 'Original Sound - ZaraKe', NULL, 'Lifestyle', NULL, 'published', 0, NULL, 65.1, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(4, 2, 'AI Art is Crazy', 'Watch what AI created in 30 seconds ????', '/uploads/thumbnails/reel_thumb_4.jpg', '/uploads/reels/reel4.mp4', 58, 3100000, 31200, 5600, 12401, 'Digital Dreams - SynthWave', NULL, 'Tech', NULL, 'published', 1, NULL, 91.2, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-06-06 11:11:56'),
(5, 2, 'Afrobeats Studio Session', 'New track dropping this Friday ????????', '/uploads/thumbnails/reel_thumb_5.jpg', '/uploads/reels/reel5.mp4', 35, 1200000, 12100, 890, 3400, 'Afro Drop (Preview) - BeatMaker', NULL, 'Music', NULL, 'published', 0, NULL, 68.7, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(6, 2, 'Comedy: African Mom', 'When your African mom finds your wallet balance ????????', '/uploads/thumbnails/reel_thumb_6.jpg', '/uploads/reels/reel6.mp4', 40, 5600000, 56300, 8900, 24101, 'Original Sound - ZaraKe', NULL, 'Comedy', NULL, 'published', 1, NULL, 95.8, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-06-07 17:05:09'),
(7, 2, 'Skincare Routine', 'The routine that ACTUALLY works ???', '/uploads/thumbnails/reel_thumb_7.jpg', '/uploads/reels/reel7.mp4', 55, 870000, 8700, 421, 1900, 'Soft Vibes - ChillBeats', NULL, 'Beauty', NULL, 'published', 0, NULL, 54.2, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(8, 2, 'Football Skills', 'Check these moves ???', '/uploads/thumbnails/reel_thumb_8.jpg', '/uploads/reels/reel8.mp4', 28, 4200000, 42001, 3400, 11200, 'Goal Mix - DJ Sports', NULL, 'Sports', NULL, 'published', 0, NULL, 82.4, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-06-09 08:03:35'),
(0, 3, 'iPhone 16 vs S25 Ultra Camera Test', 'Which camera wins? 📱📸', '/uploads/thumbnails/reel_thumb_mt1.jpg', '/uploads/reels/reel_mt1.gif', 45, 3450000, 34500, 2100, 8900, 'Tech Beats - SynthMaster', NULL, 'Tech', NULL, 'published', 0, NULL, 58.6, '2026-06-16 18:22:20', '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(0, 3, 'Building a Gaming PC in 60s', 'Timelapse build 🖥️⚡', '/uploads/thumbnails/reel_thumb_mt2.jpg', '/uploads/reels/reel_mt2.gif', 60, 2100000, 21000, 1200, 5600, 'Digital Dreams - BeatMaker', NULL, 'Tech', NULL, 'published', 1, NULL, 59.6, '2026-06-16 18:22:20', '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(0, 4, '5-Minute Soft Glam Tutorial', 'Perfect for date night! 💄✨', '/uploads/thumbnails/reel_thumb_ab1.jpg', '/uploads/reels/reel_ab1.gif', 30, 5200000, 52000, 4500, 21000, 'Soft Vibes - ChillBeats', NULL, 'Beauty', NULL, 'published', 1, NULL, 52.8, '2026-06-16 18:22:20', '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(0, 4, 'Skincare Products I Regret Buying', 'Save your money! 💸', '/uploads/thumbnails/reel_thumb_ab2.jpg', '/uploads/reels/reel_ab2.gif', 40, 1900000, 18900, 3400, 7800, 'Original Sound - AminaBeauty', NULL, 'Beauty', NULL, 'published', 0, NULL, 73.4, '2026-06-16 18:22:20', '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(0, 5, 'Perfect Jollof in 3 Steps', 'The secret is in the firewood 🔥🍚', '/uploads/thumbnails/reel_thumb_ck1.jpg', '/uploads/reels/reel_ck1.gif', 55, 6700000, 67000, 8900, 34000, 'Afro Kitchen Vibes - BeatMaker', NULL, 'Food', NULL, 'published', 1, NULL, 74.5, '2026-06-16 18:22:20', '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(0, 5, 'Making Fufu from Scratch', 'Traditional African food 🍲', '/uploads/thumbnails/reel_thumb_ck2.jpg', '/uploads/reels/reel_ck2.gif', 50, 4500000, 45000, 5600, 18900, 'Original Sound - ChefKwame', NULL, 'Food', NULL, 'published', 1, NULL, 91.5, '2026-06-16 18:22:20', '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(0, 6, 'Afrobeats Mix — Live from Lagos', 'Turn up the volume! 🎧🔥', '/uploads/thumbnails/reel_thumb_dp1.jpg', '/uploads/reels/reel_dp1.gif', 35, 8900000, 89000, 7800, 45000, 'Afro Drop - DJ Pulse', NULL, 'Music', NULL, 'published', 0, NULL, 90, '2026-06-16 18:22:20', '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(0, 6, 'Crowd Reaction to New Drop', 'They went CRAZY! 🤯🔥', '/uploads/thumbnails/reel_thumb_dp2.jpg', '/uploads/reels/reel_dp2.gif', 25, 11000000, 110000, 9800, 56000, 'New Drop (Preview) - DJ Pulse', NULL, 'Music', NULL, 'published', 1, NULL, 82, '2026-06-16 18:22:20', '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(0, 7, '10-Minute Ab Workout', 'No equipment, just results 💪', '/uploads/thumbnails/reel_thumb_fs1.jpg', '/uploads/reels/reel_fs1.gif', 30, 4100000, 41000, 2300, 15600, 'Workout Beats - GymMix', NULL, 'Fitness', NULL, 'published', 0, NULL, 77.6, '2026-06-16 18:22:20', '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(0, 7, 'What I Eat in a Day (Athlete)', 'Fuel your body right 🥗', '/uploads/thumbnails/reel_thumb_fs2.jpg', '/uploads/reels/reel_fs2.gif', 45, 2800000, 28000, 1800, 8900, 'Healthy Vibes - ChillBeats', NULL, 'Fitness', NULL, 'published', 1, NULL, 67.5, '2026-06-16 18:22:20', '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(0, 8, 'Zanzibar Beach at Sunrise', 'This is paradise! 🌅🏝️', '/uploads/thumbnails/reel_thumb_td1.jpg', '/uploads/reels/reel_td1.gif', 20, 3800000, 38000, 1900, 12300, 'Island Vibes - OceanBeats', NULL, 'Travel', NULL, 'published', 0, NULL, 94.9, '2026-06-16 18:22:20', '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(0, 8, 'Gorilla Trekking in Uganda', 'Once in a lifetime! 🦍🇺🇬', '/uploads/thumbnails/reel_thumb_td2.jpg', '/uploads/reels/reel_td2.gif', 38, 5600000, 56000, 4500, 23400, 'Jungle Drums - TribalMix', NULL, 'Travel', NULL, 'published', 1, NULL, 67, '2026-06-16 18:22:20', '2026-06-16 18:22:20', '2026-06-16 18:22:20');

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
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `spotlight_ads`
--

INSERT INTO `spotlight_ads` (`id`, `title`, `subtitle`, `image_url`, `link_url`, `badge`, `badge_color`, `sort_order`, `is_active`, `starts_at`, `ends_at`, `created_at`, `updated_at`) VALUES
(1, 'Shop the Latest Drops', 'Exclusive deals on trending products', '/uploads/home/card_1.jpg', '/marketplace', 'Shop', '#22c55e', 1, 1, NULL, NULL, '2026-06-12 20:46:41', '2026-06-12 20:46:41'),
(2, 'Go Live & Earn', 'Start streaming and receive gifts from fans', '/uploads/home/card_2.jpg', '/livestream/start', 'Stream', '#ef4444', 2, 1, NULL, NULL, '2026-06-12 20:46:41', '2026-06-12 20:46:41'),
(3, 'Discover New Music', 'Stream the hottest Afrobeats tracks', '/uploads/home/card_3.jpg', '/music', 'Music', '#ec4899', 3, 1, NULL, NULL, '2026-06-12 20:46:41', '2026-06-12 20:46:41');

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

INSERT INTO `stream_gifts` (`id`, `name`, `description`, `icon`, `image_url`, `price_usd`, `color_class`, `is_active`, `is_animated`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Heart', NULL, 'favorite', NULL, 0.50, 'text-red-400', 1, 0, 1, '2026-06-12 20:46:41', '2026-06-12 20:46:41'),
(2, 'Fire', NULL, 'local_fire_department', NULL, 1.00, 'text-orange-400', 1, 0, 2, '2026-06-12 20:46:41', '2026-06-12 20:46:41'),
(3, 'Star', NULL, 'star', NULL, 2.50, 'text-yellow-400', 1, 0, 3, '2026-06-12 20:46:41', '2026-06-12 20:46:41'),
(4, 'Diamond', NULL, 'diamond', NULL, 5.00, 'text-cyan-400', 1, 0, 4, '2026-06-12 20:46:41', '2026-06-12 20:46:41'),
(5, 'Crown', NULL, 'crown', NULL, 10.00, 'text-amber-400', 1, 0, 5, '2026-06-12 20:46:41', '2026-06-12 20:46:41'),
(6, 'Rocket', NULL, 'rocket_launch', NULL, 20.00, 'text-purple-400', 1, 0, 6, '2026-06-12 20:46:41', '2026-06-12 20:46:41'),
(7, 'Party', NULL, 'celebration', NULL, 50.00, 'text-pink-400', 1, 0, 7, '2026-06-12 20:46:41', '2026-06-12 20:46:41'),
(8, 'Super', NULL, 'bolt', NULL, 100.00, 'text-brand-400', 1, 0, 8, '2026-06-12 20:46:41', '2026-06-12 20:46:41');

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
-- Table structure for table `support_categories`
--

CREATE TABLE `support_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `icon` varchar(30) DEFAULT 'help',
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `support_categories`
--

INSERT INTO `support_categories` (`id`, `name`, `slug`, `icon`, `is_active`, `sort_order`) VALUES
(1, 'General', 'general', 'help', 1, 1),
(2, 'Technical', 'technical', 'build', 1, 2),
(3, 'Billing', 'billing', 'payments', 1, 3),
(4, 'Account', 'account', 'person', 1, 4),
(5, 'Marketplace', 'marketplace', 'storefront', 1, 5),
(6, 'Other', 'other', 'more_horiz', 1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `support_contacts`
--

CREATE TABLE `support_contacts` (
  `id` int(11) NOT NULL,
  `type` enum('email','phone','whatsapp','address','social') NOT NULL,
  `label` varchar(100) NOT NULL,
  `value` varchar(255) NOT NULL,
  `icon` varchar(30) DEFAULT 'info',
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `support_contacts`
--

INSERT INTO `support_contacts` (`id`, `type`, `label`, `value`, `icon`, `is_active`, `sort_order`) VALUES
(1, 'email', 'Email Support', 'support@globiim.com', 'email', 1, 1),
(2, 'phone', 'Phone', '+254 712 345 678', 'phone', 1, 2),
(3, 'whatsapp', 'WhatsApp', '+254 712 345 678', 'chat', 1, 3),
(4, 'address', 'Office', 'Nairobi, Kenya', 'location_on', 1, 4),
(5, 'social', 'Twitter', '@globiim', 'alternate_email', 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ticket_number` varchar(20) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `subject` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT 'general',
  `priority` enum('low','medium','high','urgent') DEFAULT 'medium',
  `status` enum('open','in_progress','waiting','resolved','closed') DEFAULT 'open',
  `last_reply_by` enum('user','admin') DEFAULT 'user',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_ticket_messages`
--

CREATE TABLE `support_ticket_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ticket_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `profile_type` enum('personal','creator','business','government') DEFAULT 'personal',
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('user','creator','admin','super_admin') DEFAULT 'user',
  `provider` varchar(50) DEFAULT NULL,
  `provider_id` varchar(255) DEFAULT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `phone_verified_at` datetime DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `is_banned` tinyint(1) DEFAULT 0,
  `last_login_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `avatar`, `bio`, `profile_type`, `phone`, `role`, `provider`, `provider_id`, `email_verified_at`, `phone_verified_at`, `is_verified`, `is_banned`, `last_login_at`, `created_at`, `updated_at`) VALUES
(1, 'DTTube Admin', 'admin', 'admin@dttube.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '/uploads/profiles/admin.jpg', 'Platform administrator', 'personal', NULL, 'admin', NULL, NULL, '2026-05-21 14:52:18', NULL, 1, 0, NULL, '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(2, 'Zara Ke', 'zarake', 'zarake@dttube.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '/uploads/profiles/zarake.jpg', 'Dancer & content creator from Nairobi ????????', 'personal', '', 'creator', NULL, NULL, '2026-05-21 14:52:18', NULL, 1, 0, NULL, '2026-05-21 14:52:18', '2026-06-06 10:39:17'),
(3, 'Marcus Tech', 'marcustech', 'marcus@dttube.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '/uploads/profiles/marcustech.jpg', 'Tech reviewer & gadget unboxer from Lagos 🇳🇬', 'personal', NULL, 'creator', NULL, NULL, '2026-06-16 18:22:20', NULL, 1, 0, NULL, '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(4, 'Amina Beauty', 'aminabeauty', 'amina@dttube.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '/uploads/profiles/aminabeauty.jpg', 'Beauty influencer & makeup artist | Nairobi 💄✨', 'personal', NULL, 'creator', NULL, NULL, '2026-06-16 18:22:20', NULL, 1, 0, NULL, '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(5, 'Chef Kwame', 'chefkwame', 'kwame@dttube.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '/uploads/profiles/chefkwame.jpg', 'Award-winning chef | African cuisine redefined 🍲🇬🇭', 'personal', NULL, 'creator', NULL, NULL, '2026-06-16 18:22:20', NULL, 1, 0, NULL, '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(6, 'DJ Pulse', 'djpulse', 'djpulse@dttube.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '/uploads/profiles/djpulse.jpg', 'Afrobeats DJ & producer | Bookings: djpulse@dttube.com 🎧', 'personal', NULL, 'creator', NULL, NULL, '2026-06-16 18:22:20', NULL, 1, 0, NULL, '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(7, 'Fit Sarah', 'fitsarah', 'sarah@dttube.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '/uploads/profiles/fitsarah.jpg', 'Certified fitness coach | Transform your body in 90 days 💪🏋️‍♀️', 'personal', NULL, 'creator', NULL, NULL, '2026-06-16 18:22:20', NULL, 1, 0, NULL, '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(8, 'Travel Dave', 'traveldave', 'dave@dttube.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '/uploads/profiles/traveldave.jpg', 'Exploring Africa one country at a time 🌍✈️ | 42/54', 'personal', NULL, 'creator', NULL, NULL, '2026-06-16 18:22:20', NULL, 1, 0, NULL, '2026-06-16 18:22:20', '2026-06-16 18:22:20');

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
(1, 2, 'Building a Startup in Africa - Full Documentary', 'The complete journey of building a tech startup from Nairobi', '/uploads/thumbnails/reel_thumb_1.jpg', '/uploads/videos/video1.mp4', NULL, 2720, 1200000, 45001, 2301, 8900, 'Tech', NULL, 'published', 1, 1, NULL, 78.5, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-06-09 15:38:15'),
(2, 2, 'How to Make Money as a Creator in 2025', 'Complete guide to monetizing your content', '/uploads/thumbnails/reel_thumb_2.jpg', '/uploads/videos/video2.mp4', NULL, 1335, 890000, 32000, 1800, 6700, 'Business', NULL, 'published', 1, 1, NULL, 73.2, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(3, 2, 'Lagos to Nairobi: Road Trip Vlog', 'Cross-country adventure through East and West Africa', '/uploads/thumbnails/reel_thumb_3.jpg', '/uploads/videos/video3.mp4', NULL, 1905, 670000, 21000, 980, 4500, 'Travel', NULL, 'published', 0, 1, NULL, 61.8, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(4, 2, 'Learn Flutter in 2 Hours - Complete Course', 'Full Flutter development course for beginners', '/uploads/thumbnails/reel_thumb_4.jpg', '/uploads/videos/video4.mp4', NULL, 7290, 2100000, 89001, 5600, 23000, 'Education', NULL, 'published', 1, 1, NULL, 92.1, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-06-06 12:29:22'),
(5, 2, 'Best Afrobeat Mix 2025', 'Non-stop afrobeats mix for your playlist', '/uploads/thumbnails/reel_thumb_5.jpg', '/uploads/videos/video5.mp4', NULL, 4500, 3500000, 120000, 7800, 34000, 'Music', NULL, 'published', 1, 1, NULL, 96.3, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-05-21 14:52:18'),
(6, 2, 'Street Food Tour: Accra Edition', 'Trying the best street food in Accra, Ghana', '/uploads/thumbnails/reel_thumb_6.jpg', '/uploads/videos/video6.mp4', NULL, 1110, 450000, 15000, 670, 2101, 'Food', NULL, 'published', 0, 1, NULL, 58.4, '2026-05-21 14:52:18', '2026-05-21 14:52:18', '2026-06-14 03:07:04'),
(0, 3, 'Samsung Galaxy S25 Ultra — Full Review', 'Deep dive into camera, battery, and AI features', '/uploads/thumbnails/reel_thumb_7.jpg', '/uploads/videos/video_mt1.mp4', NULL, 1845, 890000, 34002, 2101, 7801, 'Tech', NULL, 'published', 1, 1, NULL, 61.5, '2026-06-16 18:22:20', '2026-06-16 18:22:20', '2026-06-16 21:34:22'),
(0, 3, 'Best Budget Phones 2025 — Under KES 30k', 'Top picks for the Kenyan market', '/uploads/thumbnails/reel_thumb_8.jpg', '/uploads/videos/video_mt2.mp4', NULL, 1320, 560000, 21002, 1451, 4501, 'Tech', NULL, 'published', 1, 1, NULL, 69, '2026-06-16 18:22:20', '2026-06-16 18:22:20', '2026-06-16 21:34:22'),
(0, 4, 'Bridal Makeup Masterclass — African Wedding', 'Complete bridal look tutorial for melanin skin', '/uploads/thumbnails/reel_thumb_7.jpg', '/uploads/videos/video_ab1.mp4', NULL, 2400, 1500000, 67002, 4501, 23001, 'Beauty', NULL, 'published', 0, 1, NULL, 79.9, '2026-06-16 18:22:20', '2026-06-16 18:22:20', '2026-06-16 21:34:22'),
(0, 4, 'My Morning Skincare Routine 2025', 'Step-by-step: cleanser, serum, moisturizer, SPF', '/uploads/thumbnails/reel_thumb_8.jpg', '/uploads/videos/video_ab2.mp4', NULL, 1080, 780000, 32002, 2101, 8901, 'Beauty', NULL, 'published', 0, 1, NULL, 92.5, '2026-06-16 18:22:20', '2026-06-16 18:22:20', '2026-06-16 21:34:22'),
(0, 5, 'Ultimate African Street Food Tour — Accra', 'Trying 15 different street foods in one day!', '/uploads/thumbnails/reel_thumb_1.jpg', '/uploads/videos/video_ck1.mp4', NULL, 3600, 2800000, 110002, 8901, 45001, 'Food', NULL, 'published', 0, 1, NULL, 81, '2026-06-16 18:22:20', '2026-06-16 18:22:20', '2026-06-16 21:34:22'),
(0, 5, 'Cooking For 100 People — Community Feast', 'We cooked a massive meal for a village in Ghana', '/uploads/thumbnails/reel_thumb_2.jpg', '/uploads/videos/video_ck2.mp4', NULL, 2700, 1800000, 78002, 5601, 21001, 'Food', NULL, 'published', 1, 1, NULL, 89.6, '2026-06-16 18:22:20', '2026-06-16 18:22:20', '2026-06-16 21:34:22'),
(0, 6, 'Lagos Nights Vol. 3 — Full Mixtape', '45 minutes of non-stop afrobeats and amapiano', '/uploads/thumbnails/reel_thumb_3.jpg', '/uploads/videos/video_dp1.mp4', NULL, 2700, 4200000, 180002, 12001, 67001, 'Music', NULL, 'published', 1, 1, NULL, 93.1, '2026-06-16 18:22:20', '2026-06-16 18:22:20', '2026-06-16 21:34:22'),
(0, 6, 'How I Produce an Afrobeats Hit — From Scratch', 'FL Studio tutorial: drums, melody, mixdown', '/uploads/thumbnails/reel_thumb_4.jpg', '/uploads/videos/video_dp2.mp4', NULL, 2100, 1200000, 54002, 3401, 18901, 'Music', NULL, 'published', 1, 1, NULL, 81.4, '2026-06-16 18:22:20', '2026-06-16 18:22:20', '2026-06-16 21:34:22'),
(0, 7, '90-Day Body Transformation — Full Guide', 'Workout plan, meal prep, and mindset', '/uploads/thumbnails/reel_thumb_5.jpg', '/uploads/videos/video_fs1.mp4', NULL, 3300, 3400000, 145002, 8901, 56001, 'Fitness', NULL, 'published', 1, 1, NULL, 88, '2026-06-16 18:22:20', '2026-06-16 18:22:20', '2026-06-16 21:34:22'),
(0, 7, 'Home Workout: No Equipment Full Body', 'Get fit without a gym membership', '/uploads/thumbnails/reel_thumb_6.jpg', '/uploads/videos/video_fs2.mp4', NULL, 1800, 2100000, 89002, 4501, 34001, 'Fitness', NULL, 'published', 1, 1, NULL, 66.2, '2026-06-16 18:22:20', '2026-06-16 18:22:20', '2026-06-16 21:34:22'),
(0, 8, 'Zanzibar Travel Guide 2025 — Budget Edition', 'Everything you need: flights, hotels, food, activities', '/uploads/thumbnails/reel_thumb_1.jpg', '/uploads/videos/video_td1.mp4', NULL, 2400, 1900000, 76002, 4501, 28001, 'Travel', NULL, 'published', 1, 1, NULL, 89.3, '2026-06-16 18:22:20', '2026-06-16 18:22:20', '2026-06-16 21:34:22'),
(0, 8, 'Crossing Africa by Road — Kenya to SA', 'The ultimate African road trip adventure', '/uploads/thumbnails/reel_thumb_2.jpg', '/uploads/videos/video_td2.mp4', NULL, 4200, 4500000, 210002, 14501, 89001, 'Travel', NULL, 'published', 1, 1, NULL, 78.3, '2026-06-16 18:22:20', '2026-06-16 18:22:20', '2026-06-16 21:34:22');

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
(2, 2, 14940.00, 'KES', 1, '2026-05-21 14:52:18', '2026-06-09 22:27:19'),
(0, 3, 24741.00, 'KES', 1, '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(0, 4, 47724.00, 'KES', 1, '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(0, 5, 42813.00, 'KES', 1, '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(0, 6, 35667.00, 'KES', 1, '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(0, 7, 7142.00, 'KES', 1, '2026-06-16 18:22:20', '2026-06-16 18:22:20'),
(0, 8, 46815.00, 'KES', 1, '2026-06-16 18:22:20', '2026-06-16 18:22:20');

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
-- Indexes for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_bookmark` (`user_id`,`bookmarkable_type`,`bookmarkable_id`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart` (`user_id`,`listing_id`);

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
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_order_number` (`order_number`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order` (`order_id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `support_categories`
--
ALTER TABLE `support_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `support_contacts`
--
ALTER TABLE `support_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_number` (`ticket_number`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `support_ticket_messages`
--
ALTER TABLE `support_ticket_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ticket` (`ticket_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `support_categories`
--
ALTER TABLE `support_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `support_contacts`
--
ALTER TABLE `support_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_ticket_messages`
--
ALTER TABLE `support_ticket_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `support_ticket_messages`
--
ALTER TABLE `support_ticket_messages`
  ADD CONSTRAINT `support_ticket_messages_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
