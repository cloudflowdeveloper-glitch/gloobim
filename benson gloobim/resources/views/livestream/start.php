<?php $activeTab = 'stream'; $title = 'Go Live - DTTube'; ?>
<?php ob_start(); ?>
<div class="max-w-lg mx-auto px-3 py-4">

    <div class="flex items-center gap-3 mb-6">
        <a href="/livestream" class="w-9 h-9 rounded-full bg-surface-200 flex items-center justify-center hover:bg-surface-300 transition-colors">
            <span class="material-icons-round text-zinc-400 text-xl">chevron_left</span>
        </a>
        <h1 class="font-display text-lg font-bold text-white">Go Live</h1>
    </div>

    <div id="setupPhase" class="space-y-4">
        <div class="bg-surface-100/60 rounded-2xl border border-surface-400/15 p-5">
            <div class="flex flex-col items-center text-center mb-5">

                <div id="cameraPreviewArea" class="w-full aspect-video rounded-xl bg-black overflow-hidden relative mb-4 border-2 border-dashed border-surface-400/30">
                    <video id="cameraPreview" autoplay muted playsinline class="w-full h-full object-cover hidden"></video>
                    <div id="cameraPlaceholder" class="absolute inset-0 flex flex-col items-center justify-center">
                        <div class="w-16 h-16 rounded-full bg-surface-200 flex items-center justify-center mb-2">
                            <span class="material-icons-round text-surface-400 text-3xl">videocam</span>
                        </div>
                        <span class="text-zinc-500 text-xs">Camera preview will appear here</span>
                    </div>
                    <div id="cameraLoading" class="absolute inset-0 bg-black/80 flex items-center justify-center hidden">
                        <span class="material-icons-round text-brand-400 text-4xl animate-spin">refresh</span>
                    </div>
                    <div id="cameraError" class="absolute inset-0 bg-black/80 flex flex-col items-center justify-center hidden">
                        <span class="material-icons-round text-red-400 text-3xl mb-2">videocam_off</span>
                        <span class="text-red-400 text-xs font-medium">Camera not available</span>
                        <span class="text-zinc-500 text-[10px] mt-1">You can still go live without video</span>
                    </div>
                    <div class="absolute bottom-2 right-2 flex gap-1.5">
                        <button onclick="toggleMicrophone()" id="micCheckBtn" class="w-8 h-8 rounded-full bg-black/60 backdrop-blur-sm flex items-center justify-center hover:bg-black/80 transition-colors">
                            <span class="material-icons-round text-white text-lg" id="micIcon">mic</span>
                        </button>
                        <button onclick="togglePreviewCamera()" id="camCheckBtn" class="w-8 h-8 rounded-full bg-black/60 backdrop-blur-sm flex items-center justify-center hover:bg-black/80 transition-colors">
                            <span class="material-icons-round text-white text-lg" id="camIcon">videocam</span>
                        </button>
                    </div>
                </div>

                <h2 class="text-white text-base font-bold mb-1">Start Your Live Stream</h2>
                <p class="text-zinc-400 text-xs max-w-xs">Connect with your audience in real-time. Go live and start earning gifts!</p>
            </div>

            <div class="space-y-3">
                <div>
                    <label class="block text-zinc-400 text-xs font-medium mb-1.5">Stream Title *</label>
                    <input type="text" id="streamTitle" placeholder="What's your stream about?" maxlength="100" class="w-full bg-surface-200 text-white px-4 py-2.5 rounded-xl border border-surface-400/30 focus:border-brand-500 focus:outline-none text-sm placeholder:text-zinc-500 transition-colors">
                </div>
                <div>
                    <label class="block text-zinc-400 text-xs font-medium mb-1.5">Description (optional)</label>
                    <textarea id="streamDesc" rows="2" placeholder="Tell your audience what to expect..." maxlength="500" class="w-full bg-surface-200 text-white px-4 py-2.5 rounded-xl border border-surface-400/30 focus:border-brand-500 focus:outline-none text-sm placeholder:text-zinc-500 transition-colors resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-zinc-400 text-xs font-medium mb-1.5">Stream Quality</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button class="quality-btn px-3 py-2 rounded-xl bg-brand-600/30 border border-brand-500/50 text-brand-300 text-xs font-semibold transition-colors" data-quality="720">720p</button>
                        <button class="quality-btn px-3 py-2 rounded-xl bg-surface-200 border border-surface-400/30 text-zinc-400 text-xs font-semibold hover:bg-surface-300 transition-colors" data-quality="1080">1080p</button>
                        <button class="quality-btn px-3 py-2 rounded-xl bg-surface-200 border border-surface-400/30 text-zinc-400 text-xs font-semibold hover:bg-surface-300 transition-colors" data-quality="4k">4K</button>
                    </div>
                </div>

                <div class="flex items-center gap-2 p-3 rounded-xl bg-surface-200/50 border border-surface-400/15">
                    <span class="material-icons-round text-zinc-500 text-lg" id="readyIcon">radio_button_unchecked</span>
                    <div class="flex-1">
                        <span class="text-zinc-300 text-xs font-medium" id="readyText">Check your camera and microphone</span>
                        <span class="text-zinc-600 text-[10px] block" id="readySub">Click "Check Camera" to test your setup</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mt-5">
                <button onclick="checkHardware()" id="checkBtn" class="py-2.5 rounded-xl bg-surface-200 text-zinc-300 text-sm font-semibold hover:bg-surface-300 transition-colors flex items-center justify-center gap-2">
                    <span class="material-icons-round text-lg">videocam</span>
                    Check Camera
                </button>
                <button onclick="startGoLive()" id="startBtn" disabled class="py-2.5 rounded-xl bg-zinc-700 text-zinc-500 text-sm font-semibold cursor-not-allowed flex items-center justify-center gap-2">
                    <span class="material-icons-round text-lg">sensors</span>
                    Go Live
                </button>
            </div>
        </div>

        <div class="bg-surface-100/40 rounded-2xl border border-surface-400/10 p-4">
            <h3 class="text-white text-xs font-bold mb-3 flex items-center gap-1.5">
                <span class="material-icons-round text-zinc-500 text-sm">info</span>
                Go Live Checklist
            </h3>
            <ul class="space-y-2" id="checklist">
                <li class="flex items-start gap-2" data-step="1">
                    <span class="material-icons-round text-zinc-600 text-sm mt-0.5 step-icon">radio_button_unchecked</span>
                    <span class="text-zinc-500 text-[11px] step-text">Camera & microphone check</span>
                </li>
                <li class="flex items-start gap-2" data-step="2">
                    <span class="material-icons-round text-zinc-600 text-sm mt-0.5 step-icon">radio_button_unchecked</span>
                    <span class="text-zinc-500 text-[11px] step-text">Enter a catchy stream title</span>
                </li>
                <li class="flex items-start gap-2" data-step="3">
                    <span class="material-icons-round text-zinc-600 text-sm mt-0.5 step-icon">radio_button_unchecked</span>
                    <span class="text-zinc-500 text-[11px] step-text">Choose your stream quality</span>
                </li>
                <li class="flex items-start gap-2" data-step="4">
                    <span class="material-icons-round text-zinc-600 text-sm mt-0.5 step-icon">radio_button_unchecked</span>
                    <span class="text-zinc-500 text-[11px] step-text">Click "Go Live" to start broadcasting</span>
                </li>
            </ul>
        </div>
    </div>

    <div id="goingLivePhase" class="hidden">
        <div class="bg-surface-100/60 rounded-2xl border border-surface-400/15 p-8 text-center">
            <div class="w-24 h-24 mx-auto mb-4 rounded-full gradient-brand flex items-center justify-center">
                <span class="material-icons-round text-white text-5xl animate-pulse">sensors</span>
            </div>
            <h2 class="text-white text-lg font-bold mb-1">Starting Your Stream...</h2>
            <p class="text-zinc-400 text-xs mb-4">Setting up your broadcast</p>
            <div class="flex items-center justify-center gap-1">
                <span class="w-2 h-2 rounded-full bg-brand-500 animate-bounce" style="animation-delay:0s"></span>
                <span class="w-2 h-2 rounded-full bg-brand-500 animate-bounce" style="animation-delay:0.15s"></span>
                <span class="w-2 h-2 rounded-full bg-brand-500 animate-bounce" style="animation-delay:0.3s"></span>
            </div>
            <div id="streamStatus" class="mt-3 text-zinc-500 text-[10px]">Creating livestream...</div>
        </div>
    </div>

