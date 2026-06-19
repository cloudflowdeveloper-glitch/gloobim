<?php $title = 'Create Playlist - GLOOBIM'; $hideTopNav = true; ?>
<?php ob_start(); ?>

<div class="max-w-lg mx-auto px-4 py-4">
    <div class="flex items-center gap-3 mb-6">
        <a href="/music" class="w-9 h-9 rounded-full bg-[#14141c] flex items-center justify-center border border-[#1e1e2a]">
            <span class="material-icons-round text-zinc-300 text-lg">arrow_back</span>
        </a>
        <div>
            <h1 class="text-white text-lg font-bold">Create Playlist</h1>
            <p class="text-zinc-500 text-xs">Curate your perfect mix</p>
        </div>
    </div>

    <form id="playlistForm" enctype="multipart/form-data" class="space-y-5">
        <!-- Cover -->
        <div>
            <label class="text-zinc-300 text-xs font-semibold uppercase tracking-wider mb-2 block">Cover Image</label>
            <div class="rounded-2xl p-6 flex flex-col items-center justify-center cursor-pointer" style="border: 2px dashed rgba(131,74,229,0.3);" onclick="document.getElementById('plCover').click()">
                <img id="plCoverPreview" src="" class="w-32 h-32 rounded-2xl object-cover mb-3 hidden shadow-lg">
                <span class="material-icons-round text-4xl text-zinc-600 mb-2" id="plCoverIcon">library_music</span>
                <span class="text-zinc-500 text-xs">Click to upload cover</span>
            </div>
            <input type="file" id="plCover" name="cover" accept="image/*" class="hidden" onchange="previewPlCover(this)">
        </div>

        <!-- Name -->
        <div>
            <label class="text-zinc-300 text-xs font-semibold uppercase tracking-wider mb-2 block">Playlist Name *</label>
            <input type="text" name="name" required placeholder="My Awesome Playlist" class="w-full bg-[#14141c] text-white px-4 py-3 rounded-xl border border-[#1e1e2a] focus:border-[#834ae5] focus:outline-none text-sm placeholder:text-zinc-600">
        </div>

        <!-- Description -->
        <div>
            <label class="text-zinc-300 text-xs font-semibold uppercase tracking-wider mb-2 block">Description</label>
            <textarea name="description" rows="3" placeholder="What's this playlist about?" class="w-full bg-[#14141c] text-white px-4 py-3 rounded-xl border border-[#1e1e2a] focus:border-[#834ae5] focus:outline-none text-sm placeholder:text-zinc-600 resize-none"></textarea>
        </div>

        <!-- Search & Add Tracks -->
        <div>
            <label class="text-zinc-300 text-xs font-semibold uppercase tracking-wider mb-2 block">Add Tracks</label>
            <div class="relative mb-3">
                <span class="material-icons-round absolute left-3 top-1/2 -translate-y-1/2 text-zinc-600 text-lg">search</span>
                <input type="text" id="trackSearch" placeholder="Search for tracks to add..." class="w-full bg-[#14141c] text-white pl-10 pr-4 py-2.5 rounded-xl border border-[#1e1e2a] focus:border-[#834ae5] focus:outline-none text-sm placeholder:text-zinc-600" autocomplete="off">
            </div>
            <div id="trackResults" class="space-y-1 max-h-60 overflow-y-auto"></div>
            <div id="selectedTracks" class="mt-3 space-y-1">
                <p class="text-zinc-500 text-[10px] mb-2" id="selectedCount">0 tracks selected</p>
            </div>
            <input type="hidden" name="track_ids" id="trackIdsInput" value="">
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full py-3.5 rounded-xl text-white font-bold text-sm shadow-lg hover:opacity-90 transition-opacity" style="background: linear-gradient(135deg, #834ae5, #6b21a8);">
            📂 Create Playlist
        </button>
    </form>
</div>

<script>
const selectedTracks = new Map();

function previewPlCover(input) {
    if (!input.files[0]) return;
    const r = new FileReader();
    r.onload = e => {
        document.getElementById('plCoverPreview').src = e.target.result;
        document.getElementById('plCoverPreview').classList.remove('hidden');
        document.getElementById('plCoverIcon').classList.add('hidden');
    };
    r.readAsDataURL(input.files[0]);
}

let trackSearchTimeout;
document.getElementById('trackSearch').addEventListener('input', function() {
    clearTimeout(trackSearchTimeout);
    const q = this.value.trim();
    if (q.length < 2) { document.getElementById('trackResults').innerHTML = ''; return; }
    trackSearchTimeout = setTimeout(() => {
        fetch('/music/search?q=' + encodeURIComponent(q))
            .then(r => r.json())
            .then(d => {
                const el = document.getElementById('trackResults');
                el.innerHTML = d.tracks.slice(0, 10).map(t => {
                    const added = selectedTracks.has(parseInt(t.id));
                    return '<div class="flex items-center gap-3 p-2.5 rounded-lg cursor-pointer ' + (added ? 'bg-[#834ae5]/10' : 'hover:bg-[#1e1e2a]') + '" onclick="toggleTrack('+t.id+',\''+t.title.replace(/'/g,"\\'")+'\',\''+(t.artist_name||'').replace(/'/g,"\\'")+'\', this)">' +
                        '<span class="material-icons-round text-lg '+(added?'text-[#834ae5]':'text-zinc-600')+'">'+(added?'check_circle':'add_circle')+'</span>' +
                        '<img src="'+t.cover_url+'" class="w-10 h-10 rounded-lg object-cover">' +
                        '<div class="flex-1 min-w-0"><div class="text-white text-xs font-semibold truncate">'+t.title+'</div><div class="text-zinc-500 text-[10px]">'+t.artist_name+'</div></div>' +
                        '<span class="text-zinc-600 text-[10px]">'+fmtDur(t.duration||0)+'</span></div>';
                }).join('');
            });
    }, 300);
});

function toggleTrack(id, title, artist, el) {
    id = parseInt(id);
    if (selectedTracks.has(id)) {
        selectedTracks.delete(id);
        el.classList.remove('bg-[#834ae5]/10');
        el.querySelector('.material-icons-round').textContent = 'add_circle';
        el.querySelector('.material-icons-round').style.color = '';
    } else {
        selectedTracks.set(id, { title, artist });
        el.classList.add('bg-[#834ae5]/10');
        el.querySelector('.material-icons-round').textContent = 'check_circle';
        el.querySelector('.material-icons-round').style.color = '#834ae5';
    }
    updateSelected();
}

function updateSelected() {
    document.getElementById('selectedCount').textContent = selectedTracks.size + ' track' + (selectedTracks.size !== 1 ? 's' : '') + ' selected';
    document.getElementById('trackIdsInput').value = Array.from(selectedTracks.keys()).join(',');
    
    const list = document.getElementById('selectedTracks');
    list.querySelectorAll('.selected-item').forEach(el => el.remove());
    selectedTracks.forEach((t, id) => {
        const div = document.createElement('div');
        div.className = 'selected-item flex items-center gap-2 p-2 bg-[#14141c] rounded-lg text-xs';
        div.innerHTML = '<span class="text-[#834ae5]">🎵</span><span class="text-white flex-1 truncate">'+t.title+'</span><span class="text-zinc-500">'+t.artist+'</span><span class="material-icons-round text-zinc-600 text-sm cursor-pointer hover:text-red-400">close</span>';
        div.querySelector('.material-icons-round').onclick = () => { selectedTracks.delete(id); updateSelected(); };
        list.appendChild(div);
    });
}

function fmtDur(s) { s = parseInt(s)||0; const m=Math.floor(s/60); return m+':'+String(s%60).padStart(2,'0'); }

document.getElementById('playlistForm').addEventListener('submit', function(e) {
    e.preventDefault();
    if (selectedTracks.size === 0 && !confirm('Create playlist with no tracks?')) return;
    const data = new FormData(this);
    fetch('/music/playlist/create', { method: 'POST', body: data })
        .then(r => r.json())
        .then(d => {
            if (d.error) { alert(d.error); return; }
            alert('✅ ' + (d.message || 'Playlist created!'));
            window.location.href = '/music';
        })
        .catch(err => alert('Failed: ' + err.message));
});
</script>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
