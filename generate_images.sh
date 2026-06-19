#!/bin/bash
# Master image generation script with retry logic
# Processes images sequentially with delay to avoid rate limiting

generate() {
    local prompt="$1"
    local output="$2"
    local size="$3"
    local max_retries=5
    local retry=0
    
    while [ $retry -lt $max_retries ]; do
        z-ai image -p "$prompt" -o "$output" -s "$size" 2>/dev/null
        if [ -f "$output" ]; then
            # Check if image is valid (at least 10KB)
            local fsize=$(stat -c%s "$output" 2>/dev/null || echo "0")
            if [ "$fsize" -gt 10000 ]; then
                echo "✅ $output ($fsize bytes)"
                return 0
            fi
        fi
        retry=$((retry + 1))
        echo "⏳ Retry $retry/$max_retries for $output..."
        sleep $((retry * 15))
    done
    echo "❌ FAILED: $output"
    return 1
}

DELAY=8

# ===== MUSIC ARTIST AVATARS (remaining 9, 10) =====
echo "=== MUSIC ARTIST AVATARS ==="
generate "Portrait of African male rapper Bravo Kid, streetwear, confident pose, urban, professional" "/home/z/my-project/public/uploads/music/artists/avatar_9.jpg" "1024x1024"
sleep $DELAY
generate "Portrait of African female singer Lena Moon, moonlight theme, ethereal, professional" "/home/z/my-project/public/uploads/music/artists/avatar_10.jpg" "1024x1024"
sleep $DELAY

# ===== MUSIC TRACK COVERS (11-20) =====
echo "=== MUSIC TRACK COVERS 1-10 ==="
generate "Album cover, abstract African pattern, purple and gold, geometric, modern design" "/home/z/my-project/public/uploads/music/covers/track_1.jpg" "1024x1024"
sleep $DELAY
generate "Album cover, Nairobi city skyline at night, purple neon, urban, modern" "/home/z/my-project/public/uploads/music/covers/track_2.jpg" "1024x1024"
sleep $DELAY
generate "Album cover, moon over African savanna, dark blue and silver, dreamy" "/home/z/my-project/public/uploads/music/covers/track_3.jpg" "1024x1024"
sleep $DELAY
generate "Album cover, African dreamcatcher, warm sunset colors, bohemian" "/home/z/my-project/public/uploads/music/covers/track_4.jpg" "1024x1024"
sleep $DELAY
generate "Album cover, African celebration party, confetti, vibrant orange and red" "/home/z/my-project/public/uploads/music/covers/track_5.jpg" "1024x1024"
sleep $DELAY
generate "Album cover, romantic African sunset scene, pink and purple, love theme" "/home/z/my-project/public/uploads/music/covers/track_6.jpg" "1024x1024"
sleep $DELAY
generate "Album cover, street art graffiti style, bold colors, urban African" "/home/z/my-project/public/uploads/music/covers/track_7.jpg" "1024x1024"
sleep $DELAY
generate "Album cover, African wildlife silhouette, sunset, earthy tones" "/home/z/my-project/public/uploads/music/covers/track_8.jpg" "1024x1024"
sleep $DELAY
generate "Album cover, microphone with fire, energetic, red and black" "/home/z/my-project/public/uploads/music/covers/track_9.jpg" "1024x1024"
sleep $DELAY
generate "Album cover, African woman face silhouette, moon, mysterious, blue" "/home/z/my-project/public/uploads/music/covers/track_10.jpg" "1024x1024"
sleep $DELAY

# ===== GENRE COVERS (21-28) =====
echo "=== GENRE COVERS ==="
generate "Afrobeats genre cover, drum, colorful, energetic" "/home/z/my-project/public/uploads/music/covers/genre_1.jpg" "1024x1024"
sleep $DELAY
generate "Amapiano genre cover, piano keys, South African vibe" "/home/z/my-project/public/uploads/music/covers/genre_2.jpg" "1024x1024"
sleep $DELAY
generate "R&B Soul genre cover, microphone, smooth lighting, purple" "/home/z/my-project/public/uploads/music/covers/genre_3.jpg" "1024x1024"
sleep $DELAY
generate "Hip Hop genre cover, urban street, graffiti, bold" "/home/z/my-project/public/uploads/music/covers/genre_4.jpg" "1024x1024"
sleep $DELAY
generate "Gospel genre cover, church, light rays, golden" "/home/z/my-project/public/uploads/music/covers/genre_5.jpg" "1024x1024"
sleep $DELAY
generate "Highlife genre cover, guitar, African band, warm" "/home/z/my-project/public/uploads/music/covers/genre_6.jpg" "1024x1024"
sleep $DELAY
generate "Electronic genre cover, DJ setup, neon, futuristic" "/home/z/my-project/public/uploads/music/covers/genre_7.jpg" "1024x1024"
sleep $DELAY
generate "Traditional genre cover, African drums, cultural, warm earth" "/home/z/my-project/public/uploads/music/covers/genre_8.jpg" "1024x1024"
sleep $DELAY

