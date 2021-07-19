<?php
//require_once('../../session_setup.php');
//unset( $_SESSION[ 'admin' ] );
//require_once '../absolute_database_config.php';
//include_once('../../utilities/request_result.php');
//$request_result = new Result();
//$email_address = trim( $_REQUEST[ 'email_address' ] );
//$password = trim( $_REQUEST[ 'password' ] );
////TODO: Revisit sql injection attacks protection, possibly invisite login/register php module/lib
//// $email_address = stripslashes($email_address);
//// $password = stripslashes($password);
//// $email_address = mysql_real_escape_string($email_address);
//// $password = mysql_real_escape_string($password);
//$encrypt_password = md5( $password );
////TODO: Report Connection error to relevant role and log to determined place
////TODO: Rate limiting
//$query = sprintf( "SELECT user_id, first_name, email_address, verified, admin FROM user WHERE email_address = '%s' AND password = '%s'", $email_address, $encrypt_password );
//$result = $request_result->handleQuery( $conn, $query );
//session_unset();
//$login_success = false;
//while ( $row = mysqli_fetch_array( $result ) )
//{
//	$_SESSION[ 'user_id' ] = $row[ 'user_id' ];
//	$_SESSION[ 'user_email' ] = $row[ 'email_address' ];
//	$request_result->first_name = $row[ 'first_name' ];
//	$request_result->verified = $row[ 'verified' ];
//	$_SESSION[ 'admin' ] = $row[ 'admin' ];
//	$company_address_id = mysqli_query( $conn, "insert into user_log (user_id) values ('" . $_SESSION[ 'user_id' ] . "')" );
//	/*CHECK IF PROFILE IS COMPLETE*/
//	$sql = <<<QRY
//SELECT title, first_name, last_name, email_address, personal_country_id
//FROM user WHERE user_id = "{$_SESSION[ 'user_id' ]}"				
//QRY;
//	$result = mysqli_query( $conn, $sql );
//	$profile_result = mysqli_fetch_row( $result );
//	$unfilled_fields = array();
//	if ( $profile_result !== false )
//	{
//		foreach ( $profile_result as $k => $v )
//		{
//			if ( empty( $v ) )
//			{
//				$unfilled_fields[] = $k;
//			}
//		}
//		if ( count( $unfilled_fields ) === 0 )
//		{
//			$profile_completed = "true";
//		}
//		else
//		{
//			$profile_completed = "false";
//		}
//	}
//	else
//	{
////		ChromePhp::log( 'query failed: ' . $sql . ' error: ' . mysqli_error( $conn ) );
////		echo "error";
//	}
//
//	$request_result->login_success = true;
//	$request_result->profile_completed = $profile_completed;
//}
//echo json_encode( $request_result );


error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once('../../session_setup.php');
require_once('../../ChromePhp.php');
unset($_SESSION['admin']);
require_once '../absolute_database_config.php';
include_once('../../utilities/request_result.php');
require_once('../../utilities/functions.php');
$request_result = new Result();
$email_address = trim($_REQUEST['email_address']);
$password = trim($_REQUEST['password']);
//TODO: Revisit sql injection attacks protection, possibly invisite login/register php module/lib
// $email_address = stripslashes($email_address);
// $password = stripslashes($password);
// $email_address = mysql_real_escape_string($email_address);
// $password = mysql_real_escape_string($password);
//TODO: Report Connection error to relevant role and log to determined place
//TODO: Rate limiting
$query = sprintf("SELECT user_id, password, first_name, email_address, verified, admin FROM user u WHERE email_address = '%s'", $email_address);
$result = $request_result->handleQuery($conn, $query);
session_unset();
$request_result->login_success = false;
if (isset($_COOKIE['sharedCard'])) {
	$sharedCard = $_COOKIE['sharedCard'];
	unset($_COOKIE['sharedCard']);
	setcookie('sharedCard', null, -1, '/');
}
if (isset($_COOKIE['rejectRequest'])) {
	$rejectRequest = $_COOKIE['rejectRequest'];
	unset($_COOKIE['rejectRequest']);
	setcookie('rejectRequest', null, -1, '/');
}
if (isset($_COOKIE['approveRequest'])) {
	$rejectRequest = $_COOKIE['approveRequest'];
	unset($_COOKIE['approveRequest']);
	setcookie('approveRequest', null, -1, '/');
}
if (isset($_COOKIE['addCard'])) {
//	mail('webintenerife@gmail.com', 'cookie set', $_COOKIE['addCard']);
	$addCard = $_COOKIE['addCard'];
	unset($_COOKIE['addCard']);
	setcookie('addCard', null, -1, '/');
} else {
//	mail('webintenerife@gmail.com', 'cookie not set', '');
}
if (isset($_COOKIE['viewCardDetails'])) {
	$viewCardDetails = $_COOKIE['viewCardDetails'];
	unset($_COOKIE['viewCardDetails']);
	setcookie('viewCardDetails', null, -1, '/');
}
while ($row = mysqli_fetch_array($result)) {
	if (!check_password_hash($password, $row['password'])) {
		continue;
	}

	$request_result->first_name = $row['first_name'];
	$request_result->verified = $row['verified'];

    if($request_result->verified == 1){
		$_SESSION['user_id'] = $row['user_id'];
		$_SESSION['user_email'] = $row['email_address'];
		$_SESSION['admin'] = $row['admin'];
    }

	$company_address_id = mysqli_query($conn, "insert into user_log (user_id) values ('" . $row['user_id'] . "')");
	/* CHECK IF PROFILE IS COMPLETE */
	$sql = "SELECT title, first_name, last_name, email_address, personal_country_id FROM user WHERE user_id = '" . $row['user_id'] . "'";
	$result = mysqli_query($conn, $sql);
	$profile_result = mysqli_fetch_row($result);
	$unfilled_fields = array();
	if ($profile_result !== false) {
		foreach ($profile_result as $k => $v) {
			if (empty($v)) {
				$unfilled_fields[] = $k;
			}
		}
		if (count($unfilled_fields) === 0) {
			$profile_completed = "true";
		} else {
			$profile_completed = "false";
		}
	}
	$request_result->login_success = true;
	$request_result->profile_completed = $profile_completed;
	if (isset($sharedCard)) {
		$request_result->sharedCard = $sharedCard;
	} else {
		$request_result->sharedCard = '';
	}
	if (isset($rejectRequest)) {
		$request_result->rejectRequest = $rejectRequest;
	} else {
		$request_result->rejectRequest = '';
	}
	if (isset($approveRequest)) {
		$request_result->approveRequest = $approveRequest;
	} else {
		$request_result->approveRequest = '';
	}
	if (isset($viewCardDetails)) {
		$request_result->viewCardDetails = $viewCardDetails;
	} else {
		$request_result->viewCardDetails = '';
	}
	if (isset($addCard)) {
		$request_result->addCard = $addCard;
	} else {
		$request_result->addCard = '';
	}
}
echo json_encode($request_result);
