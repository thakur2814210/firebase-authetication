<?php
require_once('../session_setup.php');
require_once('absolute_database_config.php');
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	echo json_encode('expired');
	exit;
}
$cards_string = filter_input(INPUT_POST, 'cards');
$cards = explode(',', $cards_string);
$email_addresses = array();
foreach ($cards as $c)
{
	$query = "SELECT u.email_address FROM user u WHERE user_id=(SELECT user_id FROM card WHERE card_id='$c')";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_row($result);
	if (!in_array( $row[0], $email_addresses ))
	{
		$email_addresses[] = $row[0];
	}
}
echo implode(';', $email_addresses);
