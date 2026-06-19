# Marketplace UI Reference Analysis — `1000441349.jpg`

**Image Dimensions:** 740 × 1600 pixels (portrait mobile screenshot)  
**App Type:** Dark-themed e-commerce / marketplace mobile application  

---

## 1. OVERALL LAYOUT STRUCTURE (Top → Bottom)

The page is a single vertically-scrolling marketplace home screen with these distinct sections:

| # | Section | Y-Range | Height | Description |
|---|---------|---------|--------|-------------|
| 1 | **Status Bar** | 0–50px | 50px | System status bar (time, battery, signal) |
| 2 | **Header / Search** | 50–130px | 80px | Logo/brand name (left) + Search bar (center) + Action icons (right) |
| 3 | **Promotional Banner** | 130–250px | 120px | Large hero/promo banner with purple gradient background |
| 4 | **Category Icons Row** | 200–250px | ~50px | Horizontal scrollable category icons (overlaps bottom of promo banner) |
| 5 | **Product Section 1** | 250–380px | 130px | Horizontal product cards with purple/red accents |
| 6 | **Gap/Separator** | 380–420px | 40px | Dark separator |
| 7 | **Product Section 2** | 420–540px | 120px | Products with ratings, purple accent elements |
| 8 | **Gap** | 540–580px | 40px | Dark separator |
| 9 | **Section Header** | 580–650px | 70px | Section title with "See All" link + star ratings |
| 10 | **Banner Scroll Row** | 650–720px | 70px | Horizontal scrollable banner cards / promotional cards |
| 11 | **Product Cards Grid** | 720–880px | 160px | 2-column product card grid with images, prices |
| 12 | **Section Transition** | 880–960px | 80px | Dark transition with section header text |
| 13 | **Flash Deals** | 960–1070px | 110px | Bright product card row (deals/discounts) |
| 14 | **Product Section 4** | 1070–1200px | 130px | Products with red discount badges |
| 15 | **Product Grid 2** | 1200–1400px | 200px | 2-column product grid (lower section) |
| 16 | **Bottom Promo/CTA** | 1400–1550px | 150px | Purple gradient floating action button + nav icons |
| 17 | **Bottom Navigation Bar** | 1550–1600px | 50px | Centered home/active indicator |

---

## 2. COLOR SCHEME & THEME

### Primary Colors
| Role | Color | Hex Code |
|------|-------|----------|
| **Background** | Near-black | `#000105` / `#010206` |
| **Card Background** | Ultra-dark (slightly tinted) | `#050507` to `#0b0221` |
| **Purple Accent (Primary)** | Vibrant purple | `#6525F8` / `#7025FC` |
| **Purple Accent (Light)** | Lavender/soft purple | `#BE92FF` / `#A87FFF` |
| **Purple Accent (Gradient Mid)** | Medium purple | `#5719E0` / `#5E1FEE` |
| **Purple (Dark)** | Deep purple | `#21064D` / `#0F0031` |
| **Purple (Pale/Pink)** | Light lavender | `#FCE3FF` / `#FBE6FF` |
| **Red (Badge/Discount)** | Bright red | `#CC1821` / `#D01725` / `#CE1922` |
| **White Text** | Near-white | `#F5F5F7` / `#F4F6F5` / `#FDFDFD` |
| **Light Gray Text** | `#C1C1C3` / `#BCBCBE` | Secondary text |
| **Mid Gray Text** | `#878789` / `#8E8F94` | Tertiary/muted text |
| **Dark Gray** | `#5C5C5C` / `#454545` / `#383838` | Subtle text, borders |
| **Darker Gray** | `#292929` / `#2B2B2B` | Card surface dark areas |
| **Card Border** | Warm dark gray | `#221A17` / `#322924` / `#2A1814` |
| **Star Rating** | Yellow-orange | Detected at y=595, y=700, y=840 |

