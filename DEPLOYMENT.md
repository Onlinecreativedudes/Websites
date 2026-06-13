# Deploying the dev sites

All client sites live on one dev cPanel account, each WordPress install in its
own folder under `public_html/`. This repo holds each site's theme under
`Websites/<client>/` and a `sites.json` map. The live site is whatever is on
the `main` branch.

## How deployment works

The dev host blocks inbound automation from GitHub (SSH is filtered and the
cPanel API returns 403 from GitHub IPs), so deployment is inverted: the
**server pulls from GitHub**, it is never pushed to. The work is done by
`deploy.sh`, which:

1. `git fetch` + `git reset --hard origin/main` (so the clone matches `main`)
2. for every entry in `sites.json`, mirrors `Websites/<client>/` into that
   site's `wp-content/themes/<slug>`
3. runs `provision.php`, which activates the required plugins and the theme,
   creates the Home and Thank You pages, seeds content and images, and creates
   the Gravity Forms, all guarded so they run once and never fight manual edits.

Because of this, the GitHub Actions workflow (`.github/workflows/deploy.yml`)
is kept for manual runs only and will not work while the host firewalls the
cPanel API.

## Triggering a deploy

There are two ways to run `deploy.sh`, and they can coexist.

### Manual (instant, deliberate)

cPanel -> **Git Version Control** -> the Websites repo -> **Deploy HEAD
Commit**. This pulls `main` and runs `deploy.sh` immediately (wired via
`.cpanel.yml`).

### Automatic (hands-free) — recommended

Add a cron job once in cPanel -> **Cron Jobs**. After that, every push to
`main` goes live on the next tick, no button required:

```
*/10 * * * * /bin/bash "$HOME/<repo-path>/deploy.sh" >> "$HOME/deploy.log" 2>&1
```

- `<repo-path>` is the Repository Path shown on the Git Version Control page
  (for example `repositories/Websites`).
- Adjust the cadence to taste (`*/10` = every 10 minutes; `*/30` = half-hourly;
  `0 * * * *` = hourly).
- `deploy.log` in the home directory records each run.

Trade-off: cron means a short delay and auto-applies whatever is on `main`. The
button is instant. Running cron on a slower cadence and still hitting the button
when you want it now is a fine middle ground.

## Onboarding a new site (one-time per client)

Before a site's first deploy can fully provision:

1. Install WordPress at `public_html/<client>` on the dev cPanel.
2. Make sure the build plugins (ACF Pro, Gravity Forms, Yoast) are present as
   zips in `~/plugin-cache` (or `~/plugins`) so `provision.php` can install
   them. Paid binaries are kept off the public repo.
3. Add the client's entry to `sites.json`.

If WordPress is not yet installed at the target path, the deploy log says so and
skips provisioning for that site until it is.
