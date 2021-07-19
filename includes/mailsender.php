<?php
ob_start();
error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );
include_once(__DIR__ . '/../utilities/mail.class.php');
if ( isset( $_POST[ 'submit' ] ) )
{
//	$mailer = new Mailer();
//	$mail_result = $mailer->send_mail
//					(
//					"", "marqus.gs@gmail.com", "test", "Prova sender not Root User"
//	);
}
ob_end_flush();
?>
<!--<form method='post'>
	<input type="submit" name='submit' />
</form>-->
