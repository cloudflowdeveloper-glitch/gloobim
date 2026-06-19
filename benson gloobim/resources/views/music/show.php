<?php
$trackId = $track['id'] ?? 0;
$title = ($track['title'] ?? 'Track') . ' - GLOOBIM Music'; 
$hideTopNav = true;
?>
<?php ob_start(); ?>
<style>
    @keyframes spin-slow { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    .spin-slow { animation: spin-slow 8s linear infinite; }
    @keyframes visualizer { 0%,100% { transform: scaleY(0.3); } 50% { transform: scaleY(1); } }
    .viz-bar { animation: visualizer 1.2s ease-in-out infinite; transform-origin: bottom; }
    .viz-bar:nth-child(1) { animation-delay: 0s; height: 12px; }
    .viz-bar:nth-child(2) { animation-delay: 0.15s; height: 20px; }
    .viz-bar:nth-child(3) { animation-delay: 0.3s; height: 8px; }
    .viz-bar:nth-child(4) { animation-delay: 0.45s; height: 16px; }
    .viz-bar:nth-child(5) { animation-delay: 0.6s; height: 10px; }
    @keyframes pulse-glow { 0%,100% { box-shadow: 0 0 20px rgba(131,74,229,0.4); } 50% { box-shadow: 0 0 40px rgba(131,74,229,0.7); } }
    .glow-pulse { animation: pulse-glow 2s ease-in-out infinite; }
</style>

<div class="max-w-lg mx-auto pb-24">
    <!-- Header -->
    <div class="px-4 pt-3 pb-1 flex items-center gap-3">
        <a href="/music" class="w-9 h-9 rounded-full bg-[#14141c] flex items-center justify-center border border-[#1e1e2a]">
            <span class="material-icons-round text-zinc-300 text-lg">arrow_back</span>
        </a>
        <div class="flex-1 min-w-0">
            <h1 class="text-white text-sm font-bold truncate"><?= htmlspecialchars($track['title'] ?? 'Track') ?></h1>
            <p class="text-zinc-500 text-[10px] truncate"><?= htmlspecialchars($track['artist_name'] ?? 'Unknown Artist') ?></p>
        </div>
        <button onclick="moreOptions()" class="w-9 h-9 rounded-full bg-[#14141c] flex items-center justify-center border border-[#1e1e2a]">
            <span class="material-icons-round text-zinc-300 text-lg">more_vert</span>
        </button>
    </div>

    <!-- Cover Art -->
    <div class="px-4 py-4 flex justify-center">
        <div class="relative">
            <div class="w-64 h-64 rounded-3xl overflow-hidden glow-pulse shadow-2xl" style="box-shadow: 0 0 60px rgba(131,74,229,0.25);">
                <img src="<?= $track['cover_url'] ?? '' ?>" alt="Cover" class="w-full h-full object-cover" id="coverImg">
            </div>
            <?php if (!empty($track['is_verified'])): ?>
            <div class="absolute -top-2 -right-2 w-8 h-8 rounded-full flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #834ae5, #4f09f5);">
                <span class="material-icons-round text-white text-sm">verified</span>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Track Info -->
    <div class="px-4 text-center mb-4">
        <h2 class="text-white text-xl font-bold"><?= htmlspecialchars($track['title'] ?? '') ?></h2>
        <div class="flex items-center justify-center gap-1.5 mt-1">
            <img src="<?= $track['artist_avatar'] ?? '' ?>" class="w-5 h-5 rounded-full">
            <span class="text-zinc-400 text-sm"><?= htmlspecialchars($track['artist_name'] ?? '') ?></span>
            <?php if (!empty($track['is_verified'])): ?>
            <span class="material-icons-round text-[14px]" style="color: #834ae5;">verified</span>
            <?php endif; ?>
        </div>
        <?php if (!empty($track['is_explicit'])): ?>
        <span class="inline-block mt-1.5 px-2 py-0.5 rounded text-[9px] font-bold bg-zinc-700 text-zinc-300">E</span>
        <?php endif; ?>
    </div>

    <!-- Progress Bar -->
    <div class="px-4 mb-4">
        <div class="flex items-center gap-2 mb-1">
            <span class="text-zinc-500 text-[10px] font-medium" id="currentTime">0:00</span>
            <div class="flex-1 h-1.5 rounded-full bg-[#1e1e2a] overflow-hidden cursor-pointer" onclick="seekTrack(event)">
                <div class="h-full rounded-full transition-all duration-1000" id="progressBar" style="width: 0%; background: linear-gradient(90deg, #834ae5, #ec4899);"></div>
            </div>
            <span class="text-zinc-500 text-[10px] font-medium"><?= formatDuration((int)($track['duration'] ?? 0)) ?></span>
        </div>
    </div>

    <!-- Controls -->
    <div class="px-4 flex items-center justify-center gap-6 mb-5">
        <button onclick="shareTrack()" class="p-2 rounded-full hover:bg-[#1e1e2a] transition-colors">
            <span class="material-icons-round text-zinc-400 text-2xl">share</span>
        </button>
        <button onclick="prevTrack()" class="p-2 rounded-full hover:bg-[#1e1e2a] transition-colors">
            <span class="material-icons-round text-white text-3xl">skip_previous</span>
        </button>
        <button onclick="togglePlay()" class="w-16 h-16 rounded-full flex items-center justify-center shadow-2xl glow-pulse" style="background: linear-gradient(135deg, #834ae5, #4f09f5);">
            <span class="material-icons-round text-white text-3xl" id="playIcon">play_arrow</span>
        </button>
        <button onclick="nextTrack()" class="p-2 rounded-full hover:bg-[#1e1e2a] transition-colors">
            <span class="material-icons-round text-white text-3xl">skip_next</span>
        </button>
        <button onclick="likeTrack()" class="p-2 rounded-full hover:bg-[#1e1e2a] transition-colors" id="likeBtn">
            <span class="material-icons-round text-zinc-400 text-2xl">favorite_border</span>
        </button>
    </div>

    <!-- Stats Row -->
    <div class="px-4 grid grid-cols-3 gap-3 mb-5">
        <div class="bg-[#14141c] rounded-xl p-3 text-center border border-[#1e1e2a]">
            <span class="text-white text-sm font-bold block"><?= number_format((int)($track['plays'] ?? 0)) ?></span>
            <span class="text-zinc-500 text-[10px]">Plays</span>
        </div>
        <div class="bg-[#14141c] rounded-xl p-3 text-center border border-[#1e1e2a]">
            <span class="text-white text-sm font-bold block" id="likeCount"><?= number_format((int)($track['likes'] ?? 0)) ?></span>
            <span class="text-zinc-500 text-[10px]">Likes</span>
        </div>
        <div class="bg-[#14141c] rounded-xl p-3 text-center border border-[#1e1e2a]">
            <span class="text-white text-sm font-bold block"><?= number_format((int)($track['shares'] ?? 0)) ?></span>
            <span class="text-zinc-500 text-[10px]">Shares</span>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="px-4 space-y-2">
        <button onclick="addToPlaylist()" class="w-full flex items-center gap-3 p-3 rounded-xl bg-[#14141c] border border-[#1e1e2a] hover:bg-[#1e1e2a] transition-colors">
            <span class="material-icons-round text-zinc-400 text-lg">playlist_add</span>
            <span class="text-white text-xs">Add to Playlist</span>
        </button>
        <a href="/music/upload" class="w-full flex items-center gap-3 p-3 rounded-xl bg-[#14141c] border border-[#1e1e2a] hover:bg-[#1e1e2a] transition-colors no-underline">
            <span class="material-icons-round text-[#834ae5] text-lg">upload_file</span>
            <span class="text-[#834ae5] text-xs">Upload Your Music</span>
        </a>
    </div>
</div>

<script>
const track = <?= json_encode($track ?? []) ?>;
let isPlaying = false;
let progress = 0;
let progressInterval = null;

function togglePlay() {
    const icon = document.getElementById('playIcon');
    if (isPlaying) {
        icon.textContent = 'play_arrow';
        isPlaying = false;
        clearInterval(progressInterval);
    } else {
        icon.textContent = 'pause';
        isPlaying = true;
        if (progress === 0 && track.id) { fetch('/music/'+track.id+'/play', {method:'POST'}).catch(()=>{}); }
        startProgress();
    }
}

function startProgress() {
    clearInterval(progressInterval);
    progressInterval = setInterval(() => {
        progress++;
        const dur = track.duration || 180;
        const pct = Math.min((progress/dur)*100, 99);
        document.getElementById('progressBar').style.width = pct+'%';
        const m = Math.floor(progress/60);
        document.getElementById('currentTime').textContent = m+':'+String(progress%60).padStart(2,'0');
        if (progress >= dur) { isPlaying=false; clearInterval(progressInterval); document.getElementById('playIcon').textContent='play_arrow'; }
    }, 1000);
}

function seekTrack(e) {
    const rect = e.currentTarget.getBoundingClientRect();
    const pct = ((e.clientX-rect.left)/rect.width)*100;
    document.getElementById('progressBar').style.width = pct+'%';
    progress = Math.floor((pct/100)*(track.duration||180));
    const m = Math.floor(progress/60);
    document.getElementById('currentTime').textContent = m+':'+String(progress%60).padStart(2,'0');
}

function likeTrack() {
    if (!track.id) return;
    fetch('/music/'+track.id+'/like', {method:'POST'}).then(r=>r.json()).then(d=>{
        if (d.error) { location.href='/login'; return; }
        const icon = document.querySelector('#likeBtn .material-icons-round');
        icon.textContent = d.liked ? 'favorite' : 'favorite_border';
        icon.style.color = d.liked ? '#ec4899' : '';
        document.getElementById('likeCount').textContent = (d.likes||0).toLocaleString();
    });
}

function shareTrack() {
    if (!track.id) return;
    fetch('/music/'+track.id+'/share', {method:'POST'}).catch(()=>{});
    const url = location.origin + '/music/track/' + track.id;
    if (navigator.share) { navigator.share({title:track.title,url}).catch(()=>{}); }
    else { navigator.clipboard.writeText(url).then(()=>showToast('🔗 Link copied!')); }
}

function prevTrack() { if (progress > 3) { progress=0; document.getElementById('progressBar').style.width='0%'; document.getElementById('currentTime').textContent='0:00'; return; } history.back(); }
function nextTrack() { showToast('🎵 Next track from your queue'); }
function moreOptions() { showToast('🎵 ' + (track.title||'') + ' · ' + (track.artist_name||'')); }
function addToPlaylist() { location.href = '/music/playlist/create'; }

function showToast(msg) {
    let t = document.getElementById('toast');
    if (!t) { t=document.createElement('div'); t.id='toast'; t.className='fixed bottom-20 left-1/2 -translate-x-1/2 z-50 bg-[#1e1e2a] text-white px-4 py-2 rounded-full text-sm shadow-lg border border-[#834ae5]/30 transition-all opacity-0'; document.body.appendChild(t); }
    t.textContent=msg; t.classList.remove('opacity-0'); t.classList.add('opacity-100');
    setTimeout(()=>{t.classList.add('opacity-0');t.classList.remove('opacity-100')},2000);
}
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
