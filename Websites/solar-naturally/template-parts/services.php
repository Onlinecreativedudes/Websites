<?php
/**
 * Services — six-card grid of what we install.
 * ACF tab: Services
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow = get_field('services_eyebrow') ?: 'What we install';
$heading = get_field('services_heading') ?: 'Complete home and business energy systems';
$lede    = get_field('services_lede') ?: 'Designed and fitted by one accountable team — from the first panel to the final commissioning.';
$cards   = get_field('services_cards');

$defaults = [
    ['icon' => 'sun',              'title' => 'Solar Panel Systems',      'text' => 'Properly sized systems matched to your roof and your real usage, not a one-size template.', 'default_img' => 'gallery/residential/riverway.jpg', 'style' => 'scene'],
    ['icon' => 'battery-charging', 'title' => 'Home Battery Storage',     'text' => 'Store the power you generate and use it at night, when grid rates hit hardest.',            'default_img' => 'product/front-a.png',              'style' => 'product'],
    ['icon' => 'plug-zap',         'title' => 'Solar + Battery Packages', 'text' => 'A complete setup that cuts your reliance on the grid from day one.',                        'default_img' => 'scenario/home-interior.jpg',       'style' => 'scene'],
    ['icon' => 'refresh-cw',       'title' => 'Inverter Upgrades',        'text' => 'Failing or ageing inverter? We replace it and bring your system back to full output.',      'default_img' => 'product/right-c.png',              'style' => 'product'],
    ['icon' => 'activity',         'title' => 'System Health Checks',     'text' => 'Older system underperforming? We find out why and tell you straight.',                      'default_img' => 'gallery/residential/west-coast.jpg', 'style' => 'scene'],
    ['icon' => 'car-front',        'title' => 'EV Charger Add-On',        'text' => 'Charging an electric vehicle? We can add a charger to your install.',                       'default_img' => 'scenario/home-exterior.jpg',       'style' => 'scene'],
];
?>

<section class="section sv" id="services">
    <div class="wrap">
        <div class="sv__head">
            <div class="eyebrow-row" data-rise="rise"><span class="tick"></span><span class="eyebrow"><?php echo esc_html($eyebrow); ?></span></div>
            <h2 class="sv__h2" data-rise="rise" style="animation-delay:80ms"><?php echo esc_html($heading); ?></h2>
            <p class="lede sv__p" data-rise="rise" style="animation-delay:160ms"><?php echo esc_html($lede); ?></p>
        </div>
        <div class="sv__grid">
            <?php if ($cards) : ?>
                <?php foreach ($cards as $i => $c) : ?>
                    <div class="svc" data-rise="rise" style="animation-delay:<?php echo (int) (($i % 3) * 90); ?>ms">
                        <div class="svc__media svc__media--<?php echo esc_attr($c['image_style'] ?: 'scene'); ?>">
                            <?php echo sn_render_image($c['image'], 'sn-card', '', ['loading' => 'lazy']); ?>
                        </div>
                        <div class="svc__body">
                            <span class="svc__ico"><?php echo sn_icon($c['icon'] ?: 'sun', 22); ?></span>
                            <h3 class="svc__t"><?php echo esc_html($c['title']); ?></h3>
                            <p class="svc__d"><?php echo esc_html($c['text']); ?></p>
                            <button class="svc__link js-open-assess">Ask about this <span class="arw">&rarr;</span></button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <?php foreach ($defaults as $i => $c) : ?>
                    <div class="svc" data-rise="rise" style="animation-delay:<?php echo (int) (($i % 3) * 90); ?>ms">
                        <div class="svc__media svc__media--<?php echo esc_attr($c['style']); ?>">
                            <img src="<?php echo esc_url(SN_THEME_URI . '/assets/img/' . $c['default_img']); ?>" alt="<?php echo esc_attr($c['title']); ?>" loading="lazy" width="900" height="680">
                        </div>
                        <div class="svc__body">
                            <span class="svc__ico"><?php echo sn_icon($c['icon'], 22); ?></span>
                            <h3 class="svc__t"><?php echo esc_html($c['title']); ?></h3>
                            <p class="svc__d"><?php echo esc_html($c['text']); ?></p>
                            <button class="svc__link js-open-assess">Ask about this <span class="arw">&rarr;</span></button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
