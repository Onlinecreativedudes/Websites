<?php
if (!defined('ABSPATH')) { exit; }

/**
 * Render a Gravity Form by ID retrieved from an ACF field, falling back to the
 * site-wide default quote form set in Site Options. Returns silently if Gravity
 * Forms is not active or no form ID is available.
 *
 * @param int|null $form_id Optional explicit form ID.
 */
function pcp_render_gravity_form($form_id = null) {
    if (!$form_id && function_exists('get_field')) {
        $form_id = (int) get_field('form_id');
    }
    if (!$form_id && function_exists('get_field')) {
        $form_id = (int) get_field('default_quote_form_id', 'option');
    }

    if (!$form_id || !function_exists('gravity_form')) {
        return;
    }

    gravity_form($form_id, false, false, false, null, true);
}
