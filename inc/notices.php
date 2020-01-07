<?php
/**
 * Registers and displays notices
 */


add_action( 'admin_notices', 'kawi_review_notice' );
/**
 * Displays our review notice
 */
function kawi_review_notice(){
    $kawi_review = get_transient( 'kawi_review' );
    if( ! $kawi_review ){
        ?>
            <div class="notice notice-info is-dismissible">
                <p>
                    <?php 
                        printf( 
                            __( 'If you like my Kawi theme, do you mind <a href="%s" target="_blank" rel="noreferrer">leaving a review</a> ? That will help me a lot ! Thanks in advance !', 'kawi' ), 
                            'https://wordpress.org/themes/kawi/'
                        );
                    ?>
                </p>  
            </div>
        <?php
    }
    set_transient( 'kawi_review', true, 2 * WEEK_IN_SECONDS );
}