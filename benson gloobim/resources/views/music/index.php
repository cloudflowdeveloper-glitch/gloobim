<?php $activeTab = 'music'; $title = 'Music - GLOOBIM'; $hideTopNav = true; ?>
<?php ob_start(); ?>
<style>
    @keyframes visualizer { 0%,100% { transform: scaleY(0.3); } 50% { transform: scaleY(1); } }
    .viz-bar { animation: visualizer 1.2s ease-in-out infinite; transform-origin: bottom; }
    .viz-bar:nth-child(1) { animation-delay: 0s; height: 12px; }
    .viz-bar:nth-child(2) { animation-delay: 0.15s; height: 20px; }
    .viz-bar:nth-child(3) { animation-delay: 0.3s; height: 8px; }
    .viz-bar:nth-child(4) { animation-delay: 0.45s; height: 16px; }
    .viz-bar:nth-child(5) { animation-delay: 0.6s; height: 10px; }

    .scroll-snap-x { scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; }
    .scroll-snap-x > * { scroll-snap-align: start; }

    .card-hover { transition: all 0.3s cubic-bezier(0.4,0,0.2,1); }
    .card-hover:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.4); }

    .now-playing-bar { background: rgba(10,9,23,0.96); backdrop-filter: blur(24px); border-top: 1px solid rgba(131,74,229,0.2); }

    .category-pill { transition: all 0.25s ease; }
    .category-pill:hover { background: rgba(131,74,229,0.15); color: #c084fc; }
    .category-pill.active { background: linear-gradient(135deg, #834ae5, #6b21a8); color: white; box-shadow: 0 4px 15px rgba(131,74,229,0.4); }

    @keyframes pulse-glow { 0%,100% { box-shadow: 0 0 20px rgba(131,74,229,0.4); } 50% { box-shadow: 0 0 40px rgba(131,74,229,0.7); } }
    .glow-pulse { animation: pulse-glow 2s ease-in-out infinite; }

    .genre-card { transition: all 0.3s ease; }
    .genre-card:hover { transform: scale(1.03); }

    .chart-number { font-feature-settings: "tnum"; }

    @keyframes spin-slow { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    .spin-slow { animation: spin-slow 8s linear infinite; }

    @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-100%); } }
    .marquee-text { overflow: hidden; white-space: nowrap; }
    .marquee-text > span { display: inline-block; animation: marquee 10s linear infinite; padding-left: 100%; }

    @keyframes float-up { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
    .float-anim { animation: float-up 3s ease-in-out infinite; }

    @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
    .shimmer { background: linear-gradient(90deg, transparent 25%, rgba(255,255,255,0.05) 50%, transparent 75%); background-size: 200% 100%; animation: shimmer 3s infinite; }

    .track-row { transition: all 0.2s ease; }
    .track-row:hover { background: rgba(131,74,229,0.06); }
    .track-row.playing { background: rgba(131,74,229,0.1); }
    .track-row.playing .track-num { opacity: 0; }
    .track-row.playing .track-eq { display: flex; }
    .track-eq { display: none; }

    .artist-card { transition: all 0.3s ease; }
    .artist-card:hover { transform: translateY(-4px); }
    .artist-card:hover .artist-ring { box-shadow: 0 0 0 3px rgba(131,74,229,0.6); }

    .video-thumb { position: relative; overflow: hidden; }
    .video-thumb::after { content: ''; position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.6) 0%, transparent 50%); pointer-events: none; }

    .playlist-card { transition: all 0.3s ease; }
    .playlist-card:hover { transform: translateY(-3px); }

    .bottom-sheet { transition: transform 0.35s cubic-bezier(0.4,0,0.2,1); }
    .bottom-sheet.hidden { transform: translateY(100%); }

    /* Custom scrollbar for track list */
    .track-list::-webkit-scrollbar { width: 4px; }
    .track-list::-webkit-scrollbar-track { background: transparent; }
    .track-list::-webkit-scrollbar-thumb { background: rgba(131,74,229,0.3); border-radius: 4px; }
</style>

