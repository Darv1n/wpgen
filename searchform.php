<?php
/**
 * The template for disdisplaying searchform
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php _e( 'Search:', 'wpgen' ); ?></span>
		<input type="search" class="search-field" placeholder="<?php _e( 'Search...', 'wpgen' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php _e( 'Search for', 'wpgen' ); ?>" />
	</label>
	<button <?php button_classes( 'search-submit' ); ?> type="submit" value="<?php _e( 'Search', 'wpgen' ); ?>">
		<i class="icon icon_magnifying-glass"></i>
	</button>
</form>