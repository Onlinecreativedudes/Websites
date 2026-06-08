<?php
if (!defined('ABSPATH')) { exit; }

$logo        = pcp_field('site_logo', '', 'option');
$footer_blurb = pcp_field('footer_blurb', "A family-owned Perth business providing specialist pressure washing and soft wash services to residential, strata and commercial properties across the Perth metro area. Fully insured. 5-star rated. Perth's pressure cleaning specialists.", 'option');
$facebook    = pcp_field('facebook_url', 'https://www.facebook.com/pressurecleaningperth/', 'option');
$youtube     = pcp_field('youtube_url', '', 'option');
$copyright   = pcp_field('footer_copyright', '', 'option');

// Footer link columns — static design defaults, overridden by assigned menus.
$col_services = [
    'Driveway Cleaning', 'Roof Cleaning', 'House Washing', 'Patio &amp; Paver', 'Soft Wash', 'Bore Stain Removal',
];
$col_commercial = [
    'Property &amp; Strata', 'Hospitals &amp; Schools', 'Shopping Centres', 'Factory Floors', 'Concrete Sealing', 'Vacuum Recovery',
];
?>
</main>

<footer>
  <div class="wrap">
    <div class="foot-grid">
      <div>
        <div class="logo"><?php echo pcp_image($logo, 'medium', '', [], 'assets/brand/logo-full.png', get_bloginfo('name')); ?></div>
        <?php if ($footer_blurb) : ?><p class="foot-blurb"><?php echo esc_html($footer_blurb); ?></p><?php endif; ?>
        <div class="foot-soc">
          <?php if ($facebook) : ?><a href="<?php echo esc_url($facebook); ?>" aria-label="Facebook" target="_blank" rel="noopener"><?php echo pcp_icon('facebook'); ?></a><?php endif; ?>
          <?php if ($youtube) : ?><a href="<?php echo esc_url($youtube); ?>" aria-label="YouTube" target="_blank" rel="noopener"><?php echo pcp_icon('youtube'); ?></a><?php endif; ?>
        </div>
      </div>

      <div>
        <h4>Pressure Cleaning</h4>
        <?php if (has_nav_menu('footer_services')) : ?>
          <?php wp_nav_menu(['theme_location' => 'footer_services', 'container' => 'ul', 'menu_class' => '', 'depth' => 1, 'fallback_cb' => false]); ?>
        <?php else : ?>
          <ul>
            <?php foreach ($col_services as $l) : ?><li><a href="<?php echo esc_url(pcp_archive_url('service', '/services/')); ?>"><?php echo esc_html(wp_strip_all_tags($l)); ?></a></li><?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>

      <div>
        <h4>Commercial &amp; Sealing</h4>
        <?php if (has_nav_menu('footer_commercial')) : ?>
          <?php wp_nav_menu(['theme_location' => 'footer_commercial', 'container' => 'ul', 'menu_class' => '', 'depth' => 1, 'fallback_cb' => false]); ?>
        <?php else : ?>
          <ul>
            <?php foreach ($col_commercial as $l) : ?><li><a href="<?php echo esc_url(pcp_archive_url('commercial', '/commercial/')); ?>"><?php echo esc_html(wp_strip_all_tags($l)); ?></a></li><?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>

      <div>
        <h4>Company</h4>
        <?php if (has_nav_menu('footer_company')) : ?>
          <?php wp_nav_menu(['theme_location' => 'footer_company', 'container' => 'ul', 'menu_class' => '', 'depth' => 1, 'fallback_cb' => false]); ?>
        <?php else : ?>
          <ul>
            <li><a href="<?php echo esc_url(home_url('/why-us/')); ?>">About Us</a></li>
            <li><a href="<?php echo esc_url(home_url('/why-us/')); ?>">Why Choose Us</a></li>
            <li><a href="<?php echo esc_url(home_url('/why-us/#guarantee')); ?>">Our Guarantee</a></li>
            <li><a href="<?php echo esc_url(home_url('/#faqs')); ?>">FAQs</a></li>
            <li><a href="<?php echo esc_url(home_url('/gallery/')); ?>">Portfolio</a></li>
            <li><a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a></li>
          </ul>
        <?php endif; ?>
      </div>
    </div>

    <div class="foot-bot">
      <span>&copy; <span id="yr"></span> <?php echo $copyright ? esc_html($copyright) : esc_html(get_bloginfo('name')) . '. All rights reserved.'; ?></span>
      <span style="display:flex;gap:20px;flex-wrap:wrap">
        <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Privacy Policy</a>
        <a href="<?php echo esc_url(home_url('/terms-of-use/')); ?>">Terms of Use</a>
        <a href="<?php echo esc_url(home_url('/sitemap_index.xml')); ?>">Sitemap</a>
      </span>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
