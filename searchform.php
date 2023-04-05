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

<form class="search-form" method="get" role="search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="search-form__label" for="s">
		<input class="search-form__field" name="s" type="search" placeholder="<?php _e( 'Search...', 'wpgen' ); ?>" value="<?php echo get_search_query(); ?>" title="<?php _e( 'Search for', 'wpgen' ); ?>" />
	</label>
	<button <?php button_classes( 'search-form__submit icon icon_center icon_magnifying-glass' ); ?> type="submit" value="<?php _e( 'Search', 'wpgen' ); ?>"></button>
</form>