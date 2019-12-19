<?php
/**
 * Custom template tags for this theme
 *
 * @package Kawi
 */

if ( ! function_exists( 'kawi_site_branding' ) ) :
/**
 * Prints HTML with site logo, title and description in the header.
 */
function kawi_site_branding() {
	?>
		<div class="site-branding">
			<?php the_custom_logo(); ?>
			<div class="site-details">
				<?php if ( is_front_page() ) : ?>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php else : ?>
					<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php endif;
				$kawi_description = get_bloginfo( 'description', 'display' );
				if ( $kawi_description || is_customize_preview() ) : ?>
					<p class="site-description"><?php echo esc_html( $kawi_description ); /* WPCS: xss ok. */ ?></p>
				<?php endif; ?>
			</div>
		</div><!-- .site-branding -->
	<?php
}
endif;


if ( ! function_exists( 'kawi_menu_button' ) ) :
/**
 * Prints <button> tag for menu open/close buttons.
 * 
 * @param  string  $type  Type of menu button to display. Should match svg icon key. 
 */
function kawi_menu_button( $type = 'menu' ) {
	
	$labels = array(
		'menu'  => __( 'Open menu', 'kawi' ),
		'close' => __( 'Close menu', 'kawi' ),
	);

	?>
		<button class="toggle menu-toggle" aria-controls="menu-area" aria-expanded="false">
			<span class="screen-reader-text"><?php echo esc_html( $labels[$type] );?></span>
			<?php kawi_ui_icon( $type ); ?>
		</button>
	<?php

}
endif;


if ( ! function_exists( 'kawi_ui_icon' ) ) :
/**
 * Prints <svg> tag for matching icon.
 * 
 * @param   string  $icon  Name of the icon to display or return  
 * @param   bool    $echo  Whether to echo the <svg> tag or not.  
 * @return  string  $svg   <svg> markup for the icon
 */
function kawi_ui_icon( $icon, $echo = true ) {

	$ui_icons = kawi_get_ui_icons();

	if( array_key_exists( $icon, $ui_icons ) ){
		$svg = apply_filters( 'kawi_ui_icon_svg', $ui_icons[$icon], $icon );
		if( $echo ) {
			echo $svg;
		}
		return $svg;
	}

}
endif;


if ( ! function_exists( 'kawi_get_hero_data' ) ) :
/**
 * Returns information to display in the hero section.
 *
 * @return array  $data     HTML string with meta information for the post. 
 */
function kawi_get_hero_data(){

	$data = array(
		'title' => get_the_title(),
		'meta'  => kawi_post_meta( false ),
	);

	if( is_404() ){
		$data['title'] = esc_html__( 'Oops!', 'kawi' );
		$data['meta']  = false;
	}

	if( is_archive() ) {
		$data['title'] = get_the_archive_title();
		$data['meta']  = strip_tags( get_the_archive_description(), '<span><strong><em><code>' );
	}

	if( is_search() ){
		/* translators: %s: search query. */
		$data['title'] = sprintf( esc_html__( 'Search Results for: %s', 'kawi' ), '<span class="search-term">' . get_search_query() . '</span>' );
		$data['meta']  = false;
	}

	if( is_home() ){
		$data['title'] = single_post_title( '', false );
		$data['meta']  = false;
	}

	if( is_front_page() ){
		$data['title'] = esc_html( get_bloginfo( 'name' ) );
		$data['meta']  = esc_html( get_bloginfo( 'description', 'display' ) );
	}

	return apply_filters( 'kawi_hero_data', $data );
	
}
endif;


if ( ! function_exists( 'kawi_post_meta' ) ) :
/**
 * Returns or prints post meta information, as defined in customizer settings.
 * 
 * @param  bool    $echo     Whether to echo data or just return it. 
 * @return string  $html     HTML string with meta information for the post. 
 */
function kawi_post_meta( $echo = true ){
	
	if( 'post' !== get_post_type() ) {
		return false;
	}

	$posted_on  = kawi_posted_on( false );
	$posted_by  = kawi_posted_by( false );
	$categories = kawi_categories( false );

	$html = $posted_on . $posted_by . $categories; 

	if( $echo ) {
		echo $html;
	}

	return $html;
}
endif;


if ( ! function_exists( 'kawi_posted_on' ) ) :
/**
 * Returns or print HTML with meta information for the current post-date/time.
 * 
 * @param  bool    $echo     Whether to echo data or just return it. 
 * @return string  $html     HTML string with date info for the post. 
 */
