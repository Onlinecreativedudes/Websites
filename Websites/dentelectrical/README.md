# Dent Electrical & Air — WordPress Theme

**Client:** Dent Electrical & Air (Perth, WA)
**Build date:** May 2026
**Theme slug:** `dentelectrical`
**Built by:** Online Creative Dudes

A custom, self-contained WordPress theme for a Perth-based residential electrician. One landing page template plus a Thank You page, fully editable via ACF Pro and Gravity Forms.

---

## 1. Required plugins

Install and activate the following before activating the theme:

| Plugin | Notes |
|---|---|
| **ACF Pro** | Latest stable. Local JSON sync is wired up in the theme — field groups will appear in green "Sync available" state on first load and load automatically. |
| **Gravity Forms** | Latest stable. Used for the hero and contact forms. |
| **Rank Math** or **Yoast SEO** | For `robots.txt` and `sitemap.xml`. Either works. |
| **HFCM (Header Footer Code Manager)** | Mahesh will use this later to install GTM/tags. Don't insert any tracking scripts in the theme. |

Server requirements: WP 6.0+, PHP 8.1+.

---

## 2. Install steps

**Important order — install plugins first, theme second.** The theme auto-seeds content on activation, but it needs ACF Pro present at that moment to write the field values.

1. Activate **ACF Pro** and **Gravity Forms**.
2. Upload the `dentelectrical/` folder to `/wp-content/themes/`.
3. Activate the theme (**Appearance → Themes**).
4. Go to **Settings → Permalinks** and set to **Post Name**, then click Save.
5. **Auto-seed check** — when the theme activates with ACF Pro already on, it will:
   - Create the Thank You page (slug `/thank-you/`, template Thank You)
   - Create the Home page (template Landing Page) with all sections fully populated
   - Set Home as the static front page
   - Import placeholder images from `assets/seed/` into the Media Library and attach them
   - Populate Site Options (phone, email, branding, schema, promo pills)

   If you activated the theme **before** ACF Pro, go to **Tools → Seed Dent Content** and click the seed button to run it now.

6. Verify the seed worked:
   - Visit the front of the site → the full landing page should render with the launch copy and placeholder photos
   - **Custom Fields → Field Groups** shows four groups in green Sync state
   - **Site Options** in the sidebar is populated
   - Home and Thank You exist in **Pages** with the right templates

---

## 3. Page creation checklist

After the auto-seed, the only manual step here is verifying — the pages already exist.

| Order | Page title | Slug | Template | Created by seed? |
|---|---|---|---|---|
| 1 | Thank You | `thank-you` | **Thank You** | ✅ Yes |
| 2 | Home | `home` | **Landing Page** (front page) | ✅ Yes |
| 3 | (optional later) Services | `services` | Default | No — create manually if needed |
| 4 | (optional later) Contact | `contact` | Default | No — create manually if needed |

> **The Thank You page must exist before any Gravity Form is configured** — every form's confirmation redirect points at it. The seed handles this automatically.

### If you need to re-seed

Go to **Tools → Seed Dent Content** and click "Re-run seed". This overwrites Home + Site Options content but does not re-import images that are already in the Media Library.

---

## 4. Site Options setup

The seed populates these with launch values. **Review and replace any `[CLIENT TO CONFIRM ...]` placeholders** before going live. Go to **Site Options** in the admin sidebar:

### Contact
- Business phone (dialable) — digits only, eg `0487810565`
- Display phone (human-readable) — eg `0487 810 565`
- Business email — eg `admin@dentelectricalandair.com.au`
- Business address (full) — for schema
- Address (short) — for footer/contact section

### Branding
- Site logo (header)
- Footer logo (can be the same)
- Footer blurb
- Footer copyright line — use `{year}` for the current year
- EC number — eg `EC123456`

### Promo bar & nav
- Promo pills — short copy for the yellow top bar (max 4)
- Nav CTA button — text + URL (defaults to "Get a quote" → `#contact`)
- Mobile sticky bar — Quote URL (defaults to homepage `#contact`)

### Schema / SEO
- Schema business type — defaults to `Electrician`
- Schema suburb / state / postcode
- ABN (optional)

---

## 5. Landing Page content (Home)

The seed has filled in every section with the launch copy. Open the Home page in the editor only to:

- Replace placeholder images with the client's real photos (hero bg, founder portrait, 6 service cards, 3 feature blocks, 2 CTA bands, final CTA) — placeholders are clearly labelled "PLACEHOLDER — REPLACE VIA MEDIA LIBRARY"
- Resolve all `[CLIENT TO CONFIRM ...]` placeholders (EC number, real Google reviews, guarantee duration, etc.)
- Enter the **Gravity Form IDs** in the Hero and Contact tabs once forms are created (see §6)

The Landing Page template exposes one field group split into tabs:

1. **Hero** — eyebrow, headline (HTML allowed for colour spans), sub-headline, trust points, primary CTA, background image, form panel copy, **Gravity Form ID** for hero form
2. **Trust bar** — 5 short items with icons
3. **Services** — eyebrow, headline, intro, 6 service cards (title, copy, image, tag)
4. **CTA — Blue band** — mid-page CTA with background photo
5. **About founder** — photo, badge, eyebrow, headline, body copy, signature, 3 stats
6. **Why us** — eyebrow, headline, intro, 6 reasons (icon + title + copy)
7. **Feature — Compliance** — image, eyebrow, headline, copy, bullets, 2 CTAs
8. **Feature — Inspections** — same shape, reversed layout
9. **Feature — Process** — same shape
10. **CTA — Yellow band** — mid-page yellow CTA
11. **Reviews** — eyebrow, headline, intro, 3+ Google reviews
12. **Contact** — eyebrow, headline, hours, **Gravity Form ID** for contact form
13. **Final CTA** — full-bleed final pitch with background photo

