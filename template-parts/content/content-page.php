<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Kawi
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-wrapper">

		<div class="post-content">
			<?php 
				the_content();

				wp_link_pages( array(
					'before' => '<nav class="page-links">' . esc_html__( 'Pages:', 'kawi' ),
					'after'  => '</nav>',
				) );
			?>
		</div><!-- .post-content -->

		<?php if ( get_edit_post_link() ) : ?>
			<footer class="entry-footer">
				<?php kawi_post_footer(); ?>
			</footer><!-- .entry-footer -->
		<?php endif; ?>
		
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
