#!/usr/bin/env bash
# Runs ON the cPanel dev server, piped over SSH by provision.yml.
# Expects env: CLIENT WP_PATH THEME_SLUG SITE_TITLE ADMIN_USER ADMIN_EMAIL
set -euo pipefail

cd "$HOME"

if wp core is-installed --path="$WP_PATH" 2>/dev/null; then
  echo "WordPress is already installed at $WP_PATH — nothing to do."
  wp core version --path="$WP_PATH"
  exit 0
fi

CPUSER=$(whoami)
DBNAME="${CPUSER}_${CLIENT}"
DBUSER="$DBNAME"
DBPASS=$(openssl rand -hex 16)
ADMIN_PASS=$(openssl rand -hex 12)

# The install is served from a subfolder of the account's main domain.
MAIN_DOMAIN=$(uapi DomainInfo list_domains 2>/dev/null | awk '$1 == "main_domain:" {print $2; exit}')
if [ -z "$MAIN_DOMAIN" ]; then
  echo "Could not determine the account's main domain via uapi DomainInfo" >&2
  exit 1
fi
SITE_URL="https://${MAIN_DOMAIN}/${CLIENT}"
echo "Installing WordPress at $WP_PATH — site URL $SITE_URL"

# uapi exits 0 even when a call fails; success is "status: 1" in its output.
uapi_ok() {
  local out
  out=$(uapi "$@" 2>&1) || true
  printf '%s\n' "$out" | grep -v 'password' || true
  if printf '%s\n' "$out" | grep -qE '^[[:space:]]*status: 1$'; then
    return 0
  fi
  if printf '%s\n' "$out" | grep -qi 'already exists'; then
    echo "uapi $2: already exists — continuing"
    return 0
  fi
  echo "uapi $1 $2 failed" >&2
  return 1
}

echo "Creating database $DBNAME and user $DBUSER"
uapi_ok Mysql create_database name="$DBNAME"
uapi_ok Mysql create_user name="$DBUSER" password="$DBPASS"
# If the db user pre-existed, make sure the password matches the one we use
uapi_ok Mysql set_password user="$DBUSER" password="$DBPASS"
uapi_ok Mysql set_privileges_on_database user="$DBUSER" database="$DBNAME" privileges=ALL%20PRIVILEGES

mkdir -p "$WP_PATH"
wp core download --path="$WP_PATH"
wp config create --path="$WP_PATH" --dbname="$DBNAME" --dbuser="$DBUSER" --dbpass="$DBPASS" --dbhost=localhost
wp core install --path="$WP_PATH" --url="$SITE_URL" --title="$SITE_TITLE" \
  --admin_user="$ADMIN_USER" --admin_password="$ADMIN_PASS" --admin_email="$ADMIN_EMAIL" --skip-email

# Dev site: keep it out of search engines, use Post Name permalinks
wp option update blog_public 0 --path="$WP_PATH"
wp rewrite structure '/%postname%/' --path="$WP_PATH"
cat > "$WP_PATH/.htaccess" <<HTACCESS
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /${CLIENT}/
RewriteRule ^index\.php\$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /${CLIENT}/index.php [L]
</IfModule>
# END WordPress
HTACCESS

# Placeholder theme named after the site title; the built theme replaces
# these files when the deploy workflow rsyncs Websites/<client>/ over it.
THEME_DIR="$WP_PATH/wp-content/themes/$THEME_SLUG"
if [ ! -d "$THEME_DIR" ]; then
  mkdir -p "$THEME_DIR"
  cat > "$THEME_DIR/style.css" <<STYLE
/*
Theme Name: $SITE_TITLE
Description: Custom OCD theme for $SITE_TITLE. Placeholder scaffold — the built theme deploys from the Websites repo.
Version: 0.1.0
*/
STYLE
  cat > "$THEME_DIR/index.php" <<'INDEX'
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<main>
  <h1><?php bloginfo( 'name' ); ?></h1>
  <p>Theme scaffold &mdash; build pending.</p>
</main>
<?php wp_footer(); ?>
</body>
</html>
INDEX
fi
wp theme activate "$THEME_SLUG" --path="$WP_PATH"

# Credentials stay on the server, never in the CI log
CREDS="$HOME/.wp-admin-${CLIENT}.txt"
umask 077
cat > "$CREDS" <<EOF
WordPress install for $SITE_TITLE
Path:       $WP_PATH
URL:        $SITE_URL
Admin URL:  $SITE_URL/wp-admin/
Admin user: $ADMIN_USER
Admin pass: $ADMIN_PASS
DB name:    $DBNAME
DB user:    $DBUSER
DB pass:    $DBPASS
EOF
echo "Credentials written to $CREDS on the server (not printed in CI logs)."

echo "--- verification ---"
wp core version --path="$WP_PATH"
wp option get siteurl --path="$WP_PATH"
wp theme list --status=active --path="$WP_PATH"
curl -sk -o /dev/null -w "front page HTTP %{http_code}\n" "$SITE_URL/" || true
