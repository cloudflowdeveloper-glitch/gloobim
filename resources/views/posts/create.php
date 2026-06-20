<?php $hideTopNav = true; $activeTab = 'post'; $title = 'Create - GLOOBIM'; ?>
<?php ob_start(); ?>
<style>
    @keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
    .float-anim { animation: float 3s ease-in-out infinite; }
    @keyframes glow-pulse { 0%,100% { box-shadow: 0 0 15px rgba(168,85,247,0.3), 0 0 30px rgba(168,85,247,0.1); } 50% { box-shadow: 0 0 30px rgba(168,85,247,0.6), 0 0 60px rgba(168,85,247,0.2); } }
    .glow-pulse { animation: glow-pulse 2s ease-in-out infinite; }
    @keyframes shimmer { 0% { background-position: -200% center; } 100% { background-position: 200% center; } }
    .shimmer { background: linear-gradient(90deg, rgba(168,85,247,0.1), rgba(236,72,153,0.2), rgba(168,85,247,0.1)); background-size: 200% 100%; animation: shimmer 3s ease-in-out infinite; }
    .content-card { transition: all 0.3s cubic-bezier(0.4,0,0.2,1); }
    .content-card:hover { transform: translateY(-4px) scale(1.02); }
    .scroll-snap-x { scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; }
    .scroll-snap-x > * { scroll-snap-align: start; }
    .ai-glow { box-shadow: 0 0 40px rgba(168,85,247,0.2), inset 0 0 30px rgba(168,85,247,0.05); }
    .draft-card { transition: all 0.3s ease; }
    .draft-card:hover { transform: scale(1.03); }
    .grid-item { transition: all 0.25s ease; }
    .grid-item:hover { background: rgba(168,85,247,0.1); border-color: rgba(168,85,247,0.3); transform: translateY(-2px); }
    .badge-neon { background: linear-gradient(135deg, rgba(168,85,247,0.3), rgba(236,72,153,0.3)); border: 1px solid rgba(168,85,247,0.3); }
</style>

