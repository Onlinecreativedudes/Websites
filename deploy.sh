#!/bin/bash
#
# Server-side deploy for the dev cPanel account.
#
# The host firewall blocks inbound automation from GitHub (SSH and the cPanel
# API are filtered/403 from GitHub's IPs), so deployment is inverted: the
# server pulls from GitHub (outbound, allowed) and mirrors each site's theme
# into its WordPress install. Run from a cPanel cron job for hands-off deploys,
# or via the "Deploy HEAD Commit" button (see .cpanel.yml).
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
import json, os, subprocess, sys

repo, home = sys.argv[1], sys.argv[2]
cfg = json.load(open(os.path.join(repo, "sites.json")))

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
PY

echo "Deploy complete at $(date -u '+%Y-%m-%d %H:%M:%S UTC')"
