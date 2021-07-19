<?php

require_once('../../session_setup.php');
require_once('../../utilities/functions.php');

//cadd to db
require_once '../database_config.php';
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	echo 'expired';
	exit;
}
// get ajax data
$password = trim($_REQUEST['password']);
$encrypt_password = hash_plain_password($password);

$update = mysqli_query($conn, 
"UPDATE user 
SET  password = '".$encrypt_password."'       
WHERE user_id = '".$_SESSION['user_id']."'");

if ($update) {
	echo 'success';
} else {
	echo 'failed';
}