# ===== ADDITIONAL TRACK COVERS (29-39) =====
echo "=== ADDITIONAL TRACK COVERS ==="
generate "Featured music banner, African music festival scene, stage lights" "/home/z/my-project/public/uploads/music/covers/featured_banner.jpg" "1440x720"
sleep $DELAY
generate "Album cover, Lagos nightlife cityscape, neon reflections, purple and blue" "/home/z/my-project/public/uploads/music/covers/lagos_nights.jpg" "1024x1024"
sleep $DELAY
generate "Album cover, Nairobi sunset silhouette, giraffe, warm orange" "/home/z/my-project/public/uploads/music/covers/sunset_nairobi.jpg" "1024x1024"
sleep $DELAY
generate "Album cover, abstract sound waves, African patterns, green and gold" "/home/z/my-project/public/uploads/music/covers/afro_groove.jpg" "1024x1024"
sleep $DELAY
generate "Album cover, tropical island, palm trees, ocean, turquoise and green" "/home/z/my-project/public/uploads/music/covers/island_breeze.jpg" "1024x1024"
sleep $DELAY
generate "Album cover, kitchen cooking scene with music notes, fun, warm" "/home/z/my-project/public/uploads/music/covers/kitchen_beats.jpg" "1024x1024"
sleep $DELAY
generate "Album cover, intense workout scene, fire, energy, red and orange" "/home/z/my-project/public/uploads/music/covers/workout_fire.jpg" "1024x1024"
sleep $DELAY
generate "Album cover, African morning city, sunrise, motivational, gold" "/home/z/my-project/public/uploads/music/covers/morning_hustle.jpg" "1024x1024"
sleep $DELAY
generate "Album cover, African road trip, savanna landscape, adventure, wide" "/home/z/my-project/public/uploads/music/covers/road_trip.jpg" "1024x1024"
sleep $DELAY
generate "Album cover, ocean waves at sunset, calming, blue and orange" "/home/z/my-project/public/uploads/music/covers/ocean_waves.jpg" "1024x1024"
sleep $DELAY
generate "Album cover, futuristic digital Africa, neon, cyberpunk, blue" "/home/z/my-project/public/uploads/music/covers/digital_dreams.jpg" "1024x1024"
sleep $DELAY

# ===== PLAYLIST COVERS (40-45) =====
echo "=== PLAYLIST COVERS ==="
generate "Playlist cover, African music mix, colorful vinyl records, warm" "/home/z/my-project/public/uploads/music/playlists/playlist_1.jpg" "1024x1024"
sleep $DELAY
generate "Playlist cover, workout energy, athletic, bold red" "/home/z/my-project/public/uploads/music/playlists/playlist_2.jpg" "1024x1024"
sleep $DELAY
generate "Playlist cover, chill vibes, African sunset, calm orange" "/home/z/my-project/public/uploads/music/playlists/playlist_3.jpg" "1024x1024"
sleep $DELAY
generate "Playlist cover, party hits, confetti, dance, vibrant" "/home/z/my-project/public/uploads/music/playlists/playlist_4.jpg" "1024x1024"
sleep $DELAY
generate "Playlist cover, romantic African love songs, hearts, pink" "/home/z/my-project/public/uploads/music/playlists/playlist_5.jpg" "1024x1024"
sleep $DELAY
generate "Playlist cover, road trip songs, African highway, green" "/home/z/my-project/public/uploads/music/playlists/playlist_6.jpg" "1024x1024"
sleep $DELAY

