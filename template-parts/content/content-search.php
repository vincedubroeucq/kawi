<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Kawi
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-wrapper">

		<header class="post-header">
			<?php kawi_post_thumbnail(); ?>
			<div class="post-details">
				<?php if ( 'post' === get_post_type() && ( '' !== get_theme_mod( 'kawi_post_meta_display', 'date' ) ) ) : ?>
					<div class="post-meta">
						<?php kawi_post_meta(); ?>
					</div><!-- .post-meta -->
				<?php endif; ?>
				<?php the_title( '<h2 class="post-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
			</div>
		</header><!-- .post-header -->

		<div class="post-content">
			<?php 
				the_excerpt(); 
				kawi_read_more_link();
			?>
		</div><!-- .post-content -->

		<footer class="post-footer">
			<?php kawi_post_footer(); ?>
		</footer><!-- .post-footer -->

	</div><!-- .post-wrapper -->
</article><!-- #post-<?php the_ID(); ?> -->