<div class="max-w-lg mx-auto pb-4">
    <div class="px-3 pt-2 pb-1">
        <div class="flex items-center justify-between mb-1">
            <a href="/" class="w-9 h-9 rounded-full bg-surface-200/80 flex items-center justify-center hover:bg-surface-300 transition-colors">
                <span class="material-icons-round text-zinc-400 text-xl">close</span>
            </a>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-brand-500/60">
                    <img src="/uploads/profiles/admin.jpg" alt="Profile" class="w-full h-full object-cover">
                </div>
                <div class="relative">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-400 to-pink-500 flex items-center justify-center glow-pulse">
                        <span class="material-icons-round text-white text-lg">auto_awesome</span>
                    </div>
                    <span class="absolute -top-1 -right-1 w-3 h-3 bg-green-400 rounded-full border-2 border-surface-50"></span>
                </div>
            </div>
        </div>
        <h1 class="font-display text-2xl font-bold text-white mt-3">Create</h1>
        <p class="text-zinc-500 text-sm mt-0.5">What do you want to create today?</p>
    </div>

    <div class="flex gap-2.5 overflow-x-auto scrollbar-hide px-3 py-3 scroll-snap-x">
        <div class="flex-shrink-0 w-32 bg-surface-100/80 rounded-xl border border-surface-400/20 p-3">
            <div class="flex items-center gap-1.5 mb-1.5">
                <span class="w-2 h-2 rounded-full bg-green-400"></span>
                <span class="text-green-400 text-[9px] font-semibold">Active</span>
            </div>
            <span class="text-zinc-400 text-[10px]">Data Saver</span>
            <span class="text-white text-xs font-bold block">70% saved</span>
        </div>
        <div class="flex-shrink-0 w-32 bg-surface-100/80 rounded-xl border border-surface-400/20 p-3">
            <div class="flex items-center gap-1.5 mb-1.5">
                <span class="material-icons-round text-brand-400 text-[14px]">cloud_upload</span>
                <span class="text-brand-400 text-[9px] font-semibold">Estimate</span>
            </div>
            <span class="text-zinc-400 text-[10px]">Upload time</span>
            <span class="text-white text-xs font-bold block">~2 min</span>
        </div>
        <div class="flex-shrink-0 w-36 bg-surface-100/80 rounded-xl border border-surface-400/20 p-3">
            <div class="flex items-center gap-1.5 mb-1.5">
                <span class="material-icons-round text-pink-400 text-[14px]">trending_up</span>
                <span class="text-pink-400 text-[9px] font-semibold">Trending</span>
            </div>
            <span class="text-zinc-400 text-[10px]">#Hashtag</span>
            <span class="text-white text-xs font-bold block truncate">#DTTubeCreator</span>
        </div>
    </div>

    <div class="px-3 py-1">
        <h2 class="text-white text-sm font-bold mb-3">Popular Content</h2>
        <div class="grid grid-cols-2 gap-3">
            <a href="/reels/create" class="content-card bg-gradient-to-br from-purple-600/30 to-purple-900/30 rounded-2xl border border-purple-500/20 p-4 cursor-pointer group">
                <div class="w-10 h-10 rounded-xl bg-purple-500/30 flex items-center justify-center mb-3">
                    <span class="material-icons-round text-purple-300 text-xl">smart_display</span>
                </div>
                <span class="text-white text-sm font-bold block">Reel / Short</span>
                <span class="text-zinc-400 text-[10px] mt-0.5 block leading-relaxed">Short-form vertical video with music & effects</span>
                <div class="mt-2 inline-flex items-center gap-1 badge-neon rounded-full px-2 py-0.5">
                    <span class="text-[9px] text-brand-300 font-medium">15-60s</span>
                </div>
            </a>
            <a href="/posts/create/video" class="content-card bg-gradient-to-br from-blue-600/30 to-blue-900/30 rounded-2xl border border-blue-500/20 p-4 cursor-pointer group">
                <div class="w-10 h-10 rounded-xl bg-blue-500/30 flex items-center justify-center mb-3">
                    <span class="material-icons-round text-blue-300 text-xl">videocam</span>
                </div>
                <span class="text-white text-sm font-bold block">Video</span>
                <span class="text-zinc-400 text-[10px] mt-0.5 block leading-relaxed">Long-form HD video content with chapters</span>
                <div class="mt-2 inline-flex items-center gap-1 badge-neon rounded-full px-2 py-0.5">
                    <span class="text-[9px] text-brand-300 font-medium">Up to 4K</span>
                </div>
            </a>
            <a href="/livestream/start" class="content-card bg-gradient-to-br from-red-600/30 to-red-900/30 rounded-2xl border border-red-500/20 p-4 cursor-pointer group">
                <div class="w-10 h-10 rounded-xl bg-red-500/30 flex items-center justify-center mb-3">
                    <span class="material-icons-round text-red-300 text-xl">sensors</span>
                </div>
                <span class="text-white text-sm font-bold block">Go Live</span>
                <span class="text-zinc-400 text-[10px] mt-0.5 block leading-relaxed">Real-time streaming with chat & gifts</span>
                <div class="mt-2 inline-flex items-center gap-1 bg-red-500/30 rounded-full px-2 py-0.5 border border-red-500/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                    <span class="text-[9px] text-red-300 font-medium">Live</span>
                </div>
            </a>
            <a href="/posts/create/photo" class="content-card bg-gradient-to-br from-emerald-600/30 to-emerald-900/30 rounded-2xl border border-emerald-500/20 p-4 cursor-pointer group">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/30 flex items-center justify-center mb-3">
                    <span class="material-icons-round text-emerald-300 text-xl">photo_camera</span>
                </div>
                <span class="text-white text-sm font-bold block">Photo</span>
                <span class="text-zinc-400 text-[10px] mt-0.5 block leading-relaxed">High-res images with filters & editing</span>
                <div class="mt-2 inline-flex items-center gap-1 badge-neon rounded-full px-2 py-0.5">
                    <span class="text-[9px] text-brand-300 font-medium">4K + RAW</span>
                </div>
            </a>
        </div>
    </div>

    <div class="px-3 py-3">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-white text-sm font-bold">More Ways to Create</h2>
            <a href="/feed" class="text-brand-400 text-[10px] font-semibold no-underline">View all</a>
        </div>
        <div class="grid grid-cols-4 gap-2.5">
            <?php
            $moreItems = [
                ['Podcast', 'mic', 'from-orange-500/30 to-orange-600/20', 'text-orange-300', '/livestream/start'],
                ['Audio Room', 'headset_mic', 'from-pink-500/30 to-pink-600/20', 'text-pink-300', '/livestream/start'],
                ['Music Upload', 'music_note', 'from-purple-500/30 to-purple-600/20', 'text-purple-300', '/music/upload'],
                ['Live Shopping', 'shopping_bag', 'from-cyan-500/30 to-cyan-600/20', 'text-cyan-300', '/livestream/start'],
                ['Product Sell', 'sell', 'from-yellow-500/30 to-yellow-600/20', 'text-yellow-300', '/marketplace/create'],
                ['Course', 'school', 'from-blue-500/30 to-blue-600/20', 'text-blue-300', '/market/create'],
                ['Event', 'event', 'from-rose-500/30 to-rose-600/20', 'text-rose-300', '/livestream/schedule'],
                ['Community', 'groups', 'from-teal-500/30 to-teal-600/20', 'text-teal-300', '/feed'],
                ['New Post', 'article', 'from-zinc-500/30 to-zinc-600/20', 'text-zinc-300', '/feed'],
                ['Poll', 'poll', 'from-amber-500/30 to-amber-600/20', 'text-amber-300', '#'],
                ['Docs', 'description', 'from-violet-500/30 to-violet-600/20', 'text-violet-300', '/market/create'],
                ['AI Tools', 'auto_awesome', 'from-fuchsia-500/30 to-fuchsia-600/20', 'text-fuchsia-300', '#'],
            ];
            foreach ($moreItems as $item):
            ?>
            <a href="<?= $item[4] ?>" class="grid-item flex flex-col items-center gap-1.5 p-3 rounded-xl bg-surface-100/60 border border-surface-400/15 cursor-pointer no-underline" <?= $item[4] === '#' ? 'onclick="event.preventDefault();'.($item[0]==='Poll'?'openPollModal()':'openAISuite()').'"' : '' ?>>
                <div class="w-9 h-9 rounded-xl <?= $item[2] ?> flex items-center justify-center">
                    <span class="material-icons-round <?= $item[3] ?> text-xl"><?= $item[1] ?></span>
                </div>
                <span class="text-zinc-300 text-[8px] font-medium text-center leading-tight"><?= $item[0] ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="px-3 py-2">
        <div class="relative rounded-2xl overflow-hidden ai-glow bg-gradient-to-br from-purple-900/60 via-brand-900/40 to-pink-900/60 border border-brand-500/20">
            <div class="shimmer absolute inset-0 pointer-events-none"></div>
            <div class="relative p-5 flex items-center gap-4">
                <div class="flex-shrink-0">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-brand-400 to-pink-500 flex items-center justify-center float-anim shadow-xl shadow-brand-500/30">
                        <span class="material-icons-round text-white text-4xl">auto_awesome</span>
                    </div>
                </div>
                <div class="flex-1">
                    <span class="text-[10px] font-semibold text-brand-300 uppercase tracking-wider">AI Creator Suite</span>
                    <h3 class="font-display text-base font-bold text-white mt-0.5">Create with AI</h3>
                    <p class="text-zinc-400 text-[11px] mt-0.5 leading-relaxed">Generate scripts, thumbnails, captions & more using advanced AI</p>
                    <button onclick="openAISuite()" class="mt-3 px-5 py-2 rounded-full gradient-brand text-white text-xs font-bold shadow-lg shadow-brand-500/30 hover:opacity-90 transition-opacity glow-pulse">
                        <span class="flex items-center gap-1.5">
                            <span class="material-icons-round text-[16px]">bolt</span>
                            Try AI Suite
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="px-3 py-2">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-white text-sm font-bold">Drafts</h2>
            <a href="/feed" class="text-brand-400 text-[10px] font-semibold no-underline">See all</a>
        </div>
        <div class="flex gap-3 overflow-x-auto scrollbar-hide pb-1 scroll-snap-x">
            <div class="flex-shrink-0 w-28">
                <a href="/feed" class="aspect-[3/4] rounded-xl border-2 border-dashed border-surface-400/30 flex flex-col items-center justify-center cursor-pointer hover:border-brand-500/50 transition-colors bg-surface-100/40 no-underline block">
                    <span class="material-icons-round text-zinc-500 text-3xl">add</span>
                    <span class="text-zinc-500 text-[10px] font-medium mt-1">New Draft</span>
                </a>
            </div>
            <?php
            $drafts = [
                ['thumb' => '/uploads/profiles/admin.jpg', 'title' => 'Weekly Vlog', 'duration' => '12:34', 'time' => '2h ago'],
                ['thumb' => '/uploads/profiles/admin.jpg', 'title' => 'Tutorial', 'duration' => '28:15', 'time' => '5h ago'],
                ['thumb' => '/uploads/profiles/admin.jpg', 'title' => 'Music Video', 'duration' => '4:20', 'time' => '1d ago'],
            ];
            foreach ($drafts as $draft):
            ?>
            <a href="/feed" class="flex-shrink-0 w-28 draft-card cursor-pointer group no-underline block">
                <div class="relative rounded-xl overflow-hidden mb-1.5 aspect-[3/4]">
                    <img src="<?= $draft['thumb'] ?>" alt="<?= $draft['title'] ?>" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors"></div>
                    <div class="absolute bottom-1.5 left-1.5 bg-black/70 backdrop-blur-sm rounded-md px-1.5 py-0.5">
                        <span class="text-white text-[9px] font-medium"><?= $draft['duration'] ?></span>
                    </div>
                    <div class="absolute top-1.5 right-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button class="w-7 h-7 rounded-full bg-black/60 flex items-center justify-center hover:bg-black/80 transition-colors">
                            <span class="material-icons-round text-white text-sm">more_vert</span>
                        </button>
                    </div>
                </div>
                <span class="text-white text-[10px] font-semibold truncate block"><?= $draft['title'] ?></span>
                <span class="text-zinc-500 text-[8px]">Updated <?= $draft['time'] ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.content-card').forEach(card => {
    card.addEventListener('click', function(e) {
        if (e.target.closest('a')) return;
        const link = this.closest('a');
        if (link) window.location.href = link.href;
    });
});

