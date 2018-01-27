<?php
/**
 * Plugin Name: Page Alternative Links
 * Plugin URI: http://www.renzramos.com
 * Description: Add alternative links for page
 * Version: 1.0.0
 * Author: Renz Ramos
 * Author URI: http://www.renzramos.com
 * License: GPL2
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
     die;
}






DEFINE('RNZ_PAL_URL', plugin_dir_url( __FILE__ ) );
DEFINE('RNZ_AR_TITLE', 'Page Alternative Links');
DEFINE('RNZ_AR_SLUG', 'rnz_page_alternative_links');

// define('CURRENT_DATE',date('Y-m-d'));

//require_once('includes/admin.php');
require_once RNZ_PAL_URL . 'includes/custom-post-types.php';
require_once RNZ_PAL_URL . 'includes/meta-boxes.php';

// require_once('includes/cron.php');
// require_once('includes/email.php');

function rnz_pal_enqueue_scripts($hook) {
		echo $hook;
        if($hook != 'rnz_auto_responders_page_rnz_ar_responder_settings') return;
    
        wp_enqueue_style('rnz-pal-style', plugins_url('assets/css/style.css', __FILE__) );
        wp_enqueue_script('rnz-pal-script', plugins_url('assets/js/script.js', __FILE__) , array(), '1.0.0', true );
        
}
add_action( 'admin_enqueue_scripts', 'rnz_pal_enqueue_scripts' );


if (is_admin()){

	add_filter( 'manage_rnz_pal_posts_columns', 'rnz_pal_set_custom_edit_book_columns' );
	add_action( 'manage_rnz_pal_posts_custom_column' , 'rnz_pal_custom_book_column', 10, 2 );

	function rnz_pal_set_custom_edit_book_columns($columns) {
		unset($columns['date']);
	    $columns['alternative_link'] = __( 'Alternative Link', 'rnz_pal' );
	    $columns['original_page'] = __( 'Original Page', 'rnz_pal' );
	    $columns['redirect'] = __( 'Redirect', 'rnz_pal' );
	    $columns['canonical'] = __( 'Canonical', 'rnz_pal' );

	    return $columns;
	}

	function rnz_pal_custom_book_column( $column, $post_id ) {

		$rnz_pal_alternative_link = get_post_meta( $post_id, 'rnz_pal_alternative_link', true );
		$rnz_pal_original_page = get_post_meta( $post_id, 'rnz_pal_original_page', true );
		$rnz_pal_redirect = get_post_meta( $post_id, 'rnz_pal_redirect', true );
		$rnz_pal_canonical = get_post_meta( $post_id, 'rnz_pal_canonical', true );

	    switch ( $column ) {

	    	case 'alternative_link' :
	            printf('<a href="%s" target="_blank">%s</a>', $rnz_pal_alternative_link, $rnz_pal_alternative_link);
	        break;

	        case 'original_page' :
	            printf('<a href="%s" target="_blank">%s</a>', $rnz_pal_original_page, $rnz_pal_original_page);
	        break;

	        case 'redirect' :
	            echo get_post_meta( $post_id , 'rnz_pal_redirect' , true );
	        break;

	        case 'canonical' :
            	echo get_post_meta( $post_id , 'rnz_pal_canonical' , true ); 
            break;

	    }
	}
}


function check_alternative_links(){
	if (!is_admin()){
		global $wp;

		$protocol = 'http://';

		if (is_ssl()) {
		    $protocol = 'https://';
		}

		$request_url =  $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];;

		// The Query
		$args = array(
			'post_type' => 'rnz_pal',
			'meta_value' => untrailingslashit($request_url) ,
			'meta_key' => 'rnz_pal_alternative_link'

		);

		$query = new WP_Query( $args );

		// The Loop
		if ( $query->have_posts() ):
		
			while ( $query->have_posts() ) {
				$query->the_post();

				// Get meta data
				$rnz_pal_original_page = get_post_meta(get_the_ID(), 'rnz_pal_original_page', true);
				$rnz_pal_redirect = get_post_meta(get_the_ID(), 'rnz_pal_redirect', true);
				$rnz_pal_canonical = get_post_meta(get_the_ID(), 'rnz_pal_canonical', true);


				// Check if we want it to redirect
				if ($rnz_pal_redirect == 'on'):

					wp_redirect($rnz_pal_original_page);
					exit;

				else:

					// Get content of original page
					$original_page_content = @file_get_contents($rnz_pal_original_page);


					if ($rnz_pal_canonical == 'on'):
						// Add canonical meta data that point to the original page
						$canonical_tag = '<link rel="canonical" href="' . $rnz_pal_original_page . '" />';

						// Filter content
						$original_page_content = str_replace('<head>','<head>' . $canonical_tag, $original_page_content);

					endif;


					if ($original_page_content):

						echo $original_page_content;
						die();

					endif;

				endif;

				
				
			}
		
			wp_reset_postdata();
			
		endif;
	}

}
add_action( 'init', 'check_alternative_links' );