#!/usr/bin/env python3
"""
Deep-dive analysis focused on the visible UI elements in the livestream image.
"""

from PIL import Image
from collections import Counter, defaultdict
import math

IMAGE_PATH = "/home/z/my-project/upload/1000441348.jpg"

def rgb_to_hex(r, g, b):
    return f"#{r:02x}{g:02x}{b:02x}"

def brightness(c):
    return 0.299 * c[0] + 0.587 * c[1] + 0.114 * c[2]

def classify_color(r, g, b):
    h, s, v = rgb_to_hsv(r, g, b)
    if v < 0.08: return "Black"
    if v > 0.92 and s < 0.12: return "White"
    if s < 0.10:
        if v < 0.25: return "Very Dark Gray"
        if v < 0.45: return "Dark Gray"
        if v < 0.65: return "Gray"
        return "Light Gray"
    if h < 15 or h >= 345: return "Red"
    if h < 40: return "Orange"
    if h < 70: return "Yellow"
    if h < 160: return "Green"
    if h < 200: return "Cyan/Teal"
    if h < 260: return "Blue"
    if h < 290: return "Purple"
    return "Pink"

def rgb_to_hsv(r, g, b):
    r, g, b = r/255.0, g/255.0, b/255.0
    mx, mn = max(r, g, b), min(r, g, b)
    diff = mx - mn
    v = mx
    h = 0
    if diff > 0:
        if mx == r: h = (60 * ((g - b) / diff) + 360) % 360
        elif mx == g: h = (60 * ((b - r) / diff) + 120) % 360
        else: h = (60 * ((r - g) / diff) + 240) % 360
    s = 0 if mx == 0 else diff / mx
    return h, s, v

def scan_region_detail(img, x1, y1, x2, y2, label=""):
    """Detailed scan of a specific region."""
    w = img.size[0]
    h = img.size[1]
    x1, y1 = max(0, x1), max(0, y1)
    x2, y2 = min(w, x2), min(h, y2)
    
    all_pixels = []
    bright_pixels = []
    
    for y in range(y1, y2, 2):
        for x in range(x1, x2, 2):
            c = img.getpixel((x, y))[:3]
            b = brightness(c)
            all_pixels.append((x, y, c, b))
            if b > 50:
                bright_pixels.append((x, y, c, b))
    
    if not bright_pixels:
        return None
    
    colors = Counter(classify_color(*c) for _, _, c, _ in bright_pixels)
    avg_bright = sum(p[3] for p in bright_pixels) / len(bright_pixels)
    max_bright = max(p[3] for p in bright_pixels)
    
    # Find brightest pixel
    brightest = max(bright_pixels, key=lambda p: p[3])
    
    return {
        'label': label,
        'region': (x1, y1, x2, y2),
        'total_sampled': len(all_pixels),
        'bright_count': len(bright_pixels),
        'bright_pct': len(bright_pixels) / max(len(all_pixels), 1) * 100,
        'avg_brightness': avg_bright,
        'max_brightness': max_bright,
        'brightest_pixel': (brightest[0], brightest[1], rgb_to_hex(*brightest[2]), brightest[3]),
        'color_distribution': dict(colors.most_common()),
    }

