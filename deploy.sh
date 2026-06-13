#!/bin/bash
#
# Server-side deploy for the dev cPanel account.
#
# The host firewall blocks inbound automation from GitHub (SSH and the cPanel
# API are filtered/403 from GitHub's IPs), so deployment is inverted: the
# server pulls from GitHub (outbound, allowed) and mirrors each site's theme
# into its WordPress install, then runs a one-time provisioning pass that
# activates the plugins and theme, creates the landing page, and wires up the
# form.
#
# Uses only tools the cPanel account is guaranteed to have: bash, git, php and
# cp. (The server has no python3 or rsync, so neither is used.)
#
# Run from a cPanel cron job for hands-off deploys, or via the "Deploy HEAD
# Commit" button (see .cpanel.yml). It reads sites.json: every entry under
# "sites" maps a Websites/<name>/ folder to a wp_path and theme_slug, relative
# to the cPanel user's home.
#
set -euo pipefail

REPO_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$REPO_DIR"

# Pull the latest main without leaving local state behind.
git fetch --quiet origin main
git reset --hard --quiet origin/main

# Locate a PHP binary (not always on PATH for cron).
PHP="$(command -v php || true)"
if [ -z "$PHP" ]; then
    for p in /usr/local/bin/php /usr/bin/php \
             /opt/cpanel/ea-php82/root/usr/bin/php \
             /opt/cpanel/ea-php81/root/usr/bin/php \
             /opt/cpanel/ea-php83/root/usr/bin/php; do
        [ -x "$p" ] && PHP="$p" && break
    done
fi
if [ -z "$PHP" ]; then
    echo "no php binary found; cannot deploy"
    exit 1
fi

# Site keys from sites.json (PHP does the JSON parsing).
sites="$("$PHP" -r '$c=json_decode(file_get_contents("sites.json"),true);echo implode("\n",array_keys($c["sites"]??[]));')"

while IFS= read -r name; do
    [ -z "$name" ] && continue
    src="$REPO_DIR/Websites/$name"
    if [ ! -d "$src" ]; then
        echo "skip $name: $src not found"
        continue
    fi

    wp_path="$("$PHP" -r '$c=json_decode(file_get_contents("sites.json"),true);$s=$c["sites"][$argv[1]];echo $s["wp_path"]??("public_html/".$argv[1]);' "$name")"
    slug="$("$PHP" -r '$c=json_decode(file_get_contents("sites.json"),true);$s=$c["sites"][$argv[1]];echo $s["theme_slug"]??$argv[1];' "$name")"

    themes_parent="$HOME/$wp_path/wp-content/themes"
    dest="$themes_parent/$slug"

    if [ ! -d "$themes_parent" ]; then
        echo "themes dir missing: $themes_parent -- is WordPress at $wp_path?"
        continue
    fi

    # Mirror the theme (remove then copy, so deletions in the repo propagate).
    rm -rf "$dest"
    mkdir -p "$dest"
    cp -a "$src/." "$dest/"
    echo "deployed $name -> $dest"

    # One-time provisioning: install/activate plugins, activate theme,
    # create page, wire form.
    wp_load="$HOME/$wp_path/wp-load.php"
    if [ -f "$wp_load" ]; then
        "$PHP" "$REPO_DIR/provision.php" "$wp_load" "$slug" "$HOME"
    else
        echo "$wp_load not found; skipping provisioning for $name"
    fi
done <<< "$sites"

echo "Deploy complete at $(date -u '+%Y-%m-%d %H:%M:%S UTC')"
