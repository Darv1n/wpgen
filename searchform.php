<?php 
/**
 *
 * @package wpgen
 */

 if ( !defined( 'ABSPATH' ) ) exit;
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text">Найти:</span>
		<input type="search" class="search-field" placeholder="<?php _e( 'Search...', 'wpgen' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php _e( 'Search for', 'wpgen' ) ?>" />
	</label>
	<button type="submit" value="<?php _e( 'Search', 'wpgen' ) ?>" class="search-submit">
		<span class="search-submit__icon"></span>
	</button>
</form>