</div>

<script>
let previewStream = null;
let previewActive = false;
let micActive = false;
let hardwareOk = false;
let selectedQuality = '720';

document.querySelectorAll('.quality-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.quality-btn').forEach(b => {
            b.className = 'quality-btn px-3 py-2 rounded-xl bg-surface-200 border border-surface-400/30 text-zinc-400 text-xs font-semibold hover:bg-surface-300 transition-colors';
        });
        this.className = 'quality-btn px-3 py-2 rounded-xl bg-brand-600/30 border border-brand-500/50 text-brand-300 text-xs font-semibold transition-colors';
        selectedQuality = this.dataset.quality;
        updateReadyState();
    });
});

document.getElementById('streamTitle').addEventListener('input', updateReadyState);
document.getElementById('streamDesc').addEventListener('input', updateReadyState);

function updateReadyState() {
    const title = document.getElementById('streamTitle').value.trim();

    if (title) {
        updateChecklistStep(2, true);
    } else {
        updateChecklistStep(2, false);
    }

    updateChecklistStep(3, true);

    const canGoLive = hardwareOk && title.length > 0;
    const btn = document.getElementById('startBtn');
    const icon = document.getElementById('readyIcon');
    const text = document.getElementById('readyText');
    const sub = document.getElementById('readySub');

    if (canGoLive) {
        btn.disabled = false;
        btn.className = 'py-2.5 rounded-xl gradient-brand text-white text-sm font-bold hover:opacity-90 transition-all flex items-center justify-center gap-2';
        btn.innerHTML = '<span class="material-icons-round text-lg">sensors</span> Go Live';
        icon.textContent = 'check_circle';
        icon.className = 'material-icons-round text-emerald-400 text-lg';
        text.textContent = 'Ready to go live!';
        text.className = 'text-emerald-400 text-xs font-medium';
        sub.textContent = 'Click "Go Live" to start broadcasting';
        updateChecklistStep(4, true);
    } else {
        btn.disabled = true;
        btn.className = 'py-2.5 rounded-xl bg-zinc-700 text-zinc-500 text-sm font-semibold cursor-not-allowed flex items-center justify-center gap-2';
        btn.innerHTML = '<span class="material-icons-round text-lg">sensors</span> Go Live';
        if (!hardwareOk) {
            icon.textContent = 'radio_button_unchecked';
            icon.className = 'material-icons-round text-zinc-500 text-lg';
            text.textContent = 'Check your camera and microphone';
            text.className = 'text-zinc-300 text-xs font-medium';
            sub.textContent = 'Click "Check Camera" to test your setup';
        } else {
            icon.textContent = 'edit';
            icon.className = 'material-icons-round text-amber-400 text-lg';
            text.textContent = 'Enter a stream title';
            text.className = 'text-zinc-300 text-xs font-medium';
            sub.textContent = 'Give your stream a title to continue';
        }
        updateChecklistStep(4, false);
    }
}

