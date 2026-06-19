<?php $hideTopNav = false; $hideBottomNav = true; $title = 'Create Story - Globiim'; ?>
<?php extract($data ?? []); ?>
<?php ob_start(); ?>

<style>
    .create-page { background: #090c15; min-height: 100vh; }
    .create-header { position: sticky; top: 0; z-index: 50; background: rgba(9,12,21,0.95); backdrop-filter: blur(20px); }

    .upload-zone {
        aspect-ratio: 9/16;
        max-height: 70vh;
        border: 2px dashed #3f3f46;
        border-radius: 20px;
        background: #14141c;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .upload-zone:hover {
        border-color: #834ae5;
        background: rgba(131,74,229,0.05);
    }
    .upload-zone.has-image {
        border-style: solid;
        border-color: #1e1e2a;
    }
    .upload-zone img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .text-preview {
        position: absolute;
        left: 24px;
        right: 24px;
        text-align: center;
        text-shadow: 0 2px 10px rgba(0,0,0,0.8), 0 0 30px rgba(0,0,0,0.5);
        pointer-events: none;
        line-height: 1.3;
        z-index: 2;
        padding: 0 4px;
    }
    .text-preview.top { top: 60px; }
    .text-preview.center { top: 50%; transform: translateY(-50%); }
    .text-preview.bottom { bottom: 60px; }

    .editor-panel {
        background: #14141c;
        border: 1px solid #1e1e2a;
        border-radius: 20px;
        padding: 16px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #834ae5, #6b21a8);
        box-shadow: 0 4px 15px rgba(131,74,229,0.3);
        color: white;
        font-weight: 700;
        border: none;
        border-radius: 14px;
        padding: 14px 24px;
        font-size: 15px;
        width: 100%;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(131,74,229,0.4); }
    .btn-primary:active { transform: scale(0.97); }
    .btn-primary:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

    .color-swatch {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.2s ease;
    }
    .color-swatch:hover { transform: scale(1.15); }
    .color-swatch.selected { border-color: #834ae5; box-shadow: 0 0 0 3px rgba(131,74,229,0.3); }

    .pos-btn {
        flex: 1;
        padding: 10px 0;
        border-radius: 12px;
        border: 1px solid #1e1e2a;
        background: #090c15;
        color: #71717a;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }
    .pos-btn:hover { border-color: #3f3f46; color: #a1a1aa; }
    .pos-btn.active {
        background: linear-gradient(135deg, #834ae5, #6b21a8);
        border-color: #834ae5;
        color: white;
    }

    .style-btn {
        padding: 10px 16px;
        border-radius: 12px;
        border: 1px solid #1e1e2a;
        background: #090c15;
        color: #71717a;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .style-btn:hover { border-color: #3f3f46; color: #a1a1aa; }
    .style-btn.active {
        background: #834ae5;
        border-color: #834ae5;
        color: white;
    }
</style>

<div class="create-page max-w-lg mx-auto">

    <!-- Header -->
    <div class="create-header px-4 py-3 flex items-center justify-between border-b border-[#14141c]">
        <div class="flex items-center gap-2">
            <a href="/stories" class="p-1 -ml-1 rounded-full hover:bg-[#14141c] transition-colors">
                <span class="material-icons-round text-zinc-400 text-[22px]">arrow_back</span>
            </a>
            <h1 class="text-white font-display text-lg font-bold">Create Story</h1>
        </div>
        <span class="text-zinc-600 text-xs">Disappears in 24h</span>
    </div>

    <form id="storyForm" enctype="multipart/form-data" method="POST" class="px-4 py-4 space-y-4">

        <!-- Image Upload Zone -->
        <div class="upload-zone" id="uploadZone" onclick="document.getElementById('imageInput').click()">
            <input type="file" id="imageInput" name="image" accept="image/jpeg,image/png,image/gif,image/webp"
                   class="hidden" onchange="previewImage(event)">

            <img id="imagePreview" src="" alt="" class="hidden">

            <!-- Text overlay preview -->
            <div id="textOverlayPreview" class="text-preview center hidden"
                 style="color: #ffffff; font-size: 28px; font-weight: 500;">
            </div>

            <div id="uploadPlaceholder" class="text-center">
                <div class="w-16 h-16 rounded-full bg-[#1e1e2a] flex items-center justify-center mx-auto mb-3">
                    <span class="material-icons-round text-zinc-500 text-3xl">add_photo_alternate</span>
                </div>
                <p class="text-zinc-400 text-sm font-medium mb-1">Tap to upload a photo</p>
                <p class="text-zinc-600 text-xs">JPG, PNG, GIF or WebP · Max 10MB</p>
            </div>
        </div>

        <!-- Text Editor Panel -->
        <div class="editor-panel space-y-4">
            <h3 class="text-white text-sm font-bold flex items-center gap-2">
                <span class="material-icons-round text-[18px]" style="color: #834ae5;">text_fields</span>
                Add Text Overlay
            </h3>

            <!-- Text Input -->
            <input type="text" name="text_content" id="textInput"
                   placeholder="Write something..."
                   maxlength="500"
                   class="w-full bg-[#090c15] border border-[#1e1e2a] rounded-xl px-4 py-3 text-white text-sm placeholder:text-zinc-600 focus:border-[#834ae5] focus:outline-none transition-colors"
                   oninput="updateTextPreview()">

            <!-- Position Selector -->
            <div>
                <p class="text-zinc-500 text-[11px] font-medium mb-2">Position</p>
                <div class="flex gap-2">
                    <button type="button" class="pos-btn" data-pos="top" onclick="setPosition('top', this)">
                        <span class="material-icons-round text-[16px]">vertical_align_top</span> Top
                    </button>
                    <button type="button" class="pos-btn active" data-pos="center" onclick="setPosition('center', this)">
                        <span class="material-icons-round text-[16px]">vertical_align_center</span> Center
                    </button>
                    <button type="button" class="pos-btn" data-pos="bottom" onclick="setPosition('bottom', this)">
                        <span class="material-icons-round text-[16px]">vertical_align_bottom</span> Bottom
                    </button>
                </div>
                <input type="hidden" name="text_position" id="textPosition" value="center">
            </div>

            <!-- Text Color -->
            <div>
                <p class="text-zinc-500 text-[11px] font-medium mb-2">Text Color</p>
                <div class="flex gap-3 flex-wrap">
                    <?php
                    $colors = [
                        '#ffffff' => 'White', '#000000' => 'Black', '#834ae5' => 'Purple',
                        '#ec4899' => 'Pink', '#f59e0b' => 'Amber', '#ef4444' => 'Red',
                        '#22c55e' => 'Green', '#3b82f6' => 'Blue', '#06b6d4' => 'Cyan',
                    ];
                    $first = true;
                    foreach ($colors as $hex => $name):
                    ?>
                    <button type="button" class="color-swatch <?= $first ? 'selected' : '' ?>"
                            style="background: <?= $hex ?>;"
                            data-color="<?= $hex ?>"
                            title="<?= $name ?>"
                            onclick="setColor('<?= $hex ?>', this)">
                    </button>
                    <?php $first = false; endforeach; ?>
                </div>
                <input type="hidden" name="text_color" id="textColor" value="#ffffff">
            </div>

            <!-- Font Size -->
            <div>
                <p class="text-zinc-500 text-[11px] font-medium mb-2">Font Size: <span id="sizeLabel">24px</span></p>
                <input type="range" name="text_size" id="textSize" min="14" max="48" value="24"
                       class="w-full accent-[#834ae5]"
                       oninput="updateSizeLabel(); updateTextPreview();">
            </div>

            <!-- Font Style -->
            <div>
                <p class="text-zinc-500 text-[11px] font-medium mb-2">Font Style</p>
                <div class="flex gap-2">
                    <button type="button" class="style-btn active" data-style="normal" onclick="setFontStyle('normal', this)">Regular</button>
                    <button type="button" class="style-btn" data-style="bold" onclick="setFontStyle('bold', this)"><b>Bold</b></button>
                    <button type="button" class="style-btn" data-style="italic" onclick="setFontStyle('italic', this)"><i>Italic</i></button>
                </div>
                <input type="hidden" name="font_style" id="fontStyle" value="normal">
            </div>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-primary" id="submitBtn">
            <span class="flex items-center justify-center gap-2">
                <span class="material-icons-round text-[20px]">send</span>
                Post Story
            </span>
        </button>
        <div id="formError" class="text-red-400 text-sm text-center hidden"></div>
    </form>

    <div class="h-8"></div>
</div>

<script>
let currentPosition = 'center';
let currentColor = '#ffffff';
let currentFontStyle = 'normal';

function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('imagePreview');
        const zone = document.getElementById('uploadZone');
        const placeholder = document.getElementById('uploadPlaceholder');

        preview.src = e.target.result;
        preview.classList.remove('hidden');
        zone.classList.add('has-image');
        placeholder.classList.add('hidden');

        updateTextPreview();
    };
    reader.readAsDataURL(file);
}

function updateTextPreview() {
    const text = document.getElementById('textInput').value;
    const overlay = document.getElementById('textOverlayPreview');
    const size = document.getElementById('textSize').value;

    overlay.textContent = text;

    if (text && document.getElementById('imagePreview').classList.contains('hidden') === false) {
        overlay.classList.remove('hidden');
    } else {
        overlay.classList.add('hidden');
    }

    overlay.style.fontSize = size + 'px';
    overlay.style.color = currentColor;

    if (currentFontStyle === 'bold') {
        overlay.style.fontWeight = '700';
        overlay.style.fontStyle = 'normal';
    } else if (currentFontStyle === 'italic') {
        overlay.style.fontWeight = '400';
        overlay.style.fontStyle = 'italic';
    } else {
        overlay.style.fontWeight = '500';
        overlay.style.fontStyle = 'normal';
    }

    // Update position class
    overlay.classList.remove('top', 'center', 'bottom');
    overlay.classList.add(currentPosition);
}

function setPosition(pos, btn) {
    currentPosition = pos;
    document.getElementById('textPosition').value = pos;

    // Update active state
    document.querySelectorAll('.pos-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    // Update preview
    const overlay = document.getElementById('textOverlayPreview');
    overlay.classList.remove('top', 'center', 'bottom');
    overlay.classList.add(pos);
}

function setColor(color, btn) {
    currentColor = color;
    document.getElementById('textColor').value = color;

    // Update active state
    document.querySelectorAll('.color-swatch').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');

    // Update preview
    document.getElementById('textOverlayPreview').style.color = color;
}

function setFontStyle(style, btn) {
    currentFontStyle = style;
    document.getElementById('fontStyle').value = style;

    // Update active state
    document.querySelectorAll('.style-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    updateTextPreview();
}

function updateSizeLabel() {
    document.getElementById('sizeLabel').textContent = document.getElementById('textSize').value + 'px';
}

// Form submission
document.getElementById('storyForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const fileInput = document.getElementById('imageInput');
    if (!fileInput.files[0]) {
        showError('Please select an image first.');
        return;
    }

    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="flex items-center justify-center gap-2"><span class="material-icons-round text-[20px] animate-spin">sync</span>Posting...</span>';

    const formData = new FormData(this);

    try {
        const res = await fetch('/stories', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData,
        });

        const data = await res.json();

        if (data.error) {
            showError(data.error);
            btn.disabled = false;
            btn.innerHTML = '<span class="flex items-center justify-center gap-2"><span class="material-icons-round text-[20px]">send</span>Post Story</span>';
            return;
        }

        // Success — redirect to story viewer
        window.location.href = data.redirect || '/stories';
    } catch (err) {
        showError('Network error. Please try again.');
        btn.disabled = false;
        btn.innerHTML = '<span class="flex items-center justify-center gap-2"><span class="material-icons-round text-[20px]">send</span>Post Story</span>';
    }
});

function showError(msg) {
    const el = document.getElementById('formError');
    el.textContent = msg;
    el.classList.remove('hidden');
    setTimeout(() => el.classList.add('hidden'), 4000);
}
</script>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
