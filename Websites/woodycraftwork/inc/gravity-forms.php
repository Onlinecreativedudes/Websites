<?php
if (!defined('ABSPATH')) { exit; }

/**
 * Render a Gravity Form by ID retrieved from an ACF field.
 * Returns silently if Gravity Forms isn't active or no ID is set.
 *
 * @param int|null $form_id Optional explicit form ID.
 */
function ocd_render_gravity_form($form_id = null) {
    $form_id = (int) $form_id;
    if (!$form_id || !function_exists('gravity_form')) {
        return;
    }
    gravity_form($form_id, false, false, false, null, true);
}