function updateChecklistStep(step, done) {
    const item = document.querySelector(`[data-step="${step}"]`);
    if (!item) return;
    const icon = item.querySelector('.step-icon');
    const text = item.querySelector('.step-text');
    if (done) {
        icon.textContent = 'check_circle';
        icon.className = 'material-icons-round text-emerald-400 text-sm mt-0.5 step-icon';
        text.className = 'text-zinc-400 text-[11px] step-text line-through';
    } else {
        icon.textContent = 'radio_button_unchecked';
        icon.className = 'material-icons-round text-zinc-600 text-sm mt-0.5 step-icon';
        text.className = 'text-zinc-500 text-[11px] step-text';
    }
}

async function checkHardware() {
    const btn = document.getElementById('checkBtn');
    const loading = document.getElementById('cameraLoading');
    const error = document.getElementById('cameraError');
    const placeholder = document.getElementById('cameraPlaceholder');
    const video = document.getElementById('cameraPreview');

    btn.disabled = true;
    btn.innerHTML = '<span class="material-icons-round text-lg animate-spin">refresh</span> Checking...';
    loading.classList.remove('hidden');
    placeholder.classList.add('hidden');
    error.classList.add('hidden');

    try {
        previewStream = await navigator.mediaDevices.getUserMedia({
            video: { width: { ideal: 1280 }, height: { ideal: 720 }, facingMode: 'user' },
            audio: true
        });

        video.srcObject = previewStream;
        video.classList.remove('hidden');
        loading.classList.add('hidden');
        previewActive = true;
        micActive = true;

        document.getElementById('camIcon').textContent = 'videocam';
        document.getElementById('micIcon').textContent = 'mic';

        hardwareOk = true;
        btn.innerHTML = '<span class="material-icons-round text-lg">check</span> Camera OK';
        btn.className = 'py-2.5 rounded-xl bg-emerald-600/20 text-emerald-400 text-sm font-semibold flex items-center justify-center gap-2';
        updateChecklistStep(1, true);
        updateReadyState();

    } catch (err) {
        loading.classList.add('hidden');
        error.classList.remove('hidden');
        hardwareOk = false;

        btn.innerHTML = '<span class="material-icons-round text-lg">videocam</span> Retry Camera';
        btn.className = 'py-2.5 rounded-xl bg-surface-200 text-zinc-300 text-sm font-semibold hover:bg-surface-300 transition-colors flex items-center justify-center gap-2';
        updateChecklistStep(1, false);
        updateReadyState();
    }

    btn.disabled = false;
}

