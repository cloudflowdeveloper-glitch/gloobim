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

        /* Currency Picker */
        .curr-overlay { position: fixed; inset: 0; z-index: 200; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); opacity: 0; visibility: hidden; transition: all 0.25s ease; }
        .curr-overlay.open { opacity: 1; visibility: visible; }
        .curr-panel { position: fixed; bottom: 0; left: 0; right: 0; z-index: 201; max-width: 500px; margin: 0 auto; background: #0d1017; border-radius: 20px 20px 0 0; border-top: 1px solid rgba(131,74,229,0.2); max-height: 70vh; display: flex; flex-direction: column; transform: translateY(100%); transition: transform 0.3s cubic-bezier(0.32, 0.72, 0, 1); }
        .curr-overlay.open .curr-panel { transform: translateY(0); }
        @media (min-width: 640px) { .curr-panel { bottom: auto; top: 50%; left: 50%; right: auto; transform: translate(-50%, -50%) scale(0.95); border-radius: 20px; max-width: 380px; width: 100%; max-height: 75vh; border: 1px solid rgba(131,74,229,0.15); box-shadow: 0 25px 60px rgba(0,0,0,0.6); } .curr-overlay.open .curr-panel { transform: translate(-50%, -50%) scale(1); } }
        .curr-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; cursor: pointer; transition: background 0.15s; border-radius: 12px; margin: 0 8px; }
        .curr-item:hover { background: rgba(131,74,229,0.08); }
        .curr-item.active { background: rgba(131,74,229,0.12); border: 1px solid rgba(131,74,229,0.25); }
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
                <!-- Currency Selector Button -->
                <button onclick="openCurrencyPicker()" class="p-1.5 rounded-full hover:bg-surface-200 transition-colors" id="currencyBtn" title="Change Currency">
                    <span class="text-zinc-400 text-[11px] font-bold" id="currencyLabel">KES</span>
                </button>
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

    <!-- Currency Picker Modal -->
    <div class="curr-overlay" id="currOverlay" onclick="closeCurrencyPickerOutside(event)">
        <div class="curr-panel" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between px-4 pt-4 pb-2 flex-shrink-0">
                <div class="flex items-center gap-2">
                    <span class="material-icons-round text-brand-400 text-lg">currency_exchange</span>
                    <h2 class="text-lg font-bold text-white">Select Currency</h2>
                </div>
                <button onclick="closeCurrencyPicker()" class="w-8 h-8 rounded-full bg-[#14141c] border border-[#1e1e2a] flex items-center justify-center hover:bg-[#1e1e2a] transition-colors">
                    <span class="material-icons-round text-zinc-400 text-lg">close</span>
                </button>
            </div>
            <div class="px-4 pb-2">
                <p class="text-zinc-500 text-xs">Prices across the platform will be shown in your selected currency</p>
            </div>
            <div class="flex-1 overflow-y-auto scrollbar-hide pb-6" id="currencyList">
                <!-- Currencies loaded dynamically -->
            </div>
        </div>
    </div>

    <script>
    // ===== GLOBAL CURRENCY SYSTEM =====
    const GlobiimCurrency = {
        currencies: [],
        selected: { currency_code: 'KES', currency_symbol: 'KES', exchange_rate_usd: 129.5 },
        loaded: false,

        init() {
            fetch('/api/currency/current')
                .then(r => r.json())
                .then(data => {
                    if (data.currency_code) {
                        this.selected = data;
                        this.updateLabel();
                    }
                })
                .catch(() => {});
        },

        loadAll() {
            if (this.loaded) return this.renderList();
            fetch('/api/currencies')
                .then(r => r.json())
                .then(currencies => {
                    this.currencies = currencies;
                    this.loaded = true;
                    this.renderList();
                })
                .catch(() => {
                    document.getElementById('currencyList').innerHTML = '<p class="text-zinc-500 text-center py-8">Could not load currencies</p>';
                });
        },

        renderList() {
            const container = document.getElementById('currencyList');
            let html = '<div class="px-4 pt-2 pb-1"><p class="text-[11px] font-semibold text-zinc-600 uppercase tracking-wider">African & Global</p></div>';
            this.currencies.forEach(c => {
                const isActive = c.currency_code === this.selected.currency_code;
                const rate = parseFloat(c.exchange_rate_usd);
                const rateStr = rate === 1 ? 'Base currency' : '1 USD = ' + rate.toLocaleString() + ' ' + c.currency_code;
                const symbol = c.currency_symbol && c.currency_symbol !== '?' ? c.currency_symbol : c.currency_code;
                html += `<div class="curr-item ${isActive ? 'active' : ''}" onclick="GlobiimCurrency.select('${c.currency_code}')">
                    <div class="w-10 h-10 rounded-full bg-[#14141c] border border-[#1e1e2a] flex items-center justify-center flex-shrink-0">
                        <span class="text-[#834ae5] text-xs font-bold">${c.currency_code.substring(0,2)}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold text-white">${c.country_name}</span>
                            ${isActive ? '<span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full text-white" style="background: linear-gradient(135deg, #834ae5, #6b21a8);">Active</span>' : ''}
                        </div>
                        <p class="text-zinc-500 text-[11px]">${symbol} (${c.currency_code}) &middot; ${rateStr}</p>
                    </div>
                </div>`;
            });
            container.innerHTML = html;
        },

        select(code) {
            fetch('/api/currency/set', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ currency_code: code })
            })
            .then(r => r.json())
            .then(data => {
                if (data.currency) {
                    this.selected = data.currency;
                    this.updateLabel();
                    this.renderList();
                    this.showToast('Currency changed to ' + data.currency.currency_code);
                    // Reload page after short delay to update all prices
                    setTimeout(() => location.reload(), 800);
                } else if (data.error) {
                    this.showToast(data.error);
                }
            })
            .catch(() => this.showToast('Failed to change currency'));
        },

        updateLabel() {
            const label = document.getElementById('currencyLabel');
            if (label) label.textContent = this.selected.currency_code || 'KES';
        },

        // Convert USD amount to selected currency
        convert(usdAmount) {
            const rate = parseFloat(this.selected.exchange_rate_usd) || 1;
            return Math.round(usdAmount * rate * 100) / 100;
        },

        // Format amount in selected currency
        format(usdAmount, showSymbol = true) {
            const converted = this.convert(usdAmount);
            const symbol = this.selected.currency_symbol || this.selected.currency_code;
            if (converted >= 1000000) return (showSymbol ? symbol + ' ' : '') + (converted / 1000000).toFixed(1) + 'M';
            if (converted >= 100000) return (showSymbol ? symbol + ' ' : '') + Math.round(converted / 1000) + 'K';
            return (showSymbol ? symbol + ' ' : '') + converted.toLocaleString(undefined, { minimumFractionDigits: converted >= 100 ? 0 : 2, maximumFractionDigits: 2 });
        },

        showToast(msg) {
            const existing = document.querySelector('.curr-toast');
            if (existing) existing.remove();
            const div = document.createElement('div');
            div.className = 'curr-toast fixed top-16 left-1/2 -translate-x-1/2 px-5 py-2.5 rounded-full text-white text-sm font-medium z-[300]';
            div.style.cssText = 'background: linear-gradient(135deg, #834ae5, #6b21a8); box-shadow: 0 4px 20px rgba(131,74,229,0.4);';
            div.textContent = msg;
            document.body.appendChild(div);
            setTimeout(() => { div.style.opacity = '0'; div.style.transition = 'opacity 0.3s'; setTimeout(() => div.remove(), 300); }, 2000);
        }
    };

    // Initialize currency on page load
    GlobiimCurrency.init();

    function openCurrencyPicker() {
        document.getElementById('currOverlay').classList.add('open');
        document.body.style.overflow = 'hidden';
        GlobiimCurrency.loadAll();
    }

    function closeCurrencyPicker() {
        document.getElementById('currOverlay').classList.remove('open');
        document.body.style.overflow = '';
    }

    function closeCurrencyPickerOutside(e) {
        if (e.target === e.currentTarget) closeCurrencyPicker();
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeCurrencyPicker();
    });
    </script>

</body>
</html>