<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Kawi
 */

$layout = get_theme_mod( 'kawi_blog_posts_layout', 'posts-list' );
get_header();
?>

	<main id="main" class="main-content" role="main">
		<div id="content-wrapper" class="wrapper content-wrapper <?php echo esc_attr( $layout ); ?>">

		<?php 
		if ( have_posts() ) : 
			
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content/content', get_post_type() );

			endwhile;

		else :

			get_template_part( 'template-parts/content/content', 'none' );

		endif;
		?>

		</div><!-- .wrapper -->
		<?php kawi_the_posts_navigation(); ?>
	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
