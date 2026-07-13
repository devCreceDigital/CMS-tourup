---
name: Ethos & Earth
colors:
  surface: '#fcf9f5'
  surface-dim: '#dcdad6'
  surface-bright: '#fcf9f5'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f6f3ef'
  surface-container: '#f0ede9'
  surface-container-high: '#eae8e4'
  surface-container-highest: '#e5e2de'
  on-surface: '#1c1c1a'
  on-surface-variant: '#434843'
  inverse-surface: '#31302e'
  inverse-on-surface: '#f3f0ec'
  outline: '#737973'
  outline-variant: '#c3c8c1'
  surface-tint: '#4d6453'
  primary: '#061b0e'
  on-primary: '#ffffff'
  primary-container: '#1b3022'
  on-primary-container: '#819986'
  inverse-primary: '#b4cdb8'
  secondary: '#80552c'
  on-secondary: '#ffffff'
  secondary-container: '#fec391'
  on-secondary-container: '#794e26'
  tertiary: '#1d160a'
  on-tertiary: '#ffffff'
  tertiary-container: '#322a1d'
  on-tertiary-container: '#9d917f'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#d0e9d4'
  primary-fixed-dim: '#b4cdb8'
  on-primary-fixed: '#0b2013'
  on-primary-fixed-variant: '#364c3c'
  secondary-fixed: '#ffdcc1'
  secondary-fixed-dim: '#f5bb89'
  on-secondary-fixed: '#2e1500'
  on-secondary-fixed-variant: '#653e17'
  tertiary-fixed: '#f0e0cc'
  tertiary-fixed-dim: '#d3c4b1'
  on-tertiary-fixed: '#221a0e'
  on-tertiary-fixed-variant: '#4f4537'
  background: '#fcf9f5'
  on-background: '#1c1c1a'
  surface-variant: '#e5e2de'
  forest-deep: '#1B3022'
  copper-earth: '#C28E60'
  sand-warm: '#E8D9C5'
  off-white: '#FCF9F5'
  slate-text: '#2D3436'
typography:
  headline-xl:
    fontFamily: Playfair Display
    fontSize: 48px
    fontWeight: '700'
    lineHeight: 56px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Playfair Display
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
  headline-md:
    fontFamily: Playfair Display
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  headline-lg-mobile:
    fontFamily: Playfair Display
    fontSize: 28px
    fontWeight: '700'
    lineHeight: 36px
  body-lg:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  label-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '600'
    lineHeight: 20px
    letterSpacing: 0.05em
  caption:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '400'
    lineHeight: 16px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  container-max: 1280px
  gutter: 2rem
  margin-mobile: 1rem
  margin-desktop: 4rem
  section-gap: 8rem
  stack-sm: 0.5rem
  stack-md: 1.5rem
  stack-lg: 3rem
---

## Brand & Style

The brand identity is rooted in the concept of "Responsible Premium Tourism." It balances high-end sophistication with a deep, ethical commitment to the environment and local cultures. The visual language moves away from cold, high-tech aesthetics in favor of a warm, editorial, and organic feel that evokes the serenity of a luxury eco-lodge.

The design style is **Minimalist-Organic**. It prioritizes generous "visual air" (whitespace) to allow content to breathe, conveying a sense of exclusivity and calm. The use of natural textures (implied through color and photography), soft shadows, and refined typography creates a tactile experience that feels both professional and inviting.

**Key Brand Pillars:**
- **Heritage & Earth:** Respect for the land through deep forest greens and earthen tones.
- **Sophisticated Clarity:** Editorial-grade typography that suggests authority and storytelling.
- **Trustworthy Transparency:** Clean, verified data blocks and minimalist iconography.

## Colors

The palette is inspired by the natural landscapes of premium travel destinations. 

