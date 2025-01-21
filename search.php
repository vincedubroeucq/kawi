<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
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

					/* Start the Loop */
					while ( have_posts() ) : the_post();

						/**
						 * Run the loop for the search to output the results.
						 * If you want to overload this in a child theme then include a file
						 * called content-search.php and that will be used instead.
						 */
						get_template_part( 'template-parts/content/content', 'search' );

					endwhile;

				else :

					get_template_part( 'template-parts/content/content', 'none' );

				endif; 
			?>
		</div><!-- .content-wrapper -->
		<?php kawi_the_posts_navigation( 'page-numbers' ); ?>
	</main><!-- #main -->

<?php
get_sidebar();
get_footer();