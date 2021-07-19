<?php
require_once(__DIR__ . '/../../session_setup.php');
require_once(__DIR__ . '/../absolute_database_config.php');
include_once(__DIR__ . '/../../utilities/request_result.php');
include_once(__DIR__ . '/../../utilities/mail.class.php');
require_once('../push_notifications.php');
$export_absolute_path = $_SERVER['DOCUMENT_ROOT'] . '/export/';
$background_absolute_path = $_SERVER['DOCUMENT_ROOT'] . '/includes/create-business-card/uploads/background/';
$logo_absolute_path = $_SERVER['DOCUMENT_ROOT'] . '/includes/create-business-card/uploads/';
if (!isset($_SESSION['card']['card_id'])) {
	exit;
} else {
	$card_id = isset($_SESSION['card']['card_id']) ? $_SESSION['card']['card_id'] : '';

	if (!empty($card_id)) {
		$query = sprintf("
		select assigned_id, card_name from card where card_id='%s'
		", $card_id);
		$result = mysqli_query($conn, $query);
		$bcn = '';
		while ($row = mysqli_fetch_assoc($result)) {
			$bcn = $row['assigned_id'];
			$card_name = $row['card_name'];
		}
		$query = "select canvas_front from card_data where card_id='$card_id'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_row($result);
		$card_picture = $row[0];
		// mail('webintenerife@gmail.com', 'card image', $query . '<br>' . $card_picture . '   <br>'.mysqli_error($conn));
		$user_id = $_SESSION['user_id'];
		$r = new Result();
		$query = sprintf("
		select first_name, last_name, profile_image from user where user_id='%s'
		", $user_id);
		$result = mysqli_query($conn, $query);
		$user_name = '';
		while ($row = mysqli_fetch_assoc($result)) {
			$user_name = $row['first_name'] . ' ' . $row['last_name'];
			$author_profile = $row['profile_image'];
			if ($author_profile == ''){
				$author_profile = 'assets/img/def_avatar.gif';
			}
		}
		$query = sprintf("
			SELECT DISTINCT cc.user_id, u2.first_name, u2.last_name, u2.email_address, u2.user_id, u2.profile_image, u2.reg_id
			from card_contact cc
			inner join user u2 on cc.user_id = u2.user_id
			and u2.user_id in (
				select ccc.user_id from card_contact ccc
				where ccc.card_id = '%s'
				and ccc.user_id != '%s')
		", $card_id, $user_id);
		$result = mysqli_query($conn, $query);
		$r->data = array();
//		$date = new DateTime( 'NOW' );
//		$date_string = $date->format( 'Y-m-d H:i:s' );
		$date_string = urldecode($_POST['date_string']);

		if (isset($_SESSION['card']['edit_mode']) && $_SESSION['card']['edit_mode'] === true) {
			while ($row = mysqli_fetch_assoc($result)) {
				$email = $row['email_address'];
				$recipient_reg_id = $row['reg_id'];
				$recipient_user_id = $row['user_id'];
				$recipient_name = $row['first_name'];
				$recipient_full_name = $row['first_name'] . $row['last_name'];
				$recipient_profile_image = $row['profile_image'];
				if ($recipient_profile_image == ''){
					$recipient_profile_image = 'assets/img/def_avatar.gif';
				}

				/* add event to notifications table */
				$query2 = "INSERT INTO notifications (notification_type, event, event_date, recipient_user_id, active_user_id) VALUES ('card_created', 'Card $card_name ($bcn) updated','$date_string', '$recipient_user_id', '$user_id')";
				$result2 = mysqli_query($conn, $query2);

				/* limiting table to 20 records per user */
				$query3 = "SELECT COUNT(*) as total FROM notifications WHERE recipient_user_id='$recipient_user_id'";
				$result3 = mysqli_query($conn, $query3);
				if ($result3) {
					$row = mysqli_fetch_assoc($result3);
					if ($row['total'] > 20) {
						$query4 = "DELETE FROM `notifications`	WHERE recipient_user_id='$recipient_user_id' AND id NOT IN (	SELECT id FROM (SELECT id	FROM `notifications` ORDER BY id DESC	LIMIT 20) foo )";
						$result4 = mysqli_query($conn, $query4);
					}
				}

//				$subject = "Business card " . $bcn . " has been updated";
				$subject = "Card updated";

				$tokens = array(
					'{{__RECIPIENT_FULL_NAME__}}',
					'{{__RECIPIENT_PROFILE__}}',
					'{{__AUTHOR_PROFILE__}}',
					'{{__AUTHOR_FULL_NAME__}}',
					'{{__CARD_NAME__}}',
					'{{__CARD_PICTURE__}}',
					'{{__BCN__}}',
					'{{__TARGET_URL__}}'
				);
				$replacements = array(
					$recipient_full_name,
					BASEURL . $recipient_profile_image,
					BASEURL . $author_profile,
					$user_name,
					$card_name,
					BASEURL . $card_picture,
					$bcn,
					BASEURL . "view_card_details.php?bcn=" . $bcn . "&card_id=" . $card_id
				);

				$raw_message = file_get_contents('../../email-templates/card_updated/index.html');

				$message = str_replace($tokens, $replacements, $raw_message);
//				$message = "$user_name has updated the Business card $card_name with BCN $bcn. Check card details at <a href='https://www.cardition.com/view_card_details.php?bcn=" . $bcn . "&card_id=" . $card_id . "'>cardition.com</a>.<br><br>Cardition Staff";
				/*
				 * mg
				 * ADDED 09/08/2015 to send email when user updates one of its card
				 * EDITED 19/11/2015:
				 *  - in manage-your-cards.js addeed edit_mode to the uri string which brings user to create-business-card-step-1.php
				 *  - in create-business-card-step-1.php created a session variable to notify user is editing his card
				 *  - in create-business-card-step-6.php added variable process_completed to the uri string to go back to manage-your-cards.php
				 *  - in manage-your-cards.php check if user is in editing mode; if so, check if process_completed is true: if true sends email.
				 *    unset edit_mode
				 */
//				$message = "The user $user_name has updated the Business card ID: $bcn <a href='http://bcfolder.webintenerife.com/'></a>.<br><br>Cardition Staff";
				$mailer = new Mailer();
				$mail_result = $mailer->send_mail
						(
						"info@cardition.com", "Cardition", $email, $subject, $message
				);
				sendPush($recipient_reg_id, $subject, $message, $card_id, $user_id);
			}

//			if ( isset( $_GET[ 'process_completed' ] ) && $_GET[ 'process_completed' ] === "true" )
			if (isset($_POST['process_completed']) && process_completed == true) {
				/* DELETE OLD CARD AND RENAME NEW ONE */
				$new_id = $_SESSION['card']['card_id'];
				$old_id = $_SESSION['card']['card_id'] . '*';
				$query = "DELETE FROM card WHERE card_id='$old_id'";
				$result = mysqli_query($conn, $query);
				$query = "DELETE FROM card_data WHERE card_id='$old_id'";
				$result = mysqli_query($conn, $query);
//				$query = "UPDATE card SET card_id='$new_id' WHERE card_id='$old_id'";
//				$result = mysqli_query( $conn, $query );
//				$query = "DELETE FROM card_data WHERE card_id='$new_id'";
//				$result = mysqli_query( $conn, $query );
//				$query = "UPDATE card_data SET card_id='$new_id', canvas_back='export/$new_id-back.png', canvas_front='export/$new_id-front.png' WHERE card_id='$old_id'";
//				$result = mysqli_query( $conn, $query );
				if (is_writable($_SERVER['DOCUMENT_ROOT'] . '/export/')) {
				} else {
				}
				unlink($export_absolute_path . $old_id . '-front.png');
				unlink($export_absolute_path . $old_id . '-back.png');
				unlink($background_absolute_path . $old_id . '.png');
				if (file_exists($logo_absolute_path . $old_id . '.png')) {
					unlink($logo_absolute_path . $old_id . '.png');
				}
//				unlink( $_SERVER[ 'DOCUMENT_ROOT' ] . '/export/' . $new_id . '-front.png' );
//				unlink( $_SERVER[ 'DOCUMENT_ROOT' ] . '/export/' . $new_id . '-back.png' );
//				rename( $_SERVER[ 'DOCUMENT_ROOT' ] . '/export/' . $old_id . '-front.png', $_SERVER[ 'DOCUMENT_ROOT' ] . '/export/' . $new_id . '-front.png' );
//				rename( $_SERVER[ 'DOCUMENT_ROOT' ] . '/export/' . $old_id . '-back.png', $_SERVER[ 'DOCUMENT_ROOT' ] . '/export/' . $new_id . '-back.png' );
				/* END DELETE OLD CARD ND RENAME NEW ONE */
			} else {
			}
			if (isset($_POST['buying']) && $_POST['buying'] == false) {
				unset($_SESSION['card']['edit_mode']);
			}
			unset($_SESSION['card']);
		} else {
			$_SESSION['created_card_id'] = $card_id;
			$_SESSION['created_card_type'] = isset($_SESSION['card']['card_type']) ? $_SESSION['card']['card_type'] : 'Type undefined';
			unset($_SESSION['card']);
		}
	}
	if (isset($_POST['buying']) && $_POST['buying'] == false) {
		unset($_SESSION['card']);
	}
}