- **Primary (Forest Deep):** A rich, dark green used for primary navigation, headings, and high-impact structural elements. It conveys stability and a connection to nature.
- **Secondary (Copper Earth):** An accent color used for calls to action, highlights, and "verified" status markers. It provides a warm, metallic contrast to the cool greens.
- **Tertiary (Sand Warm):** Used for subtle backgrounds, dividers, and card fills to soften the interface.
- **Neutral (Off-White):** The foundation of the design system. This warm background prevents the "sterile" feel of pure white and reduces eye strain.
- **Text:** Primary body text uses a softened slate to maintain readability without the harshness of pure black.

## Typography

This design system uses a classic pairing of a high-personality Serif for storytelling and a highly functional Sans-Serif for utility.

- **Headlines:** `Playfair Display` provides an editorial, premium feel. It should be used for section titles and hero statements. The slightly tight letter spacing in larger sizes adds a modern touch to the classic serif.
- **Body & UI:** `Inter` is chosen for its exceptional legibility at all sizes. It handles complex information and "verified data" blocks with neutrality and precision.
- **Labels:** Use uppercase `Inter` with increased letter spacing for small UI labels, badges, and category tags to differentiate them from body prose.

## Layout & Spacing

The layout follows a **Fixed Grid** philosophy on desktop to ensure a curated, "magazine-style" reading experience, while transitioning to a fluid model on mobile devices.

- **Whitespace:** Emphasize vertical rhythm. Section gaps are intentionally large (`8rem`) to prevent information density from overwhelming the user.
- **Grid:** A 12-column grid is used for desktop. Cards and content blocks should typically span 3, 4, or 6 columns to maintain balanced proportions.
- **Safe Margins:** Generous horizontal margins on desktop (`4rem`) ensure content feels centered and premium.

## Elevation & Depth

To maintain a "tactile yet clean" feel, this design system avoids heavy drop shadows in favor of subtle layering.

- **Surface Tiers:** Use the `neutral_color` (Off-White) for the base page, and `white` (#FFFFFF) for elevated cards or containers. This creates a soft, natural depth without needing dark shadows.
- **Shadow Character:** When shadows are necessary for interactivity (e.g., hovering over a card), use "Ambient Shadows": extremely diffused (20px-40px blur), very low opacity (5-8%), and tinted with the `primary_color` (Forest Green) to ensure they feel like part of the environment rather than a digital artifact.
- **Outlines:** Use low-contrast 1px borders in `sand-warm` for secondary elements like input fields or accordion borders to maintain structure without adding visual noise.

## Shapes

The shape language is organic and approachable. 

- **Corners:** A "Rounded" setting (0.5rem base) is applied to all primary UI elements. This softens the interface, moving away from the "corporate" feel of sharp corners.
- **Large Components:** For featured sections or high-level cards, use `rounded-xl` (1.5rem) to emphasize a "soft-luxury" aesthetic.
- **Interactive Elements:** Buttons and tags may use the pill-shape style where appropriate to denote high interactivity.

## Components

- **Cards:** Use white backgrounds over the off-white page. Hierarchy is established by `headline-md` titles, `body-md` descriptions, and a clear "Copper Earth" accent for the primary action or category tag.
- **Buttons:** 
  - *Primary:* Forest Deep background with white text. High contrast, authoritative.
  - *Secondary:* Copper Earth outline or subtle Sand Warm fill.
  - *States:* On hover, buttons should shift slightly in tonal depth (e.g., Forest Deep becomes 10% lighter) rather than changing color entirely.
- **Verified Data Blocks:** Use a "Metric Style" with `label-md` for the description and `headline-lg` for the value. Include a small, minimalist icon in Copper Earth to signify "verified" or "certified" status.
- **Accordions:** Clean, border-only transitions using `sand-warm`. Use a simple plus/minus or chevron-down icon. The header should use `body-lg` in SemiBold Inter.
- **Iconography:** Use 1.5pt weight linear icons. Avoid filled icons unless used as active status markers. Icons should always be monochromatic, using the Forest Deep or Copper Earth colors.