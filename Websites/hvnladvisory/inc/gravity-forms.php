<?php
if (!defined('ABSPATH')) { exit; }

/**
 * Render a Gravity Form by ID with AJAX enabled, wrapped for theme styling.
 * Form IDs come from ACF number fields so the client can swap forms in admin.
 */
function ocd_render_form($form_id, $variant = '') {
    $form_id = absint($form_id);
    if (!$form_id || !function_exists('gravity_form')) { return; }

    $class = 'ocd-form' . ($variant ? ' ocd-form--' . sanitize_html_class($variant) : '');
    echo '<div class="' . esc_attr($class) . '">';
    gravity_form($form_id, false, false, false, null, true);
    echo '</div>';
}
