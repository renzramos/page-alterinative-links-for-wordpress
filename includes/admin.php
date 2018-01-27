<?php
function rnz_pal_page(){
	$post_type_link = 'edit.php?post_type=rnz_pal_post_type';
    add_submenu_page( $post_type_link, 'Settings' , 'Settings', 'manage_options', rnz_pal_SLUG . '_settings' , 'rnz_pal_page_callback', 'dashicons-welcome-comments' ,6 ); 

    //call register settings function
	add_action( 'admin_init', 'register_rnz_pal_settings' );


}
add_action( 'admin_menu', 'rnz_pal_page' );

function rnz_pal_page_callback(){
	?>
	<div id="rnz-ar-container" class="wrap">
        <h1><?php echo rnz_pal_TITLE; ?> <small>1.0</small></h1>
        <small>Developed by Renz Ramos</small>
        <form method="post" action="options.php">
		    
		    <?php settings_fields( 'rnz-ar-settings-group' ); ?>
		    <?php do_settings_sections( 'rnz-ar-settings-group' ); ?>
		    <table class="form-table">
		        
		        <tr valign="top">
		        	<th scope="row">Reply To</th>
		        	<td>
		        		<?php
		        		$rnz_pal_reply_to = get_option('rnz_pal_reply_to'); 
						?>
						<input type="text" value="<?php echo $rnz_pal_reply_to; ?>" name="rnz_pal_reply_to">
					</td>
		        </tr>


		        <tr valign="top">
		        	<th scope="row">Header</th>
		        	<td>
		        		<?php
		        		$rnz_pal_header = get_option('rnz_pal_header'); 
						$editor_id = 'rnz_pal_header';
						$settings = array( 'media_buttons' => true );

						wp_editor( $rnz_pal_header , $editor_id, $settings );

						?>
					</td>
		        </tr>
		         
		        <tr valign="top">
		        	<th scope="row">Footer</th>
			        <td>
		        		<?php
		        		$rnz_pal_header =get_option('rnz_pal_footer'); 
						$editor_id = 'rnz_pal_footer';
						$settings = array( 'media_buttons' => true );

						wp_editor( $rnz_pal_header , $editor_id, $settings );

						?>
					</td>
		        </tr>
		        
		    </table>
		    <?php submit_button(); ?>

		</form>
    </div>
	<?php
}

function register_rnz_pal_settings() {
	register_setting( 'rnz-ar-settings-group', 'rnz_pal_reply_to' );
	register_setting( 'rnz-ar-settings-group', 'rnz_pal_footer' );
	register_setting( 'rnz-ar-settings-group', 'rnz_pal_header' );
}




?>