### Theme: **Pure Dark Mode**
- Background is effectively `#000000` with a very subtle blue/purple tint (`#000105`)
- Cards have extremely subtle elevation — barely distinguishable from background
- No white cards — all elements are dark with light text
- Purple gradient is the dominant accent color throughout
- Red used sparingly for badges, discount tags, and notification indicators

---

## 3. ALL SECTIONS VISIBLE & THEIR ORDER

### Section 1: Status Bar (y=0–50)
- System-level status bar (iOS-style)
- Contains: time (left), signal/wifi/battery (right)
- Text color: white `#F5F5F7`
- Background: transparent/same as app background
- Brightness: only 1.9% bright pixels (minimal — just text)

### Section 2: Header + Search (y=50–130)
- **Left side (x=30–230):** App logo/brand name text
  - Bright text spanning x=30–228 at y=73–89 (peak brightness ~250)
  - Appears to be a brand logo rendered as text
- **Center (x=230–500):** Search bar area
  - Very dark background (~`#000105`)
  - May be a search input field with placeholder text
- **Right side (x=580–710):** Action icons
  - Purple-tinted icons at y=65–85
  - Colors: `#6525F8`, `#7025FC`, `#BE92FF` (purple gradient icons)
  - Likely: notification bell, shopping cart, user profile
  - White icon at y=80, x=590: `#F3F4F6`

### Section 3: Promotional Banner (y=130–250)
- **Purple gradient background** spanning most of the width
  - Left edge (x=50–100): `#5C1CE2` → `#4712CC` (purple)
  - Center: dark background with text overlay
  - Right edge (x=600–700): `#5814DC` → `#4A11B6` (purple)
  - At y=210 (prominent area): `#21064D` (deep purple gradient edge)
  - At y=230: `#764AC3` (medium purple)
- **Text content:**
  - Large heading text at y=150–170 (bright, ~275px wide: x=86–360)
  - Subtext/CTA at y=80–100 (x=50–170, ~120px wide)
- **Bottom-right element:** Persistent bright area at x=653–708 spanning y=100–180 (likely a promotional image/illustration)

### Section 4: Category Icons Row (y=200–250)
- Overlaps bottom of promo banner
- Horizontal scrollable row of circular category icons
- **Detected icon positions** (based on bright segments at y=220–240):
  - x=83 (center): Large element ~113px wide — likely active/featured category
  - x=186–267: Category icon cluster (3–4 items)
  - x=327–374: Category icon cluster (3 items)
  - x=486–527: Category icons
  - x=614–666: Category icons (right side)
- Each category likely has: circular icon image + text label below

### Section 5: Product Section 1 (y=250–380)
- Horizontal scrollable product cards
- **Card features detected:**
  - White/light text elements at various positions
  - Red accent at y=225, x=408–415 (small badge, 8px wide)
  - Red accent at y=380, x=438–441 (badge indicator)
  - Purple product accents: `#5C1CB4`, `#3C0096`
- **Red discount badge** at y=435, x=578–602 (25px wide) — likely "-XX%" tag

### Section 6: Dark Separator (y=380–420)
- Pure black gap (`#000A0B`)
- ~40px tall
- Provides visual separation between sections

### Section 7: Product Section 2 (y=420–540)
- Product listing with detailed info
- **Text elements detected:**
  - Title text at y=70, x=110 (~107px wide) and x=466 (~13px)
  - Subtitle at y=80, x=109 (~114px)
  - Price/info at y=90, x=109 (~114px) and x=227 (~49px)
  - Additional info at y=100, x=110 (~111px)
- **Action buttons** at x=580–660 (right side):
  - y=20: "See All" type link at x=589–643 (~55px total)
  - y=60: Icon at x=466 (16px), x=590 (5px), x=636 (38px)
- **Purple accent** elements for ratings/highlights
- **Gray text elements** at x=586–620 (section links)

