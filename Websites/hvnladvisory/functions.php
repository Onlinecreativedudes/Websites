<?php
if (!defined('ABSPATH')) { exit; }

define('OCD_THEME_VERSION', '1.0.1');
define('OCD_THEME_PATH', get_template_directory());
define('OCD_THEME_URI', get_template_directory_uri());

require_once OCD_THEME_PATH . '/inc/theme-setup.php';
require_once OCD_THEME_PATH . '/inc/enqueue.php';
require_once OCD_THEME_PATH . '/inc/acf-config.php';
require_once OCD_THEME_PATH . '/inc/acf-options.php';
require_once OCD_THEME_PATH . '/inc/gravity-forms.php';
require_once OCD_THEME_PATH . '/inc/helpers.php';
require_once OCD_THEME_PATH . '/inc/seed-content.php';
