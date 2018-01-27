<?php
add_action( 'add_meta_boxes', 'rnz_pal_meta_boxes');

/**
 * Adds a metabox to the right side of the screen under the â€œPublishâ€ box
 */
function rnz_pal_meta_boxes() {
	add_meta_box(
		'rnz_pal_meta_box',
		'Alternative Link Details',
		'rnz_pal_meta_box',
		'rnz_pal',
		'advanced',
		'high'
	);
}

function rnz_pal_meta_box(){
	global $post;

	wp_nonce_field( basename( __FILE__ ), 'pal_fields' );
	

	$rnz_pal_alternative_link = get_post_meta( $post->ID, 'rnz_pal_alternative_link', true );
	$rnz_pal_original_page = get_post_meta( $post->ID, 'rnz_pal_original_page', true );
	$rnz_pal_redirect = get_post_meta( $post->ID, 'rnz_pal_redirect', true );
	$rnz_pal_canonical = get_post_meta( $post->ID, 'rnz_pal_canonical', true );
	
	?>
	<style>
	div#rnz_pal_meta_box label {
	    display: block;
	}
	div#rnz_pal_meta_box h2 {
	    color: white;
	}
	div#rnz_pal_meta_box input {
	    padding: 10px;
	    margin: 5px 0px;
	}
	div#rnz_pal_meta_box {
	    background: #2c3e50;
	    color: white;
	}
	</style>
	<div class="rnz-page-alternative-link">

		<label>Alternative Link</label>
		<input type="text" name="rnz_pal_alternative_link" value="<?php echo $rnz_pal_alternative_link; ?>" class="widefat"/><br>

		<label>Original Page</label>
		<input type="text" name="rnz_pal_original_page" value="<?php echo $rnz_pal_original_page; ?>" class="widefat"/><br>

		<label>Add Redirect</label>
		<input type="checkbox" name="rnz_pal_redirect" <?php echo ($rnz_pal_redirect) ? 'checked': ''; ?> class="widefat"/><br>

		<label>Add Canonical?</label>
		<input type="checkbox" name="rnz_pal_canonical" <?php echo ($rnz_pal_canonical) ? 'checked': ''; ?> class="widefat"/><br>

	</div>


	<?php

}

function rnz_pal_save_meta_box( $post_id, $post ) {

	// Return if the user doesn't have edit permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}

	// Verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times.
	if ( ! wp_verify_nonce( $_POST['pal_fields'], basename(__FILE__) ) ) {

		return $post_id;
	}

	$responders_meta['rnz_pal_original_page'] = esc_textarea( $_POST['rnz_pal_original_page'] );
	$responders_meta['rnz_pal_alternative_link'] = esc_textarea( $_POST['rnz_pal_alternative_link'] );
	$responders_meta['rnz_pal_redirect'] = esc_textarea( $_POST['rnz_pal_redirect'] );
	$responders_meta['rnz_pal_canonical'] = esc_textarea( $_POST['rnz_pal_canonical'] );

	// Cycle through the $events_meta array.
	// Note, in this example we just have one item, but this is helpful if you have multiple.
	foreach ( $responders_meta as $key => $value ) :
		// Don't store custom data twice
		if ( 'revision' === $post->post_type ) {
			return;
		}
		if ( get_post_meta( $post_id, $key, false ) ) {
			// If the custom field already has a value, update it.
			update_post_meta( $post_id, $key, $value );
		} else {
			// If the custom field doesn't have a value, add it.
			add_post_meta( $post_id, $key, $value);
		}
		if ( ! $value ) {
			// Delete the meta key if there's no value
			delete_post_meta( $post_id, $key );
		}
	endforeach;

}
add_action( 'save_post', 'rnz_pal_save_meta_box', 1, 2 );
?>