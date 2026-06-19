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
                <button onclick="document.getElementById('searchMessages').focus();" class="w-9 h-9 rounded-full bg-[#14141c] border border-[#1e1e2a] flex items-center justify-center hover:bg-[#1e1e2a] transition-colors">
                    <span class="material-icons-round text-zinc-300 text-lg">search</span>
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
        <!-- New Story / New Chat -->
        <button onclick="newConversation()" class="flex flex-col items-center gap-1.5 flex-shrink-0">
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
                <button onclick="newConversation()" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full text-white text-sm font-semibold hover:opacity-90 transition-opacity" style="background: linear-gradient(135deg, #834ae5, #6b21a8); box-shadow: 0 4px 15px rgba(131,74,229,0.4);">
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
    <button onclick="newConversation()" class="new-chat-fab fixed bottom-20 right-4 z-40 w-14 h-14 rounded-full flex items-center justify-center shadow-lg max-w-lg" style="background: linear-gradient(135deg, #834ae5, #6b21a8); box-shadow: 0 4px 20px rgba(131,74,229,0.4);">
        <span class="material-icons-round text-white text-2xl">edit</span>
    </button>
</div>

<script>
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
            item.style.display = 'none'; // No groups yet
        } else if (tab === 'requests') {
            item.style.display = 'none'; // No requests yet
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

function newConversation() {
    const username = prompt('Enter the username to message:');
    if (username && username.trim()) {
        fetch('/messages/create', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ username: username.trim() })
        })
        .then(r => r.json())
        .then(data => {
            if (data.conversation_id) {
                window.location.href = '/messages/' + data.conversation_id;
            } else if (data.error) {
                showToast(data.error);
            }
        })
        .catch(() => showToast('Could not create conversation'));
    }
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
