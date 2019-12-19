<?php
/**
 * The default navbar for our theme.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Kawi
 */

?>

<div class="navbar default-navbar">
    <div class="wrapper navbar-wrapper">
        <?php kawi_site_branding(); ?>
        <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Navigation', 'kawi' ); ?>">
            <?php kawi_menu_button(); ?>
            <div id="menu-area" class="menu-area sidebar-menu-area" aria-expanded="false">
                <div class="menu-section menu-header">
                    <span class="menu-title"><?php esc_html_e( 'Menu', 'kawi' )?></span>
                    <?php kawi_menu_button( 'close' ); ?>
                </div>
                <div class="menu-section">
                    <?php 
                        wp_nav_menu( array(
                            'theme_location' => 'menu-1',
                            'menu_id'        => 'primary-menu',
                            'menu_class'     => 'menu primary-menu',
                            'container'      => 'ul',
                            'link_before'    => '<span>',
                            'link_after'    => '</span>'
                        ) );
                        
                        if ( has_nav_menu( 'social-menu' ) ) {
                            wp_nav_menu( array(
                                'theme_location' => 'social-menu',
                                'menu_id'        => 'social-menu',
                                'menu_class'     => 'menu social-menu',
                                'container'      => 'ul',
                                'link_before'    => '<span>',
                                'link_after'    => '</span>',
                            ) );
                        }
                    ?>
                </div>
            </div>
        </nav><!-- #site-navigation -->
    </div>
</div>