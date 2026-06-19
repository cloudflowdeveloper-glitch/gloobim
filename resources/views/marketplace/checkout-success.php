<?php
$title = 'Order Confirmed — Marketplace';
$activeTab = 'marketplace';
$hideTopNav = true;
$order = $data['order'] ?? null;
?>
<?php ob_start(); ?>
<style>
    :root { --bg-deep: #0B1120; --bg-card: #151D2E; --purple: #8B5CF6; --green: #22C55E; }
    body { background: var(--bg-deep); font-family: 'Inter', sans-serif; color: white; }
    .sc-page { max-width: 480px; margin: 0 auto; padding: 0 16px; text-align: center; }
    .sc-check { width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #22C55E, #16A34A); display: flex; align-items: center; justify-content: center; margin: 60px auto 20px; }
    .sc-check span { font-size: 48px; color: white; }
    .sc-title { font-size: 24px; font-weight: 800; margin-bottom: 8px; }
    .sc-subtitle { color: #94A3B8; font-size: 14px; margin-bottom: 24px; line-height: 1.6; }
    .sc-card { background: var(--bg-card); border-radius: 16px; padding: 18px; margin-bottom: 12px; text-align: left; border: 1px solid rgba(255,255,255,0.06); }
    .sc-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 13px; border-bottom: 1px solid rgba(255,255,255,0.04); }
    .sc-row:last-child { border-bottom: none; }
    .sc-label { color: #94A3B8; }
    .sc-value { font-weight: 600; }
    .sc-cta { display: inline-block; background: var(--purple); color: white; padding: 14px 36px; border-radius: 14px; font-weight: 700; text-decoration: none; margin: 12px 8px; font-size: 14px; }
    .sc-cta-outline { display: inline-block; background: transparent; color: var(--purple); border: 1px solid rgba(139,92,246,0.3); padding: 14px 36px; border-radius: 14px; font-weight: 600; text-decoration: none; margin: 12px 8px; font-size: 14px; }
</style>

<div class="sc-page">
    <?php if ($order): ?>
    <div class="sc-check"><span class="material-icons-round">check</span></div>
    <h1 class="sc-title">Order Confirmed!</h1>
    <p class="sc-subtitle">Your order has been placed successfully.<br>You'll receive a confirmation shortly.</p>

    <div class="sc-card">
        <div class="sc-row"><span class="sc-label">Order Number</span><span class="sc-value" style="color:var(--purple);"><?= htmlspecialchars($order['order_number']) ?></span></div>
        <div class="sc-row"><span class="sc-label">Total</span><span class="sc-value">$<?= number_format((float)$order['total'], 0) ?></span></div>
        <div class="sc-row"><span class="sc-label">Payment Method</span><span class="sc-value"><?= htmlspecialchars($order['payment_display'] ?? $order['payment_method']) ?></span></div>
        <div class="sc-row"><span class="sc-label">Payment Status</span><span class="sc-value" style="color:<?= $order['payment_status'] === 'paid' ? '#22C55E' : '#F59E0B' ?>;"><?= ucfirst($order['payment_status']) ?></span></div>
        <div class="sc-row"><span class="sc-label">Order Status</span><span class="sc-value"><?= ucfirst($order['status']) ?></span></div>
    </div>

    <div style="margin-top: 24px;">
        <a href="/marketplace" class="sc-cta">Continue Shopping</a>
        <a href="/marketplace/cart" class="sc-cta-outline">View Cart</a>
    </div>
    <?php else: ?>
    <div style="padding:80px 20px;">
        <span class="material-icons-round" style="font-size:64px;color:#374151;">receipt_long</span>
        <h1 class="sc-title" style="margin-top:16px;">No order found</h1>
        <p class="sc-subtitle">We couldn't find that order. It may have been removed.</p>
        <a href="/marketplace" class="sc-cta">Go to Marketplace</a>
    </div>
    <?php endif; ?>
</div>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
