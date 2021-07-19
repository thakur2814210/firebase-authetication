<?php

	//session_start();

	include_once('model.php');
	include_once('../../utilities/request_result.php');

	$newResult = new Result();

	echo json_encode($newResult);
