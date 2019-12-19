<?php
/**
 * The template for the default hero section, at the top of every page
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Kawi
 */
?>

<div class="hero default-hero">
    <div class="wrapper hero-wrapper">
        <?php kawi_hero_image(); ?>
        <?php $data = kawi_get_hero_data(); ?>
        <div class="main-info">
            <h1 class="main-title"><?php echo wp_kses_post( $data['title'] ); ?></h1>
            <?php if ( $data['meta'] ) : ?>
                <p class="main-meta"><?php echo $data['meta']; ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>