<?php
$title = 'My Orders — Marketplace';
$activeTab = 'marketplace';
$hideTopNav = true;
$orders = $data['orders'] ?? [];
?>
<?php ob_start(); ?>
<style>
    :root { --bg-deep: #0B1120; --bg-card: #151D2E; --purple: #8B5CF6; }
    body { background: var(--bg-deep); font-family: 'Inter', sans-serif; color: white; }
    .mo-page { max-width: 480px; margin: 0 auto; padding: 0 16px 100px; }
    .mo-header { display: flex; align-items: center; gap: 12px; padding: 48px 0 20px; }
    .mo-back { width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,0.05); border: none; color: #94A3B8; font-size: 22px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .mo-title { font-size: 20px; font-weight: 700; flex: 1; }
    .mo-card { background: var(--bg-card); border-radius: 16px; padding: 16px; margin-bottom: 10px; border: 1px solid rgba(255,255,255,0.06); }
    .mo-order-num { font-size: 13px; font-weight: 700; color: var(--purple); margin-bottom: 8px; }
    .mo-row { display: flex; justify-content: space-between; padding: 4px 0; font-size: 12px; }
    .mo-label { color: #94A3B8; }
    .mo-value { font-weight: 600; }
    .mo-badge { display: inline-block; padding: 3px 10px; border-radius: 8px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
    .mo-badge.pending { background: rgba(245,158,11,0.15); color: #F59E0B; }
    .mo-badge.paid { background: rgba(34,197,94,0.15); color: #22C55E; }
    .mo-badge.confirmed { background: rgba(59,130,246,0.15); color: #3B82F6; }
    .mo-badge.failed { background: rgba(239,68,68,0.15); color: #EF4444; }
    .mo-badge.shipped { background: rgba(139,92,246,0.15); color: #8B5CF6; }
    .mo-badge.delivered { background: rgba(34,197,94,0.2); color: #22C55E; }
    .mo-badge.cancelled { background: rgba(239,68,68,0.1); color: #EF4444; }
    .mo-badge.processing { background: rgba(59,130,246,0.15); color: #3B82F6; }
    .mo-empty { text-align: center; padding: 80px 20px; }
    .mo-empty-icon { font-size: 64px; color: #374151; margin-bottom: 16px; }
    .mo-empty a { background: var(--purple); color: white; padding: 12px 32px; border-radius: 50px; font-weight: 600; text-decoration: none; display: inline-block; }
</style>

<div class="mo-page">
    <div class="mo-header">
        <button class="mo-back" onclick="history.back()"><span class="material-icons-round">arrow_back</span></button>
        <span class="mo-title">My Orders</span>
        <span style="font-size:12px;color:#94A3B8;"><?= count($orders) ?> orders</span>
    </div>

    <?php if (empty($orders)): ?>
    <div class="mo-empty">
        <div class="mo-empty-icon"><span class="material-icons-round" style="font-size:64px;">receipt_long</span></div>
        <h2 style="font-size:18px;font-weight:600;margin-bottom:8px;">No orders yet</h2>
        <p style="color:#94A3B8;font-size:13px;margin-bottom:24px;">Start shopping in the marketplace</p>
        <a href="/marketplace">Browse Marketplace</a>
    </div>
    <?php else: ?>
    <?php foreach ($orders as $order): ?>
    <div class="mo-card">
        <div class="mo-order-num">#<?= htmlspecialchars($order['order_number']) ?></div>
        <div class="mo-row">
            <span class="mo-label">Total</span>
            <span class="mo-value">$<?= number_format((float)$order['total'], 0) ?></span>
        </div>
        <div class="mo-row">
            <span class="mo-label">Payment</span>
            <span class="mo-value"><?= htmlspecialchars(ucfirst($order['payment_method'] ?? 'N/A')) ?></span>
        </div>
        <div class="mo-row">
            <span class="mo-label">Date</span>
            <span class="mo-value"><?= date('M j, Y', strtotime($order['created_at'])) ?></span>
        </div>
        <div style="display:flex;gap:8px;margin-top:10px;">
            <span class="mo-badge <?= $order['payment_status'] ?>"><?= ucfirst($order['payment_status']) ?></span>
            <span class="mo-badge <?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
