<?php
/*
 * LISTS ALL LINK REQUESTS
 */
require_once('../../session_setup.php');
require_once '../database_config.php';
include_once('models.php');
include_once('../../utilities/request_result.php');
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	echo json_encode( 'expired' );
	exit;
}
$request_result = new Result();
$mysqli_errors = array();
$mysqli_success = array();
$user_id = $_SESSION[ 'user_id' ];
$type = trim( $_GET[ "report_type" ] );
$last_prop = "";
$query = "";
if ( $type == "new_link_requests" )
{
	$last_prop = "date_requested";
	$query = sprintf(
					"SELECT u.last_name,u.first_name,u.profile_image,u.company_name,cl.date_requested,cl.card_link as card_id,c.card_name,c.card_type, u.email_address
			FROM card_link cl
			JOIN card c
			ON cl.card_id_requested=c.card_id
			JOIN user u
			ON u.user_id=cl.user_requested_by
			WHERE cl.link_status = 'REQUESTED'
            AND c.user_id = '%s'", $user_id );
}
elseif ( $type == "holders_of_your_card" )
{
	$last_prop = "date_accepted";
	$query = sprintf(
					"SELECT u.user_id,u.last_name,u.first_name,u.profile_image,u.company_name,cl.date_accepted,cl.card_link as card_id,c.card_name,c.card_type
			FROM card_link cl
            JOIN card c
            ON cl.card_id_requested=c.card_id
			JOIN user u
            ON u.user_id=cl.user_requested_by
			WHERE cl.link_status = 'ACCEPTED'
            AND c.user_id = '%s'", $user_id
	);
}
elseif ( $type == "my_link_requests" )
{
	$last_prop = "date_requested";
	$query = sprintf(
					"SELECT u.last_name,u.first_name,u.profile_image,u.company_name,cl.date_requested,c.card_name,c.card_type,cl.card_link as card_id
			FROM card_link cl
			JOIN card c
			ON cl.card_id_requested=c.card_id
			JOIN user u
			ON u.user_id=c.user_id
			WHERE cl.link_status = 'REQUESTED'
            AND cl.user_requested_by = '%s'", $user_id );
}
else
{
	//TODO: return appropriate error here
}
//=============QUERY=============
$result = mysqli_query( $conn, $query );
if ( !$result )
{
	array_push( $mysqli_errors, array(
			'mysqli_error' => mysqli_error( $conn ),
			'executed_query' => $query ) );
}
else
{
	array_push( $mysqli_success, $query );
}
class Data
{
	var $data;
}
$resp = new Data();
$resp->data = array();
while ( $row = mysqli_fetch_array( $result ) )
{
	if ( $row[ 'card_type' ] == 'Professional' )
	{
		$row[ 'card_type' ] = 'Service';
	}
	if ( $row[ 'profile_image' ] )
	{
		$profile_image = $row[ 'profile_image' ];
	}
	else
	{
		$profile_image = "assets/img/def_avatar.gif";
	}
	if ( $type == "holders_of_your_card" )
	{
		$a = array(
				'0' => $profile_image,
				'1' => $row[ 'last_name' ],
				'2' => $row[ 'first_name' ],
				'3' => (!empty( $row[ 'company_name' ] )) ? $row[ 'company_name' ] : '',
				'4' => $row[ 'card_name' ],
				'5' => $row[ 'card_type' ],
				'6' => $row[ $last_prop ],
				'7' => $row[ 'card_id' ]
		);
	}
	else if ( $type == "new_link_requests" )
	{
//				array for original table
		$a = array(
				'0' => $profile_image,
				'1' => $row[ 'last_name' ],
				'2' => $row[ 'first_name' ],
				'3' => (!empty( $row[ 'company_name' ] )) ? $row[ 'company_name' ] : '',
				'4' => $row[ 'card_name' ],
				'5' => $row[ 'card_type' ],
				'6' => $row[ $last_prop ],
				'7' => $row[ 'card_id' ],
				'8' => $row[ 'email_address' ]
		);
	}
	else if ( $type == "my_link_requests" )
	{
		$a = array(
				'0' => $profile_image,
				'1' => $row[ 'last_name' ],
				'2' => $row[ 'first_name' ],
				'3' => (!empty( $row[ 'company_name' ] )) ? $row[ 'company_name' ] : '',
				'4' => $row[ 'card_name' ],
				'5' => $row[ 'card_type' ],
				'6' => $row[ $last_prop ],
				'7' => $row[ 'card_id' ],
//				'8' => $row[ 'email_address' ]
		);
	}
	$o = ( object ) $a;
	array_push( $resp->data, $o );
}
//=============QUERY=============
$request_result->errors = $mysqli_errors;
$request_result->successes = $mysqli_success;
if ( count( $mysqli_errors ) > 0 )
{
	$request_result->success = 0;
}
else
{
	$request_result->success = 1;
}
echo json_encode( $resp );
