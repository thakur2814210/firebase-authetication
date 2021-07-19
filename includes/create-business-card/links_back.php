<?php

require_once('../../session_setup.php');

// get ajax data
$data = trim( $_REQUEST[ 'stringdata' ] ); 

//cadd to db
require_once '../database_config.php';
//$bcard = mysqli_query($conn, "SELECT * FROM card_data WHERE user_id = '".$_SESSION['user_id']."' AND card_id = '".$_SESSION[ 'card' ][ 'card_id' ]."'");
$update = mysqli_query( $conn, "UPDATE card_data SET links_back = '" . $data . "' WHERE user_id = '" . $_SESSION[ 'user_id' ] . "' AND card_id = '" . $_SESSION[ 'card' ][ 'card_id' ] . "'" );

// convert to joins
$user = mysqli_query( $conn, "SELECT user_id, personal_address_id, company_address_id FROM user WHERE user_id = '" . $_SESSION[ 'user_id' ] . "';" );
$u = mysqli_fetch_row( $user );
$address = mysqli_query( $conn, "SELECT address_id, country_id FROM address WHERE address_id = '" . $u[ 1 ] . "';" );
$a = mysqli_fetch_row( $address );
$country = mysqli_query( $conn, "SELECT country_id, code FROM countries WHERE country_id = '" . $a[ 1 ] . "';" );
$c = mysqli_fetch_row( $country );
// generate code function
//function genCode($existing_bcn)
//{
//	$chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
////	$numbers = '0123456789';
//	$bcn = '';
//	for ( $i = 0; $i < 6; $i++ )
//	{
//		$bcn .= $chars[ rand( 0, strlen( $chars ) - 1 ) ];
//	}
//	if ( in_array( $bcn, $existing_bcn ) )
//	{
//		// regenerate code
//		genCode( $existing_bcn );
//	}
//	else
//	{
//		return $bcn;
//	}
//}
function genCode($conn)
{
	$chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$bcn = '';
	for ( $i = 0; $i < 6; $i++ )
	{
		$bcn .= $chars[ rand( 0, strlen( $chars ) - 1 ) ];
	}
	$query = "SELECT card_id FROM card WHERE assigned_id='$bcn'";
	$result = mysqli_query( $conn, $query );
	if ( $result && mysqli_num_rows( $result ) > 0 )
	{
		// regenerate code
		genCode( $conn );
	}
	else
	{
		return $bcn;
	}
}


if ( !isset( $_SESSION[ 'card' ][ 'bcn' ] ) || $_SESSION[ 'card' ][ 'bcn' ] === '' )
{
	$existing_bcn = array();
	$result = mysqli_query( $conn, "SELECT assigned_id FROM card" );
	while ( $row = mysqli_fetch_array( $result ) )
	{
		$existing_bcn[] = $row[ 'assigned_id' ];
	}
//	$assigned_id = genCode( $c[ 1 ], $existing_bcn );
//	$assigned_id = genCode( 2, 4, $existing_bcn );
//	$assigned_id = genCode( $existing_bcn );
	$assigned_id = genCode( $conn );
}
else
{
	$assigned_id = $_SESSION[ 'card' ][ 'bcn' ];
}
//$assigned_id = genCode( $c[ 1 ] );

// check if code is set
$check_assigned_id = mysqli_query( $conn, "SELECT user_id, card_id, assigned_id FROM card WHERE user_id = '" . $_SESSION[ 'user_id' ] . "' AND card_id = '" . $_SESSION[ 'card' ][ 'card_id' ] . "'" );
$b = mysqli_fetch_row( $check_assigned_id );

if ( $b[ 2 ] == "" || $b[ 2 ] == null )
{
	$update_card = mysqli_query( $conn, "UPDATE card SET assigned_id = '" . $assigned_id . "' WHERE user_id = '" . $_SESSION[ 'user_id' ] . "' AND card_id = '" . $_SESSION[ 'card' ][ 'card_id' ] . "'" );
}

// set as default if no other cards exist for this user
$result = mysqli_query( $conn, "SELECT user_id, default_card FROM user WHERE user_id = '" . $_SESSION[ 'user_id' ] . "'" );
if ( $result )
{
	$first_card = mysqli_fetch_row( $result );

	if ( $first_card[ 1 ] == null || $first_card == "" )
	{
		mysqli_query( $conn, "UPDATE user SET default_card = '" . $_SESSION[ 'card' ][ 'card_id' ] . "' WHERE user_id = '" . $_SESSION[ 'user_id' ] . "'" );
	}
}

// notify
if ( $update )
	echo 'Your Business Card has been updated successfully!';