# ===== VIDEO THUMBNAILS (46-65) =====
echo "=== VIDEO THUMBNAILS ==="
generate "Thumbnail, African woman mid-dance move, colorful studio, energetic" "/home/z/my-project/public/uploads/thumbnails/reel_thumb_1.jpg" "1024x1024"
sleep $DELAY
generate "Thumbnail, cooking jollof rice, steam, warm kitchen, appetizing" "/home/z/my-project/public/uploads/thumbnails/reel_thumb_2.jpg" "1024x1024"
sleep $DELAY
generate "Thumbnail, Nairobi city at night, neon lights, urban vibe" "/home/z/my-project/public/uploads/thumbnails/reel_thumb_3.jpg" "1024x1024"
sleep $DELAY
generate "Thumbnail, AI generated digital art, futuristic, purple glow" "/home/z/my-project/public/uploads/thumbnails/reel_thumb_4.jpg" "1024x1024"
sleep $DELAY
generate "Thumbnail, music studio recording session, microphone, purple" "/home/z/my-project/public/uploads/thumbnails/reel_thumb_5.jpg" "1024x1024"
sleep $DELAY
generate "Thumbnail, comedy scene, funny African mom expression, relatable" "/home/z/my-project/public/uploads/thumbnails/reel_thumb_6.jpg" "1024x1024"
sleep $DELAY
generate "Thumbnail, skincare products arrangement, beauty, clean aesthetic" "/home/z/my-project/public/uploads/thumbnails/reel_thumb_7.jpg" "1024x1024"
sleep $DELAY
generate "Thumbnail, football player doing skills, stadium, dynamic action" "/home/z/my-project/public/uploads/thumbnails/reel_thumb_8.jpg" "1024x1024"
sleep $DELAY
generate "Thumbnail, iPhone vs Samsung comparison, tech review style, split" "/home/z/my-project/public/uploads/thumbnails/reel_thumb_mt1.jpg" "1024x1024"
sleep $DELAY
generate "Thumbnail, gaming PC build timelapse, RGB lighting, tech" "/home/z/my-project/public/uploads/thumbnails/reel_thumb_mt2.jpg" "1024x1024"
sleep $DELAY
generate "Thumbnail, makeup application closeup, beauty, soft glam" "/home/z/my-project/public/uploads/thumbnails/reel_thumb_ab1.jpg" "1024x1024"
sleep $DELAY
generate "Thumbnail, skincare products with X marks, dont buy, beauty" "/home/z/my-project/public/uploads/thumbnails/reel_thumb_ab2.jpg" "1024x1024"
sleep $DELAY
generate "Thumbnail, perfect jollof rice plated, food photography, golden" "/home/z/my-project/public/uploads/thumbnails/reel_thumb_ck1.jpg" "1024x1024"
sleep $DELAY
generate "Thumbnail, making fufu traditional way, hands kneading, food" "/home/z/my-project/public/uploads/thumbnails/reel_thumb_ck2.jpg" "1024x1024"
sleep $DELAY
generate "Thumbnail, DJ at African club, crowd, laser lights, music" "/home/z/my-project/public/uploads/thumbnails/reel_thumb_dp1.jpg" "1024x1024"
sleep $DELAY
generate "Thumbnail, crowd going wild at concert, hands up, energy" "/home/z/my-project/public/uploads/thumbnails/reel_thumb_dp2.jpg" "1024x1024"
sleep $DELAY
generate "Thumbnail, woman doing ab workout, gym, fitness motivation" "/home/z/my-project/public/uploads/thumbnails/reel_thumb_fs1.jpg" "1024x1024"
sleep $DELAY
generate "Thumbnail, healthy meal prep bowls, colorful, fitness food" "/home/z/my-project/public/uploads/thumbnails/reel_thumb_fs2.jpg" "1024x1024"
sleep $DELAY
generate "Thumbnail, Zanzibar beach sunrise, turquoise water, paradise" "/home/z/my-project/public/uploads/thumbnails/reel_thumb_td1.jpg" "1024x1024"
sleep $DELAY
generate "Thumbnail, mountain gorilla close-up, Uganda jungle, wildlife" "/home/z/my-project/public/uploads/thumbnails/reel_thumb_td2.jpg" "1024x1024"
sleep $DELAY

# ===== LIVESTREAM THUMBNAILS (66-67) =====
echo "=== LIVESTREAM THUMBNAILS ==="
generate "Livestream thumbnail, music studio session, LIVE badge, purple" "/home/z/my-project/public/uploads/livestreams/live_1.jpg" "1344x768"
sleep $DELAY
generate "Livestream thumbnail, Q&A creator session, LIVE badge, blue" "/home/z/my-project/public/uploads/livestreams/live_2.jpg" "1344x768"
sleep $DELAY

# ===== USER AVATARS (68-75) =====
echo "=== USER AVATARS ==="
generate "Profile avatar, African woman admin, professional, red background, clean" "/home/z/my-project/public/uploads/profiles/admin.jpg" "1024x1024"
sleep $DELAY
generate "Profile avatar, young African female dancer, confident, purple background" "/home/z/my-project/public/uploads/profiles/zarake.jpg" "1024x1024"
sleep $DELAY
generate "Profile avatar, African male tech reviewer, glasses, blue background" "/home/z/my-project/public/uploads/profiles/marcustech.jpg" "1024x1024"
sleep $DELAY
generate "Profile avatar, African female beauty influencer, pink background" "/home/z/my-project/public/uploads/profiles/aminabeauty.jpg" "1024x1024"
sleep $DELAY
generate "Profile avatar, African male chef, white apron, orange background" "/home/z/my-project/public/uploads/profiles/chefkwame.jpg" "1024x1024"
sleep $DELAY
generate "Profile avatar, African male DJ, headphones, purple background" "/home/z/my-project/public/uploads/profiles/djpulse.jpg" "1024x1024"
sleep $DELAY
generate "Profile avatar, African female fitness trainer, athletic, green background" "/home/z/my-project/public/uploads/profiles/fitsarah.jpg" "1024x1024"
sleep $DELAY
generate "Profile avatar, African male traveler, adventure hat, red background" "/home/z/my-project/public/uploads/profiles/traveldave.jpg" "1024x1024"

echo "=== ALL DONE ==="