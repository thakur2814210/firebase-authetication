<?php
require_once('../../session_setup.php');
unset( $_SESSION[ 'admin' ] );
require_once '../database_config.php';
include_once('../../utilities/request_result.php');
$wants_news = $_POST['wants_news'];
$query = "UPDATE user SET wants_news='$wants_news' WHERE user_id='".$_SESSION['user_id']."'";
mysqli_query($conn, $query);
