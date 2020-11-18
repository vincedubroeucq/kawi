<?php
/**
 * The footer widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Kawi
 */

if ( ! is_active_sidebar( 'sidebar-2' ) ) {
	return;
}
?>

<aside class="widget-area footer-widget-area" role="complementary">
    <?php do_action( 'kawi_before_sidebar', 'sidebar-2' ) ?>
	<div class="wrapper widgets-wrapper footer-widgets-wrapper">
		<?php dynamic_sidebar( 'sidebar-2' ); ?>
	</div>
	<?php do_action( 'kawi_after_sidebar', 'sidebar-2' ) ?>
</aside><!-- #secondary -->
