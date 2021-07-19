<?php
	
	require_once('../../session_setup.php');

	require_once '../database_config.php';
    //Not sure if this is being used, it is creating errors on admin report.
	//include_once('models.php');
	include_once('../../utilities/request_result.php');

	$request_result = new Result();

    $mysqli_errors = array();
    $mysqli_success = array();

	$user_id = $_SESSION['user_id'];
	
	$query = 
	sprintf(
		"select u.last_name, u.first_name, u.email_address, u.date_created, c.card_type
		from user u
		inner join card c on u.user_id = c.user_id
		order by date_created desc");

	//=============QUERY=============
	$result = mysqli_query($conn,$query);
	if(!$result){
		array_push($mysqli_errors,array('mysqli_error' => mysqli_error($conn), 'executed_query' => $query));
	}else{
		array_push($mysqli_success,$query);
	}

	class Data{
		var $data;
	}

	$resp = new Data();
	
	$resp->data = array();
	while ($row = mysqli_fetch_array($result)) {

        if ($row['card_type'] == 'Professional') {
            $row['card_type'] = 'Service';
        }

		$a = array(
            '0' => $row['card_type'],
            '1' => $row['last_name'],
            '2' => $row['first_name'],
			'3' => $row['email_address'], 
			'4' => $row['date_created']
			);
		
		$o = (object)$a;
		array_push($resp->data,$o);
	}
	//=============QUERY=============

	$request_result->errors = $mysqli_errors;
	$request_result->successes = $mysqli_success;

	if(count($mysqli_errors) > 0){
		$request_result->success = 0;
	}else{
		$request_result->success = 1;
	}

	echo json_encode($resp);
