<?php
if (isset($_GET['rnz-auto-responder']) && isset($_GET['rnz-key']) ):
	
	if ( $_GET['rnz-auto-responder'] == 'run' && $_GET['rnz-key'] == 'dCoW96jKbW'){

		$key = $_GET['rnz-key'];
		$users = get_users();


		if ($users):

			?>
			<!DOCTYPE html>
			<html lang="en">
				<head>
					<meta charset="utf-8">
					<meta http-equiv="X-UA-Compatible" content="IE=edge">
					<meta name="viewport" content="width=device-width, initial-scale=1">
					<title>Auto Responder</title>
					<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
					<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
					<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
				</head>
				<body>
		
					<div class="container">
						<h1 class="text-center">Auto Responder 1.0</h1>
						<p class="text-center">Current Date: <?php echo CURRENT_DATE; ?>
						<table class="table table-bordered">
					        <thead>
					            <tr>
					                <th>ID</th>
					                <th>Name</th>
					                <th>Email</th>
					                <th>Registered Date</th>
					                <th>Responders</th>
					            </tr>
					        </thead>
					        <tbody>
					            <?php foreach ( $users as $user ) { ?>
					            <tr>
					            	<td><?php echo $user->ID; ?></td>
					            	<td><?php echo $user->first_name; ?></td>
					            	<td><?php echo $user->data->user_email; ?></td>
					            	<td><?php echo $user->data->user_registered; ?></td>
					            	<td>
					            		<?php get_responders_table($user); ?>
					            	</td>
					            </tr>
								<?php } ?>
					        </tbody>
					    </table>
						<p class="text-right">Developed by Renz Ramos (01-08-18)</p>	

					</div>

				</body>
			</html>    
			<?php

		endif; 


		exit;

	}

endif;


function get_responders_table($user){
	$key = $_GET['rnz-key'];
	$register_date = $user->data->user_registered;
	$user_id = $user->ID;
	?>
	<table class="table">
		<thead>
			<th>Name</th>
			<th>Number of Day(s)</th>
			<th>Scheduled On</th>
			<th>Need To Send?</th>
			<th>Status</th>
			<th>Action</th>
		</thead>
	<?php
	$args = array(
		'post_type' => 'rnz_auto_responders',
		'posts_per_page' => -1,
		'orderby' => 'rnz_ar_sent_after',
		'order' => 'ASC',
	);
	$responders = get_posts( $args ); 
		
		foreach ($responders as $responder){

			$responder_id = $responder->ID;
			$responder_title = get_the_title($responder_id);
			$rnz_ar_subject = get_post_meta( $responder_id, 'rnz_ar_subject', true );
			$rnz_ar_reply_to = get_post_meta( $responder_id, 'rnz_ar_reply_to', true );
			$rnz_ar_cc = get_post_meta( $responder_id, 'rnz_ar_cc', true );
			$rnz_ar_bcc = get_post_meta( $responder_id, 'rnz_ar_bcc', true );
			$rnz_ar_sent_after = get_post_meta( $responder_id, 'rnz_ar_sent_after', true );

			$scheduled_date = date('Y-m-d', strtotime($register_date . ' + ' . $rnz_ar_sent_after.  ' days'));
			

			$user_responder_key = 'rnz_ar_' . $responder_id . '_' . $user->ID;
			$user_responder_status = get_user_meta($user->ID, $user_responder_key, true);

			if (!$user_responder_status){
				if ($scheduled_date == CURRENT_DATE){
					
					// Update user
					update_user_meta($user->ID, $user_responder_key, 'sent');
					$user_responder_status = true;


					// Send Email

				}
			}

			$sent_status = '<label class="label label-success">Sent</label>';
			$pending_status = '<label class="label label-danger">Pending</label>';
			?>
			<tr>
				<td><?php echo $responder_title; ?></td>
				<td><?php echo $rnz_ar_sent_after; ?></td>
				<td><?php echo $scheduled_date; ?></td>
				<td><?php echo ($scheduled_date == CURRENT_DATE) ? 'Yes' : 'No'; ?></td>
				<td>

					<?php echo ($user_responder_status) ? $sent_status : $pending_status ; ?>
					
				</td>
				<td>
					<a target="_blank" href="<?php echo home_url(); ?>?rnz-auto-responder=preview&rnz-key=<?php echo $key; ?>&responder=<?php echo $responder_id; ?>&user=<?php echo $user_id; ?>">Preview</a>
			</tr>

		<?php } ?>
		
	</table>
	<?php
}
?>