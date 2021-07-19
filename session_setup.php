<?php
date_default_timezone_set("Europe/London");
define('BASEURL', 'http://www.cardition.com/');
//define('BASEURL', 'http://bcfoldercrazy.webintenerife.com/');

// server should keep session data for AT LEAST 12 hour
//ini_set('session.gc_maxlifetime', 43200);//12 h (60x60x12)
//ini_set('session.gc_maxlifetime', 3600);//1 h
ini_set('session.gc_maxlifetime', 10800);//3 h
// each client should remember their session id for EXACTLY 3 hour
session_set_cookie_params(3600,"/");
ini_set('session.cookie_lifetime', 0);
ini_set('session.gc-maxlifetime', 0);

session_start();

// THE LIFE OF THE COOKIE
//define('REMEMBER', 60*60*24*7); // ONE WEEK IN SECONDS
define('REMEMBER', 60*60); // ONE HOUR IN SECONDS
function create_cookie($cookie_name, $cookie_value)
{
    // CONSTRUCT A "REMEMBER ME" COOKIE WITH THE UNIQUE USER KEY
    $cookie_expires = time() + date('Z') + REMEMBER;
    $cookie_path    = '/';
    $cookie_domain  = NULL;
    $cookie_secure  = FALSE;
    $cookie_http    = FALSE; // HIDE COOKIE FROM JAVASCRIPT (PHP 5.2+)

    // SEE http://php.net/manual/en/function.setcookie.php
    setcookie
    ( $cookie_name
    , $cookie_value
    , $cookie_expires
    , $cookie_path
    , $cookie_domain
    , $cookie_secure
    , $cookie_http
    )
    ;
}
// set time-out period (in seconds)
//$inactive = 3600;

//// check to see if $_SESSION["timeout"] is set
//if ( isset( $_SESSION[ "timeout" ] ) )
//{
//	// calculate the session's "time to live"
//	$sessionTTL = time() - $_SESSION[ "timeout" ];
//	if ( $sessionTTL > $inactive )
//	{
//		session_destroy();
//		header( "Location: includes/auth/logout.php" );
//	}
//}
//
//$_SESSION[ "timeout" ] = time();

//$timeout_duration = 3600;

//if ( isset($_SESSION["LAST_ACTIVITY"]) && ($time - $_SESSION["LAST_ACTIVITY"]) > $timeout_duration )
//{
//	session_destroy();
//	header( "Location: includes/auth/logout.php" );
//}
function get_card_picture_name($card_id, $ext, $back = false) {
	$mt = microtime();
	$a = explode('.', $mt);
	$unique_time = str_replace(' ', '', end($a));
	if (!$back){
		$card_picture_name = $card_id . "." . $unique_time . '-front.' . $ext;
	}else{
		$card_picture_name = $card_id . "." . $unique_time . '-back.' . $ext;
	}
	return $card_picture_name;
}