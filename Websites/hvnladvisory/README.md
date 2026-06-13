# HVNL Advisory — theme install handover

- **Theme:** HVNL Advisory (`hvnladvisory`)
- **Client:** HVNL Advisory — Heavy Vehicle National Law / Chain of Responsibility compliance advisory
- **Build date:** June 2026
- **Dev install:** `public_html/hvnladvisory` → https://dev.onlinecreativedudes.com/hvnladvisory

The theme **auto-seeds its own content** on activation (or from Tools → Seed HVNL Content). Do not create the pages by hand — the seeder creates Home and Thank You with the full launch copy and placeholder images.

## Required plugins (activate BEFORE the theme)

1. **ACF Pro** (6.8+ — the build relies on `acf-json/` sync and the options page)
2. **Gravity Forms** (latest stable)
3. **Yoast SEO** — owns titles, meta descriptions, canonicals, Open Graph and sitemaps. The theme intentionally outputs none of those.
4. **HFCM** (Header Footer Code Manager) — for Mahesh to install GTM later

Do **not** install WP Rocket or any caching/optimisation plugin — the reviewer installs and configures WP Rocket separately.

## Install steps

1. Activate the plugins above.
2. Upload `hvnladvisory/` to `/wp-content/themes/` and activate it.
3. The seeder runs on activation: Thank You page, Home page (set as static front page), placeholder images, Site Options. If the homepage looks empty, run Tools → Seed HVNL Content (happens if the theme was activated before ACF Pro).
4. Set permalinks to **Post Name** (Settings → Permalinks → Save).
5. Verify ACF groups: Custom Fields → Field Groups should list **Landing Page**, **Site Options**, **Thank You Page** — sync them if they show as pending.

## Gravity Forms (2 forms)

Create both forms, then paste their IDs into the Home page ACF fields (Hero tab → "Gravity Form ID", Contact tab → "Gravity Form ID").

**Form 1 — Exposure Review (hero form)**
- Fields: Name (text, required), Company (text), Email (email, required), Phone (phone), "Your role in the supply chain" (select, required) with options:
  - We send or receive freight
  - We provide transport or logistics services
  - We operate heavy vehicles
  - Not sure
- Set each field's **placeholder** to its label — the theme hides labels visually and shows the underline style from the design. Make Name/Company/Email/Phone 50% width (two-column), select full width.

**Form 2 — Contact (Send us a note)**
- Fields: Name (text, required), Company (text), Email (email, required), Phone (phone), Message (paragraph). Same placeholder/width treatment; Message full width.

**Mandatory settings on every form:**
- Notification **BCC: `hello@onlinecreativedudes.com`**
- Reply-To mapped to the form's email field
- Phone field format: **International** (not US/Standard)
- AJAX submission enabled
- Honeypot enabled
- Form title and description hidden in form settings
- **Confirmation: Page redirect → `/thank-you/`.** Not a message. Not a different page. Verify on both forms before sign-off. (The design showed an inline success card; the build standard replaces that with the redirect so conversions can be tracked.)

## Menus (Appearance → Menus)

- **Primary Navigation:** Services (`#services`), Who We Help (`#who`), How We Work (`#how`), About (`#about`), Contact (`#contact`) — custom links to those anchors.
- **Footer — Quick Links:** same five anchor links.
- **Footer — Services:** CoR Self-Assessment, On-Site CoR Audit, System Design, Managed Support, Enforcement Response, NHVAS Audits — all pointing to `#services`.

## Site Options checklist

Seeded with launch values — confirm each: logo, announcement strip text, header CTA label, loader toggle/tagline, business phone `+61386186954`, display phone `(03) 8618 6954`, email `contact@hvnladvisory.com.au`, coverage "All of Australia", footer blurb, copyright, disclaimer, schema type (ProfessionalService), optional address/ABN.

## Placeholders to resolve before launch

- Reviews section: three `[CLIENT TO CONFIRM]` testimonial cards — replace with real Google reviews or direct testimonials.
- All photography is the design's placeholder imagery — swap via the Media Library / Home page image fields when the client supplies final photos.
- **Mobile hero image** (Hero tab): intentionally empty placeholder field — populate with a conversion-focused image for Google Ads landing traffic. It renders above the headline on mobile only and is never lazy-loaded.

## Notes for the install agent

- The supplied logo is a JPEG on white; the theme blends it (multiply on light, inverted on dark) exactly like the design. A transparent PNG/SVG from the client can replace it later with no code change.
- SEO meta is Yoast's job — the theme outputs no description/OG/canonical tags. Configure titles/descriptions in Yoast after install.
- The intro loader animation can be turned off at Site Options → "Show intro loader animation".
- No tracking scripts in the theme (GTM goes in via HFCM later by Mahesh).
- Do not install a page builder, alternate form plugin, or any CSS/JS framework.
