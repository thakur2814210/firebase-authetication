<?php

include_once('../utilities/request_result.php');
include_once('../utilities/mail.class.php');

$user_name = $_POST['name'];
$user_email = $_POST['email'];
$user_message = $_POST['message'];

$mailer = new Mailer();
$mail_result = $mailer->send_mail( 
				"$user_email", '',  "info@cardition.com", "Message from $user_name", "$user_message"
);
//$request_result->handleSentMail( $mail_result );
echo json_encode($mail_result);