<?php
/**
 * Template footer top bar
 *
 * @package wpgen
 */

?>

<div <?php wpgen_archive_page_columns_wrapper_classes( 'align-items-center' ); ?>>
	<div class="col-12 col-md-6 footer-column"><?php dynamic_sidebar( 'sidebar-footer-bottom-left' ); ?></div>
	<div class="col-12 col-md-6 footer-column"><?php dynamic_sidebar( 'sidebar-footer-bottom-right' ); ?></div>
</div>
