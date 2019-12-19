<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Kawi
 */

if ( ! function_exists( 'wp_body_open' ) ) {
    /**
     * Fire the wp_body_open action.
     *
     * Added for backwards compatibility to support WordPress versions prior to 5.2.0.
     */
    function wp_body_open() {
        /**
         * Triggered after the opening <body> tag.
         */
        do_action( 'wp_body_open' );
    }
}


add_filter( 'body_class', 'kawi_body_classes' );
/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function kawi_body_classes( $classes ) {
	
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds sidebar classes.
	if ( ! is_page() && ! is_404() && is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'sidebar';
		$classes[] = sanitize_html_class( get_theme_mod( 'kawi_sidebar_position', 'sidebar-right' ) ) ;
	} else {
		$classes[] = 'no-sidebar';
	}

	// Adds a class for the header image settings.
	if( has_post_thumbnail() || has_header_image() ){
		$classes[] = 'has-header-image';
		$classes[] = sanitize_html_class( get_theme_mod( 'kawi_header_image_filter', 'raw-image' ) );
		$classes[] = sanitize_html_class( get_theme_mod( 'kawi_header_image_display', 'hero-image' ) );
	}

	return $classes;
}


add_filter( 'post_class', 'kawi_post_classes', 10, 3 );
/**
 * Adds custom classes to the array of post classes.
 *
 * @param   array   $classes  An array of classes for the <article class="entry"> element.
 * @param   array   $class    An array of additional class names added to the post.
 * @param   string  $post_id  The post ID.
 * @return  array   $classes
 */
function kawi_post_classes( $classes, $class, $post_id ) {
	if( is_home() || is_archive() || is_search() ){
		$classes[] = sanitize_html_class( get_theme_mod( 'kawi_post_thumbnail_display', 'large-thumbnail' ) );
	}
	return $classes;
}


add_action( 'wp_head', 'kawi_pingback_header' );
/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function kawi_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}


add_action( 'walker_nav_menu_start_el', 'kawi_social_icons', 10, 4 );
/**
 * Filter the output of the menu items to add the markup needed  
 * to display a social icon in the social menu.
 *
 * @param    string         $item_output   Actual <a> tag that's being printed out
 * @param    Post Object    $item          Menu item Post Object
 * @param    int            $depth         Menu depth
 * @param    object         $args          Menu arguments as defined in wp_nav_menu() function call
 * @return   string         $output        Menu link output, with the icon span inside
 */
function kawi_social_icons( $item_output, $item, $depth, $args ) {

	// Do not filter the menu if it's not the social menu
	if ( ! in_array( $args->theme_location, array( 'social-menu', 'footer-social-menu' ) ) ){ 
		return $item_output;
	}

	$supported_icons  = kawi_get_supported_social_icons();
	$domain_or_scheme = 'mailto' === parse_url( $item->url, PHP_URL_SCHEME ) ? 'mailto' : parse_url( $item->url, PHP_URL_HOST );
	if( $domain_or_scheme && array_key_exists( $domain_or_scheme, $supported_icons ) ){
		$svg = apply_filters( 'kawi_social_icon_svg', $supported_icons[$domain_or_scheme], $domain_or_scheme );
		$item_output = str_replace( $args->link_before, '<span class="screen-reader-text">', $item_output );
		$item_output = str_replace( $args->link_after, '</span>' . $svg, $item_output );
	}
	
	return $item_output;
}


/**
 * Returns an associative array of supported social domain and icon names
 *
 * @return   array   $supported_icons   Associative array domain => icon svg
 **/ 
