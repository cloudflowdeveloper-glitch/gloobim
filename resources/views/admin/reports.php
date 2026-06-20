<?php $title = 'Reports — Admin'; ?>
<?php $data = $data ?? []; ?>
<?php extract($data); ?>
<?php
$reports = $reports ?? [];
$status = $status ?? 'pending';
$page = $page ?? 1;
$perPage = $perPage ?? 20;
$total = $total ?? 0;
$totalPages = (int) ceil($total / $perPage);
$pendingCount = $pendingCount ?? 0;
?>
<?php ob_start(); ?>
<style>
    :root {
        --bg-deep: #090c15;
        --bg-card: #14141c;
        --border: #1e1e2a;
        --primary: #834ae5;
        --red: #ef4444;
        --green: #22c55e;
        --amber: #f59e0b;
        --blue: #3b82f6;
        --zinc: #a1a1aa;
        --text: #e4e4e7;
        --text-dim: #71717a;
    }

    body {
        background: var(--bg-deep);
        font-family: 'Inter', -apple-system, sans-serif;
        color: var(--text);
        -webkit-font-smoothing: antialiased;
    }

    .ar-container {
        max-width: 42rem;
        margin: 0 auto;
        padding: 16px 12px 96px;
    }

    /* Header */
    .ar-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 0 20px;
    }

    .ar-back {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: rgba(255,255,255,0.05);
        border: none;
        color: #94a3b8;
        font-size: 22px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
        flex-shrink: 0;
    }

    .ar-back:hover {
        background: rgba(255,255,255,0.08);
    }

    .ar-header-title {
        font-size: 20px;
        font-weight: 700;
        flex: 1;
    }

    .ar-pending-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 24px;
        height: 24px;
        padding: 0 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
        background: var(--red);
        color: white;
    }

    .ar-pending-badge.zero {
        background: rgba(255,255,255,0.08);
        color: var(--text-dim);
    }

    /* Tabs */
    .ar-tabs {
        display: flex;
        gap: 4px;
        margin-bottom: 16px;
        background: rgba(255,255,255,0.04);
        border-radius: 14px;
        padding: 4px;
        border: 1px solid var(--border);
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .ar-tab {
        flex: 1;
        min-width: 0;
        text-align: center;
        padding: 10px 6px;
        border-radius: 11px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        color: var(--text-dim);
        border: none;
        background: none;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        white-space: nowrap;
    }

    .ar-tab.active {
        background: var(--primary);
        color: white;
    }

    .ar-tab:not(.active):hover {
        color: var(--text);
        background: rgba(255,255,255,0.04);
    }

    .ar-tab-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 20px;
        height: 20px;
        padding: 0 6px;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 700;
        background: rgba(255,255,255,0.12);
    }

    .ar-tab.active .ar-tab-count {
        background: rgba(255,255,255,0.2);
    }

    /* Report Card */
    .ar-card {
        background: var(--bg-card);
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 10px;
        border: 1px solid var(--border);
        transition: border-color 0.2s;
    }

    .ar-card:hover {
        border-color: rgba(255,255,255,0.1);
    }

    .ar-card-top {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }

    .ar-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
        border: 2px solid var(--border);
    }

    .ar-avatar-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(131,74,229,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--primary);
        font-size: 20px;
    }

    .ar-reporter-info {
        flex: 1;
        min-width: 0;
    }

    .ar-reporter-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .ar-reporter-username {
        font-size: 12px;
        color: var(--text-dim);
    }

    .ar-card-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .ar-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        flex-shrink: 0;
    }

    .ar-status-badge.pending {
        background: rgba(245,158,11,0.12);
        color: var(--amber);
    }

    .ar-status-badge.reviewed {
        background: rgba(59,130,246,0.12);
        color: var(--blue);
    }

    .ar-status-badge.resolved {
        background: rgba(34,197,94,0.12);
        color: var(--green);
    }

    .ar-status-badge.dismissed {
        background: rgba(161,161,170,0.12);
        color: var(--zinc);
    }

    .ar-status-badge .material-icons-round {
        font-size: 13px;
    }

    .ar-time {
        font-size: 11px;
        color: var(--text-dim);
        margin-left: auto;
        flex-shrink: 0;
    }

    .ar-target {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: var(--text-dim);
        margin-bottom: 10px;
        padding: 8px 10px;
        background: rgba(255,255,255,0.03);
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .ar-target .material-icons-round {
        font-size: 16px;
        color: var(--red);
    }

    .ar-target-label {
        font-weight: 500;
    }

    .ar-target-name {
        color: var(--text);
        font-weight: 600;
    }

    .ar-body {
        margin-bottom: 14px;
    }

    .ar-reason {
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 4px;
    }

    .ar-description {
        font-size: 13px;
        color: var(--text-dim);
        line-height: 1.5;
        word-break: break-word;
    }

    .ar-actions {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        padding-top: 12px;
        border-top: 1px solid var(--border);
    }

    .ar-btn {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 7px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        color: white;
    }

    .ar-btn .material-icons-round {
        font-size: 15px;
    }

    .ar-btn-review {
        background: rgba(59,130,246,0.12);
        color: var(--blue);
    }

    .ar-btn-review:hover {
        background: rgba(59,130,246,0.2);
    }

    .ar-btn-resolve {
        background: rgba(34,197,94,0.12);
        color: var(--green);
    }

    .ar-btn-resolve:hover {
        background: rgba(34,197,94,0.2);
    }

    .ar-btn-dismiss {
        background: rgba(161,161,170,0.1);
        color: var(--zinc);
    }

    .ar-btn-dismiss:hover {
        background: rgba(161,161,170,0.18);
    }

    .ar-btn-delete {
        background: rgba(239,68,68,0.12);
        color: var(--red);
        margin-left: auto;
    }

    .ar-btn-delete:hover {
        background: rgba(239,68,68,0.2);
    }

    .ar-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    /* Pagination */
    .ar-pagination {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 20px;
    }

    .ar-page-btn {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid var(--border);
        background: var(--bg-card);
        color: var(--text);
        transition: all 0.2s;
    }

    .ar-page-btn:hover:not(:disabled) {
        border-color: var(--primary);
        background: rgba(131,74,229,0.1);
    }

    .ar-page-btn.active {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }

    .ar-page-btn:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    .ar-page-btn .material-icons-round {
        font-size: 20px;
    }

    .ar-page-info {
        font-size: 13px;
        color: var(--text-dim);
        padding: 0 8px;
    }

    /* Empty state */
    .ar-empty {
        text-align: center;
        padding: 48px 20px;
    }

    .ar-empty-icon {
        font-size: 56px;
        color: var(--text-dim);
        opacity: 0.3;
        margin-bottom: 12px;
    }

    .ar-empty-text {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-dim);
    }

    /* Toast */
    .ar-toast {
        position: fixed;
        bottom: 40px;
        left: 50%;
        transform: translateX(-50%);
        padding: 12px 24px;
        border-radius: 14px;
        font-weight: 600;
        z-index: 9999;
        font-size: 14px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        animation: ar-toast-in 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .ar-toast .material-icons-round {
        font-size: 18px;
    }

    @keyframes ar-toast-in {
        from { opacity: 0; transform: translate(-50%, 16px); }
        to { opacity: 1; transform: translate(-50%, 0); }
    }

    /* Responsive */
    @media (min-width: 640px) {
        .ar-container {
            padding: 24px 16px 96px;
        }
    }
</style>

<div class="ar-container">

    <!-- Header -->
    <div class="ar-header">
        <a href="/admin" class="ar-back">
            <span class="material-icons-round">arrow_back</span>
        </a>
        <span class="ar-header-title">Reports</span>
        <?php if ($pendingCount > 0): ?>
            <span class="ar-pending-badge"><?= (int) $pendingCount ?></span>
        <?php else: ?>
            <span class="ar-pending-badge zero">0</span>
        <?php endif; ?>
    </div>

    <!-- Status Tabs -->
    <div class="ar-tabs">
        <button class="ar-tab <?= $status === 'pending' ? 'active' : '' ?>" onclick="switchStatus('pending')">
            Pending
            <?php if (($pendingCount ?? 0) > 0): ?>
                <span class="ar-tab-count"><?= (int) $pendingCount ?></span>
            <?php endif; ?>
        </button>
        <button class="ar-tab <?= $status === 'reviewed' ? 'active' : '' ?>" onclick="switchStatus('reviewed')">
            Reviewed
        </button>
        <button class="ar-tab <?= $status === 'resolved' ? 'active' : '' ?>" onclick="switchStatus('resolved')">
            Resolved
        </button>
        <button class="ar-tab <?= $status === 'dismissed' ? 'active' : '' ?>" onclick="switchStatus('dismissed')">
            Dismissed
        </button>
    </div>

    <!-- Report List -->
    <?php if (!empty($reports)): ?>
        <?php foreach ($reports as $report): ?>
        <div class="ar-card" id="report-<?= (int) $report['id'] ?>">
            <!-- Top: Reporter + Status + Time -->
            <div class="ar-card-top">
                <?php if (!empty($report['reporter_avatar'])): ?>
                    <img class="ar-avatar" src="<?= htmlspecialchars($report['reporter_avatar']) ?>" alt="" loading="lazy">
                <?php else: ?>
                    <div class="ar-avatar-placeholder">
                        <span class="material-icons-round">person</span>
                    </div>
                <?php endif; ?>
                <div class="ar-reporter-info">
                    <div class="ar-reporter-name"><?= htmlspecialchars($report['reporter_name'] ?? 'Unknown') ?></div>
                    <div class="ar-reporter-username"><?= htmlspecialchars($report['reporter_username'] ?? '') ?></div>
                </div>
                <span class="ar-time"><?= timeAgo($report['created_at']) ?></span>
            </div>

            <!-- Meta: Status badge -->
            <div class="ar-card-meta" style="margin-bottom: 10px;">
                <span class="ar-status-badge <?= htmlspecialchars($report['status']) ?>">
                    <span class="material-icons-round"><?= $report['status'] === 'pending' ? 'schedule' : ($report['status'] === 'reviewed' ? 'visibility' : ($report['status'] === 'resolved' ? 'check_circle' : 'block')) ?></span>
                    <?= htmlspecialchars(ucfirst($report['status'])) ?>
                </span>
            </div>

            <!-- Target info -->
            <div class="ar-target">
                <span class="material-icons-round">flag</span>
                <span class="ar-target-label">Reported <?= htmlspecialchars(ucfirst($report['reportable_type'] ?? 'content')) ?> #<?= (int) $report['reportable_id'] ?></span>
                <?php if (!empty($report['target_username'])): ?>
                    &mdash;
                    <span class="ar-target-name">@<?= htmlspecialchars($report['target_username']) ?></span>
                <?php elseif (!empty($report['target_name'])): ?>
                    &mdash;
                    <span class="ar-target-name"><?= htmlspecialchars($report['target_name']) ?></span>
                <?php endif; ?>
            </div>

            <!-- Body -->
            <div class="ar-body">
                <div class="ar-reason"><?= htmlspecialchars($report['reason'] ?? 'No reason provided') ?></div>
                <?php if (!empty($report['description'])): ?>
                    <div class="ar-description"><?= htmlspecialchars($report['description']) ?></div>
                <?php endif; ?>
            </div>

            <!-- Actions -->
            <div class="ar-actions">
                <?php if ($report['status'] === 'pending'): ?>
                    <button class="ar-btn ar-btn-review" onclick="updateReport(<?= (int) $report['id'] ?>, 'reviewed')">
                        <span class="material-icons-round">visibility</span>
                        Review
                    </button>
                <?php endif; ?>

                <?php if (in_array($report['status'], ['pending', 'reviewed'])): ?>
                    <button class="ar-btn ar-btn-resolve" onclick="updateReport(<?= (int) $report['id'] ?>, 'resolved')">
                        <span class="material-icons-round">check_circle</span>
                        Resolve
                    </button>
                    <button class="ar-btn ar-btn-dismiss" onclick="updateReport(<?= (int) $report['id'] ?>, 'dismissed')">
                        <span class="material-icons-round">block</span>
                        Dismiss
                    </button>
                <?php endif; ?>

                <button class="ar-btn ar-btn-delete" onclick="deleteReportedContent('<?= htmlspecialchars($report['reportable_type'] ?? 'post') ?>', <?= (int) $report['reportable_id'] ?>)">
                    <span class="material-icons-round">delete_forever</span>
                    Delete Content
                </button>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="ar-pagination">
            <button class="ar-page-btn" <?= $page <= 1 ? 'disabled' : '' ?> onclick="goToPage(<?= $page - 1 ?>)">
                <span class="material-icons-round">chevron_left</span>
            </button>

            <?php
            $start = max(1, $page - 2);
            $end = min($totalPages, $start + 4);
            $start = max(1, $end - 4);
            ?>

            <?php if ($start > 1): ?>
                <button class="ar-page-btn" onclick="goToPage(1)">1</button>
                <?php if ($start > 2): ?>
                    <span class="ar-page-info">...</span>
                <?php endif; ?>
            <?php endif; ?>

            <?php for ($i = $start; $i <= $end; $i++): ?>
                <button class="ar-page-btn <?= $i === $page ? 'active' : '' ?>" onclick="goToPage(<?= $i ?>)">
                    <?= $i ?>
                </button>
            <?php endfor; ?>

            <?php if ($end < $totalPages): ?>
                <?php if ($end < $totalPages - 1): ?>
                    <span class="ar-page-info">...</span>
                <?php endif; ?>
                <button class="ar-page-btn" onclick="goToPage(<?= $totalPages ?>)"><?= $totalPages ?></button>
            <?php endif; ?>

            <button class="ar-page-btn" <?= $page >= $totalPages ? 'disabled' : '' ?> onclick="goToPage(<?= $page + 1 ?>)">
                <span class="material-icons-round">chevron_right</span>
            </button>
        </div>
        <div style="text-align:center;margin-top:8px;">
            <span class="ar-page-info"><?= $total ?> total report<?= $total !== 1 ? 's' : '' ?></span>
        </div>
        <?php endif; ?>

    <?php else: ?>
        <!-- Empty state -->
        <div class="ar-empty">
            <div class="ar-empty-icon">
                <?php
                $emptyIcon = 'inbox';
                $emptyText = 'No reports';
                if ($status === 'pending') { $emptyIcon = 'schedule'; $emptyText = 'No pending reports'; }
                elseif ($status === 'reviewed') { $emptyIcon = 'visibility'; $emptyText = 'No reviewed reports'; }
                elseif ($status === 'resolved') { $emptyIcon = 'check_circle'; $emptyText = 'No resolved reports'; }
                elseif ($status === 'dismissed') { $emptyIcon = 'block'; $emptyText = 'No dismissed reports'; }
                ?>
                <span class="material-icons-round"><?= $emptyIcon ?></span>
            </div>
            <div class="ar-empty-text"><?= $emptyText ?></div>
        </div>
    <?php endif; ?>
</div>

<script>
function switchStatus(newStatus) {
    window.location.href = '/admin/reports?status=' + encodeURIComponent(newStatus);
}

function goToPage(page) {
    var currentStatus = '<?= htmlspecialchars($status) ?>';
    window.location.href = '/admin/reports?status=' + encodeURIComponent(currentStatus) + '&page=' + page;
}

function updateReport(id, newStatus) {
    var card = document.getElementById('report-' + id);
    if (card) {
        var buttons = card.querySelectorAll('.ar-btn');
        buttons.forEach(function(btn) { btn.disabled = true; });
    }

    fetch('/admin/reports/' + id, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
        if (data.success) {
            showToast(data.message || 'Report updated', false);
            setTimeout(function() { location.reload(); }, 600);
        } else {
            showToast(data.error || 'Failed to update report', true);
            if (card) {
                card.querySelectorAll('.ar-btn').forEach(function(btn) { btn.disabled = false; });
            }
        }
    })
    .catch(function() {
        showToast('Network error', true);
        if (card) {
            card.querySelectorAll('.ar-btn').forEach(function(btn) { btn.disabled = false; });
        }
    });
}

