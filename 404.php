<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Kawi
 */

get_header();
?>

	<main id="main" class="main-content" role="main">
		<div id="content-wrapper" class="wrapper content-wrapper">
			<section class="error-404 not-found">
				<h2 class="section-title"><?php esc_html_e( 'That page can&rsquo;t be found !', 'kawi' ); ?></h2>
				<div class="page-content">
					<p><?php esc_html_e( 'It looks like nothing was found at this location. Try typing in what you were looking for in the form below.', 'kawi' ); ?></p>
					<?php get_search_form(); ?>
				</div><!-- .page-content -->
			</section><!-- .error-404 -->
		</div><!-- .wrapper -->
	</main><!-- #main -->

<?php
get_footer();
