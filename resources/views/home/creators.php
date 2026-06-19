<?php $hideTopNav = true; $activeTab = 'home'; $title = 'Creators - Globiim'; ?>
<?php extract($data ?? []); ?>
<?php ob_start(); ?>
<style>
    body { background: #090c15; }
    .story-ring { background: linear-gradient(135deg, #9333ea, #ec4899, #f59e0b); padding: 2px; border-radius: 50%; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    @keyframes toastIn { from { opacity: 0; transform: translateX(-50%) translateY(10px); } to { opacity: 1; transform: translateX(-50%) translateY(0); } }
    .toast { position: fixed; bottom: 80px; left: 50%; transform: translateX(-50%); z-index: 9999; padding: 10px 20px; border-radius: 999px; font-size: 13px; font-weight: 600; white-space: nowrap; animation: toastIn 0.3s ease; }
    .toast-success { background: #22c55e; color: #fff; }
    .toast-error { background: #ef4444; color: #fff; }
</style>

<div class="max-w-lg mx-auto pb-4">

    <!-- ===== HEADER ===== -->
    <div class="px-4 pt-3 pb-2">
        <div class="flex items-center justify-between mb-3">
            <a href="/" class="flex items-center gap-2">
                <span class="material-icons-round text-zinc-400 text-2xl">arrow_back</span>
                <h1 class="text-white font-bold text-lg">Creators</h1>
            </a>
            <div class="w-8"></div>
        </div>
    </div>

    <!-- ===== CREATORS LIST ===== -->
    <div class="px-4">
        <?php if (empty($creators)): ?>
            <div class="text-center py-20">
                <span class="material-icons-round text-zinc-700 text-6xl mb-3">group_off</span>
                <p class="text-zinc-500 text-sm">No creators found</p>
            </div>
        <?php else: ?>
        <div class="space-y-3">
            <?php foreach ($creators as $c): ?>
            <?php $isFollowing = !empty($c['is_following']); ?>
            <div class="flex items-center gap-3 bg-[#14141c] rounded-xl p-3 border border-[#1e1e2a] hover:border-[#834ae5]/20 transition-all">
                <a href="/creator/<?= $c['username'] ?>" class="flex-shrink-0">
                    <div class="story-ring">
                        <img src="<?= $c['avatar'] ?>" alt="<?= $c['name'] ?>" class="w-14 h-14 rounded-full object-cover">
                    </div>
                </a>
                <a href="/creator/<?= $c['username'] ?>" class="flex-1 min-w-0">
                    <div class="flex items-center gap-1">
                        <span class="text-white text-sm font-semibold truncate"><?= $c['name'] ?></span>
                        <?php if (!empty($c['is_verified'])): ?>
                        <span class="material-icons-round text-xs" style="color: #834ae5;">verified</span>
                        <?php endif; ?>
                    </div>
                    <p class="text-zinc-500 text-xs">@<?= $c['username'] ?></p>
                    <p class="text-zinc-600 text-xs mt-0.5"><?= number_format($c['follower_count'] ?? 0) ?> followers</p>
                </a>
                <button onclick="followCreator(<?= $c['id'] ?>, this)"
                        class="flex-shrink-0 px-4 py-1.5 rounded-full text-xs font-semibold hover:opacity-90 transition-all"
                        style="<?= $isFollowing ? 'background: #1e1e2a; color: #a1a1aa; border: 1px solid #3f3f46;' : 'background: linear-gradient(135deg, #834ae5, #6b21a8); color: #ffffff;' ?>"
                        data-following="<?= $isFollowing ? '1' : '0' ?>"><?= $isFollowing ? 'Following' : 'Follow' ?></button>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Bottom spacer for nav -->
    <div class="h-16"></div>
</div>

<script>
function showToast(msg, isError) {
    var t = document.createElement('div');
    t.className = 'toast ' + (isError ? 'toast-error' : 'toast-success');
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(function() { t.remove(); }, 2500);
}
async function apiPost(url, body) {
    if (!body) body = {};
    var res = await fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: JSON.stringify(body) });
    var data = await res.json();
    if (res.status === 401) showToast('Please login first', true);
    return data;
}
function followCreator(id, btn) {
    var wasFollowing = btn.dataset.following === '1';
    if (wasFollowing) {
        btn.textContent = 'Follow';
        btn.style.background = 'linear-gradient(135deg, #834ae5, #6b21a8)';
        btn.style.color = '#ffffff';
        btn.style.border = 'none';
        btn.dataset.following = '0';
    } else {
        btn.textContent = 'Following';
        btn.style.background = '#1e1e2a';
        btn.style.color = '#a1a1aa';
        btn.style.border = '1px solid #3f3f46';
        btn.dataset.following = '1';
    }
    apiPost('/follow/' + id).then(function(data) {
        if (data.error) {
            if (wasFollowing) {
                btn.textContent = 'Following'; btn.style.background = '#1e1e2a'; btn.style.color = '#a1a1aa'; btn.style.border = '1px solid #3f3f46'; btn.dataset.following = '1';
            } else {
                btn.textContent = 'Follow'; btn.style.background = 'linear-gradient(135deg, #834ae5, #6b21a8)'; btn.style.color = '#ffffff'; btn.style.border = 'none'; btn.dataset.following = '0';
            }
            showToast(data.error, true);
            return;
        }
        showToast(data.message || (data.following ? 'Following!' : 'Unfollowed'));
    }).catch(function() { showToast('Network error', true); });
}
</script>
<?php $content = ob_get_clean(); ?>
<?php require BASE_PATH . '/resources/views/layouts/app.php'; ?>
