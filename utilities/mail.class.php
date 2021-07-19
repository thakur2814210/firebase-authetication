<?php

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
//date_default_timezone_set( 'Etc/UTC' );
date_default_timezone_set( 'Europe/London' );
include_once __DIR__ .'/../ChromePhp.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require  __DIR__ . '/../vendor/autoload.php';

class Mailer
{
	var $mail;
	public function __construct()
	{
		$dotenv = new Dotenv\Dotenv(__DIR__ . '/../.');
		$dotenv->load();

		$this->mail = new PHPMailer;
		$this->mail->isSMTP();
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		// 4 
		$this->mail->SMTPDebug = 4;
		//Ask for HTML-friendly debug output
		$this->mail->Debugoutput = 'error_log';
		//Set the hostname of the mail server
		$this->mail->Host = $_ENV['MAIL_HOST'];
		$this->mail->SMTPSecure = 'ssl';
		//Set the SMTP port number - likely to be 25, 465 or 587
		$this->mail->Port = 465;
		//Whether to use SMTP authentication
		$this->mail->SMTPAuth = true;
		//Username to use for SMTP authentication
		$this->mail->Username = $_ENV['MAIL_USERNAME'];
		//Password to use for SMTP authentication
		$this->mail->Password = $_ENV['MAIL_PASSWORD'];
		$this->mail->IsHTML( true );
	}
	public function send_mail( $from, $fromName, $to, $subject, $body )
	{
		if (empty($from))
		{
			$from = $this->mail->Username;
		}
		try
		{
			$this->mail->Subject = $subject;
//		$this->mail->setFrom($this->mail->Username, "Cardition");
			$this->mail->setFrom( $from , $fromName );
			$this->mail->addAddress( $to, "" );
			$this->mail->Body = $body;
			$success = $this->mail->send();
			ChromePhp::log('from mail.class: to is '.$to);
			if ( !$success )
			{
				ChromePhp::log($this->mail->ErrorInfo);
				return array(
						'state' => 'error',
						'error_info' => "error message ".$this->mail->ErrorInfo );
			}
			else
			{
				return array(
						'state' => 'success' );
			}
		}
		catch ( phpmailerException $e )
		{
			ChromePhp::log('catch 1 '.$e->errorMessage());
			echo $e->errorMessage();
		}
		catch ( Exception $e )
		{
			ChromePhp::log('catch 2 '.$e->getMessage());
			echo "getMessage gives ".$e->getMessage(); //Boring error messages from anything else!
		}
	}
	public function send_mail_group( $from, $fromName, $to, $subject, $body )
	{
		if (empty($from))
		{
			$from = $this->mail->Username;
		}
		if (!is_array( $to ))
		{
			ChromePhp::log('to is not an array');
			exit;
		}
		try
		{
			$this->mail->Subject = $subject;
//		$this->mail->setFrom($this->mail->Username, "Cardition");
			$this->mail->setFrom( $from , $fromName );
			foreach ($to as $rec)
			{
			$this->mail->AddBCC( $rec, "" );
			}
//			$this->mail->addAddress( $to, "" );
			$this->mail->Body = $body;
			$success = $this->mail->send();
			ChromePhp::log('from mail.class: to is '.$to);
			if ( !$success )
			{
				ChromePhp::log($this->mail->ErrorInfo);
				return array(
						'state' => 'error',
						'error_info' => "error message ".$this->mail->ErrorInfo );
			}
			else
			{
				return array(
						'state' => 'success' );
			}
		}
		catch ( phpmailerException $e )
		{
			ChromePhp::log('catch 1 '.$e->errorMessage());
			echo $e->errorMessage();
		}
		catch ( Exception $e )
		{
			ChromePhp::log('catch 2 '.$e->getMessage());
			echo "getMessage gives ".$e->getMessage(); //Boring error messages from anything else!
		}
	}
	
}