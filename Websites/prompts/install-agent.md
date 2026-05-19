# Install agent — handover prompt

Use this when handing a finished theme over to the install agent. Replace `<CLIENT_SLUG>` with the actual slug (eg `dentelectrical`) and `<CLIENT_NAME>` with the client name.

---

You are installing a custom WordPress theme on a fresh WordPress install for `<CLIENT_NAME>`. The theme is delivered as `<CLIENT_SLUG>.zip` (or alternatively the folder `<CLIENT_SLUG>/` from the OCD `Websites` repo).

This theme is built to OCD standards and **AUTO-SEEDS its own content** on activation. Do not edit page content by hand before activation — the theme creates the pages itself with the launch copy already filled in.

## Install steps (do these in this exact order)

1. **Plugins first** — install and activate these BEFORE the theme:
   - ACF Pro (latest stable)
   - Gravity Forms (latest stable)
   - Rank Math OR Yoast SEO (for sitemap/robots)
   - HFCM (Header Footer Code Manager) — for Mahesh to install GTM later

2. **Upload the theme:**
   - Unzip `<CLIENT_SLUG>.zip` and upload the `<CLIENT_SLUG>/` folder to `/wp-content/themes/`.

3. **Activate the theme** at Appearance → Themes. On activation, the theme will automatically:
   - Create the Thank You page (slug `/thank-you/`, template Thank You)
   - Create the Home page (template Landing Page) fully populated with launch copy
   - Set Home as the static front page
   - Import placeholder images into the Media Library and attach them to every image field
   - Populate Site Options

4. **Set permalinks** to Post Name at Settings → Permalinks → Save.

5. **Verify the seed ran** — visit the homepage. You should see the full landing page rendered with placeholder photos and full launch copy. If it looks empty, go to Tools → Seed Content and click the seed button (this happens if you activated the theme before ACF Pro).

6. **Create the Gravity Forms** the theme expects (the per-client README lists them). Mandatory settings on **every** form:
   - **Notification BCC: `hello@onlinecreativedudes.com`**
   - Reply-To mapped to the form's email field
   - Phone field format: **International** (not US/Standard)
   - AJAX submission enabled
   - Honeypot enabled
   - Form title and description hidden in form settings
   - **Confirmation: Page redirect → `/thank-you/`.** Not a message. Not a different page. Verify on every form before sign-off.

7. **Wire forms to the page** — open the Home page in the editor and paste each form's ID into the matching ACF "Gravity Form ID" number field.

8. **Replace placeholder images** — placeholders are clearly labelled "PLACEHOLDER — REPLACE VIA MEDIA LIBRARY". Open the Home page, walk each tab, swap each image field for the client's real photo. Also swap the header/footer logo in Site Options.

9. **Resolve placeholders** — search for `[CLIENT TO CONFIRM ...]` markers in the Home page editor and Site Options and replace with real values.

10. **Create menus** at Appearance → Menus and assign them to the registered locations (Primary Navigation, Footer — Services, Footer — Company). The per-client README lists what each menu should contain.

## Do not

- Add tracking scripts to the theme (GTM goes in via HFCM later by Mahesh)
- Install a page builder, alternate form plugin, or any CSS/JS framework
- Touch the theme PHP/CSS/JS to change content — everything is editable via ACF in the admin
- Disable any default WordPress behaviour (comments, oembed, REST, emoji, etc.)
- Change the form confirmation target to anything other than `/thank-you/`

## Sign-off checklist

- [ ] Auto-seed ran (Tools → Seed Content shows "Seeded on …")
- [ ] Home renders with all sections populated and images visible
- [ ] Thank You page exists with `/thank-you/` slug and `noindex` in `<head>`
- [ ] All forms submit successfully and redirect to `/thank-you/`
- [ ] No `[CLIENT TO CONFIRM]` markers remain on the public site
- [ ] All placeholder images replaced with the client's real photos
- [ ] No console errors on the homepage
- [ ] No PHP notices with `WP_DEBUG = true`

The full reference is the README inside the theme folder.
