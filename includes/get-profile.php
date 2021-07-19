<?php

require_once('../session_setup.php');

//cadd to db
require_once 'a_test_database_config.php';

$json_array = array();

//which table to select
$card_id = trim($_REQUEST['stringdata']);

$result = mysqli_query($conn, "SELECT card_id, user_id FROM card WHERE card_id = '".$card_id."'");
if ($result) {
    $user_id = mysqli_fetch_row($result);

    $result = mysqli_query($conn, "SELECT user_id, profile_image, last_name, first_name, mobile, email_Address, website_link FROM user WHERE user_id = '".$user_id[1]."'");

    if ($result) {
        $user = mysqli_fetch_row($result);

        // JSON
        $json_array['profile_image'] = $user[1];
        $json_array['last_name'] = $user[2];
        $json_array['first_name'] = $user[3];
        $json_array['mobile'] = $user[4];
        $json_array['email_address'] = $user[5];
        $json_array['website_link'] = $user[6];

        echo json_encode($json_array);
    }
}
