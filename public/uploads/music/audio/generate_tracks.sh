#!/bin/bash
cd /home/z/my-project/public/uploads/music/audio

echo "Starting audio track generation..."

# Track 1: Kukua Remix - Afrobeats style (3:34 = 214s)
ffmpeg -y -f lavfi -i "sine=frequency=440:duration=214" -f lavfi -i "sine=frequency=554:duration=214" -f lavfi -i "sine=frequency=659:duration=214" -filter_complex "[0:a]volume=0.3[a0];[1:a]volume=0.2[a1];[2:a]volume=0.15[a2];[a0][a1][a2]amix=inputs=3:duration=first,lowpass=f=2000,highpass=f=80,aformat=sample_fmts=fltp:sample_rates=44100:channel_layouts=stereo" -c:a libmp3lame -b:a 128k kukua_remix.mp3 2>/dev/null &
echo "  [1/20] Kukua Remix started..."

# Track 2: Sauti Ya Nairobi - Deep tones (3:18 = 198s)
ffmpeg -y -f lavfi -i "sine=frequency=330:duration=198" -f lavfi -i "sine=frequency=415:duration=198" -f lavfi -i "sine=frequency=523:duration=198" -filter_complex "[0:a]volume=0.3[a0];[1:a]volume=0.25[a1];[2:a]volume=0.15[a2];[a0][a1][a2]amix=inputs=3:duration=first,lowpass=f=1800,highpass=f=60,aformat=sample_fmts=fltp:sample_rates=44100:channel_layouts=stereo" -c:a libmp3lame -b:a 128k sauti_ya_nairobi.mp3 2>/dev/null &
echo "  [2/20] Sauti Ya Nairobi started..."

# Track 3: Late Night Vibes - Mellow (4:07 = 247s)
ffmpeg -y -f lavfi -i "sine=frequency=262:duration=247" -f lavfi -i "sine=frequency=330:duration=247" -f lavfi -i "sine=frequency=392:duration=247" -filter_complex "[0:a]volume=0.25[a0];[1:a]volume=0.2[a1];[2:a]volume=0.15[a2];[a0][a1][a2]amix=inputs=3:duration=first,lowpass=f=1500,highpass=f=50,aformat=sample_fmts=fltp:sample_rates=44100:channel_layouts=stereo" -c:a libmp3lame -b:a 128k late_night_vibes.mp3 2>/dev/null &
echo "  [3/20] Late Night Vibes started..."

# Track 4: African Dream - Warm (3:52 = 232s)
ffmpeg -y -f lavfi -i "sine=frequency=349:duration=232" -f lavfi -i "sine=frequency=440:duration=232" -f lavfi -i "sine=frequency=523:duration=232" -filter_complex "[0:a]volume=0.3[a0];[1:a]volume=0.2[a1];[2:a]volume=0.2[a2];[a0][a1][a2]amix=inputs=3:duration=first,lowpass=f=2200,highpass=f=70,aformat=sample_fmts=fltp:sample_rates=44100:channel_layouts=stereo" -c:a libmp3lame -b:a 128k african_dream.mp3 2>/dev/null &
echo "  [4/20] African Dream started..."

wait
echo "  Batch 1 (tracks 1-4) complete."

# Track 5: Sherehe - Party (3:09 = 189s)
ffmpeg -y -f lavfi -i "sine=frequency=523:duration=189" -f lavfi -i "sine=frequency=659:duration=189" -f lavfi -i "sine=frequency=784:duration=189" -filter_complex "[0:a]volume=0.25[a0];[1:a]volume=0.2[a1];[2:a]volume=0.2[a2];[a0][a1][a2]amix=inputs=3:duration=first,lowpass=f=3000,highpass=f=100,aformat=sample_fmts=fltp:sample_rates=44100:channel_layouts=stereo" -c:a libmp3lame -b:a 128k sherehe.mp3 2>/dev/null &
echo "  [5/20] Sherehe started..."

