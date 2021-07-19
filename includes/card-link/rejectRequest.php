<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once('../../session_setup.php');
$request_id = $_REQUEST['requestId'];
create_cookie('rejectRequest', $request_id);
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	header('Location: ../../index.php');
}else{
	header('Location: card-request.php');
}
