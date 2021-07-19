<?php
require_once(__DIR__.'/../session_setup.php');
require_once('absolute_database_config.php');
if (!isset($_SESSION[ 'card' ][ 'default' ]))
{
	$_SESSION[ 'card' ][ 'default' ] = true;
	$default_card_exists = false;
	$query = "SELECT card_name FROM card WHERE user_id='".$_SESSION['user_id']. "'";
	$result = mysqli_query($conn, $query);
	if ($result)
	{
		while ($row = mysqli_fetch_assoc($result))
		{
			if ($row['card_name'] === 'Default card')
			{
				$default_card_exists = true;
			}
		}
	}
	if ($default_card_exists)
	{
		echo "exists";
		exit;
	}
	else
	{
		$_SESSION[ 'card' ][ 'default' ] = true;
		echo "done";
		exit;
	}
}
else
{
	echo "creating";
	exit;
}
