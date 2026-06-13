#!/bin/bash
#
# Server-side deploy for the dev cPanel account.
#
# The host firewall blocks inbound automation from GitHub (SSH and the cPanel
# API are filtered/403 from GitHub's IPs), so deployment is inverted: the
# server pulls from GitHub (outbound, allowed) and mirrors each site's theme
# into its WordPress install, then runs a one-time provisioning pass that
# activates the theme, creates the landing page, and wires up the form.
#
# Run from a cPanel cron job for hands-off deploys, or via the "Deploy HEAD
# Commit" button (see .cpanel.yml).
#
# It reads sites.json: every entry under "sites" maps a Websites/<name>/ folder
# to a wp_path and theme_slug. Paths are relative to the cPanel user's home.
#
set -euo pipefail

REPO_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$REPO_DIR"

# Pull the latest main without leaving local state behind.
git fetch --quiet origin main
git reset --hard --quiet origin/main

python3 - "$REPO_DIR" "$HOME" <<'PY'
import json, os, shutil, subprocess, sys

repo, home = sys.argv[1], sys.argv[2]
cfg = json.load(open(os.path.join(repo, "sites.json")))

def find_php():
    p = shutil.which("php")
    if p:
        return p
    for cand in ("/usr/local/bin/php", "/usr/bin/php",
                 "/opt/cpanel/ea-php82/root/usr/bin/php",
                 "/opt/cpanel/ea-php81/root/usr/bin/php"):
        if os.path.exists(cand):
            return cand
    return None

for name, site in cfg.get("sites", {}).items():
    src = os.path.join(repo, "Websites", name) + "/"
    if not os.path.isdir(src):
        print(f"skip {name}: {src} not found")
        continue
    wp_path = site.get("wp_path", f"public_html/{name}")
    slug    = site.get("theme_slug", name)
    dest    = os.path.join(home, wp_path, "wp-content", "themes", slug) + "/"
    os.makedirs(dest, exist_ok=True)
    subprocess.run(["rsync", "-a", "--delete", src, dest], check=True)
    print(f"deployed {name} -> {dest}")

    # One-time provisioning: activate theme, create page, wire form.
    wp_load = os.path.join(home, wp_path, "wp-load.php")
    if os.path.isfile(wp_load):
        php = find_php()
        if php:
            subprocess.run([php, os.path.join(repo, "provision.php"), wp_load, slug, home])
        else:
            print("no php cli found; skipping provisioning")
    else:
        print(f"{wp_load} not found; skipping provisioning for {name}")
PY

echo "Deploy complete at $(date -u '+%Y-%m-%d %H:%M:%S UTC')"
