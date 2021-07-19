<?php

    require_once '../../session_setup.php';

    require_once '../database_config.php';
    //Not sure if this is being used, it is creating errors on admin report.
    //include_once('models.php');
    include_once '../../utilities/request_result.php';
    include_once '../../utilities/functions.php';

    $request_result = new Result();

    $mysqli_errors = array();
    $mysqli_success = array();

    $user_id = $_SESSION['user_id'];

    $query =
    sprintf(
'select u.last_name, u.first_name, u.email_address, u.date_created, (select count(c.card_id) from card c where u.user_id=c.user_id) as card_number from user u order by date_created desc ');

    //=============QUERY=============
    $result = mysqli_query($conn, $query);

    if (!$result) {
        array_push($mysqli_errors, array('mysqli_error' => mysqli_error($conn), 'executed_query' => $query));
    } else {
        array_push($mysqli_success, $query);
    }

    $resp = [];
    $resp['data'] = [];
    
    while ($row = mysqli_fetch_array($result)) {
        $a = [
            '0' => $row['last_name'],
            '1' => $row['first_name'],
            '2' => $row['email_address'],
            '3' => $row['card_number'],
            '4' => $row['date_created'],
            ];

        array_push($resp['data'], $a);
    }
    //=============QUERY=============

    $request_result->errors = $mysqli_errors;
    $request_result->successes = $mysqli_success;

    if (count($mysqli_errors) > 0) {
        $request_result->success = 0;
    } else {
        $request_result->success = 1;
    }

    echo safe_json_encode($resp);
