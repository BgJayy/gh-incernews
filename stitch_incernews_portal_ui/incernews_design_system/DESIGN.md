---
name: IncerNews Design System
colors:
  surface: '#faf8ff'
  surface-dim: '#d2d9f4'
  surface-bright: '#faf8ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f2f3ff'
  surface-container: '#eaedff'
  surface-container-high: '#e2e7ff'
  surface-container-highest: '#dae2fd'
  on-surface: '#131b2e'
  on-surface-variant: '#3d4943'
  inverse-surface: '#283044'
  inverse-on-surface: '#eef0ff'
  outline: '#6d7a73'
  outline-variant: '#bccac2'
  surface-tint: '#006c50'
  primary: '#00694e'
  on-primary: '#ffffff'
  primary-container: '#008563'
  on-primary-container: '#f5fff8'
  inverse-primary: '#6adbb1'
  secondary: '#006590'
  on-secondary: '#ffffff'
  secondary-container: '#88ceff'
  on-secondary-container: '#00587e'
  tertiary: '#b41b24'
  on-tertiary: '#ffffff'
  tertiary-container: '#d83739'
  on-tertiary-container: '#fffbff'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#87f7cd'
  primary-fixed-dim: '#6adbb1'
  on-primary-fixed: '#002116'
  on-primary-fixed-variant: '#00513c'
  secondary-fixed: '#c8e6ff'
  secondary-fixed-dim: '#88ceff'
  on-secondary-fixed: '#001e2e'
  on-secondary-fixed-variant: '#004c6d'
  tertiary-fixed: '#ffdad7'
  tertiary-fixed-dim: '#ffb3ae'
  on-tertiary-fixed: '#410004'
  on-tertiary-fixed-variant: '#930014'
  background: '#faf8ff'
  on-background: '#131b2e'
  surface-variant: '#dae2fd'
typography:
  display-hero:
    fontFamily: Montserrat
    fontSize: 56px
    fontWeight: '800'
    lineHeight: 64px
    letterSpacing: -0.03em
  display-hero-mobile:
    fontFamily: Montserrat
    fontSize: 34px
    fontWeight: '800'
    lineHeight: 40px
    letterSpacing: -0.02em
  headline-xl:
    fontFamily: Montserrat
    fontSize: 40px
    fontWeight: '800'
    lineHeight: 48px
    letterSpacing: -0.025em
  headline-xl-mobile:
    fontFamily: Montserrat
    fontSize: 28px
    fontWeight: '800'
    lineHeight: 34px
    letterSpacing: -0.015em
  headline-lg:
    fontFamily: Montserrat
    fontSize: 30px
    fontWeight: '700'
    lineHeight: 38px
    letterSpacing: -0.02em
  headline-md:
    fontFamily: Montserrat
    fontSize: 22px
    fontWeight: '700'
    lineHeight: 30px
    letterSpacing: -0.015em
  headline-sm:
    fontFamily: Montserrat
    fontSize: 18px
    fontWeight: '700'
    lineHeight: 24px
    letterSpacing: -0.01em
  lead-paragraph:
    fontFamily: Montserrat
    fontSize: 20px
    fontWeight: '500'
    lineHeight: 32px
    letterSpacing: -0.01em
  body-lg:
    fontFamily: Montserrat
    fontSize: 17px
    fontWeight: '400'
    lineHeight: 28px
    letterSpacing: -0.005em
  body-md:
    fontFamily: Montserrat
    fontSize: 15px
    fontWeight: '400'
    lineHeight: 24px
    letterSpacing: 0em
  body-sm:
    fontFamily: Montserrat
    fontSize: 13px
    fontWeight: '400'
    lineHeight: 20px
    letterSpacing: 0em
  label-caps:
    fontFamily: Montserrat
    fontSize: 11px
    fontWeight: '700'
    lineHeight: 14px
    letterSpacing: 0.08em
  label-md:
    fontFamily: Montserrat
    fontSize: 14px
    fontWeight: '600'
    lineHeight: 18px
    letterSpacing: 0em
  caption:
    fontFamily: Montserrat
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
    letterSpacing: 0.01em
rounded:
  sm: 0.125rem
  DEFAULT: 0.25rem
  md: 0.375rem
  lg: 0.5rem
  xl: 0.75rem
  full: 9999px
spacing:
  gutter: 1.5rem
  gutter-mobile: 1rem
  gutter-tablet: 1.25rem
  margin: 2rem
  margin-mobile: 1rem
  margin-tablet: 1.5rem
  space-xs: 0.25rem
  space-sm: 0.5rem
  space-md: 1rem
  space-lg: 1.5rem
  space-xl: 2.5rem
---

## Brand & Style

The design system establishes a high-velocity, authoritative digital journalism presence. It bridges traditional editorial credibility with modern digital responsiveness, prioritizing reading endurance, scannability, and immediate hierarchy. 

