<?php
require_once('../session_setup.php');
//cadd to db
require_once 'absolute_database_config.php';

$bcn = trim( $_REQUEST[ 'bcn' ] );
//echo "Submitted ID: ".$card_id;

//$result = mysqli_query( $conn, "SELECT c.card_id FROM card c WHERE c.user_id != '" . $_SESSION[ 'user_id' ] . "'" );
//if ( $result )
//{
//	$not_your_own = mysqli_fetch_row($result);
//}

//$result = mysqli_query( $conn, "SELECT card_id, assigned_id FROM card WHERE assigned_id = '$card_id'" ); original query
$result = mysqli_query( $conn, "SELECT card_id, user_id FROM card WHERE assigned_id = '$bcn'" ); //mg 18/11/2015: no need to get assigned_id; user_id added to check if the user is the logged in user
if ( $result )
{
	$row = mysqli_fetch_row( $result );
	if ( $row )
	{
		if ($row[1] === $_SESSION['user_id'])
		{
			echo "owned";
		}
		else
		{
		//echo "Retrieved Card: ".$card_exists[0];
			echo $row[ 0 ];
		}
	}
	else
	{
		echo "failed";
	}
}
else
{
	echo "failed";
}