### Headline rich-text spans

Inside any headline WYSIWYG you can wrap text in:
- `<span class="blue-mark">…</span>` — blue text
- `<span class="yellow-mark">…</span>` — yellow text
- `<span class="highlight">…</span>` — yellow highlight block
- `<span class="underline-mark">…</span>` — yellow underline
- `<br>` — line break

### Side panel (every page)
- **Page SEO** — meta description (150–160 chars) + social share image

---

## 6. Gravity Forms setup

Create **two forms**:

| Form name | Where it lives | ACF field that holds the ID |
|---|---|---|
| Hero — Quick Quote | Hero panel on Home | Landing Page → Hero → "Gravity Form ID (hero)" |
| Contact — Full Enquiry | Contact section on Home | Landing Page → Contact → "Gravity Form ID (contact)" |

After saving each form, copy the form's ID (visible in **Forms → Forms** list) and paste it into the corresponding ACF number field on the Home page.

### Mandatory settings on **every** form

Configure these via **Form Settings → Notifications / Confirmations** for each form before sign-off:

- **Notification BCC:** `hello@onlinecreativedudes.com` — always, on every form
- **Reply-To:** mapped to the form's email field (Replace `{Email:N}` merge tag)
- **Phone fields:** Format = **International** (not US/Standard)
- **AJAX submission:** Enabled (Form Settings → Form Options → "Enable AJAX")
- **Honeypot:** Enabled (Form Settings → Form Options → "Enable anti-spam honeypot")
- **Form title & description:** Hidden in form settings (the template renders headlines separately)
- **Confirmation:** Type = **Page**, Page = **Thank You** (slug `/thank-you/`)

> **Verify on every form before sign-off:** Confirmation must be Page → `/thank-you/`. Not a confirmation message. Not a different page. Always `/thank-you/`.

### Suggested fields

**Hero — Quick Quote:** Full name, Phone (intl), Suburb, Service (dropdown), Message (optional textarea)

**Contact — Full Enquiry:** Full name, Email, Phone (intl), Suburb, Service (dropdown), Message (textarea)

Service dropdown options to seed:
- General electrical / repair
- Electrical inspection / report
- Lighting / LEDs / downlights
- Switchboard upgrade
- Home automation / smart switches
- Safety switches / RCDs
- Pre-sale / pre-lease inspection
- Other

---

## 7. Menu setup

Go to **Appearance → Menus** and create three menus, assigning each to the right location:

| Menu name | Items | Location |
|---|---|---|
| Primary Navigation | Electrical Services (#services), About (#about), Process (#process), Reviews (#reviews), Contact (#contact) — use Custom Links | **Primary Navigation** |
| Footer — Services | General electrical, Inspections, Lighting, Switchboards, Home automation, Safety switches | **Footer — Services** |
| Footer — Company | About (#about), Process (#process), Reviews (#reviews), Contact (#contact) | **Footer — Company** |

---

## 8. Third-party services

- **Google Maps:** Not required for v1. ACF map fields are not used.
- **Mahesh / GTM:** Tracking installation happens later via HFCM. Don't add scripts to the theme.

---

## 9. Build notes / known points

- The Thank You page outputs `<meta name="robots" content="noindex, nofollow">` and contains a `<div id="conversion-event" data-event="form_submission">` for Mahesh to wire GTM triggers.
- Hero image gets `<link rel="preload">` automatically for LCP performance.
- Schema.org `LocalBusiness` JSON-LD is rendered in the footer from Site Options.
- All forms use the `.ocd-form` wrapper, so Gravity Forms styling is scoped — Gravity's default CSS in `Forms → Settings → CSS` can be left at default ("No"), and our styles will pick it up.
- No default WordPress functionality is disabled. Comments, oembed, REST API, XML-RPC, emoji scripts etc. remain intact per OCD build standards. Toggle them in Settings if the client requests it.
- Two custom image sizes are registered: `hero` (1920×1080 crop), `card` (800×600 crop), `thumb-sq` (600×600 crop), `founder` (900×1100 crop). After uploading initial images you may want to run **Regenerate Thumbnails** plugin once.

---

## 10. Front-end checklist (post-install QA)

- [ ] Auto-seed ran (Tools → Seed Dent Content shows "Seeded on …")
- [ ] Home renders with all 16 sections populated and placeholder images visible
- [ ] Thank You page exists with `/thank-you/` slug and `noindex` in `<head>`
- [ ] Promo bar shows on every page
- [ ] Nav becomes solid on scroll over hero
- [ ] Mobile nav toggle opens/closes
- [ ] Hero image preloads (Network panel: "preload")
- [ ] Placeholder images replaced with client's real photos
- [ ] All `[CLIENT TO CONFIRM ...]` placeholders resolved
- [ ] Hero Gravity Form submits → redirects to `/thank-you/`
- [ ] Contact Gravity Form submits → redirects to `/thank-you/`
- [ ] Mobile sticky bar appears below 700px, body has bottom padding
- [ ] LocalBusiness JSON-LD visible in `<script type="application/ld+json">`
- [ ] No console errors
- [ ] No PHP notices with `WP_DEBUG = true`