### Visual Archetype & Movement
The design movement leans into **Editorial Modernism with Structural Discipline**:
- **Information Architecture:** Asymmetric grid balances and high-contrast typographic pairings evoke modern broadsheets while remaining responsive across modern displays.
- **Rhythm:** Rapid scannability for real-time news delivery through distinct category anchoring, tight metadata grouping, and generous reading measure.
- **Atmosphere:** Clean, daylight-driven editorial surfaces (`#ffffff` and `#f8fafc`) punctuated by deep structural tones and vibrant, focused teal accents.

### Emotional Target
Readers and contributors must experience:
- **Authority & Trust:** Grounded structural frames that reject tabloid clutter.
- **Speed & Clarity:** Zero cognitive drag when parsing breaking developments, long-form investigations, or live-updating trackers.
- **Visual Precision:** Sharply framed imagery and disciplined line geometry that respect journalistic content.

## Colors

The color system enforces editorial hierarchy through strict functional roles, utilizing high-contrast accents against clean neutral surfaces.

### Palette Architecture
- **Primary (`#1a9b76` - Forest Teal / Emerald):** Interactive highlights, category indicators, text link underlines, progress indicators, and primary call-to-action buttons. Represents vitality, precision, and modern editorial energy.
- **Secondary (`#006590` - Deep Ocean Blue):** Structural chrome, persistent navigation backdrops, mastheads, section titles, authority banners, and metadata accents. Conveys journalistic integrity and institutional permanence.
- **Tertiary (`#d9383a` - Crimson Signal):** Dedicated to breaking news tickers, live reporting indicators, urgency badges, and critical validation alerts.
- **Neutral Core (`#0f172a` - Slate 900):** Primary reading ink for headlines and display text, yielding WCAG AAA compliance on all primary ground planes.
- **Muted Ink (`#475569` - Slate 600):** Secondary reading text, article excerpts, bylines, and datestamps.
- **Subtle Ink (`#64748b` - Slate 500):** Captions, image credits, and tertiary table metadata.

### Surfaces & Dividers
- **Canvas Base (`#ffffff`):** Primary document surface for article bodies and default editorial card backgrounds.
- **Subtle Surface (`#f8fafc`):** Structural alternates, reading wells, sidebars, dashboard backdrops, and secondary module backgrounds.
- **Structural Dividing Line (`#e2e8f0`):** Crisp 1px rules across article dividers, editorial column borders, and grid separators.
- **Surface Hover (`#f1f5f9`):** Interactive background states for list items, clean rows, and ghost controls.

## Typography

The typographical system relies entirely on the geometric versatility of **Montserrat**, utilizing distinct weights and proportions to separate commanding editorial voice from sustained narrative reading.

### Typographic Hierarchy & Scale
- **Display & Headline Levels (Weight 700 & 800):** Applied to breaking alerts, section covers, and article titles. Tight negative tracking (`-0.015em` to `-0.03em`) creates a dense, newsprint-like punch without sacrificing optical clarity.
- **Narrative Reading (Body-LG / Lead):** Editorial body text is tuned specifically for sustained screen reading with an expanded line-height-to-size ratio (`17px/28px` and `20px/32px`) and a neutral tracking posture.
- **Metadata, Overlines & Badges (Label-Caps):** Category badges, tickers, and byline anchors utilize uppercase styling with explicit positive letter spacing (`0.08em`) to guarantee crisp rendering even at 11px.

## Layout & Spacing

The layout is grounded in a 12-column adaptive fluid grid designed for complex editorial content, multi-card stories, and high-density portal dashboards.

### Grid Anatomy & Breakpoints
- **Mobile (< 768px):** 4-column layout, `1rem` margins, `1rem` gutter. Complex multi-story units collapse into linear vertical streams; headlines automatically reflow to mobile token tiers.
- **Tablet (768px – 1024px):** 8-column layout, `1.5rem` margins, `1.25rem` gutter. Facilitates 2-column story splits and stacked lead features.
- **Desktop (1024px – 1440px):** 12-column layout, `2rem` margins, `1.5rem` gutter. Enables standard portal arrangements: 8-column feature/story body paired with a 4-column dynamic sidebar.
- **Wide Editorial Canvas (> 1440px):** Max container width capped at `1400px` centered with auto-margins to preserve optimal reading line-length (`65–75` characters per line).

### Asymmetric Hero Composition
Lead portals utilize an 8/4 or 6/3/3 column distribution:
- Primary lead story takes an 8-column block with a large image and excerpt.
- Secondary breaking briefs take the remaining 4 columns stacked as dense, text-driven cards with clean dividing lines.

## Elevation & Depth

This design system eschews heavy drop shadows in favor of **Crisp Architectural Outlines** and **Tonal Stratification**. Clean 1px structural rules provide organization while minimizing visual noise.