### Section 8: Dark Separator (y=540–580)
- 100% dark pixels — pure black divider
- 40px tall

### Section 9: Section Header (y=580–650)
- **Section title** at y=590–610:
  - Left side text: x=62–184 (multiple segments = word-wrapped title)
  - x=267–351: Secondary text/label (86px wide)
  - x=432–516: Description/subtitle (85px total)
  - x=592–684: Right-side text = "See All" link (93px wide)
- **Star ratings** detected at y=595–600:
  - x=234–247 (star rating row, ~14px wide)
  - x=393–411 (star rating, ~19px)
  - x=555–571 (star rating, ~17px)
- Yellow/orange star pixels at y=595–600 spanning x=239–568
- Multiple rows of text suggesting category labels and star ratings

### Section 10: Banner Scroll Row (y=650–720)
- **Horizontal scrollable promotional cards**
- **Left card (x=40–190):** Large card ~150px wide
  - Average brightness: 89–138 (prominent)
  - Color: warm tones `#221A17` to `#321A1A` (dark reddish-brown = product image)
- **Right cards (x=570–620):**
  - Smaller cards/badges
  - Average brightness: 64–67 (darker than left card)
  - Color: neutral gray `#404448`
- **Red tag elements** at y=695–705:
  - x=39–108 (69px wide) — red banner/tag
  - x=567–635 (69px wide) — red banner/tag
  - Color: `#C91C2F` to `#D01725` (bright red)
- **Card edge detection:**
  - Left edge: warm dark colors `#221A17` to `#2E201F`
  - Right edge: red tones `#CC1821` to `#000A0B` (rounded card corners)

### Section 11: Product Cards Grid (y=720–880)
- **2-column grid layout**
- Card content (from mid-tone brightness ~40–150):
  - Product images at ~`#5A5A5A` to `#787878` (grayscale product images)
  - Card background: `#3C3C3C` to `#606060`
  - Text elements in white/light gray
- **Yellow star ratings** detected at y=700 and y=840
  - y=840: x=43–402 (star rating spanning card width)

### Section 12: Section Transition (y=880–960)
- Mostly dark (95.7% dark pixels)
- **Section header** at y=915–940:
  - Left text at x=35–172 (section title)
  - Right text at x=635–678 ("See All" link)
- Text at y=940: x=28–296 (large text block = section heading + subtitle)

### Section 13: Flash Deals / Deals Section (y=960–1070)
- **Brightest section of the page** (27.8% bright pixels at y=1000)
- **Card layout — 4 product cards in horizontal scroll:**
  1. Card 1: x=36–188 (~150px wide), avg brightness=89–121
  2. Card 2: x=267–311 (~45px wide), avg brightness=104
  3. Card 3: x=316–362 (~47px wide), avg brightness=108
  4. Card 4: x=367–413 (~47px wide), avg brightness=92
  5. Card 5: x=494–539 (~46px wide), avg brightness=88
  6. Card 6: x=544–637 (~95px wide, 2 cards), avg brightness=103–111
- Card backgrounds: light gray `#BCBCBE` to `#CFCFCF`
- Card content: product images, prices, discount percentages
- **Red discount tag** at y=1040, x=345–350 (6px — small "-X%" badge)
- Section has white/light card backgrounds (unlike rest of page which is dark)

### Section 14: Product Section 4 (y=1070–1200)
- **4 product cards with red badges** detected at y=1140–1155:
  - Card 1: x=37–75 (red badge, ~35px)
  - Card 2: x=218–255 (red badge, ~35px)
  - Card 3: x=394–433 (red badge, ~35px)
  - Card 4: x=567–605 (red badge, ~35px)
- Spacing: ~140px between card centers → 4 equal-width cards
- Card width: ~90px with ~50px gaps
- Red badges are positioned at top-left of each card
- Badge color: `#CC1821` to `#D01725`
- **Title text** at y=1100, x=28–194 (large heading, 167px wide)