function kawi_get_supported_social_icons(){
	
	$supported_icons = array(
		'facebook.com'      => '<svg class="icon icon-facebook" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" role="img" focusable="false"><rect x="0" fill="none" width="24" height="24"/><g><path d="M20.007 3H3.993C3.445 3 3 3.445 3 3.993v16.013c0 .55.445.994.993.994h8.62v-6.97H10.27V11.31h2.346V9.31c0-2.325 1.42-3.59 3.494-3.59.993 0 1.847.073 2.096.106v2.43h-1.438c-1.128 0-1.346.537-1.346 1.324v1.734h2.69l-.35 2.717h-2.34V21h4.587c.548 0 .993-.445.993-.993V3.993c0-.548-.445-.993-.993-.993z"/></g></svg>',
		'www.facebook.com'  => '<svg class="icon icon-facebook" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" role="img" focusable="false"><rect x="0" fill="none" width="24" height="24"/><g><path d="M20.007 3H3.993C3.445 3 3 3.445 3 3.993v16.013c0 .55.445.994.993.994h8.62v-6.97H10.27V11.31h2.346V9.31c0-2.325 1.42-3.59 3.494-3.59.993 0 1.847.073 2.096.106v2.43h-1.438c-1.128 0-1.346.537-1.346 1.324v1.734h2.69l-.35 2.717h-2.34V21h4.587c.548 0 .993-.445.993-.993V3.993c0-.548-.445-.993-.993-.993z"/></g></svg>',
		'mailto'            => '<svg class="icon icon-mailto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" role="img" focusable="false"><rect x="0" fill="none" width="24" height="24"/><g><path d="M20 4H4c-1.105 0-2 .895-2 2v12c0 1.105.895 2 2 2h16c1.105 0 2-.895 2-2V6c0-1.105-.895-2-2-2zm0 4.236l-8 4.882-8-4.882V6h16v2.236z"/></g></svg>',
		'github.com'        => '<svg class="icon icon-github" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" role="img" focusable="false"><rect x="0" fill="none" width="24" height="24"/><g><path d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.166 6.84 9.49.5.09.68-.22.68-.485 0-.236-.008-.866-.013-1.7-2.782.603-3.37-1.34-3.37-1.34-.454-1.156-1.11-1.464-1.11-1.464-.908-.62.07-.607.07-.607 1.004.07 1.532 1.03 1.532 1.03.89 1.53 2.34 1.09 2.91.833.09-.647.348-1.086.634-1.337-2.22-.252-4.555-1.112-4.555-4.944 0-1.09.39-1.984 1.03-2.682-.104-.254-.448-1.27.096-2.646 0 0 .84-.27 2.75 1.025.8-.223 1.654-.333 2.504-.337.85.004 1.705.114 2.504.336 1.91-1.294 2.748-1.025 2.748-1.025.546 1.376.202 2.394.1 2.646.64.7 1.026 1.59 1.026 2.682 0 3.84-2.337 4.687-4.565 4.935.36.307.68.917.68 1.852 0 1.335-.013 2.415-.013 2.74 0 .27.18.58.688.482C19.138 20.16 22 16.416 22 12c0-5.523-4.477-10-10-10z"/></g></svg>',
		'instagram.com'     => '<svg class="icon icon-instagram" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" role="img" focusable="false"><rect x="0" fill="none" width="24" height="24"/><g><path d="M12 4.622c2.403 0 2.688.01 3.637.052.877.04 1.354.187 1.67.31.42.163.72.358 1.036.673.315.315.51.615.673 1.035.123.317.27.794.31 1.67.043.95.052 1.235.052 3.638s-.01 2.688-.052 3.637c-.04.877-.187 1.354-.31 1.67-.163.42-.358.72-.673 1.036-.315.315-.615.51-1.035.673-.317.123-.794.27-1.67.31-.95.043-1.234.052-3.638.052s-2.688-.01-3.637-.052c-.877-.04-1.354-.187-1.67-.31-.42-.163-.72-.358-1.036-.673-.315-.315-.51-.615-.673-1.035-.123-.317-.27-.794-.31-1.67-.043-.95-.052-1.235-.052-3.638s.01-2.688.052-3.637c.04-.877.187-1.354.31-1.67.163-.42.358-.72.673-1.036.315-.315.615-.51 1.035-.673.317-.123.794-.27 1.67-.31.95-.043 1.235-.052 3.638-.052M12 3c-2.444 0-2.75.01-3.71.054s-1.613.196-2.185.418c-.592.23-1.094.538-1.594 1.04-.5.5-.807 1-1.037 1.593-.223.572-.375 1.226-.42 2.184C3.01 9.25 3 9.555 3 12s.01 2.75.054 3.71.196 1.613.418 2.186c.23.592.538 1.094 1.038 1.594s1.002.808 1.594 1.038c.572.222 1.227.375 2.185.418.96.044 1.266.054 3.71.054s2.75-.01 3.71-.054 1.613-.196 2.186-.418c.592-.23 1.094-.538 1.594-1.038s.808-1.002 1.038-1.594c.222-.572.375-1.227.418-2.185.044-.96.054-1.266.054-3.71s-.01-2.75-.054-3.71-.196-1.613-.418-2.186c-.23-.592-.538-1.094-1.038-1.594s-1.002-.808-1.594-1.038c-.572-.222-1.227-.375-2.185-.418C14.75 3.01 14.445 3 12 3zm0 4.378c-2.552 0-4.622 2.07-4.622 4.622s2.07 4.622 4.622 4.622 4.622-2.07 4.622-4.622S14.552 7.378 12 7.378zM12 15c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zm4.804-8.884c-.596 0-1.08.484-1.08 1.08s.484 1.08 1.08 1.08c.596 0 1.08-.484 1.08-1.08s-.483-1.08-1.08-1.08z"/></g></svg>',
		'www.instagram.com' => '<svg class="icon icon-instagram" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" role="img" focusable="false"><rect x="0" fill="none" width="24" height="24"/><g><path d="M12 4.622c2.403 0 2.688.01 3.637.052.877.04 1.354.187 1.67.31.42.163.72.358 1.036.673.315.315.51.615.673 1.035.123.317.27.794.31 1.67.043.95.052 1.235.052 3.638s-.01 2.688-.052 3.637c-.04.877-.187 1.354-.31 1.67-.163.42-.358.72-.673 1.036-.315.315-.615.51-1.035.673-.317.123-.794.27-1.67.31-.95.043-1.234.052-3.638.052s-2.688-.01-3.637-.052c-.877-.04-1.354-.187-1.67-.31-.42-.163-.72-.358-1.036-.673-.315-.315-.51-.615-.673-1.035-.123-.317-.27-.794-.31-1.67-.043-.95-.052-1.235-.052-3.638s.01-2.688.052-3.637c.04-.877.187-1.354.31-1.67.163-.42.358-.72.673-1.036.315-.315.615-.51 1.035-.673.317-.123.794-.27 1.67-.31.95-.043 1.235-.052 3.638-.052M12 3c-2.444 0-2.75.01-3.71.054s-1.613.196-2.185.418c-.592.23-1.094.538-1.594 1.04-.5.5-.807 1-1.037 1.593-.223.572-.375 1.226-.42 2.184C3.01 9.25 3 9.555 3 12s.01 2.75.054 3.71.196 1.613.418 2.186c.23.592.538 1.094 1.038 1.594s1.002.808 1.594 1.038c.572.222 1.227.375 2.185.418.96.044 1.266.054 3.71.054s2.75-.01 3.71-.054 1.613-.196 2.186-.418c.592-.23 1.094-.538 1.594-1.038s.808-1.002 1.038-1.594c.222-.572.375-1.227.418-2.185.044-.96.054-1.266.054-3.71s-.01-2.75-.054-3.71-.196-1.613-.418-2.186c-.23-.592-.538-1.094-1.038-1.594s-1.002-.808-1.594-1.038c-.572-.222-1.227-.375-2.185-.418C14.75 3.01 14.445 3 12 3zm0 4.378c-2.552 0-4.622 2.07-4.622 4.622s2.07 4.622 4.622 4.622 4.622-2.07 4.622-4.622S14.552 7.378 12 7.378zM12 15c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zm4.804-8.884c-.596 0-1.08.484-1.08 1.08s.484 1.08 1.08 1.08c.596 0 1.08-.484 1.08-1.08s-.483-1.08-1.08-1.08z"/></g></svg>',
		'linkedin.com'      => '<svg class="icon icon-linkedin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" role="img" focusable="false"><rect x="0" fill="none" width="24" height="24"/><g><path d="M19.7 3H4.3C3.582 3 3 3.582 3 4.3v15.4c0 .718.582 1.3 1.3 1.3h15.4c.718 0 1.3-.582 1.3-1.3V4.3c0-.718-.582-1.3-1.3-1.3zM8.34 18.338H5.666v-8.59H8.34v8.59zM7.003 8.574c-.857 0-1.55-.694-1.55-1.548 0-.855.692-1.548 1.55-1.548.854 0 1.547.694 1.547 1.548 0 .855-.692 1.548-1.546 1.548zm11.335 9.764h-2.67V14.16c0-.995-.017-2.277-1.387-2.277-1.39 0-1.6 1.086-1.6 2.206v4.248h-2.668v-8.59h2.56v1.174h.036c.357-.675 1.228-1.387 2.527-1.387 2.703 0 3.203 1.78 3.203 4.092v4.71z"/></g></svg>',
		'www.linkedin.com'  => '<svg class="icon icon-linkedin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" role="img" focusable="false"><rect x="0" fill="none" width="24" height="24"/><g><path d="M19.7 3H4.3C3.582 3 3 3.582 3 4.3v15.4c0 .718.582 1.3 1.3 1.3h15.4c.718 0 1.3-.582 1.3-1.3V4.3c0-.718-.582-1.3-1.3-1.3zM8.34 18.338H5.666v-8.59H8.34v8.59zM7.003 8.574c-.857 0-1.55-.694-1.55-1.548 0-.855.692-1.548 1.55-1.548.854 0 1.547.694 1.547 1.548 0 .855-.692 1.548-1.546 1.548zm11.335 9.764h-2.67V14.16c0-.995-.017-2.277-1.387-2.277-1.39 0-1.6 1.086-1.6 2.206v4.248h-2.668v-8.59h2.56v1.174h.036c.357-.675 1.228-1.387 2.527-1.387 2.703 0 3.203 1.78 3.203 4.092v4.71z"/></g></svg>',
		'pinterest.com'     => '<svg class="icon icon-pinterest" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" role="img" focusable="false"><rect x="0" fill="none" width="24" height="24"/><g><path d="M12 2C6.477 2 2 6.477 2 12c0 4.236 2.636 7.855 6.356 9.312-.087-.79-.166-2.005.035-2.87.183-.78 1.174-4.97 1.174-4.97s-.3-.6-.3-1.484c0-1.39.807-2.43 1.81-2.43.853 0 1.265.642 1.265 1.41 0 .858-.547 2.14-.83 3.33-.235.995.5 1.806 1.482 1.806 1.777 0 3.144-1.874 3.144-4.58 0-2.393-1.72-4.067-4.177-4.067-2.846 0-4.516 2.134-4.516 4.34 0 .86.33 1.78.744 2.282.082.098.094.185.07.286-.078.316-.247.995-.28 1.134-.044.183-.145.222-.334.134-1.25-.58-2.03-2.407-2.03-3.874 0-3.154 2.292-6.05 6.607-6.05 3.47 0 6.166 2.47 6.166 5.774 0 3.446-2.173 6.22-5.19 6.22-1.012 0-1.965-.526-2.29-1.148l-.624 2.377c-.226.87-.835 1.957-1.243 2.622.935.29 1.93.445 2.96.445 5.523 0 10-4.477 10-10S17.523 2 12 2z"/></g></svg>',
		'skype.com'         => '<svg class="icon icon-skype" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" role="img" focusable="false"><rect x="0" fill="none" width="24" height="24"/><g><path d="M10.113 2.7l.1-.02c.033.016.066.032.098.05l-.197-.03zM2.72 10.222c-.006.034-.01.07-.017.103.018.032.033.064.05.095l-.033-.197zm18.555 3.548c.007-.034.01-.07.018-.105-.018-.03-.033-.064-.052-.095l.035.2zm-7.712 7.43c.032.018.065.034.096.052.035-.006.07-.01.104-.017l-.2-.036zM22 16.385c0 1.494-.58 2.898-1.637 3.953-1.056 1.056-2.46 1.636-3.953 1.636-.967 0-1.914-.25-2.75-.725l.105-.016-.202-.035c.032.018.065.034.096.052-.544.096-1.1.147-1.655.147-1.275 0-2.512-.25-3.676-.744-1.126-.474-2.136-1.156-3.003-2.023-.867-.867-1.548-1.877-2.023-3.002-.493-1.163-.743-2.4-.743-3.675 0-.546.05-1.093.143-1.628.018.032.033.064.05.095l-.033-.2c-.006.035-.01.07-.017.104C2.243 9.5 2 8.566 2 7.616c0-1.494.582-2.9 1.637-3.954 1.056-1.056 2.46-1.638 3.953-1.638.915 0 1.818.228 2.622.655-.033.006-.067.012-.1.02l.2.03c-.033-.018-.067-.034-.1-.05.003 0 .004-.002.005-.002.586-.112 1.187-.17 1.788-.17 1.275 0 2.512.25 3.676.743 1.125.477 2.136 1.157 3.003 2.025.868.867 1.548 1.877 2.024 3.002.493 1.164.743 2.4.743 3.676 0 .575-.054 1.15-.157 1.712-.018-.03-.033-.064-.052-.095l.035.2c.007-.034.01-.07.018-.105.46.83.707 1.767.707 2.72zm-5.183-2.248c0-1.33-.613-2.743-3.033-3.282l-2.21-.49c-.84-.192-1.806-.444-1.806-1.237 0-.795.68-1.35 1.903-1.35 2.47 0 2.244 1.697 3.47 1.697.644 0 1.208-.38 1.208-1.03 0-1.522-2.435-2.664-4.5-2.664-2.242 0-4.63.952-4.63 3.488 0 1.222.436 2.522 2.84 3.124l2.983.745c.904.222 1.13.73 1.13 1.188 0 .762-.758 1.507-2.13 1.507-2.678 0-2.306-2.062-3.742-2.062-.645 0-1.113.444-1.113 1.078 0 1.237 1.5 2.887 4.856 2.887 3.196 0 4.777-1.538 4.777-3.6z"/></g></svg>',
		'telegram.com'      => '<svg class="icon icon-telegram" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" role="img" focusable="false"><rect x="0" fill="none" width="24" height="24"/><g><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm3.08 14.757s-.25.625-.936.325l-2.54-1.95-1.63 1.487s-.128.095-.267.035c0 0-.12-.01-.27-.486-.15-.476-.91-2.973-.91-2.973L6 12.35s-.387-.138-.425-.44c-.037-.3.437-.46.437-.46l10.03-3.935s.824-.362.824.238l-1.786 9.004z"/></g></svg>',
		'tumblr.com'        => '<svg class="icon icon-tumblr" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" role="img" focusable="false"><rect x="0" fill="none" width="24" height="24"/><g><path d="M19 3H5c-1.105 0-2 .895-2 2v14c0 1.105.895 2 2 2h14c1.105 0 2-.895 2-2V5c0-1.105-.895-2-2-2zm-5.57 14.265c-2.445.042-3.37-1.742-3.37-2.998V10.6H8.922V9.15c1.703-.615 2.113-2.15 2.21-3.026.006-.06.053-.084.08-.084h1.645V8.9h2.246v1.7H12.85v3.495c.008.476.182 1.13 1.08 1.107.3-.008.698-.094.907-.194l.54 1.6c-.205.297-1.12.642-1.946.657z"/></g></svg>',
		'twitter.com'       => '<svg class="icon icon-twitter" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" role="img" focusable="false"><rect x="0" fill="none" width="24" height="24"/><g><path d="M22.23 5.924c-.736.326-1.527.547-2.357.646.847-.508 1.498-1.312 1.804-2.27-.793.47-1.67.812-2.606.996C18.325 4.498 17.258 4 16.078 4c-2.266 0-4.103 1.837-4.103 4.103 0 .322.036.635.106.935-3.41-.17-6.433-1.804-8.457-4.287-.353.607-.556 1.312-.556 2.064 0 1.424.724 2.68 1.825 3.415-.673-.022-1.305-.207-1.86-.514v.052c0 1.988 1.415 3.647 3.293 4.023-.344.095-.707.145-1.08.145-.265 0-.522-.026-.773-.074.522 1.63 2.038 2.817 3.833 2.85-1.404 1.1-3.174 1.757-5.096 1.757-.332 0-.66-.02-.98-.057 1.816 1.164 3.973 1.843 6.29 1.843 7.547 0 11.675-6.252 11.675-11.675 0-.178-.004-.355-.012-.53.802-.578 1.497-1.3 2.047-2.124z"/></g></svg>',
		'vimeo.com'         => '<svg class="icon icon-vimeo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" role="img" focusable="false"><rect x="0" fill="none" width="24" height="24"/><g><path d="M22.396 7.164c-.093 2.026-1.507 4.8-4.245 8.32C15.323 19.16 12.93 21 10.97 21c-1.214 0-2.24-1.12-3.08-3.36-.56-2.052-1.118-4.105-1.68-6.158-.622-2.24-1.29-3.36-2.004-3.36-.156 0-.7.328-1.634.98l-.978-1.26c1.027-.903 2.04-1.806 3.037-2.71C6 3.95 7.03 3.328 7.716 3.265c1.62-.156 2.616.95 2.99 3.32.404 2.558.685 4.148.84 4.77.468 2.12.982 3.18 1.543 3.18.435 0 1.09-.687 1.963-2.064.872-1.376 1.34-2.422 1.402-3.142.125-1.187-.343-1.782-1.4-1.782-.5 0-1.013.115-1.542.34 1.023-3.35 2.977-4.976 5.862-4.883 2.14.063 3.148 1.45 3.024 4.16z"/></g></svg>',
		'youtube.com'       => '<svg class="icon icon-youtube" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" role="img" focusable="false"><rect x="0" fill="none" width="24" height="24"/><g><path d="M21.8 8s-.195-1.377-.795-1.984c-.76-.797-1.613-.8-2.004-.847-2.798-.203-6.996-.203-6.996-.203h-.01s-4.197 0-6.996.202c-.39.046-1.242.05-2.003.846C2.395 6.623 2.2 8 2.2 8S2 9.62 2 11.24v1.517c0 1.618.2 3.237.2 3.237s.195 1.378.795 1.985c.76.797 1.76.77 2.205.855 1.6.153 6.8.2 6.8.2s4.203-.005 7-.208c.392-.047 1.244-.05 2.005-.847.6-.607.795-1.985.795-1.985s.2-1.618.2-3.237v-1.517C22 9.62 21.8 8 21.8 8zM9.935 14.595v-5.62l5.403 2.82-5.403 2.8z"/></g></svg>',
	);

	return apply_filters( 'kawi_supported_social_icons' , $supported_icons );
	
}


