<?php
require_once('../../session_setup.php');
if (isset($_SESSION['card']))
{
	include "../create-business-card/cancel_card_creation.php";
}
unset($_SESSION['user_id']);
session_destroy();
header('Location: ../../index.php');