### Section 15: Product Grid 2 (y=1200–1400)
- 2-column product card grid
- Cards are very dark with minimal bright content
- Most content is text only (white/light gray on dark)
- Card spacing consistent with Section 11

### Section 16: Bottom Promo / CTA Area (y=1400–1550)
- **Centered purple gradient button/element:**
  - Center: x=369 (screen center)
  - Width: ~70px at widest (x=332–407)
  - Gradient: `#572C97` → `#641FF4` → `#A87FFF` → `#FCE3FF` → `#FDFDFD`
  - Appears to be a floating action button (FAB) with purple gradient
  - Top is purple, fades to white/light lavender
- **Navigation icons** at y=1479–1533:
  - Left group (x=80–113): Purple icons ~`#C694F5`, `#B78CDF`
  - Center-left (x=227–248): Gray icons `#8E8F94`, `#A8A9AE`
  - Center (x=331–407): Main FAB button (purple gradient)
  - Center-right (x=485–533): Gray icons `#8F9094`, `#A3A4A9`
  - Right (x=627–664): Gray icons `#86878C`, `#898A8F`
- This suggests **5 navigation items** in the bottom tab bar

### Section 17: Bottom Navigation Bar (y=1550–1600)
- **Active indicator** at y=1577–1583:
  - Centered at x=369 (screen center)
  - Width: ~12px at top, ~244px at y=1579–1583
  - Color: white `#F9FBFA` / `#F4F6F5`
  - This is a **home indicator bar** (iOS-style bottom safe area indicator)
- Background: dark `#17171E`
- 94.7% dark pixels — minimal UI elements

---

## 4. BUTTON STYLES, LABELS & ACTIONS

### Floating Action Button (FAB)
- **Location:** Centered at x=369, y≈1440–1530
- **Shape:** Circular/pill (gradient from deep purple to light lavender)
- **Gradient:** `#572C97` (top) → `#641FF4` → `#FCE3FF` → `#FDFDFD` (center) → `#460EB1` (bottom)
- **Size:** ~70px diameter
- **Purpose:** Likely "Add to Cart" or primary CTA

### "See All" Links
- **Location:** Right side of section headers
- **Color:** Light gray `#BCBCBE`
- **Size:** ~45–93px wide text
- Found at y=580–650 (x=592–684) and y=880–960 (x=635–678)

### Action Icons (Header)
- **Location:** Top-right, y=50–100, x=580–710
- **Colors:** Purple gradient (`#6525F8` → `#BE92FF`) + white (`#F3F4F6`)
- **Count:** 2–3 icons (notification, cart, profile)

### Red Discount Badges
- **Color:** `#CC1821` / `#D01725`
- **Locations:** Top-left of product cards
- **Sizes:** Small (6px) to medium (35px)
- Likely show percentage discount: "-XX%"

---

## 5. PRODUCT CARD DESIGN

### Standard Product Card (Dark Theme)
- **Background:** `#050507` to `#0B0221` (nearly black, very subtle purple tint)
- **Border:** None visible (or extremely subtle `#221A17`)
- **Border Radius:** ~8–12px (inferred from rounded card edge colors)
- **Structure:**
  1. **Product Image** (top ~60%): Grayscale/dark-toned, `#5A5A5A`–`#787878`
  2. **Product Title** (below image): White `#F5F5F7`, single line, truncated
  3. **Price:** White/bright, likely bold
  4. **Rating:** Yellow star icons (detected at y=700, y=840), ~15–20px wide
  5. **Optional Red Badge:** Top-left corner, `#CC1821`, percentage discount

### Flash Deal Card (Light Theme)
- **Background:** Light gray `#BCBCBE` to `#F0F0F0`
- **Width:** ~45–150px depending on card type
- **Height:** ~70px (section height)
- **Border:** Subtle, rounded corners
- **Content:** Product image, price, discount percentage

