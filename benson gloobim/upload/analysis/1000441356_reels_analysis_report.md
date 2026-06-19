# Reels/Clips Page UI Analysis Report
## Image: `/upload/1000441356.jpg` | 740×1600px | Portrait Mobile

---

## 1. OVERALL LAYOUT
- **Full-screen immersive vertical video layout** (portrait mode, 740×1600px, aspect ~9:19.5)
- **Dark theme** — ~65% of all pixels are pure black (#000000) or near-black (brightness <30)
- **Content density**: Only ~28.5% of pixels have brightness >25, indicating the video itself is dark/moody
- The UI follows the standard TikTok/Instagram Reels pattern: full-screen video with overlaid UI controls
- **No split-screen** — single full-screen video view

---

## 2. BACKGROUND / VIDEO AREA
- **Position**: Fills the entire screen (0,0 to 740,1600)
- **Content**: A dark video showing:
  - A **human figure/person** in the center-left area (x≈40-480, y≈280-620) wearing warm-colored clothing (orange/red/amber tones)
  - **Warm color palette**: Orange (#81452d to #fca96d), Red (#7b422e to #d66b6e), Amber (#fde9a3 to #fba19f)
  - A **purple/blue abstract element** on the right side (x≈500-700, y≈250-470) — possibly a filter, effect, or secondary content
  - **Purple tones**: #53278e to #b45af0, #6134ca to #9752db
  - **Blue accents**: #4d2d96 to #793ee4
- **Video opacity**: Content fades to black at top (y<140) and bottom (y>1250) — indicating gradient overlays

---

## 3. TOP NAVIGATION / HEADER (y=55-160)

### 3a. Status Bar (y=0-55)
- **Left side** (x≈57-96, y≈27-45):
  - **Signal/WiFi/Battery icons** in white (#ffffff) and light gray (#959595 to #b7b7b7)
  - Small, standard iOS/Android status bar icons
  - Brightness: 148-255

- **Right side** (x≈591-700, y≈27-45):
  - **Battery indicator**: x≈615-648, white bars (#f2f2f2 to #ffffff) — 3-4 vertical bars
  - **Signal bars**: x≈669-699, white (#fdfdfd to #fefefe) — 5 small horizontal bars
  - Brightness: 185-255

- **Center**: Dark/black (likely camera notch or cutout)

### 3b. Navigation Controls (y=55-160)

#### Back/Menu Button (x≈30-90, y≈76-142)
- A **gradient avatar circle** ~50px diameter:
  - Gradient from magenta/pink (#c93a5d) at edges to pink (#e87c89) at center
  - Multiple concentric color rings: outer red (#ef4249), middle pink (#b6878f), inner gray (#938690)
  - **Exact colors**: #c54544 → #b9758a → #9f8d9d → #8d8489 → #a4959c
  - **This is a creator profile avatar with a colored ring border** (like Instagram's gradient ring)

#### Username / Creator Info (x≈108-280, y≈88-130)
- **White text elements** at multiple positions:
  - x≈108-117, y≈88-100: White (#ffffff to #f5f5f5) — username text start
  - x≈126-174, y≈88-130: Gray text (#666666 to #bababa) — secondary text/description
  - x≈159-183, y≈91-97: Mix of gray shades — "Following" button or tag text

#### Blue Tab/Label (x≈210-250, y≈88-100)
- A **blue indicator/pill**: #1b87e4 to #71c6ff (vibrant blue)
  - Appears to be a **"Reels" tab indicator** or content label
  - Size: ~40×12px
  - Exact hex: #018ffb, #0389fc, #0080ee, #058eff

#### Follow Button (x≈174-200, y≈88-130)
- White/gray outline button area
  - Text appears to be white (#ffffff to #f7f7f7)
  - Size: ~26×42px

#### Search/Discovery Icon (x≈522-554, y≈96-124)
- White icon (#ffffff to #f8f8f8)
- **Search magnifying glass icon** — simple, clean, white
- Size: ~32×28px

#### Right-side Notification Icons (x≈600-720, y≈78-130)

**Icon Cluster 1** (x≈618-640, y≈78-120):
- **RED notification/bell icon** (#e4454d to #ff3743)
- Contains white highlight pixels (#ffffff to #ffd5cf) suggesting a lit/unread state
- Size: ~22×42px
- Likely a **DM/Messages** or **Notifications bell** icon

**Icon Cluster 2** (x≈686-714, y≈78-120):
- **RED icon** (#e23139 to #ff393f)  
- Also has white accents (#ffd9d2 to #ffcece)
- Size: ~28×42px
- Likely a **Share** or **More options** icon

**Gray/White separator text** (x≈600-660, y≈118-130):
- Gray text (#979797 to #ffffff) — possibly "Reels" or section label text

---

## 4. RIGHT SIDE ACTION BUTTONS

Unlike clean TikTok-style white icons, this image has **colorful interactive elements** embedded in the video:

### 4a. Purple/Magenta Element (x≈600-616, y≈179-200)
- Bright **magenta/purple** (#c838ef to #b01ce0) 
- Size: ~16×21px
- Likely a **sticker, emoji, or AR filter effect** on the video

### 4b. Orange/Gold Element (x≈660-690, y≈236-290)
- **Orange/Gold gradient** (#dead5e to #ffe552)
- Transitions to **Blue/Purple** (#9552f7 to #7246d1) below it
- Size: ~30×54px
- Likely a **shopping/product tag** or interactive element

### 4c. Purple Gradient Bar (x≈600-650, y≈353-450)
- **Vertical purple gradient** strip:
  - Top: #8a55eb → #b381fc (light purple)
  - Middle: #a269f4 → #b546ed (medium purple)  
  - Bottom: #865be8 → #7d357e (dark purple to pink)
- Size: ~50×97px
- This appears to be a **product card or shoppable element** in a purple theme

### 4d. Pink/Red Heart (x≈644-720, y≈542-570)
- **Pink/Red heart-shaped element** (#f14074 to #ff6285)
- Very bright, prominent: brightness 123-167
- Size: ~76×28px
- **This is likely the "LIKE" heart button** — shown as activated/red

### 4e. Additional Elements
- **Purple elements** at x≈544-640, y≈580-640: (#581dc5 to #80419a) — more filter effects or decorative elements
- **Blue content** at x≈644-700, y≈620-660: (#5587e8 to #7189e6) with white accents — possibly a share/save icon

---

## 5. BOTTOM OVERLAY SECTION

### 5a. Creator/Username Area (y≈1050-1170)
- **Gray/white text** at x≈40-80, y≈1060-1100: (#8d847b to #b7ab9b)
- Multiple text lines with **mixed brightness** (120-180)
- **Username** appears at x≈40-120, y≈1060-1085 in white/light gray
- **Description text** below in slightly darker gray
- **Tagged/mentioned text** at x≈100-300, y≈1060-1090 with similar gray tones

### 5b. Action Button (x≈585-640, y≈1180-1200)
- **White rounded rectangle/pill** button:
  - Colors: #f5f5f7 to #fefeff (white/near-white)
  - With gray accents (#7b7b7d)
  - Size: ~55×20px
  - Likely a **"Follow"** button

### 5c. Description/Music Info (y≈1220-1280)
- **Mixed content area**:
  - Pink/red emoji elements at x≈51-69, y≈1232-1244: (#ea4c4d to #ffe4ee) — heart emoji in description
  - Gray/white text spanning x≈105-300, y≈1220-1265: description text
  - More text at x≈120-280 with varying gray tones

### 5d. Music Ticker/Info (y≈1316-1380)
- **Colorful content** suggesting product cards or tags:
  - Pink: (#d15975 to #ff92b2) at x≈429-450, y≈1316-1336
  - Orange/Gold: (#ffbe76 to #f8d346) at x≈444-456, y≈1320-1336
  - Blue: (#73b8e2 to #c1d5e0) at x≈513-540, y≈1324-1340
  - White text with gray accents at x≈342-370, y≈1332-1360

---

## 6. SEARCH BARS / INPUT FIELDS
- **No visible search bar** in the traditional sense
- A **search icon** (magnifying glass) is present in the header at x≈522-554, y≈96-124
- White color, tap-to-activate style (not an expandable search field)

---

## 7. PROGRESS INDICATORS / SWIPE DOTS
- **No visible progress bar or swipe indicator dots** were detected
- The video appears to be a single, paused/static frame
- No horizontal scroll indicators found

---

## 8. COLOR SCHEME

### Primary Colors (exact hex values):
| Element | Color | Usage |
|---------|-------|-------|
| Background | #000000 | Full black background |
| Video warm tones | #81452d - #fca96d | Orange/amber clothing/content |
| Video purple | #53278e - #b45af0 | Purple filter/overlay |
| Video red | #7b422e - #d66b6e | Red tones in video |
| UI White | #ffffff | All text, icons, buttons |
| UI Gray | #959595 - #b7b7b7 | Secondary text, inactive elements |
| UI Dark Gray | #565656 - #7d7d7d | Tertiary text |
| Accent Blue | #1b87e4 - #71c6ff | Tab indicator, links |
| Accent Red | #e4454d - #ff3743 | Notification icons, hearts |
| Avatar Ring | #c93a5d - #e87c89 | Gradient pink/magenta ring |
| Heart/Like | #f14074 - #ff6285 | Activated like button |
| Purple Element | #8a55eb - #b381fc | Interactive element |
| Gold/Orange | #dead5e - #ffe552 | Product tag/CTA |
| Bottom Nav BG | #000000 / #010101 | Black background |

### Gradient Patterns:
- **Top-to-bottom video fade**: Black at y<140, content at y=140-1200, fade to black at y>1250
- **Avatar ring**: Radial gradient from #c93a5d (outer) to #938690 (mid) to #b9758a (inner)
- **Purple element**: Vertical linear gradient from #b381fc (top) to #7d357e (bottom)

---

## 9. BUTTON STYLES

| Button | Style | Colors | Size |
|--------|-------|--------|------|
| Follow button | **Rounded pill/outline** | White fill on dark, or white text | ~26×42px |
| Search icon | **Icon only, no container** | White (#ffffff) | ~32×28px |
| Notification icons | **Icon only, red** | Red (#e4454d to #ff3743) | ~22×42px each |
| Like/Heart | **Icon, filled/activated** | Pink/Red (#f14074) | ~76×28px |
| Bottom nav items | **Icon only** | White/Gray | ~30×30px |
| Create (+) button | **Gradient circle** | Orange/Gold (#ef9b00 to #fca70c) | ~16×20px |

---

## 10. TEXT STYLES

| Text Type | Estimated Size | Weight | Color |
|-----------|---------------|--------|-------|
| Username/Handle | ~16-18px (scaled) | Bold/semibold | White (#ffffff) |
| Secondary text | ~13-14px (scaled) | Regular | Gray (#959595) |
| "Following" label | ~11-12px | Medium | Gray (#bababa) |
| Tab label | ~12-13px | Semibold | Blue (#1b87e4) or White |
| Description | ~13-14px | Regular | White/Light gray |
| Music info | ~11-12px | Regular | Gray (#939393) |
| Status bar time | ~11px | Medium | Gray (#949494) |

---

## 11. SPECIAL EFFECTS

- **Top gradient overlay**: Black-to-transparent gradient from y=0 to y≈140 (hides status bar area over video)
- **Bottom gradient overlay**: Transparent-to-black gradient from y≈1100 to y≈1400 (darkens video for text readability)
- **Avatar glow/ring**: Gradient magenta-to-pink ring around creator avatar (Instagram-style)
- **AR effects/stickers**: Purple, gold, and blue elements overlaid on video content (interactive decorations)
- **Heart animation**: Bright pink/red heart element suggesting active animation state

---

## 12. FLOATING / OVERLAPPING CTA BUTTONS

- **Product tag** at x≈660-690, y≈236-290: Gold/orange to blue gradient element overlaid on video
- **Purple interactive card** at x≈600-650, y≈353-450: Vertical purple element
- **Activated Like heart** at x≈644-720, y≈542-570: Bright pink/red heart floating over content
- **No prominent "Shop Now" or "Buy" CTA** detected — this appears to be a standard content reel, not a shoppable one

---

## 13. EXACT POSITIONING OF EVERY ELEMENT

| Element | X Position | Y Position | Size |
|---------|-----------|-----------|------|
| Status bar (signal) | x: 57-96 | y: 27-45 | ~39×18px |
| Status bar (battery/signal) | x: 591-700 | y: 27-45 | ~109×18px |
| Creator avatar | x: 30-90 | y: 76-142 | ~60×66px |
| Username text | x: 108-280 | y: 88-130 | ~172×42px |
| "Following" button | x: 174-200 | y: 88-130 | ~26×42px |
| Blue tab indicator | x: 210-250 | y: 88-100 | ~40×12px |
| Search icon | x: 522-554 | y: 96-124 | ~32×28px |
| Notification icon 1 | x: 618-640 | y: 78-120 | ~22×42px |
| Notification icon 2 | x: 686-714 | y: 78-120 | ~28×42px |
| Purple sticker | x: 600-616 | y: 179-200 | ~16×21px |
| Gold product tag | x: 660-690 | y: 236-290 | ~30×54px |
| Purple card | x: 600-650 | y: 353-450 | ~50×97px |
| Like/Heart button | x: 644-720 | y: 542-570 | ~76×28px |
| Creator description | x: 40-300 | y: 1050-1170 | ~260×120px |
| Follow button | x: 585-640 | y: 1180-1200 | ~55×20px |
| Description text | x: 105-300 | y: 1220-1265 | ~195×45px |
| Music ticker | x: 342-540 | y: 1316-1380 | ~198×64px |
| Bottom nav bar | x: 0-740 | y: 1450-1570 | ~740×120px |
| iOS home indicator | x: 242-500 | y: 1576-1584 | ~258×8px |

---

## 14. BRAND LOGOS / WATERMARKS

- **No visible brand logo** or watermark was detected
- The app appears to use **system-standard icons** (no custom brand mark visible)
- The gradient avatar ring style is reminiscent of **Instagram** (gradient ring around profile picture)

---

## 15. BOTTOM NAVIGATION BAR (y≈1450-1570)

### Structure:
- **Black background** (#000000)
- **5 navigation items** arranged horizontally, evenly spaced
- **Home indicator bar** (iOS-style) at very bottom

### Navigation Items (left to right):

| Position | X Center | Icon Color | Type |
|----------|----------|------------|------|
| 1. Home | x≈68 | White (#ffffff) | House icon |
| 2. Search/Discover | x≈210 | White (#ffffff) | Magnifying glass |
| 3. Create (+) | x≈326 | **Orange/Gold** (#ef9b00-#fca70c) | Plus in circle |
| 4. Reels/Video | x≈410 | **Red** (#e82c3d) | Video/reels icon |
| 5. Profile | x≈512 | **Red** (#da3748) | User icon |

### Key Notes:
- The **Create (+) button** has a distinctive **orange/gold gradient** — NOT the standard white or blue found in most apps
- **Reels tab** (position 4) is colored **red** (#e82c3d) suggesting it's the **active tab**
- Items 1-2 are white (inactive), items 3-5 are colored (3 is always orange, 4-5 may be active states)
- **Home indicator**: White rounded bar at y≈1576-1584, x≈242-500, ~258×8px, color #f5f5f5 to #f8f8f8

### Active State Indicator:
- No visible dot or line under the active tab
- The red color on the Reels icon serves as the active indicator

---

## TAILWIND CSS IMPLEMENTATION NOTES

### Key Tailwind Classes to Use:
```php
// Container
<div class="relative w-full h-screen bg-black overflow-hidden">

// Status bar
<div class="flex justify-between px-4 pt-2">
  // Signal icons (white)
  <div class="text-white/70 text-xs">...</div>
  // Battery/time (white)
  <div class="text-white text-xs">...</div>
</div>

// Header overlay
<div class="flex items-center justify-between px-4 py-3">
  // Avatar with gradient ring
  <div class="w-10 h-10 rounded-full" 
       style="background: linear-gradient(135deg, #c93a5d, #e87c89); padding: 2px;">
    <img class="w-full h-full rounded-full border-2 border-black" />
  </div>
  // Username
  <span class="text-white font-semibold text-sm">username</span>
  // Blue tab
  <span class="text-blue-500 text-xs font-semibold">Reels</span>
  // Search icon
  <div class="text-white w-8 h-8">🔍</div>
  // Notification icons
  <div class="text-red-500 w-6 h-6">🔔</div>
</div>

// Bottom gradient overlay
<div class="absolute bottom-0 left-0 right-0 h-1/3" 
     style="background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);">
  // Creator info
  <div class="px-4 pb-4">
    <div class="flex items-center gap-2">
      <span class="text-white font-semibold">@username</span>
      <button class="px-3 py-1 border border-white/50 rounded-md text-white text-xs">
        Follow
      </button>
    </div>
    <p class="text-white/80 text-sm mt-1">Description text here...</p>
    <div class="flex items-center gap-1 mt-1 text-white/60 text-xs">
      <span>♪</span>
      <span>Music name - Artist</span>
    </div>
  </div>
</div>

// Right side actions
<div class="absolute right-3 top-1/3 flex flex-col items-center gap-5">
  <button class="text-pink-500">❤️</button>
  <button class="text-white">💬</button>
  <button class="text-white">↗️</button>
  <button class="text-white">⋯</button>
  <div class="w-8 h-8 rounded-full border-2 border-white">
    <img class="w-full h-full rounded-full" />
  </div>
</div>

// Bottom nav bar
<div class="absolute bottom-0 left-0 right-0 bg-black pb-6 pt-2">
  <div class="flex justify-around items-center">
    <div class="text-white/60">🏠</div>
    <div class="text-white/60">🔍</div>
    <div class="text-orange-500">➕</div>  <!-- Orange create button -->
    <div class="text-red-500">🎬</div>  <!-- Active reels tab -->
    <div class="text-red-500">👤</div>
  </div>
  // iOS home indicator
  <div class="mx-auto mt-2 w-32 h-1 bg-white/30 rounded-full"></div>
</div>
```

### Exact Color Values for CSS Variables:
```css
:root {
  --bg-primary: #000000;
  --bg-gradient-top: linear-gradient(to bottom, rgba(0,0,0,0.7), transparent);
  --bg-gradient-bottom: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
  --text-primary: #ffffff;
  --text-secondary: #959595;
  --text-tertiary: #565656;
  --accent-blue: #1b87e4;
  --accent-red: #e4454d;
  --accent-pink: #f14074;
  --accent-purple: #8a55eb;
  --accent-orange: #fca70c;
  --avatar-ring: linear-gradient(135deg, #c93a5d, #e87c89);
  --like-active: #ff6285;
}
```
