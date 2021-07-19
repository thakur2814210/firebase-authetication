<?php

require_once('../../session_setup.php');

// get ajax data
$data = trim( $_REQUEST[ 'stringdata' ] );

//cadd to db
require_once '../database_config.php';
$bcard = mysqli_query( $conn, "SELECT * FROM card_data WHERE user_id = '" . $_SESSION[ 'user_id' ] . "' AND card_id = '" . $_SESSION[ 'card' ][ 'card_id' ] . "'" );
$update = mysqli_query( $conn, "UPDATE card_data SET links_front = '" . $data . "' WHERE user_id = '" . $_SESSION[ 'user_id' ] . "' AND card_id = '" . $_SESSION[ 'card' ][ 'card_id' ] . "'" );

// notify
if ( $update )
	echo 'Your Business Card has been updated successfully!';