### Banner Card (Promotional)
- **Background:** Warm tones `#221A17` (dark reddish-brown = product image)
- **Width:** ~150px (left large card), ~50px (right smaller cards)
- **Height:** ~70px
- **Features:** Red tag overlay `#C91C2F`

---

## 6. NAVIGATION ELEMENTS

### Bottom Tab Bar (5 tabs)
Based on icon detection at y=1479–1533:

| Position | X-Center | Color | State | Likely Label |
|----------|----------|-------|-------|--------------|
| Tab 1 (Far Left) | ~85 | Purple `#C694F5` | **Active** | Home |
| Tab 2 | ~230 | Gray `#8E8F94` | Inactive | Categories/Browse |
| Tab 3 (Center FAB) | ~369 | Purple gradient | Active | Cart/Add |
| Tab 4 | ~510 | Gray `#A3A4A9` | Inactive | Wishlist/Favorites |
| Tab 5 (Far Right) | ~645 | Gray `#86878C` | Inactive | Profile/Account |

**Tab Spacing:** ~148px between tabs (evenly distributed across 740px width)

### Home Indicator Bar
- **Location:** y=1577–1583, centered at x=369
- **Width:** ~244px (wide bar)
- **Color:** White `#F9FBFA`
- **Type:** iOS-style home indicator

### Category Icons Row
- **Location:** y=200–250
- **Layout:** Horizontal scroll
- **Style:** Circular icons with text labels below
- **Active state:** Purple tint (`#C694F5`)
- **Icon count:** 5+ visible (scrollable)

---

## 7. TYPOGRAPHY & FONT STYLES

### Font Colors by Hierarchy
| Level | Color | Hex | Usage |
|-------|-------|-----|-------|
| H1 (Primary) | Near-white | `#F5F5F7` | Section titles, promo headings |
| H2 (Secondary) | Light gray | `#C1C1C3` | Card titles, product names |
| Body | Mid gray | `#878789` | Subtitles, descriptions |
| Caption | Dark gray | `#5C5C5C` / `#454545` | Prices, meta info |
| Muted | Very dark gray | `#292929` | Placeholder text |
| Accent | Purple | `#6525F8` | Active tabs, highlights |
| Sale | Red | `#CC1821` | Discount percentages |

### Text Sizes (estimated from pixel width)
- **Promo heading:** ~120–170px wide at y=150 → ~24–32sp
- **Section titles:** ~130–170px wide → ~20–24sp
- **Product titles:** ~110–115px wide → ~14–16sp
- **"See All" links:** ~45–65px wide → ~12–14sp
- **Price text:** ~40–50px wide → ~14–16sp (bold)
- **Tab labels:** ~30–35px wide → ~10–12sp

### Font Weight
- **Bold:** Used for prices and section headings (brighter, wider pixel distribution)
- **Regular:** Used for product titles and descriptions
- **Light:** Used for "See All" links and meta text (dimmer gray)

---

## 8. ICONS USED

| Icon Type | Location | Color | Size |
|-----------|----------|-------|------|
| **Search** | Header center | Dark/gray | ~20px |
| **Notification Bell** | Header right (x=590) | White `#F3F4F6` | ~20px |
| **Shopping Bag/Cart** | Header right (x=610–620) | Purple gradient | ~25px |
| **User Profile** | Header right (x=650) | Purple `#8E57CF` | ~20px |
| **Category Icons** | y=200–250 | Various/mixed | ~40px circles |
| **Star Ratings** | Product cards | Yellow-orange | ~15px each |
| **Home Tab** | Bottom left (x=85) | Purple `#C694F5` | ~24px |
| **Categories Tab** | Bottom (x=230) | Gray `#8E8F94` | ~24px |
| **Cart FAB** | Bottom center (x=369) | Purple gradient | ~70px circle |
| **Wishlist Tab** | Bottom (x=510) | Gray `#A3A4A9` | ~24px |
| **Profile Tab** | Bottom (x=645) | Gray `#86878C` | ~24px |

