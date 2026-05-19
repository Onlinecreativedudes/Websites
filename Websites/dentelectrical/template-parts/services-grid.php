<?php
/**
 * Services grid
 * ACF group: group_services_landing
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow      = get_field('services_eyebrow');
$headline     = get_field('services_headline');
$intro        = get_field('services_intro');
$services     = get_field('services_items');
$contact_anchor = get_field('contact_anchor_url') ?: '#contact';

if (!$services || !is_array($services)) { return; }
?>

<section class="section services" id="services">
    <div class="container">
        <div class="section__header reveal">
            <div class="section__header-lead">
                <?php if ($eyebrow) : ?>
                    <span class="eyebrow"><?php echo esc_html($eyebrow); ?></span>
                <?php endif; ?>
                <?php if ($headline) : ?>
                    <h2 class="display-l"><?php echo wp_kses_post($headline); ?></h2>
                <?php endif; ?>
            </div>
            <?php if ($intro) : ?>
                <div class="section__header-aside">
                    <p><?php echo esc_html($intro); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <div class="services__grid">
            <?php foreach ($services as $i => $service) :
                $title    = $service['title'] ?? '';
                $copy     = $service['copy'] ?? '';
                $tag      = $service['tag'] ?? '';
                $tag_tone = $service['tag_tone'] ?? 'yellow';
                $image    = $service['image'] ?? null;
                $cta_text = $service['cta_text'] ?? 'Get a quote';
                $delay    = ($i % 3);
                if (!$title) { continue; }
            ?>
                <article class="service-card reveal <?php echo $delay ? 'reveal--delay-' . (int) $delay : ''; ?>">
                    <div class="service-card__image">
                        <?php if ($tag) : ?>
                            <span class="service-card__tag service-card__tag--<?php echo esc_attr($tag_tone); ?>"><?php echo esc_html($tag); ?></span>
                        <?php endif; ?>
                        <?php if ($image && !empty($image['ID'])) : ?>
                            <?php echo wp_get_attachment_image($image['ID'], 'card', false, [
                                'class' => 'service-card__img',
                                'alt'   => esc_attr($image['alt'] ?? ''),
                            ]); ?>
                        <?php endif; ?>
                    </div>
                    <div class="service-card__body">
                        <h3 class="service-card__title"><?php echo esc_html($title); ?></h3>
                        <?php if ($copy) : ?>
                            <p class="service-card__copy"><?php echo esc_html($copy); ?></p>
                        <?php endif; ?>
                        <a href="<?php echo esc_url($contact_anchor); ?>" class="service-card__link">
                            <?php echo esc_html($cta_text); ?>
                            <?php echo ocd_icon('arrow'); ?>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
