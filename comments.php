<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Kawi
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">
	<div class="wrapper comments-wrapper">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
				$kawi_comment_count = get_comments_number();
				if ( '1' === $kawi_comment_count ) {
					printf(
						/* translators: 1: title. */
						esc_html__( 'One comment on &ldquo;%1$s&rdquo;', 'kawi' ),
						'<span>' . get_the_title() . '</span>'
					);
				} else {
					printf( // WPCS: XSS OK.
						/* translators: 1: comment count number, 2: title. */
						esc_html( _nx( '%1$s comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', $kawi_comment_count, 'comments title', 'kawi' ) ),
						number_format_i18n( $kawi_comment_count ),
						'<span>' . get_the_title() . '</span>'
					);
				}
			?>
		</h2><!-- .comments-title -->

		<?php kawi_the_comments_navigation(); ?>

		<ul class="comment-list">
			<?php wp_list_comments( array( 'short_ping' => true, 'callback' => 'kawi_comment_callback', 'avatar_size' => 96 ) ); ?>
		</ul><!-- .comment-list -->

		<?php kawi_the_comments_navigation();

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) : ?>
			<p class="no-comments"><strong><?php esc_html_e( 'Comments are closed.', 'kawi' ); ?></strong></p>
		<?php endif;

	endif;
	comment_form(); 
	?>
	</div>
</div><!-- #comments -->
