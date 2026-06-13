# Solar Naturally — WordPress theme

Custom landing page theme for Solar Naturally (Bunbury solar & battery), built by Online Creative Dudes.

- One page template: `page-templates/landing-page.php` (assign it to the landing page and set Site Options).
- All content edits live in ACF: page sections on the page itself (tabbed by section), site-wide content under **Site Options**.
- The assessment form is Gravity Forms: create the form (First/Last name, Phone, Email, Suburb, Bill select, Own-home radio), then put its ID in **Site Options → Forms**. Every embedded form and the popup use that ID.
- ACF schema is local JSON in `acf-json/` — run `wp acf json sync` after deploy (the CI pipeline does this automatically).
- Fonts are self-hosted (`assets/fonts/`), icons are inlined Lucide SVGs, all interactions are vanilla JS (`assets/js/main.js`). No jQuery.
