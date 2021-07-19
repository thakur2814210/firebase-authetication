<?php
	require_once 'includes/absolute_database_config.php';
	$result = mysqli_query($conn, "SELECT user_id, personal_country_id FROM user WHERE user_id = '".$_SESSION['user_id']."'");
	$user_profile_complete = mysqli_fetch_row($result);
