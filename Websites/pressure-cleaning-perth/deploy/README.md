# Pressure Cleaning Perth — deploy notes

Theme-only repository. WordPress core, ACF Pro, Gravity Forms, Yoast SEO and WP Rocket
are installed on the target, not committed here.

## Deploy steps (reviewer)

1. **Database** — create it in cPanel.
2. **WordPress** — standard install if not already present.
3. **wp-config.php** — create with the DB credentials. Never from this repo.
4. **Plugins** — install and activate **ACF Pro (6.8+)**, **Gravity Forms**, **Yoast SEO**.
   Confirm ACF Pro is 6.8 or later (the `wp acf json` CLI is required).
5. **Theme** — deploy this theme into `wp-content/themes/pressure-cleaning-perth/` and activate it.
6. **Sync ACF schema** (field groups, the Service and Commercial post types, Site Options):
   ```
   wp acf json status
   wp acf json sync --dry-run
   wp acf json sync
   ```
   Confirm `wp acf json status` reports nothing pending afterwards.
7. **Seed content** (optional, recommended):
   ```
   wp eval-file wp-content/themes/pressure-cleaning-perth/deploy/seed.php
   wp eval-file wp-content/themes/pressure-cleaning-perth/deploy/seed-services.php
   ```
   The first creates the page tree, front/blog pages, Commercial entries and the
   primary menu. The second creates the 13 Service pages with their full content
   (hero, problem, methods, why, process, FAQs, areas) plus Yoast title/meta.
   Both are idempotent (match by slug, update in place). Then flush permalinks:
   `wp rewrite flush` (the seeds also flush).
8. **Gravity Forms** — create/import the quote form. Set its ID in
   **Site Options → CTA & Forms → Default quote form**, and per-page form IDs where wanted.
9. **Site Options** — upload the logo (or it falls back to the bundled brand asset),
   set phone/email, social links.
10. **WP Rocket** — install and configure (minify, combine, critical CSS, defer/delay JS,
    lazy-load, page cache). The theme ships lean, unminified source by design.

## Notes

- **Meta/SEO**: the theme emits no title, meta description, canonical or Open Graph tags.
  Yoast owns all of that.
- **Fonts**: enqueued from Google Fonts (Archivo, Hanken Grotesk, Caveat), matching the
  design source. Self-hosting/subsetting is a deferred optimisation — the export's font
  files are bundler-UUID variable fonts and were not cleanly mappable at build time.
- **Mobile hero image**: every hero has a `mobile_hero_image` ACF field. It renders above
  the headline on mobile only, is hidden on desktop, and is not lazy-loaded (likely LCP).
- **Missing decorative asset**: the CSS references `brand/footer-torn.png` (the green torn
  footer edge). It was supplied as a chat image only, not a file — drop the PNG into
  `assets/brand/footer-torn.png` to restore that edge. The footer renders correctly without it.
- **Images**: content photos ship in `assets/images/` as design seed. Replace with real
  uploads via the ACF image fields; templates fall back to the seed assets when a field is empty.
