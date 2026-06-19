import ZAI from 'z-ai-web-dev-sdk';
import fs from 'fs';
import path from 'path';

const outputDir = '/home/z/my-project/public/uploads/reels';

if (!fs.existsSync(outputDir)) {
  fs.mkdirSync(outputDir, { recursive: true });
}

const reels = [
  { filename: 'reel1.gif', prompt: 'African dance challenge video cover, young woman in vibrant Ankara print outfit dancing energetically, colorful party background, motion blur effect, viral social media reel aesthetic, portrait orientation' },
  { filename: 'reel2.gif', prompt: 'African street food cooking video cover, chef preparing suya on open grill, flames and smoke, close-up action shot, warm golden lighting, food reel aesthetic, portrait orientation' },
  { filename: 'reel3.gif', prompt: 'African comedy skit video cover, two friends in hilarious situation, exaggerated expressions, colorful urban Lagos street background, entertainment reel, portrait orientation' },
  { filename: 'reel4.gif', prompt: 'African fashion show reel cover, model strutting in modern African designer outfit, runway style, dramatic spotlight, fashion content, portrait orientation' },
  { filename: 'reel5.gif', prompt: 'African sunrise workout reel cover, woman doing yoga on rooftop with city skyline view, golden hour lighting, fitness motivation, wellness content, portrait orientation' },
  { filename: 'reel6.gif', prompt: 'African wildlife safari reel cover, close-up of elephant in natural habitat, golden savanna grass, nature documentary quality, travel content, portrait orientation' },
  { filename: 'reel7.gif', prompt: 'African music performance reel cover, Afrobeat artist singing into microphone on stage, colorful concert lights, crowd silhouettes, music content, portrait orientation' },
  { filename: 'reel8.gif', prompt: 'African art and craft reel cover, artisan creating traditional beadwork, colorful beads and wire, close-up hands working, cultural heritage content, portrait orientation' },
];

async function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

async function generateWithRetry(zai, prompt, outputPath, maxRetries = 8) {
  for (let attempt = 1; attempt <= maxRetries; attempt++) {
    try {
      console.log(`  Attempt ${attempt}/${maxRetries}...`);
      const response = await zai.images.generations.create({
        prompt: prompt,
        size: '768x1344'
      });

      const imageBase64 = response.data[0].base64;
      const buffer = Buffer.from(imageBase64, 'base64');
      fs.writeFileSync(outputPath, buffer);
      console.log(`  ✓ Saved: ${outputPath} (${buffer.length} bytes)`);
      return { success: true, path: outputPath, size: buffer.length };
    } catch (error) {
      const isRateLimit = error.message && error.message.includes('429');
      const waitTime = isRateLimit ? 30000 * attempt : 5000 * attempt;
      console.log(`  ✗ Failed: ${error.message}`);
      console.log(`  Waiting ${waitTime / 1000}s before retry...`);
      await sleep(waitTime);
    }
  }
  return { success: false, error: 'Max retries exceeded' };
}

async function main() {
  console.log('Initializing ZAI SDK...');
  const zai = await ZAI.create();
  console.log('SDK ready. Generating 8 reel covers...\n');

  const results = [];
  for (let i = 0; i < reels.length; i++) {
    const reel = reels[i];
    const outputPath = path.join(outputDir, reel.filename);
    console.log(`[${i + 1}/8] Generating ${reel.filename}...`);

    const result = await generateWithRetry(zai, reel.prompt, outputPath);
    results.push({ filename: reel.filename, ...result });

    if (i < reels.length - 1) {
      console.log(`  Waiting 5s before next image...\n`);
      await sleep(5000);
    }
  }

  console.log('\n=== RESULTS ===');
  const succeeded = results.filter(r => r.success);
  const failed = results.filter(r => !r.success);
  console.log(`Success: ${succeeded.length}/8`);
  console.log(`Failed: ${failed.length}/8`);
  if (failed.length > 0) {
    console.log('Failed files:', failed.map(f => f.filename).join(', '));
  }
}

main().catch(console.error);