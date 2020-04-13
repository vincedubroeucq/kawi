<?php
/**
 * The main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Kawi
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area" role="complementary">
    <?php do_action( 'kawi_before_sidebar' ) ?>
	<div class="wrapper widgets-wrapper">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</div>
	<?php do_action( 'kawi_after_sidebar' ) ?>
</aside><!-- #secondary -->