function kawi_posted_on( $echo = true ) {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( DATE_W3C ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		/* translators: %s: post date. */
		_x( '<span class="screen-reader-text">Posted on :</span>%s', 'post date', 'kawi' ),
		$time_string
	);

	$html = sprintf(
		'<span class="posted-on post-meta-item">%1$s %2$s</span>',
		kawi_ui_icon( 'date', false ),
		$posted_on
	);

	if( $echo ) {
		echo $html;
	}

	return $html; 

}
endif;


if ( ! function_exists( 'kawi_posted_by' ) ) :
/**
 * Returns or print HTML with meta information for the current author.
 * 
 * @param  bool    $echo     Whether to echo data or just return it. 
 * @return string  $html     HTML string with author info for the post. 
 */
function kawi_posted_by( $echo = true ) {
	
	global $post;
	$author_id   = $post->post_author;
	$author_name = get_the_author_meta( 'display_name', $author_id );

	$byline = sprintf(
		/* translators: %s: post author. */
		_x( '<span class="screen-reader-text">By :</span> %s', 'post author', 'kawi' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( $author_id ) ) . '">' . esc_html( $author_name ) . '</a></span>'
	);
	
	$html = sprintf(
		'<span class="byline post-meta-item">%1$s %2$s</span>',
		kawi_ui_icon( 'author', false ),
		$byline
	);

	if( $echo ) {
		echo $html;
	}

	return $html; 

}
endif;


if ( ! function_exists( 'kawi_categories' ) ) :
/**
 * Returns or print HTML with meta information for the current author.
 * 
 * @param  bool    $echo     Whether to echo data or just return it. 
 * @return string  $html     HTML string with categories for the post.
 */
function kawi_categories( $echo = true ) {
	$categories = sprintf(
		/* translators: %s: Categories list. */		
		/* translators: used between list items, there is a space after the comma */
		_x( '<span class="screen-reader-text">Posted in :</span> %s', 'categories', 'kawi' ),
		get_the_category_list( '<span class="seperator">|</span>' )
	);

	$html = sprintf(
		'<span class="categories post-meta-item">%1$s %2$s</span>',
		kawi_ui_icon( 'categories', false ),
		$categories
	);
	
	if( $echo ) {
		echo $html;
	}

	return $html; 
}
endif;


if ( ! function_exists( 'kawi_tags' ) ) :
/**
 * Prints HTML for the tag list.
 * 
 * @param  bool    $echo     Whether to echo html or just return it. 
 * @return string  $html     HTML string with tags for the post.
 */
function kawi_tags( $echo = true ) {
	$tags = get_the_tag_list( '', ', ' );
	$html = false;
	
	if( $tags && get_theme_mod( 'kawi_meta_show_tags', true ) ){
		$tag_list = sprintf(
			/* translators: %s: Tags list. */		
			/* translators: used between list items, there is a space after the comma */
			_x( '<span class="screen-reader-text">Tags :</span> %s', 'tags', 'kawi' ),
			$tags
		);
		$html = '<span class="tags">' . kawi_ui_icon( 'tags', false ) . '<span class="tag-list">' . $tag_list . '</span></span>'; // WPCS: XSS OK.
	}

	if( $echo ) {
		echo $html;
	}

	return $html;	
}
endif;


if ( ! function_exists( 'kawi_comment_link' ) ) :
/**
 * Prints HTML for the comment link.
 */
function kawi_comment_link() {
	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) && get_theme_mod( 'kawi_meta_show_comment_link', true ) ) {
		echo '<span class="comment-link">';
		kawi_ui_icon( 'comment' );
		comments_popup_link(
			sprintf(
				wp_kses(
					/* translators: %s: post title */
					__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'kawi' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);
		echo '</span>';
	}
}
endif;


if ( ! function_exists( 'kawi_post_footer' ) ) :
/**
 * Displays post footer with meta information for tags, comments and edit link.
 */
function kawi_post_footer() {
	
	// Display meta only on posts.
	if ( 'post' === get_post_type() ) {
		kawi_tags();
		kawi_comment_link();
	}

	edit_post_link(
		sprintf(
			wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers */
				__( 'Edit <span class="screen-reader-text">%s</span>', 'kawi' ),
				array(
					'span' => array(
						'class' => array(),
					),
				)
			),
			get_the_title()
		),
		'<span class="edit-link">' . kawi_ui_icon( 'edit', false ),
		'</span>'
	);
}
endif;


if ( ! function_exists( 'kawi_post_thumbnail' ) ) :
/**
 * Displays an optional post thumbnail on listing views.
 * The thumbnail is wrapped in an anchor tag.
 */
