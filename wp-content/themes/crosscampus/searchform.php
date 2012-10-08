<?php
/**
 * The template for displaying search forms in ydn
 *
 * @package ydn
 * @since ydn 1.0
 */
?>
	<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
    <div class="input-append">
      <label for="s" class="assistive-text"><?php _e( 'Search', 'ydn' ); ?></label>
      <input type="text" class="input span4" name="s" id="s" /><button type="submit" class="btn" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'ydn' ); ?>" />Search</button>
    </div>
	</form>
