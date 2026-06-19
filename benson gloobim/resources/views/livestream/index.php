<?php $activeTab = 'stream'; $title = 'Live - DTTube'; $hideTopNav = true; ?>
<?php
$liveStreams = $data['liveStreams'] ?? [];
$scheduledStreams = $data['scheduledStreams'] ?? [];
$featuredStreams = $data['featuredStreams'] ?? [];

// Fallback data
if (empty($liveStreams)) {
    $liveStreams = [
        ['id' => 1, 'title' => 'Late Night Music Session', 'description' => 'Live music and chat', 'thumbnail' => 'https://placehold.co/400x225/d97706/ffffff?text=LIVE+1', 'user_id' => 2, 'creator_name' => 'Zara Ke', 'username' => 'zarake', 'creator_avatar' => 'https://placehold.co/48x48/6d28d9/ffffff?text=ZK', 'is_verified' => 1, 'viewers' => 4200, 'peak_viewers' => 8500, 'total_likes' => 234, 'total_gifts' => 56, 'gift_earnings' => 4250, 'category' => 'Music', 'started_at' => date('Y-m-d H:i:s'), 'status' => 'live'],
        ['id' => 2, 'title' => 'Q&A: Starting Your Journey', 'description' => 'Ask me anything', 'thumbnail' => 'https://placehold.co/400x225/6d28d9/ffffff?text=LIVE+2', 'user_id' => 2, 'creator_name' => 'Zara Ke', 'username' => 'zarake', 'creator_avatar' => 'https://placehold.co/48x48/6d28d9/ffffff?text=ZK', 'is_verified' => 1, 'viewers' => 2800, 'peak_viewers' => 5100, 'total_likes' => 89, 'total_gifts' => 23, 'gift_earnings' => 1820, 'category' => 'Lifestyle', 'started_at' => date('Y-m-d H:i:s'), 'status' => 'live'],
        ['id' => 3, 'title' => 'Cooking with Zara', 'description' => 'Recipe time!', 'thumbnail' => 'https://placehold.co/400x225/dc2626/ffffff?text=LIVE+3', 'user_id' => 2, 'creator_name' => 'Zara Ke', 'username' => 'zarake', 'creator_avatar' => 'https://placehold.co/48x48/6d28d9/ffffff?text=ZK', 'is_verified' => 1, 'viewers' => 1200, 'peak_viewers' => 3200, 'total_likes' => 156, 'total_gifts' => 34, 'gift_earnings' => 2100, 'category' => 'Food', 'started_at' => date('Y-m-d H:i:s'), 'status' => 'live'],
        ['id' => 4, 'title' => 'Gaming Marathon', 'description' => '24hr gaming challenge', 'thumbnail' => 'https://placehold.co/400x225/2563eb/ffffff?text=LIVE+4', 'user_id' => 2, 'creator_name' => 'Zara Ke', 'username' => 'zarake', 'creator_avatar' => 'https://placehold.co/48x48/6d28d9/ffffff?text=ZK', 'is_verified' => 1, 'viewers' => 8900, 'peak_viewers' => 12400, 'total_likes' => 563, 'total_gifts' => 89, 'gift_earnings' => 5600, 'category' => 'Gaming', 'started_at' => date('Y-m-d H:i:s'), 'status' => 'live'],
    ];
}

if (empty($scheduledStreams)) {
    $scheduledStreams = [
        ['id' => 5, 'title' => 'Weekend Vibes: Acoustic', 'thumbnail' => 'https://placehold.co/400x225/059669/ffffff?text=SCHED+1', 'user_id' => 2, 'creator_name' => 'Zara Ke', 'username' => 'zarake', 'creator_avatar' => 'https://placehold.co/48x48/6d28d9/ffffff?text=ZK', 'is_verified' => 1, 'started_at' => date('Y-m-d H:i:s', strtotime('+2 days')), ],
        ['id' => 6, 'title' => 'Creator Workshop: Editing', 'thumbnail' => 'https://placehold.co/400x225/2563eb/ffffff?text=SCHED+2', 'user_id' => 2, 'creator_name' => 'Zara Ke', 'username' => 'zarake', 'creator_avatar' => 'https://placehold.co/48x48/6d28d9/ffffff?text=ZK', 'is_verified' => 1, 'started_at' => date('Y-m-d H:i:s', strtotime('+1 day')), ],
    ];
}

