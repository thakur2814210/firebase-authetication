<?php

ob_start();
require_once('../../session_setup.php');
require_once('../../ChromePhp.php');
require_once '../absolute_database_config.php';
//$sql =<<<QRY
//SELECT u.user_id, u.first_name, u.last_name, u.personal_address_id, u.personal_country_id,
//a.address_1, a.city, a.country_id, a.post_code
//FROM user u
//JOIN address a ON a.address_id = u.personal_address_id
//WHERE user_id = "{$_SESSION['user_id']}"				
//QRY;
if (isset($_SESSION['user_id'])) {
	$sql = <<<QRY
SELECT title, first_name, last_name, email_address, personal_country_id
FROM user WHERE user_id = "{$_SESSION['user_id']}"				
QRY;
	$result = mysqli_query($conn, $sql);
	$profile_completed = mysqli_fetch_row($result);
	$unfilled_fields = array();
	if ($profile_completed !== false) {
		ChromePhp::log('query ok ' . $sql);
		foreach ($profile_completed as $k => $v) {
			ChromePhp::log("$k is $v");
			if (empty($v)) {
				$unfilled_fields[] = $k;
			}
		}
		if (count($unfilled_fields) === 0) {
			echo "true";
			ChromePhp::log('profile is completed');
		} else {
			echo "false";
			ChromePhp::log('profile is not completed');
		}
	} else {
		ChromePhp::log('query failed: ' . $sql . ' error: ' . mysqli_error($conn));
		echo "error";
	}
}else{
	echo 'no-session';
}