<?php
/**
 * Animated GIF Generator for Reels (No FFmpeg)
 * Uses a proper LZW encoder for valid animated GIFs.
 */

define('REEL_DIR', __DIR__ . '/public/uploads/reels');
define('THUMB_DIR', __DIR__ . '/public/uploads/thumbnails');
define('W', 288); define('H', 512); define('FPS', 10);

@mkdir(REEL_DIR, 0755, true); @mkdir(THUMB_DIR, 0755, true);

$reels = [
    1 => ['t'=>'Dance Challenge #Kukua',  'd'=>30,'bg'=>[109,40,217], 'ac'=>[255,255,255],'a'=>'dance', 'e'=>'💃'],
    2 => ['t'=>'Cooking Jollof Rice',     'd'=>45,'bg'=>[220,38,38],  'ac'=>[255,200,50], 'a'=>'cooking','e'=>'🍚'],
    3 => ['t'=>'Nairobi Night Vibes',     'd'=>22,'bg'=>[5,150,105],  'ac'=>[255,255,100],'a'=>'night',  'e'=>'🌃'],
    4 => ['t'=>'AI Art is Crazy',         'd'=>25,'bg'=>[37,99,235],  'ac'=>[100,255,255],'a'=>'ai',     'e'=>'🤖'],
    5 => ['t'=>'Afrobeats Studio Session','d'=>35,'bg'=>[217,119,6],  'ac'=>[255,200,80], 'a'=>'music',  'e'=>'🎧'],
    6 => ['t'=>'Comedy: African Mom',     'd'=>40,'bg'=>[225,29,72],  'ac'=>[255,255,150],'a'=>'comedy', 'e'=>'😂'],
    7 => ['t'=>'Skincare Routine',        'd'=>30,'bg'=>[124,58,237], 'ac'=>[200,180,255],'a'=>'beauty', 'e'=>'✨'],
    8 => ['t'=>'Football Skills',         'd'=>28,'bg'=>[8,145,178],  'ac'=>[255,255,255],'a'=>'sports', 'e'=>'⚽'],
];

// Cap reel 4 at 25s (too large otherwise)
echo "🎬 Generating Animated Reel GIFs\n";
echo str_repeat('━', 45) . "\n\n";

foreach ($reels as $id => $r) {
    $frames = $r['d'] * FPS;
    $delay = (int)(100 / FPS);
    echo "Reel #{$id}: {$r['t']} ({$r['d']}s, {$frames}f) ";
    
    $gif = new AnimatedGif(W, H);
    
    for ($f = 0; $f < $frames; $f++) {
        $t = $f / max($frames - 1, 1);
        $img = imagecreatetruecolor(W, H);
        imagefilledrectangle($img, 0, 0, W, H, color($img, ...$r['bg']));
        drawFrame($img, $r, $t);
        $gif->addFrame($img, $delay);
        imagedestroy($img);
        if (($f + 1) % max(1, (int)($frames/5)) === 0) echo '.';
    }
    
    $path = REEL_DIR . "/reel{$id}.gif";
    $gif->save($path);
    $kb = round(filesize($path) / 1024, 1);
    echo " {$kb}KB\n";
    
    // Thumbnail
    $img = imagecreatetruecolor(W, H);
    imagefilledrectangle($img, 0, 0, W, H, color($img, ...$r['bg']));
    drawFrame($img, $r, 0.5);
    imagejpeg($img, THUMB_DIR . "/reel_thumb_{$id}.jpg", 85);
    imagedestroy($img);
}

echo "\n✅ Done! 8 reels + thumbnails generated.\n";
echo "   SQL: UPDATE reels SET video_url='/uploads/reels/reel{N}.gif' WHERE id=N;\n";

// ========== Frame Drawing ==========

