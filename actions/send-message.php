<?php
/**
 * "send-message" action
 * (inspired by bp-themes/bp-default/members/single/messages/compose.php)
 * 
 * available variables :
 *  - $recipientsIds : array of IDs of all the users the action is targeted at
 * 
 * Plugin Name:       BP Members Directory Actions
 */

//var_dump($_REQUEST);
// display form or perform action
if (! empty($_REQUEST['subject']) && ! empty($_REQUEST['content']) && ! empty($_REQUEST['serialized_recipients_ids'])) {
	// Send message
	/*echo "<br><br>On réalise l'action d'envoi de messages !!!!<br>";
	var_dump($_REQUEST['subject']);
	echo "<br/><br/>";
	var_dump($_REQUEST['content']);
	echo "<br/><br/>";
	var_dump($_REQUEST['serialized_recipients_ids']);
	echo "<br/><br/>";*/
	$args = array(
		'recipients' => unserialize($_REQUEST['serialized_recipients_ids']),
		'subject' => $_REQUEST['subject'],
		'content' => $_REQUEST['content']
	);
	$messageId = messages_new_message($args);
	if ($messageId === false) {
		// error
		?>
		<p class="error notice">
			<?php _e('An error occured while sending your message', 'bp-members-directory-actions') ?>
		</p>
		<?php
	} else {
		// ok
		?>
		<p class="error notice">
			<?php _e('Your message was sent successfully', 'bp-members-directory-actions') ?>
		</p>
		<?php
	}

} else { // display send message form ?>

	<form action="" method="post" id="send_message_form" class="standard-form" role="main" enctype="multipart/form-data">

		<?php do_action( 'bp_before_messages_compose_content' ); ?>

		<label for="send-to-input"><?php _e("Send To (Username or Friend's Name)", 'buddypress'); ?></label>
		<ul class="first acfb-holder">
			<li>
				<?php
				$nbRecipients = count($recipientsIds);
				if ($nbRecipients == 1) {
					$recipient = new WP_User($recipientsIds[0]);
					echo $recipient->display_name;
				} else {
					echo count($recipientsIds) . ' ' . __('members', 'bp-members-directory-actions');
				}
				?>
			</li>
		</ul>

		<label for="subject"><?php _e( 'Subject', 'buddypress'); ?></label>
		<input type="text" required name="subject" id="subject" value="<?php bp_messages_subject_value(); ?>" />

		<label for="content"><?php _e( 'Message', 'buddypress'); ?></label>
		<textarea name="content" required id="message_content" rows="15" cols="40"><?php bp_messages_content_value(); ?></textarea>

		<!-- Propagate recipients IDs -->
		<input type="hidden" name="serialized_recipients_ids"
			   id="serialized_recipients_ids"
			   value="<?php echo serialize($recipientsIds); ?>" />
		<?php
			// Stay in BP Members Directory Action execution stream
			bp_mda_propagate_action();
			// Propagate search results if any, to keep display consistent
			bp_mda_bp_profile_search_proxy();
		?>

		<?php do_action( 'bp_after_messages_compose_content' ); ?>

		<div class="submit">
			<input type="submit" value="<?php esc_attr_e( "Send Message", 'buddypress' ); ?>" name="send" id="send" />
		</div>

		<?php wp_nonce_field('bp_mda_send_message'); ?>
	</form>
<?php }