# Track 6: Nakupenda - Love (3:25 = 205s)
ffmpeg -y -f lavfi -i "sine=frequency=294:duration=205" -f lavfi -i "sine=frequency=370:duration=205" -f lavfi -i "sine=frequency=440:duration=205" -filter_complex "[0:a]volume=0.3[a0];[1:a]volume=0.25[a1];[2:a]volume=0.15[a2];[a0][a1][a2]amix=inputs=3:duration=first,lowpass=f=1600,highpass=f=55,aformat=sample_fmts=fltp:sample_rates=44100:channel_layouts=stereo" -c:a libmp3lame -b:a 128k nakupenda.mp3 2>/dev/null &
echo "  [6/20] Nakupenda started..."

# Track 7: Mambo Bora - Reggae (2:56 = 176s)
ffmpeg -y -f lavfi -i "sine=frequency=392:duration=176" -f lavfi -i "sine=frequency=494:duration=176" -f lavfi -i "sine=frequency=587:duration=176" -filter_complex "[0:a]volume=0.3[a0];[1:a]volume=0.2[a1];[2:a]volume=0.15[a2];[a0][a1][a2]amix=inputs=3:duration=first,lowpass=f=2500,highpass=f=65,aformat=sample_fmts=fltp:sample_rates=44100:channel_layouts=stereo" -c:a libmp3lame -b:a 128k mambo_bora.mp3 2>/dev/null &
echo "  [7/20] Mambo Bora started..."

# Track 8: Safari Sounds - Nature (4:23 = 263s)
ffmpeg -y -f lavfi -i "sine=frequency=220:duration=263" -f lavfi -i "sine=frequency=277:duration=263" -f lavfi -i "sine=frequency=330:duration=263" -filter_complex "[0:a]volume=0.25[a0];[1:a]volume=0.2[a1];[2:a]volume=0.15[a2];[a0][a1][a2]amix=inputs=3:duration=first,lowpass=f=1200,highpass=f=40,aformat=sample_fmts=fltp:sample_rates=44100:channel_layouts=stereo" -c:a libmp3lame -b:a 128k safari_sounds.mp3 2>/dev/null &
echo "  [8/20] Safari Sounds started..."

wait
echo "  Batch 2 (tracks 5-8) complete."

# Track 9: Leo Niko Ready - Hip-hop (3:12 = 192s)
ffmpeg -y -f lavfi -i "sine=frequency=466:duration=192" -f lavfi -i "sine=frequency=587:duration=192" -f lavfi -i "sine=frequency=698:duration=192" -filter_complex "[0:a]volume=0.3[a0];[1:a]volume=0.25[a1];[2:a]volume=0.15[a2];[a0][a1][a2]amix=inputs=3:duration=first,lowpass=f=3500,highpass=f=80,aformat=sample_fmts=fltp:sample_rates=44100:channel_layouts=stereo" -c:a libmp3lame -b:a 128k leo_niko_ready.mp3 2>/dev/null &
echo "  [9/20] Leo Niko Ready started..."

# Track 10: Ulimi Wangu - Melodic (3:48 = 228s)
ffmpeg -y -f lavfi -i "sine=frequency=349:duration=228" -f lavfi -i "sine=frequency=440:duration=228" -f lavfi -i "sine=frequency=523:duration=228" -filter_complex "[0:a]volume=0.25[a0];[1:a]volume=0.2[a1];[2:a]volume=0.15[a2];[a0][a1][a2]amix=inputs=3:duration=first,lowpass=f=2000,highpass=f=60,aformat=sample_fmts=fltp:sample_rates=44100:channel_layouts=stereo" -c:a libmp3lame -b:a 128k ulimi_wangu.mp3 2>/dev/null &
echo "  [10/20] Ulimi Wangu started..."

