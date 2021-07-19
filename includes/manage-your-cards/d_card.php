<?php

ob_start();
require_once('../../session_setup.php');
require_once '../database_config.php';
include_once('../../utilities/request_result.php');
include_once('../../utilities/mail.class.php');
require_once('../push_notifications.php');
if (!isset($_SESSION['user_id'])) {
	echo json_encode('expired');
	exit;
}
$request_result = new Result();
//used to record db operations
$mysqli_errors = array();
$mysqli_success = array();
$r = new Result();
/*
 * mg
 * ADDED 09/08/2015
 * get data to send email to all members who have the deleted card in their folder
 */
$card_id = $_GET['card_id'];
$query = sprintf("
	select card_name, assigned_id from card where card_id='%s'
	", $card_id);
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
	$bcn = $row['assigned_id'];
	$card_name = $row['card_name'];
}
$query = "select canvas_front from card_data where card_id='$card_id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_row($result);
$card_picture = $row[0];
$user_id = $_SESSION['user_id'];
$query = sprintf("select first_name, last_name, profile_image from user where user_id='%s'", $user_id);
//echo $query;exit;
//$query = "select first_name, last_name from user, profile_image where user_id='$user_id'";
$result = mysqli_query($conn, $query);
$user_name = '';
while ($row = mysqli_fetch_assoc($result)) {
	$first_name = $row['first_name'];
	$last_name = $row['last_name'];
	$user_name = $row['first_name'] . ' ' . $row['last_name'];
	$author_profile = $row['profile_image'];
	if ($author_profile == '') {
		$author_profile = 'assets/img/def_avatar.gif';
	}
}
//mail('webintenerife@gmail.com', 'deleting card from site', 'author profile is '.$author_profile.' and author name is '. $user_name);
$query2 = sprintf("select distinct "
	. "cc.user_id"
	. ", u2.first_name"
	. ", u2.last_name"
	. ", u2.email_address"
	. ", u2.user_id"
	. ", u2.reg_id"
	. ", u2.profile_image"
	. " from card_contact cc"
	. " inner join user u2 on cc.user_id = u2.user_id and u2.user_id in (select ccc.user_id from card_contact ccc"
	. " where ccc.card_id = '%s' and ccc.user_id != '%s')", $card_id, $user_id);
$result2 = mysqli_query($conn, $query2) or die(mysqli_error($conn));
//$r->data = array();
$date = new DateTime('NOW');
$date_string = $date->format('Y-m-d H:i:s');
while ($row = mysqli_fetch_assoc($result2)) {
	$recipient_user_id = $row['user_id'];
	$recipient_name = $row['first_name'];
	$email = $row['email_address'];
	$recipient_reg_id = $row['reg_id'];
	$recipient_full_name = $row['first_name'] . $row['last_name'];
	$recipient_profile_image = $row['profile_image'];
	if ($recipient_profile_image == '') {
		$recipient_profile_image = 'assets/img/def_avatar.gif';
	}
	/* add event to notifications table */
	$query3 = "INSERT INTO notifications (notification_type, event, event_date, recipient_user_id, active_user_id) VALUES ('card_deleted', 'Card $bcn has been deleted by its owner $first_name $last_name','$date_string', '$recipient_user_id', '$user_id')";
	mysqli_query($conn, $query3);

	/* limiting table to 20 records per user */
	$query4 = "SELECT COUNT(*) as total FROM notifications WHERE recipient_user_id='$recipient_user_id'";
	$result4 = mysqli_query($conn, $query4);
	if ($result4) {
		$row = mysqli_fetch_assoc($result4);
		if ($row['total'] > 20) {
			$query = "DELETE FROM `notifications`	WHERE recipient_user_id='$recipient_user_id' AND id NOT IN (	SELECT id FROM (SELECT id	FROM `notifications` ORDER BY id DESC	LIMIT 20) foo )";
			$result = mysqli_query($conn, $query);
		}
	}

//	$subject = "Business card $bcn has been deleted";
	$subject = "Card deleted";
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
		BASEURL . 'view_card_details.php?bcn=" . $bcn . "&card_id=" . $card_id . "'
	);

	$raw_message = file_get_contents('../../email-templates/card_deleted/index.html');

	$message = str_replace($tokens, $replacements, $raw_message);

//	$message = "Hi, $recipient_name. $first_name $last_name has deleted the Business card $card_name with BCN $bcn.<br>This card will no longer appear in your folder.<br><br>Cardition Staff";
//	mail('marqus.gs@gmail.com', $row['user_id'], $row['email_address']);
	$mailer = new Mailer();
	$mail_result = $mailer->send_mail
			(
			"info@cardition.com", "Cardition", $email, $subject, $message
	);
	sendPush($recipient_reg_id, $subject, $message, $card_id, $user_id);
}
//exit;
/*
 * ./ mg send mail code
 */
