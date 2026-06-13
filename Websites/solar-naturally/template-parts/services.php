<?php
/**
 * Services — six-card grid of what we install.
 * ACF tab: Services
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow = get_field('services_eyebrow') ?: 'What we install';
$heading = get_field('services_heading') ?: 'Complete home and business energy systems';
$lede    = get_field('services_lede') ?: 'Designed and fitted by one accountable team — from the first panel to the final commissioning.';

$defaults = [
    ['icon' => 'sun',              'title' => 'Solar Panel Systems',      'text' => 'Properly sized systems matched to your roof and your real usage, not a one-size template.', 'image_style' => 'scene'],
    ['icon' => 'battery-charging', 'title' => 'Home Battery Storage',     'text' => 'Store the power you generate and use it at night, when grid rates hit hardest.',            'image_style' => 'product'],
    ['icon' => 'plug-zap',         'title' => 'Solar + Battery Packages', 'text' => 'A complete setup that cuts your reliance on the grid from day one.',                        'image_style' => 'scene'],
    ['icon' => 'refresh-cw',       'title' => 'Inverter Upgrades',        'text' => 'Failing or ageing inverter? We replace it and bring your system back to full output.',      'image_style' => 'product'],
    ['icon' => 'activity',         'title' => 'System Health Checks',     'text' => 'Older system underperforming? We find out why and tell you straight.',                      'image_style' => 'scene'],
    ['icon' => 'car-front',        'title' => 'EV Charger Add-On',        'text' => 'Charging an electric vehicle? We can add a charger to your install.',                       'image_style' => 'scene'],
];

// Positional fallback photos, used for any card without its own ACF image so
// the section keeps its imagery even once the text fields are populated.
$fallback_imgs = [
    'gallery/residential/riverway.jpg',
    'product/front-a.png',
    'scenario/home-interior.jpg',
    'product/right-c.png',
    'gallery/residential/west-coast.jpg',
    'scenario/home-exterior.jpg',
];

$cards = get_field('services_cards') ?: $defaults;
?>

<section class="section sv" id="services">
    <div class="wrap">
        <div class="sv__head">
            <div class="eyebrow-row" data-rise="rise"><span class="tick"></span><span class="eyebrow"><?php echo esc_html($eyebrow); ?></span></div>
            <h2 class="sv__h2" data-rise="rise" style="animation-delay:80ms"><?php echo esc_html($heading); ?></h2>
            <p class="lede sv__p" data-rise="rise" style="animation-delay:160ms"><?php echo esc_html($lede); ?></p>
        </div>
        <div class="sv__grid">
            <?php foreach ($cards as $i => $c) :
                $style = $c['image_style'] ?? 'scene';
                $title = $c['title'] ?? '';
            ?>
                <div class="svc" data-rise="rise" style="animation-delay:<?php echo (int) (($i % 3) * 90); ?>ms">
                    <div class="svc__media svc__media--<?php echo esc_attr($style); ?>">
                        <?php if (!empty($c['image']['ID'])) : ?>
                            <?php echo sn_render_image($c['image'], 'sn-card', '', ['loading' => 'lazy']); ?>
                        <?php else :
                            $rel = $fallback_imgs[$i % count($fallback_imgs)];
                        ?>
                            <img src="<?php echo esc_url(SN_THEME_URI . '/assets/img/' . $rel); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy" width="900" height="680">
                        <?php endif; ?>
                    </div>
                    <div class="svc__body">
                        <span class="svc__ico"><?php echo sn_icon($c['icon'] ?: 'sun', 22); ?></span>
                        <h3 class="svc__t"><?php echo esc_html($title); ?></h3>
                        <p class="svc__d"><?php echo esc_html($c['text'] ?? ''); ?></p>
                        <button class="svc__link js-open-assess">Ask about this <span class="arw">&rarr;</span></button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