---

## 9. SPACING & PATTERNS

### Vertical Spacing
| Element | Gap Size |
|---------|----------|
| Status bar height | 50px |
| Header height | 80px |
| Section gap | 40px |
| Section header height | 70px |
| Banner/card row height | 70px |
| Product grid row height | 160px |

### Horizontal Spacing (740px total width)
| Element | Left Margin | Element Width | Right Margin | Notes |
|---------|------------|---------------|-------------|-------|
| Header content | ~20px | ~700px | ~20px | Full-width with padding |
| Section headers | ~30px | Text varies | ~30px | "See All" aligned right |
| 2-col grid cards | ~20px | ~340px each | ~20px | 20px gap between cards |
| 4-col deal cards | ~20px | ~155px each | ~20px | ~10px gaps |
| Bottom tab bar | - | 5 tabs evenly | - | ~148px tab spacing |
| Flash deal cards | ~20px | 45–150px each | ~10px | Variable width |

### Padding Pattern
- **Page horizontal padding:** ~20px on each side
- **Card internal padding:** ~8–12px
- **Section vertical padding:** ~15–20px above/below section headers
- **Card border radius:** ~8–12px (estimated)

---

## 10. SPECIAL UI ELEMENTS

### Red Discount Tags
- **Color:** `#CC1821` / `#D01725` (bright red)
- **Shape:** Small rounded rectangle or pill
- **Position:** Top-left corner of product cards
- **Content:** Likely "-XX%" text
- **Found at:** y=225, y=380, y=435, y=695, y=700, y=705, y=1040, y=1140–1155

### Purple Gradient Accents
- **Usage:** Active states, highlights, decorative elements
- **Gradient:** Deep purple → Bright purple → Lavender → Near-white
- **Applied to:** Header icons, active tab, FAB button, promo banner edges

### Star Ratings
- **Color:** Yellow-orange (low pixel count suggests small stars)
- **Position:** Below product title/description
- **Pattern:** 1–5 filled stars in a row
- **Detected at:** y=595, y=600, y=700, y=840

### Banner Cards with Red Tags
- **Position:** y=650–720 (scrollable row)
- **Feature:** Product cards with overlaid red promotional tags
- **Left card:** Larger (~150px), warm-toned product image
- **Right cards:** Smaller, neutral gray

### Promotional Banner
- **Full-width** hero banner at y=130–250
- **Purple gradient** background with white text
- **Promotional image** on the right side (persistent bright area at x=653–708)

### Flash Deals Section (Bright)
- **Unique:** Uses LIGHT card backgrounds on an otherwise DARK page
- **Card color:** `#BCBCBE` to `#F0F0F0`
- **Purpose:** Draws attention with contrast inversion

---

## SUMMARY — KEY DESIGN TOKENS

```css
/* Colors */
--bg-primary: #000105;
--bg-card: #050507;
--bg-card-tinted: #0B0221;
--purple-primary: #6525F8;
--purple-light: #BE92FF;
--purple-dark: #21064D;
--purple-pale: #FCE3FF;
--red-accent: #CC1821;
--red-bright: #D01725;
--text-primary: #F5F5F7;
--text-secondary: #C1C1C3;
--text-tertiary: #878789;
--text-muted: #5C5C5C;
--text-placeholder: #292929;
--border-subtle: #221A17;
--gray-mid: #454545;

/* Spacing */
--page-padding: 20px;
--card-padding: 10px;
--card-radius: 10px;
--section-gap: 40px;
--tab-spacing: 148px;

/* Typography */
--font-heading: 24-32sp bold #F5F5F7;
--font-body: 14-16sp regular #C1C1C3;
--font-caption: 12sp regular #878789;
--font-price: 16sp bold #F5F5F7;
--font-badge: 10sp bold #CC1821 on transparent;
```