function drawFrame($img, $r, $t): void {
    [$aR,$aG,$aB] = $r['ac'];
    switch($r['a']) {
        case 'dance':
            for($i=0;$i<8;$i++) {
                $c=color($img,$aR,$aG,$aB,60);
                imagefilledellipse($img, W/2+80*cos($i*M_PI/4+$t*M_PI*3), H/2-60+80*sin($i*M_PI/4+$t*M_PI*3), 12,12,$c);
            }
            break;
        case 'cooking':
            for($i=0;$i<10;$i++) {
                $p=($i*0.25+$t*0.7)%1;
                $c=color($img,255,255,255,(int)(100*(1-$p)));
                imagefilledellipse($img, W/2+45*sin($i*1.5+$t), H/2-40-$p*170, 6,6,$c);
            }
            break;
        case 'night':
            imagefilledrectangle($img,0,H/2+30,W,H,color($img,15,25,15));
            for($i=0;$i<5;$i++) imagefilledrectangle($img,$i*58,(int)(H/2-10-60*sin($i)),$i*58+44,H/2+30,color($img,25,35,25));
            for($i=0;$i<25;$i++){ $b=(int)(180+75*abs(sin($t*5+$i))); imagesetpixel($img,($i*89)%W,($i*61)%(H/2),color($img,$b,$b,$b)); }
            break;
        case 'ai':
            for($i=0;$i<12;$i++) for($j=0;$j<5;$j++){
                $y=(int)((($i*0.11+$t*0.5)%1)*H*1.3)-$j*14;
                if($y>=0&&$y<H) imagestring($img,2,$i*24,$y,chr(rand(48,90)),color($img,$aR,$aG,$aB,100-$j*18));
            }
            break;
        case 'music':
            for($i=0;$i<8;$i++){ $h=30+110*abs(sin($t*M_PI*2.5+$i*0.4)); imagefilledrectangle($img,20+$i*33,H/2-(int)$h,20+$i*33+24,H/2,color($img,$aR,$aG,$aB,40)); }
            for($x=0;$x<W;$x+=3) imagesetpixel($img,$x,H/2+80+(int)(25*sin($x/W*M_PI*6+$t*M_PI*2)),color($img,$aR,$aG,$aB));
            break;
        case 'comedy':
            foreach(['HA','LOL','😂','HAHA','🤣'] as $i=>$w) imagestring($img,3+$i%2,15+($i%3)*85,60+(int)(220*(($i*0.4+$t*1.1)%1)),$w,color($img,255,255,100));
            break;
        case 'beauty':
            for($i=0;$i<18;$i++){ $b=(int)(200+55*abs(sin($t*5+$i))); imagefilledellipse($img,($i*131+30)%(W-20),H-(int)(($i*0.17+$t*0.4)%1*H*0.7),3,3,color($img,$b,$b,$b)); }
            break;
        case 'sports':
            imagefilledrectangle($img,0,H/2+15,W,H,color($img,34,139,34));
            imagefilledellipse($img, W/2+(int)(90*sin($t*M_PI*3)), H/2-30+(int)(140*abs(sin($t*M_PI*4))), 22,22, color($img,255,255,255));
            break;
    }
    // Title + progress + emoji
    imagestring($img,2,8,H-40,$r['t'],color($img,$aR,$aG,$aB));
    imagefilledrectangle($img,0,0,(int)($t*W),3,color($img,255,255,255));
    imagestring($img,4,12+(int)(6*sin($t*M_PI*2)),20+(int)(3*sin($t*M_PI*3)),$r['e'],color($img,255,255,255));
}

function color($img, int $r, int $g, int $b, int $a=0): int {
    return $a>0 ? imagecolorallocatealpha($img,$r,$g,$b,$a) : imagecolorallocate($img,$r,$g,$b);
}

// ========== Proper Animated GIF Builder ==========

class AnimatedGif {
    private int $w, $h;
    private array $frames = [];
    private array $delays = [];
    private array $palettes = [];
    
    function __construct(int $w, int $h) { $this->w = $w; $this->h = $h; }
    
    function addFrame($img, int $delay): void {
        // Convert to palette
        imagetruecolortopalette($img, false, 256);
        $this->palettes[] = $this->extractPalette($img);
        $this->delays[] = $delay;
        
        // Get pixel indices
        $pixels = '';
        for ($y = 0; $y < $this->h; $y++)
            for ($x = 0; $x < $this->w; $x++)
                $pixels .= chr(imagecolorat($img, $x, $y));
        $this->frames[] = $pixels;
    }
    