// ── Poll Modal ──────────────────────────────────────────
function openPollModal() {
    const html = `
    <div id="modalOverlay" class="fixed inset-0 bg-black/70 z-50 flex items-end sm:items-center justify-center" onclick="if(event.target===this)closeModal()">
    <div class="bg-[#14141c] w-full max-w-lg rounded-t-3xl sm:rounded-3xl p-5 pb-8 animate-[slideUp_0.3s_ease] border border-[#1e1e2a] max-h-[90vh] overflow-y-auto" style="animation: slideUp 0.3s ease;">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-white text-lg font-bold">📊 Create Poll</h2>
            <button onclick="closeModal()" class="w-8 h-8 rounded-full bg-[#1e1e2a] flex items-center justify-center"><span class="material-icons-round text-zinc-400 text-lg">close</span></button>
        </div>
        <div class="space-y-4">
            <div>
                <label class="text-zinc-400 text-xs font-semibold uppercase tracking-wider mb-1.5 block">Question</label>
                <input type="text" id="pollQuestion" placeholder="Ask something..." class="w-full bg-[#1e1e2a] text-white px-4 py-2.5 rounded-xl border border-[#2d2d32] focus:border-[#834ae5] focus:outline-none text-sm placeholder:text-zinc-600">
            </div>
            <div id="pollOptions">
                <label class="text-zinc-400 text-xs font-semibold uppercase tracking-wider mb-1.5 block">Options</label>
                <div class="space-y-2">
                    <input type="text" class="poll-opt w-full bg-[#1e1e2a] text-white px-4 py-2 rounded-xl border border-[#2d2d32] focus:border-[#834ae5] focus:outline-none text-sm" placeholder="Option 1">
                    <input type="text" class="poll-opt w-full bg-[#1e1e2a] text-white px-4 py-2 rounded-xl border border-[#2d2d32] focus:border-[#834ae5] focus:outline-none text-sm" placeholder="Option 2">
                    <input type="text" class="poll-opt w-full bg-[#1e1e2a] text-white px-4 py-2 rounded-xl border border-[#2d2d32] focus:border-[#834ae5] focus:outline-none text-sm" placeholder="Option 3">
                </div>
            </div>
            <button onclick="addPollOption()" class="text-[#834ae5] text-xs font-semibold flex items-center gap-1"><span class="material-icons-round text-sm">add</span> Add option</button>
            <div class="flex items-center justify-between bg-[#1e1e2a] rounded-xl p-3">
                <span class="text-white text-xs">Duration</span>
                <select class="bg-[#14141c] text-white text-xs px-3 py-1.5 rounded-lg border border-[#2d2d32]">
                    <option>1 day</option><option>3 days</option><option>7 days</option>
                </select>
            </div>
            <button onclick="submitPoll()" class="w-full py-3 rounded-xl text-white font-bold text-sm" style="background:linear-gradient(135deg,#834ae5,#6b21a8);">🎯 Post Poll</button>
        </div>
    </div></div>`;
    document.body.insertAdjacentHTML('beforeend', html);
}
function addPollOption() {
    const div = document.getElementById('pollOptions');
    const input = document.createElement('input');
    input.type = 'text';
    input.className = 'poll-opt w-full bg-[#1e1e2a] text-white px-4 py-2 rounded-xl border border-[#2d2d32] focus:border-[#834ae5] focus:outline-none text-sm mt-2';
    input.placeholder = 'Option ' + (div.querySelectorAll('.poll-opt').length + 1);
    div.appendChild(input);
}
function submitPoll() {
    const q = document.getElementById('pollQuestion').value.trim();
    if (!q) { alert('Enter a question'); return; }
    const opts = Array.from(document.querySelectorAll('.poll-opt')).map(i=>i.value.trim()).filter(v=>v);
    if (opts.length < 2) { alert('Need at least 2 options'); return; }
    alert('✅ Poll created: "' + q + '" with ' + opts.length + ' options');
    closeModal();
}

