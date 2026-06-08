<?php
if (!defined('ABSPATH')) { exit; }
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
  <label class="screen-reader-text" for="s">Search</label>
  <input type="search" id="s" name="s" placeholder="Search&hellip;" value="<?php echo esc_attr(get_search_query()); ?>">
  <button type="submit" class="btn btn-green">Search</button>
</form>
