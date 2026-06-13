<?php
/**
 * About / founder split with floating badges, plus the Meet the Team grid.
 * ACF tabs: About, Team (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$kicker      = get_field('about_kicker');
$headline    = get_field('about_headline');
$body        = get_field('about_body');
$credentials = get_field('about_credentials');
$cta_label   = get_field('about_cta_label');
$portrait    = get_field('about_portrait');
$stat_number = get_field('about_stat_number');
$stat_label  = get_field('about_stat_label');
$badge_name  = get_field('about_badge_name');
$badge_role  = get_field('about_badge_role');

$team_headline = get_field('team_headline');
$team_members  = get_field('team_members');

if (!$headline) { return; }
?>

<section class="about section" id="about">
    <div class="container container--wide">
        <div class="about__split">
            <div class="about__content">
                <?php ocd_kicker($kicker); ?>
                <h2 class="section__headline"><?php echo ocd_kses_headline($headline); ?></h2>

                <?php if ($body) : ?>
                    <div class="about__body"><?php echo wp_kses_post($body); ?></div>
                <?php endif; ?>

                <?php if ($credentials) : ?>
                    <div class="about__credentials">
                        <?php foreach ($credentials as $cred) : if (empty($cred['label'])) { continue; } ?>
                            <div class="about__credential">
                                <?php echo ocd_tick(); ?>
                                <span><?php echo esc_html($cred['label']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="about__actions">
                    <?php ocd_book_cta($cta_label, 'btn btn--dark'); ?>
                    <?php ocd_phone_link('phone-link'); ?>
                </div>
            </div>

            <?php if ($portrait && !empty($portrait['ID'])) : ?>
                <div class="about__media">
                    <div class="about__portrait">
                        <?php echo ocd_render_image($portrait, 'portrait', 'about__portrait-img'); ?>
                    </div>
                    <?php if ($stat_number) : ?>
                        <div class="about__stat">
                            <div class="about__stat-number"><?php echo esc_html($stat_number); ?></div>
                            <?php if ($stat_label) : ?>
                                <div class="about__stat-label"><?php echo esc_html($stat_label); ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($badge_name) : ?>
                        <div class="about__badge">
                            <div class="about__badge-name"><?php echo esc_html($badge_name); ?></div>
                            <?php if ($badge_role) : ?>
                                <div class="about__badge-role"><?php echo esc_html($badge_role); ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($team_members) : ?>
            <div class="team">
                <?php if ($team_headline) : ?>
                    <h3 class="team__headline"><?php echo ocd_kses_headline($team_headline); ?></h3>
                <?php endif; ?>
                <div class="team__grid" data-stagger>
                    <?php foreach ($team_members as $member) : ?>
                        <figure class="team__member">
                            <?php if (!empty($member['photo']['ID'])) : ?>
                                <div class="team__photo">
                                    <?php echo ocd_render_image($member['photo'], 'portrait', 'team__photo-img'); ?>
                                </div>
                            <?php endif; ?>
                            <figcaption>
                                <div class="team__name"><?php echo esc_html($member['name']); ?></div>
                                <?php if (!empty($member['role'])) : ?>
                                    <div class="team__role"><?php echo esc_html($member['role']); ?></div>
                                <?php endif; ?>
                            </figcaption>
                        </figure>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