//TODO: DELETE ALL DATA RELATED TO CARD (DB entires & Files on disk)
$queries = array(
	sprintf("DELETE FROM subscription WHERE card_id = '%s'", $_GET['card_id']),
	sprintf("DELETE FROM card_comment WHERE card_id = '%s'", $_GET['card_id']),
	sprintf("DELETE FROM card_contact WHERE card_id = '%s'", $_GET['card_id']),
	sprintf("DELETE FROM card_data WHERE card_id = '%s'", $_GET['card_id']),
	sprintf("DELETE FROM card_folder WHERE card_id = '%s'", $_GET['card_id']),
	sprintf("DELETE FROM card_link WHERE card_id_requested = '%s'", $_GET['card_id']),
	sprintf("DELETE FROM personal_card_setting WHERE card_id = '%s'", $_GET['card_id']),
	sprintf("DELETE FROM professional_card_setting WHERE card_id = '%s'", $_GET['card_id']),
	sprintf("DELETE FROM card WHERE card_id = '%s'", $_GET['card_id'])
);
foreach ($queries as $query) {
	$result = mysqli_query($conn, $query);
	if (!$result) {
		array_push($mysqli_errors, array(
			'mysqli_error' => mysqli_error($conn),
			'executed_query' => $query));
	} else {
		array_push($mysqli_success, $query);
	}
}
$export_absolute_path = $_SERVER['DOCUMENT_ROOT'] . 'export';
$bg_absolute_path = $_SERVER['DOCUMENT_ROOT'] . 'create-business-card/uploads/background/';
$logo_absolute_path = $_SERVER['DOCUMENT_ROOT'] . 'create-business-card/uploads/background/';
$front_image = $_GET['card_id'] . "-front.png";
$back_image = $_GET['card_id'] . "-back.png";
$background_image1 = $_GET['card_id'] . ".png";
$background_image2 = $_GET['card_id'] . ".jpg";
$logo_image1 = $_GET['card_id'] . ".png";
$logo_image2 = $_GET['card_id'] . ".jpg";
if (file_exists($export_absolute_path . $front_image)) {
	unlink($export_absolute_path . $front_image);
}
if (file_exists($export_absolute_path . $back_image)) {
	unlink($export_absolute_path . $back_image);
}
if (file_exists($bg_absolute_path . $background_image1)) {
	unlink($bg_absolute_path . $background_image1);
}
if (file_exists($bg_absolute_path . $background_image2)) {
	unlink($bg_absolute_path . $background_image2);
}
if (file_exists($logo_absolute_path . $logo_image1)) {
	unlink($logo_absolute_path . $logo_image1);
}
if (file_exists($logo_absolute_path . $logo_image2)) {
	unlink($logo_absolute_path . $logo_image2);
}


// set as default if no other cards exist for this user
$query = sprintf("select default_card, c.card_id from user u
				left join card c on c.user_id = u.user_id
				where u.user_id = '%s'
				limit 1;", $_SESSION['user_id']);
$result2 = mysqli_query($conn, $query);
if (!$result2) {
	array_push($mysqli_errors, array(
		'mysqli_error' => mysqli_error($conn),
		'executed_query' => $query));
} else {
	while ($row = mysqli_fetch_array($result2)) {
		if ($row['card_id'] == null) {
			$query = "UPDATE user SET default_card = NULL WHERE user_id = '" . $_SESSION['user_id'] . "'";
			mysqli_query($conn, $query);
			$result3 = mysqli_query($conn, $query);
			if (!$result3) {
				array_push($mysqli_errors, array(
					'mysqli_error' => mysqli_error($conn),
					'executed_query' => $query));
			} else {
				array_push($mysqli_success, $query);
			}
		} else {
			if ($row['default_card'] == $_GET['card_id']) {
				$query = sprintf("UPDATE user SET default_card = '%s' WHERE user_id = '%s'", $row['card_id'], $_SESSION['user_id']);
				mysqli_query($conn, $query);
				$result4 = mysqli_query($conn, $query);
				if (!$result4) {
					array_push($mysqli_errors, array(
						'mysqli_error' => mysqli_error($conn),
						'executed_query' => $query));
				} else {
					array_push($mysqli_success, $query);
				}
			}
		}
	}
	$errors_length = count($mysqli_errors);
	if ($errors_length > 0) {
		$request_result->success = 0;
	} else {
		$request_result->success = 1;
	}
	$request_result->errors = $mysqli_errors;
	$request_result->successes = $mysqli_success;
	echo json_encode('success');
}
ob_end_flush();
