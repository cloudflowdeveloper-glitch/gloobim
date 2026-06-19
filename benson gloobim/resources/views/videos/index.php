<?php $activeTab = 'videos'; $title = 'Videos - Globiim'; $hideTopNav = true; $hideBottomNav = false; ?>
<?php extract($data ?? []); ?>
<?php ob_start(); ?>
<style>
    .category-tab { transition: all 0.25s ease; }
    .category-tab:hover { background: rgba(147,51,234,0.15); }
    .category-tab.active { background: linear-gradient(135deg, #9333ea, #6b21a8); color: white; box-shadow: 0 4px 15px rgba(147,51,234,0.3); }

    .featured-track { display: flex; gap: 12px; overflow-x: auto; scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; scrollbar-width: none; padding: 4px 0; }
    .featured-track::-webkit-scrollbar { display: none; }
    .featured-card { flex: 0 0 calc(100% - 48px); min-width: calc(100% - 48px); scroll-snap-align: center; }
    .featured-card:first-child { margin-left: 16px; }

    .video-card { transition: all 0.2s ease; }
    .video-card:hover { border-color: rgba(147,51,234,0.2); }

    .action-btn { transition: all 0.2s ease; }
    .action-btn:hover { transform: scale(1.15); }
    .action-btn:active { transform: scale(0.9); }

    .hover-scale { transition: transform 0.25s ease, box-shadow 0.25s ease; }
    .hover-scale:hover { transform: scale(1.03); box-shadow: 0 8px 25px rgba(0,0,0,0.4); }

    @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
    .shimmer { background: linear-gradient(90deg, transparent 25%, rgba(255,255,255,0.06) 50%, transparent 75%); background-size: 200% 100%; animation: shimmer 3s infinite; }

    .progress-bar-mini { height: 3px; border-radius: 2px; background: rgba(255,255,255,0.1); overflow: hidden; }
    .progress-bar-mini-fill { height: 100%; border-radius: 2px; background: linear-gradient(90deg, #9333ea, #c084fc); transition: width 0.3s ease; }

    .scroll-snap-x { scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; }
    .scroll-snap-x > * { scroll-snap-align: start; }
</style>

<div class="max-w-lg mx-auto pb-4">

    <!-- ===== HEADER ===== -->
    <div class="px-4 pt-3 pb-2">
        <div class="flex items-center justify-between mb-1">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center gradient-brand">
                    <span class="material-icons-round text-white text-xl">play_circle</span>
                </div>
                <div>
                    <h1 class="font-display text-2xl font-bold tracking-tight text-white">Videos</h1>
                    <p class="text-zinc-500 text-[11px]">Watch. Learn. Be Inspired.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="showToast('Search coming soon!')" class="w-10 h-10 rounded-full bg-[#14141c] flex items-center justify-center hover:bg-[#1e1e2a] transition-colors relative border border-[#1e1e2a]">
                    <span class="material-icons-round text-zinc-300 text-[20px]">search</span>
                </button>
                <a href="/videos/create" class="w-10 h-10 rounded-full bg-[#14141c] flex items-center justify-center hover:bg-[#1e1e2a] transition-colors relative border border-[#1e1e2a]">
                    <span class="material-icons-round text-zinc-300 text-[20px]">videocam</span>
                </a>
                <button onclick="showToast('No new notifications')" class="w-10 h-10 rounded-full bg-[#14141c] flex items-center justify-center hover:bg-[#1e1e2a] transition-colors relative border border-[#1e1e2a]">
                    <span class="material-icons-round text-zinc-300 text-[20px]">notifications_none</span>
                    <span class="absolute top-1.5 right-1.5 w-4 h-4 rounded-full bg-brand-600 text-white text-[8px] font-bold flex items-center justify-center">3</span>
                </button>
            </div>
        </div>

        <!-- ===== CATEGORY TABS ===== -->
        <div class="flex gap-2 overflow-x-auto scrollbar-hide pb-1 scroll-snap-x mt-3">
            <?php
            $categories = [
                ['name' => 'All', 'icon' => 'apps'],
                ['name' => 'Tech', 'icon' => 'laptop_mac'],
                ['name' => 'Gaming', 'icon' => 'sports_esports'],
                ['name' => 'Music', 'icon' => 'music_note'],
                ['name' => 'Business', 'icon' => 'business_center'],
                ['name' => 'Education', 'icon' => 'school'],
                ['name' => 'Comedy', 'icon' => 'emoji_emotions'],
                ['name' => 'Sports', 'icon' => 'sports_basketball'],
            ];
            ?>
            <?php foreach ($categories as $idx => $cat): ?>
            <button class="category-tab <?= $idx === 0 ? 'active' : '' ?> flex-shrink-0 px-4 py-1.5 rounded-full bg-[#14141c] text-zinc-400 text-xs font-medium border border-[#1e1e2a] flex items-center gap-1.5" onclick="selectCategory(this)">
                <span class="material-icons-round text-[14px]"><?= $cat['icon'] ?></span>
                <?= $cat['name'] ?>
            </button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ===== FEATURED VIDEO ===== -->
    <div class="py-3 border-t border-[#14141c]/60">
        <div class="featured-track" id="featuredTrack">
            <?php foreach ($featuredVideos as $fi => $fv): ?>
            <a href="/videos/<?= $fv['id'] ?>" class="featured-card">
                <div class="relative rounded-2xl overflow-hidden hover-scale">
                    <img src="<?= $fv['thumbnail'] ?>" alt="<?= htmlspecialchars($fv['title']) ?>" class="w-full aspect-video object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                    <div class="absolute inset-0 shimmer pointer-events-none"></div>
                    <!-- Featured badge -->
                    <div class="absolute top-3 left-3">
                        <span class="px-3 py-1 rounded-lg text-[10px] font-bold text-white uppercase tracking-wider" style="background: linear-gradient(135deg, #9333ea, #6b21a8);">Featured</span>
                    </div>
                    <!-- Duration -->
                    <div class="absolute top-3 right-3 px-2 py-0.5 rounded-lg bg-black/70 backdrop-blur-sm text-white text-[10px] font-medium">
                        <?= formatDuration($fv['duration'] ?? 0) ?>
                    </div>
                    <!-- Play button center -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-14 h-14 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30">
                            <span class="material-icons-round text-white text-3xl">play_arrow</span>
                        </div>
                    </div>
                    <!-- Info bottom -->
                    <div class="absolute bottom-0 left-0 right-0 p-4">
                        <h3 class="text-white text-base font-bold leading-tight mb-1"><?= htmlspecialchars($fv['title']) ?></h3>
                        <p class="text-zinc-300 text-[11px] leading-snug mb-2"><?= htmlspecialchars($fv['description'] ?? '') ?></p>
                        <div class="flex items-center gap-2">
                            <img src="<?= $fv['creator_avatar'] ?? 'https://placehold.co/36/36/6d28d9/ffffff?text=C' ?>" alt="" class="w-6 h-6 rounded-full border border-white/30">
                            <div class="min-w-0">
                                <div class="flex items-center gap-1">
                                    <span class="text-white text-[11px] font-semibold truncate"><?= htmlspecialchars($fv['creator_name'] ?? '') ?></span>
                                    <?php if (!empty($fv['is_verified'])): ?>
                                    <span class="material-icons-round text-brand-400 text-[12px]">verified</span>
                                    <?php endif; ?>
                                </div>
                                <span class="text-zinc-400 text-[9px]"><?= formatCount($fv['views'] ?? 0) ?> views · <?= timeAgo($fv['created_at'] ?? '') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <!-- Dots -->
        <div class="flex justify-center gap-1.5 mt-2">
            <?php for ($d = 0; $d < count($featuredVideos); $d++): ?>
            <div class="w-1.5 h-1.5 rounded-full <?= $d === 0 ? 'bg-brand-500 w-4' : 'bg-zinc-600' ?>" style="transition: all 0.3s ease;"></div>
            <?php endfor; ?>
        </div>
    </div>

    <!-- ===== CONTINUE WATCHING ===== -->
    <?php if (!empty($continueWatching)): ?>
    <div class="px-4 py-3 border-t border-[#14141c]/60">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="material-icons-round text-brand-400 text-[18px]">history</span>
                <h2 class="text-white text-sm font-bold">Continue Watching</h2>
            </div>
            <a href="#" class="text-[11px] font-semibold flex items-center gap-0.5 text-brand-400">
                See all <span class="material-icons-round text-sm">chevron_right</span>
            </a>
        </div>
        <div class="flex gap-3 overflow-x-auto scrollbar-hide scroll-snap-x">
            <?php foreach ($continueWatching as $cw): ?>
            <a href="/videos/<?= $cw['id'] ?>" class="flex-shrink-0 w-[260px] group">
                <div class="relative rounded-xl overflow-hidden mb-2 hover-scale">
                    <img src="<?= $cw['thumbnail'] ?>" alt="<?= htmlspecialchars($cw['title']) ?>" class="w-full aspect-video object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                    <div class="absolute bottom-1.5 right-1.5 px-2 py-0.5 rounded-lg bg-black/80 text-white text-[10px] font-medium">
                        <?= formatDuration($cw['duration'] ?? 0) ?>
                    </div>
                    <div class="absolute top-2 right-2">
                        <button onclick="event.preventDefault(); event.stopPropagation(); showToast('More options')" class="w-7 h-7 rounded-full bg-black/40 backdrop-blur-sm flex items-center justify-center">
                            <span class="material-icons-round text-white text-[14px]">more_vert</span>
                        </button>
                    </div>
                </div>
                <h3 class="text-white text-[13px] font-semibold line-clamp-2 leading-snug"><?= htmlspecialchars($cw['title']) ?></h3>
                <div class="flex items-center gap-1 mt-0.5">
                    <?php if (!empty($cw['is_verified'])): ?>
                    <span class="material-icons-round text-brand-400 text-[11px]">verified</span>
                    <?php endif; ?>
                    <span class="text-zinc-400 text-[11px]"><?= htmlspecialchars($cw['creator_name'] ?? '') ?></span>
                </div>
                <!-- Progress bar -->
                <div class="progress-bar-mini mt-1.5">
                    <div class="progress-bar-mini-fill" style="width: <?= $cw['progress'] ?? 40 ?>%;"></div>
                </div>
                <span class="text-brand-400 text-[9px] font-semibold mt-0.5 block"><?= $cw['progress'] ?? 40 ?>% watched</span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- ===== TRENDING VIDEOS ===== -->
    <div class="px-4 py-3 border-t border-[#14141c]/60">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="material-icons-round text-brand-400 text-[18px]">local_fire_department</span>
                <h2 class="text-white text-sm font-bold">Trending Videos</h2>
            </div>
            <a href="#" class="text-[11px] font-semibold flex items-center gap-0.5 text-brand-400">
                See all <span class="material-icons-round text-sm">chevron_right</span>
            </a>
        </div>
        <div class="space-y-3">
            <?php foreach ($trendingVideos as $ti => $tv): ?>
            <a href="/videos/<?= $tv['id'] ?>" class="flex gap-3 group">
                <div class="relative flex-shrink-0 w-[140px] rounded-xl overflow-hidden">
                    <img src="<?= $tv['thumbnail'] ?>" alt="<?= htmlspecialchars($tv['title']) ?>" class="w-full aspect-video object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute bottom-1.5 right-1.5 px-1.5 py-0.5 rounded bg-black/80 text-white text-[9px] font-medium">
                        <?= formatDuration($tv['duration'] ?? 0) ?>
                    </div>
                    <div class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded bg-brand-600 text-white text-[8px] font-bold">
                        #<?= $ti + 1 ?>
                    </div>
                </div>
                <div class="flex-1 min-w-0 py-0.5">
                    <h3 class="text-white text-[13px] font-semibold line-clamp-2 leading-snug"><?= htmlspecialchars($tv['title']) ?></h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-zinc-500 text-[11px]"><?= htmlspecialchars($tv['creator_name'] ?? '') ?></span>
                        <?php if (!empty($tv['is_verified'])): ?>
                        <span class="material-icons-round text-brand-400 text-[11px]">verified</span>
                        <?php endif; ?>
                    </div>
                    <span class="text-zinc-600 text-[10px]"><?= formatCount($tv['views'] ?? 0) ?> views · <?= timeAgo($tv['created_at'] ?? '') ?></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ===== CREATORS TO WATCH ===== -->
    <div class="px-4 py-3 border-t border-[#14141c]/60">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="material-icons-round text-brand-400 text-[18px]">group</span>
                <h2 class="text-white text-sm font-bold">Creators to Watch</h2>
            </div>
            <a href="#" class="text-[11px] font-semibold flex items-center gap-0.5 text-brand-400">
                See all <span class="material-icons-round text-sm">chevron_right</span>
            </a>
        </div>
        <div class="space-y-3">
            <?php foreach ($creatorsToWatch as $creator): ?>
            <div class="flex items-center gap-3 p-3 bg-[#14141c] rounded-xl border border-[#1e1e2a]">
                <div class="relative flex-shrink-0">
                    <img src="<?= $creator['avatar'] ?? 'https://placehold.co/44/44/6d28d9/ffffff?text=C' ?>" alt="<?= htmlspecialchars($creator['name'] ?? '') ?>" class="w-11 h-11 rounded-full object-cover border-2 border-brand-600/30">
                    <?php if (!empty($creator['is_verified'])): ?>
                    <span class="absolute -bottom-0.5 -right-0.5 material-icons-round text-brand-400 text-sm bg-[#14141c] rounded-full">verified</span>
                    <?php endif; ?>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-white text-sm font-semibold truncate"><?= htmlspecialchars($creator['name'] ?? '') ?></h3>
                    <p class="text-zinc-500 text-[11px]"><?= formatCount($creator['subscriber_count'] ?? $creator['follower_count'] ?? 0) ?> subscribers</p>
                </div>
<?php $vCreatorIsFollowing = !empty($creator['is_following']); ?>
                <button onclick="followCreator(<?= $creator['id'] ?? 0 ?>, this)" data-following="<?= $vCreatorIsFollowing ? '1' : '0' ?>" class="px-4 py-1.5 rounded-full text-white text-[11px] font-semibold flex-shrink-0 hover:opacity-90 transition-opacity" style="<?= $vCreatorIsFollowing ? 'background:#1e1e2a;color:#a1a1aa;' : 'background:linear-gradient(135deg,#9333ea,#6b21a8);color:#fff;' ?>"><?= $vCreatorIsFollowing ? 'Following' : 'Follow' ?></button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ===== NEW UPLOADS ===== -->
    <?php if (!empty($newUploads)): ?>
    <div class="px-4 py-3 border-t border-[#14141c]/60">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="material-icons-round text-brand-400 text-[18px]">new_releases</span>
                <h2 class="text-white text-sm font-bold">New Uploads</h2>
            </div>
            <a href="#" class="text-[11px] font-semibold flex items-center gap-0.5 text-brand-400">
                See all <span class="material-icons-round text-sm">chevron_right</span>
            </a>
        </div>
        <div class="space-y-3">
            <?php foreach ($newUploads as $nu): ?>
            <a href="/videos/<?= $nu['id'] ?>" class="flex gap-3 group">
                <div class="relative flex-shrink-0 w-[140px] rounded-xl overflow-hidden">
                    <img src="<?= $nu['thumbnail'] ?>" alt="<?= htmlspecialchars($nu['title']) ?>" class="w-full aspect-video object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute bottom-1.5 right-1.5 px-1.5 py-0.5 rounded bg-black/80 text-white text-[9px] font-medium">
                        <?= formatDuration($nu['duration'] ?? 0) ?>
                    </div>
                </div>
                <div class="flex-1 min-w-0 py-0.5">
                    <h3 class="text-white text-[13px] font-semibold line-clamp-2 leading-snug"><?= htmlspecialchars($nu['title']) ?></h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-zinc-500 text-[11px]"><?= htmlspecialchars($nu['creator_name'] ?? '') ?></span>
                        <?php if (!empty($nu['is_verified'])): ?>
                        <span class="material-icons-round text-brand-400 text-[11px]">verified</span>
                        <?php endif; ?>
                    </div>
                    <span class="text-zinc-600 text-[10px]"><?= formatCount($nu['views'] ?? 0) ?> views · <?= timeAgo($nu['created_at'] ?? '') ?></span>
                    <?php if (!empty($nu['description'])): ?>
                    <p class="text-zinc-500 text-[10px] mt-1 line-clamp-2"><?= htmlspecialchars($nu['description']) ?></p>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Bottom spacer -->
    <div class="h-4"></div>
</div>

<script>
function formatCount(num) {
    num = parseInt(num) || 0;
    if (num >= 1000000) return (num / 1000000).toFixed(1) + 'M';
    if (num >= 1000) return (num / 1000).toFixed(1) + 'K';
    return num.toString();
}

function formatDuration(secs) {
    secs = parseInt(secs) || 0;
    const h = Math.floor(secs / 3600);
    const m = Math.floor((secs % 3600) / 60);
    const s = secs % 60;
    if (h > 0) return h + ':' + String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
    return m + ':' + String(s).padStart(2, '0');
}

function timeAgo(dateStr) {
    if (!dateStr) return '';
    const now = new Date();
    const date = new Date(dateStr.replace(/-/g, '/'));
    const diff = Math.floor((now - date) / 1000);
    if (diff < 60) return 'just now';
    if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
    if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
    if (diff < 604800) return Math.floor(diff / 86400) + 'd ago';
    if (diff < 2592000) return Math.floor(diff / 604800) + 'w ago';
    return Math.floor(diff / 2592000) + 'mo ago';
}

function selectCategory(btn) {
    document.querySelectorAll('.category-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

function followCreator(id, btn) {
    const wasFollowing = btn.dataset.following === '1';
    if (wasFollowing) {
        btn.textContent = 'Follow';
        btn.style.background = 'linear-gradient(135deg, #9333ea, #6b21a8)';
        btn.style.color = '#ffffff';
        btn.dataset.following = '0';
    } else {
        btn.textContent = 'Following';
        btn.style.background = '#1e1e2a';
        btn.style.color = '#a1a1aa';
        btn.dataset.following = '1';
    }
    fetch('/follow/' + id, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(d => {
            if (d.error) {
                // Revert on error
                if (wasFollowing) {
                    btn.textContent = 'Following';
                    btn.style.background = '#1e1e2a';
                    btn.style.color = '#a1a1aa';
                    btn.dataset.following = '1';
                } else {
                    btn.textContent = 'Follow';
                    btn.style.background = 'linear-gradient(135deg, #9333ea, #6b21a8)';
                    btn.style.color = '#ffffff';
                    btn.dataset.following = '0';
                }
                if (d.error === 'Login required') location.href = '/login';
                else showToast(d.error, true);
                return;
            }
            showToast(d.message || (d.following ? 'Following!' : 'Unfollowed'));
        }).catch(() => {});
}

function showToast(msg) {
    const existing = document.querySelector('.toast-msg');
    if (existing) existing.remove();
    const div = document.createElement('div');
    div.className = 'toast-msg fixed top-16 left-1/2 -translate-x-1/2 px-5 py-2.5 rounded-full text-white text-sm font-medium z-[200]';
    div.style.cssText = 'background: linear-gradient(135deg, #9333ea, #6b21a8); box-shadow: 0 4px 20px rgba(147,51,234,0.4);';
    div.textContent = msg;
    document.body.appendChild(div);
    setTimeout(() => { div.style.opacity = '0'; div.style.transition = 'opacity 0.3s'; setTimeout(() => div.remove(), 300); }, 2000);
}
</script>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