/**
 * Returns an associative array of icons used in the UI
 *
 * @return   array   $ui_icons   icon name => icon svg
 **/
function kawi_get_ui_icons(){

	$ui_icons = array(
		'menu'       => '<svg class="icon icon-menu" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 16 16" aria-hidden="true" role="img" focusable="false"><rect x="0" fill="none" width="16" height="16" /><g><path d="M0 14h16v-2H0v2zM0 2v2h16V2H0zm0 7h16V7H0v2z" /></g></svg>',
		'close'      => '<svg class="icon icon-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" aria-hidden="true" role="img" focusable="false"><rect x="0" fill="none" width="16" height="16" /><g><path d="M12.7 4.7l-1.4-1.4L8 6.6 4.7 3.3 3.3 4.7 6.6 8l-3.3 3.3 1.4 1.4L8 9.4l3.3 3.3 1.4-1.4L9.4 8" /></g></svg>',
		'tags'       => '<svg class="icon icon-tags" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" aria-hidden="true" role="img" focusable="false"><rect x="0" fill="none" width="16" height="16"/><g><path d="M11.3 4.3c-.2-.2-.4-.3-.7-.3H3c-.5 0-1 .5-1 1v6c0 .6.5 1 1 1h7.6c.3 0 .5-.1.7-.3L15 8l-3.7-3.7zM10 9c-.5 0-1-.5-1-1s.5-1 1-1 1 .5 1 1-.5 1-1 1z"/></g></svg>',
		'categories' => '<svg class="icon icon-categories" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" aria-hidden="true" role="img" focusable="false"><rect x="0" fill="none" width="16" height="16"/><g><path d="M13 5H8l-.7-1.4c-.2-.4-.5-.6-.9-.6H3c-.5 0-1 .5-1 1v8c0 .6.5 1 1 1h10c.6 0 1-.4 1-1V6c0-.6-.4-1-1-1z"/></g></svg>',
		'date'       => '<svg class="icon icon-date" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" aria-hidden="true" role="img" focusable="false"><rect x="0" fill="none" width="16" height="16"/><g><path d="M12 3h-1V2H9v1H7V2H5v1H4c-1.1 0-2 .9-2 2v6c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-3 8H8V6.2l-.8.3-.4-1L9 4.8V11z"/></g></svg>',
		'author'     => '<svg class="icon icon-user" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" aria-hidden="true" role="img" focusable="false"><rect x="0" fill="none" width="16" height="16"/><g><path d="M8 8c1.7 0 3-1.3 3-3S9.7 2 8 2 5 3.3 5 5s1.3 3 3 3zm2 1H6c-1.7 0-3 1.3-3 3v2h10v-2c0-1.7-1.3-3-3-3z"/></g></svg>',
		'comment'    => '<svg class="icon icon-comment" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" aria-hidden="true" role="img" focusable="false"><rect x="0" fill="none" width="16" height="16"/><g><path d="M12 4H4c-1.1 0-2 .9-2 2v8l2.4-2.4c.4-.4.9-.6 1.4-.6H12c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2z"/></g></svg>',
		'edit'       => '<svg class="icon icon-edit" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" aria-hidden="true" role="img" focusable="false"><rect x="0" fill="none" width="16" height="16"/><g><path d="M12.6 6.9l.5-.5c.8-.8.8-2 0-2.8l-.7-.7c-.8-.8-2-.8-2.8 0l-.5.5 3.5 3.5zM8.4 4.1L2 10.5V14h3.5l6.4-6.4"/></g></svg>',
		'next'       => '<svg class="icon icon-next" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" aria-hidden="true" role="img" focusable="false"><rect x="0" fill="none" width="16" height="16"/><g><path d="M3 7h6.6L7.3 4.7l1.4-1.4L13.4 8l-4.7 4.7-1.4-1.4L9.6 9H3"/></g></svg>',
		'previous'   => '<svg class="icon icon-previous" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" aria-hidden="true" role="img" focusable="false"><rect x="0" fill="none" width="16" height="16"/><g><path d="M13 7H6.4l2.3-2.3-1.4-1.4L2.6 8l4.7 4.7 1.4-1.4L6.4 9H13"/></g></svg>',
	);

	return apply_filters( 'kawi_ui_icons' , $ui_icons );

}


