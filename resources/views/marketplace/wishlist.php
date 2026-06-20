<?php
$title = 'Wishlist — Marketplace';
$activeTab = 'marketplace';
$hideTopNav = true;
$user = \Core\Auth::user();
$items = $data['items'] ?? [];
$count = count($items);
?>
<?php ob_start(); ?>
<style>
    :root { --bg-deep: #0B1120; --bg-card: #151D2E; --purple: #8B5CF6; }
    body { background: var(--bg-deep); font-family: 'Inter', sans-serif; color: white; }
    .wl-page { max-width: 480px; margin: 0 auto; padding: 0 16px 100px; }
    .wl-header { padding: 48px 0 20px; position: sticky; top: 0; background: var(--bg-deep); z-index: 50; display: flex; align-items: center; gap: 12px; }
    .wl-back-btn { width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,0.05); border: none; color: #94A3B8; font-size: 22px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .wl-title { font-size: 20px; font-weight: 700; flex: 1; }
    .wl-item { display: flex; gap: 14px; background: var(--bg-card); border-radius: 16px; padding: 12px; margin-bottom: 10px; align-items: flex-start; }
    .wl-item-img { width: 90px; height: 90px; border-radius: 12px; object-fit: cover; flex-shrink: 0; }
    .wl-item-info { flex: 1; min-width: 0; }
    .wl-item-title { font-size: 14px; font-weight: 600; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .wl-item-price { font-size: 16px; font-weight: 700; color: var(--purple); margin-bottom: 4px; }
    .wl-item-seller { font-size: 11px; color: #94A3B8; }
    .wl-item-actions { display: flex; gap: 8px; margin-top: 8px; }
    .wl-cart-btn { background: var(--purple); color: white; border: none; border-radius: 10px; padding: 8px 16px; font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 4px; }
    .wl-remove-btn { background: rgba(239,68,68,0.15); color: #EF4444; border: none; border-radius: 10px; padding: 8px; font-size: 12px; cursor: pointer; display: flex; align-items: center; gap: 4px; }
    .wl-empty { text-align: center; padding: 80px 20px; }
    .wl-empty-icon { font-size: 64px; color: #374151; margin-bottom: 16px; }
    .wl-empty a { background: var(--purple); color: white; padding: 12px 32px; border-radius: 50px; font-weight: 600; text-decoration: none; display: inline-block; }
    .toast { position: fixed; bottom: 100px; left: 50%; transform: translateX(-50%); padding: 10px 24px; border-radius: 24px; font-weight: 600; z-index: 999; font-size: 14px; }
</style>
<div class="wl-page">
    <div class="wl-header">
        <button class="wl-back-btn" onclick="history.back()"><span class="material-icons-round">arrow_back</span></button>
        <span class="wl-title">Wishlist</span>
        <span style="background:var(--purple);color:white;font-size:12px;font-weight:600;padding:4px 12px;border-radius:20px;"><?= $count ?> saved</span>
    </div>

    <?php if (empty($items)): ?>
    <div class="wl-empty">
        <div class="wl-empty-icon"><span class="material-icons-round" style="font-size:64px;">favorite_border</span></div>
        <h2 style="font-size:18px;font-weight:600;margin-bottom:8px;">No saved items yet</h2>
        <p style="color:#94A3B8;font-size:13px;margin-bottom:24px;">Tap the heart icon on any listing to save it here</p>
        <a href="/marketplace">Browse Marketplace</a>
    </div>
    <?php else: ?>
    <?php foreach ($items as $item): ?>
    <div class="wl-item" id="wl-row-<?= $item['id'] ?>">
        <a href="/marketplace/<?= $item['id'] ?>"><img src="<?= $item['image_url'] ?? '/uploads/profiles/admin.jpg' ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="wl-item-img"></a>
        <div class="wl-item-info">
            <a href="/marketplace/<?= $item['id'] ?>" style="text-decoration:none;color:inherit;"><div class="wl-item-title"><?= htmlspecialchars($item['title']) ?></div></a>
            <div class="wl-item-price">$<?= number_format((float)$item['price']) ?></div>
            <div class="wl-item-seller">by @<?= htmlspecialchars($item['username'] ?? $item['seller_name'] ?? '?') ?></div>
            <div class="wl-item-actions">
                <button class="wl-cart-btn" onclick="addToCart(<?= $item['id'] ?>)"><span class="material-icons-round" style="font-size:16px;">shopping_cart</span>Add to Cart</button>
                <button class="wl-remove-btn" onclick="removeWishlist(<?= $item['id'] ?>, <?= $item['wishlist_id'] ?>)"><span class="material-icons-round" style="font-size:16px;">delete_outline</span></button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
<script>
function addToCart(listingId) {
    fetch('/marketplace/' + listingId + '/cart', { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'} })
    .then(r => r.json()).then(d => { toast(d.message+' 🛒', false); });
}
function removeWishlist(listingId, wishlistId) {
    fetch('/marketplace/' + listingId + '/wishlist', { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'} })
    .then(r => r.json()).then(d => {
        document.getElementById('wl-row-' + listingId).style.opacity = '0.4';
        setTimeout(() => document.getElementById('wl-row-' + listingId).remove(), 300);
        toast('Removed from wishlist', false);
    });
}
function toast(msg, isError) {
    var t = document.createElement('div');
    t.className = 'toast';
    t.style.background = isError ? '#EF4444' : '#22C55E';
    t.style.color = 'white';
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 2000);
}
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
