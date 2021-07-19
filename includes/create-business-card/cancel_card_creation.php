<?php

require_once(__DIR__ . '/../../session_setup.php');
require_once(__DIR__ . '/../absolute_database_config.php');
$export_relative_path = '../../export/';
$bg_relative_path = 'uploads/background/';
$logo_relative_path = 'uploads/';
$export_absolute_path = $_SERVER['DOCUMENT_ROOT'] . '/export/';
$background_absolute_path = $_SERVER['DOCUMENT_ROOT'] . '/includes/create-business-card/uploads/background/';
$logo_absolute_path = $_SERVER['DOCUMENT_ROOT'] . '/includes/create-business-card/uploads/';
if (!isset($_SESSION['card']['card_id'])) {

} elseif (isset($_SESSION['card']['edit_mode'])) {
	/*
	 * if card is not saved I have to delete
	 */
	$new_card_id = $_SESSION['card']['card_id'];
	$old_card_id = $_SESSION['card']['card_id'] . '*';
	$query = "select canvas_front, canvas_back from card_data where card_id='$new_card_id'";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_row($result);
	$canvas_front = $row[0];
	$canvas_back = $row[1];
	$cf_arr = explode('.', $canvas_front);
	$old_card_front = $cf_arr[0] . '*' . $cf_arr[1];
	$cb_arr = explode('.', $canvas_back);
	$old_card_back = $cb_arr[0] . '*' . $cb_arr[1];
	/*
	 * if no edit has been made or if the edit process was interrupted before
	 * user clicked Preview&Save then we don't do anything: everything remains as it was
	 */
	if (!file_exists($export_absolute_path . $old_card_front)) {
		unset($_SESSION['card']);
	} else {
		/*
		 * we process editing results
		 */
		/*
		 * delete new card from the DB
		 */
		$query = "DELETE FROM card WHERE card_id='$new_card_id'";
		$result = mysqli_query($conn, $query);
		$query = "DELETE FROM card_data WHERE card_id='$new_card_id'";
		$result = mysqli_query($conn, $query);
		/*
		 * delete new files from the server
		 */
		if (file_exists($export_absolute_path . $canvas_front)) {
			unlink($export_absolute_path . $canvas_front);
		}
		if (file_exists($export_absolute_path . $canvas_back)) {
			unlink($export_absolute_path . $canvas_back);
		}
		/*
		 * rename old files to drop the asterisk
		 */
		$query = "select canvas_front, canvas_back from card_data where card_id='$new_card_id'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_row($result);
		$canvas_front = $row[0];
		$canvas_back = $row[1];
		$cf_arr = explode('.', $canvas_front);
		$old_card_front = $cf_arr[0] . '*' . $cf_arr[1];
		$cb_arr = explode('.', $canvas_back);
		$old_card_back = $cb_arr[0] . '*' . $cb_arr[1];
		rename($export_absolute_path . $old_card_front, $export_absolute_path . $canvas_front);
		rename($export_absolute_path . $old_card_back, $export_absolute_path . $canvas_back);
		if (file_exists($background_absolute_path . $old_card_id . '.png')) {
			unlink($background_absolute_path . $new_card_id . '.png');
			rename($background_absolute_path . $old_card_id . '*.png', $background_absolute_path . $new_card_id . '.png');
		}
		if (file_exists($logo_absolute_path . $old_card_id . '.png')) {
			unlink($logo_absolute_path . $new_card_id . '.png');
			rename($logo_absolute_path . $old_card_id . '*.png', $logo_absolute_path . $new_card_id . '.png');
		}
		/*
		 * update card and card_data to drop asterisk
		 */
		$query = "UPDATE card SET card_id='$new_card_id' WHERE card_id='$old_card_id'";
		$result = mysqli_query($conn, $query);
		$query = "UPDATE card_data SET card_id='$new_card_id', canvas_back='export/$new_card_id-back.png', canvas_front='export/$new_card_id-front.png' WHERE card_id='$old_card_id'";
		$result = mysqli_query($conn, $query);

		unset($_SESSION['card']);
	}
} else {
	$front_image = $_SESSION['card']['card_id'] . "-front.png";
	$back_image = $_SESSION['card']['card_id'] . "-back.png";
	$background_image1 = $_SESSION['card']['card_id'] . "-front." . $_SESSION['card']['front_extension'];
	$background_image2 = $_SESSION['card']['card_id'] . "-back." . $_SESSION['card']['back_extension'];
	$logo_image1 = $_SESSION['card']['card_id'] . ".png";
	$logo_image2 = $_SESSION['card']['card_id'] . ".jpg";
	$queries = array(
		sprintf("DELETE FROM subscription WHERE card_id = '%s'", $_SESSION['card']['card_id']),
		sprintf("DELETE FROM card_comment WHERE card_id = '%s'", $_SESSION['card']['card_id']),
		sprintf("DELETE FROM card_contact WHERE card_id = '%s'", $_SESSION['card']['card_id']),
		sprintf("DELETE FROM card_data WHERE card_id = '%s'", $_SESSION['card']['card_id']),
		sprintf("DELETE FROM card_folder WHERE card_id = '%s'", $_SESSION['card']['card_id']),
		sprintf("DELETE FROM card_link WHERE card_id_requested = '%s'", $_SESSION['card']['card_id']),
		sprintf("DELETE FROM personal_card_setting WHERE card_id = '%s'", $_SESSION['card']['card_id']),
		sprintf("DELETE FROM professional_card_setting WHERE card_id = '%s'", $_SESSION['card']['card_id']),
		sprintf("DELETE FROM card WHERE card_id = '%s'", $_SESSION['card']['card_id'])
	);
	foreach ($queries as $query) {
		$result = mysqli_query($conn, $query);
//		if ( !$result )
//		{
//			array_push( $mysqli_errors, array(
//					'mysqli_error' => mysqli_error( $conn ),
//					'executed_query' => $query ) );
//			exit;
//		}
//		else
//		{
//			array_push( $mysqli_success, $query );
//		}
	}
	if (file_exists($export_absolute_path . $front_image)) {
		unlink($export_absolute_path . $front_image);
	}
	if (file_exists($export_absolute_path . $back_image)) {
		unlink($export_absolute_path . $back_image);
	}
	if (file_exists($background_absolute_path . $background_image1)) {
		unlink($background_absolute_path . $background_image1);
	}
	if (file_exists($background_absolute_path . $background_image2)) {
		unlink($background_absolute_path . $background_image2);
	}
	if (file_exists($logo_absolute_path . $logo_image1)) {
		unlink($logo_absolute_path . $logo_image1);
	}
	if (file_exists($logo_absolute_path . $logo_image2)) {
		unlink($logo_absolute_path . $logo_image2);
	}
	unset($_SESSION['card']);
}
//echo $_SESSION[ 'card' ][ 'card_id' ];
