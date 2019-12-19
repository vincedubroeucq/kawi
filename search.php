<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Kawi
 */


get_header();
?>

	<main id="main" class="main-content" role="main">
		<div id="content-wrapper" class="wrapper content-wrapper">
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

					kawi_the_posts_navigation( 'page-numbers' );

				else :

					get_template_part( 'template-parts/content/content', 'none' );

				endif; 
			?>
		</div><!-- .content-wrapper -->
	</main><!-- #main -->

<?php
get_sidebar();
get_footer();