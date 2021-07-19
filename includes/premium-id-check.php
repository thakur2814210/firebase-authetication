<?php

require_once('../session_setup.php');
if ( isset( $_POST[ "premium_id" ] ) )
{

	//cadd to db
	require_once 'a_test_database_config.php';

	//value from registration page
	$assigned_id = $_POST[ "premium_id" ];

	//check in db
	$result = mysqli_query( $conn, "SELECT assigned_id FROM card WHERE assigned_id = '" . $assigned_id . "'" );

	$id_exist = mysqli_num_rows( $result ); //records count
	//if returned value is more than 0, not available
	if ( $id_exist )
	{
		die( '<span id="prem-avail" class="glyphicon glyphicon-remove-sign" style="cursor: default; color:#d2322d !important;:" title="Unavailable"></span>' );
	}
	else
	{
		$_SESSION[ 'prem_id' ] = $assigned_id;
		die( '<span id="prem-avail" class="glyphicon glyphicon-ok-sign" style="cursor: default; color:#5cb85c !important;" title="Available"></span>' );
	}
}
