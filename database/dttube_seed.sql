USE `dttube`;

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `avatar`, `bio`, `role`, `provider`, `provider_id`, `email_verified_at`, `is_verified`, `created_at`, `updated_at`) VALUES
(1, 'DTTube Admin', 'admin', 'admin@dttube.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '/uploads/profiles/admin.jpg', 'Platform administrator', 'admin', NULL, NULL, NOW(), 1, NOW(), NOW()),
(2, 'Zara Ke', 'zarake', 'zarake@dttube.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '/uploads/profiles/zarake.jpg', 'Dancer & content creator from Nairobi 💃🔥', 'creator', NULL, NULL, NOW(), 1, NOW(), NOW());

INSERT INTO `wallets` (`id`, `user_id`, `balance`, `currency`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 0.00, 'KES', 1, NOW(), NOW()),
(2, 2, 15000.00, 'KES', 1, NOW(), NOW());

INSERT INTO `followers` (`follower_id`, `following_id`, `created_at`) VALUES
(1, 2, NOW());

INSERT INTO `reels` (`id`, `user_id`, `title`, `description`, `thumbnail`, `video_url`, `duration`, `views`, `likes`, `comments_count`, `shares`, `song_name`, `category`, `status`, `is_featured`, `viral_score`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'Dance Challenge #Kukua', 'Can you keep up with the Kukua challenge? 🔥💃', '/uploads/thumbnails/reel_thumb_1.jpg', '/uploads/reels/reel1.gif', 30, 2450000, 24500, 1800, 5200, 'Kukua Beat - DJ MixMaster', 'Dance', 'published', 1, 87.5, NOW(), NOW(), NOW()),
(2, 2, 'Cooking Jollof Rice', 'My secret recipe revealed! 🍚', '/uploads/thumbnails/reel_thumb_2.jpg', '/uploads/reels/reel2.gif', 45, 1800000, 18900, 3200, 8100, 'Afro Kitchen Vibes - BeatMaker', 'Food', 'published', 0, 72.3, NOW(), NOW(), NOW()),
(3, 2, 'Nairobi Night Vibes', 'Night out in the city 🌃', '/uploads/thumbnails/reel_thumb_3.jpg', '/uploads/reels/reel3.gif', 22, 980000, 9800, 560, 1900, 'Original Sound - ZaraKe', 'Lifestyle', 'published', 0, 65.1, NOW(), NOW(), NOW()),
(4, 2, 'AI Art is Crazy', 'Watch what AI created in 30 seconds 🤖', '/uploads/thumbnails/reel_thumb_4.jpg', '/uploads/reels/reel4.gif', 25, 3100000, 31200, 5600, 12400, 'Digital Dreams - SynthWave', 'Tech', 'published', 1, 91.2, NOW(), NOW(), NOW()),
(5, 2, 'Afrobeats Studio Session', 'New track dropping this Friday 🎧🔥', '/uploads/thumbnails/reel_thumb_5.jpg', '/uploads/reels/reel5.gif', 35, 1200000, 12100, 890, 3400, 'Afro Drop (Preview) - BeatMaker', 'Music', 'published', 0, 68.7, NOW(), NOW(), NOW()),
(6, 2, 'Comedy: African Mom', 'When your African mom finds your wallet balance 😂💰', '/uploads/thumbnails/reel_thumb_6.jpg', '/uploads/reels/reel6.gif', 40, 5600000, 56300, 8900, 24100, 'Original Sound - ZaraKe', 'Comedy', 'published', 1, 95.8, NOW(), NOW(), NOW()),
(7, 2, 'Skincare Routine', 'The routine that ACTUALLY works ✨', '/uploads/thumbnails/reel_thumb_7.jpg', '/uploads/reels/reel7.gif', 30, 870000, 8700, 421, 1900, 'Soft Vibes - ChillBeats', 'Beauty', 'published', 0, 54.2, NOW(), NOW(), NOW()),
(8, 2, 'Football Skills', 'Check these moves ⚽', '/uploads/thumbnails/reel_thumb_8.jpg', '/uploads/reels/reel8.gif', 28, 4200000, 42000, 3400, 11200, 'Goal Mix - DJ Sports', 'Sports', 'published', 0, 82.4, NOW(), NOW(), NOW());

INSERT INTO `videos` (`id`, `user_id`, `title`, `description`, `thumbnail`, `video_url`, `duration`, `views`, `likes`, `comments_count`, `shares`, `category`, `status`, `is_featured`, `is_monetized`, `viral_score`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'Building a Startup in Africa - Full Documentary', 'The complete journey of building a tech startup from Nairobi', '/uploads/thumbnails/reel_thumb_1.jpg', '/uploads/videos/video1.mp4', 2720, 1200000, 45000, 2300, 8900, 'Tech', 'published', 1, 1, 78.5, NOW(), NOW(), NOW()),
(2, 2, 'How to Make Money as a Creator in 2025', 'Complete guide to monetizing your content', '/uploads/thumbnails/reel_thumb_2.jpg', '/uploads/videos/video2.mp4', 1335, 890000, 32000, 1800, 6700, 'Business', 'published', 1, 1, 73.2, NOW(), NOW(), NOW()),
(3, 2, 'Lagos to Nairobi: Road Trip Vlog', 'Cross-country adventure through East and West Africa', '/uploads/thumbnails/reel_thumb_3.jpg', '/uploads/videos/video3.mp4', 1905, 670000, 21000, 980, 4500, 'Travel', 'published', 0, 1, 61.8, NOW(), NOW(), NOW()),
(4, 2, 'Learn Flutter in 2 Hours - Complete Course', 'Full Flutter development course for beginners', '/uploads/thumbnails/reel_thumb_4.jpg', '/uploads/videos/video4.mp4', 7290, 2100000, 89000, 5600, 23000, 'Education', 'published', 1, 1, 92.1, NOW(), NOW(), NOW()),
(5, 2, 'Best Afrobeat Mix 2025', 'Non-stop afrobeats mix for your playlist', '/uploads/thumbnails/reel_thumb_5.jpg', '/uploads/videos/video5.mp4', 4500, 3500000, 120000, 7800, 34000, 'Music', 'published', 1, 1, 96.3, NOW(), NOW(), NOW()),
(6, 2, 'Street Food Tour: Accra Edition', 'Trying the best street food in Accra, Ghana', '/uploads/thumbnails/reel_thumb_6.jpg', '/uploads/videos/video6.mp4', 1110, 450000, 15000, 670, 2100, 'Food', 'published', 0, 1, 58.4, NOW(), NOW(), NOW());

INSERT INTO `posts` (`id`, `user_id`, `content`, `image_url`, `likes`, `comments_count`, `shares`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'Just dropped a new dance challenge! Who can keep up? 🔥💃 #KukuaChallenge #DTTube', '/uploads/posts/post_1.jpg', 12400, 843, 2100, 'published', NOW(), NOW()),
(2, 2, 'New afrobeats pack dropping this Friday on my creator store! 🎧🔥 Pre-order now', '/uploads/posts/post_2.jpg', 3200, 198, 567, 'published', NOW(), NOW()),
(3, 2, 'When your African mom discovers you have a wallet balance 😂💰 #Comedy #Relatable', NULL, 24300, 3800, 8900, 'published', NOW(), NOW());

INSERT INTO `livestreams` (`id`, `user_id`, `title`, `description`, `thumbnail`, `stream_key`, `stream_url`, `viewers`, `peak_viewers`, `total_likes`, `total_gifts`, `gift_earnings`, `status`, `started_at`, `ended_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'Late Night Music Session', 'Live music and chat 🎵', '/uploads/livestreams/live_1.jpg', 'seed_sk_1', '/livestream/1', 4200, 8500, 234, 56, 4250.00, 'live', NOW(), NULL, NOW(), NOW()),
(2, 2, 'Q&A: Starting Your Creator Journey', 'Ask me anything about content creation', '/uploads/livestreams/live_2.jpg', 'seed_sk_2', '/livestream/2', 2800, 5100, 89, 23, 1820.00, 'live', NOW(), NULL, NOW(), NOW()),
(3, 2, 'Weekend Vibes: Acoustic Session', 'Unplugged acoustic performances', '/uploads/livestreams/live_3.jpg', 'seed_sk_3', '/livestream/3', 0, 0, 0, 0, 0.00, 'scheduled', DATE_ADD(NOW(), INTERVAL 2 DAY), NULL, NOW(), NOW()),
(4, 2, 'Creator Workshop: Editing Tips', 'Learn video editing like a pro', '/uploads/livestreams/live_4.jpg', 'seed_sk_4', '/livestream/4', 0, 0, 0, 0, 0.00, 'scheduled', DATE_ADD(NOW(), INTERVAL 1 DAY), NULL, NOW(), NOW()),
(5, 1, 'DTTube Town Hall', 'Platform updates and roadmap discussion', '/uploads/livestreams/live_5.jpg', 'seed_sk_5', '/livestream/5', 12500, 18200, 567, 89, 8900.00, 'ended', DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY), NOW(), NOW()),
(6, 2, 'Afrobeats Dance Tutorial', 'Learn the hottest dance moves', '/uploads/livestreams/live_6.jpg', 'seed_sk_6', '/livestream/6', 8900, 12400, 345, 67, 5600.00, 'ended', DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY), NOW(), NOW());

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `body`, `is_read`, `created_at`) VALUES
(1, 1, 'welcome', 'Welcome to DTTube!', 'Your admin account is ready.', 0, NOW()),
(2, 2, 'verification', 'Account Verified!', 'Your creator account has been verified.', 0, NOW());

INSERT INTO `stream_gifts` (`id`, `name`, `icon`, `price_usd`, `color_class`, `is_active`, `sort_order`, `created_at`) VALUES
(1, 'Heart', 'favorite', 0.50, 'text-red-400', 1, 1, NOW()),
(2, 'Fire', 'local_fire_department', 1.00, 'text-orange-400', 1, 2, NOW()),
(3, 'Star', 'star', 2.50, 'text-yellow-400', 1, 3, NOW()),
(4, 'Diamond', 'diamond', 5.00, 'text-cyan-400', 1, 4, NOW()),
(5, 'Crown', 'crown', 10.00, 'text-amber-400', 1, 5, NOW()),
(6, 'Rocket', 'rocket_launch', 20.00, 'text-purple-400', 1, 6, NOW()),
(7, 'Party', 'celebration', 50.00, 'text-pink-400', 1, 7, NOW()),
(8, 'Super', 'bolt', 100.00, 'text-brand-400', 1, 8, NOW());

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

INSERT INTO `marketplace_categories` (`id`, `name`, `slug`, `icon`, `cover_url`, `product_count`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Electronics', 'electronics', 'electronics', '/uploads/marketplace/electronics.jpg', 24, 1, 1, NOW(), NOW()),
(2, 'Fashion', 'fashion', 'fashion', '/uploads/marketplace/fashion.jpg', 18, 2, 1, NOW(), NOW()),
(3, 'Home', 'home', 'home', '/uploads/marketplace/home.jpg', 12, 3, 1, NOW(), NOW()),
(4, 'Beauty', 'beauty', 'beauty', '/uploads/marketplace/beauty.jpg', 9, 4, 1, NOW(), NOW()),
(5, 'Sports', 'sports', 'sports', '/uploads/marketplace/sports.jpg', 7, 5, 1, NOW(), NOW()),
(6, 'Gaming', 'gaming', 'gaming', '/uploads/marketplace/gaming.jpg', 15, 6, 1, NOW(), NOW()),
(7, 'Books', 'books', 'books', '/uploads/marketplace/books.jpg', 5, 7, 1, NOW(), NOW()),
(8, 'Auto', 'auto', 'auto', '/uploads/marketplace/auto.jpg', 3, 8, 1, NOW(), NOW());

