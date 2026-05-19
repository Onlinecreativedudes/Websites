# New client build — kickoff prompt

Use this when starting a new client theme. Paste the body below as the first message in a fresh Claude Code session pointed at the `Websites` repo.

---

You are building a new custom WordPress theme for an OCD client. The deliverable is a self-contained theme folder ready for the install agent to drop into `/wp-content/themes/`.

**Build standard:** read `docs/ocd-build-guidelines.md` in this repo first and follow it exactly. Hard rules in there are non-negotiable (no page builders, no other form plugins, no CSS/JS frameworks, no build tooling, never strip default WordPress behaviour, every form confirmation must redirect to `/thank-you/`).

**Reference build:** `dentelectrical/` is a complete working example. Mirror its file structure, ACF JSON conventions, helper naming, seed system, and README format.

**Before you write a single file, confirm:**
1. Client slug (lowercase, no spaces — eg `acmeplumbing`)
2. Client name (display name — eg `Acme Plumbing & Gas`)
3. Design source (Figma URL, HTML file, or image mockup)
4. Brand colours (or "match the design")
5. What sections the landing page has (list them)
6. Phone, email, and any other contact info

If you don't have all of these, stop and ask.

**Build flow:**
1. Create `<clientslug>/` at the repo root
2. Scaffold from the dentelectrical structure
3. Update `style.css` header, `OCD_THEME_VERSION`, text domain, and function prefix
4. Build template parts to match the design's sections
5. Wire all content through ACF JSON field groups (one group per template, organised by tabs)
6. Embed Gravity Forms via `gravity_form()` with the ID from an ACF number field
7. Auto-seed the design content on theme activation (mirror `inc/seed-content.php`)
8. Generate branded placeholder images for every image field
9. Write `README.md` with install steps, GF mandatory settings, and the `/thank-you/` redirect requirement
10. Run §19 of the guidelines as a final checklist

**Deliverable:** zip the client folder and surface it to the user, plus paste the install prompt they can hand to the install agent.

**Do not** mix client work into other folders. Each client is fully self-contained.
