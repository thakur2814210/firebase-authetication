<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once('../../session_setup.php');
$card_id = $_REQUEST['cardId'];
create_cookie('addCard', $card_id);
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	header('Location: ../../index.php');
}else{
	header('Location: ../../dashboard.php');
}