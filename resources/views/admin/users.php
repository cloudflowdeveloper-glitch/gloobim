<?php $title = 'Manage Users — Admin'; ?>
<?php $data = $data ?? []; ?>
<?php extract($data); ?>
<?php ob_start(); ?>
<style>
    :root {
        --bg: #090c15;
        --card: #14141c;
        --border: #1e1e2a;
        --primary: #834ae5;
        --primary-dim: rgba(131,74,229,0.15);
        --green: #22c55e;
        --green-dim: rgba(34,197,94,0.15);
        --red: #ef4444;
        --red-dim: rgba(239,68,68,0.15);
        --amber: #f59e0b;
        --amber-dim: rgba(245,158,11,0.15);
        --gray: #94a3b8;
        --gray-dim: rgba(148,163,184,0.15);
        --text: #e2e8f0;
        --text-dim: #64748b;
    }
    body { background: var(--bg); font-family: 'Inter', sans-serif; color: var(--text); margin: 0; -webkit-font-smoothing: antialiased; }
    .au-container { max-width: 672px; margin: 0 auto; padding: 12px 12px 96px; }

    /* Header */
    .au-header { display: flex; align-items: center; gap: 12px; padding: 36px 0 20px; }
    .au-back { width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--border); color: var(--gray); font-size: 22px; cursor: pointer; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: background 0.15s; flex-shrink: 0; }
    .au-back:hover { background: rgba(255,255,255,0.08); }
    .au-header-title { font-size: 20px; font-weight: 700; flex: 1; }
    .au-count-badge { background: var(--primary-dim); color: var(--primary); font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 20px; white-space: nowrap; }

    /* Filters */
    .au-filters { display: flex; gap: 8px; margin-bottom: 16px; flex-wrap: wrap; }
    .au-search-wrap { flex: 1; min-width: 0; position: relative; }
    .au-search-wrap .material-icons-round { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-dim); font-size: 20px; pointer-events: none; }
    .au-search { width: 100%; height: 42px; border-radius: 12px; background: var(--card); border: 1px solid var(--border); color: var(--text); padding: 0 14px 0 40px; font-size: 14px; outline: none; box-sizing: border-box; transition: border-color 0.15s; }
    .au-search::placeholder { color: var(--text-dim); }
    .au-search:focus { border-color: var(--primary); }
    .au-select { height: 42px; border-radius: 12px; background: var(--card); border: 1px solid var(--border); color: var(--text); padding: 0 10px; font-size: 13px; outline: none; cursor: pointer; box-sizing: border-box; -webkit-appearance: none; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='%2394a3b8'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; padding-right: 28px; min-width: 110px; }
    .au-select:focus { border-color: var(--primary); }

    /* User Card */
    .au-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 14px; margin-bottom: 10px; transition: border-color 0.15s; }
    .au-card:hover { border-color: rgba(255,255,255,0.08); }
    .au-card.banned-card { border-color: rgba(239,68,68,0.25); }
    .au-card-top { display: flex; gap: 12px; align-items: flex-start; }
    .au-avatar { width: 42px; height: 42px; border-radius: 50%; object-fit: cover; flex-shrink: 0; background: var(--primary-dim); display: flex; align-items: center; justify-content: center; color: var(--primary); font-weight: 700; font-size: 15px; overflow: hidden; }
    .au-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
    .au-info { flex: 1; min-width: 0; }
    .au-name-row { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
    .au-name { font-size: 15px; font-weight: 650; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .au-verified { color: #22c55e; font-size: 16px; flex-shrink: 0; display: none; }
    .au-verified.show { display: inline-block; }
    .au-banned-badge { background: var(--red-dim); color: var(--red); font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 8px; text-transform: uppercase; letter-spacing: 0.5px; flex-shrink: 0; display: none; }
    .au-banned-badge.show { display: inline-block; }
    .au-username { font-size: 13px; color: var(--text-dim); margin-top: 1px; }
    .au-email { font-size: 12px; color: var(--text-dim); opacity: 0.7; margin-top: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    /* Badges */
    .au-badges { display: flex; flex-wrap: wrap; gap: 5px; margin-top: 8px; }
    .au-badge { font-size: 10px; font-weight: 600; padding: 3px 9px; border-radius: 8px; text-transform: uppercase; letter-spacing: 0.3px; }
    .au-badge.role-user { background: var(--gray-dim); color: var(--gray); }
    .au-badge.role-creator { background: var(--primary-dim); color: var(--primary); }
    .au-badge.role-admin { background: var(--amber-dim); color: var(--amber); }
    .au-badge.type-personal { background: rgba(148,163,184,0.08); color: #94a3b8; }
    .au-badge.type-creator { background: var(--primary-dim); color: var(--primary); }
    .au-badge.type-business { background: var(--amber-dim); color: var(--amber); }
    .au-badge.type-government { background: rgba(56,189,248,0.15); color: #38bdf8; }

    /* Stats */
    .au-stats { display: flex; gap: 14px; margin-top: 10px; }
    .au-stat { display: flex; align-items: center; gap: 4px; font-size: 12px; color: var(--text-dim); }
    .au-stat .material-icons-round { font-size: 14px; color: var(--text-dim); opacity: 0.7; }
    .au-stat strong { color: var(--text); font-weight: 600; }

    /* Meta */
    .au-meta { display: flex; justify-content: space-between; align-items: center; margin-top: 10px; font-size: 11px; color: var(--text-dim); }

    /* Actions */
    .au-actions { display: flex; flex-direction: column; gap: 6px; flex-shrink: 0; margin-left: 4px; }
    .au-btn { height: 32px; border-radius: 8px; border: 1px solid var(--border); background: rgba(255,255,255,0.03); color: var(--gray); font-size: 11px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 4px; padding: 0 10px; white-space: nowrap; transition: all 0.15s; font-family: inherit; }
    .au-btn:hover { background: rgba(255,255,255,0.06); color: var(--text); }
    .au-btn .material-icons-round { font-size: 15px; }
    .au-btn.btn-ban { border-color: rgba(239,68,68,0.2); }
    .au-btn.btn-ban:hover { background: var(--red-dim); color: var(--red); border-color: rgba(239,68,68,0.35); }
    .au-btn.btn-ban.is-banned { background: var(--green-dim); color: var(--green); border-color: rgba(34,197,94,0.25); }
    .au-btn.btn-ban.is-banned:hover { border-color: rgba(34,197,94,0.4); }
    .au-btn.btn-verify { border-color: rgba(34,197,94,0.2); }
    .au-btn.btn-verify:hover { background: var(--green-dim); color: var(--green); border-color: rgba(34,197,94,0.35); }
    .au-btn.btn-verify.is-verified { background: var(--primary-dim); color: var(--primary); border-color: rgba(131,74,229,0.25); }
    .au-btn.btn-verify.is-verified:hover { border-color: rgba(131,74,229,0.4); }
    .au-role-select { height: 32px; border-radius: 8px; background: var(--card); border: 1px solid var(--border); color: var(--text); padding: 0 6px; font-size: 11px; font-weight: 600; cursor: pointer; outline: none; -webkit-appearance: none; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='%2394a3b8'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 6px center; padding-right: 20px; font-family: inherit; }
    .au-role-select:focus { border-color: var(--primary); }

    /* Pagination */
    .au-pagination { display: flex; align-items: center; justify-content: center; gap: 6px; padding-top: 20px; }
    .au-page-btn { min-width: 36px; height: 36px; border-radius: 10px; border: 1px solid var(--border); background: var(--card); color: var(--gray); font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 0 6px; transition: all 0.15s; font-family: inherit; }
    .au-page-btn:hover:not(:disabled) { background: rgba(255,255,255,0.06); color: var(--text); }
    .au-page-btn.active { background: var(--primary); color: white; border-color: var(--primary); }
    .au-page-btn:disabled { opacity: 0.3; cursor: not-allowed; }
    .au-page-btn .material-icons-round { font-size: 18px; }
    .au-page-info { font-size: 12px; color: var(--text-dim); padding: 0 6px; }

    /* Empty state */
    .au-empty { text-align: center; padding: 60px 20px; color: var(--text-dim); }
    .au-empty .material-icons-round { font-size: 48px; opacity: 0.3; margin-bottom: 12px; display: block; }
    .au-empty p { font-size: 14px; }

    /* Toast */
    .au-toast { position: fixed; bottom: 40px; left: 50%; transform: translateX(-50%); padding: 10px 24px; border-radius: 24px; font-weight: 600; z-index: 999; font-size: 13px; color: white; animation: auFadeInUp 0.3s ease; box-shadow: 0 8px 30px rgba(0,0,0,0.4); }
    @keyframes auFadeInUp { from { opacity: 0; transform: translate(-50%, 10px); } to { opacity: 1; transform: translate(-50%, 0); } }

    /* Responsive */
    @media (min-width: 520px) {
        .au-actions { flex-direction: row; align-items: center; margin-left: 8px; }
        .au-role-select { min-width: 90px; }
    }
</style>

<div class="au-container">
    <!-- Header -->
    <div class="au-header">
        <a href="/admin" class="au-back"><span class="material-icons-round">arrow_back</span></a>
        <span class="au-header-title">Manage Users</span>
        <span class="au-count-badge"><?= number_format($total ?? 0) ?> users</span>
    </div>

    <!-- Filters -->
    <div class="au-filters">
        <div class="au-search-wrap">
            <span class="material-icons-round">search</span>
            <input type="text" class="au-search" id="auSearchInput" placeholder="Search users..." value="<?= htmlspecialchars($search ?? '') ?>" onkeydown="if(event.key==='Enter')searchUsers()">
        </div>
        <select class="au-select" id="auRoleFilter" onchange="searchUsers()">
            <option value="">All Roles</option>
            <option value="user" <?= ($role ?? '') === 'user' ? 'selected' : '' ?>>User</option>
            <option value="creator" <?= ($role ?? '') === 'creator' ? 'selected' : '' ?>>Creator</option>
            <option value="admin" <?= ($role ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>
        <select class="au-select" id="auStatusFilter" onchange="searchUsers()">
            <option value="">All Status</option>
            <option value="active" <?= ($status ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="banned" <?= ($status ?? '') === 'banned' ? 'selected' : '' ?>>Banned</option>
        </select>
    </div>

    <!-- User List -->
    <?php if (empty($users)): ?>
    <div class="au-empty">
        <span class="material-icons-round">people_outline</span>
        <p>No users found</p>
    </div>
    <?php else: ?>
        <?php foreach ($users as $u): ?>
        <?php
            $initials = mb_substr($u['name'] ?? 'U', 0, 1);
            $avatarHtml = !empty($u['avatar'])
                ? '<img src="' . htmlspecialchars($u['avatar']) . '" alt="" onerror="this.parentElement.textContent=\'' . htmlspecialchars($initials) . '\'">'
                : htmlspecialchars($initials);
            $roleClass = 'role-' . ($u['role'] ?? 'user');
            $profileType = $u['profile_type'] ?? 'personal';
            $typeClass = 'type-' . $profileType;
            $lastLogin = !empty($u['last_login_at']) ? timeAgo($u['last_login_at']) : 'Never';
            $createdAt = timeAgo($u['created_at']);
        ?>
        <div class="au-card <?= ($u['is_banned'] ?? false) ? 'banned-card' : '' ?>" id="user-<?= $u['id'] ?>">
            <div class="au-card-top">
                <div class="au-avatar"><?= $avatarHtml ?></div>
                <div class="au-info">
                    <div class="au-name-row">
                        <span class="au-name"><?= htmlspecialchars($u['name'] ?? 'Unknown') ?></span>
                        <span class="au-verified <?= ($u['is_verified'] ?? false) ? 'show' : '' ?>" id="vicon-<?= $u['id'] ?>">
                            <span class="material-icons-round" style="font-size:16px">verified</span>
                        </span>
                        <span class="au-banned-badge <?= ($u['is_banned'] ?? false) ? 'show' : '' ?>" id="bbadge-<?= $u['id'] ?>">Banned</span>
                    </div>
                    <div class="au-username">@<?= htmlspecialchars($u['username'] ?? '') ?></div>
                    <div class="au-email"><?= htmlspecialchars($u['email'] ?? '') ?></div>
                    <div class="au-badges">
                        <span class="au-badge <?= $roleClass ?>" id="rbadge-<?= $u['id'] ?>"><?= htmlspecialchars($u['role'] ?? 'user') ?></span>
                        <span class="au-badge <?= $typeClass ?>"><?= htmlspecialchars(ucfirst($profileType)) ?></span>
                    </div>
                    <div class="au-stats">
                        <span class="au-stat">
                            <span class="material-icons-round">people</span>
                            <strong><?= formatCount($u['follower_count'] ?? 0) ?></strong>
                        </span>
                        <span class="au-stat">
                            <span class="material-icons-round">article</span>
                            <strong><?= formatCount($u['post_count'] ?? 0) ?></strong>
                        </span>
                        <span class="au-stat">
                            <span class="material-icons-round">videocam</span>
                            <strong><?= formatCount($u['reel_count'] ?? 0) ?></strong>
                        </span>
                    </div>
                    <div class="au-meta">
                        <span>Joined <?= $createdAt ?></span>
                        <span>Login: <?= $lastLogin ?></span>
                    </div>
                </div>
                <div class="au-actions">
                    <select class="au-role-select" id="role-sel-<?= $u['id'] ?>" onchange="changeRole(<?= $u['id'] ?>, this.value)">
                        <option value="user" <?= ($u['role'] ?? '') === 'user' ? 'selected' : '' ?>>User</option>
                        <option value="creator" <?= ($u['role'] ?? '') === 'creator' ? 'selected' : '' ?>>Creator</option>
                        <option value="admin" <?= ($u['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                    <button class="au-btn btn-verify <?= ($u['is_verified'] ?? false) ? 'is-verified' : '' ?>" id="vbtn-<?= $u['id'] ?>" onclick="toggleVerify(<?= $u['id'] ?>)">
                        <span class="material-icons-round">verified</span>
                        <span id="vbtn-text-<?= $u['id'] ?>">Verify</span>
                    </button>
                    <button class="au-btn btn-ban <?= ($u['is_banned'] ?? false) ? 'is-banned' : '' ?>" id="bbtn-<?= $u['id'] ?>" onclick="toggleBan(<?= $u['id'] ?>)">
                        <span class="material-icons-round" id="bbtn-icon-<?= $u['id'] ?>"><?= ($u['is_banned'] ?? false) ? 'check_circle' : 'block' ?></span>
                        <span id="bbtn-text-<?= $u['id'] ?>"><?= ($u['is_banned'] ?? false) ? 'Unban' : 'Ban' ?></span>
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Pagination -->
    <?php
        $currentPage = max(1, (int)($page ?? 1));
        $totalPages = max(1, (int)ceil(($total ?? 0) / ($perPage ?? 20)));
        $hasPrev = $currentPage > 1;
        $hasNext = $currentPage < $totalPages;
        $queryParams = http_build_query(array_filter([
            'q' => $search ?? '',
            'role' => $role ?? '',
            'status' => $status ?? '',
        ], function($v) { return $v !== ''; }));
        $qPrefix = $queryParams ? '?' . $queryParams . '&' : '?';
    ?>
    <?php if ($totalPages > 1): ?>
    <div class="au-pagination">
        <button class="au-page-btn" <?= $hasPrev ? '' : 'disabled' ?> onclick="location.href='/admin/users<?= $qPrefix ?>page=<?= $currentPage - 1 ?>'">
            <span class="material-icons-round">chevron_left</span>
        </button>
        <?php
            $start = max(1, $currentPage - 2);
            $end = min($totalPages, $currentPage + 2);
            if ($start > 1) {
                echo '<button class="au-page-btn" onclick="location.href=\'/admin/users' . $qPrefix . 'page=1\'">1</button>';
                if ($start > 2) echo '<span class="au-page-info">...</span>';
            }
            for ($i = $start; $i <= $end; $i++):
        ?>
        <button class="au-page-btn <?= $i === $currentPage ? 'active' : '' ?>" onclick="location.href='/admin/users<?= $qPrefix ?>page=<?= $i ?>'"><?= $i ?></button>
        <?php endfor; ?>
        <?php
            if ($end < $totalPages) {
                if ($end < $totalPages - 1) echo '<span class="au-page-info">...</span>';
                echo '<button class="au-page-btn" onclick="location.href=\'/admin/users' . $qPrefix . 'page=' . $totalPages . '\'">' . $totalPages . '</button>';
            }
        ?>
        <button class="au-page-btn" <?= $hasNext ? '' : 'disabled' ?> onclick="location.href='/admin/users<?= $qPrefix ?>page=<?= $currentPage + 1 ?>'">
            <span class="material-icons-round">chevron_right</span>
        </button>
    </div>
    <?php endif; ?>
</div>

<script>
function toggleBan(id) {
    var card = document.getElementById('user-' + id);
    var btn = document.getElementById('bbtn-' + id);
    var btnIcon = document.getElementById('bbtn-icon-' + id);
    var btnText = document.getElementById('bbtn-text-' + id);
    var badge = document.getElementById('bbadge-' + id);
    var isBanned = btn.classList.contains('is-banned');

    btn.style.pointerEvents = 'none';
    btn.style.opacity = '0.5';

    fetch('/admin/users/' + id + '/ban', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' }
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        if (d.banned) {
            btn.classList.add('is-banned');
            btnIcon.textContent = 'check_circle';
            btnText.textContent = 'Unban';
            badge.classList.add('show');
            card.classList.add('banned-card');
        } else {
            btn.classList.remove('is-banned');
            btnIcon.textContent = 'block';
            btnText.textContent = 'Ban';
            badge.classList.remove('show');
            card.classList.remove('banned-card');
        }
        showToast(d.message || (d.banned ? 'User banned' : 'User unbanned'));
        btn.style.pointerEvents = '';
        btn.style.opacity = '';
    })
    .catch(function() {
        showToast('Error updating ban status', true);
        btn.style.pointerEvents = '';
        btn.style.opacity = '';
    });
}

function toggleVerify(id) {
    var btn = document.getElementById('vbtn-' + id);
    var btnText = document.getElementById('vbtn-text-' + id);
    var icon = document.getElementById('vicon-' + id);
    var isVerified = btn.classList.contains('is-verified');

    btn.style.pointerEvents = 'none';
    btn.style.opacity = '0.5';

    fetch('/admin/users/' + id + '/verify', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' }
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        if (d.verified) {
            btn.classList.add('is-verified');
            btnText.textContent = 'Unverify';
            icon.classList.add('show');
        } else {
            btn.classList.remove('is-verified');
            btnText.textContent = 'Verify';
            icon.classList.remove('show');
        }
        showToast(d.message || (d.verified ? 'User verified' : 'User unverified'));
        btn.style.pointerEvents = '';
        btn.style.opacity = '';
    })
    .catch(function() {
        showToast('Error updating verification', true);
        btn.style.pointerEvents = '';
        btn.style.opacity = '';
    });
}

function changeRole(id, role) {
    var select = document.getElementById('role-sel-' + id);
    var badge = document.getElementById('rbadge-' + id);
    var prevRole = badge.textContent.trim().toLowerCase();

    if (role === prevRole) return;

    select.style.pointerEvents = 'none';
    select.style.opacity = '0.5';

    fetch('/admin/users/' + id + '/role', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' },
        body: JSON.stringify({ role: role })
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        if (d.role) {
            badge.textContent = d.role;
            badge.className = 'au-badge role-' + d.role;
            showToast('Role changed to ' + d.role);
        } else {
            select.value = prevRole;
            showToast('Error changing role', true);
        }
        select.style.pointerEvents = '';
        select.style.opacity = '';
    })
    .catch(function() {
        select.value = prevRole;
        showToast('Error changing role', true);
        select.style.pointerEvents = '';
        select.style.opacity = '';
    });
}

function searchUsers() {
    var q = document.getElementById('auSearchInput').value.trim();
    var role = document.getElementById('auRoleFilter').value;
    var status = document.getElementById('auStatusFilter').value;
    var params = new URLSearchParams();
    if (q) params.set('q', q);
    if (role) params.set('role', role);
    if (status) params.set('status', status);
    var qs = params.toString();
    location.href = '/admin/users' + (qs ? '?' + qs : '');
}

function showToast(msg, isError) {
    var existing = document.querySelector('.au-toast');
    if (existing) existing.remove();

    var t = document.createElement('div');
    t.className = 'au-toast';
    t.style.background = isError ? '#ef4444' : '#22c55e';
    t.style.color = 'white';
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(function() { t.remove(); }, 2500);
}
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
