<?php
if (!defined('ABSPATH')) { exit; }

$phone_display = ocd_site_phone_display();
$phone_tel     = ocd_site_phone_tel();

$quote_url = get_field('mobile_bar_quote_url', 'option');
if (!$quote_url) { $quote_url = home_url('/#contact'); }
?>

<div class="mobile-bar" role="complementary" aria-label="<?php esc_attr_e('Quick actions', 'dentelectrical'); ?>">
    <div class="mobile-bar__inner">
        <?php if ($phone_display) : ?>
            <a href="<?php echo esc_url($phone_tel); ?>" class="mobile-bar__btn mobile-bar__btn--primary">
                <?php echo ocd_icon('phone'); ?>
                <?php esc_html_e('Call now', 'dentelectrical'); ?>
            </a>
        <?php endif; ?>
        <a href="<?php echo esc_url($quote_url); ?>" class="mobile-bar__btn mobile-bar__btn--secondary">
            <?php echo ocd_icon('mail'); ?>
            <?php esc_html_e('Get a quote', 'dentelectrical'); ?>
        </a>
    </div>
</div>