<div class="max-w-lg mx-auto pb-4">
    <!-- Back Arrow Header -->
    <div class="px-4 pt-3 pb-1">
        <div class="flex items-center justify-between mb-3">
            <a href="/" class="w-9 h-9 rounded-full bg-[#14141c] flex items-center justify-center hover:bg-[#1e1e2a] transition-colors border border-[#1e1e2a]">
                <span class="material-icons-round text-zinc-300 text-lg">arrow_back</span>
            </a>
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #834ae5, #6b21a8);">
                    <span class="material-icons-round text-white text-lg">music_note</span>
                </div>
                <h1 class="font-display text-xl font-bold tracking-tight">
                    <span class="text-white">GLO</span><span class="gradient-text">OB</span><span class="text-white">IM Music</span>
                </h1>
            </div>
            <div class="flex items-center gap-2">
                <button class="w-9 h-9 rounded-full bg-[#14141c] flex items-center justify-center hover:bg-[#1e1e2a] transition-colors relative border border-[#1e1e2a]" onclick="location.href='/notifications'">
                    <span class="material-icons-round text-zinc-300 text-[20px]">notifications_none</span>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full" id="notifDot"></span>
                </button>
                <a href="/profile" class="w-8 h-8 rounded-full overflow-hidden" style="box-shadow: 0 0 0 2px rgba(131,74,229,0.5);">
                    <img src="https://placehold.co/80x80/6d28d9/ffffff?text=U" alt="Profile" class="w-full h-full object-cover">
                </a>
            </div>
        </div>

        <!-- Search + AI Button -->
        <div class="flex items-center gap-2 mb-4">
            <div class="relative flex-1">
                <span class="material-icons-round absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-600 text-lg">search</span>
                <input type="text" id="musicSearch" placeholder="Search songs, artists, albums..." class="w-full bg-[#14141c] text-white pl-10 pr-4 py-2.5 rounded-xl border border-[#1e1e2a] focus:border-[#834ae5] focus:outline-none text-sm placeholder:text-zinc-600 transition-all" autocomplete="off">
                <!-- Search Results Dropdown -->
                <div id="searchResults" class="hidden absolute top-full left-0 right-0 mt-1 bg-[#14141c] border border-[#1e1e2a] rounded-xl overflow-hidden z-50 max-h-80 overflow-y-auto shadow-2xl"></div>
            </div>
            <button class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 hover:opacity-90 transition-opacity" style="background: linear-gradient(135deg, #834ae5, #ec4899); box-shadow: 0 4px 15px rgba(131,74,229,0.3);">
                <div class="flex items-end gap-0.5 h-full py-2.5">
                    <div class="viz-bar w-1 bg-white rounded-full"></div>
                    <div class="viz-bar w-1 bg-white rounded-full"></div>
                    <div class="viz-bar w-1 bg-white rounded-full"></div>
                    <div class="viz-bar w-1 bg-white rounded-full"></div>
                    <div class="viz-bar w-1 bg-white rounded-full"></div>
                </div>
            </button>
        </div>

<!-- Quick Actions -->
        <!-- Category Pills -->
        <div class="flex gap-2 overflow-x-auto scrollbar-hide pb-1 scroll-snap-x mt-3">
            <?php $categories = ['All', 'Trending', 'Afrobeats', 'Bongo Flava', 'Hip Hop', 'Amapiano', 'Gospel', 'New']; ?>
            <?php foreach ($categories as $idx => $cat): ?>
            <button class="category-pill <?= $idx === 0 ? 'active' : '' ?> flex-shrink-0 px-4 py-2 rounded-full bg-[#14141c] text-zinc-400 text-xs font-medium border border-[#1e1e2a]" onclick="selectCategory(this)"><?= $cat ?></button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Featured Hero Banner -->
    <?php $feat = $featured ?? ['title' => 'African Heat 2025', 'description' => 'Hottest African tracks right now', 'cover_url' => '/uploads/music/covers/featured_banner.jpg', 'author' => 'DTTube Music', 'listeners' => 12400]; ?>
    <div class="px-4 py-3">
        <div class="relative rounded-2xl overflow-hidden card-hover" style="box-shadow: 0 0 40px rgba(131,74,229,0.15);">
            <img src="<?= $feat['cover_url'] ?>" alt="<?= $feat['title'] ?>" class="w-full aspect-[2/1] object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
            <div class="absolute inset-0" style="background: linear-gradient(135deg, rgba(131,74,229,0.25), transparent);"></div>
            <div class="absolute bottom-0 left-0 right-0 p-4">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-[9px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-full" style="background: linear-gradient(135deg, rgba(131,74,229,0.6), rgba(236,72,153,0.4)); color: white;">Featured</span>
                    <span class="text-[10px] text-zinc-400"><?= $feat['author'] ?? 'DTTube Music' ?></span>
                </div>
                <h2 class="font-display text-xl font-bold text-white leading-tight"><?= $feat['title'] ?></h2>
                <p class="text-zinc-300 text-xs mt-0.5"><?= $feat['description'] ?></p>
                <div class="flex items-center justify-between mt-3">
                    <div class="flex items-center gap-3">
                        <button onclick="playTrack(0)" class="px-5 py-2.5 rounded-full text-white text-xs font-bold shadow-lg hover:opacity-90 transition-opacity glow-pulse flex items-center gap-1.5" style="background: linear-gradient(135deg, #834ae5, #4f09f5);">
                            <span class="material-icons-round text-[18px]">play_arrow</span>
                            Listen Now
                        </button>
                        <div class="flex items-center gap-1.5">
                            <span class="material-icons-round text-[16px] text-zinc-400">headphones</span>
                            <span class="text-[11px] font-medium text-zinc-400"><?= number_format($feat['listeners'] ?? 12400, 1) ?>K</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="shareTrack(<?= $np['id'] ?? 0 ?>, this)" class="w-9 h-9 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center hover:bg-white/20 transition-colors">
                            <span class="material-icons-round text-white text-lg">share</span>
                        </button>
                        <button onclick="likeTrack(<?= $np['id'] ?? 0 ?>, this)" class="w-9 h-9 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center hover:bg-white/20 transition-colors">
                            <span class="material-icons-round text-white text-lg">favorite_border</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pagination Dots -->
        <div class="flex items-center justify-center gap-1.5 mt-3">
            <span class="w-6 h-1.5 rounded-full" style="background: #834ae5;"></span>
            <span class="w-1.5 h-1.5 rounded-full bg-zinc-700"></span>
            <span class="w-1.5 h-1.5 rounded-full bg-zinc-700"></span>
        </div>
    </div>

    <!-- Top Artists -->
    <?php if (!empty($top_artists)): ?>
    <div class="px-4 py-2">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-white text-sm font-bold">Top Artists</h2>
            <a href="/music" class="text-[11px] font-semibold" style="color: #834ae5;">See all</a>
        </div>
        <div class="flex gap-4 overflow-x-auto scrollbar-hide pb-1 scroll-snap-x">
            <?php foreach ($top_artists as $i => $artist): ?>
            <a href="/music" class="flex-shrink-0 flex flex-col items-center gap-1.5 artist-card no-underline">
                <div class="artist-ring w-16 h-16 rounded-full overflow-hidden transition-all" style="padding: 2.5px; background: linear-gradient(135deg, #9333ea, #ec4899, #f59e0b);">
                    <img src="<?= $artist['artist_avatar'] ?>" alt="<?= $artist['artist_name'] ?>" class="w-full h-full rounded-full object-cover border-2 border-[#090c15]">
                </div>
                <span class="text-white text-[11px] font-semibold truncate max-w-[70px] text-center"><?= $artist['artist_name'] ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Browse by Genre -->
    <div class="px-4 py-2">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-white text-sm font-bold"><?= !empty($genre_filter) ? '🎵 ' . htmlspecialchars($genre_filter) . ' Tracks' : 'Browse by Genre' ?></h2>
            <?php if (!empty($genre_filter)): ?>
            <a href="/music" class="text-[11px] font-semibold" style="color: #834ae5;">← All genres</a>
            <?php else: ?>
            <a href="/music/playlist/create" class="text-[11px] font-semibold" style="color: #834ae5;">+ Playlist</a>
            <?php endif; ?>
        </div>
        <div class="grid grid-cols-2 gap-2">
            <?php foreach ($genres as $i => $genre): ?>
            <a href="/music/genre/<?= $genre['slug'] ?>" class="genre-card relative rounded-xl overflow-hidden cursor-pointer no-underline block" style="background: linear-gradient(135deg, <?= $genre['color'] ?? '#834ae5' ?>, rgba(0,0,0,0.4)); min-height: 68px;">
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="shimmer absolute inset-0"></div>
                <div class="relative flex items-center justify-between p-3">
                    <div>
                        <span class="text-white text-xs font-bold block"><?= $genre['name'] ?? 'Genre' ?></span>
                        <span class="text-white/50 text-[9px]"><?= number_format($genre['track_count'] ?? 0) ?> tracks</span>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center">
                        <span class="material-icons-round text-white text-lg"><?= $genre['icon'] ?? 'music_note' ?></span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Music Videos Section -->
    <?php
    $videoTracks = array_filter($trending ?? [], function($t) { return !empty($t['video_url']); });
    $videoTracks = array_slice($videoTracks, 0, 4);
    ?>
    <?php if (count($videoTracks) > 0): ?>
    <div class="px-4 py-3">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <h2 class="text-white text-sm font-bold">Music Videos</h2>
                <span class="material-icons-round text-[16px]" style="color: #834ae5;">play_circle</span>
            </div>
            <a href="/music" class="text-[11px] font-semibold" style="color: #834ae5;">See all</a>
        </div>
        <div class="flex gap-3 overflow-x-auto scrollbar-hide pb-1 scroll-snap-x">
            <?php foreach ($videoTracks as $i => $track): ?>
            <a href="/music/track/<?= $track['id'] ?? '' ?>" class="flex-shrink-0 w-44 cursor-pointer group no-underline">
                <div class="video-thumb relative rounded-xl overflow-hidden mb-2 card-hover">
                    <img src="<?= $track['cover_url'] ?>" alt="<?= $track['title'] ?>" class="w-full aspect-[3/4] object-cover">
                    <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center z-10">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #834ae5, #4f09f5);">
                            <span class="material-icons-round text-white text-2xl">play_arrow</span>
                        </div>
                    </div>
                    <div class="absolute bottom-2 right-2 bg-black/70 backdrop-blur-sm rounded-md px-1.5 py-0.5 z-10">
                        <span class="text-white text-[10px] font-medium"><?= floor($track['duration']/60) ?>:<?= str_pad($track['duration']%60, 2, '0', STR_PAD_LEFT) ?></span>
                    </div>
                    <div class="absolute top-2 left-2 flex items-center gap-1 bg-black/60 backdrop-blur-sm rounded-md px-1.5 py-0.5 z-10">
                        <span class="material-icons-round text-[12px]" style="color: #ec4899;">play_circle</span>
                        <span class="text-[9px] text-white font-medium">VIDEO</span>
                    </div>
                </div>
                <span class="text-white text-[11px] font-semibold truncate block"><?= $track['title'] ?></span>
                <div class="flex items-center gap-1 mt-0.5">
                    <img src="<?= $track['artist_avatar'] ?>" alt="" class="w-4 h-4 rounded-full">
                    <span class="text-zinc-500 text-[9px] truncate"><?= $track['artist_name'] ?></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Now Playing Featured Track -->
    <?php $np = ($trending[0] ?? $recent[0] ?? null); ?>
    <?php if ($np): ?>
    <div class="px-4 py-2">
        <a href="/music/track/<?= $np['id'] ?>" class="relative rounded-2xl overflow-hidden block no-underline" style="background: linear-gradient(135deg, rgba(131,74,229,0.12), rgba(107,33,168,0.04)); border: 1px solid rgba(131,74,229,0.12);">
            <div class="flex items-center gap-4 p-4">
                <div class="relative flex-shrink-0">
                    <div class="w-20 h-20 rounded-2xl overflow-hidden glow-pulse shadow-lg">
                        <img src="<?= $np['cover_url'] ?>" alt="Now Playing" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full flex items-center justify-center" style="background: linear-gradient(135deg, #834ae5, #4f09f5);">
                        <span class="material-icons-round text-white text-xs" id="heroPlayIcon">pause</span>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1.5">
                        <span class="text-[9px] font-bold uppercase tracking-widest" style="color: #c084fc;">Now Playing</span>
                        <div class="flex items-center gap-0.5">
                            <div class="w-1 h-2 bg-[#834ae5] rounded-full animate-pulse"></div>
                            <div class="w-1 h-3 bg-[#9333ea] rounded-full animate-pulse" style="animation-delay: 0.15s;"></div>
                            <div class="w-1 h-2 bg-[#c084fc] rounded-full animate-pulse" style="animation-delay: 0.3s;"></div>
                        </div>
                    </div>
                    <h3 class="text-white text-base font-bold truncate mt-0.5"><?= $np['title'] ?></h3>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <img src="<?= $np['artist_avatar'] ?>" alt="" class="w-4 h-4 rounded-full">
                        <span class="text-zinc-400 text-xs truncate"><?= $np['artist_name'] ?></span>
                        <?php if (!empty($np['is_verified'])): ?>
                        <span class="material-icons-round text-[12px]" style="color: #834ae5;">verified</span>
                        <?php endif; ?>
                    </div>
                    <div class="flex items-center gap-3 mt-2">
                        <div class="flex-1 h-1 rounded-full bg-[#1e1e2a] overflow-hidden cursor-pointer" onclick="seekTrack(event)">
                            <div class="h-full rounded-full transition-all" id="heroProgress" style="width: 35%; background: linear-gradient(90deg, #834ae5, #ec4899);"></div>
                        </div>
                        <span class="text-zinc-500 text-[10px] font-medium" id="heroTime">1:24</span>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <?php endif; ?>

    <!-- Trending Songs Chart -->
    <div class="px-4 py-2">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <h2 class="text-white text-sm font-bold">Trending Songs</h2>
                <span class="material-icons-round text-[16px]" style="color: #834ae5;">trending_up</span>
            </div>
            <a href="/music" class="text-[11px] font-semibold" style="color: #834ae5;">See all</a>
        </div>
        <div class="rounded-2xl overflow-hidden track-list-container" style="background: rgba(20,20,28,0.4); border: 1px solid rgba(30,30,42,0.5);">
            <?php foreach ($trending as $i => $track): ?>
            <div class="track-row flex items-center gap-3 px-3 py-2.5 cursor-pointer <?= $i < count($trending) - 1 ? 'border-b border-[#14141c]/60' : '' ?>" onclick="location.href='/music/track/<?= $track['id'] ?>'" data-index="<?= $i ?>" id="trackRow<?= $i ?>">
                <!-- Track number / EQ animation -->
                <div class="relative w-6 flex-shrink-0">
                    <span class="chart-number track-num text-sm font-bold <?= $i < 3 ? '' : 'text-zinc-600' ?>" <?= $i < 3 ? 'style="color: #834ae5;"' : '' ?>><?= $i + 1 ?></span>
                    <div class="track-eq absolute inset-0 items-end gap-0.5 justify-center">
                        <div class="w-0.5 bg-[#834ae5] rounded-full" style="height: 6px; animation: visualizer 0.8s ease-in-out infinite; animation-delay: 0s;"></div>
                        <div class="w-0.5 bg-[#c084fc] rounded-full" style="height: 10px; animation: visualizer 0.8s ease-in-out infinite; animation-delay: 0.2s;"></div>
                        <div class="w-0.5 bg-[#834ae5] rounded-full" style="height: 4px; animation: visualizer 0.8s ease-in-out infinite; animation-delay: 0.4s;"></div>
                    </div>
                </div>

                <!-- Cover art -->
                <div class="relative flex-shrink-0">
                    <img src="<?= $track['cover_url'] ?>" alt="<?= $track['title'] ?>" class="w-11 h-11 rounded-xl object-cover">
                </div>

                <!-- Title & Artist -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1">
                        <span class="text-white text-xs font-semibold truncate"><?= $track['title'] ?></span>
                        <?php if (!empty($track['is_explicit'])): ?>
                        <span class="text-[8px] font-bold bg-zinc-700 text-zinc-300 px-1 rounded">E</span>
                        <?php endif; ?>
                        <?php if (!empty($track['is_verified'])): ?>
                        <span class="material-icons-round text-[12px] flex-shrink-0" style="color: #834ae5;">verified</span>
                        <?php endif; ?>
                    </div>
                    <div class="flex items-center gap-1 mt-0.5">
                        <img src="<?= $track['artist_avatar'] ?>" alt="" class="w-3.5 h-3.5 rounded-full">
                        <span class="text-zinc-500 text-[10px] truncate"><?= $track['artist_name'] ?></span>
                    </div>
                </div>

                <!-- Plays & Duration -->
                <div class="flex items-center gap-2 flex-shrink-0">
                    <?php if (!empty($track['video_url'])): ?>
                    <span class="material-icons-round text-[14px] text-zinc-600">play_circle</span>
                    <?php endif; ?>
                    <div class="text-right">
                        <span class="text-zinc-600 text-[10px] block leading-none"><?= number_format($track['plays'] / 1000, 0) ?>K</span>
                        <span class="text-zinc-700 text-[9px]"><?= floor($track['duration'] / 60) ?>:<?= str_pad($track['duration'] % 60, 2, '0', STR_PAD_LEFT) ?></span>
                    </div>
                    <button onclick="event.stopPropagation();moreOptions(<?= $track['id'] ?? $i ?>)" class="p-1 rounded-full hover:bg-[#1e1e2a] transition-colors">
                        <span class="material-icons-round text-zinc-600 text-lg">more_vert</span>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Recently Played -->
    <div class="px-4 py-3">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <h2 class="text-white text-sm font-bold">Recently Played</h2>
                <span class="material-icons-round text-[16px] text-zinc-600">history</span>
            </div>
            <a href="/music" class="text-[11px] font-semibold" style="color: #834ae5;">See all</a>
        </div>
        <div class="flex gap-3 overflow-x-auto scrollbar-hide pb-1 scroll-snap-x">
            <?php foreach (array_slice(array_merge($recent ?? [], $trending ?? []), 0, 8) as $i => $track): ?>
            <div class="flex-shrink-0 w-32 cursor-pointer" onclick="location.href='/music/track/<?= $track['id'] ?? '' ?>'">
                <div class="relative rounded-xl overflow-hidden mb-2 card-hover group">
                    <img src="<?= $track['cover_url'] ?>" alt="<?= $track['title'] ?>" class="w-full aspect-square object-cover">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #834ae5, #6b21a8);">
                            <span class="material-icons-round text-white text-2xl">play_arrow</span>
                        </div>
                    </div>
                    <?php if ($i < 2): ?>
                    <div class="absolute bottom-1.5 left-1.5 flex items-center gap-0.5 bg-black/60 backdrop-blur-sm rounded-md px-1.5 py-0.5">
                        <div class="w-1 h-1 rounded-full bg-green-400 animate-pulse"></div>
                        <span class="text-[8px] text-green-400 font-medium">Now</span>
                    </div>
                    <?php endif; ?>
                    <div class="absolute top-1.5 right-1.5 bg-black/60 backdrop-blur-sm rounded-md px-1.5 py-0.5">
                        <span class="text-[9px] text-white font-medium"><?= floor($track['duration']/60) ?>:<?= str_pad($track['duration']%60, 2, '0', STR_PAD_LEFT) ?></span>
                    </div>
                </div>
                <span class="text-white text-[11px] font-semibold truncate block"><?= $track['title'] ?></span>
                <span class="text-zinc-500 text-[9px] truncate block"><?= $track['artist_name'] ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Made for You / Playlists -->
    <div class="px-4 py-2">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <h2 class="text-white text-sm font-bold">Made for You</h2>
                <span class="material-icons-round text-[16px] text-zinc-600">auto_awesome</span>
            </div>
            <a href="/music" class="text-[11px] font-semibold" style="color: #834ae5;">Show all</a>
        </div>
        <div class="flex gap-3 overflow-x-auto scrollbar-hide pb-1 scroll-snap-x">
            <?php
                $playlistGradients = [
                    'linear-gradient(135deg, #834ae5, #4f09f5)',
                    'linear-gradient(135deg, #ec4899, #9333ea)',
                    'linear-gradient(135deg, #f59e0b, #ef4444)',
                    'linear-gradient(135deg, #06b6d4, #834ae5)',
                    'linear-gradient(135deg, #22c55e, #059669)',
                    'linear-gradient(135deg, #6366f1, #ec4899)',
                ];
            ?>
            <?php foreach ($playlists as $playlist): ?>
            <?php $pg = $playlistGradients[array_search($playlist['id'], array_column($playlists, 'id')) % count($playlistGradients)]; ?>
            <div class="flex-shrink-0 w-36 cursor-pointer playlist-card" onclick="location.href='/music'">
                <div class="relative rounded-2xl overflow-hidden mb-2">
                    <?php if (!empty($playlist['cover_url'])): ?>
                    <img src="<?= $playlist['cover_url'] ?>" alt="<?= $playlist['name'] ?>" class="w-full aspect-square object-cover">
                    <?php else: ?>
                    <div class="w-full aspect-square flex items-center justify-center" style="background: <?= $pg ?>;">
                        <span class="material-icons-round text-white/30 text-5xl">library_music</span>
                    </div>
                    <?php endif; ?>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                    <div class="absolute inset-0 bg-black/20 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #834ae5, #4f09f5);">
                            <span class="material-icons-round text-white text-2xl">play_arrow</span>
                        </div>
                    </div>
                    <div class="absolute top-2 right-2 bg-black/50 backdrop-blur-sm rounded-full px-2 py-0.5">
                        <span class="text-white text-[9px] font-medium"><?= $playlist['track_count'] ?> tracks</span>
                    </div>
                </div>
                <span class="text-white text-[11px] font-semibold truncate block"><?= $playlist['name'] ?></span>
                <div class="flex items-center gap-1 text-zinc-500">
                    <span class="text-[9px]"><?= $playlist['author_name'] ?? 'Curated' ?></span>
                    <span class="text-[8px]">·</span>
                    <span class="text-[9px]"><?= number_format(($playlist['followers'] ?? rand(1000, 50000)) / 1000, 1) ?>K</span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bottom spacer for now-playing bar -->
    <div class="h-16"></div>
</div>

<!-- Now Playing Bar -->
<div id="nowPlaying" class="now-playing-bar fixed bottom-14 left-0 right-0 z-40 px-3 py-2 hidden">
    <div class="max-w-lg mx-auto flex items-center gap-3">
        <div class="w-11 h-11 rounded-xl overflow-hidden flex-shrink-0 spin-slow" style="box-shadow: 0 0 0 2px rgba(131,74,229,0.3);">
            <img id="npCover" src="" alt="" class="w-full h-full object-cover">
        </div>
        <div class="flex-1 min-w-0">
            <div class="marquee-text">
                <span id="npTitle" class="text-white text-sm font-semibold"></span>
            </div>
            <span id="npArtist" class="text-zinc-400 text-[11px]"></span>
        </div>
        <div class="flex items-center gap-1">
            <button onclick="prevTrack()" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-[#1e1e2a] transition-colors">
                <span class="material-icons-round text-zinc-300 text-xl">skip_previous</span>
            </button>
            <button onclick="togglePlay()" class="w-10 h-10 rounded-full flex items-center justify-center shadow-lg glow-pulse" style="background: linear-gradient(135deg, #834ae5, #4f09f5);">
                <span class="material-icons-round text-white text-2xl" id="playIcon">play_arrow</span>
            </button>
            <button onclick="nextTrack()" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-[#1e1e2a] transition-colors">
                <span class="material-icons-round text-zinc-300 text-xl">skip_next</span>
            </button>
            <button onclick="openQueue()" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-[#1e1e2a] transition-colors">
                <span class="material-icons-round text-zinc-400 text-xl">queue_music</span>
            </button>
        </div>
    </div>
    <!-- Progress bar -->
    <div class="max-w-lg mx-auto mt-1.5 px-1">
        <div class="h-1 rounded-full bg-[#1e1e2a] overflow-hidden cursor-pointer" onclick="seekBar(event)">
            <div class="h-full rounded-full transition-all duration-1000" id="progressBar" style="width: 0%; background: linear-gradient(90deg, #834ae5, #ec4899);"></div>
        </div>
    </div>
</div>

<script>
const allTracks = <?= json_encode(array_merge($trending ?? [], $recent ?? [])) ?>;
let currentTrackIndex = -1;
let isPlaying = false;
let progressInterval = null;
let progress = 0;
let likedTracks = {};

// ── Player ──────────────────────────────────────────────

function playTrack(index) {
    const track = allTracks[index];
    if (!track) return;
    currentTrackIndex = index;

    document.querySelectorAll('.track-row').forEach(r => r.classList.remove('playing'));
    const row = document.getElementById('trackRow' + index);
    if (row) row.classList.add('playing');

    const player = document.getElementById('nowPlaying');
    player.classList.remove('hidden');
    document.getElementById('npCover').src = track.cover_url;
    document.getElementById('npTitle').textContent = track.title;
    document.getElementById('npArtist').textContent = track.artist_name;
    document.getElementById('playIcon').textContent = 'pause';
    document.getElementById('heroPlayIcon') && (document.getElementById('heroPlayIcon').textContent = 'pause');
    isPlaying = true; progress = 0;
    clearInterval(progressInterval);
    startProgress(track);

    // Record play
    fetch('/music/' + track.id + '/play', { method: 'POST' }).catch(() => {});
}

function startProgress(track) {
    progressInterval = setInterval(() => {
        progress += 1;
        const pct = Math.min((progress / (track.duration || 180)) * 100, 99);
        document.getElementById('progressBar').style.width = pct + '%';
        const hp = document.getElementById('heroProgress'); if (hp) hp.style.width = pct + '%';
        const ht = document.getElementById('heroTime');
        if (ht) { const m = Math.floor(progress/60); ht.textContent = m + ':' + String(progress%60).padStart(2,'0'); }
        if (progress >= (track.duration || 180)) { clearInterval(progressInterval); isPlaying = false; nextTrack(); }
    }, 1000);
}

function togglePlay() {
    if (currentTrackIndex < 0) { playTrack(0); return; }
    const icon = document.getElementById('playIcon');
    const heroIcon = document.getElementById('heroPlayIcon');
    if (isPlaying) { icon.textContent = 'play_arrow'; if (heroIcon) heroIcon.textContent = 'play_arrow'; isPlaying = false; clearInterval(progressInterval); }
    else { icon.textContent = 'pause'; if (heroIcon) heroIcon.textContent = 'pause'; isPlaying = true; startProgress(allTracks[currentTrackIndex]); }
}

function nextTrack() { playTrack(currentTrackIndex < allTracks.length-1 ? currentTrackIndex+1 : 0); }
function prevTrack() {
    if (progress > 3) { progress = 0; document.getElementById('progressBar').style.width = '0%'; return; }
    playTrack(currentTrackIndex > 0 ? currentTrackIndex-1 : allTracks.length-1);
}
function seekBar(e) {
    const rect = e.currentTarget.getBoundingClientRect();
    const pct = ((e.clientX - rect.left) / rect.width) * 100;
    document.getElementById('progressBar').style.width = pct + '%';
    const hp = document.getElementById('heroProgress'); if (hp) hp.style.width = pct + '%';
    const track = allTracks[currentTrackIndex];
    if (track) progress = Math.floor((pct/100) * (track.duration||180));
}
function seekTrack(e) { seekBar(e); }

// ── Like / Share ────────────────────────────────────────

function likeTrack(id, btn) {
    fetch('/music/' + id + '/like', { method: 'POST' })
        .then(r => r.json())
        .then(d => {
            if (d.error) { location.href = '/login'; return; }
            likedTracks[id] = d.liked;
            const icon = btn.querySelector('.material-icons-round');
            if (icon) { icon.textContent = d.liked ? 'favorite' : 'favorite_border'; icon.style.color = d.liked ? '#ec4899' : ''; }
            // Update count in DOM if present
            const countEl = document.getElementById('likeCount'+id);
            if (countEl) countEl.textContent = d.likes;
        });
}

function shareTrack(id, btn) {
    fetch('/music/' + id + '/share', { method: 'POST' }).catch(()=>{});
    const url = window.location.origin + '/music/track/' + id;
    if (navigator.share) { navigator.share({ title: 'Check out this track!', url: url }).catch(()=>{}); }
    else { navigator.clipboard.writeText(url).then(() => showToast('🔗 Link copied!')).catch(()=>{}); }
}

// ── Search ──────────────────────────────────────────────

let searchTimeout;
document.getElementById('musicSearch')?.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const q = this.value.trim();
    const results = document.getElementById('searchResults');
    
    if (q.length < 2) { if (results) results.classList.add('hidden'); return; }
    
    searchTimeout = setTimeout(() => {
        fetch('/music/search?q=' + encodeURIComponent(q))
            .then(r => r.json())
            .then(d => {
                if (!results) return;
                if (d.tracks.length === 0 && d.artists.length === 0) {
                    results.innerHTML = '<div class="p-4 text-zinc-500 text-sm text-center">No results for "' + q + '"</div>';
                } else {
                    results.innerHTML = d.tracks.slice(0,6).map(t => 
                        '<div class="flex items-center gap-3 p-3 hover:bg-[#1e1e2a] cursor-pointer rounded-lg" onclick="playTrackById('+t.id+')">' +
                        '<img src="'+t.cover_url+'" class="w-10 h-10 rounded-lg object-cover"><div class="flex-1 min-w-0">' +
                        '<div class="text-white text-xs font-semibold truncate">'+t.title+'</div>' +
                        '<div class="text-zinc-500 text-[10px]">'+t.artist_name+'</div></div>' +
                        '<span class="text-zinc-600 text-[10px]">'+formatDuration(t.duration||0)+'</span></div>'
                    ).join('');
                }
                results.classList.remove('hidden');
            });
    }, 300);
});

function playTrackById(id) {
    const idx = allTracks.findIndex(t => t.id == id);
    if (idx >= 0) { playTrack(idx); document.getElementById('searchResults').classList.add('hidden'); }
}

// ── Category Pills ──────────────────────────────────────

const categories = { All:'/music', Trending:'/music', Afrobeats:'afrobeats', 'Bongo Flava':'bongo-flava', 'Hip Hop':'hip-hop', Amapiano:'amapiano', Gospel:'gospel', 'RnB / Soul':'rnb-soul', Gengetone:'gengetone', Dancehall:'dancehall', New:'/music' };
function selectCategory(btn) {
    document.querySelectorAll('.category-pill').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const slug = categories[btn.textContent.trim()];
    if (!slug) return;
    if (slug === '/music') { location.href = '/music'; return; }
    location.href = '/music/genre/' + slug;
}

function fetchTrending() {
    fetch('/music/trending').then(r=>r.json()).then(d => updateTrackList(d.trending)).catch(()=>{});
}

function updateTrackList(tracks) {
    if (!tracks || !tracks.length) { showToast('No tracks found in this category'); return; }
    const container = document.querySelector('.track-list-container');
    if (!container) return;
    container.innerHTML = tracks.map((t,i) => 
        '<div class="track-row flex items-center gap-3 px-3 py-2.5 cursor-pointer '+(i<tracks.length-1?'border-b border-[#14141c]/60':'')+'" onclick="location.href=\'/music/track/'+t.id+'\'">'+
        '<div class="relative w-6 flex-shrink-0"><span class="chart-number track-num text-sm font-bold '+(i<3?'':'text-zinc-600')+'" '+(i<3?'style="color:#834ae5"':'')+'>'+(i+1)+'</span></div>'+
        '<img src="'+t.cover_url+'" class="w-11 h-11 rounded-xl object-cover flex-shrink-0">'+
        '<div class="flex-1 min-w-0"><div class="text-white text-xs font-semibold truncate">'+t.title+'</div>'+
        '<div class="text-zinc-500 text-[10px]">'+(t.artist_name||'')+'</div></div>'+
        '<div class="text-zinc-600 text-[10px] text-right">'+formatCount(t.plays||0)+'</div>'+
        '<div class="text-zinc-700 text-[9px]">'+formatDuration(t.duration||0)+'</div>'+
        '<span class="material-icons-round text-zinc-600 text-lg ml-1">chevron_right</span></div>'
    ).join('');
}

// ── Toast ───────────────────────────────────────────────

function showToast(msg) {
    let t = document.getElementById('toast');
    if (!t) { t = document.createElement('div'); t.id = 'toast'; t.className = 'fixed bottom-20 left-1/2 -translate-x-1/2 z-50 bg-[#1e1e2a] text-white px-4 py-2 rounded-full text-sm shadow-lg border border-[#834ae5]/30 transition-all opacity-0'; document.body.appendChild(t); }
    t.textContent = msg; t.classList.remove('opacity-0'); t.classList.add('opacity-100');
    setTimeout(() => { t.classList.remove('opacity-100'); t.classList.add('opacity-0'); }, 2000);
}

// ── Queue / Playlist / Options ──────────────────────────

function openQueue() { showToast('📋 Now Playing: ' + allTracks.length + ' tracks in queue'); }
function moreOptions(id) { const t = allTracks.find(tr => tr.id == id); showToast(t ? '🎵 ' + t.title + ' by ' + t.artist_name : 'Options'); }
function openPlaylist(id) { location.href = '/music'; }

// ── Helpers ─────────────────────────────────────────────

function formatCount(n) { n = parseInt(n)||0; return n>=1e6 ? (n/1e6).toFixed(1)+'M' : n>=1e3 ? (n/1e3).toFixed(1)+'K' : String(n); }
function formatDuration(s) { s = parseInt(s)||0; const m=Math.floor(s/60); return m+':'+String(s%60).padStart(2,'0'); }

// ═══════════════════════════════════════════════════════════
// NOTE: Category pills now navigate to genre pages directly.
// Search dropdown still uses fetch for live suggestions.
// All track cards link to /music/track/{id} show page.
// ═══════════════════════════════════════════════════════════

</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