### Layering Philosophy
- **Flat Ground Plane:** Base page runs on `#ffffff`. Alternating feed zones and analytical dashboard panels rely on the `#f8fafc` tone without artificial elevation.
- **Subtle Surface Definition:** Cards and content wells sit on crisp `1px solid #e2e8f0` borders rather than ambient blur spreads.
- **Hover Transitions:** Interactive cards and story units elevate purely via a 1px border shift to `#1a9b76` (Forest Teal) paired with a subtle, tight micro-shadow: `0 4px 12px rgba(15, 23, 42, 0.06)`.
- **Floating Modals & Overlays:** Pinned breaking news ribbons, mobile navigation drawers, and floating share docks utilize an authoritative solid structure bounded by `0 8px 24px rgba(0, 101, 144, 0.12)` to establish clear elevation over dense text layouts.

## Shapes

The interface adopts a disciplined **Soft Geometry (`level 1`)** to maintain serious journalistic precision. Roundedness is applied sparingly to retain sharp alignment lines along editorial grid columns.

### Geometry Application Rules
- **Editorial Cards & Image Frames:** `0.25rem` (4px). Keeps images sharp and content divisions clearly structured like news columns.
- **Badges & Category Pills:** Fully rounded (`9999px`) pill geometry to immediately distinguish metadata and categorical flags from square media assets.
- **Form Controls & Search Bars:** `0.25rem` (4px) corner rounding reinforces structural stability across admin data tables and reader newsletter modules.
- **Buttons:** `0.25rem` (4px) for standard actions; full-pill treatment reserved strictly for social share actions and high-visibility live tag overlays.

## Components

### 1. Breaking News Ticker Ribbon
- **Container:** Edge-to-edge full width or contained header banner. Background `#006590` (Deep Ocean Blue) with a contrasting `#d9383a` (Crimson) label block reading "BREAKING" or "LIVE" in bold `label-caps`.
- **Content:** Ticker text in `#ffffff` `body-sm` (font weight 600) scrolling or cycling with crossfade transitions. Active link hover highlights in `#1a9b76`.
- **Dividers:** Vertical `#ffffff20` rules separating sequential items.

### 2. Editorial News Cards
- **Lead Hero Card:** Full width of column allocation. Top-oriented media asset with a `16:9` ratio, overlaid with a Category Badge (top left). Followed by `headline-xl` headline, author/timestamp row, and `body-lg` excerpt.
- **Secondary Card (Horizontal Split):** Thumbnail with a `1:1` or `4:3` ratio on the left (80px–120px wide), headline (`headline-sm`) and metadata to the right. Separated by a `1px solid #e2e8f0` bottom rule.
- **Card States:** Hover initiates a border color change to `#1a9b76` and subtle title color transition from `#0f172a` to `#1a9b76`.

### 3. Category Pill Badges
- **Structure:** `4px 10px` internal padding, `9999px` border-radius, `label-caps` typography.
- **Thematic Colors:** 
  - *Standard:* `#1a9b76` background with `#ffffff` text.
  - *Authority/Politics:* `#006590` background with `#ffffff` text.
  - *Breaking/Alert:* `#d9383a` background with `#ffffff` text.
  - *Ghost Badges:* `#f8fafc` background, `1px solid #e2e8f0`, `#0f172a` text.

### 4. Interactive Buttons
- **Primary Button:** `#1a9b76` background, `#ffffff` text, 4px border radius, `label-md` typography. Hover shifts background to `#147a5d` (darkened teal).
- **Secondary Button:** Outlined `1.5px solid #006590`, text `#006590`, transparent background. Hover fills background with `#006590` and text shifts to `#ffffff`.
- **Ghost/Tertiary:** No border, text `#475569`, hover switches to `#1a9b76` with a soft `#f1f5f9` backdrop.

### 5. Form Fields & Search Inputs
- **Base Input:** Background `#ffffff`, border `1px solid #e2e8f0`, radius 4px, height 42px, padding `0 12px`, text `body-md` in `#0f172a`.
- **Focus State:** 2px ring in `#1a9b76` with border shifting to `#1a9b76`.
- **Portal Search Bar:** Expanded 48px height, embedded search icon in `#006590`, `#f8fafc` background changing to `#ffffff` on focus.

### 6. Breadcrumbs & Metadata Ribbons
- **Breadcrumbs:** `body-sm` scale, uppercase category roots, chevron divider `/` in `#64748b`. The active leaf is set in bold `#0f172a`.
- **Article Metadata:** Byline text in `#0f172a` (weight 600), publication timestamp in `#64748b`, estimated reading time marked with an emerald dot accent (`#1a9b76`).

### 7. Social Share Buttons
- **Shape & Dimension:** 36px circular or pill-shaped icon units. 
- **Palette:** `#f8fafc` background with `1px solid #e2e8f0`, icon fill in `#006590`. Hover transitions the surface to `#006590` with icon in `#ffffff`.

### 8. Admin Data Tables
- **Table Canvas:** Surface `#ffffff`, border `1px solid #e2e8f0`.
- **Header Row:** Background `#f8fafc`, text in `label-caps` `#64748b`, bottom border `2px solid #e2e8f0`.
- **Rows:** Alternating rows subtle `#ffffff` and `#fafafa`, row hover highlight `#f1f5f9`, border `1px solid #e2e8f0`.
- **Status Cells:** Pill badges indicating publish status ("Draft", "Live", "Scheduled", "Archived").