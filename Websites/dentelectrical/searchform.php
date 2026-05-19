<?php if (!defined('ABSPATH')) { exit; } ?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label for="search-field" class="screen-reader-text"><?php esc_html_e('Search for:', 'dentelectrical'); ?></label>
    <input type="search" id="search-field" class="search-form__field" placeholder="<?php esc_attr_e('Search…', 'dentelectrical'); ?>" value="<?php echo esc_attr(get_search_query()); ?>" name="s">
    <button type="submit" class="search-form__submit btn btn--primary"><?php esc_html_e('Search', 'dentelectrical'); ?></button>
</form>
