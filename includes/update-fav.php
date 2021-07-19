<?php
require_once('../session_setup.php');
//cadd to db
require_once 'database_config.php';
require_once '../ChromePhp.php';
//check if user has a main card
function get_user_main_card($user_id, $conn){
    $query = "select card_id from card where user_id='$user_id' and main_card=1";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        return false;
    }
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_row($result);
        return $row[0];
    } else {
        return false;
    }
}
$card_id = trim($_REQUEST['stringdata']);
$user_id = $_SESSION['user_id'];
$result = mysqli_query($conn, "UPDATE user SET default_card = '$card_id' WHERE user_id = '$user_id'");
$old_main_card_id = get_user_main_card($user_id, $conn);
Chromephp::log('$old_main_card_id');
Chromephp::log($old_main_card_id);
if ($old_main_card_id != false) {
    $q_update_main_card = "update card set main_card = (case"
        . " when card_id= '$old_main_card_id'  then '0'"
        . " when card_id='$card_id' then '1' end)";
        Chromephp::log('q_update_main_card');
        Chromephp::log($q_update_main_card);
    mysqli_query($conn, $q_update_main_card);
    if ($result) {
        if (mysqli_affected_rows($conn) > 0) {
            $result->success = 1;
        } else {
            $result->success = 0;
        }
    } else {
        $result->success = 0;
    }
} else {
    $q_update_main_card = "update card set main_card='1' where card_id='$card_id'";
    Chromephp::log('q_update_main_card2');
    Chromephp::log($q_update_main_card);
mysqli_query($conn, $q_update_main_card);
    if ($result) {
        if (mysqli_affected_rows($conn) > 0) {
			$result->success = 1;
        } else {
            $result->success = 0;
        }
    } else {
        $result->success = 0;
    }
}