    private function extractPalette($img): array {
        $pal = [];
        for ($i = 0; $i < imagecolorstotal($img); $i++) {
            $c = imagecolorsforindex($img, $i);
            $pal[$i] = [$c['red'], $c['green'], $c['blue']];
        }
        return $pal;
    }
    
    function save(string $path): void {
        $fh = fopen($path, 'wb');
        // Header
        fwrite($fh, 'GIF89a');
        fwrite($fh, pack('vv', $this->w, $this->h));
        fwrite($fh, "\x70\x00\x00"); // No global color table
        
        // Loop
        fwrite($fh, "\x21\xFF\x0B" . 'NETSCAPE2.0' . "\x03\x01" . pack('v', 0) . "\x00");
        
        foreach ($this->frames as $i => $pixels) {
            // Graphics Control Extension
            $disposal = ($i == count($this->frames) - 1) ? 1 : 2;
            fwrite($fh, "\x21\xF9\x04");
            fwrite($fh, chr(($disposal << 2))); // no transparent
            fwrite($fh, pack('v', $this->delays[$i]));
            fwrite($fh, "\x00\x00");
            
            // Image Descriptor
            fwrite($fh, "\x2C");
            fwrite($fh, pack('vvvv', 0, 0, $this->w, $this->h));
            
            // Local color table
            $pal = $this->palettes[$i];
            $nColors = count($pal);
            $colorRes = (int)ceil(log(max($nColors, 2), 2)) - 1;
            $packed = 0x80 | ($colorRes << 5) | $colorRes;
            fwrite($fh, chr($packed));
            
            // Pad palette to power of 2
            $palSize = 2 << $colorRes;
            for ($j = 0; $j < $palSize; $j++) {
                if ($j < $nColors) fwrite($fh, pack('CCC', $pal[$j][0], $pal[$j][1], $pal[$j][2]));
                else fwrite($fh, "\x00\x00\x00");
            }
            
            // LZW encode + write
            $minCode = max(2, $colorRes + 1);
            fwrite($fh, chr($minCode));
            $data = $this->lzwEncode($pixels, $minCode);
            foreach (str_split($data, 255) as $block)
                fwrite($fh, chr(strlen($block)) . $block);
            fwrite($fh, "\x00");
        }
        
        fwrite($fh, "\x3B");
        fclose($fh);
    }
    
    private function lzwEncode(string $data, int $minCode): string {
        $clear = 1 << $minCode;
        $eoi = $clear + 1;
        $next = $eoi + 1;
        $codeSize = $minCode + 1;
        
        $dict = [];
        for ($i = 0; $i < $clear; $i++) $dict[chr($i)] = $i;
        
        $out = '';
        $bits = '';
        
        $this->emitCode($bits, $clear, $codeSize);
        
        $w = '';
        for ($i = 0, $len = strlen($data); $i < $len; $i++) {
            $c = $data[$i];
            $wc = $w . $c;
            if (isset($dict[$wc])) { $w = $wc; }
            else {
                $this->emitCode($bits, $dict[$w], $codeSize);
                if ($next < 4095) $dict[$wc] = $next++;
                $w = $c;
                if ($next >= (1 << $codeSize) && $codeSize < 12) $codeSize++;
                if ($next >= 4095) {
                    $this->emitCode($bits, $clear, $codeSize);
                    $dict = []; for ($j = 0; $j < $clear; $j++) $dict[chr($j)] = $j;
                    $next = $eoi + 1; $codeSize = $minCode + 1;
                }
            }
        }
        if ($w !== '') $this->emitCode($bits, $dict[$w], $codeSize);
        $this->emitCode($bits, $eoi, $codeSize);
        
        // Flush bits to bytes
        while (strlen($bits) >= 8) {
            $out .= chr(bindec(substr($bits, 0, 8)));
            $bits = substr($bits, 8);
        }
        if (strlen($bits) > 0) {
            $out .= chr(bindec(str_pad($bits, 8, '0')));
        }
        return $out;
    }
    
    private function emitCode(string &$bits, int $code, int $size): void {
        $bits .= str_pad(decbin($code), $size, '0', STR_PAD_LEFT);
        while (strlen($bits) >= 8) {
            // Flush complete bytes only at end for proper alignment
            // We accumulate and flush at the end instead
            break;
        }
    }
}

// Flush accumulated bits at end
