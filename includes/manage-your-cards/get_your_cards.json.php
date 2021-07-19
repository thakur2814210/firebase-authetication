<?php

require_once('../../session_setup.php');
include_once('models.php');
include_once('../../utilities/request_result.php');
require_once '../database_config.php';
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	echo json_encode('expired');
	exit;
}
$request_result = new Result();
$request_result->result = array();
$query = mysqli_query( $conn, "select
        c.card_id,
        c.card_name,
        c.card_type,
        c.assigned_id,
        cd.landscape,
        cd.canvas_back,
        cd.canvas_front,
        cd.widgets_front,
        cd.widgets_back,
        cd.links_front,
        cd.links_back,
        u.default_card
        from user u
        inner join card_data cd on u.user_id = cd.user_id
        inner join card c on c.user_id = cd.user_id and c.card_id = cd.card_id
        where u.user_id = '" . $_SESSION[ 'user_id' ] . "'" );
$request_result->result = array();
while ( $row = mysqli_fetch_array( $query ) )
{
	$card = new YourCard(
					$row[ 'card_id' ], $row[ 'card_type' ], $row[ 'card_name' ], $row[ 'canvas_back' ], $row[ 'canvas_front' ], $row[ 'widgets_front' ], $row[ 'widgets_back' ], $row[ 'links_front' ], $row[ 'links_back' ], $row[ 'landscape' ], $row[ 'default_card' ], $row[ 'assigned_id' ], "0" );
	array_push( $request_result->result, $card );
};
echo json_encode( $request_result );
