<?php
if (!defined('ABSPATH')) { exit; }

/**
 * Render a Gravity Form by ID retrieved from an ACF field.
 * Returns silently if Gravity Forms isn't active or no ID is set.
 *
 * @param int|null $form_id Optional. Pass an explicit form ID; otherwise
 *                          the current post's ACF `form_id` field is used.
 */
function sn_render_gravity_form($form_id = null) {
    if (!$form_id) {
        $form_id = function_exists('get_field') ? (int) get_field('form_id') : 0;
    }

    if (!$form_id || !function_exists('gravity_form')) {
        return;
    }

    gravity_form($form_id, false, false, false, null, true);
}