# Track 11: Lagos Nights - DJ Pulse (4:05 = 245s)
ffmpeg -y -f lavfi -i "sine=frequency=415:duration=245" -f lavfi -i "sine=frequency=523:duration=245" -f lavfi -i "sine=frequency=622:duration=245" -filter_complex "[0:a]volume=0.3[a0];[1:a]volume=0.2[a1];[2:a]volume=0.2[a2];[a0][a1][a2]amix=inputs=3:duration=first,lowpass=f=2800,highpass=f=70,aformat=sample_fmts=fltp:sample_rates=44100:channel_layouts=stereo" -c:a libmp3lame -b:a 128k lagos_nights.mp3 2>/dev/null &
echo "  [11/20] Lagos Nights started..."

# Track 12: Sunset in Nairobi (3:18 = 198s)
ffmpeg -y -f lavfi -i "sine=frequency=262:duration=198" -f lavfi -i "sine=frequency=330:duration=198" -f lavfi -i "sine=frequency=494:duration=198" -filter_complex "[0:a]volume=0.25[a0];[1:a]volume=0.2[a1];[2:a]volume=0.15[a2];[a0][a1][a2]amix=inputs=3:duration=first,lowpass=f=1800,highpass=f=50,aformat=sample_fmts=fltp:sample_rates=44100:channel_layouts=stereo" -c:a libmp3lame -b:a 128k sunset_nairobi.mp3 2>/dev/null &
echo "  [12/20] Sunset in Nairobi started..."

wait
echo "  Batch 3 (tracks 9-12) complete."

# Track 13: Afro Groove (3:30 = 210s)
ffmpeg -y -f lavfi -i "sine=frequency=370:duration=210" -f lavfi -i "sine=frequency=466:duration=210" -f lavfi -i "sine=frequency=554:duration=210" -filter_complex "[0:a]volume=0.3[a0];[1:a]volume=0.25[a1];[2:a]volume=0.15[a2];[a0][a1][a2]amix=inputs=3:duration=first,lowpass=f=2500,highpass=f=75,aformat=sample_fmts=fltp:sample_rates=44100:channel_layouts=stereo" -c:a libmp3lame -b:a 128k afro_groove.mp3 2>/dev/null &
echo "  [13/20] Afro Groove started..."

# Track 14: Island Breeze (3:07 = 187s)
ffmpeg -y -f lavfi -i "sine=frequency=294:duration=187" -f lavfi -i "sine=frequency=349:duration=187" -f lavfi -i "sine=frequency=440:duration=187" -filter_complex "[0:a]volume=0.25[a0];[1:a]volume=0.2[a1];[2:a]volume=0.15[a2];[a0][a1][a2]amix=inputs=3:duration=first,lowpass=f=1400,highpass=f=45,aformat=sample_fmts=fltp:sample_rates=44100:channel_layouts=stereo" -c:a libmp3lame -b:a 128k island_breeze.mp3 2>/dev/null &
echo "  [14/20] Island Breeze started..."

# Track 15: Kitchen Beats - Chef Kwame (2:36 = 156s)
ffmpeg -y -f lavfi -i "sine=frequency=440:duration=156" -f lavfi -i "sine=frequency=554:duration=156" -f lavfi -i "sine=frequency=659:duration=156" -filter_complex "[0:a]volume=0.3[a0];[1:a]volume=0.2[a1];[2:a]volume=0.15[a2];[a0][a1][a2]amix=inputs=3:duration=first,lowpass=f=2200,highpass=f=80,aformat=sample_fmts=fltp:sample_rates=44100:channel_layouts=stereo" -c:a libmp3lame -b:a 128k kitchen_beats.mp3 2>/dev/null &
echo "  [15/20] Kitchen Beats started..."

