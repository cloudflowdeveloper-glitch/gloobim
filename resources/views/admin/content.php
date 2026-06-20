<?php $title = 'Content Moderation — Admin'; ?>
<?php $data = $data ?? []; ?>
<?php extract($data); ?>
<?php ob_start(); ?>
<style>
    .cm-page { max-width: 672px; margin: 0 auto; padding: 0 12px 96px; }
    body { background: #090c15; font-family: 'Inter', sans-serif; color: #e2e8f0; }

    /* Header */
    .cm-header { display: flex; align-items: center; gap: 12px; padding: 48px 0 16px; }
    .cm-back { width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid #1e1e2a; color: #94a3b8; font-size: 22px; cursor: pointer; display: flex; align-items: center; justify-content: center; text-decoration: none; }
    .cm-header-info { flex: 1; }
    .cm-title { font-size: 20px; font-weight: 700; color: #fff; }
    .cm-total { font-size: 12px; color: #64748b; margin-top: 2px; }

    /* Type Tabs */
    .cm-type-tabs { display: flex; gap: 4px; margin-bottom: 12px; background: #14141c; border-radius: 12px; padding: 4px; border: 1px solid #1e1e2a; }
    .cm-type-tab { flex: 1; text-align: center; padding: 8px 0; border-radius: 9px; font-size: 13px; font-weight: 600; cursor: pointer; color: #64748b; border: none; background: none; transition: all 0.2s; text-decoration: none; }
    .cm-type-tab:hover { color: #94a3b8; }
    .cm-type-tab.active { background: #834ae5; color: #fff; }

    /* Status Filter */
    .cm-status-bar { display: flex; gap: 6px; margin-bottom: 16px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .cm-status-bar::-webkit-scrollbar { display: none; }
    .cm-status-chip { padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; cursor: pointer; color: #94a3b8; background: #14141c; border: 1px solid #1e1e2a; white-space: nowrap; transition: all 0.2s; text-decoration: none; }
    .cm-status-chip:hover { border-color: #834ae5; color: #c4b5fd; }
    .cm-status-chip.active { background: rgba(131,74,229,0.15); border-color: #834ae5; color: #c4b5fd; }

    /* Content Card */
    .cm-card { background: #14141c; border: 1px solid #1e1e2a; border-radius: 16px; padding: 14px; margin-bottom: 10px; transition: border-color 0.2s; }
    .cm-card:hover { border-color: #2a2a3a; }
    .cm-card-top { display: flex; gap: 12px; margin-bottom: 10px; }

    /* Thumbnail */
    .cm-thumb { width: 80px; height: 80px; border-radius: 10px; object-fit: cover; flex-shrink: 0; background: #1e1e2a; display: flex; align-items: center; justify-content: center; overflow: hidden; }
    .cm-thumb img { width: 100%; height: 100%; object-fit: cover; border-radius: 10px; }
    .cm-thumb .cm-thumb-icon { font-size: 28px; color: #475569; }

    /* Content Body */
    .cm-body { flex: 1; min-width: 0; }
    .cm-text { font-size: 13px; color: #cbd5e1; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin-bottom: 6px; word-break: break-word; }
    .cm-card-title { font-size: 14px; font-weight: 600; color: #fff; margin-bottom: 4px; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }

    /* Status Badge */
    .cm-status { display: inline-flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 10px; margin-bottom: 6px; }
    .cm-status.published { background: rgba(34,197,94,0.12); color: #22c55e; }
    .cm-status.pending { background: rgba(245,158,11,0.12); color: #f59e0b; }
    .cm-status.deleted { background: rgba(239,68,68,0.12); color: #ef4444; }
    .cm-status .material-icons-round { font-size: 13px; }

    /* Creator */
    .cm-creator { display: flex; align-items: center; gap: 8px; }
    .cm-avatar { width: 24px; height: 24px; border-radius: 50%; object-fit: cover; background: #1e1e2a; flex-shrink: 0; }
    .cm-avatar-placeholder { width: 24px; height: 24px; border-radius: 50%; background: #834ae5; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; color: #fff; flex-shrink: 0; }
    .cm-creator-name { font-size: 12px; font-weight: 600; color: #94a3b8; }
    .cm-creator-username { font-size: 11px; color: #475569; }
    .cm-date { font-size: 11px; color: #475569; margin-left: auto; flex-shrink: 0; }

    /* Stats Row */
    .cm-stats { display: flex; gap: 14px; padding: 10px 0 6px; border-top: 1px solid #1e1e2a; margin-top: 10px; }
    .cm-stat { display: flex; align-items: center; gap: 4px; font-size: 12px; color: #64748b; }
    .cm-stat .material-icons-round { font-size: 14px; }

    /* Actions */
    .cm-actions { display: flex; gap: 6px; margin-top: 10px; flex-wrap: wrap; }
    .cm-btn { display: inline-flex; align-items: center; gap: 5px; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer; border: none; transition: all 0.15s; text-decoration: none; }
    .cm-btn .material-icons-round { font-size: 14px; }
    .cm-btn-delete { background: rgba(239,68,68,0.12); color: #ef4444; }
    .cm-btn-delete:hover { background: rgba(239,68,68,0.25); }
    .cm-btn-restore { background: rgba(34,197,94,0.12); color: #22c55e; }
    .cm-btn-restore:hover { background: rgba(34,197,94,0.25); }
    .cm-btn-feature { background: rgba(131,74,229,0.12); color: #a78bfa; }
    .cm-btn-feature:hover { background: rgba(131,74,229,0.25); }
    .cm-btn-view { background: rgba(255,255,255,0.05); color: #94a3b8; }
    .cm-btn-view:hover { background: rgba(255,255,255,0.1); }

    /* Pagination */
    .cm-pagination { display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 20px; }
    .cm-page-btn { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 600; cursor: pointer; border: 1px solid #1e1e2a; background: #14141c; color: #64748b; text-decoration: none; transition: all 0.2s; }
    .cm-page-btn:hover:not(.active):not(:disabled) { border-color: #834ae5; color: #c4b5fd; }
    .cm-page-btn.active { background: #834ae5; border-color: #834ae5; color: #fff; }
    .cm-page-btn:disabled { opacity: 0.3; cursor: not-allowed; }
    .cm-page-info { font-size: 12px; color: #475569; margin-top: 8px; text-align: center; }

    /* Empty State */
    .cm-empty { text-align: center; padding: 60px 20px; }
    .cm-empty-icon { font-size: 56px; color: #1e1e2a; margin-bottom: 16px; }
    .cm-empty-title { font-size: 16px; font-weight: 600; color: #475569; margin-bottom: 6px; }
    .cm-empty-desc { font-size: 13px; color: #334155; }

    /* Toast */
    .cm-toast { position: fixed; bottom: 40px; left: 50%; transform: translateX(-50%); padding: 10px 24px; border-radius: 24px; font-weight: 600; z-index: 9999; font-size: 14px; color: #fff; animation: cmFadeUp 0.3s ease; pointer-events: none; }
    @keyframes cmFadeUp { from { opacity: 0; transform: translate(-50%, 10px); } to { opacity: 1; transform: translate(-50%, 0); } }
</style>

<div class="cm-page">

    <!-- Header -->
    <div class="cm-header">
        <a href="/admin" class="cm-back"><span class="material-icons-round">arrow_back</span></a>
        <div class="cm-header-info">
            <div class="cm-title">Content Moderation</div>
            <div class="cm-total"><?= number_format($total) ?> total items</div>
        </div>
    </div>

    <!-- Type Tabs -->
    <div class="cm-type-tabs">
        <a href="?type=posts<?= $status ? '&status=' . $status : '' ?>" class="cm-type-tab <?= ($type === 'posts') ? 'active' : '' ?>">
            <span class="material-icons-round" style="font-size:14px;vertical-align:middle;margin-right:2px;">article</span> Posts
        </a>
        <a href="?type=reels<?= $status ? '&status=' . $status : '' ?>" class="cm-type-tab <?= ($type === 'reels') ? 'active' : '' ?>">
            <span class="material-icons-round" style="font-size:14px;vertical-align:middle;margin-right:2px;">movie</span> Reels
        </a>
        <a href="?type=videos<?= $status ? '&status=' . $status : '' ?>" class="cm-type-tab <?= ($type === 'videos') ? 'active' : '' ?>">
            <span class="material-icons-round" style="font-size:14px;vertical-align:middle;margin-right:2px;">videocam</span> Videos
        </a>
    </div>

    <!-- Status Filter -->
    <div class="cm-status-bar">
        <a href="?type=<?= $type ?>" class="cm-status-chip <?= (!$status) ? 'active' : '' ?>">All</a>
        <a href="?type=<?= $type ?>&status=published" class="cm-status-chip <?= ($status === 'published') ? 'active' : '' ?>">Published</a>
        <a href="?type=<?= $type ?>&status=pending" class="cm-status-chip <?= ($status === 'pending') ? 'active' : '' ?>">Pending</a>
        <a href="?type=<?= $type ?>&status=deleted" class="cm-status-chip <?= ($status === 'deleted') ? 'active' : '' ?>">Deleted</a>
    </div>

    <!-- Content List -->
    <?php if (empty($items)): ?>
    <div class="cm-empty">
        <div class="cm-empty-icon"><span class="material-icons-round" style="font-size:56px;">inventory_2</span></div>
        <div class="cm-empty-title">No content found</div>
        <div class="cm-empty-desc">No <?= htmlspecialchars($type) ?> match the current filter.</div>
    </div>
    <?php else: ?>
        <?php foreach ($items as $item): ?>
        <div class="cm-card" id="cm-item-<?= $item['id'] ?>">
            <div class="cm-card-top">

                <?php if ($type === 'posts'): ?>
                    <!-- Post: text + optional image -->
                    <div class="cm-body">
                        <?php if (!empty($item['image_url']) || !empty($item['thumbnail'])): ?>
                            <div style="margin-bottom:8px;border-radius:10px;overflow:hidden;max-height:160px;">
                                <img src="<?= htmlspecialchars($item['image_url'] ?: $item['thumbnail']) ?>" alt="" style="width:100%;height:100%;object-fit:cover;display:block;" loading="lazy">
                            </div>
                        <?php endif; ?>
                        <div class="cm-text"><?= htmlspecialchars($item['content'] ?? '') ?></div>
                        <div class="cm-status <?= htmlspecialchars($item['status']) ?>">
                            <span class="material-icons-round">
                                <?php if ($item['status'] === 'published'): ?>check_circle
                                <?php elseif ($item['status'] === 'pending'): ?>schedule
                                <?php else: ?>cancel
                                <?php endif; ?>
                            </span>
                            <?= ucfirst(htmlspecialchars($item['status'])) ?>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Reel / Video: thumbnail + title -->
                    <?php $thumb = $item['thumbnail'] ?? $item['image_url'] ?? ''; ?>
                    <div class="cm-thumb">
                        <?php if ($thumb): ?>
                            <img src="<?= htmlspecialchars($thumb) ?>" alt="" loading="lazy">
                        <?php else: ?>
                            <span class="material-icons-round cm-thumb-icon"><?= $type === 'reels' ? 'movie' : 'videocam' ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="cm-body">
                        <div class="cm-card-title"><?= htmlspecialchars($item['title'] ?? 'Untitled') ?></div>
                        <?php if (!empty($item['content'])): ?>
                            <div class="cm-text"><?= htmlspecialchars($item['content']) ?></div>
                        <?php endif; ?>
                        <div class="cm-status <?= htmlspecialchars($item['status']) ?>">
                            <span class="material-icons-round">
                                <?php if ($item['status'] === 'published'): ?>check_circle
                                <?php elseif ($item['status'] === 'pending'): ?>schedule
                                <?php else: ?>cancel
                                <?php endif; ?>
                            </span>
                            <?= ucfirst(htmlspecialchars($item['status'])) ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Creator Row -->
            <div class="cm-creator">
                <?php if (!empty($item['creator_avatar'])): ?>
                    <img class="cm-avatar" src="<?= htmlspecialchars($item['creator_avatar']) ?>" alt="" loading="lazy">
                <?php else: ?>
                    <div class="cm-avatar-placeholder"><?= strtoupper(mb_substr($item['creator_name'] ?? 'U', 0, 1)) ?></div>
                <?php endif; ?>
                <div>
                    <div class="cm-creator-name"><?= htmlspecialchars($item['creator_name'] ?? 'Unknown') ?></div>
                    <div class="cm-creator-username">@<?= htmlspecialchars($item['username'] ?? '-') ?></div>
                </div>
                <div class="cm-date"><?= timeAgo($item['created_at']) ?></div>
            </div>

            <!-- Stats -->
            <div class="cm-stats">
                <div class="cm-stat"><span class="material-icons-round">visibility</span> <?= formatCount($item['views'] ?? 0) ?></div>
                <div class="cm-stat"><span class="material-icons-round">favorite</span> <?= formatCount($item['likes'] ?? 0) ?></div>
                <div class="cm-stat"><span class="material-icons-round">chat_bubble_outline</span> <?= formatCount($item['comments_count'] ?? 0) ?></div>
                <div class="cm-stat"><span class="material-icons-round">share</span> <?= formatCount($item['shares'] ?? 0) ?></div>
            </div>

            <!-- Actions -->
            <div class="cm-actions" id="cm-actions-<?= $item['id'] ?>">
                <?php if ($item['status'] === 'deleted'): ?>
                    <button class="cm-btn cm-btn-restore" onclick="restoreContent('<?= $type ?>', <?= $item['id'] ?>)">
                        <span class="material-icons-round">restore</span> Restore
                    </button>
                <?php else: ?>
                    <button class="cm-btn cm-btn-delete" onclick="deleteContent('<?= $type ?>', <?= $item['id'] ?>)">
                        <span class="material-icons-round">delete_outline</span> Delete
                    </button>
                <?php endif; ?>

                <?php if ($type === 'reels' || $type === 'videos'): ?>
                    <button class="cm-btn cm-btn-feature" onclick="featureContent('<?= $type ?>', <?= $item['id'] ?>)">
                        <span class="material-icons-round">star_outline</span> Feature
                    </button>
                <?php endif; ?>

                <?php
                    $viewPath = '/';
                    if ($type === 'posts') $viewPath = '/post/' . $item['id'];
                    elseif ($type === 'reels') $viewPath = '/reel/' . $item['id'];
                    elseif ($type === 'videos') $viewPath = '/video/' . $item['id'];
                ?>
                <a href="<?= $viewPath ?>" class="cm-btn cm-btn-view" target="_blank">
                    <span class="material-icons-round">open_in_new</span> View
                </a>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Pagination -->
        <?php
            $totalPages = ceil($total / $perPage);
            $currentPage = (int)$page;
            $queryBase = '?type=' . $type . ($status ? '&status=' . $status : '');
        ?>
        <?php if ($totalPages > 1): ?>
        <div class="cm-pagination">
            <?php if ($currentPage > 1): ?>
                <a href="<?= $queryBase ?>&page=1" class="cm-page-btn"><span class="material-icons-round" style="font-size:16px;">first_page</span></a>
                <a href="<?= $queryBase ?>&page=<?= $currentPage - 1 ?>" class="cm-page-btn"><span class="material-icons-round" style="font-size:16px;">chevron_left</span></a>
            <?php else: ?>
                <span class="cm-page-btn" disabled><span class="material-icons-round" style="font-size:16px;">first_page</span></span>
                <span class="cm-page-btn" disabled><span class="material-icons-round" style="font-size:16px;">chevron_left</span></span>
            <?php endif; ?>

            <?php
                $start = max(1, $currentPage - 2);
                $end = min($totalPages, $currentPage + 2);
                if ($start > 1) { echo '<span class="cm-page-btn" style="pointer-events:none;">…</span>'; }
            ?>
            <?php for ($i = $start; $i <= $end; $i++): ?>
                <a href="<?= $queryBase ?>&page=<?= $i ?>" class="cm-page-btn <?= ($i === $currentPage) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
            <?php if ($end < $totalPages) { echo '<span class="cm-page-btn" style="pointer-events:none;">…</span>'; } ?>

            <?php if ($currentPage < $totalPages): ?>
                <a href="<?= $queryBase ?>&page=<?= $currentPage + 1 ?>" class="cm-page-btn"><span class="material-icons-round" style="font-size:16px;">chevron_right</span></a>
                <a href="<?= $queryBase ?>&page=<?= $totalPages ?>" class="cm-page-btn"><span class="material-icons-round" style="font-size:16px;">last_page</span></a>
            <?php else: ?>
                <span class="cm-page-btn" disabled><span class="material-icons-round" style="font-size:16px;">chevron_right</span></span>
                <span class="cm-page-btn" disabled><span class="material-icons-round" style="font-size:16px;">last_page</span></span>
            <?php endif; ?>
        </div>
        <div class="cm-page-info">Page <?= $currentPage ?> of <?= $totalPages ?> · <?= number_format($total) ?> total</div>
        <?php endif; ?>

    <?php endif; ?>
</div>

<script>
function deleteContent(type, id) {
    if (!confirm('Delete this content? This action hides it from users.')) return;

    var btn = document.querySelector('#cm-actions-' + id + ' .cm-btn-delete');
    if (btn) { btn.style.opacity = '0.5'; btn.style.pointerEvents = 'none'; }

    fetch('/admin/content/' + type + '/' + id + '/delete', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        if (d.success) {
            showToast(d.message || 'Content deleted');
            setTimeout(function() { location.reload(); }, 600);
        } else {
            showToast(d.error || 'Failed to delete', true);
            if (btn) { btn.style.opacity = ''; btn.style.pointerEvents = ''; }
        }
    })
    .catch(function() {
        showToast('Network error. Please try again.', true);
        if (btn) { btn.style.opacity = ''; btn.style.pointerEvents = ''; }
    });
}

function restoreContent(type, id) {
    var btn = document.querySelector('#cm-actions-' + id + ' .cm-btn-restore');
    if (btn) { btn.style.opacity = '0.5'; btn.style.pointerEvents = 'none'; }

    fetch('/admin/content/' + type + '/' + id + '/restore', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        if (d.success) {
            showToast(d.message || 'Content restored');
            setTimeout(function() { location.reload(); }, 600);
        } else {
            showToast(d.error || 'Failed to restore', true);
            if (btn) { btn.style.opacity = ''; btn.style.pointerEvents = ''; }
        }
    })
    .catch(function() {
        showToast('Network error. Please try again.', true);
        if (btn) { btn.style.opacity = ''; btn.style.pointerEvents = ''; }
    });
}

function featureContent(type, id) {
    var btn = document.querySelector('#cm-actions-' + id + ' .cm-btn-feature');
    if (btn) { btn.style.opacity = '0.5'; btn.style.pointerEvents = 'none'; }

    fetch('/admin/content/' + type + '/' + id + '/feature', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        showToast(d.message || (d.featured ? 'Content featured' : 'Content unfeatured'));
        if (btn) { btn.style.opacity = ''; btn.style.pointerEvents = ''; }
    })
    .catch(function() {
        showToast('Network error. Please try again.', true);
        if (btn) { btn.style.opacity = ''; btn.style.pointerEvents = ''; }
    });
}

function showToast(msg, isError) {
    var existing = document.querySelector('.cm-toast');
    if (existing) existing.remove();

    var t = document.createElement('div');
    t.className = 'cm-toast';
    t.style.background = isError ? '#ef4444' : '#22c55e';
    t.style.color = '#fff';
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(function() { t.remove(); }, 2500);
}
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
