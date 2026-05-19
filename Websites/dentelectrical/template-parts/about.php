<?php
/**
 * About founder
 * ACF group: group_about_landing
 */
if (!defined('ABSPATH')) { exit; }

$photo       = get_field('about_photo');
$badge       = get_field('about_photo_badge');
$eyebrow     = get_field('about_eyebrow');
$headline    = get_field('about_headline');
$copy        = get_field('about_copy');
$sig_name    = get_field('about_sig_name');
$sig_role    = get_field('about_sig_role');
$stats       = get_field('about_stats');

if (!$headline) { return; }
?>

<section class="section about" id="about">
    <div class="container">
        <div class="about__grid">
            <div class="about__photo reveal">
                <?php if ($photo && !empty($photo['ID'])) : ?>
                    <?php echo wp_get_attachment_image($photo['ID'], 'founder', false, [
                        'class' => 'about__photo-img',
                        'alt'   => esc_attr($photo['alt'] ?? ''),
                    ]); ?>
                <?php endif; ?>
                <?php if ($badge) : ?>
                    <span class="about__photo-badge">
                        <span class="hero__dot" aria-hidden="true"></span>
                        <?php echo esc_html($badge); ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="about__content reveal reveal--delay-1">
                <?php if ($eyebrow) : ?>
                    <span class="eyebrow"><?php echo esc_html($eyebrow); ?></span>
                <?php endif; ?>

                <h2 class="about__headline"><?php echo wp_kses_post($headline); ?></h2>

                <?php if ($copy) : ?>
                    <div class="about__copy"><?php echo wp_kses_post($copy); ?></div>
                <?php endif; ?>

                <?php if ($sig_name || $sig_role) : ?>
                    <div class="about__signature">
                        <div>
                            <?php if ($sig_name) : ?>
                                <div class="about__sig-name"><?php echo esc_html($sig_name); ?></div>
                            <?php endif; ?>
                            <?php if ($sig_role) : ?>
                                <div class="about__sig-role"><?php echo esc_html($sig_role); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($stats && is_array($stats)) : ?>
                    <div class="stats">
                        <?php foreach ($stats as $stat) :
                            $num   = $stat['number'] ?? '';
                            $label = $stat['label']  ?? '';
                            $tone  = $stat['tone']   ?? 'default';
                            if (!$num && !$label) { continue; }
                        ?>
                            <div class="stat">
                                <div class="stat__num stat__num--<?php echo esc_attr($tone); ?>"><?php echo wp_kses_post($num); ?></div>
                                <div class="stat__label"><?php echo esc_html($label); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