# Track 16: Workout Fire - Fit Sarah (3:45 = 225s)
ffmpeg -y -f lavfi -i "sine=frequency=494:duration=225" -f lavfi -i "sine=frequency=587:duration=225" -f lavfi -i "sine=frequency=698:duration=225" -filter_complex "[0:a]volume=0.3[a0];[1:a]volume=0.25[a1];[2:a]volume=0.2[a2];[a0][a1][a2]amix=inputs=3:duration=first,lowpass=f=3000,highpass=f=90,aformat=sample_fmts=fltp:sample_rates=44100:channel_layouts=stereo" -c:a libmp3lame -b:a 128k workout_fire.mp3 2>/dev/null &
echo "  [16/20] Workout Fire started..."

wait
echo "  Batch 4 (tracks 13-16) complete."

# Track 17: Morning Hustle - Fit Sarah (2:58 = 178s)
ffmpeg -y -f lavfi -i "sine=frequency=523:duration=178" -f lavfi -i "sine=frequency=659:duration=178" -f lavfi -i "sine=frequency=784:duration=178" -filter_complex "[0:a]volume=0.25[a0];[1:a]volume=0.2[a1];[2:a]volume=0.15[a2];[a0][a1][a2]amix=inputs=3:duration=first,lowpass=f=2800,highpass=f=85,aformat=sample_fmts=fltp:sample_rates=44100:channel_layouts=stereo" -c:a libmp3lame -b:a 128k morning_hustle.mp3 2>/dev/null &
echo "  [17/20] Morning Hustle started..."

# Track 18: Road Trip Anthem - Travel Dave (3:54 = 234s)
ffmpeg -y -f lavfi -i "sine=frequency=349:duration=234" -f lavfi -i "sine=frequency=440:duration=234" -f lavfi -i "sine=frequency=523:duration=234" -filter_complex "[0:a]volume=0.25[a0];[1:a]volume=0.2[a1];[2:a]volume=0.15[a2];[a0][a1][a2]amix=inputs=3:duration=first,lowpass=f=2000,highpass=f=55,aformat=sample_fmts=fltp:sample_rates=44100:channel_layouts=stereo" -c:a libmp3lame -b:a 128k road_trip.mp3 2>/dev/null &
echo "  [18/20] Road Trip Anthem started..."

# Track 19: Ocean Waves - Travel Dave (4:27 = 267s)
ffmpeg -y -f lavfi -i "sine=frequency=220:duration=267" -f lavfi -i "sine=frequency=262:duration=267" -f lavfi -i "sine=frequency=330:duration=267" -filter_complex "[0:a]volume=0.2[a0];[1:a]volume=0.15[a1];[2:a]volume=0.1[a2];[a0][a1][a2]amix=inputs=3:duration=first,lowpass=f=1000,highpass=f=30,aformat=sample_fmts=fltp:sample_rates=44100:channel_layouts=stereo" -c:a libmp3lame -b:a 128k ocean_waves.mp3 2>/dev/null &
echo "  [19/20] Ocean Waves started..."

# Track 20: Digital Dreams - Marcus Tech (3:10 = 190s)
ffmpeg -y -f lavfi -i "sine=frequency=466:duration=190" -f lavfi -i "sine=frequency=587:duration=190" -f lavfi -i "sine=frequency=740:duration=190" -filter_complex "[0:a]volume=0.25[a0];[1:a]volume=0.2[a1];[2:a]volume=0.15[a2];[a0][a1][a2]amix=inputs=3:duration=first,lowpass=f=3200,highpass=f=80,aformat=sample_fmts=fltp:sample_rates=44100:channel_layouts=stereo" -c:a libmp3lame -b:a 128k digital_dreams.mp3 2>/dev/null &
echo "  [20/20] Digital Dreams started..."

wait
echo "  Batch 5 (tracks 17-20) complete."
echo ""
echo "All 20 audio tracks generated!"

# Verify all files
echo ""
echo "=== Generated Files ==="
count=$(ls -1 *.mp3 2>/dev/null | wc -l)
echo "Total MP3 files: $count"
ls -lh *.mp3
