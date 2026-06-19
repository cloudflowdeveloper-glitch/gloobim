<?php $activeTab = 'videos'; $title = 'Upload Video - Globiim'; $hideTopNav = true; $hideBottomNav = false; ?>
<?php ob_start(); ?>
<style>
    @keyframes pulse-border { 0%, 100% { border-color: rgba(147,51,234,0.3); } 50% { border-color: rgba(147,51,234,0.7); } }
    .upload-zone { transition: all 0.3s ease; }
    .upload-zone:hover, .upload-zone.dragover { background: rgba(147,51,234,0.08); }
    .upload-zone.dragover { border-color: #9333ea !important; animation: pulse-border 1.5s ease infinite; }
    .upload-zone.has-file { border-color: #22c55e !important; background: rgba(34,197,94,0.05); }

    .step-dot { transition: all 0.3s ease; }
    .step-dot.active { background: linear-gradient(135deg, #9333ea, #6b21a8); box-shadow: 0 0 12px rgba(147,51,234,0.4); }
    .step-dot.completed { background: #22c55e; }
    .step-line { transition: background 0.3s ease; }
    .step-line.active { background: linear-gradient(90deg, #22c55e, #9333ea); }

    .option-chip { transition: all 0.2s ease; cursor: pointer; }
    .option-chip:hover { background: rgba(147,51,234,0.15); border-color: rgba(147,51,234,0.4); }
    .option-chip.selected { background: linear-gradient(135deg, #9333ea, #6b21a8); color: white; border-color: transparent; }

    @keyframes fade-in { 0% { opacity: 0; transform: translateY(10px); } 100% { opacity: 1; transform: translateY(0); } }
    .fade-in { animation: fade-in 0.3s ease-out forwards; }
</style>

<div class="max-w-lg mx-auto pb-4">

    <!-- ===== HEADER ===== -->
    <div class="px-4 pt-3 pb-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="/videos" class="w-10 h-10 rounded-full bg-[#14141c] flex items-center justify-center hover:bg-[#1e1e2a] transition-colors border border-[#1e1e2a]">
                <span class="material-icons-round text-zinc-300 text-xl">chevron_left</span>
            </a>
            <div>
                <h1 class="text-white text-lg font-bold">Upload Video</h1>
                <p class="text-zinc-500 text-[11px]">Share your content with the world</p>
            </div>
        </div>
        <button onclick="showToast('Draft saved!')" class="px-4 py-2 rounded-full bg-[#14141c] text-zinc-300 text-xs font-semibold border border-[#1e1e2a] hover:bg-[#1e1e2a] transition-colors">
            Save Draft
        </button>
    </div>

    <!-- ===== STEPS INDICATOR ===== -->
    <div class="px-4 pb-3">
        <div class="flex items-center justify-center gap-0">
            <div class="step-dot active w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold" id="stepDot1">1</div>
            <div class="step-line w-16 h-0.5 bg-zinc-700 rounded" id="stepLine1"></div>
            <div class="step-dot w-8 h-8 rounded-full bg-zinc-700 flex items-center justify-center text-zinc-400 text-xs font-bold" id="stepDot2">2</div>
            <div class="step-line w-16 h-0.5 bg-zinc-700 rounded" id="stepLine2"></div>
            <div class="step-dot w-8 h-8 rounded-full bg-zinc-700 flex items-center justify-center text-zinc-400 text-xs font-bold" id="stepDot3">3</div>
        </div>
        <div class="flex justify-center gap-0 mt-1" style="margin-left: -32px; margin-right: -32px;">
            <span class="w-8 text-center text-[9px] text-zinc-500">Upload</span>
            <span class="w-16"></span>
            <span class="w-8 text-center text-[9px] text-zinc-500">Details</span>
            <span class="w-16"></span>
            <span class="w-8 text-center text-[9px] text-zinc-500">Publish</span>
        </div>
    </div>

    <!-- ===== STEP 1: UPLOAD ===== -->
    <div id="step1" class="px-4 fade-in">
        <!-- Upload Zone -->
        <div id="uploadZone" class="upload-zone border-2 border-dashed border-zinc-700 rounded-2xl p-8 text-center cursor-pointer mb-4" onclick="document.getElementById('videoFileInput').click()" ondragover="event.preventDefault(); this.classList.add('dragover')" ondragleave="this.classList.remove('dragover')" ondrop="handleDrop(event)">
            <input type="file" id="videoFileInput" accept="video/*" class="hidden" onchange="handleFileSelect(this)">
            <div id="uploadIcon">
                <div class="w-16 h-16 rounded-full mx-auto mb-3 flex items-center justify-center" style="background: linear-gradient(135deg, rgba(147,51,234,0.2), rgba(107,33,168,0.2));">
                    <span class="material-icons-round text-brand-400 text-3xl">cloud_upload</span>
                </div>
                <h3 class="text-white text-sm font-semibold mb-1">Drag & drop your video</h3>
                <p class="text-zinc-500 text-xs">or click to browse files</p>
            </div>
            <!-- File selected state -->
            <div id="fileSelected" class="hidden">
                <div class="w-16 h-16 rounded-full mx-auto mb-3 flex items-center justify-center bg-green-500/20">
                    <span class="material-icons-round text-green-400 text-3xl">video_file</span>
                </div>
                <h3 class="text-white text-sm font-semibold mb-1" id="fileName">video.mp4</h3>
                <p class="text-zinc-500 text-xs" id="fileSize">0 MB</p>
                <div class="mt-3 flex items-center justify-center gap-2">
                    <div class="flex-1 max-w-[200px] h-1.5 rounded-full bg-zinc-700 overflow-hidden">
                        <div id="uploadProgress" class="h-full rounded-full bg-gradient-to-r from-brand-500 to-brand-400 transition-all duration-500" style="width: 0%;"></div>
                    </div>
                    <span class="text-green-400 text-xs font-semibold" id="uploadPercent">0%</span>
                </div>
            </div>
        </div>

        <!-- Format info -->
        <div class="flex items-center gap-3 p-3 bg-[#14141c] rounded-xl border border-[#1e1e2a] mb-4">
            <span class="material-icons-round text-brand-400 text-lg">info</span>
            <div class="text-zinc-400 text-[11px] leading-relaxed">
                <span class="text-white font-medium">MP4, MOV, AVI</span> up to <span class="text-white font-medium">10GB</span> · Max <span class="text-white font-medium">2 hours</span> · Recommended <span class="text-white font-medium">1080p</span>
            </div>
        </div>

        <!-- Quick options -->
        <div class="mb-4">
            <h3 class="text-white text-sm font-semibold mb-2">Quick Upload</h3>
            <div class="grid grid-cols-3 gap-2">
                <button onclick="simulateUpload('Short Clip', 30)" class="p-3 bg-[#14141c] rounded-xl border border-[#1e1e2a] hover:border-brand-500/30 transition-all group">
                    <span class="material-icons-round text-brand-400 text-xl mb-1 block group-hover:scale-110 transition-transform">speed</span>
                    <span class="text-zinc-300 text-[11px] font-medium">Short Clip</span>
                    <span class="text-zinc-600 text-[9px] block">&lt; 1 min</span>
                </button>
                <button onclick="simulateUpload('Tutorial', 600)" class="p-3 bg-[#14141c] rounded-xl border border-[#1e1e2a] hover:border-brand-500/30 transition-all group">
                    <span class="material-icons-round text-brand-400 text-xl mb-1 block group-hover:scale-110 transition-transform">school</span>
                    <span class="text-zinc-300 text-[11px] font-medium">Tutorial</span>
                    <span class="text-zinc-600 text-[9px] block">5-30 min</span>
                </button>
                <button onclick="simulateUpload('Vlog', 900)" class="p-3 bg-[#14141c] rounded-xl border border-[#1e1e2a] hover:border-brand-500/30 transition-all group">
                    <span class="material-icons-round text-brand-400 text-xl mb-1 block group-hover:scale-110 transition-transform">videocam</span>
                    <span class="text-zinc-300 text-[11px] font-medium">Vlog</span>
                    <span class="text-zinc-600 text-[9px] block">10-60 min</span>
                </button>
            </div>
        </div>

        <!-- Record button -->
        <button onclick="showToast('Camera opening...')" class="w-full py-3.5 rounded-xl text-white text-sm font-semibold flex items-center justify-center gap-2 hover:opacity-90 transition-opacity mb-4" style="background: linear-gradient(135deg, #9333ea, #6b21a8);">
            <span class="material-icons-round text-lg">fiber_manual_record</span>
            Record Video
        </button>

        <!-- Next button -->
        <button onclick="goToStep(2)" id="nextBtn1" class="w-full py-3.5 rounded-xl text-zinc-500 text-sm font-semibold bg-[#14141c] border border-[#1e1e2a] cursor-not-allowed transition-all" disabled>
            Continue to Details
        </button>
    </div>

    <!-- ===== STEP 2: DETAILS ===== -->
    <div id="step2" class="px-4 hidden">
        <div class="space-y-4">
            <!-- Thumbnail -->
            <div>
                <label class="text-white text-sm font-semibold mb-1.5 block">Thumbnail</label>
                <div class="relative border-2 border-dashed border-zinc-700 rounded-xl overflow-hidden aspect-video bg-[#14141c] flex items-center justify-center cursor-pointer hover:border-brand-500/30 transition-colors" onclick="document.getElementById('thumbInput').click()">
                    <input type="file" id="thumbInput" accept="image/*" class="hidden" onchange="handleThumbSelect(this)">
                    <div id="thumbPlaceholder" class="text-center">
                        <span class="material-icons-round text-zinc-600 text-3xl">add_photo_alternate</span>
                        <p class="text-zinc-500 text-xs mt-1">Upload custom thumbnail</p>
                    </div>
                    <img id="thumbPreview" class="hidden absolute inset-0 w-full h-full object-cover">
                </div>
            </div>

            <!-- Title -->
            <div>
                <label class="text-white text-sm font-semibold mb-1.5 block">Title <span class="text-red-400">*</span></label>
                <input type="text" id="videoTitle" placeholder="Give your video a catchy title..." maxlength="100" class="w-full bg-[#14141c] text-white px-4 py-3 rounded-xl border border-[#1e1e2a] focus:border-brand-500 focus:outline-none text-sm placeholder:text-zinc-600 transition-all">
                <div class="flex justify-end mt-1">
                    <span class="text-zinc-600 text-[10px]" id="titleCount">0/100</span>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="text-white text-sm font-semibold mb-1.5 block">Description</label>
                <textarea id="videoDesc" placeholder="Tell viewers about your video..." rows="4" maxlength="2000" class="w-full bg-[#14141c] text-white px-4 py-3 rounded-xl border border-[#1e1e2a] focus:border-brand-500 focus:outline-none text-sm placeholder:text-zinc-600 transition-all resize-none"></textarea>
                <div class="flex justify-end mt-1">
                    <span class="text-zinc-600 text-[10px]" id="descCount">0/2000</span>
                </div>
            </div>

            <!-- Category -->
            <div>
                <label class="text-white text-sm font-semibold mb-1.5 block">Category</label>
                <div class="flex flex-wrap gap-2" id="categoryChips">
                    <?php foreach (['Tech', 'Gaming', 'Music', 'Business', 'Education', 'Comedy', 'Sports', 'Lifestyle', 'News', 'Art'] as $cat): ?>
                    <button type="button" class="option-chip px-3 py-1.5 rounded-full bg-[#14141c] text-zinc-400 text-xs font-medium border border-[#1e1e2a]" onclick="selectCategoryChip(this)"><?= $cat ?></button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Visibility -->
            <div>
                <label class="text-white text-sm font-semibold mb-1.5 block">Visibility</label>
                <div class="space-y-2">
                    <label class="flex items-center gap-3 p-3 bg-[#14141c] rounded-xl border border-brand-500/50 cursor-pointer">
                        <input type="radio" name="visibility" value="public" checked class="accent-brand-500">
                        <span class="material-icons-round text-brand-400 text-lg">public</span>
                        <div>
                            <span class="text-white text-xs font-semibold">Public</span>
                            <p class="text-zinc-500 text-[10px]">Everyone can see this video</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-3 bg-[#14141c] rounded-xl border border-[#1e1e2a] cursor-pointer hover:border-brand-500/30 transition-colors">
                        <input type="radio" name="visibility" value="unlisted" class="accent-brand-500">
                        <span class="material-icons-round text-zinc-500 text-lg">link</span>
                        <div>
                            <span class="text-white text-xs font-semibold">Unlisted</span>
                            <p class="text-zinc-500 text-[10px]">Only people with the link can view</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-3 bg-[#14141c] rounded-xl border border-[#1e1e2a] cursor-pointer hover:border-brand-500/30 transition-colors">
                        <input type="radio" name="visibility" value="private" class="accent-brand-500">
                        <span class="material-icons-round text-zinc-500 text-lg">lock</span>
                        <div>
                            <span class="text-white text-xs font-semibold">Private</span>
                            <p class="text-zinc-500 text-[10px]">Only you can see this video</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Tags -->
            <div>
                <label class="text-white text-sm font-semibold mb-1.5 block">Tags</label>
                <input type="text" id="videoTags" placeholder="Add tags separated by commas..." class="w-full bg-[#14141c] text-white px-4 py-3 rounded-xl border border-[#1e1e2a] focus:border-brand-500 focus:outline-none text-sm placeholder:text-zinc-600 transition-all">
            </div>

            <!-- Schedule -->
            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="scheduleCheck" class="accent-brand-500" onchange="toggleSchedule()">
                    <span class="text-white text-sm font-medium">Schedule for later</span>
                </label>
                <input type="datetime-local" id="scheduleDate" class="hidden w-full mt-2 bg-[#14141c] text-white px-4 py-3 rounded-xl border border-[#1e1e2a] focus:border-brand-500 focus:outline-none text-sm">
            </div>
        </div>

        <!-- Action buttons -->
        <div class="flex gap-3 mt-6 mb-4">
            <button onclick="goToStep(1)" class="flex-1 py-3.5 rounded-xl text-zinc-300 text-sm font-semibold bg-[#14141c] border border-[#1e1e2a] hover:bg-[#1e1e2a] transition-colors">
                Back
            </button>
            <button onclick="goToStep(3)" class="flex-1 py-3.5 rounded-xl text-white text-sm font-semibold hover:opacity-90 transition-opacity" style="background: linear-gradient(135deg, #9333ea, #6b21a8);">
                Continue
            </button>
        </div>
    </div>

    <!-- ===== STEP 3: PUBLISH ===== -->
    <div id="step3" class="px-4 hidden">
        <!-- Preview card -->
        <div class="bg-[#14141c] rounded-2xl border border-[#1e1e2a] overflow-hidden mb-4">
            <div class="relative aspect-video bg-zinc-800">
                <img id="previewThumb" src="https://placehold.co/640x360/1e1e2a/ffffff?text=Video" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                <div class="absolute bottom-3 left-3 right-3">
                    <h3 class="text-white text-sm font-bold" id="previewTitle">Your Video Title</h3>
                    <p class="text-zinc-300 text-[11px] truncate" id="previewDesc">Video description preview</p>
                </div>
                <div class="absolute top-3 right-3 px-2 py-0.5 rounded bg-brand-600 text-white text-[9px] font-bold">NEW</div>
            </div>
            <div class="p-3 flex items-center gap-3">
                <img src="https://placehold.co/36/36/6d28d9/ffffff?text=U" class="w-9 h-9 rounded-full border border-brand-600/30">
                <div>
                    <span class="text-white text-xs font-semibold">You</span>
                    <p class="text-zinc-500 text-[10px]" id="previewCategory">No category selected</p>
                </div>
            </div>
        </div>

        <!-- Checklist -->
        <div class="space-y-2 mb-4">
            <div class="flex items-center gap-2 p-2.5 rounded-lg" id="checkTitle">
                <span class="material-icons-round text-zinc-600 text-lg">check_circle_outline</span>
                <span class="text-zinc-500 text-xs">Add a title</span>
            </div>
            <div class="flex items-center gap-2 p-2.5 rounded-lg" id="checkDesc">
                <span class="material-icons-round text-zinc-600 text-lg">check_circle_outline</span>
                <span class="text-zinc-500 text-xs">Add a description</span>
            </div>
            <div class="flex items-center gap-2 p-2.5 rounded-lg" id="checkCategory">
                <span class="material-icons-round text-zinc-600 text-lg">check_circle_outline</span>
                <span class="text-zinc-500 text-xs">Select a category</span>
            </div>
            <div class="flex items-center gap-2 p-2.5 rounded-lg">
                <span class="material-icons-round text-green-400 text-lg">check_circle</span>
                <span class="text-green-400 text-xs">Video uploaded</span>
            </div>
        </div>

        <!-- Publish button -->
        <button onclick="publishVideo()" class="w-full py-3.5 rounded-xl text-white text-sm font-bold flex items-center justify-center gap-2 hover:opacity-90 transition-opacity mb-3" style="background: linear-gradient(135deg, #9333ea, #6b21a8); box-shadow: 0 4px 15px rgba(147,51,234,0.4);">
            <span class="material-icons-round text-lg">publish</span>
            Publish Video
        </button>
        <button onclick="goToStep(2)" class="w-full py-3 rounded-xl text-zinc-300 text-sm font-semibold bg-[#14141c] border border-[#1e1e2a] hover:bg-[#1e1e2a] transition-colors mb-4">
            Back to Edit
        </button>
    </div>
</div>

<script>
let currentStep = 1;
let uploadedFile = null;
let selectedCategory = '';

function handleFileSelect(input) {
    if (input.files && input.files[0]) {
        processFile(input.files[0]);
    }
}

function handleDrop(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('dragover');
    if (e.dataTransfer.files && e.dataTransfer.files[0]) {
        processFile(e.dataTransfer.files[0]);
    }
}

function processFile(file) {
    uploadedFile = file;
    document.getElementById('uploadIcon').classList.add('hidden');
    document.getElementById('fileSelected').classList.remove('hidden');
    document.getElementById('fileName').textContent = file.name;
    const sizeMB = (file.size / (1024 * 1024)).toFixed(1);
    document.getElementById('fileSize').textContent = sizeMB + ' MB';
    document.getElementById('uploadZone').classList.add('has-file');

    // Simulate upload progress
    let progress = 0;
    const interval = setInterval(() => {
        progress += Math.random() * 15 + 5;
        if (progress >= 100) {
            progress = 100;
            clearInterval(interval);
            enableNextButton();
        }
        document.getElementById('uploadProgress').style.width = progress + '%';
        document.getElementById('uploadPercent').textContent = Math.round(progress) + '%';
    }, 200);
}

function simulateUpload(type, duration) {
    document.getElementById('uploadIcon').classList.add('hidden');
    document.getElementById('fileSelected').classList.remove('hidden');
    document.getElementById('fileName').textContent = type + '.mp4';
    document.getElementById('fileSize').textContent = ((duration / 60) * 2.5).toFixed(1) + ' MB';
    document.getElementById('uploadZone').classList.add('has-file');
    uploadedFile = { name: type + '.mp4', duration: duration };

    let progress = 0;
    const interval = setInterval(() => {
        progress += Math.random() * 20 + 10;
        if (progress >= 100) {
            progress = 100;
            clearInterval(interval);
            enableNextButton();
        }
        document.getElementById('uploadProgress').style.width = progress + '%';
        document.getElementById('uploadPercent').textContent = Math.round(progress) + '%';
    }, 150);
}

function enableNextButton() {
    const btn = document.getElementById('nextBtn1');
    btn.disabled = false;
    btn.classList.remove('text-zinc-500', 'cursor-not-allowed', 'bg-[#14141c]');
    btn.classList.add('text-white', 'cursor-pointer');
    btn.style.background = 'linear-gradient(135deg, #9333ea, #6b21a8)';
}

function goToStep(step) {
    if (step === 2 && document.getElementById('nextBtn1').disabled) return;

    document.getElementById('step' + currentStep).classList.add('hidden');
    document.getElementById('step' + step).classList.remove('hidden');
    document.getElementById('step' + step).classList.add('fade-in');

    // Update dots
    for (let i = 1; i <= 3; i++) {
        const dot = document.getElementById('stepDot' + i);
        dot.classList.remove('active', 'completed');
        if (i < step) dot.classList.add('completed');
        else if (i === step) dot.classList.add('active');
    }
    // Update lines
    for (let i = 1; i <= 2; i++) {
        const line = document.getElementById('stepLine' + i);
        line.classList.remove('active');
        if (i < step) line.classList.add('active');
    }

    currentStep = step;

    if (step === 3) updatePreview();
}

function selectCategoryChip(btn) {
    document.querySelectorAll('#categoryChips .option-chip').forEach(c => c.classList.remove('selected'));
    btn.classList.add('selected');
    selectedCategory = btn.textContent;
}

function toggleSchedule() {
    const dateInput = document.getElementById('scheduleDate');
    dateInput.classList.toggle('hidden');
}

function handleThumbSelect(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            document.getElementById('thumbPreview').src = e.target.result;
            document.getElementById('thumbPreview').classList.remove('hidden');
            document.getElementById('thumbPlaceholder').classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Character counters
document.getElementById('videoTitle')?.addEventListener('input', function() {
    document.getElementById('titleCount').textContent = this.value.length + '/100';
});
document.getElementById('videoDesc')?.addEventListener('input', function() {
    document.getElementById('descCount').textContent = this.value.length + '/2000';
});

function updatePreview() {
    const title = document.getElementById('videoTitle').value || 'Untitled Video';
    const desc = document.getElementById('videoDesc').value || 'No description';
    document.getElementById('previewTitle').textContent = title;
    document.getElementById('previewDesc').textContent = desc;
    document.getElementById('previewCategory').textContent = selectedCategory || 'No category';

    // Update checks
    updateCheck('checkTitle', title !== 'Untitled Video' && title.length > 0);
    updateCheck('checkDesc', desc !== 'No description' && desc.length > 0);
    updateCheck('checkCategory', selectedCategory.length > 0);
}

function updateCheck(id, passed) {
    const el = document.getElementById(id);
    const icon = el.querySelector('.material-icons-round');
    const text = el.querySelector('span:last-child');
    if (passed) {
        icon.textContent = 'check_circle';
        icon.classList.remove('text-zinc-600');
        icon.classList.add('text-green-400');
        text.classList.remove('text-zinc-500');
        text.classList.add('text-green-400');
    }
}

function publishVideo() {
    const title = document.getElementById('videoTitle').value.trim();
    if (!title) { showToast('Please add a title'); goToStep(2); return; }

    var btn = document.querySelector('#step3 button');
    var origHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="material-icons-round" style="animation:spin 0.8s linear infinite;">sync</span> Processing...';

    var fd = new FormData();
    fd.append('title', title);
    fd.append('description', document.getElementById('videoDesc').value.trim());
    fd.append('category', selectedCategory);

    if (uploadedFile instanceof File) {
        fd.append('video', uploadedFile);
    } else if (uploadedFile?.duration) {
        fd.append('duration', uploadedFile.duration);
    }

    fetch('/videos', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: fd
    }).then(r => r.json()).then(d => {
        if (d.error) { showToast(d.error); btn.disabled = false; btn.innerHTML = origHtml; return; }
        var msg = d.message || 'Video published!';
        if (d.reels_created > 0) msg += ' — Auto-split into ' + d.reels_created + ' reels';
        showToast(msg);
        setTimeout(function() { window.location.href = '/videos'; }, 2000);
    }).catch(function() {
        showToast('Error uploading video');
        btn.disabled = false;
        btn.innerHTML = origHtml;
    });
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
