<?php $activeTab = 'menu'; $title = 'Marketplace - DTTube'; $hideTopNav = true; ?>
<?php
$user = \Core\Auth::user();

// Use fallback data
$categories = $data['categories'] ?? [
    ['name' => 'Electronics', 'icon' => 'electronics', 'count' => 24],
    ['name' => 'Fashion', 'icon' => 'fashion', 'count' => 18],
    ['name' => 'Home', 'icon' => 'home', 'count' => 12],
    ['name' => 'Beauty', 'icon' => 'beauty', 'count' => 9],
    ['name' => 'Sports', 'icon' => 'sports', 'count' => 7],
    ['name' => 'Gaming', 'icon' => 'gaming', 'count' => 15],
    ['name' => 'Books', 'icon' => 'books', 'count' => 5],
    ['name' => 'Auto', 'icon' => 'auto', 'count' => 3],
];

$featured = $data['featured'] ?? [
    ['id' => 1, 'title' => 'iPhone 15 Pro Max', 'price' => '$1,199', 'old_price' => '$1,399', 'image' => '/uploads/marketplace/product_iphone.jpg', 'rating' => 4.8, 'reviews' => 2340, 'discount' => '-14%', 'badge' => 'Hot'],
    ['id' => 2, 'title' => 'Sony WH-1000XM5', 'price' => '$349', 'old_price' => '$399', 'image' => '/uploads/marketplace/product_headphones.jpg', 'rating' => 4.9, 'reviews' => 5120, 'discount' => '-12%', 'badge' => 'Best Seller'],
    ['id' => 3, 'title' => 'MacBook Air M3', 'price' => '$1,099', 'old_price' => '$1,299', 'image' => '/uploads/marketplace/product_laptop.jpg', 'rating' => 4.7, 'reviews' => 1890, 'discount' => '-15%', 'badge' => 'New'],
    ['id' => 4, 'title' => 'Apple Watch Ultra 2', 'price' => '$799', 'old_price' => '$899', 'image' => '/uploads/marketplace/product_watch.jpg', 'rating' => 4.6, 'reviews' => 980, 'discount' => '-11%', 'badge' => ''],
    ['id' => 5, 'title' => 'Nike Air Max 90', 'price' => '$130', 'old_price' => '$180', 'image' => '/uploads/marketplace/product_sneakers.jpg', 'rating' => 4.5, 'reviews' => 3210, 'discount' => '-28%', 'badge' => 'Sale'],
];

$trending = $data['trending'] ?? [
    ['id' => 6, 'title' => 'Canon EOS R6 II', 'price' => '$2,499', 'old_price' => '', 'image' => '/uploads/marketplace/product_camera.jpg', 'rating' => 4.8, 'reviews' => 420],
    ['id' => 7, 'title' => 'Ray-Ban Aviator', 'price' => '$163', 'old_price' => '$220', 'image' => '/uploads/marketplace/product_sunglasses.jpg', 'rating' => 4.4, 'reviews' => 1560, 'discount' => '-26%'],
    ['id' => 8, 'title' => 'JBL Flip 6', 'price' => '$129', 'old_price' => '', 'image' => '/uploads/marketplace/product_speaker.jpg', 'rating' => 4.6, 'reviews' => 2890],
    ['id' => 9, 'title' => 'North Face Jacket', 'price' => '$280', 'old_price' => '$350', 'image' => '/uploads/marketplace/product_jacket.jpg', 'rating' => 4.3, 'reviews' => 780, 'discount' => '-20%'],
    ['id' => 10, 'title' => 'iPad Air M2', 'price' => '$599', 'old_price' => '$749', 'image' => '/uploads/marketplace/product_tablet.jpg', 'rating' => 4.7, 'reviews' => 1340, 'discount' => '-20%'],
    ['id' => 11, 'title' => 'Ergonomic Chair Pro', 'price' => '$450', 'old_price' => '$599', 'image' => '/uploads/marketplace/product_chair.jpg', 'rating' => 4.5, 'reviews' => 670, 'discount' => '-25%'],
];