// ── AI Creator Suite Modal ──────────────────────────────
let aiTab = 'captions';

function openAISuite() {
    const html = `
    <div id="modalOverlay" class="fixed inset-0 bg-black/70 z-50 flex items-end sm:items-center justify-center" onclick="if(event.target===this)closeModal()">
    <div class="bg-[#14141c] w-full max-w-lg rounded-t-3xl sm:rounded-3xl p-5 pb-8 animate-[slideUp_0.3s_ease] border border-[#834ae5]/20 max-h-[90vh] overflow-y-auto" style="box-shadow: 0 0 60px rgba(131,74,229,0.2); animation: slideUp 0.3s ease;">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:linear-gradient(135deg,#834ae5,#ec4899);"><span class="material-icons-round text-white text-sm">auto_awesome</span></div>
                <h2 class="text-white text-lg font-bold">AI Creator Suite</h2>
            </div>
            <button onclick="closeModal()" class="w-8 h-8 rounded-full bg-[#1e1e2a] flex items-center justify-center"><span class="material-icons-round text-zinc-400 text-lg">close</span></button>
        </div>
        <!-- Tabs -->
        <div class="flex gap-1 mb-4 bg-[#1e1e2a] rounded-xl p-1">
            <button onclick="switchAITab('captions',this)" class="ai-tab flex-1 py-2 rounded-lg text-xs font-semibold transition-all bg-[#834ae5] text-white">📝 Captions</button>
            <button onclick="switchAITab('hashtags',this)" class="ai-tab flex-1 py-2 rounded-lg text-xs font-semibold transition-all text-zinc-400">🏷️ Hashtags</button>
            <button onclick="switchAITab('ideas',this)" class="ai-tab flex-1 py-2 rounded-lg text-xs font-semibold transition-all text-zinc-400">💡 Ideas</button>
            <button onclick="switchAITab('scripts',this)" class="ai-tab flex-1 py-2 rounded-lg text-xs font-semibold transition-all text-zinc-400">🎬 Scripts</button>
        </div>
        <!-- Content Area -->
        <div id="aiContent" class="space-y-3">
            ${getCaptionsTab()}
        </div>
    </div></div>`;
    document.body.insertAdjacentHTML('beforeend', html);
}

