<?php if (!defined('ABSPATH')) { exit; } ?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label for="search-field" class="screen-reader-text"><?php esc_html_e('Search for:', 'woodycraftwork'); ?></label>
    <input type="search" id="search-field" class="search-form__field" placeholder="<?php esc_attr_e('Search…', 'woodycraftwork'); ?>" value="<?php echo esc_attr(get_search_query()); ?>" name="s">
    <button type="submit" class="btn search-form__submit"><?php esc_html_e('Search', 'woodycraftwork'); ?></button>
</form>