function togglePreviewCamera() {
    if (!previewStream) return;
    const video = document.getElementById('cameraPreview');
    const track = previewStream.getVideoTracks()[0];
    if (track) {
        track.enabled = !track.enabled;
        previewActive = track.enabled;
        document.getElementById('camIcon').textContent = track.enabled ? 'videocam' : 'videocam_off';
        if (!track.enabled) video.classList.add('hidden');
        else video.classList.remove('hidden');
    }
}

function toggleMicrophone() {
    if (!previewStream) return;
    const track = previewStream.getAudioTracks()[0];
    if (track) {
        track.enabled = !track.enabled;
        micActive = track.enabled;
        document.getElementById('micIcon').textContent = track.enabled ? 'mic' : 'mic_off';
    }
}

async function startGoLive() {
    const title = document.getElementById('streamTitle').value.trim();
    if (!title || !hardwareOk) return;

    document.getElementById('setupPhase').classList.add('hidden');
    document.getElementById('goingLivePhase').classList.remove('hidden');

    const statusEl = document.getElementById('streamStatus');

    try {
        statusEl.textContent = 'Creating livestream...';

        const resp = await fetch('/livestream/start', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({
                title: title,
                description: document.getElementById('streamDesc').value.trim(),
                thumbnail: null,
            })
        });

        const data = await resp.json();

        if (data.redirect_url) {
            statusEl.textContent = 'Stream created! Redirecting...';

            if (previewStream) {
                previewStream.getTracks().forEach(t => t.stop());
                previewStream = null;
            }

            setTimeout(() => {
                window.location.href = data.redirect_url;
            }, 500);
        } else {
            throw new Error(data.error || 'Failed to create stream');
        }
    } catch (err) {
        statusEl.textContent = 'Error: ' + err.message;
        document.getElementById('setupPhase').classList.remove('hidden');
        document.getElementById('goingLivePhase').classList.add('hidden');
        alert('Failed to start stream: ' + err.message);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updateReadyState();
});
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
