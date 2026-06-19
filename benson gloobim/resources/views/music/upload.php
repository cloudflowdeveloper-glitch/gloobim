<?php $title = 'Upload Music - GLOOBIM'; $hideTopNav = true; ?>
<?php ob_start(); ?>
<style>
    .upload-zone { border: 2px dashed rgba(131,74,229,0.3); transition: all 0.3s ease; }
    .upload-zone:hover, .upload-zone.dragover { border-color: #834ae5; background: rgba(131,74,229,0.05); }
    .progress-ring { transition: stroke-dashoffset 0.3s ease; }
</style>

<div class="max-w-lg mx-auto px-4 py-4 pb-24">
    <!-- Header -->
    <div class="flex items-center gap-3 mb-6">
        <a href="/music" class="w-9 h-9 rounded-full bg-[#14141c] flex items-center justify-center border border-[#1e1e2a]">
            <span class="material-icons-round text-zinc-300 text-lg">arrow_back</span>
        </a>
        <div>
            <h1 class="text-white text-lg font-bold">Upload Music</h1>
            <p class="text-zinc-500 text-xs">Share your sound with the world</p>
        </div>
    </div>

    <form id="uploadForm" enctype="multipart/form-data" class="space-y-5">
        <!-- Cover Art Upload -->
        <div>
            <label class="text-zinc-300 text-xs font-semibold uppercase tracking-wider mb-2 block">Cover Art</label>
            <div class="upload-zone rounded-2xl p-6 flex flex-col items-center justify-center cursor-pointer" id="coverZone" onclick="document.getElementById('coverInput').click()">
                <img id="coverPreview" src="" class="w-32 h-32 rounded-2xl object-cover mb-3 hidden shadow-lg">
                <span class="material-icons-round text-4xl text-zinc-600 mb-2" id="coverIcon">add_photo_alternate</span>
                <span class="text-zinc-500 text-xs">Click to upload cover art</span>
                <span class="text-zinc-600 text-[10px] mt-0.5">JPG, PNG · Max 5MB</span>
            </div>
            <input type="file" id="coverInput" name="cover" accept="image/*" class="hidden" onchange="previewCover(this)">
        </div>

        <!-- Title -->
        <div>
            <label class="text-zinc-300 text-xs font-semibold uppercase tracking-wider mb-2 block">Title *</label>
            <input type="text" name="title" required placeholder="Track title" class="w-full bg-[#14141c] text-white px-4 py-3 rounded-xl border border-[#1e1e2a] focus:border-[#834ae5] focus:outline-none text-sm placeholder:text-zinc-600">
        </div>

        <!-- Artist Name -->
        <div>
            <label class="text-zinc-300 text-xs font-semibold uppercase tracking-wider mb-2 block">Artist Name</label>
            <input type="text" name="artist_name" placeholder="Artist / Band name" class="w-full bg-[#14141c] text-white px-4 py-3 rounded-xl border border-[#1e1e2a] focus:border-[#834ae5] focus:outline-none text-sm placeholder:text-zinc-600">
        </div>

        <!-- Genre -->
        <div>
            <label class="text-zinc-300 text-xs font-semibold uppercase tracking-wider mb-2 block">Genre</label>
            <select name="genre_id" class="w-full bg-[#14141c] text-white px-4 py-3 rounded-xl border border-[#1e1e2a] focus:border-[#834ae5] focus:outline-none text-sm">
                <option value="">Select genre...</option>
                <?php foreach ($genres as $g): ?>
                <option value="<?= $g['id'] ?>"><?= $g['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Audio File -->
        <div>
            <label class="text-zinc-300 text-xs font-semibold uppercase tracking-wider mb-2 block">Audio File *</label>
            <div class="upload-zone rounded-2xl p-6 text-center cursor-pointer" id="audioZone" onclick="document.getElementById('audioInput').click()">
                <span class="material-icons-round text-4xl text-zinc-600 mb-2 block" id="audioIcon">audio_file</span>
                <span class="text-zinc-500 text-xs" id="audioLabel">Click to upload audio file</span>
                <span class="text-zinc-600 text-[10px] mt-0.5 block">MP3, WAV, AAC · Max 50MB</span>
            </div>
            <input type="file" id="audioInput" name="audio" accept="audio/*" class="hidden" onchange="previewAudio(this)">
        </div>

        <!-- Explicit toggle -->
        <div class="flex items-center justify-between bg-[#14141c] rounded-xl p-4 border border-[#1e1e2a]">
            <div>
                <span class="text-white text-sm font-medium">Explicit Content</span>
                <p class="text-zinc-500 text-[10px]">Mark if track contains explicit lyrics</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="is_explicit" class="sr-only peer">
                <div class="w-11 h-6 bg-[#1e1e2a] rounded-full peer-checked:bg-[#834ae5] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
            </label>
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full py-3.5 rounded-xl text-white font-bold text-sm shadow-lg hover:opacity-90 transition-opacity" style="background: linear-gradient(135deg, #834ae5, #6b21a8);">
            <span id="submitText">🎵 Upload Track</span>
            <span id="submitLoader" class="hidden">
                <span class="inline-block w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin mr-1 align-middle"></span> Uploading...
            </span>
        </button>
    </form>
</div>

<script>
function previewCover(input) {
    if (!input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('coverPreview').src = e.target.result;
        document.getElementById('coverPreview').classList.remove('hidden');
        document.getElementById('coverIcon').classList.add('hidden');
    };
    reader.readAsDataURL(input.files[0]);
}

function previewAudio(input) {
    if (!input.files[0]) return;
    document.getElementById('audioLabel').textContent = '📁 ' + input.files[0].name;
    document.getElementById('audioIcon').textContent = 'check_circle';
    document.getElementById('audioIcon').style.color = '#834ae5';
}

document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const btn = form.querySelector('button[type=submit]');
    btn.disabled = true;
    document.getElementById('submitText').classList.add('hidden');
    document.getElementById('submitLoader').classList.remove('hidden');

    const data = new FormData(form);
    fetch('/music/upload', { method: 'POST', body: data })
        .then(r => r.json())
        .then(d => {
            if (d.error) { alert(d.error); return; }
            alert('✅ ' + (d.message || 'Track uploaded!'));
            window.location.href = '/music';
        })
        .catch(err => alert('Upload failed: ' + err.message))
        .finally(() => {
            btn.disabled = false;
            document.getElementById('submitText').classList.remove('hidden');
            document.getElementById('submitLoader').classList.add('hidden');
        });
});
</script>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
