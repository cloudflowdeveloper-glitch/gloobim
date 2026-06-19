<?php
$title = 'Cart — Marketplace';
$activeTab = 'marketplace';
$hideTopNav = true;
$user = \Core\Auth::user();
$items = $data['items'] ?? [];
$total = $data['total'] ?? 0;
?>
<?php ob_start(); ?>
<style>
    :root { --bg-deep: #0B1120; --bg-card: #151D2E; --bg-surface: #1E293B; --purple: #8B5CF6; --text-primary: #FFFFFF; --text-secondary: #94A3B8; }
    body { background: var(--bg-deep); font-family: 'Inter', sans-serif; color: var(--text-primary); }
    .cart-page { max-width: 480px; margin: 0 auto; padding: 0 16px 100px; }
    .cart-header { display: flex; align-items: center; justify-content: space-between; padding: 48px 0 20px; position: sticky; top: 0; background: var(--bg-deep); z-index: 50; }
    .cart-back-btn { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 12px; background: rgba(255,255,255,0.05); color: var(--text-secondary); font-size: 22px; cursor: pointer; border: none; }
    .cart-title { font-size: 20px; font-weight: 700; }
    .cart-count-badge { background: var(--purple); color: white; font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 20px; }
    .cart-item { background: var(--bg-card); border-radius: 16px; padding: 14px; display: flex; gap: 14px; margin-bottom: 10px; align-items: center; }
    .cart-item-img { width: 80px; height: 80px; border-radius: 12px; object-fit: cover; background: var(--bg-surface); flex-shrink: 0; }
    .cart-item-info { flex: 1; min-width: 0; }
    .cart-item-title { font-size: 14px; font-weight: 600; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .cart-item-seller { font-size: 11px; color: var(--text-secondary); margin-bottom: 6px; }
    .cart-item-price { font-size: 16px; font-weight: 700; color: var(--purple); }
    .cart-item-actions { display: flex; align-items: center; gap: 10px; margin-top: 6px; }
    .cart-qty-btn { width: 32px; height: 32px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.05); color: white; font-size: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .cart-qty-num { font-size: 14px; font-weight: 600; min-width: 24px; text-align: center; }
    .cart-remove-btn { background: none; border: none; color: #EF4444; font-size: 20px; cursor: pointer; padding: 4px; }
    .cart-summary { background: var(--bg-card); border-radius: 16px; padding: 20px; margin-top: 16px; border: 1px solid rgba(139,92,246,0.15); }
    .cart-summary-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 14px; color: var(--text-secondary); }
    .cart-summary-row.total { border-top: 1px solid rgba(255,255,255,0.06); padding-top: 14px; margin-top: 4px; color: white; font-size: 18px; font-weight: 700; }
    .cart-checkout-btn { background: var(--purple); color: white; border: none; border-radius: 14px; padding: 16px; font-size: 16px; font-weight: 700; width: 100%; cursor: pointer; margin-top: 16px; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .cart-empty { text-align: center; padding: 80px 20px; }
    .cart-empty-icon { font-size: 64px; color: #374151; margin-bottom: 16px; }
    .cart-empty h2 { font-size: 18px; font-weight: 600; margin-bottom: 8px; }
    .cart-empty p { color: var(--text-secondary); font-size: 13px; margin-bottom: 24px; }
    .cart-empty a { background: var(--purple); color: white; padding: 12px 32px; border-radius: 50px; font-weight: 600; text-decoration: none; display: inline-block; }
    .toast { position: fixed; bottom: 100px; left: 50%; transform: translateX(-50%); background: #22c55e; color: white; padding: 10px 24px; border-radius: 24px; font-weight: 600; z-index: 999; font-size: 14px; }
    .fade-in-up { animation: fadeInUp 0.3s ease; }
    @keyframes fadeInUp { from { opacity: 0; transform: translate(-50%, 10px); } to { opacity: 1; transform: translate(-50%, 0); } }
</style>

<div class="cart-page">
    <div class="cart-header">
        <button class="cart-back-btn" onclick="history.back()">
            <span class="material-icons-round">arrow_back</span>
        </button>
        <span class="cart-title">Shopping Cart</span>
        <span class="cart-count-badge"><?= count($items) ?> items</span>
    </div>

    <?php if (empty($items)): ?>
    <div class="cart-empty">
        <div class="cart-empty-icon">
            <span class="material-icons-round" style="font-size:64px;">shopping_cart</span>
        </div>
        <h2>Your cart is empty</h2>
        <p>Browse the marketplace and add items you love</p>
        <a href="/marketplace">Start Shopping</a>
    </div>
    <?php else: ?>
    <?php foreach ($items as $item): ?>
    <div class="cart-item" id="cart-row-<?= $item['cart_id'] ?>">
        <img src="<?= $item['image_url'] ?? 'https://placehold.co/160x160/1E293B/94A3B8?text=No+Image' ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="cart-item-img">
        <div class="cart-item-info">
            <div class="cart-item-title"><?= htmlspecialchars($item['title']) ?></div>
            <div class="cart-item-seller">by @<?= htmlspecialchars($item['username']) ?></div>
            <div class="cart-item-price">$<?= number_format((float)$item['price']) ?></div>
            <div class="cart-item-actions">
                <button class="cart-qty-btn" onclick="updateQty(<?= $item['cart_id'] ?>, <?= $item['quantity'] - 1 ?>)">−</button>
                <span class="cart-qty-num" id="qty-<?= $item['cart_id'] ?>"><?= $item['quantity'] ?></span>
                <button class="cart-qty-btn" onclick="updateQty(<?= $item['cart_id'] ?>, <?= $item['quantity'] + 1 ?>)">+</button>
                <button class="cart-remove-btn" onclick="removeItem(<?= $item['cart_id'] ?>)" style="margin-left: auto;">
                    <span class="material-icons-round">delete_outline</span>
                </button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <div class="cart-summary">
        <div class="cart-summary-row"><span>Subtotal</span><span>$<?= number_format($total, 0) ?></span></div>
        <div class="cart-summary-row"><span>Shipping</span><span>Calculated at checkout</span></div>
        <div class="cart-summary-row total"><span>Total</span><span>$<?= number_format($total, 0) ?></span></div>
    </div>

    <a href="/marketplace/checkout" class="cart-checkout-btn" style="text-decoration:none;display:flex;align-items:center;justify-content:center;gap:8px;">
        <span class="material-icons-round">lock</span>
        Proceed to Checkout
    </a>
    <?php endif; ?>
</div>

<script>
function updateQty(cartId, qty) {
    if (qty < 1) { removeItem(cartId); return; }
    fetch('/marketplace/cart/' + cartId, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ quantity: qty })
    }).then(r => r.json()).then(d => {
        if (d.success) location.reload();
        else showToast(d.error || 'Error updating quantity', true);
    });
}

function removeItem(cartId) {
    fetch('/marketplace/cart/' + cartId, {
        method: 'DELETE',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).then(r => r.json()).then(d => {
        if (d.success) {
            document.getElementById('cart-row-' + cartId).remove();
            showToast('Removed from cart');
            if (document.querySelectorAll('.cart-item').length === 0) location.reload();
        }
    });
}

function showToast(msg, isError) {
    var t = document.createElement('div');
    t.className = 'toast fade-in-up';
    t.style.background = isError ? '#EF4444' : '#22C55E';
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(function() { t.remove(); }, 2000);
}
</script>
</body>
</html>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
