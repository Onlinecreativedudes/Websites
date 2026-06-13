<?php
/**
 * Statement band (parallax, oversized line).
 * ACF tab: Statement (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$heading = get_field('statement_heading');
$sub     = get_field('statement_sub');
$image   = get_field('statement_image');

if (!$heading) { return; }

$bg = ($image && !empty($image['url'])) ? ($image['sizes']['hero'] ?? $image['url']) : '';
?>

<section class="statement"<?php if ($bg) : ?> style="background-image:url(<?php echo esc_url($bg); ?>)"<?php endif; ?>>
    <div class="container reveal">
        <div class="big"><?php echo wp_kses_post($heading); ?></div>
        <?php if ($sub) : ?><div class="sm"><?php echo esc_html($sub); ?></div><?php endif; ?>
    </div>
</section>
