<?php

$email_address = filter_input(INPUT_POST, 'email_address', FILTER_SANITIZE_EMAIL);
// $email_address = $_REQUEST['e'];
require_once '../absolute_database_config.php';
include_once('../../utilities/request_result.php');
include_once '../../utilities/mail.class.php';
include_once '../../config/config.php';
$request_result = new Result();
$query = "SELECT user_id, first_name, last_name FROM user WHERE email_address = '".$email_address."'";
$result = $request_result->handleQuery($conn, $query);
$row = mysqli_fetch_row($result);
$user_id = $row[0];
$first_name = $row[1];
$last_name = $row[2];

$query = "SELECT token from email_token WHERE user_id='$user_id' AND type='registration'";
$result = $request_result->handleQuery($conn, $query);

$token = '';
if ($result && mysqli_num_rows($result) > 0) {
	$row = mysqli_fetch_row($result);
	$token = $row[0];
} else {
	// somehow the token doesn't exist (or the previous query failed), so create a new one
	$token = sha1($email_address . time() . rand(0, 1000000));
	$query = sprintf("INSERT INTO email_token (user_id, token, type) VALUES ('%s','%s','%s')", $user_id, $token, 'registration');
	$result = $request_result->handleQuery($conn, $query);
}

$mailer = new Mailer();
$url = $config->getBaseUrl().'confirm_registration.php?token='.$token;
$msg = <<<MSG
    Hi $first_name $last_name,<br> this email has been sent to you because you have registered at cardition.com. Please click the link below or copy and paste it in the address bar of your favorite browser to complete your registration:<br><br><a href="$url">$url</a> <br><br>Thank you<br><br>Kind regards<br>cardition.com Support Team
MSG;
$mail_result = $mailer->send_mail(
    'info@cardition.com', 'Cardition', $email_address, 'Cardition - Welcome, please confirm your email', "Please, click the link below to complete your registration <a href='$url'>confirm email address</a> <br><br>Thank you<br><br>Kind regards<br><a href='https://www.cardition.com'>Cardition.com</a> team"
);
$request_result->handleSentMail($mail_result);
echo json_encode($request_result);