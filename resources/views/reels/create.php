<?php $title = 'Create Reel - DTTube'; ?>
<?php ob_start(); ?>
<div class="max-w-lg mx-auto px-4 py-6 pb-24 lg:pb-6">
    <h1 class="font-display text-2xl font-bold text-white mb-6">Create Reel</h1>
    <div class="glass rounded-2xl p-6">
        <div class="border-2 border-dashed border-surface-400/50 rounded-2xl p-8 text-center mb-4 hover:border-brand-500/50 transition-colors cursor-pointer">
            <span class="material-icons-round text-surface-400 text-4xl mb-2 block">cloud_upload</span>
            <p class="text-zinc-400 text-sm">Drag & drop your reel or click to browse</p>
            <p class="text-zinc-500 text-xs mt-1">MP4, MOV up to 500MB, max 3 min</p>
        </div>
        <input type="text" placeholder="Add a caption..." class="w-full bg-surface-200 text-white px-4 py-3 rounded-xl border border-surface-400/50 focus:border-brand-500 focus:outline-none text-sm placeholder:text-surface-500 mb-3">
        <input type="text" placeholder="Add hashtags..." class="w-full bg-surface-200 text-white px-4 py-3 rounded-xl border border-surface-400/50 focus:border-brand-500 focus:outline-none text-sm placeholder:text-surface-500 mb-4">
        <button class="w-full gradient-brand py-3 rounded-xl text-white font-semibold">Post Reel</button>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
