# Pusdatin Kemendikdasmen — Design System & Visual Specification

> **System Name:** Pusdatin Kemendikdasmen Data Backbone UI  
> **Target Platform:** Sistem Manajemen Kerja Sama Pemanfaatan Data Backbone  
> **Brand Identity:** Pusat Data dan Teknologi Informasi, Kementerian Pendidikan Dasar dan Menengah Republik Indonesia  

---

## 1. Brand Identity & Design Spine

This design system establishes an executive, authoritative, and modern visual language tailored for **Pusdatin Kemendikdasmen**. It combines the trust and weight of a national government institution with the sleek responsiveness of modern data architecture.

### Core Visual Principles
1. **Executive Authority & Trust:** Built upon a deep, rich navy foundation (`#030B1E`) representing data security and institutional credibility.
2. **Precision & Clarity:** Data metrics, numerical indicators, and timestamps use tabular monospace typography (`JetBrains Mono`), eliminating visual ambiguity.
3. **Anti-Generic Surface Elevation:** Surfaces utilize micro-gradients, 1px translucent borders (`border-white/10` or `border-slate-200/80`), and localized radial ambient glows instead of noisy dropshadows or generic purple gradients.
4. **WCAG 2.1 AA Compliance & Performance:** All text pairings meet a minimum 4.5:1 contrast ratio. Animations use GPU-accelerated properties (`transform`, `opacity`) with zero heavy runtime overhead.

---

## 2. Color Palette & Token System

### Institutional Base Palette
- **Deep Navy (`--color-navy-base`):** `#030B1E` — Used for main app sidebars, dark hero backdrop, and high-level headers.
- **Navy Surface (`--color-navy-surface`):** `#0A192F` — Card containers and dropdowns on dark mode.
- **Canvas Slate (`--color-canvas-slate`):** `#F8FAFC` — Main workspace background for light mode.
- **Surface White (`--color-surface-white`):** `#FFFFFF` — Primary content cards and table containers.

### Accent & Brand Colors
- **Royal Tech Blue (`--color-brand-primary`):** `#1E50A2` — Primary button background, active nav borders.
- **Electric Blue (`--color-brand-vibrant`):** `#2563EB` — Interactive focus states and text accents.
- **Tut Wuri Amber Gold (`--color-brand-accent`):** `#D97706` / `#F59E0B` — Inspired by the Tut Wuri Handayani emblem, used for warnings, highlights, and urgent status badges.

### Status Indicators
- **Completed / Approved (`--color-status-success`):** `#10B981` (Emerald)
- **Pending / In Review (`--color-status-warning`):** `#F59E0B` (Amber)
- **Discussion / Action Required (`--color-status-info`):** `#3B82F6` (Blue)
- **Rejected / Expired (`--color-status-danger`):** `#F43F5E` (Rose)

---

## 3. Typography Hierarchy

| Role | Font Family | Weight | Tracking / Line Height | Usage |
|---|---|---|---|---|
| **Display / Hero H1** | `Poppins` | Bold (700) | `tracking-tight leading-tight` | Main landing headline |
| **Section H2 / Page Title** | `Poppins` | SemiBold (600) | `tracking-tight` | Header titles, card section titles |
| **Card Title / Subhead** | `Poppins` | Medium (500) | `leading-snug` | Table column headers, card titles |
| **Body Text** | `Poppins` | Regular (400) | `leading-relaxed` | Descriptions, labels, form field values |
| **UI Labels & Badges** | `Poppins` | Medium / SemiBold | `tracking-normal` | Stat values, status badges, menu items |


---

## 4. Component Standards

### 4.1 Buttons & CTAs
- **Primary CTA:** Background `#1E50A2` / `#2563EB`, text `#FFFFFF`, rounded corners `rounded-xl`, hover state `hover:bg-indigo-700 shadow-md transition-all duration-200 active:scale-[0.98]`.
- **Secondary / Ghost CTA:** Border `border border-slate-300`, background `bg-white`, hover `hover:bg-slate-100 text-slate-800`.
- **Contrast Guarantee:** Every CTA must maintain >= 4.5:1 text-to-background contrast ratio.

### 4.2 Stat Cards & Dashboards
- **Structure:** Dual-layer surface with clean 1px border (`border-slate-200/80`), icon container in desaturated micro-gradient pill, metric in `JetBrains Mono`, and subtle micro-label.
- **Hover Feedback:** Smooth 1-2px Y-translation (`hover:-translate-y-0.5 hover:shadow-md transition-all duration-200`).

### 4.3 Navigation & Sidebar
- **Sidebar Theme:** Dark Navy (`#050C1D`), text `#94A3B8`, active route highlighted with `bg-indigo-600/20 text-white border-l-4 border-indigo-400 font-semibold`.
- **Header:** Sticky translucent white (`bg-white/90 backdrop-blur-md`), soft bottom border (`border-slate-200/80`), keyboard skip-to-content link included.

---

## 5. Performance & Accessibility Guardrails

1. **Font Loading Strategy:** All Google Fonts use `font-display: swap` and preconnect links (`<link rel="preconnect" href="https://fonts.googleapis.com">`).
2. **WCAG 2.1 AA Keyboard Navigation:** Visible focus rings (`focus:ring-2 focus:ring-indigo-500 focus:outline-none`) on all interactive buttons, links, and inputs. Screen-reader skip-to-content links (`#main-content`) preserved on all layouts.
3. **Smooth Micro-Animations:** Standardized transition timings (`duration-200 ease-in-out`), avoiding long, distracting page transitions.