function switchAITab(tab, btn) {
    aiTab = tab;
    document.querySelectorAll('.ai-tab').forEach(b => { b.classList.remove('bg-[#834ae5]','text-white'); b.classList.add('text-zinc-400'); });
    btn.classList.add('bg-[#834ae5]','text-white'); btn.classList.remove('text-zinc-400');
    const content = document.getElementById('aiContent');
    if (tab === 'captions') content.innerHTML = getCaptionsTab();
    else if (tab === 'hashtags') content.innerHTML = getHashtagsTab();
    else if (tab === 'ideas') content.innerHTML = getIdeasTab();
    else if (tab === 'scripts') content.innerHTML = getScriptsTab();
}

function getCaptionsTab() {
    return '<div class="bg-[#1e1e2a] rounded-xl p-4"><textarea id="captionTopic" placeholder="What is your content about? e.g. Nairobi street food tour..." class="w-full bg-[#14141c] text-white px-4 py-3 rounded-xl border border-[#2d2d32] text-sm placeholder:text-zinc-600 resize-none" rows="3"></textarea><button onclick="generateCaptions()" class="mt-3 px-5 py-2 rounded-full text-white text-xs font-bold" style="background:linear-gradient(135deg,#834ae5,#ec4899);">✨ Generate Captions</button><div id="captionResults" class="mt-3 space-y-2"></div></div>';
}
function getHashtagsTab() {
    return '<div class="bg-[#1e1e2a] rounded-xl p-4"><textarea id="hashtagTopic" placeholder="Describe your post... e.g. African music festival highlights" class="w-full bg-[#14141c] text-white px-4 py-3 rounded-xl border border-[#2d2d32] text-sm placeholder:text-zinc-600 resize-none" rows="2"></textarea><button onclick="generateHashtags()" class="mt-3 px-5 py-2 rounded-full text-white text-xs font-bold" style="background:linear-gradient(135deg,#834ae5,#ec4899);">🏷️ Generate Hashtags</button><div id="hashtagResults" class="mt-3 flex flex-wrap gap-2"></div></div>';
}
function getIdeasTab() {
    return '<div class="bg-[#1e1e2a] rounded-xl p-4"><select id="ideaCategory" class="w-full bg-[#14141c] text-white px-4 py-3 rounded-xl border border-[#2d2d32] text-sm mb-3"><option>🎵 Music</option><option>😂 Comedy</option><option>🍳 Food</option><option>💻 Tech</option><option>⚽ Sports</option><option>💄 Beauty</option><option>✈️ Travel</option><option>📚 Education</option></select><button onclick="generateIdeas()" class="px-5 py-2 rounded-full text-white text-xs font-bold" style="background:linear-gradient(135deg,#834ae5,#ec4899);">💡 Get Content Ideas</button><div id="ideaResults" class="mt-3 space-y-2"></div></div>';
}
function getScriptsTab() {
    return '<div class="bg-[#1e1e2a] rounded-xl p-4"><select id="scriptType" class="w-full bg-[#14141c] text-white px-4 py-3 rounded-xl border border-[#2d2d32] text-sm mb-3"><option>🎬 Reel / Short</option><option>📹 YouTube Video</option><option>🎙️ Podcast Intro</option><option>📱 TikTok</option><option>🎤 Live Stream</option></select><button onclick="generateScript()" class="px-5 py-2 rounded-full text-white text-xs font-bold" style="background:linear-gradient(135deg,#834ae5,#ec4899);">🎬 Generate Script</button><div id="scriptResults" class="mt-3 p-3 bg-[#14141c] rounded-xl text-white text-xs leading-relaxed whitespace-pre-wrap hidden"></div></div>';
}

