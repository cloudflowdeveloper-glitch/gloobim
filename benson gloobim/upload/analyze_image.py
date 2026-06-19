#!/usr/bin/env python3
"""
Comprehensive image analysis script for livestream/stream reference image.
Uses Pillow to extract visual details, colors, layout structure, and UI elements.
"""

from PIL import Image
from collections import Counter, defaultdict
import math

IMAGE_PATH = "/home/z/my-project/upload/1000441348.jpg"

def color_distance(c1, c2):
    return math.sqrt(sum((a - b) ** 2 for a, b in zip(c1, c2)))

def rgb_to_hex(r, g, b):
    return f"#{r:02x}{g:02x}{b:02x}"

def brightness(c):
    return 0.299 * c[0] + 0.587 * c[1] + 0.114 * c[2]

def classify_color(r, g, b):
    h, s, v = rgb_to_hsv(r, g, b)
    if v < 0.08:
        return "Black"
    if v > 0.92 and s < 0.12:
        return "White"
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

def get_dominant_colors(img, region=None, n=10, sample_step=3):
    if region:
        x1, y1, x2, y2 = region
        if x2 <= x1 or y2 <= y1:
            return []
        img = img.crop((x1, y1, x2, y2))
    pixels = list(img.getdata())
    sampled = pixels[::sample_step]
    quantized = [(r // 8 * 8, g // 8 * 8, b // 8 * 8) for r, g, b in sampled]
    counter = Counter(quantized)
    return counter.most_common(n)

def safe_crop(img, x, y, w, h):
    iw, ih = img.size
    x = max(0, min(x, iw-1))
    y = max(0, min(y, ih-1))
    w = max(1, min(w, iw - x))
    h = max(1, min(h, ih - y))
    return img.crop((x, y, x+w, y+h))

def sample_region(img, name, x, y, w, h, n=5):
    region = safe_crop(img, x, y, w, h)
    colors = get_dominant_colors(region, n=n, sample_step=2)
    if not colors:
        return name, None
    c1 = colors[0][0]
    result = {
        'box': (x, y, w, h),
        'primary': (rgb_to_hex(*c1), c1, classify_color(*c1)),
        'all': [(rgb_to_hex(*c), c, classify_color(*c), cnt) for c, cnt in colors]
    }
    return name, result

def main():
    print("=" * 80)
    print("COMPREHENSIVE LIVESTREAM IMAGE ANALYSIS")
    print(f"File: {IMAGE_PATH}")
    print("=" * 80)
    
    img = Image.open(IMAGE_PATH)
    w, h = img.size
    
    print(f"\n{'─'*80}")
    print("1. IMAGE BASICS")
    print(f"{'─'*80}")
    print(f"   Size: {w} x {h} px | Mode: {img.mode} | Format: {img.format}")
    print(f"   Aspect: {w/h:.3f} | Orientation: {'Portrait (Mobile UI)' if w < h else 'Landscape'}")
    
    # ===== GLOBAL COLORS =====
    print(f"\n{'─'*80}")
    print("2. GLOBAL DOMINANT COLORS (Top 20)")
    print(f"{'─'*80}")
    overall = get_dominant_colors(img, n=20, sample_step=5)
    total_sampled = sum(c for _, c in overall)
    for i, (color, cnt) in enumerate(overall):
        pct = cnt / total_sampled * 100
        print(f"   {i+1:2d}. {rgb_to_hex(*color)} {str(color):>18s}  {classify_color(*color):<15s}  {pct:>5.1f}%")
    
    # ===== FIND NON-BLACK PIXELS =====
    print(f"\n{'─'*80}")
    print("3. NON-BLACK CONTENT ANALYSIS")
    print(f"{'─'*80}")
    
    # Find all pixels that are not near-black
    non_black = []
    sample_step = 4
    for y in range(0, h, sample_step):
        for x in range(0, w, sample_step):
            c = img.getpixel((x, y))[:3]
            b = brightness(c)
            if b > 30:  # non-near-black
                non_black.append((x, y, c, b))
    
    print(f"   Non-black pixels (b>30): {len(non_black)} out of {(w//sample_step)*(h//sample_step)} sampled ({len(non_black)/((w//sample_step)*(h//sample_step))*100:.1f}%)")
    
    # Cluster non-black pixels by Y range
    if non_black:
        y_min_content = min(p[1] for p in non_black)
        y_max_content = max(p[1] for p in non_black)
        x_min_content = min(p[0] for p in non_black)
        x_max_content = max(p[0] for p in non_black)
        
        print(f"   Content Y range: {y_min_content}-{y_max_content}px ({y_min_content/h*100:.1f}%-{y_max_content/h*100:.1f}%)")
        print(f"   Content X range: {x_min_content}-{x_max_content}px ({x_min_content/w*100:.1f}%-{x_max_content/w*100:.1f}%)")
        
        # Color distribution of non-black content
        color_counter = Counter()
        for x, y, c, b in non_black:
            name = classify_color(*c)
            color_counter[name] += 1
        
        print(f"\n   Color distribution of visible content:")
        for name, cnt in color_counter.most_common():
            pct = cnt / len(non_black) * 100
            print(f"   {name:<15s}: {cnt:>6d} ({pct:>5.1f}%)")
        
        # Group content by vertical bands
        print(f"\n   Content grouped by Y position bands:")
        band_height = h // 10
        for band in range(10):
            y_start = band * band_height
            y_end = (band + 1) * band_height
            band_pixels = [(x, y, c, b) for x, y, c, b in non_black if y_start <= y < y_end]
            if band_pixels:
                colors_in_band = Counter(classify_color(*c) for _, _, c, _ in band_pixels)
                avg_bright = sum(b for _, _, _, b in band_pixels) / len(band_pixels)
                print(f"   Band {band} ({y_start:>5d}-{y_end:>5d}px, {y_start/h*100:.0f}%-{y_end/h*100:.0f}%):")
                print(f"      Pixels: {len(band_pixels)}, Avg brightness: {avg_bright:.1f}")
                for cn, cc in colors_in_band.most_common(5):
                    print(f"      {cn}: {cc} ({cc/len(band_pixels)*100:.0f}%)")
            else:
                print(f"   Band {band} ({y_start:>5d}-{y_end:>5d}px): [empty/dark]")
        
        # Group content by horizontal position bands
        print(f"\n   Content grouped by X position bands:")
        band_width = w // 8
        for band in range(8):
            x_start = band * band_width
            x_end = (band + 1) * band_width
            band_pixels = [(x, y, c, b) for x, y, c, b in non_black if x_start <= x < x_end]
            if band_pixels:
                colors_in_band = Counter(classify_color(*c) for _, _, c, _ in band_pixels)
                avg_bright = sum(b for _, _, _, b in band_pixels) / len(band_pixels)
                print(f"   Band {band} ({x_start:>4d}-{x_end:>4d}px, {x_start/w*100:.0f}%-{x_end/w*100:.0f}%):")
                print(f"      Pixels: {len(band_pixels)}, Avg brightness: {avg_bright:.1f}")
                for cn, cc in colors_in_band.most_common(5):
                    print(f"      {cn}: {cc} ({cc/len(band_pixels)*100:.0f}%)")
            else:
                print(f"   Band {band} ({x_start:>4d}-{x_end:>4d}px): [empty/dark]")
    
    # ===== SPECIFIC UI REGION SAMPLING =====
    print(f"\n{'─'*80}")
    print("4. UI ELEMENT COLOR SAMPLING")
    print(f"{'─'*80}")
    
    # Define regions based on detected boundaries and transitions
    region_defs = [
        # Top area (status bar / header)
        ("status_bar",       0, 0, w, 80),
        ("header_area",      0, 80, w, 80),
        ("below_header",      0, 160, w, 70),
        
        # Upper content (around y=160 where pink/red content detected)
        ("upper_left_1",      20, 140, 120, 50),
        ("upper_left_2",      50, 150, 80, 30),
        
        # Mid content areas with blue/purple
        ("mid_blue_area_1",   30, 300, 200, 50),
        ("mid_blue_area_2",   0, 350, 150, 60),
        ("mid_purple_area",   50, 380, 120, 40),
        
        # Center area
        ("center_top",        0, h//3-30, w, 60),
        ("center_mid",        0, h//2-30, w, 60),
        ("center_bot",        0, h//2+30, w, 60),
        
        # Lower areas
        ("lower_1",           0, 1020, w, 60),
        ("lower_2",           0, 1100, w, 100),
        ("lower_3",           0, 1250, w, 80),
        
        # Bottom area (y~1440 where white/gray content detected)
        ("bottom_bar_top",    0, 1400, w, 50),
        ("bottom_bar_mid",    0, 1440, w, 60),
        ("bottom_bar_bot",    0, 1520, w, 80),
        
        # Very bottom
        ("very_bottom",       0, h-60, w, 60),
        ("bottom_left_icon",  10, h-80, 60, 60),
        ("bottom_center_icon", w//2-30, h-80, 60, 60),
        ("bottom_right_icon", w-70, h-80, 60, 60),
    ]
    
    for name, x, y, rw, rh in region_defs:
        _, result = sample_region(img, name, x, y, rw, rh)
        if result:
            p = result['primary']
            print(f"   {name:<25s}: {p[0]} {str(p[1]):>16s}  {p[2]}")
        else:
            print(f"   {name:<25s}: [empty/invalid region]")
    
    # ===== BRIGHT AREAS (buttons, icons, text) =====
    print(f"\n{'─'*80}")
    print("5. BRIGHT/COLORED UI ELEMENTS (brightness > 100)")
    print(f"{'─'*80}")
    
    bright_clusters = []
    for x, y, c, b in non_black:
        if b > 100:
            bright_clusters.append((x, y, c, b, classify_color(*c)))
    
    if bright_clusters:
        print(f"   Found {len(bright_clusters)} bright pixels")
        
        # Cluster by proximity
        clusters = defaultdict(list)
        used = set()
        cluster_id = 0
        
        for i, (x1, y1, c1, b1, n1) in enumerate(bright_clusters):
            if i in used:
                continue
            cluster = [(x1, y1, c1, b1, n1)]
            used.add(i)
            for j, (x2, y2, c2, b2, n2) in enumerate(bright_clusters):
                if j not in used and abs(x1-x2) < 40 and abs(y1-y2) < 40:
                    cluster.append((x2, y2, c2, b2, n2))
                    used.add(j)
            clusters[cluster_id] = cluster
            cluster_id += 1
        
        # Show significant clusters
        significant = {k: v for k, v in clusters.items() if len(v) >= 3}
        print(f"   Significant clusters (>=3 pixels): {len(significant)}")
        
        for cid, pixels in sorted(significant.items(), key=lambda x: min(p[1] for p in x[1]))[:25]:
            xs = [p[0] for p in pixels]
            ys = [p[1] for p in pixels]
            cx = min(xs)
            cy = min(ys)
            cw = max(xs) - cx + 1
            ch = max(ys) - cy + 1
            colors = Counter(p[4] for p in pixels)
            avg_b = sum(p[3] for p in pixels) / len(pixels)
            dominant_color = colors.most_common(1)[0][0]
            print(f"   Cluster {cid:3d}: pos=({cx:>4d},{cy:>5d}) size={cw:>3d}x{ch:>3d} "
                  f"pixels={len(pixels):>4d} avg_b={avg_b:>6.1f} colors={dict(colors)}")
    
    # ===== GRADIENT ANALYSIS AT KEY Y POSITIONS =====
    print(f"\n{'─'*80}")
    print("6. DETAILED HORIZONTAL SCANS AT KEY Y POSITIONS")
    print(f"{'─'*80}")
    
    key_y_positions = [0, 60, 100, 150, 160, 200, 250, 320, 370, 400, 450, 
                      530, 615, 642, 750, 800, 900, 1020, 1050, 1110, 1180,
                      1270, 1320, 1400, 1440, 1500, 1550, h-1]
    
    for y in key_y_positions:
        if y >= h:
            continue
        row = []
        for x in range(0, w, 4):
            c = img.getpixel((x, y))[:3]
            row.append((x, c))
        
        non_dark = [(x, c) for x, c in row if brightness(c) > 25]
        if non_dark:
            color_types = Counter(classify_color(*c) for _, c in non_dark)
            x_range = f"x={non_dark[0][0]}-{non_dark[-1][0]}"
            print(f"   y={y:>5d} ({y/h*100:>5.1f}%): {len(non_dark):>3d} visible pixels | {x_range} | {dict(color_types)}")
        else:
            print(f"   y={y:>5d} ({y/h*100:>5.1f}%): [all dark]")
    
    # ===== CORNER ANALYSIS =====
    print(f"\n{'─'*80}")
    print("7. CORNER & EDGE PIXELS")
    print(f"{'─'*80}")
    
    edge_points = [
        ("Top-Left",       3, 3),
        ("Top-Center",     w//2, 3),
        ("Top-Right",      w-4, 3),
        ("Mid-Left",       3, h//2),
        ("Mid-Center",     w//2, h//2),
        ("Mid-Right",      w-4, h//2),
        ("Bot-Left",       3, h-4),
        ("Bot-Center",     w//2, h-4),
        ("Bot-Right",      w-4, h-4),
    ]
    
    for name, x, y in edge_points:
        c = img.getpixel((x, y))[:3]
        b = brightness(c)
        print(f"   {name:<15s}: {rgb_to_hex(*c)} b={b:>6.1f} ({classify_color(*c)})")
    
    # ===== SECTION BOUNDARY DETECTION =====
    print(f"\n{'─'*80}")
    print("8. MAJOR SECTION BOUNDARIES")
    print(f"{'─'*80}")
    
    boundaries = []
    prev_avg = None
    for y in range(0, h, 3):
        row_pixels = [img.getpixel((x, y))[:3] for x in range(0, w, 15)]
        row_avg = tuple(sum(c[i] for c in row_pixels) // len(row_pixels) for i in range(3))
        
        if prev_avg:
            dist = color_distance(row_avg, prev_avg)
            if dist > 35:
                boundaries.append((y, dist, row_avg))
        prev_avg = row_avg
    
    # Group close boundaries
    groups = []
    if boundaries:
        current = [boundaries[0]]
        for b in boundaries[1:]:
            if b[0] - current[-1][0] < 20:
                current.append(b)
            else:
                groups.append(current)
                current = [b]
        groups.append(current)
    
    major = [g for g in groups if max(b[1] for b in g) > 45]
    print(f"   {len(major)} major boundaries detected:")
    for i, group in enumerate(major[:20]):
        y_pos = group[0][0]
        max_d = max(b[1] for b in group)
        c = group[0][2]
        print(f"   {i+1:2d}. y={y_pos:>5d}px ({y_pos/h*100:>5.1f}%) dist={max_d:>6.1f} color={rgb_to_hex(*c)} ({classify_color(*c)})")
    
    # ===== FINAL SUMMARY =====
    print(f"\n{'='*80}")
    print("ANALYSIS COMPLETE")
    print(f"{'='*80}")
    
    return img, non_black, bright_clusters, major

if __name__ == "__main__":
    main()
