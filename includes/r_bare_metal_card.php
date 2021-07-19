<?php

	require_once('../../session_setup.php');

	include_once('../../utilities/request_result.php');

	$request_result = new Result();
	
	$query = sprintf(
        "SELECT c.card_id,c.card_name,cl.link_status,cl.request_origin ,u.first_name
        FROM card c 
        LEFT JOIN card_link cl 
        ON c.card_id = cl.card_id_requested
        JOIN user u
        ON c.user_id=u.user_id
        WHERE u.user_id != '%s'",
        $_SESSION["user_id"]);

	require_once '../database_config.php';
	$result = mysqli_query($conn,$query);
	if(!$result){
		$request_result->meta = $query;
		$request_result->success = 0;
		$request_result->err = mysqli_error($conn);
		echo json_encode($request_result);
	}else{
		$request_result->success = 1;
	}

	$request_result->data = $result->fetch_all( MYSQLI_ASSOC );

	$count = mysqli_affected_rows($conn);
	if($count>0){
		$request_result->success = 1;
		$request_result->meta = "Data successfully retrieved from the db";
	}
	else{
		$request_result->success = 0;
		$request_result->meta = "Data unsuccessfully retrieved from the db";
	}

	echo json_encode($request_result);