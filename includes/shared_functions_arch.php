<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once(__DIR__ . '/shared_classes.php');

function GetSetting($card_id, $card_type, $setting_description, $conn) {
//	mail('webintenerife@gmail.com', 'GetSetting', $card_id . '   ' .$card_type.'     '.$setting_description);
	$settings_to_return = new Settings();
	$search_term = "";
	$query = "";
	$skip = FALSE;
	if ($setting_description == "visible_in_search") {
		if ($card_type == "Personal" || $card_type == "Corporate") {
			$search_term = "global_search";
			$query = sprintf("SELECT * FROM personal_card_setting WHERE card_id = '%s'", $card_id);
		} elseif ($card_type == "Professional" || $card_type == "Service" || $card_type == "Product") {
			$search_term = "visible_pp_search";
			$query = sprintf("SELECT * FROM professional_card_setting WHERE card_id = '%s'", $card_id);
		}
	} elseif ($setting_description == "mutual_contacts") {
		if ($card_type == "Personal" || $card_type == "Corporate") {
			$search_term = "seen_in_user_folder";
			$query = sprintf("SELECT * FROM personal_card_setting WHERE card_id = '%s'", $card_id);
		} elseif ($card_type == "Professional" || $card_type == "Service" || $card_type == "Product") {
			$query = sprintf("SELECT * FROM professional_card_setting WHERE card_id = '%s'", $card_id);
			$settings_to_return->requested_setting = TRUE;
			$skip = TRUE;
		}
	}
//	if (!empty($query)){
	$card_type_result = mysqli_query($conn, $query);
	//print_r($card_type_result);
	if (!$card_type_result) {
		//panic
	}
	$settings_to_return->settings_type = $card_type;
	$global_search = "";
	while ($type_row = mysqli_fetch_array($card_type_result)) {
		if (!$skip) {
			$global_search = $type_row[$search_term];
		}
		$settings_to_return->settings_blob = $type_row;
	}
	if ($global_search == "1") {
		$settings_to_return->requested_setting = TRUE;
	} elseif ($global_search == "0") {
		//all good, just not allowed to show in search
		$settings_to_return->requested_setting = FALSE;
	} else {
		//panic!! (golang style)
		//throw exception
	}
	return $settings_to_return;
//}
}
