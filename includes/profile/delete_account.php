<?php
//ob_start();
//error_reporting( E_ALL );
//ini_set( 'display_errors', 'On' );
require_once('../../session_setup.php');
require_once '../absolute_database_config.php';
define( 'CARD_PIC_PATH', getenv( $_SERVER[ 'DOCUMENT_ROOT' ] ) . '/' );
define( 'PROFILE_PIC_PATH', getenv( $_SERVER[ 'DOCUMENT_ROOT' ] ) . '/includes/profile/uploads/' );
define( 'LOGO_PIC_PATH', getenv( $_SERVER[ 'DOCUMENT_ROOT' ] ) . '/includes/create-business-card/uploads/' );
define( 'BG_PIC_PATH', getenv( $_SERVER[ 'DOCUMENT_ROOT' ] ) . '/includes/create-business-card/uploads/background/' );
/*
 * WORKFLOW
 * 1 - get user id from session
 * 2 - get all card_id of the user from card
 * 3 - get personal_address_id from user table
 * 4 - get all card images path from card_data (in export folder)
 * 5 - get all images related to the user from includes/create-business-card/uploads/ and includes/create-business-card/uploads/background
 * 6 - froeach card delete from subsciption
 * 									delete from personal_card_setting
 * 									delete from professional_card_setting
 * 									delete from card_comment where card_id
 * 7 - delete from card_data
 * 								 card_link (user_requested_by)
 * 								 card_comment
 * 								 card_folder
 * 								 card
 * 								 folder
 * 								 notification where recipient_user_id
 * 								 notification where active_user_id
 * 								 notification_settings where user_id
 * 8 - foreach card image unlink files
 * 9 - foreach user image unlink files
 * 10 - delete from user
 */
$user_id = $_SESSION[ 'user_id' ];
$query = "SELECT email_address, personal_address_id FROM user WHERE user_id='$user_id'";
$result = mysqli_query( $conn, $query );
if ( $result )
{
	$row = mysqli_fetch_row( $result );
	$email_address = $row[ 0 ];
	$personal_address_id = $row[ 1 ];
}
if (!isset($personal_address_id) || empty($personal_address_id))
{
	$personal_address_id = 'NA';
}
$query1 = "SELECT card_id FROM card WHERE user_id='$user_id'";
$result = mysqli_query( $conn, $query1 );
if ( $result )
{
	$card_ids = array();
	while ( $row = mysqli_fetch_assoc( $result ) )
	{
		$card_ids[] = $row[ 'card_id' ];
	}
}
//echo "User id: $user_id<br>";
//echo "Personal address id: $personal_address_id<br>";
//echo "<pre>";
//var_dump( $card_ids );
//echo "</pre>";
$card_images = array();
foreach ( $card_ids as $cid )
{
	$query2 = "SELECT canvas_back, canvas_front FROM card_data WHERE card_id='$cid'";
	$result = mysqli_query( $conn, $query2 );
	if ( $result )
	{
		while ( $row = mysqli_fetch_assoc( $result ) )
		{
			$card_images[] = $row[ 'canvas_back' ];
			$card_images[] = $row[ 'canvas_front' ];
		}
	}
}

//echo "<pre>";
//var_dump( $card_images );
//echo "</pre>";
//echo "user profile is " . PROFILE_PIC_PATH . $user_id . '.png<br>'; // or .jpg
$queries = array();
foreach ( $card_ids as $cid )
{
	$queries[] = "DELETE FROM subscription WHERE card_id='$cid";
	$queries[] = "DELETE FROM personal_card_setting WHERE card_id='$cid";
	$queries[] = "DELETE FROM professional_card_setting WHERE card_id='$cid";
	$queries[] = "DELETE FROM card_comment WHERE card_id='$cid'"; //delete comment received by every user's card
//	echo "card logo is " . LOGO_PIC_PATH . $cid . '.png or .jpg<br>'; // or .jpg
//	echo " card background is " . BG_PIC_PATH . $cid . '.png or .jpg<br>'; // or .jpg
}
//echo "<br><br>CARD QUERIES<br>";
foreach ( $queries as $q )
{
//	echo "query is $q<br>";
	$result = mysqli_query( $conn, $q );
}
$queries = array();
if ($personal_address_id != 'NA')
{
	$queries[] = "DELETE FROM address WHERE personal_address_id='$personal_address_id'";
}
$queries[] = "DELETE FROM card_contact WHERE user_id='$user_id'";
$queries[] = "DELETE FROM card_link WHERE user_requested_by='$user_id'";
$queries[] = "DELETE FROM card_comment WHERE user_id='$user_id'";
$queries[] = "DELETE FROM card_folder WHERE user_id='$user_id'";
$queries[] = "DELETE FROM card_data WHERE user_id='$user_id'";
$queries[] = "DELETE FROM card WHERE user_id='$user_id'";
$queries[] = "DELETE FROM folder WHERE user_id='$user_id'";
$queries[] = "DELETE FROM notifications WHERE recipient_user_id='$user_id'";
$queries[] = "DELETE FROM notifications WHERE active_user_id='$user_id'";
$queries[] = "DELETE FROM notifications_settings WHERE user_id='$user_id'";
$queries[] = "DELETE FROM user WHERE user_id='$user_id'";
//echo "<br><br>USER QUERIES<br>";
foreach ( $queries as $q )
{
//	echo "query is $q<br>";
	$result = mysqli_query( $conn, $q );
}
echo "$email_address";
