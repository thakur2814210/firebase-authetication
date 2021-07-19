<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once('../../session_setup.php');
require_once '../database_config.php';
require_once('../push_notifications.php');
include_once('models.php');
include_once('../../utilities/request_result.php');
include_once(__DIR__ . '/../../utilities/mail.class.php');

$r = new Result();
$card_id = trim($_REQUEST['card_id']);
$user_requested_by = $_SESSION['user_id'];
$date_string = urldecode($_POST['date_string']);
$query = sprintf("SELECT user_id, card_name, card_type, assigned_id FROM card WHERE card_id = '%s'", $card_id);
//$result = $r->handleQuery( $conn, $query );
$result = mysqli_query($conn, $query);
while ($card_type_row = mysqli_fetch_array($result)) {
	$recipient_user_id = $card_type_row["user_id"];
	$card_type = $card_type_row["card_type"];
	$bcn = $card_type_row["assigned_id"];
	$card_name = $card_type_row["card_name"];
}
$query = "select canvas_front from card_data where card_id='$card_id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_row($result);
$card_picture = $row[0];
$query = "SELECT first_name, last_name, email_address, reg_id, profile_image FROM user WHERE user_id='$recipient_user_id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_row($result);
$recipient_first_name = $row[0];
$recipient_last_name = $row[1];
$recipient_email = $row[2];
$recipient_reg_id = $row[3];
$recipient_profile_image = $row[4];
if ($recipient_profile_image == '') {
	$recipient_profile_image = 'assets/img/def_avatar.gif';
}
$query = "SELECT first_name, last_name, reg_id, profile_image FROM user WHERE user_id='$user_requested_by'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_row($result);
$requested_by_first_name = $row[0];
$requested_by_last_name = $row[1];
$requested_by_reg_id = $row[2];
$requested_profile_image = $row[3];
if ($requested_profile_image == '') {
	$requested_profile_image = 'assets/img/def_avatar.gif';
}

//=============QUERY=============
//check settings to manage require-reciprocity and need-approval
//=============QUERY=============
//It is a persona/corporate card
if ($card_type == "Personal" || $card_type == "Corporate") {
	//Check if we have to do extra procedure because of reciprocity setting
	//=============QUERY=============
	$query = sprintf("SELECT requires_reciprocity FROM personal_card_setting WHERE card_id = '%s'", $card_id);
	$result = $r->handleQuery($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		$requires_reciprocity = $row["requires_reciprocity"];
	}
	//=============QUERY=============
	if ($requires_reciprocity == "1" || $requires_reciprocity == 1) {
		//Get the default card of requesting user, which we will then use as reciprocity card
		//=============QUERY=============
		$query = sprintf("SELECT u.default_card FROM user u WHERE u.user_id='%s'", $user_requested_by);
		$result = $r->handleQuery($conn, $query);
		while ($row = mysqli_fetch_array($result)) {
			$default_card_id = $row["default_card"];
		}
		//just ensure opposite user does not own the card yet
		//=============QUERY=============
		$query = sprintf("SELECT card_id FROM card_contact WHERE user_id = '%s' AND card_id = '%s'", $recipient_user_id, $default_card_id);
		$result = $r->handleQuery($conn, $query);
		$count = 0;
		while ($row = mysqli_fetch_array($result)) {
			$count = 1;
			$id = $row["card_id"];
		}
		//=============QUERY=============
		if ($count == 0) {
			//Create the card contact for the opposite user expecting the card to be added to his contact list
			//=============QUERY=============
			$query = sprintf("INSERT INTO card_contact (user_id,card_id,private) values ('%s','%s',1)", $recipient_user_id, $default_card_id);
			$result = $r->handleQuery($conn, $query);
			$result = mysqli_query($conn, $query);
//			if ($result)
//			{
//				$r->success = 1;
//			}
//			else
//			{
//				$r->success = 0;
//			}
			//=============QUERY=============
			//Create relevant card_link entry for accounting purposes
			//=============QUERY=============
			//TODO: String interpolation should use full obj card_link, not separate properties as params
			$query = sprintf("INSERT INTO card_link (user_requested_by,card_id_requested,link_status,date_requested,date_accepted,request_origin) VALUES ('%s','%s','%s',NOW(),NOW(),'reciprocity')", $recipient_user_id, $default_card_id, "ACCEPTED"
			);
			$result = $r->handleQuery($conn, $query);
			//=============QUERY=============
		}
	}
}
//=============QUERY=============
//=============NOTIFCATION AND EMAIL=============
/* add event to notifications table */
$query2 = "INSERT INTO notifications (notification_type, event, event_date, recipient_user_id, active_user_id) VALUES ('card_added', '$requested_by_first_name $requested_by_last_name added your business card \'$card_name\' ($bcn) to its folder.','$date_string', '$recipient_user_id', '$user_requested_by')";
$result2 = mysqli_query($conn, $query2);

/* limiting table to 20 records per user */
$query3 = "SELECT COUNT(*) as total FROM notifications WHERE recipient_user_id='$recipient_user_id'";
$result3 = mysqli_query($conn, $query3);
if ($result3) {
	$row = mysqli_fetch_assoc($result3);
	if ($row['total'] > 20) {
		$query4 = "DELETE FROM `notifications` WHERE recipient_user_id='$recipient_user_id' AND id NOT IN (	SELECT id FROM (SELECT id	FROM `notifications` ORDER BY id DESC	LIMIT 20) foo )";
		$result4 = mysqli_query($conn, $query4);
	}
}

$subject = "Your Business card " . $bcn . " has been added";
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
	$recipient_first_name . ' ' . $recipient_last_name,
	BASEURL . $recipient_profile_image,
	BASEURL . $requested_profile_image,
	$requested_by_first_name . ' ' . $requested_by_last_name,
	$card_name,
	BASEURL . $card_picture,
	$bcn,
	BASEURL
);

$raw_message = file_get_contents('../../email-templates/card_added/index.html');

$message = str_replace($tokens, $replacements, $raw_message);
//$msg = "$requested_by_first_name $requested_by_last_name has added your Business card '$card_name' with BCN $bcn. Log in at <a href='" . $config->getBaseUrl() . "'>cardition.com</a> to see this card.<br><br>Cardition Staff";
$mailer = new Mailer();
$mail_result = $mailer->send_mail("info@cardition.com", "Cardition", $recipient_email, $subject, $message);
/*
 * sending notification to app
 */
//$to = "c3wR2U31nY0:APA91bGKdHn0PHn4CnuMsMdaVDJcccNAP61Jle2AXWWJBXR_DL1vYTLe545xlK04HOlCcu35YG7UMysxUO6VtovYBwNYNbkhy--VzEpz23bG8RUC7B3neKx2ZLh2JGaQTwpgEONE-uzN";
$title = "Card added";
$msg = "$requested_by_first_name $requested_by_last_name has added your Business card '$card_name' with BCN $bcn.";
//mail('webintenerife@gmail.com', 'sending push', $recipient_reg_id . ' <br>' .$message);
sendPushPicture($recipient_reg_id, $title, $msg, $card_id, $bcn, $user_requested_by);
