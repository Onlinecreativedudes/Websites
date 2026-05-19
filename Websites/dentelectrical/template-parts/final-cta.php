<?php
/**
 * Final CTA section
 * ACF group: group_finalcta_landing
 */
if (!defined('ABSPATH')) { exit; }

$headline = get_field('final_cta_headline');
$copy     = get_field('final_cta_copy');
$cta      = get_field('final_cta_cta');
$photo    = get_field('final_cta_photo');
$show_phone = get_field('final_cta_show_phone');

$phone_display = ocd_site_phone_display();
$phone_tel     = ocd_site_phone_tel();

if (!$headline) { return; }
?>

<section class="final-cta">
    <?php if ($photo && !empty($photo['ID'])) : ?>
        <div class="final-cta__bg">
            <?php echo wp_get_attachment_image($photo['ID'], 'hero', false, [
                'class' => 'final-cta__bg-img',
                'alt'   => '',
            ]); ?>
        </div>
    <?php endif; ?>

    <div class="container">
        <div class="final-cta__inner reveal">
            <h2 class="final-cta__headline"><?php echo wp_kses_post($headline); ?></h2>
            <?php if ($copy) : ?>
                <p class="final-cta__copy"><?php echo esc_html($copy); ?></p>
            <?php endif; ?>
            <div class="final-cta__actions">
                <?php ocd_render_link($cta, 'btn btn--primary'); ?>

                <?php if ($show_phone && $phone_display) : ?>
                    <a href="<?php echo esc_url($phone_tel); ?>" class="btn btn--outline-light">
                        <?php echo ocd_icon('phone'); ?>
                        <?php echo esc_html($phone_display); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
