<?php

require_once('../database_config.php');
require_once('../../cardition_api/functions.php');
include_once('../../utilities/request_result.php');
include_once('../../utilities/mail.class.php');

$request_result = new Result();

$sent_token = trim($_GET["token"]);
$password = trim($_REQUEST['password']);
$encrypted_password = hash_plain_password($password);

$user_id;

$query = "SELECT * FROM email_token WHERE token = '".$sent_token."'"." AND type='reset'";
$result = $request_result->handleQuery($conn,$query);
$token_exists = mysqli_num_rows($result);

if ($token_exists != null && $token_exists != 0) {
	while ($row = mysqli_fetch_array($result)) {
		$user_id = $row["user_id"];
	}
	$query = sprintf("UPDATE user SET password = '%s' WHERE user_id='%s'",$encrypted_password, $user_id);
	$result = $request_result->handleQuery($conn, $query);
	
	$query = sprintf("DELETE FROM email_token WHERE token = '%s' AND type='reset'", $sent_token);
	$result = $request_result->handleQuery($conn, $query);

	$request_result->token_exists = true;
	$request_result->reset_successful = true;
} else {
	$request_result->token_exists = false;
	$request_result->reset_successful = false;
}

echo json_encode($request_result);