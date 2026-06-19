<?php $activeTab = 'stream'; $title = 'Schedule Stream - DTTube'; ?>
<?php ob_start(); ?>
<div class="max-w-lg mx-auto px-3 py-4">
    <div class="flex items-center gap-3 mb-6">
        <a href="/livestream" class="w-9 h-9 rounded-full bg-surface-200 flex items-center justify-center hover:bg-surface-300 transition-colors">
            <span class="material-icons-round text-zinc-400 text-xl">chevron_left</span>
        </a>
        <h1 class="font-display text-lg font-bold text-white">Schedule Stream</h1>
    </div>

    <form id="scheduleForm" class="space-y-4" onsubmit="scheduleStream(event)">
        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Stream Title *</label>
            <input type="text" id="title" required placeholder="What's your stream about?" maxlength="100" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-brand-500/60 focus:outline-none text-sm">
        </div>

        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Description</label>
            <textarea id="description" rows="3" placeholder="Tell your audience what to expect..." maxlength="500" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-brand-500/60 focus:outline-none text-sm resize-none"></textarea>
        </div>

        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Category</label>
            <select id="category" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-brand-500/60 focus:outline-none text-sm">
                <option value="">Select category</option>
                <option value="Music">Music</option>
                <option value="Gaming">Gaming</option>
                <option value="Talk Show">Talk Show</option>
                <option value="Education">Education</option>
                <option value="Creative">Creative</option>
                <option value="Tech">Tech</option>
                <option value="Sports">Sports</option>
                <option value="Entertainment">Entertainment</option>
                <option value="News">News</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Schedule Date & Time *</label>
            <input type="datetime-local" id="scheduled_at" required class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-brand-500/60 focus:outline-none text-sm">
        </div>

        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Thumbnail URL (optional)</label>
            <input type="url" id="thumbnail" placeholder="https://..." class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-brand-500/60 focus:outline-none text-sm">
            <p class="text-zinc-600 text-[9px] mt-1">Custom thumbnail for your scheduled stream</p>
        </div>

        <div class="bg-surface-100/40 rounded-xl border border-surface-400/10 p-4">
            <h3 class="text-white text-xs font-bold mb-3">Stream Settings</h3>
            <div class="space-y-3">
                <label class="flex items-center gap-3">
                    <input type="checkbox" id="is_private" class="w-4 h-4 rounded bg-surface-200 border-surface-400/30 text-brand-500 focus:ring-brand-500/30">
                    <span class="text-zinc-300 text-xs">Private stream</span>
                </label>
                <div class="flex items-center gap-3">
                    <span class="text-zinc-400 text-[11px] w-20">Restricted to</span>
                    <select id="restricted_to" class="flex-1 bg-surface-200/80 text-white px-3 py-1.5 rounded-lg border border-surface-400/30 text-xs">
                        <option value="everyone">Everyone</option>
                        <option value="followers">Followers only</option>
                        <option value="subscribers">Subscribers only</option>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" id="submitBtn" class="w-full py-2.5 rounded-xl gradient-brand text-white text-sm font-bold hover:opacity-90 transition-all flex items-center justify-center gap-2">
            <span class="material-icons-round text-lg">event</span>
            Schedule Stream
        </button>
    </form>
</div>

<script>
function scheduleStream(e) {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="material-icons-round animate-spin text-lg">refresh</span> Scheduling...';

    const data = {
        title: document.getElementById('title').value,
        description: document.getElementById('description').value,
        category: document.getElementById('category').value || null,
        scheduled_at: document.getElementById('scheduled_at').value,
        thumbnail: document.getElementById('thumbnail').value || null,
        is_private: document.getElementById('is_private').checked,
        restricted_to: document.getElementById('restricted_to').value,
    };

    fetch('/livestream/schedule', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify(data)
    }).then(r => r.json()).then(d => {
        if (d.id) {
            alert('Stream scheduled successfully!');
            window.location.href = d.redirect_url || '/livestream/my';
        } else {
            alert(d.error || 'Error scheduling stream');
            btn.disabled = false;
            btn.innerHTML = '<span class="material-icons-round text-lg">event</span> Schedule Stream';
        }
    }).catch(() => {
        alert('Network error');
        btn.disabled = false;
        btn.innerHTML = '<span class="material-icons-round text-lg">event</span> Schedule Stream';
    });
}
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
