<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Kawi
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="wrapper footer-wrapper">
			<div class="site-info">
				<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'kawi' ) ); ?>">
					<?php
						/* translators: %s: CMS name, i.e. WordPress. */
						printf( esc_html__( 'Proudly powered by %s', 'kawi' ), 'WordPress' );
					?>
				</a>
				<span class="sep"> | </span>
				<span>
					<?php
						/* translators: 1: Theme name, 2: Theme author. */
						printf( esc_html__( 'Theme: %1$s by %2$s.', 'kawi' ), 'Kawi', '<a href="https://vincentdubroeucq.com">Vincent Dubroeucq</a>' );
					?>
				</span>
			</div><!-- .site-info -->
			<nav class="footer-menu-section" role="navigation" aria-label="<?php esc_attr_e( 'Footer navigation', 'kawi' ); ?>">
				<?php
					if ( has_nav_menu( 'footer-menu' ) ) {
						wp_nav_menu( array(
							'theme_location' => 'footer-menu',
							'menu_id'        => 'footer-menu',
							'menu_class'     => 'menu footer-menu',
							'container'      => 'ul',
							'depth'          => 1,
							'link_before'    => '<span>',
							'link_after'     => '</span>',
							) );
						}
						
					if ( has_nav_menu( 'footer-social-menu' ) ) {
						wp_nav_menu( array(
							'theme_location' => 'footer-social-menu',
							'menu_id'        => 'footer-social-menu',
							'menu_class'     => 'menu social-menu footer-social-menu',
							'container'      => 'ul',
							'depth'          => 1,
							'link_before'    => '<span>',
							'link_after'     => '</span>',
						) );
					}
				?>
			</nav>
		</div><!-- .footer-wrapper -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
