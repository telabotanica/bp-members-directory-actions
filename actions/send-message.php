<?php
/**
 * "send-message" action
 * (inspired by bp-themes/bp-default/members/single/messages/compose.php)
 * Some code might be useless
 * @TODO review it properly
 * 
 * available variables :
 *  - $recipientsIds : array of IDs of all the users the action is targeted at
 * 
 * Plugin Name:       BP Members Directory Actions
 */

// display form or perform action
if (! empty($_REQUEST['subject']) && ! empty($_REQUEST['content']) && ! empty($_REQUEST['serialized_recipients_ids'])) {
	// Send message
	$args = array(
		'recipients' => unserialize($_REQUEST['serialized_recipients_ids']),
		'subject' => $_REQUEST['subject'],
		'content' => $_REQUEST['content']
	);
	$messageId = messages_new_message($args);
	if ($messageId === false) {
		// error - compatible with themes having a notices hook
		add_action('theme_notices', 'bp_mda_send_message_notice_error');
		bp_mda_send_message_notice_error();
	} else {
		// ok - compatible with themes having a notices hook
		add_action('theme_notices', 'bp_mda_send_message_notice_message_sent');
		bp_mda_send_message_notice_message_sent();
	}

} else { // display send message form ?>

	<h2 id="bp_mda_action_form_head" class="bp_mda_action_form_title"><?php _e("Send Message", 'buddypress'); ?></h2>

	<form action="" method="post" id="send_message_form" class="standard-form bp_mda_action_form" role="main" enctype="multipart/form-data">

		<?php do_action( 'bp_before_messages_compose_content' ); ?>

		<label for="send-to-input"><?php _e("Send To", 'buddypress'); ?> : </label>
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
		<input autofocus type="text" required name="subject" id="subject" value="<?php bp_messages_subject_value(); ?>" />

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
			<input class="bp_mda_action_submit" type="submit" value="<?php esc_attr_e( "Send Message", 'buddypress' ); ?>" name="send" id="send" />
		</div>

		<?php wp_nonce_field('bp_mda_send_message'); ?>
	</form>
<?php }

function bp_mda_send_message_notice_message_sent($no_theme_classes=false) { ?>
	<div class="notice notice-confirm">
		<?php _e('Your message was sent successfully', 'bp-members-directory-actions') ?>
	</div>
<?php }

function bp_mda_send_message_notice_error() { ?>
	<div class="notice notice-warning">
		<?php _e('An error occured while sending your message', 'bp-members-directory-actions') ?>
	</div>
<?php }
