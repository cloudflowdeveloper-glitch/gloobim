<?php $activeTab = 'stream'; $title = 'My Livestreams - DTTube'; ?>
<?php
$liveStreams = $data['liveStreams'] ?? [];
$scheduledStreams = $data['scheduledStreams'] ?? [];
$endedStreams = $data['endedStreams'] ?? [];
$savedStreams = $data['savedStreams'] ?? [];
$user = \Core\Auth::user();
?>
<?php ob_start(); ?>
<div class="max-w-lg mx-auto px-3 py-4 pb-24 lg:pb-6">
    <div class="flex items-center gap-3 mb-5">
        <a href="/livestream" class="w-9 h-9 rounded-full bg-surface-200 flex items-center justify-center hover:bg-surface-300 transition-colors">
            <span class="material-icons-round text-zinc-400 text-xl">chevron_left</span>
        </a>
        <h1 class="font-display text-lg font-bold text-white">My Streams</h1>
        <a href="/livestream/start" class="ml-auto w-9 h-9 rounded-full gradient-brand flex items-center justify-center hover:opacity-90 transition-opacity">
            <span class="material-icons-round text-white text-lg">sensors</span>
        </a>
    </div>

    <div class="flex gap-2 mb-5 overflow-x-auto scrollbar-hide pb-1">
        <button onclick="switchTab('live')" id="tabLive" class="flex-shrink-0 px-3.5 py-1.5 rounded-full gradient-brand text-white text-xs font-semibold">Live</button>
        <button onclick="switchTab('scheduled')" id="tabScheduled" class="flex-shrink-0 px-3.5 py-1.5 rounded-full bg-surface-200 text-zinc-400 text-xs font-semibold hover:text-white">Scheduled</button>
        <button onclick="switchTab('ended')" id="tabEnded" class="flex-shrink-0 px-3.5 py-1.5 rounded-full bg-surface-200 text-zinc-400 text-xs font-semibold hover:text-white">Ended</button>
        <button onclick="switchTab('saved')" id="tabSaved" class="flex-shrink-0 px-3.5 py-1.5 rounded-full bg-surface-200 text-zinc-400 text-xs font-semibold hover:text-white">Saved</button>
    </div>

    <div id="sectionLive">
        <?php if (!empty($liveStreams)): ?>
        <div class="space-y-2">
            <?php foreach ($liveStreams as $s): ?>
            <a href="/livestream/<?= $s['id'] ?>" class="flex items-center gap-3 p-3 rounded-xl bg-surface-100/40 border border-surface-400/10 hover:border-red-500/30 transition-all">
                <div class="w-14 h-14 rounded-xl bg-surface-200 overflow-hidden relative flex-shrink-0">
                    <img src="<?= $s['thumbnail'] ?? 'https://picsum.photos/id/30/56/56' ?>" alt="" class="w-full h-full object-cover">
                    <span class="absolute top-1 left-1 px-1.5 py-0.5 rounded bg-red-500 text-white text-[7px] font-bold">LIVE</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-xs font-semibold truncate"><?= htmlspecialchars($s['title'] ?? '') ?></p>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="flex items-center gap-1 text-red-400 text-[10px]"><span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span><?= (int)($s['viewers'] ?? 0) ?> viewers</span>
                        <span class="text-zinc-600">·</span>
                        <span class="text-zinc-500 text-[10px]"><?= isset($s['started_at']) ? timeAgo($s['started_at']) : '' ?></span>
                    </div>
                </div>
                <span class="material-icons-round text-zinc-500">chevron_right</span>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-10 bg-surface-100/30 rounded-2xl border border-surface-400/10">
            <span class="material-icons-round text-zinc-500 text-4xl">sensors</span>
            <h3 class="text-white font-semibold text-sm mt-2">No live streams</h3>
            <p class="text-zinc-500 text-xs mt-1">Start a new live stream</p>
            <a href="/livestream/start" class="inline-flex items-center gap-1.5 mt-3 gradient-brand px-4 py-2 rounded-full text-white text-xs font-semibold">Go Live</a>
        </div>
        <?php endif; ?>
    </div>

    <div id="sectionScheduled" class="hidden">
        <?php if (!empty($scheduledStreams)): ?>
        <div class="space-y-2">
            <?php foreach ($scheduledStreams as $s): ?>
            <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-100/40 border border-surface-400/10">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-brand-500/30 to-blue-500/30 flex items-center justify-center flex-shrink-0">
                    <span class="material-icons-round text-brand-400 text-xl">schedule</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-xs font-semibold truncate"><?= htmlspecialchars($s['title'] ?? '') ?></p>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="text-brand-400 text-[10px]"><?= isset($s['started_at']) ? date('M d, Y g:i A', strtotime($s['started_at'])) : '' ?></span>
                        <?php if (!empty($s['category'])): ?><span class="text-zinc-600">·</span><span class="text-zinc-500 text-[10px]"><?= htmlspecialchars($s['category']) ?></span><?php endif; ?>
                    </div>
                </div>
                <button onclick="cancelStream(<?= $s['id'] ?>)" class="px-2.5 py-1.5 rounded-lg bg-red-500/20 text-red-400 text-[10px] font-semibold hover:bg-red-500/30 transition-colors">Cancel</button>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-10 bg-surface-100/30 rounded-2xl border border-surface-400/10">
            <span class="material-icons-round text-zinc-500 text-4xl">calendar_today</span>
            <h3 class="text-white font-semibold text-sm mt-2">No scheduled streams</h3>
            <p class="text-zinc-500 text-xs mt-1">Schedule your next live stream</p>
            <a href="/livestream/schedule" class="inline-flex items-center gap-1.5 mt-3 gradient-brand px-4 py-2 rounded-full text-white text-xs font-semibold">Schedule</a>
        </div>
        <?php endif; ?>
    </div>

    <div id="sectionEnded" class="hidden">
        <?php if (!empty($endedStreams)): ?>
        <div class="space-y-2">
            <?php foreach ($endedStreams as $s): ?>
            <a href="/livestream/<?= $s['id'] ?>" class="flex items-center gap-3 p-3 rounded-xl bg-surface-100/40 border border-surface-400/10 hover:border-surface-400/30 transition-all">
                <div class="w-14 h-14 rounded-xl bg-surface-200 overflow-hidden flex-shrink-0">
                    <img src="<?= $s['thumbnail'] ?? '/uploads/profiles/admin.jpg' ?>" alt="" class="w-full h-full object-cover">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-xs font-semibold truncate"><?= htmlspecialchars($s['title'] ?? '') ?></p>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="text-zinc-500 text-[10px]"><?= isset($s['ended_at']) ? timeAgo($s['ended_at']) : '' ?></span>
                        <span class="text-zinc-600">·</span>
                        <span class="text-zinc-500 text-[10px]"><?= (int)($s['total_likes'] ?? 0) ?> likes</span>
                        <span class="text-zinc-600">·</span>
                        <span class="text-zinc-500 text-[10px]"><?= (int)($s['peak_viewers'] ?? 0) ?> peak</span>
                    </div>
                </div>
                <span class="material-icons-round text-zinc-500">chevron_right</span>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-10 bg-surface-100/30 rounded-2xl border border-surface-400/10">
            <span class="material-icons-round text-zinc-500 text-4xl">history</span>
            <h3 class="text-white font-semibold text-sm mt-2">No past streams yet</h3>
            <p class="text-zinc-500 text-xs mt-1">Your ended streams will appear here</p>
        </div>
        <?php endif; ?>
    </div>

    <div id="sectionSaved" class="hidden">
        <?php if (!empty($savedStreams)): ?>
        <div class="space-y-2">
            <?php foreach ($savedStreams as $s): ?>
            <a href="/livestream/<?= $s['stream_id'] ?>" class="flex items-center gap-3 p-3 rounded-xl bg-surface-100/40 border border-surface-400/10 hover:border-amber-500/30 transition-all">
                <div class="w-14 h-14 rounded-xl bg-surface-200 overflow-hidden flex-shrink-0">
                    <img src="<?= $s['thumbnail'] ?? 'https://picsum.photos/id/32/56/56' ?>" alt="" class="w-full h-full object-cover">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-xs font-semibold truncate"><?= htmlspecialchars($s['title'] ?? '') ?></p>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="text-zinc-500 text-[10px]">@<?= htmlspecialchars($s['username'] ?? '') ?></span>
                        <?php if (!empty($s['is_verified'])): ?><span class="material-icons-round text-brand-400 text-[10px]">verified</span><?php endif; ?>
                        <span class="text-zinc-600">·</span>
                        <span class="text-zinc-500 text-[10px]"><?= $s['status'] === 'live' ? '<span class="text-red-400">LIVE</span>' : ($s['status'] === 'scheduled' ? 'Scheduled' : 'Ended') ?></span>
                    </div>
                </div>
                <span class="material-icons-round text-zinc-500">chevron_right</span>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-10 bg-surface-100/30 rounded-2xl border border-surface-400/10">
            <span class="material-icons-round text-zinc-500 text-4xl">bookmark</span>
            <h3 class="text-white font-semibold text-sm mt-2">No saved streams</h3>
            <p class="text-zinc-500 text-xs mt-1">Save streams you want to watch later</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function switchTab(tab) {
    ['Live', 'Scheduled', 'Ended', 'Saved'].forEach(t => {
        const id = 'tab' + t;
        const section = 'section' + t;
        document.getElementById(id).className = 'flex-shrink-0 px-3.5 py-1.5 rounded-full bg-surface-200 text-zinc-400 text-xs font-semibold hover:text-white';
        document.getElementById(section).classList.add('hidden');
    });
    const tabNames = { live: 'Live', scheduled: 'Scheduled', ended: 'Ended', saved: 'Saved' };
    const activeTab = tabNames[tab] || 'Live';
    document.getElementById('tab' + activeTab).className = 'flex-shrink-0 px-3.5 py-1.5 rounded-full gradient-brand text-white text-xs font-semibold';
    document.getElementById('section' + activeTab).classList.remove('hidden');
}

function cancelStream(id) {
    if (!confirm('Cancel this scheduled stream?')) return;
    fetch('/livestream/' + id, { method: 'DELETE', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(d => { location.reload(); })
        .catch(() => alert('Error cancelling stream'));
}
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
