<?php
require_once('../session_setup.php');
require_once('../utilities/functions.php');
require_once('./absolute_database_config.php');
if (isset($_SESSION['user_id'])) {
	$query = "SELECT n.id, n.event, n.event_date, n.recipient_user_id, n.active_user_id, u.first_name, u.last_name, u.profile_image, u.company_name FROM notifications n LEFT JOIN user u ON u.user_id=n.active_user_id WHERE n.recipient_user_id='" . $_SESSION['user_id'] . "' ORDER BY n.event_date DESC LIMIT 0, 20";
	$result = mysqli_query($conn, $query);

	class Data {

		var $data;

	}

	$resp = new Data();
	$resp->data = array();
	if ($result) {
		while ($row = mysqli_fetch_array($result)) {
			if ($row['profile_image']) {
				$profile_image = $row['profile_image'];
			} else {
				$profile_image = "assets/img/def_avatar.gif";
			}
			$a = array(
				'0' => $profile_image,
				'1' => $row['first_name'],
				'2' => $row['last_name'],
				'3' => (!empty($row['company_name'])) ? $row['company_name'] : '',
				'4' => $row['event'],
				'5' => $row['event_date'],
				'6' => $row['id']
			);
			$o = (object) $a;
			array_push($resp->data, $o);
		}
	}

	echo json_encode(utf8ize($resp));
}else{
	echo json_encode('no_session');
}
