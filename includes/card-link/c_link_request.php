<?php
/*
 * SENDS A REQUEST FOR A PRIVATE CARD
 */
require_once '../../session_setup.php';
require_once '../database_config.php';
include_once 'models.php';
include_once '../../utilities/request_result.php';
include_once '../../utilities/mail.class.php';
require_once '../../ChromePhp.php';
require_once '../push_notifications.php';
if (!isset($_SESSION['user_id'])) {
    echo json_encode('expired');
    exit;
}
$request_result = new Result();
$card_id = trim($_REQUEST['card_id']);
$query = "SELECT canvas_front FROM card_data WHERE card_id='$card_id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_row($result);
$card_picture = $row[0];
$user_requested_by = $_SESSION['user_id'];
date_default_timezone_set('Europe/Istanbul');
$card_link = new CardLink(null, $user_requested_by, $card_id, "REQUESTED", new DateTime('NOW'), null);
//TODO: String interpolation should use full obj card_link, not separate properties as params
$query = sprintf("INSERT INTO card_link (user_requested_by,card_id_requested,link_status,date_requested,request_origin) values	('%s','%s','%s',NOW(),'link_request')", $card_link->user_requested_by, $card_link->card_id_requested, $card_link->link_status);
$result = $request_result->handleQuery($conn, $query);
//now we get the id of the card_link just inserted in order to pass it as param in sendPush function instead of using card_id
//$query2 = "SELECT card_link FROM card_link WHERE user_requested_by='$card_link->user_requested_by' AND card_id_requested='$card_link->card_id_requested'";
//$result2 = mysqli_query($conn, $query2);
//$row2 = mysqli_fetch_row($result2);
//$card_link_id = $row2['0'];
$card_link_id = mysqli_insert_id($conn);
mail('webintenerife@gmail.com', 'card link id from site', $card_link_id);
$query = sprintf("SELECT c.user_id, c.assigned_id, u.email_address, u.first_name, u.last_name, u.profile_image, u.reg_id FROM card c JOIN user u ON c.user_id = u.user_id WHERE c.card_id = '%s'", $card_id);
$result = $request_result->handleQuery($conn, $query);
//ChromePhp::log($query . ' fucked error: '.mysqli_error($conn));
$email_address = "";
while ($row = $result->fetch_object()) {
    $bcn = $row->assigned_id;
    $email_address = $row->email_address;
    $recipient_user_id = $row->user_id;
    $recipient_first_name = $row->first_name;
    $recipient_last_name = $row->last_name;
    $recipient_profile_image = $row->profile_image;
    $recipient_reg_id = $row->reg_id;
}
/* add event to notifications table */
//$date = new DateTime( 'NOW' );
//$date_string = $date->format( 'Y-m-d H:i:s' );
$date_string = urldecode($_POST['date_string']);
ChromePhp::log('$recipient_user_id is ' . $recipient_user_id . ' and his email is ' . $email_address);
$query = "INSERT INTO notifications (notification_type, card_id, request_id, event, event_date, recipient_user_id, active_user_id) VALUES ('link_requested', '$card_id', '$card_link_id', 'sent you a link request','$date_string', '$recipient_user_id', '$user_requested_by')";
ChromePhp::log('insert query is ' . $query);
mysqli_query($conn, $query);
//ChromePhp::log( 'error: ' . mysqli_error( $conn ) );
/* limiting table to 20 records per user */
$query = "SELECT COUNT(*) as total FROM notifications WHERE recipient_user_id='$recipient_user_id'";
//ChromePhp::log( 'insert query is ' . $query );
$result = mysqli_query($conn, $query);
//ChromePhp::log( 'error: ' . mysqli_error( $conn ) );
if ($result) {
    $row = mysqli_fetch_assoc($result);
    ChromePhp::log('total is ' . $row['total']);
    if ($row['total'] > 20) {
        $query = "DELETE FROM `notifications` WHERE recipient_user_id='$recipient_user_id' AND id NOT IN (SELECT id FROM (SELECT id FROM `notifications` ORDER BY id DESC	LIMIT 20) foo )";
//        ChromePhp::log( 'insert query is ' . $query );
        $result = mysqli_query($conn, $query);
//        ChromePhp::log( 'error: ' . mysqli_error( $conn ) );
    }
}

$query = "SELECT first_name, last_name, profile_image FROM user WHERE user_id='$user_requested_by'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_row($result);
$first_name = $row[0];
$last_name = $row[1];
$profile_image = $row[2];

$title = "Cardition - Card linking request";
$target_url_accept = $config->getBaseUrl() . "includes/card-link/approveRequest.php?requestId=$card_link_id'";
$target_url_reject = $config->getBaseUrl() . "includes/card-link/rejectRequest.php?requestId=$card_link_id'";
$tokens = array(
    '{{__RECIPIENT_FULL_NAME__}}',
    '{{__RECIPIENT_PROFILE__}}',
    '{{__AUTHOR_PROFILE__}}',
    '{{__AUTHOR_FULL_NAME__}}',
    '{{__CARD_PICTURE__}}',
    '{{__TARGET_URL_ACCEPT__}}',
    '{{__TARGET_URL_REJECT__}}',
    '{{__BCN__}}',
);
$replacements = array(
    $recipient_first_name . ' ' . $recipient_last_name,
    BASEURL . $recipient_profile_image,
    BASEURL . $profile_image,
    $first_name . ' ' . $last_name,
    BASEURL . $card_picture,
    $target_url_accept,
    $target_url_reject,
    $bcn,
);

$raw_message = file_get_contents('../../email-templates/requests_send/index.html');

$message = str_replace($tokens, $replacements, $raw_message);

//$message = "Hi, $recipient_name. You received a new card link request by $first_name $last_name! Visit <a href='" . $config->getBaseUrl() . "'>cardition.com</a> to approve it!";
//$message = "Hi, $recipient_first_name. You received a new card link request by $first_name $last_name! <a href='" . $config->getBaseUrl() . "includes/card-link/approveRequest.php?requestId=$card_link_id'>Approve</a> <a href='" . $config->getBaseUrl() . "includes/card-link/rejectRequest.php?requestId=$card_link_id'>Reject</a>";
$message_app = "Hi, $recipient_first_name. You received a new card link request by $first_name $last_name!";
$mailer = new Mailer();
$mail_result = $mailer->send_mail(
    "info@cardition.com", "Cardition", $email_address, $title, $message
);
$request_result->handleSentMail($mail_result);
echo json_encode($request_result);
sendPushRequest($recipient_reg_id, $title, $message_app, $card_link_id, $user_requested_by);