function kawi_post_thumbnail() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}
	$size = get_theme_mod( 'kawi_post_thumbnail_display', 'large-thumbnail' ) === 'large-thumbnail' ? 'kawi-featured' : 'thumbnail';	
	?>
		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php the_post_thumbnail( $size, array( 'alt' => the_title_attribute( array( 'echo' => false, ) ), ) ); ?>
		</a>
	<?php
}
endif;


if ( ! function_exists( 'kawi_hero_image' ) ) :
/**
 * Displays the hero image.
 */
function kawi_hero_image() {
	
	$header_image_setting = get_theme_mod( 'kawi_header_image_display', 'hero-image' );
	
	// Don't display any image tag if the customizer setting is set on a background image. 
	if ( ! in_array( $header_image_setting, [ 'hero-image', 'hero-image-full' ] ) ) {
		return;
	}
	
	$img_tag = false;
	
	if ( is_singular() && has_post_thumbnail() && kawi_jetpack_featured_image_display() ) {
		$size = 'hero-image-full' === $header_image_setting ? 'kawi-featured-full' : 'kawi-featured-large';
		$img_tag = get_the_post_thumbnail( null, $size );
	} elseif( get_header_image() ){
		$img_tag = get_header_image_tag();
	}

	if ( $img_tag ) : ?>
		<div class="hero-image">
			<?php echo $img_tag; ?>
		</div><!-- .hero-image -->
	<?php endif;
}
endif;


if ( ! function_exists( 'kawi_post_content' ) ) :
/**
 * Displays content of the post.
 */
function kawi_post_content() {
	$content_setting = get_theme_mod( 'kawi_content_setting', 'full-content' );
	
	switch ( $content_setting ) {
		case 'no-content':
			break;
		
		case 'excerpt':
			the_excerpt();
			kawi_read_more_link();
			break;

		default:
			the_content( kawi_read_more_text() );
			if( 'excerpt' === get_option( 'jetpack_content_blog_display' ) ){
				kawi_read_more_link();
			}
			break;
	}
}
endif;


if ( ! function_exists( 'kawi_read_more_link' ) ) :
/**
 * Displays read more link, if set in settings.
 */
function kawi_read_more_link() {	
	echo '<a href="' . esc_url( get_permalink() ) . '" class="more-link">' . kawi_read_more_text() . '</a>';
}
endif;


/**
 * Returns the Read more link text.
 *
 * @return   string   $text   Text of the various read more links
 **/
function kawi_read_more_text(){
	$text = sprintf(
		wp_kses(
			/* translators: %s: Name of current post. Only visible to screen readers */
			__( 'Continue reading <span class="screen-reader-text">"%s"</span>', 'kawi' ),
			array(
				'span' => array(
					'class' => array(),
				),
			)
		),
		get_the_title()
	);
	return $text;
}


if ( ! function_exists( 'kawi_the_posts_navigation' ) ) :
/**
 * Displays posts navigation on blog listing pages.
 * 
 * @param  string  $display  Display option you can pass in to bypass the customizer setting.
 */
function kawi_the_posts_navigation( $display = null ) {
	
	$args = [
		'prev_text' => kawi_ui_icon( 'previous', false ) . '<span class="link-text">' . __( 'Previous posts', 'kawi' ) . '</span>',
		'next_text' => '<span class="link-text">' .__( 'Next posts', 'kawi' ) . '</span>' . kawi_ui_icon( 'next', false ),
	];

	$nav_style = ! empty( $display ) ? $display : get_theme_mod( 'kawi_posts_nav_style', 'nav-buttons' );
	switch ( $nav_style ) {
		case 'page-numbers':
			the_posts_pagination( $args );
			break;
		case 'nav-buttons':
			the_posts_navigation( $args );
			break;
	}	
}
endif;



if ( ! function_exists( 'kawi_the_comments_navigation' ) ) :
/**
 * Displays comments navigation on single page.
 */
function kawi_the_comments_navigation() {
	
	$args = [
		'prev_text' => kawi_ui_icon( 'previous', false ) . '<span class="link-text">' . __( 'Older comments', 'kawi' ) . '</span>',
		'next_text' => '<span class="link-text">' .__( 'Newer comments', 'kawi' ) . '</span>' . kawi_ui_icon( 'next', false ),
	];

	$nav_style = get_theme_mod( 'kawi_comments_nav_style', 'nav-buttons' );
	switch ( $nav_style ) {
		case 'page-numbers':
			the_comments_pagination( $args );
			break;
		case 'nav-buttons':
			the_comments_navigation( $args );
			break;
	}
}
endif;
