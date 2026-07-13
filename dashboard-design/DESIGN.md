---
name: Scholar Journey
colors:
  surface: '#f8f9ff'
  surface-dim: '#cbdbf5'
  surface-bright: '#f8f9ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#eff4ff'
  surface-container: '#e5eeff'
  surface-container-high: '#dce9ff'
  surface-container-highest: '#d3e4fe'
  on-surface: '#0b1c30'
  on-surface-variant: '#434655'
  inverse-surface: '#213145'
  inverse-on-surface: '#eaf1ff'
  outline: '#737686'
  outline-variant: '#c3c6d7'
  surface-tint: '#0053db'
  primary: '#004ac6'
  on-primary: '#ffffff'
  primary-container: '#2563eb'
  on-primary-container: '#eeefff'
  inverse-primary: '#b4c5ff'
  secondary: '#565e74'
  on-secondary: '#ffffff'
  secondary-container: '#dae2fd'
  on-secondary-container: '#5c647a'
  tertiary: '#525657'
  on-tertiary: '#ffffff'
  tertiary-container: '#6b6e70'
  on-tertiary-container: '#eff1f3'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dbe1ff'
  primary-fixed-dim: '#b4c5ff'
  on-primary-fixed: '#00174b'
  on-primary-fixed-variant: '#003ea8'
  secondary-fixed: '#dae2fd'
  secondary-fixed-dim: '#bec6e0'
  on-secondary-fixed: '#131b2e'
  on-secondary-fixed-variant: '#3f465c'
  tertiary-fixed: '#e0e3e5'
  tertiary-fixed-dim: '#c4c7c9'
  on-tertiary-fixed: '#191c1e'
  on-tertiary-fixed-variant: '#444749'
  background: '#f8f9ff'
  on-background: '#0b1c30'
  surface-variant: '#d3e4fe'
typography:
  headline-xl:
    fontFamily: Inter
    fontSize: 48px
    fontWeight: '800'
    lineHeight: '1.2'
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Inter
    fontSize: 32px
    fontWeight: '700'
    lineHeight: '1.3'
  headline-lg-mobile:
    fontFamily: Inter
    fontSize: 28px
    fontWeight: '700'
    lineHeight: '1.3'
  headline-md:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '600'
    lineHeight: '1.4'
  body-lg:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '400'
    lineHeight: '1.6'
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.6'
  label-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '600'
    lineHeight: '1'
    letterSpacing: 0.05em
  caption:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '400'
    lineHeight: '1.4'
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  container-max: 1280px
  gutter: 24px
  margin-mobile: 16px
  margin-desktop: 48px
  stack-sm: 8px
  stack-md: 16px
  stack-lg: 32px
---

## Brand & Style

The design system is engineered for an educational school trip platform, striking a balance between academic authority and the excitement of travel. The brand personality is dependable, organized, and inspiring. 

The visual direction follows a **Corporate / Modern** style with subtle **Minimalist** influences. It prioritizes clarity and information density, ensuring parents and educators feel a sense of security while students feel engaged. The interface utilizes generous whitespace, refined typography, and a structured layout to transform complex itineraries into legible, inviting narratives. 

Key emotional responses:
- **Trust:** Established through a robust navy and blue palette.
- **Clarity:** Driven by a systematic approach to schedules and pricing tables.
- **Anticipation:** Evoked through high-quality imagery and soft, expansive surfaces.

## Colors

The color palette is anchored in a professional "Academic Blue" and "Deep Navy" to instill confidence. 

- **Primary (#2563EB):** Used for primary actions, active states, and highlighting key itinerary milestones.
- **Secondary (#0F172A):** A dark navy used for primary headings and high-contrast surfaces (e.g., footer or sidebar callouts) to provide grounding.
- **Tertiary (#F8FAFC):** A very light cool-grey used for section backgrounds to distinguish content blocks without the harshness of pure white.
- **Neutral (#64748B):** Reserved for secondary text, borders, and icon outlines.

The default mode is **Light**, utilizing a "Paper & Ink" philosophy where content lives on clean, white elevated surfaces against a subtle tertiary background.

## Typography

The typography system relies on **Inter** for its exceptional legibility and neutral, functional character. 

- **Headlines:** Use tight letter-spacing and heavy weights (Bold to Extra-Bold) to create a clear hierarchy against long-form itinerary text. 
- **Body:** Set with generous line-height (1.6) to ensure readability for detailed trip descriptions and "Things to Note."
- **Labels:** Small caps or heavy weights at 14px are used for metadata like "DESTINOS" or "FECHA" to distinguish them from actionable content.
- **Scaling:** For mobile devices, headline sizes are reduced to prevent awkward line breaks while maintaining the same weight and vertical rhythm.

## Layout & Spacing

This design system utilizes a **12-column fluid grid** for desktop and a **4-column grid** for mobile. 

- **The Sidebar Model:** Elements like "Inscríbete ahora" and "Beneficios" are contained within a 4-column sidebar on desktop (33% width) and reflow to the bottom or become expandable drawers on mobile.
- **Spacing Rhythm:** An 8px base unit drives all spacing. Use `stack-lg` for separating major sections (e.g., Itinerary vs. Payment Table) and `stack-md` for internal component padding.
- **Safe Areas:** Maintain a minimum of 48px horizontal margin on desktop to allow the "Hero" imagery to breathe, as seen in the wireframe's illustrative header.

## Elevation & Depth

Visual hierarchy is established through **Tonal Layers** and **Ambient Shadows**.

- **Level 0 (Background):** The tertiary color (#F8FAFC) acts as the canvas.
- **Level 1 (Cards/Containers):** Pure white (#FFFFFF) surfaces with a 1px border in #E2E8F0.
- **Level 2 (Active/Floating):** Used for CTA cards and hovered states. These feature a soft, diffused shadow: `box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)`.
- **Focus:** No heavy blurs are used; instead, depth is conveyed through subtle contrast between the white container and the cool-grey background.

## Shapes

The design system uses a **Rounded** shape language to feel approachable and modern.

- **Standard Radius:** 0.5rem (8px) for input fields, small buttons, and list items.
- **Large Radius (rounded-lg):** 1rem (16px) for primary containers, trip cards, and image carousels.
- **Extra Large Radius (rounded-xl):** 1.5rem (24px) for the main CTA "Registration" card to make it visually distinct and prominent.

## Components

### Buttons
- **Primary:** Solid #2563EB with white text. Rounded (8px) for standard, or Pill-shaped for "Register" actions.
- **Secondary/Outline:** 1px border of #2563EB with primary colored text. Used for "Ver más" or "Ver Presupuesto."

### Tables (Payment/Schedule)
- **Header:** Light blue tint (#EFF6FF) with `label-md` typography.
- **Rows:** Alternating subtle zebra striping. Use 16px vertical padding for high readability.

### List Items & Itinerary
- **Milestones:** A vertical line (2px, #E2E8F0) connects circular icons to create a timeline effect.
- **Icons:** Enclosed in a soft-blue circle background. Use monochromatic stroke icons for a clean look.

### Cards
- **CTA Card:** High-contrast background (Secondary #0F172A) with white text to create a "sticky" focal point in the sidebar.
- **Benefit Chips:** Small, light-background containers with a checkmark icon, arranged in a flex-wrap grid.

### Input Fields
- Flat design with a subtle 1px border (#CBD5E1). On focus, the border transitions to Primary Blue with a soft 3px outer glow.