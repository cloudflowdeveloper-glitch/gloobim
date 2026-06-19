<?php $title = ($data['listing']['title'] ?? 'Listing') . ' - DTTube'; ?>
<?php
$listing = $data['listing'] ?? [];
$user = \Core\Auth::user();
$isOwner = $user && $listing && (int)$user['id'] === (int)($listing['user_id'] ?? 0);
$isWishlisted = false;
if ($user && $listing) {
    try {
        $wl = \Core\Database::queryOne("SELECT id FROM marketplace_wishlist WHERE user_id = ? AND listing_id = ?", [$user['id'], $listing['id']]);
        $isWishlisted = !empty($wl);
    } catch (\Exception $e) {}
}
$price = (float)($listing['price'] ?? 0);
$currency = $listing['currency'] ?? 'KES';
$category = $listing['category'] ?? 'Other';
$condition = $listing['condition'] ?? 'good';
$conditionLabels = ['new' => 'Brand New', 'like_new' => 'Like New', 'good' => 'Good', 'fair' => 'Fair', 'used' => 'Used'];
?>
<?php ob_start(); ?>
<style>
    .mk-detail-page { background: #000105; min-height: 100vh; padding-bottom: 100px; }
    .mk-detail-img { width: 100%; aspect-ratio: 1; object-fit: cover; background: #0B0221; }
    .mk-detail-card { background: #0B0221; border-radius: 20px; border: 1px solid rgba(255,255,255,0.04); padding: 20px; }
    .mk-price-large { color: #F5F5F7; font-size: 28px; font-weight: 800; font-family: 'Space Grotesk', sans-serif; }
    .mk-price-label { color: #878789; font-size: 13px; }
    .mk-detail-section { background: #0B0221; border-radius: 16px; border: 1px solid rgba(255,255,255,0.04); padding: 16px; margin-bottom: 12px; }
    .mk-detail-row { display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.04); }
    .mk-detail-row:last-child { border-bottom: none; padding-bottom: 0; }
    .mk-detail-row:first-child { padding-top: 0; }
    .mk-detail-label { color: #878789; font-size: 13px; }
    .mk-detail-value { color: #F5F5F7; font-size: 13px; font-weight: 600; }
    .mk-buy-bar { position: fixed; bottom: 0; left: 0; right: 0; z-index: 50; background: rgba(0,1,5,0.96); backdrop-filter: blur(20px); border-top: 1px solid rgba(255,255,255,0.06); padding: 12px 16px 20px; }
    .mk-buy-btn { background: linear-gradient(135deg, #6525F8, #9333ea); color: white; border: none; border-radius: 14px; padding: 14px; font-size: 15px; font-weight: 700; cursor: pointer; width: 100%; text-align: center; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s ease; }
    .mk-buy-btn:hover { opacity: 0.9; }
    .mk-buy-btn-outline { background: transparent; color: #F5F5F7; border: 1px solid rgba(255,255,255,0.15); border-radius: 14px; padding: 14px; font-size: 15px; font-weight: 600; cursor: pointer; text-align: center; transition: all 0.2s ease; }
    .mk-buy-btn-outline:hover { background: rgba(255,255,255,0.06); }
    .mk-seller-card { background: #0B0221; border-radius: 16px; border: 1px solid rgba(255,255,255,0.04); padding: 14px; display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
    .mk-verified-badge { background: linear-gradient(135deg, #6525F8, #BE92FF); width: 18px; height: 18px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; }
    .mk-back-btn { width: 38px; height: 38px; border-radius: 50%; background: rgba(0,0,0,0.5); backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; position: absolute; top: 12px; left: 12px; z-index: 5; cursor: pointer; border: none; }
    .mk-share-btn { width: 38px; height: 38px; border-radius: 50%; background: rgba(0,0,0,0.5); backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; position: absolute; top: 12px; right: 12px; z-index: 5; cursor: pointer; border: none; }
    .mk-wishlist-btn { width: 38px; height: 38px; border-radius: 50%; background: rgba(0,0,0,0.5); backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; position: absolute; top: 12px; right: 58px; z-index: 5; cursor: pointer; border: none; }
    .mk-fav-active span { color: #CC1821; }
</style>

<div class="mk-detail-page">
    <?php if ($listing): ?>
    <!-- Product Image -->
    <div style="position: relative;">
        <img src="<?= $listing['image_url'] ?? 'https://placehold.co/600x600/0B0221/ffffff?text=No+Image' ?>" alt="<?= htmlspecialchars($listing['title'] ?? '') ?>" class="mk-detail-img">
        <?php if (!empty($listing['sold'])): ?>
        <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.65); display: flex; align-items: center; justify-content: center;">
            <span style="background: rgba(204,24,33,0.85); color: white; font-size: 16px; font-weight: 800; padding: 8px 28px; border-radius: 30px; text-transform: uppercase; letter-spacing: 2px;">Sold</span>
        </div>
        <?php endif; ?>
        <button class="mk-back-btn" onclick="history.back();">
            <span class="material-icons-round" style="color: white; font-size: 22px;">chevron_left</span>
        </button>
        <button class="mk-wishlist-btn<?= $isWishlisted ? ' mk-fav-active' : '' ?>" id="wishlistBtn" onclick="toggleWishlist(this);">
            <span class="material-icons-round" style="color: <?= $isWishlisted ? '#CC1821' : 'white' ?>; font-size: 22px;"><?= $isWishlisted ? 'favorite' : 'favorite_border' ?></span>
        </button>
        <button class="mk-share-btn" onclick="shareListing();">
            <span class="material-icons-round" style="color: white; font-size: 22px;">share</span>
        </button>
        <!-- Image indicators -->
        <div style="position: absolute; bottom: 12px; left: 50%; transform: translateX(-50%); display: flex; gap: 6px;">
            <div style="width: 24px; height: 4px; border-radius: 2px; background: #6525F8;"></div>
            <div style="width: 8px; height: 4px; border-radius: 2px; background: rgba(255,255,255,0.3);"></div>
            <div style="width: 8px; height: 4px; border-radius: 2px; background: rgba(255,255,255,0.3);"></div>
        </div>
    </div>

    <!-- Product Info -->
    <div style="padding: 16px;">
        <div class="mk-detail-card" style="margin-bottom: 12px;">
            <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 8px;">
                <div style="flex: 1;">
                    <h1 style="color: #F5F5F7; font-size: 18px; font-weight: 700; margin: 0 0 4px;"><?= htmlspecialchars($listing['title'] ?? '') ?></h1>
                    <p style="color: #878789; font-size: 12px; margin: 0;"><?= htmlspecialchars($category) ?> · <?= formatCount($listing['views'] ?? 0) ?> views · <?= isset($listing['created_at']) ? timeAgo($listing['created_at']) : 'Recently' ?></p>
                </div>
            </div>
            <div style="display: flex; align-items: baseline; gap: 8px;">
                <span class="mk-price-large"><?= htmlspecialchars($currency) ?> <?= number_format($price) ?></span>
            </div>
            <!-- Condition & Location badges -->
            <div style="display: flex; gap: 8px; margin-top: 12px;">
                <span style="background: rgba(101,37,248,0.15); color: #BE92FF; font-size: 11px; font-weight: 600; padding: 4px 12px; border-radius: 10px;"><?= $conditionLabels[$condition] ?? $condition ?></span>
                <?php if (!empty($listing['location'])): ?>
                <span style="background: rgba(255,255,255,0.06); color: #C1C1C3; font-size: 11px; font-weight: 500; padding: 4px 12px; border-radius: 10px; display: flex; align-items: center; gap: 4px;">
                    <span class="material-icons-round" style="font-size: 14px;">location_on</span>
                    <?= htmlspecialchars($listing['location']) ?>
                </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Seller Card -->
        <div class="mk-seller-card">
            <img src="<?= $listing['seller_avatar'] ?? 'https://placehold.co/48x48/6525F8/ffffff?text=S' ?>" alt="" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(101,37,248,0.3);">
            <div style="flex: 1;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="color: #F5F5F7; font-size: 14px; font-weight: 600;"><?= htmlspecialchars($listing['seller_name'] ?? $listing['username'] ?? '') ?></span>
                    <?php if (!empty($listing['is_verified'])): ?>
                    <div class="mk-verified-badge">
                        <span class="material-icons-round" style="color: white; font-size: 12px;">check</span>
                    </div>
                    <?php endif; ?>
                </div>
                <span style="color: #878789; font-size: 11px;">@<?= htmlspecialchars($listing['username'] ?? '') ?></span>
                <span style="color: #878789; font-size: 11px; margin: 0 6px;">·</span>
                <span style="color: #878789; font-size: 11px;"><?= number_format((int)($listing['seller_followers'] ?? 0)) ?> followers</span>
            </div>
            <?php if (!$isOwner && $user): ?>
            <a href="/messages" style="background: rgba(101,37,248,0.15); color: #BE92FF; font-size: 12px; font-weight: 600; padding: 8px 16px; border-radius: 12px; text-decoration: none; display: flex; align-items: center; gap: 4px;">
                <span class="material-icons-round" style="font-size: 16px;">chat_bubble</span>
                Chat
            </a>
            <?php endif; ?>
        </div>

        <!-- Description -->
        <?php if (!empty($listing['description'])): ?>
        <div class="mk-detail-section">
            <h3 style="color: #F5F5F7; font-size: 14px; font-weight: 700; margin: 0 0 10px;">Description</h3>
            <p style="color: #C1C1C3; font-size: 13px; line-height: 1.7; margin: 0; white-space: pre-wrap;"><?= htmlspecialchars($listing['description']) ?></p>
        </div>
        <?php endif; ?>

        <!-- Details -->
        <div class="mk-detail-section">
            <h3 style="color: #F5F5F7; font-size: 14px; font-weight: 700; margin: 0 0 4px;">Product Details</h3>
            <div class="mk-detail-row">
                <span class="mk-detail-label">Condition</span>
                <span class="mk-detail-value"><?= $conditionLabels[$condition] ?? $condition ?></span>
            </div>
            <div class="mk-detail-row">
                <span class="mk-detail-label">Category</span>
                <span class="mk-detail-value"><?= htmlspecialchars($category) ?></span>
            </div>
            <div class="mk-detail-row">
                <span class="mk-detail-label">Location</span>
                <span class="mk-detail-value"><?= htmlspecialchars($listing['location'] ?? 'N/A') ?></span>
            </div>
            <div class="mk-detail-row">
                <span class="mk-detail-label">Listed</span>
                <span class="mk-detail-value"><?= isset($listing['created_at']) ? timeAgo($listing['created_at']) : 'Recently' ?></span>
            </div>
            <?php if (!empty($listing['phone'])): ?>
            <div class="mk-detail-row">
                <span class="mk-detail-label">Phone</span>
                <span class="mk-detail-value"><?= htmlspecialchars($listing['phone']) ?></span>
            </div>
            <?php endif; ?>
        </div>

        <!-- Safety Tips -->
        <div style="background: rgba(101,37,248,0.08); border: 1px solid rgba(101,37,248,0.15); border-radius: 14px; padding: 14px; margin-bottom: 12px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                <span class="material-icons-round" style="color: #BE92FF; font-size: 20px;">shield</span>
                <span style="color: #BE92FF; font-size: 13px; font-weight: 700;">Safety Tips</span>
            </div>
            <ul style="color: #C1C1C3; font-size: 11px; line-height: 1.8; margin: 0; padding-left: 18px;">
                <li>Meet in a public place for transactions</li>
                <li>Inspect the item before making payment</li>
                <li>Never send money in advance to unknown sellers</li>
            </ul>
        </div>
    </div>

    <!-- Buy Bar -->
    <div class="mk-buy-bar">
        <div style="max-width: 480px; margin: 0 auto; display: flex; gap: 10px;">
            <?php if ($isOwner): ?>
            <button onclick="markSold(<?= $listing['id'] ?>)" style="flex: 0 0 auto; background: rgba(34,197,94,0.15); color: #22C55E; border: 1px solid rgba(34,197,94,0.2); border-radius: 14px; padding: 14px 18px; font-size: 15px; font-weight: 600; cursor: pointer;">
                <span class="material-icons-round" style="font-size: 20px;">check_circle</span>
            </button>
            <a href="/marketplace/<?= $listing['id'] ?>/edit" class="mk-buy-btn-outline" style="flex: 0 0 auto; padding: 14px 18px; display: flex; align-items: center; text-decoration: none;">
                <span class="material-icons-round" style="font-size: 20px;">edit</span>
            </a>
            <button onclick="deleteListing(<?= $listing['id'] ?>)" style="flex: 0 0 auto; background: rgba(204,24,33,0.15); color: #CC1821; border: 1px solid rgba(204,24,33,0.2); border-radius: 14px; padding: 14px 18px; font-size: 15px; cursor: pointer;">
                <span class="material-icons-round" style="font-size: 20px;">delete</span>
            </button>
            <div style="flex: 1;"></div>
            <?php elseif ($user): ?>
            <button class="mk-buy-btn-outline" style="flex: 0 0 auto; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 14px;" onclick="toggleWishlist(document.getElementById('wishlistBtn'));">
                <span class="material-icons-round" style="font-size: 20px;">favorite_border</span>
            </button>
            <button class="mk-buy-btn" style="flex: 0 0 auto; display: flex; align-items: center; justify-content: center; gap: 8px; padding: 14px 16px;" onclick="addToCart(<?= $listing['id'] ?>)">
                <span class="material-icons-round" style="font-size: 20px;">shopping_cart</span>
                Add to Cart
            </button>
            <?php if (!empty($listing['phone'])): ?>
            <a href="tel:<?= htmlspecialchars($listing['phone']) ?>" class="mk-buy-btn" style="flex: 1; text-decoration: none;">
                <span class="material-icons-round" style="font-size: 20px;">phone</span>
                Call
            </a>
            <?php else: ?>
            <a href="/messages" class="mk-buy-btn" style="flex: 1; text-decoration: none;">
                <span class="material-icons-round" style="font-size: 20px;">chat_bubble</span>
                Message
            </a>
            <?php endif; ?>
            <?php else: ?>
            <a href="/login" class="mk-buy-btn" style="text-decoration: none;">
                <span class="material-icons-round" style="font-size: 20px;">login</span>
                Sign in to Buy
            </a>
            <?php endif; ?>
        </div>
    </div>

    <?php else: ?>
    <div style="padding: 60px 20px; text-align: center;">
        <span class="material-icons-round" style="color: #5C5C5C; font-size: 64px;">storefront</span>
        <h3 style="color: #F5F5F7; font-size: 16px; font-weight: 600; margin: 16px 0 8px;">Listing not found</h3>
        <p style="color: #878789; font-size: 13px; margin: 0 0 24px;">This item may have been removed or doesn't exist</p>
        <a href="/marketplace" style="background: linear-gradient(135deg, #6525F8, #9333ea); color: white; padding: 12px 32px; border-radius: 14px; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-block;">Browse Marketplace</a>
    </div>
    <?php endif; ?>
</div>

<!-- Confirmation Modal -->
<div class="confirm-overlay" id="confirmModal">
    <div class="confirm-modal">
        <div style="width:52px;height:52px;border-radius:50%;background:rgba(245,158,11,0.15);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
            <span class="material-icons-round" style="font-size:26px;color:#F59E0B;">warning</span>
        </div>
        <h3 style="font-size:17px;font-weight:700;margin-bottom:4px;" id="confirmModalTitle">Confirm</h3>
        <p style="color:#94A3B8;font-size:13px;line-height:1.5;margin-bottom:18px;" id="confirmModalText">Are you sure?</p>
        <div style="display:flex;gap:10px;">
            <button onclick="closeConfirmModal()" style="flex:1;padding:11px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.08);border-radius:12px;color:#94A3B8;font-size:14px;font-weight:600;cursor:pointer;">Cancel</button>
            <button id="confirmModalBtn" style="flex:1;padding:11px;background:linear-gradient(135deg,#8B5CF6,#A78BFA);border:none;border-radius:12px;color:white;font-size:14px;font-weight:600;cursor:pointer;">Confirm</button>
        </div>
    </div>
</div>

<style>
    .confirm-overlay { position:fixed;inset:0;background:rgba(0,0,0,0.75);backdrop-filter:blur(4px);z-index:9999;display:flex;align-items:center;justify-content:center;padding:20px;opacity:0;pointer-events:none;transition:opacity 0.25s; }
    .confirm-overlay.active { opacity:1;pointer-events:all; }
    .confirm-modal { background:#151D2E;border-radius:20px;padding:26px 22px;max-width:340px;width:100%;text-align:center;border:1px solid rgba(255,255,255,0.06);animation:confirmSlide 0.25s ease; }
    @keyframes confirmSlide { from{transform:translateY(20px);opacity:0;} to{transform:translateY(0);opacity:1;} }
</style>

<!-- Hide default layout bottom nav -->
<style>
    body > nav.glass-bottom { display: none !important; }
</style>

<script>
function toggleWishlist(btn) {
    fetch('/marketplace/<?= $listing['id'] ?>/wishlist', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(d => {
        const icon = btn.querySelector('span');
        if (d.wishlisted) {
            btn.classList.add('mk-fav-active');
            icon.textContent = 'favorite';
            icon.style.color = '#CC1821';
        } else {
            btn.classList.remove('mk-fav-active');
            icon.textContent = 'favorite_border';
            icon.style.color = 'white';
        }
    });
}

function addToCart(listingId) {
    fetch('/marketplace/' + listingId + '/cart', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => {
        if (!r.ok) throw new Error('Server error ' + r.status);
        return r.json();
    })
    .then(d => {
        if (d.error) { showToast(d.error, true); return; }
        showToast(d.message || 'Added to cart 🛒', false);
    })
    .catch(err => {
        showToast('Could not add to cart. Try again.', true);
        console.error(err);
    });
}

function showToast(msg, isError) {
    var toast = document.createElement('div');
    toast.style.cssText = 'position:fixed;bottom:100px;left:50%;transform:translateX(-50%);color:white;padding:10px 24px;border-radius:24px;font-weight:600;z-index:999;font-size:14px;animation:fadeInUp 0.3s ease';
    toast.style.background = isError ? '#EF4444' : '#22C55E';
    toast.textContent = msg;
    document.body.appendChild(toast);
    setTimeout(function() { toast.remove(); }, 2500);
}

function shareListing() {
    if (navigator.share) {
        navigator.share({ title: '<?= htmlspecialchars(addslashes($listing['title'] ?? '')) ?>', url: window.location.href });
    } else {
        navigator.clipboard.writeText(window.location.href).then(() => {
            const btn = document.querySelector('.mk-share-btn');
            const icon = btn.querySelector('span');
            icon.textContent = 'check';
            setTimeout(() => { icon.textContent = 'share'; }, 2000);
        });
    }
}

function markSold(id) {
    showConfirmModal('Mark as Sold?', 'This will mark the item as sold and remove it from active listings.', function() {
        fetch('/marketplace/' + id + '/sold', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json()).then(function(d) { location.reload(); })
            .catch(function() { showToast('Error', true); });
    });
}

function deleteListing(id) {
    showConfirmModal('Delete Listing?', 'This action cannot be undone. The listing will be permanently removed.', function() {
        fetch('/marketplace/' + id, { method: 'DELETE', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json()).then(function() { window.location.href = '/marketplace/my'; })
            .catch(function() { showToast('Error', true); });
    });
}

// Shared confirmation modal
var _confirmCallback = null;
function showConfirmModal(title, text, onConfirm) {
    _confirmCallback = onConfirm;
    document.getElementById('confirmModalTitle').textContent = title;
    document.getElementById('confirmModalText').textContent = text;
    document.getElementById('confirmModal').classList.add('active');
}
function closeConfirmModal() {
    document.getElementById('confirmModal').classList.remove('active');
    _confirmCallback = null;
}
document.addEventListener('DOMContentLoaded', function() {
    var confirmBtn = document.getElementById('confirmModalBtn');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            if (_confirmCallback) { _confirmCallback(); closeConfirmModal(); }
        });
    }
});
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
