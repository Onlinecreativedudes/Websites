<?php
if (!defined('ABSPATH')) { exit; }

$logo       = pcp_field('site_logo', '', 'option');
$phone_disp = pcp_phone_display();
$phone_tel  = pcp_phone_tel();
$quote_url  = pcp_field('nav_quote_url', home_url('/contact/'), 'option');

// Logo markup (Site Options image, falling back to the bundled brand asset).
$logo_html = pcp_image(
    $logo, 'medium', 'logo-img', ['loading' => 'eager'],
    'assets/brand/logo-full.png', get_bloginfo('name')
);

// Helper: list CPT entries for a nav dropdown (static fallback if none yet).
$nav_cpt = function ($post_type, array $fallback) {
    $items = [];
    if (post_type_exists($post_type)) {
        $q = get_posts([
            'post_type'      => $post_type,
            'posts_per_page' => 12,
            'orderby'        => 'menu_order title',
            'order'          => 'ASC',
        ]);
        foreach ($q as $p) {
            $items[] = ['title' => get_the_title($p), 'url' => get_permalink($p)];
        }
    }
    if (!$items) {
        foreach ($fallback as $label) {
            $items[] = ['title' => $label, 'url' => '#'];
        }
    }
    return $items;
};

$services_url   = pcp_archive_url('service', '/services/');
$commercial_url = pcp_archive_url('commercial', '/commercial/');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main">Skip to content</a>

<header class="head" id="head">
  <div class="wrap">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="logo" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?> home" rel="home"><?php echo $logo_html; ?></a>
    <nav class="main" aria-label="Primary">
      <ul>
        <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
        <li><a href="<?php echo esc_url(home_url('/why-us/')); ?>">About Us <?php echo pcp_icon('caret'); ?></a>
          <div class="drop">
            <a href="<?php echo esc_url(home_url('/why-us/')); ?>">Why Choose Us</a>
            <a href="<?php echo esc_url(home_url('/why-us/#guarantee')); ?>">Code of Conduct &amp; Guarantee</a>
            <a href="<?php echo esc_url(home_url('/#faqs')); ?>">FAQs</a>
            <a href="<?php echo esc_url(home_url('/#reviews')); ?>">Testimonials</a>
            <a href="<?php echo esc_url(home_url('/blog/')); ?>">Blog</a>
          </div>
        </li>
        <li><a href="<?php echo esc_url($services_url); ?>">Pressure Cleaning <?php echo pcp_icon('caret'); ?></a>
          <div class="drop mega">
            <div class="mega-grid">
              <div>
                <h5>Surfaces &amp; Areas</h5>
                <?php foreach (array_slice($nav_cpt('service', ['Roof Cleaning','House &amp; Building Washdowns','Driveway Cleaning','Fascias &amp; Eaves Cleaning','Poolside &amp; Patio','Tile Cleaning']), 0, 6) as $it) : ?>
                  <a href="<?php echo esc_url($it['url']); ?>"><?php echo esc_html(wp_strip_all_tags($it['title'])); ?></a>
                <?php endforeach; ?>
              </div>
              <div>
                <h5>More Services</h5>
                <?php foreach (array_slice($nav_cpt('service', ['Tennis &amp; Sport Courts','Colorbond Fence Wash','Limestone Cleaning','Bore Stain Removal','Calcium Removal','Graffiti Removal']), 6, 6) as $it) : ?>
                  <a href="<?php echo esc_url($it['url']); ?>"><?php echo esc_html(wp_strip_all_tags($it['title'])); ?></a>
                <?php endforeach; ?>
              </div>
              <div>
                <h5>Methods</h5>
                <a href="<?php echo esc_url($services_url); ?>">Soft Wash</a>
                <a href="<?php echo esc_url($services_url); ?>">Vacuum Recovery</a>
                <a href="<?php echo esc_url(home_url('/#sealing')); ?>">Sealing</a>
              </div>
            </div>
            <div class="mega-foot">
              <span>Not sure what your property needs? We will tell you honestly.</span>
              <a href="<?php echo esc_url($quote_url); ?>" class="btn btn-green" style="padding:11px 20px;font-size:.86rem">Get a Free Quote</a>
            </div>
          </div>
        </li>
        <li><a href="<?php echo esc_url($commercial_url); ?>">Commercial <?php echo pcp_icon('caret'); ?></a>
          <div class="drop">
            <?php foreach ($nav_cpt('commercial', ['Property &amp; Strata','Healthcare &amp; Hospitals','Hotels &amp; Complexes','Schools','Shopping Centres','Cool Room Cleaning','Factory Floor Cleaning']) as $it) : ?>
              <a href="<?php echo esc_url($it['url']); ?>"><?php echo esc_html(wp_strip_all_tags($it['title'])); ?></a>
            <?php endforeach; ?>
          </div>
        </li>
        <li><a href="<?php echo esc_url(home_url('/#sealing')); ?>">Sealing <?php echo pcp_icon('caret'); ?></a>
          <div class="drop">
            <a href="<?php echo esc_url($services_url); ?>">Concrete Sealing</a>
            <a href="<?php echo esc_url($services_url); ?>">Paver Sealing</a>
            <a href="<?php echo esc_url($services_url); ?>">Limestone Sealing</a>
          </div>
        </li>
        <li><a href="<?php echo esc_url(home_url('/gallery/')); ?>">Portfolio</a></li>
        <li><a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a></li>
      </ul>
      <a href="<?php echo esc_url($quote_url); ?>" class="btn btn-green cta-top">Get a Free Quote</a>
    </nav>
    <button class="burger" id="burger" aria-label="Open menu"><span></span><span></span><span></span></button>
  </div>
</header>

<div class="drawer" id="drawer">
  <div class="drawer-panel">
    <button class="x" id="closeDrawer" aria-label="Close menu">&times;</button>
    <a href="<?php echo esc_url(home_url('/')); ?>" style="border:0;padding-top:0"><?php echo pcp_image($logo, 'medium', '', ['loading'=>'eager','height'=>48], 'assets/brand/logo-full.png', get_bloginfo('name')); ?></a>
    <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
    <a href="<?php echo esc_url(home_url('/why-us/')); ?>">About Us</a>
    <a href="<?php echo esc_url($services_url); ?>">Pressure Cleaning</a>
    <a href="<?php echo esc_url($commercial_url); ?>">Commercial</a>
    <a href="<?php echo esc_url(home_url('/#sealing')); ?>">Sealing</a>
    <a href="<?php echo esc_url(home_url('/gallery/')); ?>">Portfolio</a>
    <a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a>
    <h4>Surfaces</h4>
    <?php foreach (array_slice($nav_cpt('service', ['Roof Cleaning','Driveway Cleaning','House Washing','Soft Wash']), 0, 4) as $it) : ?>
      <a href="<?php echo esc_url($it['url']); ?>"><?php echo esc_html(wp_strip_all_tags($it['title'])); ?></a>
    <?php endforeach; ?>
    <a href="<?php echo esc_url($quote_url); ?>" class="btn btn-green">Get a Free Quote</a>
    <a href="<?php echo esc_url($phone_tel); ?>" class="btn btn-blue" style="width:100%;justify-content:center;margin-top:12px">Call <?php echo esc_html($phone_disp); ?></a>
  </div>
</div>

<main id="main">
