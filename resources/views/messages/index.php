<?php $activeTab = 'menu'; $title = 'Messages - Globiim'; $hideTopNav = true; $hideBottomNav = false; ?>
<?php extract($data ?? []); ?>
<?php ob_start(); ?>
<style>
    .conv-item { transition: all 0.2s ease; }
    .conv-item:hover { background: rgba(255,255,255,0.03); }
    .conv-item:active { transform: scale(0.98); }
    .online-dot { position: absolute; bottom: 0; right: 0; width: 12px; height: 12px; border-radius: 50%; border: 2.5px solid #090c15; }
    .online-dot.online { background: #22c55e; }
    .online-dot.offline { background: #52525b; }
    .tab-btn { transition: all 0.2s ease; }
    .tab-btn.active { background: linear-gradient(135deg, #834ae5, #6b21a8); color: white; box-shadow: 0 2px 10px rgba(131,74,229,0.3); }
    .new-chat-fab { transition: all 0.2s ease; }
    .new-chat-fab:hover { transform: scale(1.1); box-shadow: 0 6px 25px rgba(131,74,229,0.4); }
    .new-chat-fab:active { transform: scale(0.95); }
    .typing-indicator span { display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #834ae5; animation: typing-bounce 1.4s infinite; }
    .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
    .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }
    @keyframes typing-bounce { 0%, 60%, 100% { transform: translateY(0); } 30% { transform: translateY(-4px); } }

    /* ===== New Message Modal ===== */
    .nm-overlay {
        position: fixed; inset: 0; z-index: 100;
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(4px);
        opacity: 0; visibility: hidden;
        transition: all 0.25s ease;
    }
    .nm-overlay.open { opacity: 1; visibility: visible; }

    .nm-panel {
        position: fixed; bottom: 0; left: 0; right: 0; z-index: 101;
        max-width: 500px; margin: 0 auto;
        background: #0d1017;
        border-radius: 20px 20px 0 0;
        border-top: 1px solid rgba(131,74,229,0.2);
        max-height: 85vh;
        display: flex; flex-direction: column;
        transform: translateY(100%);
        transition: transform 0.3s cubic-bezier(0.32, 0.72, 0, 1);
    }
    .nm-overlay.open .nm-panel { transform: translateY(0); }

    @media (min-width: 640px) {
        .nm-panel {
            bottom: auto; top: 50%; left: 50%; right: auto;
            transform: translate(-50%, -50%) scale(0.95);
            border-radius: 20px;
            max-width: 420px;
            width: 100%;
            max-height: 75vh;
            border: 1px solid rgba(131,74,229,0.15);
            box-shadow: 0 25px 60px rgba(0,0,0,0.6), 0 0 40px rgba(131,74,229,0.1);
        }
        .nm-overlay.open .nm-panel { transform: translate(-50%, -50%) scale(1); }
    }

    .nm-handle {
        width: 36px; height: 4px; border-radius: 2px;
        background: rgba(255,255,255,0.15);
        margin: 10px auto 0;
    }
    @media (min-width: 640px) { .nm-handle { display: none; } }

    .nm-search-input {
        background: #14141c;
        border: 1.5px solid #1e1e2a;
        border-radius: 24px;
        padding: 12px 16px 12px 44px;
        color: white;
        font-size: 15px;
        width: 100%;
        outline: none;
        transition: border-color 0.2s;
    }
    .nm-search-input::placeholder { color: #52525b; }
    .nm-search-input:focus { border-color: #834ae5; }

    .nm-user-item {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 16px;
        cursor: pointer;
        transition: background 0.15s;
        border-radius: 12px;
        margin: 0 8px;
    }
    .nm-user-item:hover { background: rgba(131,74,229,0.08); }
    .nm-user-item:active { background: rgba(131,74,229,0.15); transform: scale(0.98); }
    .nm-user-item.focused { background: rgba(131,74,229,0.12); outline: 1.5px solid rgba(131,74,229,0.4); outline-offset: -1.5px; }

    .nm-skeleton {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 16px; margin: 0 8px;
    }
    .nm-skeleton .skel-avatar {
        width: 48px; height: 48px; border-radius: 50%;
        background: linear-gradient(90deg, #1e1e2a 25%, #27272a 50%, #1e1e2a 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        flex-shrink: 0;
    }
    .nm-skeleton .skel-lines { flex: 1; }
    .nm-skeleton .skel-line {
        height: 12px; border-radius: 6px; margin-bottom: 8px;
        background: linear-gradient(90deg, #1e1e2a 25%, #27272a 50%, #1e1e2a 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }
    .nm-skeleton .skel-line:last-child { width: 60%; margin-bottom: 0; }
    @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

    .nm-empty-state {
        display: flex; flex-direction: column; align-items: center;
        justify-content: center; padding: 40px 20px; text-align: center;
    }
</style>

<div class="max-w-lg mx-auto h-screen flex flex-col" style="background: #090c15;">

    <!-- ===== HEADER ===== -->
    <div class="px-4 pt-3 pb-3 flex-shrink-0" style="border-bottom: 1px solid rgba(20,20,28,0.8);">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <a href="/" class="w-9 h-9 rounded-full bg-[#14141c] border border-[#1e1e2a] flex items-center justify-center hover:bg-[#1e1e2a] transition-colors">
                    <span class="material-icons-round text-zinc-300 text-lg">arrow_back</span>
                </a>
                <h1 class="font-display text-xl font-bold text-white">Messages</h1>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="openNewMessage()" class="w-9 h-9 rounded-full bg-[#14141c] border border-[#1e1e2a] flex items-center justify-center hover:bg-[#1e1e2a] transition-colors" title="New Message">
                    <span class="material-icons-round text-zinc-300 text-lg">edit_square</span>
                </button>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="relative">
            <span class="material-icons-round absolute left-3 top-1/2 -translate-y-1/2 text-zinc-600 text-lg">search</span>
            <input type="text" id="searchMessages" placeholder="Search conversations..." class="w-full bg-[#14141c] text-white pl-10 pr-10 py-2.5 rounded-xl border border-[#1e1e2a] focus:border-[#834ae5] focus:outline-none text-sm placeholder:text-zinc-600 transition-all" oninput="filterConversations(this.value)">
            <button onclick="this.previousElementSibling.value=''; filterConversations('');" class="absolute right-2 top-1/2 -translate-y-1/2 p-1 rounded-full hover:bg-[#1e1e2a] transition-colors hidden" id="clearSearchBtn">
                <span class="material-icons-round text-zinc-500 text-sm">close</span>
            </button>
        </div>
    </div>

    <!-- ===== FILTER TABS ===== -->
    <div class="px-4 py-2.5 flex items-center gap-2 overflow-x-auto scrollbar-hide flex-shrink-0" style="border-bottom: 1px solid rgba(20,20,28,0.5);">
        <button class="tab-btn active flex-shrink-0 px-4 py-1.5 rounded-full bg-[#14141c] text-zinc-400 text-xs font-semibold border border-[#1e1e2a]" onclick="switchTab(this, 'all')">All</button>
        <button class="tab-btn flex-shrink-0 px-4 py-1.5 rounded-full bg-[#14141c] text-zinc-400 text-xs font-semibold border border-[#1e1e2a]" onclick="switchTab(this, 'unread')">
            Unread
            <?php if (!empty($conversations)): ?>
            <?php $unreadTotal = array_sum(array_column($conversations, 'unread_count')); if ($unreadTotal > 0): ?>
            <span class="ml-1 min-w-[16px] h-4 px-1 rounded-full bg-red-500 text-white text-[9px] font-bold inline-flex items-center justify-center"><?= $unreadTotal ?></span>
            <?php endif; endif; ?>
        </button>
        <button class="tab-btn flex-shrink-0 px-4 py-1.5 rounded-full bg-[#14141c] text-zinc-400 text-xs font-semibold border border-[#1e1e2a]" onclick="switchTab(this, 'groups')">Groups</button>
        <button class="tab-btn flex-shrink-0 px-4 py-1.5 rounded-full bg-[#14141c] text-zinc-400 text-xs font-semibold border border-[#1e1e2a]" onclick="switchTab(this, 'requests')">Requests</button>
    </div>

    <!-- ===== ONLINE AVATARS ROW ===== -->
    <?php if (!empty($conversations)): ?>
    <div class="px-4 py-3 flex items-center gap-4 overflow-x-auto scrollbar-hide flex-shrink-0" style="border-bottom: 1px solid rgba(20,20,28,0.5);">
        <!-- New Chat -->
        <button onclick="openNewMessage()" class="flex flex-col items-center gap-1.5 flex-shrink-0">
            <div class="w-14 h-14 rounded-full bg-[#14141c] border-2 border-dashed border-zinc-700 flex items-center justify-center hover:border-[#834ae5] transition-colors">
                <span class="material-icons-round text-[#834ae5] text-xl">edit</span>
            </div>
            <span class="text-[9px] text-zinc-500 font-medium">New Chat</span>
        </button>
        <?php foreach (array_slice($conversations, 0, 8) as $conv):
            $isOnline = !empty($conv['is_online']);
        ?>
        <a href="/messages/<?= $conv['id'] ?>" class="flex flex-col items-center gap-1.5 flex-shrink-0">
            <div class="relative">
                <div class="w-14 h-14 rounded-full overflow-hidden" style="box-shadow: 0 0 0 2.5px rgba(34,197,94,0.4);">
                    <img src="<?= $conv['other_avatar'] ?? 'https://placehold.co/56x56/3f3f46/ffffff?text=U' ?>" alt="<?= htmlspecialchars($conv['other_name'] ?? '') ?>" class="w-full h-full object-cover">
                </div>
                <div class="online-dot <?= $isOnline ? 'online' : 'offline' ?>"></div>
            </div>
            <span class="text-[9px] text-zinc-500 font-medium truncate max-w-[52px]"><?= htmlspecialchars(explode(' ', $conv['other_name'])[0]) ?></span>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- ===== CONVERSATION LIST ===== -->
    <div class="flex-1 overflow-y-auto scrollbar-hide" id="conversationList">
        <?php if (!empty($user) && !empty($conversations)): ?>
        <div class="px-3 py-2">
            <?php foreach ($conversations as $conv):
                $isOnline = !empty($conv['is_online']);
                $isUnread = !empty($conv['unread_count']) && (int)$conv['unread_count'] > 0;
                $isMine = (int)$conv['last_sender_id'] === (int)$user['id'];
                $lastMsg = $conv['last_message'] ?? 'No messages yet';
                $lastTime = $conv['last_message_at'] ?? '';
            ?>
            <a href="/messages/<?= $conv['id'] ?>" class="conv-item flex items-center gap-3 p-3 rounded-xl mb-1 <?= $isUnread ? 'bg-[#0e0323]/50' : '' ?>" data-name="<?= htmlspecialchars(strtolower($conv['other_name'] ?? '')) ?>" data-unread="<?= $isUnread ? '1' : '0' ?>">
                <!-- Avatar -->
                <div class="relative flex-shrink-0">
                    <div class="w-12 h-12 rounded-full overflow-hidden" style="<?= $isUnread ? 'box-shadow: 0 0 0 2.5px rgba(131,74,229,0.5);' : '' ?>">
                        <img src="<?= $conv['other_avatar'] ?? 'https://placehold.co/48x48/3f3f46/ffffff?text=U' ?>" alt="<?= htmlspecialchars($conv['other_name'] ?? '') ?>" class="w-full h-full object-cover">
                    </div>
                    <div class="online-dot <?= $isOnline ? 'online' : 'offline' ?>"></div>
                    <?php if (!empty($conv['other_verified'])): ?>
                    <div class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-[#834ae5] rounded-full flex items-center justify-center">
                        <svg width="8" height="8" viewBox="0 0 24 24" fill="white"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold truncate <?= $isUnread ? 'text-white' : 'text-zinc-300' ?>"><?= htmlspecialchars($conv['other_name'] ?? 'Unknown') ?></span>
                        <span class="text-[10px] flex-shrink-0 ml-2 <?= $isUnread ? 'text-[#834ae5] font-semibold' : 'text-zinc-600' ?>"><?= $lastTime ? timeAgo($lastTime) : '' ?></span>
                    </div>
                    <div class="flex items-center justify-between mt-0.5">
                        <div class="flex items-center gap-1 min-w-0 flex-1">
                            <?php if ($isMine): ?>
                            <span class="material-icons-round text-[#834ae5] text-xs flex-shrink-0">done_all</span>
                            <?php endif; ?>
                            <p class="text-xs truncate <?= $isUnread ? 'text-zinc-300 font-medium' : 'text-zinc-500' ?>">
                                <?php if (!empty($conv['last_message_type']) && $conv['last_message_type'] === 'image'): ?>
                                <span class="material-icons-round text-zinc-500 text-[12px] align-middle mr-0.5">photo</span> Photo
                                <?php elseif (!empty($conv['last_message_type']) && $conv['last_message_type'] === 'voice'): ?>
                                <span class="material-icons-round text-zinc-500 text-[12px] align-middle mr-0.5">mic</span> Voice message
                                <?php else: ?>
                                <?php if ($isMine): ?><span class="text-zinc-500">You: </span><?php endif; ?>
                                <?= htmlspecialchars($lastMsg) ?>
                                <?php endif; ?>
                            </p>
                        </div>
                        <?php if ($isUnread): ?>
                        <span class="flex-shrink-0 min-w-[20px] h-5 px-1.5 rounded-full flex items-center justify-center text-white text-[10px] font-bold ml-2" style="background: linear-gradient(135deg, #834ae5, #6b21a8);"><?= $conv['unread_count'] ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($conv['typing']) && (bool)$conv['typing']): ?>
                    <div class="flex items-center gap-1 mt-0.5">
                        <div class="typing-indicator"><span></span><span></span><span></span></div>
                        <span class="text-[10px] text-[#834ae5]">typing...</span>
                    </div>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

        <?php elseif (!empty($user)): ?>
        <!-- Empty State -->
        <div class="flex items-center justify-center h-full px-6">
            <div class="text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full flex items-center justify-center" style="background: linear-gradient(135deg, rgba(131,74,229,0.15), rgba(20,20,28,0.9));">
                    <span class="material-icons-round text-[#834ae5] text-4xl">chat_bubble_outline</span>
                </div>
                <h3 class="text-white font-bold text-base mb-1">No conversations yet</h3>
                <p class="text-zinc-500 text-xs mb-5">Start chatting with creators and fans!</p>
                <button onclick="openNewMessage()" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full text-white text-sm font-semibold hover:opacity-90 transition-opacity" style="background: linear-gradient(135deg, #834ae5, #6b21a8); box-shadow: 0 4px 15px rgba(131,74,229,0.4);">
                    <span class="material-icons-round text-lg">edit</span>
                    New Message
                </button>
            </div>
        </div>

        <?php else: ?>
        <!-- Sign In Required -->
        <div class="flex items-center justify-center h-full px-6">
            <div class="text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full flex items-center justify-center" style="background: linear-gradient(135deg, rgba(131,74,229,0.15), rgba(20,20,28,0.9));">
                    <span class="material-icons-round text-[#834ae5] text-4xl">lock_outline</span>
                </div>
                <h3 class="text-white font-bold text-base mb-1">Sign in to message</h3>
                <p class="text-zinc-500 text-xs mb-5">Connect with creators and fans</p>
                <a href="/login" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full text-white text-sm font-semibold hover:opacity-90 transition-opacity" style="background: linear-gradient(135deg, #834ae5, #6b21a8); box-shadow: 0 4px 15px rgba(131,74,229,0.4);">
                    <span class="material-icons-round text-lg">login</span>
                    Sign In
                </a>
            </div>
        </div>
        <?php endif; ?>

        <!-- Bottom spacer for nav -->
        <div class="h-16"></div>
    </div>

    <!-- ===== FLOATING NEW CHAT BUTTON ===== -->
    <button onclick="openNewMessage()" class="new-chat-fab fixed bottom-20 right-4 z-40 w-14 h-14 rounded-full flex items-center justify-center shadow-lg max-w-lg" style="background: linear-gradient(135deg, #834ae5, #6b21a8); box-shadow: 0 4px 20px rgba(131,74,229,0.4);">
        <span class="material-icons-round text-white text-2xl">edit</span>
    </button>
</div>

<!-- ===== NEW MESSAGE MODAL (Facebook-style) ===== -->
<div class="nm-overlay" id="nmOverlay" onclick="closeNewMessageOutside(event)">
    <div class="nm-panel" onclick="event.stopPropagation()">
        <!-- Mobile handle bar -->
        <div class="nm-handle sm:hidden"></div>

        <!-- Header -->
        <div class="flex items-center justify-between px-4 pt-4 pb-2 flex-shrink-0">
            <h2 class="text-lg font-bold text-white">New Message</h2>
            <button onclick="closeNewMessage()" class="w-8 h-8 rounded-full bg-[#14141c] border border-[#1e1e2a] flex items-center justify-center hover:bg-[#1e1e2a] transition-colors">
                <span class="material-icons-round text-zinc-400 text-lg">close</span>
            </button>
        </div>

        <!-- To: Search Field -->
        <div class="px-4 pb-3 flex-shrink-0">
            <div class="relative">
                <span class="material-icons-round absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-500 text-lg">search</span>
                <input
                    type="text"
                    id="nmSearchInput"
                    class="nm-search-input"
                    placeholder="Search by name or username..."
                    autocomplete="off"
                    oninput="handleNmSearch(this.value)"
                    onkeydown="handleNmKeydown(event)"
                >
                <button id="nmClearBtn" onclick="clearNmSearch()" class="absolute right-3 top-1/2 -translate-y-1/2 p-0.5 rounded-full hover:bg-[#1e1e2a] transition-colors hidden">
                    <span class="material-icons-round text-zinc-500 text-base">cancel</span>
                </button>
            </div>
        </div>

        <!-- Divider -->
        <div class="mx-4 mb-1 flex-shrink-0" style="height: 1px; background: rgba(255,255,255,0.04);"></div>

        <!-- Results Area -->
        <div class="flex-1 overflow-y-auto scrollbar-hide pb-6" id="nmResults">
            <!-- Initial state: suggestions or recent -->
            <div id="nmInitialState">
                <div class="px-4 pt-2 pb-1">
                    <p class="text-[11px] font-semibold text-zinc-600 uppercase tracking-wider">Suggested</p>
                </div>
                <div id="nmSuggestions"></div>
            </div>

            <!-- Search results (hidden initially) -->
            <div id="nmSearchResults" class="hidden"></div>

            <!-- Loading skeletons -->
            <div id="nmLoading" class="hidden">
                <div class="nm-skeleton"><div class="skel-avatar"></div><div class="skel-lines"><div class="skel-line"></div><div class="skel-line"></div></div></div>
                <div class="nm-skeleton"><div class="skel-avatar"></div><div class="skel-lines"><div class="skel-line"></div><div class="skel-line"></div></div></div>
                <div class="nm-skeleton"><div class="skel-avatar"></div><div class="skel-lines"><div class="skel-line"></div><div class="skel-line"></div></div></div>
            </div>

            <!-- No results -->
            <div id="nmNoResults" class="hidden nm-empty-state">
                <span class="material-icons-round text-zinc-700 text-4xl mb-3">person_search</span>
                <p class="text-zinc-500 text-sm font-medium">No one found</p>
                <p class="text-zinc-600 text-xs mt-1">Try a different name or username</p>
            </div>
        </div>
    </div>
</div>

<script>
let nmSearchTimer = null;
let nmCreatingConversation = false;

// ===== Open / Close Modal =====
function openNewMessage() {
    const overlay = document.getElementById('nmOverlay');
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
    const input = document.getElementById('nmSearchInput');
    setTimeout(() => input.focus(), 300);
    loadSuggestions();
}

function closeNewMessage() {
    const overlay = document.getElementById('nmOverlay');
    overlay.classList.remove('open');
    document.body.style.overflow = '';
    document.getElementById('nmSearchInput').value = '';
    document.getElementById('nmClearBtn').classList.add('hidden');
    document.getElementById('nmSearchResults').classList.add('hidden');
    document.getElementById('nmInitialState').classList.remove('hidden');
    document.getElementById('nmNoResults').classList.add('hidden');
    document.getElementById('nmLoading').classList.add('hidden');
}

function closeNewMessageOutside(e) {
    if (e.target === e.currentTarget) closeNewMessage();
}

// ===== Search Logic =====
function handleNmSearch(query) {
    const clearBtn = document.getElementById('nmClearBtn');
    clearBtn.classList.toggle('hidden', !query.trim());

    clearTimeout(nmSearchTimer);

    if (!query.trim()) {
        document.getElementById('nmSearchResults').classList.add('hidden');
        document.getElementById('nmInitialState').classList.remove('hidden');
        document.getElementById('nmNoResults').classList.add('hidden');
        document.getElementById('nmLoading').classList.add('hidden');
        return;
    }

    // Show loading
    document.getElementById('nmInitialState').classList.add('hidden');
    document.getElementById('nmSearchResults').classList.add('hidden');
    document.getElementById('nmNoResults').classList.add('hidden');
    document.getElementById('nmLoading').classList.remove('hidden');

    // Debounce API call
    nmSearchTimer = setTimeout(() => {
        fetch('/messages/search?q=' + encodeURIComponent(query.trim()))
            .then(r => r.json())
            .then(users => {
                document.getElementById('nmLoading').classList.add('hidden');
                renderSearchResults(users);
            })
            .catch(() => {
                document.getElementById('nmLoading').classList.add('hidden');
                document.getElementById('nmNoResults').classList.remove('hidden');
            });
    }, 300);
}

function clearNmSearch() {
    const input = document.getElementById('nmSearchInput');
    input.value = '';
    input.focus();
    document.getElementById('nmClearBtn').classList.add('hidden');
    document.getElementById('nmSearchResults').classList.add('hidden');
    document.getElementById('nmInitialState').classList.remove('hidden');
    document.getElementById('nmNoResults').classList.add('hidden');
}

// ===== Render Results =====
function renderSearchResults(users) {
    const container = document.getElementById('nmSearchResults');
    const noResults = document.getElementById('nmNoResults');

    if (!users || users.length === 0) {
        container.classList.add('hidden');
        noResults.classList.remove('hidden');
        return;
    }

    noResults.classList.add('hidden');
    container.classList.remove('hidden');
    container.innerHTML = '<div class="px-4 pt-2 pb-1"><p class="text-[11px] font-semibold text-zinc-600 uppercase tracking-wider">Results</p></div>';

    users.forEach(user => {
        container.innerHTML += createUserItemHTML(user);
    });
}

function createUserItemHTML(user) {
    const avatar = user.avatar || 'https://placehold.co/48x48/3f3f46/ffffff?text=' + (user.name || 'U').charAt(0);
    const verified = user.is_verified ? '<div class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-[#834ae5] rounded-full flex items-center justify-center"><svg width="8" height="8" viewBox="0 0 24 24" fill="white"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg></div>' : '';
    const bio = user.bio ? '<p class="text-[11px] text-zinc-500 truncate">' + escHTML(user.bio) + '</p>' : '';
    const followBadge = user.is_following > 0 ? '<span class="text-[10px] font-medium px-2 py-0.5 rounded-full border" style="color: #834ae5; border-color: rgba(131,74,229,0.3);">Following</span>' : '';

    return `
        <div class="nm-user-item" onclick="startChatWithUser(${user.id})">
            <div class="relative flex-shrink-0">
                <div class="w-12 h-12 rounded-full overflow-hidden bg-[#1e1e2a]">
                    <img src="${escHTML(avatar)}" alt="${escHTML(user.name)}" class="w-full h-full object-cover" onerror="this.style.display='none'">
                </div>
                ${verified}
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-white truncate">${escHTML(user.name)}</span>
                    ${followBadge}
                </div>
                <p class="text-xs text-zinc-500 truncate">@${escHTML(user.username)}</p>
                ${bio}
            </div>
            <span class="material-icons-round text-zinc-700 text-lg flex-shrink-0">chevron_right</span>
        </div>
    `;
}

function escHTML(str) {
    if (!str) return '';
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

// ===== Start Chat =====
function startChatWithUser(userId) {
    if (nmCreatingConversation) return;
    nmCreatingConversation = true;

    // Show a quick loading state on the clicked item
    const items = document.querySelectorAll('.nm-user-item');
    items.forEach(item => { item.style.pointerEvents = 'none'; item.style.opacity = '0.5'; });

    fetch('/messages/create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ user_id: userId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.conversation_id) {
            window.location.href = '/messages/' + data.conversation_id;
        } else if (data.error) {
            showToast(data.error);
            nmCreatingConversation = false;
            items.forEach(item => { item.style.pointerEvents = ''; item.style.opacity = '1'; });
        }
    })
    .catch(() => {
        showToast('Could not start conversation');
        nmCreatingConversation = false;
        items.forEach(item => { item.style.pointerEvents = ''; item.style.opacity = '1'; });
    });
}

// ===== Suggestions (load users you follow or recent) =====
function loadSuggestions() {
    const container = document.getElementById('nmSuggestions');
    // Show loading
    container.innerHTML = `
        <div class="nm-skeleton"><div class="skel-avatar"></div><div class="skel-lines"><div class="skel-line"></div><div class="skel-line"></div></div></div>
        <div class="nm-skeleton"><div class="skel-avatar"></div><div class="skel-lines"><div class="skel-line"></div><div class="skel-line"></div></div></div>
    `;

    fetch('/messages/search?q=')
        .then(r => r.json())
        .then(users => {
            if (users && users.length > 0) {
                container.innerHTML = '';
                users.slice(0, 6).forEach(user => {
                    container.innerHTML += createUserItemHTML(user);
                });
            } else {
                container.innerHTML = `
                    <div class="px-4 py-6 text-center">
                        <span class="material-icons-round text-zinc-700 text-3xl mb-2 block">people_outline</span>
                        <p class="text-zinc-500 text-xs">Search for someone to message</p>
                    </div>
                `;
            }
        })
        .catch(() => {
            container.innerHTML = `
                <div class="px-4 py-6 text-center">
                    <span class="material-icons-round text-zinc-700 text-3xl mb-2 block">people_outline</span>
                    <p class="text-zinc-500 text-xs">Search for someone to message</p>
                </div>
            `;
        });
}

// ===== Keyboard Navigation =====
function handleNmKeydown(e) {
    if (e.key === 'Escape') {
        closeNewMessage();
    }
    // Navigate results with arrow keys
    if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
        e.preventDefault();
        const items = document.querySelectorAll('.nm-user-item');
        if (items.length === 0) return;

        const current = document.querySelector('.nm-user-item.focused');
        let idx = -1;
        items.forEach((item, i) => { if (item === current) idx = i; });

        if (current) current.classList.remove('focused');

        if (e.key === 'ArrowDown') {
            idx = (idx + 1) % items.length;
        } else {
            idx = idx <= 0 ? items.length - 1 : idx - 1;
        }

        items[idx].classList.add('focused');
        items[idx].scrollIntoView({ block: 'nearest' });
    }

    if (e.key === 'Enter') {
        const focused = document.querySelector('.nm-user-item.focused');
        if (focused) focused.click();
    }
}

// Close on Escape key globally
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('nmOverlay').classList.contains('open')) {
        closeNewMessage();
    }
});

// ===== Existing Functions =====
function timeAgo(dateStr) {
    if (!dateStr) return '';
    const now = new Date();
    const date = new Date(dateStr.replace(/-/g, '/'));
    const diff = Math.floor((now - date) / 1000);
    if (diff < 60) return 'now';
    if (diff < 3600) return Math.floor(diff / 60) + 'm';
    if (diff < 86400) return Math.floor(diff / 3600) + 'h';
    if (diff < 604800) return Math.floor(diff / 86400) + 'd';
    if (diff < 2592000) return Math.floor(diff / 604800) + 'w';
    return Math.floor(diff / 2592000) + 'mo';
}

function switchTab(btn, tab) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const items = document.querySelectorAll('.conv-item');
    items.forEach(item => {
        if (tab === 'all') {
            item.style.display = '';
        } else if (tab === 'unread') {
            item.style.display = item.dataset.unread === '1' ? '' : 'none';
        } else if (tab === 'groups') {
            item.style.display = 'none';
        } else if (tab === 'requests') {
            item.style.display = 'none';
        }
    });
}

function filterConversations(query) {
    const q = query.toLowerCase().trim();
    const items = document.querySelectorAll('.conv-item');
    const clearBtn = document.getElementById('clearSearchBtn');
    clearBtn.classList.toggle('hidden', !q);

    items.forEach(item => {
        if (!q) { item.style.display = ''; return; }
        const name = item.dataset.name || '';
        item.style.display = name.includes(q) ? '' : 'none';
    });
}

function showToast(msg) {
    const existing = document.querySelector('.msg-toast');
    if (existing) existing.remove();
    const div = document.createElement('div');
    div.className = 'msg-toast fixed top-16 left-1/2 -translate-x-1/2 px-5 py-2.5 rounded-full text-white text-sm font-medium z-[200]';
    div.style.cssText = 'background: linear-gradient(135deg, #834ae5, #6b21a8); box-shadow: 0 4px 20px rgba(131,74,229,0.4);';
    div.textContent = msg;
    document.body.appendChild(div);
    setTimeout(() => { div.style.opacity = '0'; div.style.transition = 'opacity 0.3s'; setTimeout(() => div.remove(), 300); }, 2000);
}
</script>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>