// ── AI Generators ───────────────────────────────────────
function generateCaptions() {
    const topic = document.getElementById('captionTopic').value.trim();
    if (!topic) { alert('Describe your content first!'); return; }
    const captions = [
        '🔥 ' + topic + ' — you won\'t believe what happened next!',
        '✨ New ' + topic + ' content just dropped! Check it out 👀',
        'POV: ' + topic + ' hits different at this time of day 🌅',
        'Rate this ' + topic + ' from 1-10 in the comments! 👇',
        'Behind the scenes of ' + topic + ' 🎬 #CreatorLife',
    ];
    document.getElementById('captionResults').innerHTML = captions.map((c,i) => 
        '<div class="flex items-start gap-2 p-3 bg-[#14141c] rounded-xl cursor-pointer hover:bg-[#1e1e2a] transition-colors" onclick="copyText(this,\''+c.replace(/'/g,"\\'")+'\')">'+
        '<span class="text-[#834ae5] text-xs font-bold mt-0.5">'+(i+1)+'.</span>'+
        '<span class="text-white text-xs flex-1">'+c+'</span>'+
        '<span class="material-icons-round text-zinc-600 text-sm">content_copy</span></div>'
    ).join('');
}

function generateHashtags() {
    const topic = document.getElementById('hashtagTopic').value.trim();
    if (!topic) { alert('Describe your post first!'); return; }
    const tags = ['#DTTube', '#CreatorLife', '#ViralContent', '#AfricanCreators', '#TrendingNow', '#ContentCreator', '#ExplorePage', '#FYP'];
    document.getElementById('hashtagResults').innerHTML = tags.map(t => 
        '<span class="px-3 py-1.5 rounded-full text-xs font-medium cursor-pointer hover:opacity-80 transition-opacity" style="background:rgba(131,74,229,0.15);color:#c084fc;" onclick="copyText(this,\''+t+'\')">'+t+'</span>'
    ).join('');
}

function generateIdeas() {
    const cat = document.getElementById('ideaCategory').value.replace(/[🎵😂🍳💻⚽💄✈️📚]/g,'').trim();
    const ideas = [
        'Top 5 ' + cat + ' trends everyone is talking about right now 📊',
        'My honest review: ' + cat + ' products I actually use daily ⭐',
        cat + ' myths DEBUNKED — what they don\'t tell you 🤯',
        'A day in the life of a ' + cat + ' creator 🎬',
        'Beginner\'s guide to ' + cat + ' — start here! 📚',
    ];
    document.getElementById('ideaResults').innerHTML = ideas.map((idea,i) => 
        '<div class="flex items-start gap-2 p-3 bg-[#14141c] rounded-xl cursor-pointer hover:bg-[#1e1e2a] transition-colors" onclick="copyText(this,\''+idea.replace(/'/g,"\\'")+'\')">'+
        '<span class="text-amber-400 text-xs">💡</span>'+
        '<span class="text-white text-xs flex-1">'+idea+'</span></div>'
    ).join('');
}

function generateScript() {
    const type = document.getElementById('scriptType').value.replace(/[🎬📹🎙️📱🎤]/g,'').trim();
    const scripts = {
        'Reel / Short': "HOOK (0-3s): 🎬 Shocking fact or question\n\nBODY (3-20s): Main content — keep it fast! Show, don't tell.\n\nCTA (20-25s): Smash that like + follow for more!\n\n🎵 Add trending audio for extra reach",
        'YouTube Video': "INTRO (0-30s): Today we're talking about [TOPIC]\n\nMAIN (30s-8min): Break into 3 key points:\n1. Point one with example\n2. Point two with story\n3. Point three with data\n\nOUTRO: Subscribe + comment your thoughts below!\n\n⏱️ Keep chapters tight — viewers drop off after 2min",
        'Podcast Intro': "🎙️ [HOOK]: 'Have you ever wondered why...'\n\nWelcome back to [SHOW NAME]!\nI'm your host [NAME] and today we're diving into:\n[TOPIC]\n\nBut first — a quick word from our sponsor...\n\nLet's get into it! 🎧",
        'TikTok': "TEXT ON SCREEN: [HOOK in bold]\n\n0-3s: Eye-catching visual + text\n3-15s: Quick transformation / reaction\n15-20s: Punchline or reveal\n\nCAPTION: Short and punchy\n🎵 Use trending sound",
        'Live Stream': "WELCOME: Hey fam! Welcome to the stream! 👋\n\nWHAT WE'RE DOING: Today we're [TOPIC]\n\nENGAGE: Drop a 🔥 in chat if you're excited!\nShoutout to [VIEWER NAME] for joining!\n\nMID-STREAM: Q&A session — ask me anything!\n\nWRAP-UP: Thanks for hanging out — same time tomorrow? 👀",
    };
    const result = scripts[type] || scripts['Reel / Short'];
    const el = document.getElementById('scriptResults');
    el.textContent = result;
    el.classList.remove('hidden');
}

// ── Helpers ─────────────────────────────────────────────
function copyText(el, text) {
    navigator.clipboard.writeText(text).then(() => {
        const icon = el.querySelector('.material-icons-round');
        if (icon) { icon.textContent = 'check'; icon.style.color = '#22c55e'; setTimeout(()=>{icon.textContent='content_copy';icon.style.color='';},1500); }
        showToast('📋 Copied!');
    });
}

function closeModal() { document.getElementById('modalOverlay')?.remove(); }

function showToast(msg) {
    let t = document.getElementById('toast');
    if (!t) { t = document.createElement('div'); t.id = 'toast'; t.className = 'fixed bottom-20 left-1/2 -translate-x-1/2 z-[60] bg-[#1e1e2a] text-white px-4 py-2 rounded-full text-sm shadow-lg border border-[#834ae5]/30 transition-all opacity-0'; document.body.appendChild(t); }
    t.textContent = msg; t.classList.remove('opacity-0'); t.classList.add('opacity-100');
    setTimeout(() => { t.classList.add('opacity-0'); t.classList.remove('opacity-100'); }, 2000);
}

// View all → scroll to section
document.querySelector('.text-brand-400.text-\\[10px\\].font-semibold')?.addEventListener('click', () => {
    document.querySelector('h2:contains("More Ways")')?.scrollIntoView({behavior:'smooth'});
});
</script>

<style>
@keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
</style>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
