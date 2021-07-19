<?php
/*
 * RETRIEVES USER CONTACTS TO SHARE CARDS IN FOLDER
 * IS CALLED BY MG_CONTACTS.JS ON CLICK EVENT BOUND TO '#share_selected_cards' BUTTON
 * THEN RETURNS THE LIST OF CONTACTS AND THE POPUP IS OPENED TO SELECT CONTACTS TO SHARE CARDS WITH
 */
error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );
require_once('../session_setup.php');
require_once 'database_config.php';
require_once __DIR__ . '/../ChromePhp.php';
$user_id = $_SESSION[ 'user_id' ];
define( 'PROFILE_PIC_PATH', getenv( $_SERVER[ 'DOCUMENT_ROOT' ] ) . '/includes/profile/uploads/' );
function in_array_r( $needle, $haystack, $strict = false )
{
	foreach ( $haystack as $item )
	{
		if ( ($strict ? $item === $needle : $item == $needle) || (is_array( $item ) && in_array_r( $needle, $item, $strict )) )
		{
			return true;
		}
	}
	return false;
}
$contacts = array();
$contact = array();
$card_ids = explode( ',', $_POST[ 'card_ids' ] );
$one_card_only = false;
if ( count( $card_ids ) == 1 )
{
	$one_card_only = true;
	$query = "SELECT DISTINCT user_id FROM card WHERE card_id ='{$card_ids[ 0 ]}'";
	ChromePhp::log( 'query is ' . $query );
	$result = mysqli_query( $conn, $query );
	ChromePhp::log( 'error: ' . mysqli_error( $conn ) );
	$row = mysqli_fetch_row( $result );
	$card_user_id = $row[ 0 ];
}

/*
 * check if among cards there is someone whose owner didn't 
 * set permissions for sharing
 */
$response = array();
$bcns = array();
foreach ( $card_ids as $c_id )
{
	$query = "SELECT share_among_users FROM personal_card_setting WHERE card_id='$c_id'";
	$result = mysqli_query( $conn, $query );
	$row = mysqli_fetch_row( $result );
	$share_among_users = $row[ 0 ];
	if ($share_among_users == 0)
	{
		$query = "SELECT assigned_id FROM card  WHERE card_id='$c_id'";
		$result = mysqli_query( $conn, $query );
		$row = mysqli_fetch_row( $result );
		$bcns[] = $row[ 0 ];
	}
}
if (count($bcns) > 0)
{
	$response['outcome'] = 'no_share';
	$response['bcns'] = $bcns;
	echo json_encode($response);
	exit;
}

$conditions = "join card c on c.card_id = cc.card_id
        join card_data cd on c.card_id = cd.card_id
        join user u on c.user_id = u.user_id
        join card_contact ccr on ccr.card_id = cc.card_id
        where cc.user_id = '$user_id' AND (c.card_type='Personal' OR c.card_type='Corporate') group by c.card_id";

$query = "SELECT c.card_name, c.card_id, c.card_type,c.distributed_brand,c.category,c.sub_category,c.assigned_id,cd.canvas_front,cd.canvas_back,cd.links_front,cd.links_back,cd.landscape,u.user_id, u.first_name,u.last_name,u.profile_image,u.company_name,u.department_name,u.position,u.phone,u.mobile,u.email_address,u.title,u.website_link,cc.rating as your_rating,round(sum(cc.rating) / count(cc.rating), 1) as average_rating FROM card_contact cc $conditions";
ChromePhp::log( 'query is ' . $query );
$result = mysqli_query( $conn, $query );
ChromePhp::log( 'error: ' . mysqli_error( $conn ) );
while ( $row = mysqli_fetch_assoc( $result ) )
{
	$contact[ 'user_id' ] = $row[ 'user_id' ];
	$contact[ 'user_name' ] = $row[ 'first_name' ] . ' ' . $row[ 'last_name' ];
	if ( isset( $row[ 'profile_image' ] ) )
	{
		if ( file_exists( PROFILE_PIC_PATH . $row[ 'profile_image' ] ) )
		{
			$contact[ 'profile_image' ] = $row[ 'profile_image' ];
		}
		else
		{
			$contact[ 'profile_image' ] = 'assets/img/def_avatar.gif';
		}
	}
	else
	{
		$contact[ 'profile_image' ] = 'assets/img/def_avatar.gif';
	}
	$contact[ 'email_address' ] = $row[ 'email_address' ];
//	ChromePhp::log($contact);
	if ( !in_array_r( $contact[ 'user_id' ], $contacts ) )
	{
		if ( $one_card_only )
		{
			if ( $card_user_id != $contact[ 'user_id' ] && $contact[ 'user_id' ] != $user_id )
			{
				array_push( $contacts, $contact );
			}
		}
		else
		{
			array_push( $contacts, $contact );
		}
	}
}


//foreach ( $card_ids as $c_id )
//{
//	$query = "SELECT DISTINCT user_id, assigned_id FROM card WHERE card_id ='$c_id'";
//	ChromePhp::log( 'query is ' . $query );
//	$result = mysqli_query( $conn, $query );
//	ChromePhp::log( 'error: ' . mysqli_error( $conn ) );
//	$row = mysqli_fetch_row( $result );
//	$card_user_id = $row[ 0 ];
//	$card_bcn = $row[ 1 ];
//	$query = "SELECT first_name, last_name, profile_image, email_address FROM user WHERE user_id='$card_user_id' LIMIT 1";
//	ChromePhp::log( 'query is ' . $query );
//	$result = mysqli_query( $conn, $query );
//	ChromePhp::log( 'error: ' . mysqli_error( $conn ) );
//	if ( $result )
//	{
//		while ( $row = mysqli_fetch_assoc( $result ) )
//		{
//			$contact[ 'user_id' ] = $card_user_id;
//			$contact[ 'user_name' ] = $row[ 'first_name' ] . ' ' . $row[ 'last_name' ];
//			if ( isset( $row[ 'profile_image' ] ) )
//			{
//				if ( file_exists( PROFILE_PIC_PATH . $row[ 'profile_image' ] ) )
//				{
//					$contact[ 'profile_image' ] = $row[ 'profile_image' ];
//				}
//				else
//				{
//					$contact[ 'profile_image' ] = 'assets/img/def_avatar.gif';
//				}
//			}
//			else
//			{
//				$contact[ 'profile_image' ] = 'assets/img/def_avatar.gif';
//			}
//			$contact[ 'email_address' ] = $row[ 'email_address' ];
//		}
////		ChromePhp::log('in array ' .print_r($contacts));
////		ChromePhp::log('is there? '.$contact['user_id']);
//		if ( !in_array_r( $contact[ 'user_id' ], $contacts ) )
//		{
//			array_push( $contacts, $contact );
//		}
//	}//end while $row
//}//end foreach $card_ids
//print_r('$contacts');
//print_r($contacts);
$response = array();
$response['outcome'] = 'contacts';
$response['contacts'] = $contacts;
echo json_encode( $response );
