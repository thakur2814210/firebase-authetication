<?php

require_once '/../database_config.php';
include_once('/../../utilities/request_result.php');

$request_result = new Result();

$sent_token = trim($_GET["token"]);
$token_exists = false;

if($sent_token == ""){
	echo "Invalid Token provided";
}else{
	$query = sprintf("SELECT * FROM email_token WHERE token='%s' AND type='%s'",$sent_token,"registration");
	$result = $request_result->handleQuery($conn,$query);
	$token_exists = mysqli_num_rows($result);

	if ($token_exists != null && $token_exists != 0) {
		$request_result->token_exists = true;
		$token_exists = true;

//		$query = sprintf("UPDATE user SET verified = 1 WHERE user_id='%s'",$user_id);
//		$request_result->handleQuery($conn,$query);
		
		$query = sprintf("DELETE FROM email_token WHERE token='%s' AND type='%s'",$sent_token,"registration");
		$request_result->handleQuery($conn,$query);

	}else{
		$request_result->token_exists = false;
	}
}