add_filter( 'the_title', 'kawi_default_post_title', 10, 2 );
/**
 * Filters the post title to provide a default one in case there's no title to display.
 *
 * @param   string  $title  Post title to display
 * @param   int     $id     Post ID
 * @return  string  $title   Default post title
 **/
function kawi_default_post_title( $title, $id ){
	if ( empty( $title ) ){
		$title = '<em>' . __( 'Untitled', 'kawi' ). '</em>';
	}
	return $title;
}

/**
 * Callback function used to display comments
 *
 * @param   WP_Comment   $comment   Comment to display.
 * @param   int          $depth     Depth we're at.
 * @param   array        $args      Arguments passed to wp_list_comments() function call.
 **/
function kawi_comment_callback( $comment, $args, $depth ) {
	$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
?>
	<<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $comment->has_children ? 'parent' : '', $comment ); ?>>
		<div id="div-comment-<?php comment_ID(); ?>" class="comment-body wrapper comment-wrapper">
			
			<header class="comment-author vcard">
				<?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
				<div class="comment-details">
					<strong class="comment-author-name h6"><?php comment_author_link( $comment ); ?></strong>
						<time class="comment-date" datetime="<?php comment_time( 'c' ); ?>">
							<?php
								/* translators: 1: comment date, 2: comment time */
								printf( esc_html__( 'On %1$s at %2$s', 'kawi' ), get_comment_date( '', $comment ), get_comment_time() );
							?>
						</time>
				</div><!-- .comment-metadata -->
			</header><!-- .comment-author -->

			<div class="comment-content">
				<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'kawi' ); ?></p>
				<?php endif; ?>
				<?php comment_text(); ?>
			</div><!-- .comment-content -->

			<footer class="comment-footer">
				<?php
					comment_reply_link( array_merge( $args, array(
						'add_below' => 'div-comment',
						'depth'     => $depth,
						'max_depth' => $args['max_depth'],
						'before'    => '<span class="reply-link">' . kawi_ui_icon( 'comment', false ),
						'after'     => '</span>'
					) ) );
					edit_comment_link( '<span>' . __( 'Edit', 'kawi' ) . '</span>', '<span class="edit-link">' . kawi_ui_icon( 'edit', false ), '</span>' ); 
				?>
			</footer><!-- .comment-footer -->

		</div><!-- .comment-body -->
