#!/bin/bash
set -e

OUTDIR="/home/z/my-project/public/uploads/reels"
mkdir -p "$OUTDIR"

generate() {
  local name="$1"
  local prompt="$2"
  local outfile="$OUTDIR/$name"
  local max_retries=8
  local attempt=1

  while [ $attempt -le $max_retries ]; do
    echo "  Attempt $attempt/$max_retries..."
    if z-ai image -p "$prompt" -o "$outfile" -s 768x1344 2>&1; then
      local fsize=$(stat -c%s "$outfile" 2>/dev/null || echo "0")
      echo "  ✓ Saved: $outfile (${fsize} bytes)"
      return 0
    fi
    # Calculate wait time: 30s * attempt for rate limits
    local wait=$((30 * attempt))
    echo "  ✗ Failed. Waiting ${wait}s before retry..."
    sleep $wait
    attempt=$((attempt + 1))
  done
  echo "  ✗✗ FAILED after $max_retries attempts: $name"
  return 1
}

echo "Generating 8 reel cover images for Gloobim..."
echo ""

generate "reel1.gif" "African dance challenge video cover, young woman in vibrant Ankara print outfit dancing energetically, colorful party background, motion blur effect, viral social media reel aesthetic, portrait orientation"
echo ""
sleep 5

generate "reel2.gif" "African street food cooking video cover, chef preparing suya on open grill, flames and smoke, close-up action shot, warm golden lighting, food reel aesthetic, portrait orientation"
echo ""
sleep 5

generate "reel3.gif" "African comedy skit video cover, two friends in hilarious situation, exaggerated expressions, colorful urban Lagos street background, entertainment reel, portrait orientation"
echo ""
sleep 5

generate "reel4.gif" "African fashion show reel cover, model strutting in modern African designer outfit, runway style, dramatic spotlight, fashion content, portrait orientation"
echo ""
sleep 5

generate "reel5.gif" "African sunrise workout reel cover, woman doing yoga on rooftop with city skyline view, golden hour lighting, fitness motivation, wellness content, portrait orientation"
echo ""
sleep 5

generate "reel6.gif" "African wildlife safari reel cover, close-up of elephant in natural habitat, golden savanna grass, nature documentary quality, travel content, portrait orientation"
echo ""
sleep 5

generate "reel7.gif" "African music performance reel cover, Afrobeat artist singing into microphone on stage, colorful concert lights, crowd silhouettes, music content, portrait orientation"
echo ""
sleep 5

generate "reel8.gif" "African art and craft reel cover, artisan creating traditional beadwork, colorful beads and wire, close-up hands working, cultural heritage content, portrait orientation"
echo ""

echo ""
echo "=== FINAL CHECK ==="
for f in reel1.gif reel2.gif reel3.gif reel4.gif reel5.gif reel6.gif reel7.gif reel8.gif; do
  if [ -f "$OUTDIR/$f" ]; then
    sz=$(stat -c%s "$OUTDIR/$f")
    echo "✓ $f ($sz bytes)"
  else
    echo "✗ $f (MISSING)"
  fi
done
echo "Done!"