# Woody Craftwork — WordPress theme

Custom theme built by Online Creative Dudes for **Woody Craftwork**, a family-owned Perth joinery and custom cabinetry business.

- **Theme slug:** `woodycraftwork`
- **Build date:** June 2026
- **Dev URL:** https://dev.onlinecreativedudes.com/woodycraftwork
- **Type:** Single landing page (no custom post types)

This theme **auto-seeds its own content** on activation. Do not hand-build pages before activating — the theme creates the Home and Thank You pages with the launch copy and placeholder images already in place.

---

## Required plugins

Install and activate **before** the theme:

- ACF Pro (6.8+) — field groups load from `acf-json/`
- Gravity Forms (latest) — the two enquiry forms
- Yoast SEO (latest) — owns all titles, meta descriptions, canonicals, Open Graph and schema. The theme deliberately emits none of these.
- HFCM (Header Footer Code Manager) — for Mahesh to add GTM later

> WP Rocket is **not** installed by the build agent. The reviewer installs and configures it. The theme ships clean, unminified source with no hand-rolled critical CSS or defer.

---

## Install steps

1. Install and activate the plugins above.
2. Upload the `woodycraftwork/` folder to `/wp-content/themes/` and activate it (Appearance → Themes). On activation the theme will:
   - Create the **Thank You** page (slug `/thank-you/`, template Thank You)
   - Create the **Home** page (template Landing Page) fully populated
   - Set Home as the static front page
   - Import the placeholder images into the Media Library and attach them to every image field
   - Populate Site Options
3. Set permalinks to **Post Name** (Settings → Permalinks → Save).
4. Verify the seed ran — visit the homepage; it should render fully with photos. If empty (theme activated before ACF Pro), run **Tools → Seed Woody Content**.
5. Verify ACF field groups show in green "Synced" state under Custom Fields → Field Groups (Landing Page, Site Options, Thank You Page).

---

## Gravity Forms

Two forms. On the dev deploy these are created and wired automatically by `provision.php`. On a manual install, build them yourself and paste each ID into the matching ACF number field on the Home page.

| Form | Lives on | ACF field (Home page) | Fields |
| --- | --- | --- | --- |
| **Request a Call Back** | Hero call-back bar | `hero_form_id` (Hero tab) | Name (req), Message (req) |
| **Request a Free Quote** | Contact section | `contact_form_id` (Contact tab) | Name (req), Phone (req), Email (req), Suburb, Project type (req, select), About your project |

**Mandatory settings on every form (verify before sign-off):**

- Notification **BCC: `hello@onlinecreativedudes.com`** (always)
- Reply-To mapped to the form's email field (the quote form)
- Phone field format: **International**
- AJAX submission: enabled
- Honeypot: enabled
- Form title and description: hidden
- **Confirmation: Page redirect → `/thank-you/`.** Not a message, not a different page. Verify on both forms.

Project type select options: Custom Kitchen, Bathroom & Vanity, Laundry, Wardrobe / Robe, Home Office, Entertainment / Living, Commercial fit-out, Other.

---

## Site Options (Site Options menu)

- **Header:** announcement (left + highlight), logo mark, brand name, brand tagline, header button
- **Contact:** phone (dialable), phone (display), email
- **Footer:** footer blurb, copyright line (`{year}` token), footer credit

---

## Menus

Three locations are registered. Until you create menus, the header and footer fall back to sensible on-page anchor links, so the site looks right out of the box.

- **Primary Navigation** — Cabinetry Services (`#services`), About (`#about`), Why Us (`#why`), Our Work (`#gallery`), Contact (`#contact`)
- **Footer — Quick Links**
- **Footer — Services**

---

## Items for the client to confirm / replace

- **ABN** — footer shows `ABN [CLIENT TO CONFIRM]` (Site Options → Footer → Copyright line)
- **Reviews** — three placeholder reviews are flagged with a dashed "Placeholder — replace" badge. Swap in real Google reviews and turn off the "Placeholder?" toggle on each (Home → Reviews tab)
- **Photos** — placeholder cabinetry photos are imported from the design. Replace each image field with the client's real project photography (Home page, walk each tab; logo mark in Site Options)
- **Founder name** — copy references "Filipe". Confirm spelling and surname.
- **Thank You page** — confirm it is set to `noindex` in Yoast (the theme does not emit robots tags).

---

## Notes

- **Fonts** are self-hosted (Jost + Hanken Grotesk, latin + latin-ext woff2 subsets) in `assets/fonts/`, with `font-display: swap`; the two latin faces are preloaded.
- **Mobile hero image** (`hero_image_mobile`) renders above the hero heading on screens ≤768px and is suppressed on desktop via a `<picture>` source. It is the mobile LCP image and is not lazy-loaded.
- **Meta/SEO/schema** are owned entirely by Yoast. The theme outputs no meta description, canonical, Open Graph, or JSON-LD.
- **Parallax bands** use fixed background images on desktop and pin to scroll on mobile.
- The gallery opens a lightbox; arrow keys and Escape are wired.
