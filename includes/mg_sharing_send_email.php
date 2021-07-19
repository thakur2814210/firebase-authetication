<?php
/*
 * SHARE USER OWN CARD WITH HIS CONTACTS FROM GMAIL, OUTLOOK OR OTHER
 */
require_once('../session_setup.php');
require_once 'database_config.php';
include_once('../utilities/mail.class.php');
//$site_url = "https://www.cardition.com/";
$site_url = "http://www.cardition.com/";
$user_id = $_SESSION[ 'user_id' ];
$email_addresses = explode( ',', $_POST[ 'email_addresses' ] );
$card_id = $_POST[ 'card_id' ];
$card_name = $_POST [ 'card_name' ];
$card_bcn = $_POST[ 'card_bcn' ];
$card_owner = $_POST[ 'card_owner' ];
$card_image = $_POST[ 'card_image' ];
$card_image_back = $_POST[ 'card_image_back' ];
$img_flip_class = $_POST[ 'img_flip_class' ];

echo "success";
$query = "SELECT email_address FROM user WHERE user_id='$user_id'";
$result = mysqli_query( $conn, $query );
$row = mysqli_fetch_row( $result );
$from = $row[ 0 ];
//$query = "SELECT MAX(id) FROM shared_cards";
//$result = mysqli_query( $conn, $query );
//if ( !$result )
//{
//	$last_id = 1;
//}
//else
//{
//	$row = mysqli_fetch_row( $result );
//	$last_id = $row[ 0 ] + 1;
//}
	$query2 = "INSERT INTO shared_cards (user_id, card_id, card_name, card_bcn, card_owner, card_image, card_image_back, img_flip_class, shared_with) VALUES ('$user_id', '$card_id', '$card_name', '$card_bcn', '$card_owner', '$card_image', '$card_image_back', '$img_flip_class', '" . implode(',', $email_addresses) ."') ON DUPLICATE KEY UPDATE card_name='$card_name', card_bcn='$card_bcn', card_owner='$card_owner', shared_with='" . implode(',', $email_addresses) ."'";
$result2 = mysqli_query($conn, $query2);
$last_id = mysqli_insert_id($conn);

$subject = "$card_owner wants share a card!";
$msg = 'Hello.';
$msg .= "<p>I would like to share my card \"$card_name\" (\"$card_bcn\") with you.</p>";
$msg .= "<p><br /></p>";
$msg .= "<p>You will be able to save this in your Cardition account by clicking on this link: <a href='".$site_url ."guest_card_viewer.php?viewcard=$last_id'>Cardition card viewer</a></p>";
$msg .= "<p><br/></p>";
$msg .= "<p>If you are not yet a member, please register at a href='https://www.cardition.com'>cardition.com</a></p>";
$msg .= "<p style='text-align: center;'><img src='".$site_url ."/$card_image' alt='$card_name' /></p>";
$msg .= "<p><br /></p>";
$msg .= "<p>Thank you</p>";
$msg .= "<p>Best regards</p>";
$msg .= "<p>$card_owner</p>";
$mailer = new Mailer();
$mail_result = $mailer->send_mail_group(
				$from, $card_owner, $email_addresses, $subject, $msg
);
//echo 'success';
