<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Kawi
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-wrapper">

		<?php if( ! is_single() ): ?>
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
		<?php endif; ?>

		<div class="post-content">
			<?php kawi_post_content(); ?>
			<?php if( is_single() ) {
				wp_link_pages( array(
					'before' => '<nav class="page-links">' . esc_html__( 'Pages:', 'kawi' ),
					'after'  => '</nav>',
				) );
			} ?>
		</div><!-- .post-content -->

		<footer class="post-footer">
			<?php kawi_post_footer(); ?>
		</footer><!-- .post-footer -->

	</div>
</article><!-- #post-<?php the_ID(); ?> -->