function deleteReportedContent(type, id) {
    if (!confirm('Are you sure you want to delete this reported content? This cannot be undone.')) return;

    var card = document.querySelector('.ar-card');
    if (card) {
        card.querySelectorAll('.ar-btn-delete').forEach(function(btn) { btn.disabled = true; });
    }

    fetch('/admin/content/' + encodeURIComponent(type) + '/' + id + '/delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
        if (data.success) {
            showToast(data.message || 'Content deleted', false);
            setTimeout(function() { location.reload(); }, 600);
        } else {
            showToast(data.error || 'Failed to delete content', true);
        }
    })
    .catch(function() {
        showToast('Network error', true);
    });
}

function showToast(msg, isError) {
    // Remove existing toasts
    document.querySelectorAll('.ar-toast').forEach(function(t) { t.remove(); });

    var toast = document.createElement('div');
    toast.className = 'ar-toast';
    toast.style.background = isError ? '#ef4444' : '#22c55e';
    toast.style.color = 'white';

    var icon = document.createElement('span');
    icon.className = 'material-icons-round';
    icon.textContent = isError ? 'error_outline' : 'check_circle_outline';

    var text = document.createElement('span');
    text.textContent = msg;

    toast.appendChild(icon);
    toast.appendChild(text);
    document.body.appendChild(toast);

    setTimeout(function() {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s';
        setTimeout(function() { toast.remove(); }, 300);
    }, 2500);
}
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