def main():
    img = Image.open(IMAGE_PATH)
    w, h = img.size
    
    print("=" * 80)
    print("DEEP-DIVE UI ELEMENT ANALYSIS")
    print("=" * 80)
    
    # Based on initial scan, define focused regions to analyze
    regions = [
        # Status bar area (y=0-80)
        ("Status Bar",            0, 0, w, 80),
        
        # Top UI elements (y=60-120) - likely back button, share, etc.
        ("Top Nav Left",          20, 55, 120, 120),
        ("Top Nav Right Icons",   660, 55, 770, 120),
        ("Top Right Corner",      720, 15, 796, 80),
        
        # Header/search area (y=100-170)
        ("Header Row",            20, 100, 770, 170),
        ("Header Left Text",      24, 100, 200, 140),
        ("Header Center Tabs",    200, 100, 500, 170),
        ("Header Right",          500, 100, 770, 170),
        
        # Content area with pink/red elements (y=140-200) - likely product/brand cards
        ("Pink Row Left",         24, 140, 200, 200),
        ("Pink Row Center-Left",  200, 140, 400, 200),
        ("Pink Row Center-Right", 400, 140, 600, 200),
        ("Pink Row Right",        600, 140, 770, 200),
        
        # Avatar/brand area (y=148-180) - detected pink/red
        ("Avatar Area",           40, 148, 100, 185),
        ("Brand Text Area",       100, 148, 250, 185),
        ("Action Buttons",        250, 148, 400, 185),
        ("Right Section",         600, 148, 770, 185),
        
        # Gray bar / separator (y=225-280) 
        ("Gray Bar",              0, 220, w, 290),
        
        # Blue/purple content (y=250-400) - likely products or images
        ("Blue Content Left",     40, 250, 250, 400),
        ("Blue Content Mid",      250, 250, 450, 400),
        ("Blue Content Right",    450, 250, 700, 400),
        
        # Purple content (y=370-420) - detected
        ("Purple Section",        40, 370, 700, 420),
        
        # Stream content (y=530-650)
        ("Stream Content",       0, 530, w, 650),
        
        # Large colorful area (y=642-800) - blue/purple dominant
        ("Large Content 1",       0, 640, w//2, 800),
        ("Large Content 2",       w//2, 640, w, 800),
        
        # Red/pink/orange section (y=750-1000)
        ("Warm Content Area",     0, 750, w, 1050),
        ("Warm Content Left",     0, 850, w//2, 1050),
        ("Warm Content Right",    w//2, 850, w, 1050),
        
        # Lower section (y=1000-1200)
        ("Lower Content 1",       0, 1000, w, 1100),
        ("Lower Content 2",       0, 1050, w, 1200),
        
        # Bottom area (y=1270-1600)
        ("Bottom Section 1",      0, 1270, w, 1400),
        ("Bottom Section 2",      0, 1400, w, 1520),
        
        # Very bottom bar
        ("Bottom Bar",            0, 1500, w, h),
        ("Bottom Nav Icons",      0, 1530, w, h),
        ("Bottom Left Icon",      20, 1530, 140, h),
        ("Bottom Center Icon",    w//2-80, 1530, w//2+80, h),
        ("Bottom Right Icon",     w-140, 1530, w, h),
    ]
    
    for name, x1, y1, x2, y2 in regions:
        result = scan_region_detail(img, x1, y1, x2, y2, name)
        if result:
            print(f"\n{'─'*60}")
            print(f"  {result['label']}")
            print(f"  Region: ({result['region']})")
            print(f"  Bright pixels: {result['bright_count']}/{result['total_sampled']} ({result['bright_pct']:.1f}%)")
            print(f"  Avg brightness: {result['avg_brightness']:.1f} | Max: {result['max_brightness']:.1f}")
            print(f"  Brightest pixel: pos={result['brightest_pixel'][0]},{result['brightest_pixel'][1]} "
                  f"color={result['brightest_pixel'][2]} b={result['brightest_pixel'][3]:.1f}")
            print(f"  Colors: {result['color_distribution']}")
        else:
            print(f"\n{'─'*60}")
            print(f"  {name}: [all dark - no visible content]")
    
    # ===== Extract exact hex colors at specific strategic points =====
    print(f"\n{'='*80}")
    print("EXACT PIXEL COLOR SAMPLING AT STRATEGIC POINTS")
    print(f"{'='*80}")
    
    # Sample at key points based on cluster positions
    key_points = [
        # From cluster analysis
        ("Status bar icon", 40, 25),
        ("Status bar right", 680, 25),
        ("Nav right icon 1", 680, 75),
        ("Nav right icon 2", 740, 25),
        ("Back button area", 40, 75),
        ("Header text start", 40, 115),
        ("Header tab center", 300, 160),
        ("Avatar/brand area", 55, 158),
        ("Pink indicator", 88, 158),
        ("Orange element", 195, 158),
        ("Gray text area", 240, 160),
        ("Right content", 660, 158),
        ("Cyan element", 510, 158),
        ("Purple icon", 350, 158),
        ("Blue content start", 50, 330),
        ("Blue content mid", 330, 330),
        ("Purple content", 400, 400),
        ("Stream area left", 50, 535),
        ("Stream area right", 700, 535),
        ("Blue-purple trans", 600, 750),
        ("Red content", 100, 900),
        ("Orange content", 600, 900),
        ("Bottom bar 1", 50, 1540),
        ("Bottom bar 2", w//2, 1540),
        ("Bottom bar 3", w-50, 1540),
        ("Bottom nav active", 400, 1570),
    ]
    
    print(f"\n  {'Location':<30s} {'Hex':<12s} {'RGB':<18s} {'Brightness':<12s} {'Classification'}")
    print(f"  {'-'*88}")
    
    for name, x, y in key_points:
        if 0 <= x < w and 0 <= y < h:
            c = img.getpixel((x, y))[:3]
            b = brightness(c)
            cls = classify_color(*c)
            print(f"  {name:<30s} {rgb_to_hex(*c):<12s} {str(c):<18s} {b:>8.1f}      {cls}")
    
    # ===== VERTICAL SCANS (looking for UI columns) =====
    print(f"\n{'='*80}")
    print("VERTICAL COLUMN SCANS")
    print(f"{'='*80}")
    
    x_positions = [100, 200, 300, 400, 500, 600, 700, w-50]
    for x in x_positions:
        visible = 0
        colors = Counter()
        for y in range(0, h, 4):
            c = img.getpixel((x, y))[:3]
            b = brightness(c)
            if b > 50:
                visible += 1
                colors[classify_color(*c)] += 1
        pct = visible / (h // 4) * 100
        if visible > 0:
            print(f"  x={x:>4d}: {visible:>3d} visible ({pct:.0f}%) | {dict(colors.most_common(5))}")
        else:
            print(f"  x={x:>4d}: [all dark]")

if __name__ == "__main__":
    main()