$streamCategories = ['All', 'Music', 'Gaming', 'Food', 'Lifestyle', 'Tech', 'Sports', 'Art'];
$streamProducts = [
    ['id' => 1, 'title' => 'GLOOBIM Merch Tee', 'price' => '$29.99', 'image' => '/uploads/marketplace/product_sneakers.jpg', 'stream_id' => 1],
    ['id' => 2, 'title' => 'Wireless Earbuds', 'price' => '$49.99', 'image' => '/uploads/marketplace/product_headphones.jpg', 'stream_id' => 1],
    ['id' => 3, 'title' => 'LED Ring Light', 'price' => '$19.99', 'image' => '/uploads/marketplace/product_watch.jpg', 'stream_id' => 1],
    ['id' => 4, 'title' => 'Phone Stand', 'price' => '$14.99', 'image' => '/uploads/marketplace/product_iphone.jpg', 'stream_id' => 1],
];
?>
<?php ob_start(); ?>
<style>
    .stream-page { background: #000008; min-height: 100vh; padding-bottom: 80px; }
    .stream-top-bar { position: sticky; top: 0; z-index: 40; background: rgba(0,0,8,0.95); backdrop-filter: blur(16px); padding: 12px 16px 10px; }
    .stream-card { background: #0B0221; border-radius: 16px; overflow: hidden; border: 1px solid rgba(255,255,255,0.04); transition: all 0.3s ease; }
    .stream-card:hover { border-color: rgba(101,37,248,0.3); transform: translateY(-2px); }
    .stream-card img { width: 100%; aspect-ratio: 16/9; object-fit: cover; }
    .live-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 10px; font-size: 10px; font-weight: 700; color: white; background: #CC1821; }
    .live-dot { width: 6px; height: 6px; background: white; border-radius: 50%; animation: pulse-live 1.5s ease-in-out infinite; }
    @keyframes pulse-live { 0%,100% { opacity: 1; } 50% { opacity: 0.4; } }
    .viewer-chip { display: inline-flex; align-items: center; gap: 4px; padding: 4px 8px; border-radius: 8px; font-size: 10px; color: rgba(255,255,255,0.7); background: rgba(0,0,0,0.6); backdrop-filter: blur(8px); }
    .follow-btn { padding: 8px 20px; border-radius: 12px; font-size: 12px; font-weight: 700; color: white; background: linear-gradient(135deg, #834ae5, #6b21a8); box-shadow: 0 4px 15px rgba(131,74,229,0.4); border: none; cursor: pointer; transition: all 0.2s ease; }
    .follow-btn:hover { opacity: 0.9; transform: scale(1.03); }
    .follow-btn.following { background: #14141c; color: #a1a1aa; box-shadow: none; }
    .cat-pill { padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap; border: none; cursor: pointer; transition: all 0.2s ease; }
    .cat-pill.active { background: linear-gradient(135deg, #834ae5, #6b21a8); color: white; }
    .cat-pill:not(.active) { background: rgba(255,255,255,0.06); color: #878789; }
    .cat-pill:not(.active):hover { background: rgba(255,255,255,0.1); color: #C1C1C3; }
    .product-card { background: #141420; border-radius: 14px; overflow: hidden; flex-shrink: 0; width: 140px; transition: all 0.2s ease; }
    .product-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.4); }
    .product-card img { width: 100%; aspect-ratio: 1; object-fit: cover; }
    .shop-now-btn { background: linear-gradient(135deg, #834ae5, #6b21a8); color: white; border: none; border-radius: 10px; padding: 5px 12px; font-size: 10px; font-weight: 700; cursor: pointer; width: 100%; text-align: center; }
    .shop-now-btn:hover { opacity: 0.9; }
    .scheduled-card { background: #0B0221; border-radius: 16px; overflow: hidden; border: 1px solid rgba(255,255,255,0.04); flex-shrink: 0; width: 200px; }
    .scheduled-card img { width: 100%; aspect-ratio: 16/9; object-fit: cover; }
    .stream-bottom-nav { background: rgba(0,0,8,0.96); backdrop-filter: blur(20px); border-top: 1px solid rgba(255,255,255,0.06); position: fixed; bottom: 0; left: 0; right: 0; z-index: 50; }
    .sn-item { display: flex; flex-direction: column; align-items: center; gap: 2px; padding: 6px 0; text-decoration: none; min-width: 56px; }
    .sn-item span { font-size: 22px; color: #8E8F94; }
    .sn-item small { font-size: 10px; color: #8E8F94; font-weight: 500; }
    .sn-item.active span, .sn-item.active small { color: #C694F5; }
    .go-live-fab { width: 52px; height: 52px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #CC1821, #FF4444); box-shadow: 0 4px 20px rgba(204,24,33,0.5); margin-top: -22px; border: none; cursor: pointer; position: relative; }
    .scroll-row { display: flex; gap: 12px; overflow-x: auto; padding-bottom: 4px; }
    .scroll-row::-webkit-scrollbar { display: none; }
    .scroll-row { -ms-overflow-style: none; scrollbar-width: none; }
    .scroll-row > * { flex-shrink: 0; }
    .stream-count-badge { background: rgba(255,255,255,0.08); color: #878789; font-size: 11px; padding: 4px 12px; border-radius: 10px; font-weight: 500; }
</style>

<div class="stream-page">
    <!-- Top Bar -->
    <div class="stream-top-bar">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <a href="/" style="width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: center; text-decoration: none;">
                    <span class="material-icons-round" style="color: white; font-size: 20px;">arrow_back</span>
                </a>
                <h1 style="color: #F5F5F7; font-size: 18px; font-weight: 700; font-family: 'Space Grotesk', sans-serif;">Live Streams</h1>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <button style="width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: center; border: none; cursor: pointer;">
                    <span class="material-icons-round" style="color: rgba(255,255,255,0.7); font-size: 20px;">search</span>
                </button>
                <a href="/livestream/start" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 12px; background: linear-gradient(135deg, #CC1821, #FF4444); color: white; font-size: 12px; font-weight: 700; text-decoration: none; border: none; cursor: pointer; box-shadow: 0 4px 15px rgba(204,24,33,0.4);">
                    <span class="material-icons-round" style="font-size: 16px;">sensors</span>
                    Go Live
                </a>
            </div>
        </div>
        <!-- Category Pills -->
        <div class="scroll-row" style="gap: 8px;">
            <?php foreach ($streamCategories as $idx => $cat): ?>
            <button class="cat-pill <?= $idx === 0 ? 'active' : '' ?>" onclick="selectStreamCat(this)"><?= $cat ?></button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Featured Stream Hero -->
    <?php if (!empty($liveStreams)): ?>
    <div style="padding: 0 16px; margin-bottom: 20px;">
        <?php $hero = $liveStreams[0]; ?>
        <div class="stream-card" style="margin-bottom: 14px;">
            <div style="position: relative;">
                <img src="<?= $hero['thumbnail'] ?>" alt="<?= htmlspecialchars($hero['title']) ?>">
                <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 50%);"></div>
                <!-- Live badge & viewers -->
                <div style="position: absolute; top: 10px; left: 10px; display: flex; gap: 8px;">
                    <div class="live-badge"><div class="live-dot"></div>LIVE</div>
                    <div class="viewer-chip">
                        <span class="material-icons-round" style="font-size: 14px;">visibility</span>
                        <?= number_format((int)$hero['viewers']) ?>
                    </div>
                </div>
                <!-- Action icons -->
                <div style="position: absolute; top: 10px; right: 10px; display: flex; gap: 6px;">
                    <button style="width: 32px; height: 32px; border-radius: 50%; background: rgba(0,0,0,0.5); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; border: none; cursor: pointer;">
                        <span class="material-icons-round" style="color: white; font-size: 18px;">favorite_border</span>
                    </button>
                    <button style="width: 32px; height: 32px; border-radius: 50%; background: rgba(0,0,0,0.5); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; border: none; cursor: pointer;">
                        <span class="material-icons-round" style="color: white; font-size: 18px;">share</span>
                    </button>
                </div>
                <!-- Bottom info -->
                <div style="position: absolute; bottom: 10px; left: 12px; right: 12px; display: flex; align-items: flex-end; justify-content: space-between;">
                    <a href="/livestream/<?= $hero['id'] ?>" style="text-decoration: none; flex: 1; min-width: 0;">
                        <h3 style="color: white; font-size: 14px; font-weight: 700; margin: 0 0 4px; line-height: 1.2;"><?= htmlspecialchars($hero['title']) ?></h3>
                        <?php if (!empty($hero['description'])): ?>
                        <p style="color: rgba(255,255,255,0.6); font-size: 11px; margin: 0; line-height: 1.2;"><?= htmlspecialchars($hero['description']) ?></p>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>
        <!-- Streamer Info Bar -->
        <div style="display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.04);">
            <div style="position: relative;">
                <div style="width: 44px; height: 44px; border-radius: 50%; padding: 2.5px; background: linear-gradient(135deg, #834ae5, #ec4899, #f59e0b);">
                    <img src="<?= $hero['creator_avatar'] ?>" alt="" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                </div>
                <div style="position: absolute; bottom: 0; right: 0; width: 14px; height: 14px; background: linear-gradient(135deg, #834ae5, #ec4899); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #000008;">
                    <span class="material-icons-round" style="color: white; font-size: 8px;">verified</span>
                </div>
            </div>
            <div style="flex: 1; min-width: 0;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="color: #F5F5F7; font-size: 13px; font-weight: 600;"><?= htmlspecialchars($hero['creator_name'] ?? '') ?></span>
                    <span style="color: #878789; font-size: 11px;">@<?= htmlspecialchars($hero['username'] ?? '') ?></span>
                </div>
                <div style="display: flex; align-items: center; gap: 10px; margin-top: 4px;">
                    <span style="color: #878789; font-size: 10px; display: flex; align-items: center; gap: 2px;">
                        <span class="material-icons-round" style="font-size: 13px; color: #CC1821;">favorite</span>
                        <?= number_format((int)($hero['total_likes'] ?? 0)) ?>
                    </span>
                    <span style="color: #878789; font-size: 10px; display: flex; align-items: center; gap: 2px;">
                        <span class="material-icons-round" style="font-size: 13px; color: #834ae5;">card_giftcard</span>
                        <?= number_format((int)($hero['total_gifts'] ?? 0)) ?> gifts
                    </span>
                </div>
            </div>
            <button class="follow-btn" onclick="toggleFollow(this, <?= $hero['user_id'] ?? 0 ?>)">Follow</button>
        </div>
    </div>
    <?php endif; ?>

    <!-- Shop This Stream -->
    <?php if (!empty($streamProducts)): ?>
    <div style="padding: 0 16px; margin-bottom: 24px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="material-icons-round" style="color: #834ae5; font-size: 20px;">shopping_bag</span>
                <span style="color: #F5F5F7; font-size: 15px; font-weight: 700;">Shop This Stream</span>
            </div>
            <a href="/marketplace" style="color: #878789; font-size: 12px; font-weight: 500; text-decoration: none; display: flex; align-items: center; gap: 2px;">See All <span class="material-icons-round" style="font-size: 14px;">chevron_right</span></a>
        </div>
        <div class="scroll-row">
            <?php foreach ($streamProducts as $p): ?>
            <div class="product-card">
                <div style="position: relative;">
                    <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['title']) ?>">
                </div>
                <div style="padding: 10px;">
                    <p style="color: #F5F5F7; font-size: 11px; font-weight: 600; margin: 0 0 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($p['title']) ?></p>
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span style="color: #BE92FF; font-size: 13px; font-weight: 700;"><?= htmlspecialchars($p['price']) ?></span>
                    </div>
                    <button class="shop-now-btn" style="margin-top: 8px;">Add to Cart</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Live Now Grid -->
    <?php if (count($liveStreams) > 1): ?>
    <div style="padding: 0 16px; margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
            <div class="live-dot"></div>
            <span style="color: #F5F5F7; font-size: 15px; font-weight: 700;">Live Now</span>
            <span class="stream-count-badge"><?= count($liveStreams) ?> streams</span>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <?php foreach (array_slice($liveStreams, 1) as $stream): ?>
            <a href="/livestream/<?= $stream['id'] ?>" class="stream-card" style="text-decoration: none; display: block;">
                <div style="position: relative;">
                    <img src="<?= $stream['thumbnail'] ?>" alt="<?= htmlspecialchars($stream['title']) ?>">
                    <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.6) 0%, transparent 40%);"></div>
                    <div style="position: absolute; top: 8px; left: 8px; display: flex; gap: 6px;">
                        <div class="live-badge"><div class="live-dot"></div>LIVE</div>
                        <div class="viewer-chip">
                            <span class="material-icons-round" style="font-size: 12px;">visibility</span>
                            <?= number_format((int)$stream['viewers']) ?>
                        </div>
                    </div>
                </div>
                <div style="padding: 10px 10px 8px;">
                    <h4 style="color: #F5F5F7; font-size: 12px; font-weight: 600; margin: 0 0 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($stream['title']) ?></h4>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <img src="<?= $stream['creator_avatar'] ?>" alt="" style="width: 24px; height: 24px; border-radius: 50%; object-fit: cover;">
                        <span style="color: #C1C1C3; font-size: 11px; font-weight: 500; flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= htmlspecialchars($stream['creator_name'] ?? '') ?></span>
                        <?php if (!empty($stream['is_verified'])): ?>
                        <span class="material-icons-round" style="color: #834ae5; font-size: 12px; flex-shrink: 0;">verified</span>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Upcoming Streams -->
    <?php if (!empty($scheduledStreams)): ?>
    <div style="padding: 0 16px; margin-bottom: 24px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="material-icons-round" style="color: #834ae5; font-size: 20px;">schedule</span>
                <span style="color: #F5F5F7; font-size: 15px; font-weight: 700;">Upcoming</span>
            </div>
            <a href="/livestream" style="color: #878789; font-size: 12px; font-weight: 500; text-decoration: none; display: flex; align-items: center; gap: 2px;">See All <span class="material-icons-round" style="font-size: 14px;">chevron_right</span></a>
        </div>
        <div class="scroll-row">
            <?php foreach ($scheduledStreams as $stream): ?>
            <div class="scheduled-card">
                <div style="position: relative;">
                    <img src="<?= $stream['thumbnail'] ?>" alt="<?= htmlspecialchars($stream['title']) ?>">
                    <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 40%);"></div>
                    <div style="position: absolute; bottom: 8px; left: 8px; right: 8px;">
                        <span style="color: white; font-size: 12px; font-weight: 600; display: block;"><?= htmlspecialchars($stream['title']) ?></span>
                        <span style="color: rgba(255,255,255,0.6); font-size: 10px; display: flex; align-items: center; gap: 4px;">
                            <span class="material-icons-round" style="font-size: 12px;">event</span>
                            <?= $stream['started_at'] ? date('M j, g:i A', strtotime($stream['started_at'])) : 'TBD' ?>
                        </span>
                    </div>
                </div>
                <div style="padding: 10px;">
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <img src="<?= $stream['creator_avatar'] ?? 'https://placehold.co/32x32/6d28d9/ffffff?text=C' ?>" alt="" style="width: 24px; height: 24px; border-radius: 50%; object-fit: cover;">
                        <span style="color: #C1C1C3; font-size: 11px; font-weight: 500;"><?= htmlspecialchars($stream['creator_name'] ?? '') ?></span>
                    </div>
                    <button class="follow-btn" style="width: 100%; margin-top: 8px; font-size: 11px; padding: 7px 0; display: flex; align-items: center; justify-content: center; gap: 6px;" onclick="this.innerHTML = '<span class=\'material-icons-round\' style=\'font-size:16px\'>notifications_none</span> Remind Me'">
                        <span class="material-icons-round" style="font-size: 16px;">notifications_none</span>
                        Remind Me
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Empty State -->
    <?php if (empty($liveStreams) && empty($scheduledStreams)): ?>
    <div style="padding: 80px 40px; text-align: center;">
        <div style="width: 80px; height: 80px; margin: 0 auto 20px; border-radius: 50%; background: linear-gradient(135deg, rgba(131,74,229,0.15), rgba(131,74,229,0.05)); display: flex; align-items: center; justify-content: center;">
            <span class="material-icons-round" style="color: #834ae5; font-size: 40px;">sensors</span>
        </div>
        <h3 style="color: #F5F5F7; font-size: 16px; font-weight: 600; margin: 0 0 8px;">No live streams right now</h3>
        <p style="color: #878789; font-size: 13px; margin: 0 0 24px;">Be the first to go live and connect with your audience!</p>
        <a href="/livestream/start" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 28px; border-radius: 14px; background: linear-gradient(135deg, #CC1821, #FF4444); color: white; font-size: 14px; font-weight: 700; text-decoration: none; box-shadow: 0 4px 20px rgba(204,24,33,0.4);">
            <span class="material-icons-round" style="font-size: 18px;">sensors</span>
            Go Live Now
        </a>
    </div>
    <?php endif; ?>
</div>

<!-- Stream Bottom Navigation -->
<div class="stream-bottom-nav">
    <div style="max-width: 480px; margin: 0 auto; display: flex; align-items: flex-end; justify-content: space-around; padding: 6px 0 8px;">
        <a href="/" class="sn-item">
            <span class="material-icons-round">home</span>
            <small>Home</small>
        </a>
        <a href="/livestream" class="sn-item active">
            <span class="material-icons-round">sensors</span>
            <small>Stream</small>
        </a>
        <a href="/livestream" class="sn-item" style="margin-top: -10px;">
            <div class="go-live-fab" style="position: relative;">
                <span class="material-icons-round" style="color: white; font-size: 26px;">add</span>
            </div>
            <small style="margin-top: 4px;">Post</small>
        </a>
        <a href="/marketplace" class="sn-item">
            <span class="material-icons-round">shopping_bag</span>
            <small>Shop</small>
        </a>
        <a href="/profile" class="sn-item">
            <span class="material-icons-round">person_outline</span>
            <small>Profile</small>
        </a>
    </div>
</div>

<!-- Hide the default layout bottom nav -->
<style>body > nav.glass-bottom { display: none !important; }</style>

<script>
function selectStreamCat(btn) {
    document.querySelectorAll('.cat-pill').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

function toggleFollow(btn, userId) {
    if (btn.classList.contains('following')) {
        btn.textContent = 'Follow';
        btn.classList.remove('following');
    } else {
        btn.textContent = 'Following';
        btn.classList.add('following');
    }
    fetch('/follow/' + userId, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } }).then(r => r.json()).then(d => {
        if (d.error) { location.href = '/login'; return; }
        showToast(d.message || (d.following ? 'Following!' : 'Unfollowed'));
    }).catch(() => {});
}
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