$flashDeals = $data['flashDeals'] ?? [
    ['id' => 12, 'title' => 'Wireless Earbuds Pro', 'price' => '$49', 'old_price' => '$99', 'image' => '/uploads/marketplace/flash_1.jpg', 'rating' => 4.3, 'reviews' => 4520, 'discount' => '-50%', 'time_left' => '02:14:36'],
    ['id' => 13, 'title' => 'Smart Band 8', 'price' => '$29', 'old_price' => '$59', 'image' => '/uploads/marketplace/flash_2.jpg', 'rating' => 4.1, 'reviews' => 2340, 'discount' => '-51%', 'time_left' => '05:42:18'],
    ['id' => 14, 'title' => 'USB-C Hub 7-in-1', 'price' => '$24', 'old_price' => '$45', 'image' => '/uploads/marketplace/flash_3.jpg', 'rating' => 4.4, 'reviews' => 1890, 'discount' => '-47%', 'time_left' => '01:58:52'],
    ['id' => 15, 'title' => 'Mini Drone HD', 'price' => '$79', 'old_price' => '$159', 'image' => '/uploads/marketplace/flash_4.jpg', 'rating' => 4.2, 'reviews' => 890, 'discount' => '-50%', 'time_left' => '03:21:05'],
];

$dealBanners = $data['dealBanners'] ?? [
    ['image' => '/uploads/marketplace/deal_banner_1.jpg', 'title' => 'Flash Sale', 'subtitle' => 'Limited Time Offer'],
    ['image' => '/uploads/marketplace/deal_banner_2.jpg', 'title' => 'New Arrivals', 'subtitle' => 'Just Landed'],
    ['image' => '/uploads/marketplace/deal_banner_3.jpg', 'title' => 'Free Shipping', 'subtitle' => 'Orders over $50'],
];

$topRated = $data['topRated'] ?? [
    ['id' => 1, 'title' => 'iPhone 15 Pro Max', 'price' => '$1,199', 'image' => '/uploads/marketplace/product_iphone.jpg', 'rating' => 4.8, 'reviews' => 2340],
    ['id' => 2, 'title' => 'Sony WH-1000XM5', 'price' => '$349', 'image' => '/uploads/marketplace/product_headphones.jpg', 'rating' => 4.9, 'reviews' => 5120],
    ['id' => 6, 'title' => 'Canon EOS R6 II', 'price' => '$2,499', 'image' => '/uploads/marketplace/product_camera.jpg', 'rating' => 4.8, 'reviews' => 420],
    ['id' => 10, 'title' => 'iPad Air M2', 'price' => '$599', 'image' => '/uploads/marketplace/product_tablet.jpg', 'rating' => 4.7, 'reviews' => 1340],
    ['id' => 3, 'title' => 'MacBook Air M3', 'price' => '$1,099', 'image' => '/uploads/marketplace/product_laptop.jpg', 'rating' => 4.7, 'reviews' => 1890],
    ['id' => 8, 'title' => 'JBL Flip 6', 'price' => '$129', 'image' => '/uploads/marketplace/product_speaker.jpg', 'rating' => 4.6, 'reviews' => 2890],
];

