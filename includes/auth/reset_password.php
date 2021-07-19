<?php

require_once '../database_config.php';
include_once('../../utilities/request_result.php');
include_once('../../utilities/mail.class.php');

$request_result = new Result();

$email_address = trim($_REQUEST['email_address']);
$user_id;

$query = "SELECT * FROM user WHERE email_address = '".$email_address."'";
$result = $request_result->handleQuery($conn,$query);
$email_exist = mysqli_num_rows($result);

while ($row = mysqli_fetch_array($result)) {
	$user_id = $row["user_id"];
}

if ($email_exist != null && $email_exist != 0) {
	$request_result->email_exists = true;

	$token = sha1($email_address.time().rand(0, 1000000));
	$query = sprintf("INSERT INTO email_token (user_id, token, type) VALUES ('%s','%s','%s')",
		$user_id,
		$token,
		'reset'
	);
	$result = $request_result->handleQuery($conn, $query);

	$mailer = new Mailer();
	$url = $config->getBaseUrl()."reset_password.php?token=".$token;
	$mail_result = $mailer->send_mail(
		"info@cardition.com", "Cardition", $email_address,
		"Cardition - Password reset",
		"To reset your password, just follow the following link ".$url
	);
	$request_result->handleSentMail($mail_result);

} else {
	$request_result->email_exists = false;
}

echo json_encode($request_result);
