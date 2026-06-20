<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Globiim - Creator Super Platform' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Space Grotesk', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#faf5ff',
                            100: '#f3e8ff',
                            200: '#e9d5ff',
                            300: '#d8b4fe',
                            400: '#c084fc',
                            500: '#a855f7',
                            600: '#9333ea',
                            700: '#7e22ce',
                            800: '#6b21a8',
                            900: '#581c87',
                            950: '#3b0764',
                        },
                        surface: {
                            50: '#18181b',
                            100: '#1e1e22',
                            200: '#27272a',
                            300: '#2d2d32',
                            400: '#3f3f46',
                            500: '#52525b',
                        },
                    },
                },
            },
        }
    </script>
    <style>
        body { background: #090c15; color: #e4e4e7; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .gradient-brand { background: linear-gradient(135deg, #9333ea 0%, #6d28d9 50%, #4f46e5 100%); }
        .gradient-text { background: linear-gradient(135deg, #c084fc 0%, #818cf8 50%, #6d28d9 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .glass { background: rgba(9, 12, 21, 0.92); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(20, 20, 28, 0.8); }
        .glass-bottom { background: rgba(9, 12, 21, 0.95); backdrop-filter: blur(20px); border-top: 1px solid rgba(20, 20, 28, 0.8); }
        .hover-scale { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .hover-scale:hover { transform: scale(1.03); box-shadow: 0 8px 30px rgba(147, 51, 234, 0.15); }
        .pulse-live { animation: pulse-live 2s ease-in-out infinite; }
        @keyframes pulse-live { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
        .story-ring { background: linear-gradient(135deg, #9333ea, #ec4899, #f59e0b); padding: 2.5px; border-radius: 50%; }
        .story-ring-seen { background: #3f3f46; padding: 2.5px; border-radius: 50%; }
    </style>
</head>
<body class="font-sans antialiased min-h-screen">

    <?php if (empty($hideTopNav)): ?>
    <nav class="glass fixed top-0 left-0 right-0 z-50 h-14" id="topNav">
        <div class="max-w-lg mx-auto px-3 h-full flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 flex-shrink-0">
                <img src="/logo.jpeg" alt="Globiim" class="h-8 w-auto rounded-lg object-contain">
                <span class="font-display font-bold text-lg tracking-tight"><span class="text-white">Glo</span><span class="gradient-text">biim</span></span>
            </a>

            <div class="flex-1 mx-3">
                <div class="relative">
                    <span class="material-icons-round absolute left-3 top-1/2 -translate-y-1/2 text-surface-500 text-lg">search</span>
                    <input type="text" placeholder="Search..." class="w-full bg-surface-200/80 text-white pl-9 pr-4 py-2 rounded-full border border-surface-400/30 focus:border-brand-500 focus:outline-none text-sm placeholder:text-surface-500 transition-all">
                </div>
            </div>

            <div class="flex items-center gap-1">
                <a href="/messages" class="p-1.5 rounded-full hover:bg-surface-200 transition-colors relative">
                    <span class="material-icons-round text-zinc-300 text-[20px]">chat_bubble_outline</span>
                    <span class="absolute top-0.5 right-0.5 w-2 h-2 bg-brand-500 rounded-full"></span>
                </a>
                <a href="/notifications" class="p-1.5 rounded-full hover:bg-surface-200 transition-colors relative">
                    <span class="material-icons-round text-zinc-300 text-[20px]">notifications_none</span>
                    <span class="absolute top-0.5 right-0.5 w-2 h-2 bg-red-500 rounded-full"></span>
                </a>
                <a href="/profile" class="ml-0.5">
                    <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-brand-600/50">
                        <img src="/uploads/profiles/admin.jpg" alt="Profile" class="w-full h-full object-cover">
                    </div>
                </a>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <main class="<?= empty($hideTopNav) ? 'pt-14' : '' ?> <?= empty($hideBottomNav) ? '' : 'pb-14' ?> min-h-screen max-w-lg mx-auto">
        <?= $content ?? '' ?>
    </main>

    <?php if (empty($hideBottomNav)): ?>
    <nav class="glass-bottom fixed bottom-0 left-0 right-0 z-50 h-14">
        <div class="max-w-lg mx-auto px-3 h-full flex items-center justify-around">
            <a href="/" class="flex flex-col items-center justify-center gap-0.5 px-3 py-1 rounded-xl <?= isset($activeTab) && $activeTab === 'home' ? 'text-brand-400' : 'text-zinc-500' ?>">
                <span class="material-icons-round text-[22px]">home</span>
                <span class="text-[10px] font-medium">Home</span>
            </a>
            <a href="/reels" class="flex flex-col items-center justify-center gap-0.5 px-3 py-1 rounded-xl <?= isset($activeTab) && $activeTab === 'clips' ? 'text-[#e82c3d]' : 'text-zinc-500' ?>">
                <span class="material-icons-round text-[22px]">movie</span>
                <span class="text-[10px] font-medium">Clips</span>
            </a>
            <a href="/posts/create" class="flex flex-col items-center justify-center gap-0.5 px-3 py-1">
                <div class="w-10 h-7 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #ef9b00, #fca70c);">
                    <span class="material-icons-round text-white text-lg">add</span>
                </div>
                <span class="text-[10px] font-medium text-zinc-500">Post</span>
            </a>
            <a href="/videos" class="flex flex-col items-center justify-center gap-0.5 px-3 py-1 rounded-xl <?= isset($activeTab) && $activeTab === 'videos' ? 'text-brand-400' : 'text-zinc-500' ?>">
                <span class="material-icons-round text-[22px]">play_circle</span>
                <span class="text-[10px] font-medium">Videos</span>
            </a>
            <a href="/menu" class="flex flex-col items-center justify-center gap-0.5 px-3 py-1 rounded-xl <?= isset($activeTab) && $activeTab === 'menu' ? 'text-brand-400' : 'text-zinc-500' ?>">
                <span class="material-icons-round text-[22px]">menu</span>
                <span class="text-[10px] font-medium">Menu</span>
            </a>
        </div>
    </nav>
    <?php endif; ?>

</body>
</html>