$listings = $data['listings'] ?? [];
?>
<?php ob_start(); ?>
<style>
    .marketplace-page { background: #000105; min-height: 100vh; padding-bottom: 80px; }
    .mk-search-bar { background: rgba(20,20,28,0.85); border: 1px solid rgba(101,37,248,0.2); border-radius: 14px; }
    .mk-search-bar:focus-within { border-color: rgba(101,37,248,0.5); box-shadow: 0 0 20px rgba(101,37,248,0.1); }
    .mk-promo-banner { border-radius: 20px; overflow: hidden; position: relative; }
    .mk-promo-banner::after { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(101,37,248,0.3) 0%, rgba(33,6,77,0.5) 100%); }
    .mk-category-item { transition: transform 0.2s ease; }
    .mk-category-item:active { transform: scale(0.92); }
    .mk-category-icon { width: 58px; height: 58px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: linear-gradient(145deg, #21064D 0%, #6525F8 100%); box-shadow: 0 4px 15px rgba(101,37,248,0.3); }
    .mk-product-card { background: #0B0221; border-radius: 16px; overflow: hidden; border: 1px solid rgba(255,255,255,0.04); transition: all 0.3s ease; }
    .mk-product-card:hover { border-color: rgba(101,37,248,0.3); transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.4); }
    .mk-product-card img { width: 100%; aspect-ratio: 1; object-fit: cover; }
    .mk-discount-badge { background: #CC1821; color: white; font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 8px; position: absolute; top: 8px; left: 8px; z-index: 2; }
    .mk-hot-badge { background: linear-gradient(135deg, #FF6B35, #CC1821); color: white; font-size: 9px; font-weight: 700; padding: 2px 8px; border-radius: 8px; position: absolute; top: 8px; right: 8px; z-index: 2; text-transform: uppercase; letter-spacing: 0.5px; }
    .mk-stars { color: #FFB800; font-size: 11px; letter-spacing: -1px; }
    .mk-price { color: #F5F5F7; font-weight: 700; font-size: 14px; }
    .mk-old-price { color: #5C5C5C; font-size: 11px; text-decoration: line-through; }
    .mk-section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
    .mk-section-title { color: #F5F5F7; font-size: 17px; font-weight: 700; }
    .mk-see-all { color: #BCBCBE; font-size: 12px; font-weight: 500; text-decoration: none; }
    .mk-flash-card { background: #F0F0F0; border-radius: 14px; overflow: hidden; }
    .mk-flash-card img { width: 100%; aspect-ratio: 1; object-fit: cover; background: #E8E8E8; }
    .mk-flash-card .mk-flash-info { padding: 8px 10px; }
    .mk-flash-card .mk-flash-title { color: #1a1a1a; font-size: 12px; font-weight: 600; }
    .mk-flash-card .mk-flash-price { color: #CC1821; font-size: 13px; font-weight: 700; }
    .mk-deal-banner { border-radius: 16px; overflow: hidden; flex-shrink: 0; width: 260px; }
    .mk-timer { display: inline-flex; gap: 3px; align-items: center; }
    .mk-timer-box { background: rgba(255,255,255,0.12); color: white; font-size: 11px; font-weight: 700; padding: 2px 5px; border-radius: 6px; font-family: 'Space Grotesk', monospace; }
    .mk-timer-sep { color: rgba(255,255,255,0.5); font-size: 10px; font-weight: 700; }
    .mk-bottom-nav { background: rgba(0,1,5,0.96); backdrop-filter: blur(20px); border-top: 1px solid rgba(255,255,255,0.06); }
    .mk-nav-item { display: flex; flex-direction: column; align-items: center; gap: 2px; padding: 6px 0; text-decoration: none; min-width: 56px; }
    .mk-nav-item span.nav-icon { font-size: 24px; color: #8E8F94; }
    .mk-nav-item span.nav-label { font-size: 10px; color: #8E8F94; font-weight: 500; }
    .mk-nav-item.active span.nav-icon { color: #C694F5; }
    .mk-nav-item.active span.nav-label { color: #C694F5; }
    .mk-fab { width: 52px; height: 52px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: linear-gradient(180deg, #FDFDFD 0%, #FCE3FF 15%, #641FF4 60%, #572C97 100%); box-shadow: 0 4px 20px rgba(101,37,248,0.5); margin-top: -22px; }
    .mk-fab span { color: white; font-size: 26px; }
    .mk-sold-badge { position: absolute; inset: 0; background: rgba(0,0,0,0.65); display: flex; align-items: center; justify-content: center; z-index: 3; }
    .mk-sold-badge span { background: rgba(204,24,33,0.85); color: white; font-size: 11px; font-weight: 700; padding: 4px 14px; border-radius: 20px; text-transform: uppercase; letter-spacing: 1px; }
    .mk-qty-badge { background: linear-gradient(135deg, #6525F8, #BE92FF); color: white; font-size: 10px; font-weight: 600; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; position: absolute; top: -4px; right: -4px; }
    .mk-icon-btn { width: 36px; height: 36px; border-radius: 12px; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease; }
    .mk-icon-btn-purple { background: linear-gradient(135deg, #6525F8, #BE92FF); }
    .mk-icon-btn-ghost { background: rgba(255,255,255,0.08); }
    .mk-icon-btn-ghost:hover { background: rgba(255,255,255,0.14); }
    .mk-notification-dot { width: 8px; height: 8px; background: #CC1821; border-radius: 50%; position: absolute; top: 6px; right: 6px; border: 2px solid #000105; }
    .mk-scroll-row { display: flex; gap: 12px; overflow-x: auto; padding-bottom: 4px; scroll-snap-type: x mandatory; }
    .mk-scroll-row::-webkit-scrollbar { display: none; }
    .mk-scroll-row { -ms-overflow-style: none; scrollbar-width: none; }
    .mk-scroll-row > * { scroll-snap-align: start; flex-shrink: 0; }
    .mk-heart-btn { width: 32px; height: 32px; border-radius: 50%; background: rgba(0,0,0,0.5); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; position: absolute; bottom: 8px; right: 8px; z-index: 2; cursor: pointer; transition: all 0.2s ease; border: none; }
    .mk-heart-btn:hover { background: rgba(204,24,33,0.7); }
    .mk-heart-btn:hover span { color: white; }
    .mk-heart-btn span { color: rgba(255,255,255,0.8); font-size: 18px; }
    .mk-cart-quick-btn { width: 32px; height: 32px; border-radius: 50%; background: rgba(101,37,248,0.7); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; position: absolute; bottom: 8px; right: 48px; z-index: 2; cursor: pointer; transition: all 0.2s ease; border: none; }
    .mk-cart-quick-btn:hover { background: rgba(101,37,248,1); transform: scale(1.08); }
    .mk-cart-quick-btn span { color: white; font-size: 18px; }
    .mk-add-cart-btn { background: linear-gradient(135deg, #6525F8, #9333ea); color: white; border: none; border-radius: 10px; padding: 6px 14px; font-size: 11px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; }
    .mk-add-cart-btn:hover { opacity: 0.9; transform: scale(1.03); }
</style>

<div class="marketplace-page">
    <!-- Top Header -->
    <div style="padding: 16px 16px 0; position: sticky; top: 0; z-index: 40; background: rgba(0,1,5,0.95); backdrop-filter: blur(16px);">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
            <a href="/" style="width: 38px; height: 38px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: rgba(20,20,28,0.85); text-decoration: none;">
                <span class="material-icons-round" style="color: #F3F4F6; font-size: 20px;">arrow_back</span>
            </a>
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 34px; height: 34px; border-radius: 10px; background: linear-gradient(135deg, #6525F8, #BE92FF); display: flex; align-items: center; justify-content: center;">
                    <span class="material-icons-round" style="color: white; font-size: 20px;">storefront</span>
                </div>
                <span style="color: #F5F5F7; font-size: 20px; font-weight: 800; font-family: 'Space Grotesk', sans-serif;">Marketplace</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <a href="/notifications" class="mk-icon-btn mk-icon-btn-ghost" style="position: relative; text-decoration: none;">
                    <span class="material-icons-round" style="color: #F3F4F6; font-size: 20px;">notifications_none</span>
                    <div class="mk-notification-dot" id="notif-dot" style="display: none;"></div>
                </a>
                <a href="/marketplace/cart" class="mk-icon-btn mk-icon-btn-purple" style="position: relative; text-decoration: none;">
                    <span class="material-icons-round" style="color: white; font-size: 20px;">shopping_cart</span>
                    <span id="header-cart-badge" style="position:absolute;top:-6px;right:-6px;background:#CC1821;color:white;font-size:10px;font-weight:700;min-width:18px;height:18px;border-radius:9px;display:none;align-items:center;justify-content:center;line-height:1;">0</span>
                </a>
            </div>
        </div>

        <!-- Search Bar -->
        <form action="/marketplace" method="GET" style="margin:0;">
        <div class="mk-search-bar" style="display: flex; align-items: center; padding: 10px 14px; margin-bottom: 16px;">
            <span class="material-icons-round" style="color: #878789; font-size: 20px; margin-right: 10px;">search</span>
            <input type="text" name="search" placeholder="Search products, brands..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" style="flex: 1; background: transparent; border: none; outline: none; color: #F5F5F7; font-size: 14px;" />
            <button type="submit" style="background:none;border:none;cursor:pointer;padding:0;display:flex;">
                <span class="material-icons-round" style="color: #878789; font-size: 20px; margin-left: 8px;">tune</span>
            </button>
        </div>
        </form>
    </div>

    <!-- Promo Banner -->
    <div style="padding: 0 16px; margin-bottom: -20px; position: relative; z-index: 1;">
        <div class="mk-promo-banner" style="height: 160px;">
            <img src="/uploads/marketplace/promo_banner.jpg" alt="Mega Sale" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
    </div>

    <!-- Category Icons -->
    <div style="padding: 0 8px; margin-top: 12px; position: relative; z-index: 2; margin-bottom: 20px;">
        <div class="mk-scroll-row" style="padding: 0 12px; gap: 18px; justify-content: space-between;">
            <?php foreach ($categories as $cat): ?>
            <a href="/marketplace?category=<?= urlencode($cat['name']) ?>" class="mk-category-item" style="display: flex; flex-direction: column; align-items: center; gap: 6px; text-decoration: none; min-width: 58px;">
                <div class="mk-category-icon">
                    <img src="/uploads/marketplace/<?= htmlspecialchars($cat['icon'] ?? 'electronics') ?>.jpg" alt="<?= htmlspecialchars($cat['name']) ?>" style="width: 58px; height: 58px; border-radius: 50%; object-fit: cover;">
                </div>
                <span style="color: #C1C1C3; font-size: 10px; font-weight: 500;"><?= htmlspecialchars($cat['name']) ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Featured Products - Horizontal Scroll -->
    <div style="padding: 0 16px; margin-bottom: 24px;">
        <div class="mk-section-header">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="mk-section-title">Featured</span>
                <span class="material-icons-round mk-stars" style="font-size: 16px;">star</span>
                <span class="material-icons-round mk-stars" style="font-size: 16px;">star</span>
                <span class="material-icons-round mk-stars" style="font-size: 16px;">star</span>
                <span class="material-icons-round mk-stars" style="font-size: 16px;">star</span>
                <span class="material-icons-round mk-stars" style="font-size: 16px;">star_half</span>
            </div>
            <a href="#" class="mk-see-all">See All ›</a>
        </div>
        <div class="mk-scroll-row" style="margin: 0 -16px; padding: 0 16px;">
            <?php foreach ($featured as $p): ?>
            <a href="/marketplace/<?= $p['id'] ?>" class="mk-product-card" style="width: 155px; text-decoration: none;">
                <div style="position: relative;">
                    <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['title']) ?>">
                    <?php if (!empty($p['discount'])): ?>
                    <div class="mk-discount-badge"><?= htmlspecialchars($p['discount']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($p['badge'])): ?>
                    <div class="mk-hot-badge"><?= htmlspecialchars($p['badge']) ?></div>
                    <?php endif; ?>
                    <button class="mk-heart-btn" onclick="event.preventDefault();">
                        <span class="material-icons-round">favorite_border</span>
                    </button>
<button class="mk-cart-quick-btn" onclick="event.preventDefault();event.stopPropagation();quickAddCart(<?= $p['id'] ?>)">
                        <span class="material-icons-round">shopping_cart</span>
                    </button>
                </div>
                <div style="padding: 10px 10px 12px;">
                    <p style="color: #F5F5F7; font-size: 12px; font-weight: 600; margin: 0 0 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($p['title']) ?></p>
                    <div style="display: flex; align-items: center; gap: 4px; margin-bottom: 4px;">
                        <span class="material-icons-round mk-stars" style="font-size: 13px;">star</span>
                        <span style="color: #878789; font-size: 10px;"><?= number_format((float)$p['rating'], 1) ?> (<?= number_format((int)$p['reviews']) ?>)</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <span class="mk-price"><?= htmlspecialchars($p['price']) ?></span>
                        <?php if (!empty($p['old_price'])): ?>
                        <span class="mk-old-price"><?= htmlspecialchars($p['old_price']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Deal Banners Row -->
    <div style="padding: 0 16px; margin-bottom: 24px;">
        <div class="mk-scroll-row" style="margin: 0 -16px; padding: 0 16px;">
            <?php foreach ($dealBanners as $banner): ?>
            <div class="mk-deal-banner" style="height: 120px; position: relative;">
                <img src="<?= htmlspecialchars($banner['image']) ?>" alt="<?= htmlspecialchars($banner['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                <div style="position: absolute; inset: 0; display: flex; flex-direction: column; justify-content: center; padding: 16px;">
                    <span style="color: white; font-size: 16px; font-weight: 700;"><?= htmlspecialchars($banner['title']) ?></span>
                    <span style="color: rgba(255,255,255,0.8); font-size: 11px; font-weight: 500;"><?= htmlspecialchars($banner['subtitle']) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Trending Now - Product Grid -->
    <div style="padding: 0 16px; margin-bottom: 24px;">
        <div class="mk-section-header">
            <span class="mk-section-title">Trending Now</span>
            <a href="#" class="mk-see-all">See All ›</a>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <?php foreach ($trending as $p): ?>
            <a href="/marketplace/<?= $p['id'] ?>" class="mk-product-card" style="text-decoration: none;">
                <div style="position: relative;">
                    <img src="<?= htmlspecialchars($p['image'] ?? $p['image_url'] ?? '/uploads/marketplace/product_iphone.jpg') ?>" alt="<?= htmlspecialchars($p['title'] ?? '') ?>">
                    <?php if (!empty($p['discount'])): ?>
                    <div class="mk-discount-badge"><?= htmlspecialchars($p['discount']) ?></div>
                    <?php endif; ?>
                    <button class="mk-heart-btn" onclick="event.preventDefault();">
                        <span class="material-icons-round">favorite_border</span>
                    </button>
<button class="mk-cart-quick-btn" onclick="event.preventDefault();event.stopPropagation();quickAddCart(<?= $p['id'] ?>)">
                        <span class="material-icons-round">shopping_cart</span>
                    </button>
                </div>
                <div style="padding: 10px 10px 12px;">
                    <p style="color: #F5F5F7; font-size: 12px; font-weight: 600; margin: 0 0 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($p['title'] ?? '') ?></p>
                    <div style="display: flex; align-items: center; gap: 4px; margin-bottom: 4px;">
                        <span class="material-icons-round mk-stars" style="font-size: 13px;">star</span>
                        <span style="color: #878789; font-size: 10px;"><?= number_format((float)($p['rating'] ?? 4.5), 1) ?> (<?= number_format((int)($p['reviews'] ?? 100)) ?>)</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <span class="mk-price"><?= htmlspecialchars($p['price'] ?? '') ?></span>
                        <?php if (!empty($p['old_price'])): ?>
                        <span class="mk-old-price"><?= htmlspecialchars($p['old_price']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Flash Deals Section -->
    <div style="padding: 0 16px; margin-bottom: 24px;">
        <div class="mk-section-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span class="mk-section-title">⚡ Flash Deals</span>
                <div class="mk-timer">
                    <span class="mk-timer-box">02</span>
                    <span class="mk-timer-sep">:</span>
                    <span class="mk-timer-box">14</span>
                    <span class="mk-timer-sep">:</span>
                    <span class="mk-timer-box">36</span>
                </div>
            </div>
            <a href="#" class="mk-see-all">See All ›</a>
        </div>
        <div class="mk-scroll-row" style="margin: 0 -16px; padding: 0 16px;">
            <?php foreach ($flashDeals as $p): ?>
            <a href="/marketplace/<?= $p['id'] ?>" class="mk-flash-card" style="width: 140px; text-decoration: none;">
                <div style="position: relative;">
                    <img src="<?= htmlspecialchars($p['image'] ?? $p['image_url'] ?? '/uploads/marketplace/product_iphone.jpg') ?>" alt="<?= htmlspecialchars($p['title'] ?? '') ?>">
                    <div class="mk-discount-badge" style="top: 6px; left: 6px; font-size: 10px;"><?= htmlspecialchars($p['discount'] ?? '') ?></div>
                </div>
                <div class="mk-flash-info">
                    <p class="mk-flash-title" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($p['title'] ?? '') ?></p>
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 4px;">
                        <span class="mk-flash-price"><?= htmlspecialchars($p['price'] ?? '') ?></span>
                        <span style="color: #878789; font-size: 9px; text-decoration: line-through;"><?= htmlspecialchars($p['old_price'] ?? '') ?></span>
                    </div>
                    <div style="margin-top: 6px;">
                        <button class="mk-add-cart-btn" style="width: 100%; border-radius: 8px; padding: 5px 0; font-size: 10px;" onclick="event.preventDefault();event.stopPropagation();quickAddCart(<?= $p['id'] ?>)">
                            <span class="material-icons-round" style="font-size: 14px; vertical-align: -3px;">add_shopping_cart</span> Add
                        </button>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Top Rated - Horizontal Scroll Row -->
    <div style="padding: 0 16px; margin-bottom: 24px;">
        <div class="mk-section-header">
            <span class="mk-section-title">🏆 Top Rated</span>
            <a href="#" class="mk-see-all">See All ›</a>
        </div>
        <div class="mk-scroll-row" style="margin: 0 -16px; padding: 0 16px;">
            <?php foreach ($topRated as $p): ?>
            <a href="/marketplace/<?= $p['id'] ?>" class="mk-product-card" style="width: 145px; text-decoration: none;">
                <div style="position: relative;">
                    <img src="<?= htmlspecialchars($p['image'] ?? $p['image_url'] ?? '/uploads/marketplace/product_iphone.jpg') ?>" alt="<?= htmlspecialchars($p['title'] ?? '') ?>">
                    <button class="mk-heart-btn" onclick="event.preventDefault();">
                        <span class="material-icons-round">favorite_border</span>
                    </button>
<button class="mk-cart-quick-btn" onclick="event.preventDefault();event.stopPropagation();quickAddCart(<?= $p['id'] ?>)">
                        <span class="material-icons-round">shopping_cart</span>
                    </button>
                </div>
                <div style="padding: 10px 10px 12px;">
                    <p style="color: #F5F5F7; font-size: 12px; font-weight: 600; margin: 0 0 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($p['title'] ?? '') ?></p>
                    <div style="display: flex; align-items: center; gap: 4px; margin-bottom: 4px;">
                        <span class="material-icons-round mk-stars" style="font-size: 13px;">star</span>
                        <span style="color: #878789; font-size: 10px;"><?= number_format((float)($p['rating'] ?? 4.5), 1) ?> (<?= number_format((int)($p['reviews'] ?? 100)) ?>)</span>
                    </div>
                    <span class="mk-price"><?= htmlspecialchars($p['price'] ?? '') ?></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Recently Viewed / More Products Grid -->
    <div style="padding: 0 16px; margin-bottom: 100px;">
        <div class="mk-section-header">
            <span class="mk-section-title">Recommended For You</span>
            <a href="#" class="mk-see-all">See All ›</a>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <?php
            $recommended = array_merge($featured, $trending);
            $recSlice = array_slice($recommended, 0, 6);
            foreach ($recSlice as $p):
            ?>
            <a href="/marketplace/<?= $p['id'] ?>" class="mk-product-card" style="text-decoration: none;">
                <div style="position: relative;">
                    <img src="<?= htmlspecialchars($p['image'] ?? $p['image_url'] ?? '/uploads/marketplace/product_iphone.jpg') ?>" alt="<?= htmlspecialchars($p['title'] ?? '') ?>">
                    <?php if (!empty($p['discount'])): ?>
                    <div class="mk-discount-badge"><?= htmlspecialchars($p['discount']) ?></div>
                    <?php endif; ?>
                    <button class="mk-heart-btn" onclick="event.preventDefault();">
                        <span class="material-icons-round">favorite_border</span>
                    </button>
                    <button class="mk-cart-quick-btn" onclick="event.preventDefault();event.stopPropagation();quickAddCart(<?= $p['id'] ?>)">
                        <span class="material-icons-round">shopping_cart</span>
                    </button>
                </div>
                <div style="padding: 10px 10px 12px;">
                    <p style="color: #F5F5F7; font-size: 12px; font-weight: 600; margin: 0 0 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($p['title'] ?? '') ?></p>
                    <div style="display: flex; align-items: center; gap: 4px; margin-bottom: 4px;">
                        <span class="material-icons-round mk-stars" style="font-size: 13px;">star</span>
                        <span style="color: #878789; font-size: 10px;"><?= number_format((float)($p['rating'] ?? 4.5), 1) ?> (<?= number_format((int)($p['reviews'] ?? 100)) ?>)</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <span class="mk-price"><?= htmlspecialchars($p['price']) ?></span>
                        <?php if (!empty($p['old_price'])): ?>
                        <span class="mk-old-price"><?= htmlspecialchars($p['old_price']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Marketplace Bottom Navigation -->
<div class="mk-bottom-nav" style="position: fixed; bottom: 0; left: 0; right: 0; z-index: 50;">
    <div style="max-width: 480px; margin: 0 auto; display: flex; align-items: flex-end; justify-content: space-around; padding: 6px 0 8px;">
        <a href="/" class="mk-nav-item active">
            <span class="material-icons-round nav-icon">home</span>
            <span class="nav-label">Home</span>
        </a>
        <a href="/marketplace" class="mk-nav-item">
            <span class="material-icons-round nav-icon">category</span>
            <span class="nav-label">Categories</span>
        </a>
        <a href="/marketplace/cart" class="mk-nav-item">
            <span class="material-icons-round nav-icon" style="position:relative;">
                shopping_cart
                <span class="mk-qty-badge" id="nav-cart-badge" style="font-size:9px;display:none;">0</span>
            </span>
            <span class="nav-label">Cart</span>
        </a>
        <a href="/marketplace/wishlist" class="mk-nav-item">
            <span class="material-icons-round nav-icon">favorite_border</span>
            <span class="nav-label">Wishlist</span>
        </a>
        <a href="/profile" class="mk-nav-item">
            <span class="material-icons-round nav-icon">person_outline</span>
            <span class="nav-label">Profile</span>
        </a>
    </div>
</div>

<!-- Hide the default layout bottom nav -->
<style>
    body > nav.glass-bottom { display: none !important; }
</style>
<script>
// Heart button — toggle wishlist via event delegation
document.addEventListener('click', function(e) {
    var heartBtn = e.target.closest('.mk-heart-btn');
    if (!heartBtn) return;
    e.preventDefault(); e.stopPropagation();
    var card = heartBtn.closest('a[href]');
    if (!card) return;
    var href = card.getAttribute('href');
    var id = href ? href.split('/').pop() : null;
    if (!id || isNaN(id)) return;

    fetch('/marketplace/' + id + '/wishlist', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(d => {
        var icon = heartBtn.querySelector('span');
        if (d.wishlisted) {
            icon.textContent = 'favorite';
            icon.style.color = '#CC1821';
        } else {
            icon.textContent = 'favorite_border';
            icon.style.color = '';
        }
    });
});

// Quick add to cart from product cards
function quickAddCart(listingId) {
    fetch('/marketplace/' + listingId + '/cart', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r) {
        if (!r.ok) throw new Error('Server error ' + r.status);
        return r.json();
    })
    .then(function(d) {
        if (d.error) { toast(d.error, true); return; }
        if (d.cart_count !== undefined) updateCartBadges(d.cart_count);
        toast('Added to cart ' + String.fromCodePoint(0x1F6D2), false);
    })
    .catch(function(err) {
        toast('Could not add. Try again.', true);
        console.error(err);
    });
}

function updateCartBadges(count) {
    var badges = document.querySelectorAll('#nav-cart-badge, #header-cart-badge');
    badges.forEach(function(b) {
        if (b) { b.textContent = count; b.style.display = count > 0 ? 'flex' : 'none'; }
    });
}

function toast(msg, isError) {
    var t = document.createElement('div');
    t.style.cssText = 'position:fixed;bottom:100px;left:50%;transform:translateX(-50%);color:white;padding:10px 24px;border-radius:24px;font-weight:600;z-index:999;font-size:14px;animation:fadeInUp 0.3s ease';
    t.style.background = isError ? '#EF4444' : '#22C55E';
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(function(){ t.remove(); }, 2500);
}

// Load real cart count on page load
document.addEventListener('DOMContentLoaded', function() {
    fetch('/marketplace/cart-count', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(d => { if (d.count > 0) updateCartBadges(d.count); });
});
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