<?php
}


/**
 * Display a Featured Image on archive pages if option is ticked.
 */
function kawi_jetpack_featured_image_archive_display() {
    if ( ! function_exists( 'jetpack_featured_images_remove_post_thumbnail' ) ) {
        return false;
    } else {
        $options         = get_theme_support( 'jetpack-content-options' );
        $featured_images = ( ! empty( $options[0]['featured-images'] ) ) ? $options[0]['featured-images'] : null;
 
        $settings = array(
            'archive-default' => ( isset( $featured_images['archive-default'] ) && false === $featured_images['archive-default'] ) ? '' : 1,
        );
 
        $settings = array_merge( $settings, array(
            'archive-option'  => get_option( 'jetpack_content_featured_images_archive', $settings['archive-default'] ),
        ) );
 
        if ( $settings['archive-option'] ) {
            return true;
        } else {
            return false;
        }
    }
}


/**
 * Show/Hide Featured Image outside of the loop.
 */
function kawi_jetpack_featured_image_display() {
    if ( ! function_exists( 'jetpack_featured_images_remove_post_thumbnail' ) ) {
        return true;
    } else {
        $options         = get_theme_support( 'jetpack-content-options' );
        $featured_images = ( ! empty( $options[0]['featured-images'] ) ) ? $options[0]['featured-images'] : null;
 
        $settings = array(
            'post-default' => ( isset( $featured_images['post-default'] ) && false === $featured_images['post-default'] ) ? '' : 1,
            'page-default' => ( isset( $featured_images['page-default'] ) && false === $featured_images['page-default'] ) ? '' : 1,
        );
 
        $settings = array_merge( $settings, array(
            'post-option'  => get_option( 'jetpack_content_featured_images_post', $settings['post-default'] ),
            'page-option'  => get_option( 'jetpack_content_featured_images_page', $settings['page-default'] ),
        ) );
 
        if ( ( ! $settings['post-option'] && is_single() )
            || ( ! $settings['page-option'] && is_singular() && is_page() ) ) {
            return false;
        } else {
            return true;
        }
    }
}
