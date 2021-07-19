<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once('../session_setup.php');
require_once 'absolute_database_config.php';
$card_id = $_POST['card_id'];
$query = "SELECT assigned_id FROM card WHERE card_id='$card_id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_row($result);
echo $row[0];
