<?php
if (!defined('ABSPATH')) { exit; }

define('SN_THEME_VERSION', '1.0.0');
define('SN_THEME_PATH', get_template_directory());
define('SN_THEME_URI', get_template_directory_uri());

require_once SN_THEME_PATH . '/inc/theme-setup.php';
require_once SN_THEME_PATH . '/inc/enqueue.php';
require_once SN_THEME_PATH . '/inc/acf-config.php';
require_once SN_THEME_PATH . '/inc/acf-sync.php';
require_once SN_THEME_PATH . '/inc/acf-options.php';
require_once SN_THEME_PATH . '/inc/gravity-forms.php';
require_once SN_THEME_PATH . '/inc/helpers.php';
