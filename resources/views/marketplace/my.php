<?php $activeTab = 'menu'; $title = 'My Listings - DTTube'; ?>
<?php $listings = $data['listings'] ?? []; ?>
<?php ob_start(); ?>
<style>
    :root { --bg-deep: #0B1120; --bg-card: #151D2E; --bg-surface: #1E293B; --purple: #8B5CF6; --green: #22C55E; --red: #EF4444; }
    body { background: var(--bg-deep); font-family: 'Inter', sans-serif; color: white; }
    .ml-page { max-width: 480px; margin: 0 auto; padding: 0 16px 100px; }
    .ml-header { display: flex; align-items: center; justify-content: space-between; padding: 48px 0 20px; }
    .ml-header-left { display: flex; align-items: center; gap: 12px; }
    .ml-back { width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,0.05); border: none; color: #94A3B8; font-size: 22px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .ml-title { font-size: 20px; font-weight: 700; }
    .ml-add-btn { width: 40px; height: 40px; border-radius: 12px; background: var(--purple); border: none; color: white; font-size: 22px; cursor: pointer; display: flex; align-items: center; justify-content: center; text-decoration: none; }
    .ml-card { display: flex; align-items: center; gap: 14px; padding: 14px; background: var(--bg-card); border-radius: 16px; margin-bottom: 10px; border: 1px solid rgba(255,255,255,0.06); transition: all 0.2s; text-decoration: none; color: inherit; flex-wrap: nowrap; }
    .ml-card:hover { border-color: rgba(139,92,246,0.2); transform: translateY(-1px); }
    .ml-card-img { width: 64px; height: 64px; border-radius: 12px; object-fit: cover; flex-shrink: 0; background: var(--bg-surface); }
    .ml-card-info { flex: 1; min-width: 0; overflow: hidden; }
    .ml-card-title { font-size: 14px; font-weight: 600; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: flex; align-items: center; gap: 6px; max-width: 100%; }
    .ml-card-price { font-size: 16px; font-weight: 700; color: var(--purple); }
    .ml-card-meta { font-size: 11px; color: #94A3B8; margin-top: 3px; display: flex; align-items: center; gap: 8px; }
    .ml-badge { display: inline-block; padding: 2px 10px; border-radius: 8px; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .ml-badge.active { background: rgba(34,197,94,0.15); color: var(--green); }
    .ml-badge.sold { background: rgba(239,68,68,0.15); color: var(--red); }
    .ml-badge.pending { background: rgba(245,158,11,0.15); color: #F59E0B; }
    .ml-actions { display: flex; flex-direction: row; gap: 6px; flex-shrink: 0; align-self: center; }
    .ml-action-btn { width: 32px; height: 32px; border-radius: 10px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: none; font-size: 16px; transition: all 0.2s; }
    .ml-edit-btn { background: rgba(139,92,246,0.15); color: var(--purple); }
    .ml-edit-btn:hover { background: rgba(139,92,246,0.25); }
    .ml-del-btn { background: rgba(239,68,68,0.1); color: var(--red); }
    .ml-del-btn:hover { background: rgba(239,68,68,0.2); }
    .ml-empty { text-align: center; padding: 80px 20px; background: var(--bg-card); border-radius: 20px; border: 1px solid rgba(255,255,255,0.06); }
    .ml-empty-icon { font-size: 64px; color: #374151; margin-bottom: 16px; }
    .ml-empty h2 { font-size: 18px; font-weight: 600; margin-bottom: 8px; }
    .ml-empty p { color: #94A3B8; font-size: 13px; margin-bottom: 20px; }
    .ml-empty a { background: var(--purple); color: white; padding: 12px 32px; border-radius: 50px; font-weight: 600; text-decoration: none; display: inline-block; }
    .toast { position: fixed; bottom: 40px; left: 50%; transform: translateX(-50%); padding: 10px 24px; border-radius: 24px; font-weight: 600; z-index: 999; font-size: 14px; }
</style>

<div class="ml-page">
    <div class="ml-header">
        <div class="ml-header-left">
            <button class="ml-back" onclick="history.back()"><span class="material-icons-round">arrow_back</span></button>
            <span class="ml-title">My Listings</span>
        </div>
        <a href="/marketplace/create" class="ml-add-btn"><span class="material-icons-round">add</span></a>
    </div>

    <?php if (!empty($listings)): ?>
        <?php foreach ($listings as $listing): ?>
        <a href="/marketplace/<?= $listing['id'] ?>" class="ml-card">
            <img src="<?= $listing['image_url'] ?? 'https://placehold.co/128x128/1E293B/94A3B8?text=Item' ?>" alt="" class="ml-card-img">
            <div class="ml-card-info">
                <div class="ml-card-title">
                    <?= htmlspecialchars($listing['title'] ?? 'Untitled') ?>
                    <?php if (!empty($listing['sold'])): ?>
                    <span class="ml-badge sold">Sold</span>
                    <?php else: ?>
                    <span class="ml-badge <?= $listing['status'] === 'active' ? 'active' : 'pending' ?>"><?= ucfirst($listing['status'] ?? 'Active') ?></span>
                    <?php endif; ?>
                </div>
                <div class="ml-card-price"><?= htmlspecialchars($listing['currency'] ?? 'KES') ?> <?= number_format((float)($listing['price'] ?? 0)) ?></div>
                <div class="ml-card-meta">
                    <span><?= htmlspecialchars($listing['category'] ?? '') ?></span>
                    <span>·</span>
                    <span><?= formatCount($listing['views'] ?? 0) ?> views</span>
                    <span>·</span>
                    <span><?= isset($listing['created_at']) ? date('M d', strtotime($listing['created_at'])) : '' ?></span>
                </div>
            </div>
            <div class="ml-actions" onclick="event.preventDefault();event.stopPropagation();">
                <a href="/marketplace/<?= $listing['id'] ?>/edit" class="ml-action-btn ml-edit-btn"><span class="material-icons-round">edit</span></a>
                <button class="ml-action-btn ml-del-btn" onclick="deleteListing(<?= $listing['id'] ?>)"><span class="material-icons-round">delete</span></button>
            </div>
        </a>
        <?php endforeach; ?>
    <?php else: ?>
    <div class="ml-empty">
        <div class="ml-empty-icon"><span class="material-icons-round" style="font-size:64px;">inventory_2</span></div>
        <h2>No listings yet</h2>
        <p>Start selling your items on the marketplace</p>
        <a href="/marketplace/create">Sell an Item</a>
    </div>
    <?php endif; ?>
</div>

<!-- Delete Confirmation Modal -->
<div class="ml-modal-overlay" id="deleteModal">
    <div class="ml-modal">
        <div class="ml-modal-icon-wrap">
            <span class="material-icons-round">delete_forever</span>
        </div>
        <h3 class="ml-modal-title">Delete Listing?</h3>
        <p class="ml-modal-text">This action cannot be undone. The listing will be permanently removed.</p>
        <div class="ml-modal-actions">
            <button class="ml-modal-cancel" onclick="closeDeleteModal()">Cancel</button>
            <button class="ml-modal-confirm" id="confirmDeleteBtn">Delete</button>
        </div>
    </div>
</div>

<style>
    .ml-modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.75); backdrop-filter: blur(4px); z-index: 999; display: flex; align-items: center; justify-content: center; padding: 20px; opacity: 0; pointer-events: none; transition: opacity 0.25s; }
    .ml-modal-overlay.active { opacity: 1; pointer-events: all; }
    .ml-modal { background: #151D2E; border-radius: 20px; padding: 28px 24px; max-width: 360px; width: 100%; text-align: center; border: 1px solid rgba(255,255,255,0.06); animation: mlSlideUp 0.25s ease; }
    @keyframes mlSlideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .ml-modal-icon-wrap { width: 56px; height: 56px; border-radius: 50%; background: rgba(239,68,68,0.12); display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; }
    .ml-modal-icon-wrap span { font-size: 28px; color: #EF4444; }
    .ml-modal-title { font-size: 18px; font-weight: 700; margin-bottom: 6px; }
    .ml-modal-text { color: #94A3B8; font-size: 13px; line-height: 1.5; margin-bottom: 20px; }
    .ml-modal-actions { display: flex; gap: 10px; }
    .ml-modal-cancel { flex: 1; padding: 12px; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; color: #94A3B8; font-size: 14px; font-weight: 600; cursor: pointer; }
    .ml-modal-confirm { flex: 1; padding: 12px; background: #EF4444; border: none; border-radius: 12px; color: white; font-size: 14px; font-weight: 600; cursor: pointer; }
</style>

<script>
var pendingDeleteId = null;

function deleteListing(id) {
    pendingDeleteId = id;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
    pendingDeleteId = null;
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (!pendingDeleteId) return;
    var id = pendingDeleteId;
    closeDeleteModal();
    fetch('/marketplace/' + id, { method: 'DELETE', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(function() { location.reload(); })
        .catch(function() { showToast('Error deleting listing', true); });
});

function showToast(msg, isError) {
    var t = document.createElement('div');
    t.className = 'toast';
    t.style.background = isError ? '#EF4444' : '#22C55E';
    t.style.color = 'white';
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(function(){ t.remove(); }, 2000);
}
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
