<?php
include_once('manage-your-cards/models.php');
include_once('../utilities/request_result.php');
require_once 'database_config.php';
$user_id = $_GET['user_id'];
$request_result = new Result();
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
        where u.user_id = '$user_id'" );
$data = array();
while ( $row = mysqli_fetch_array( $query ) )
{
	$card = array(
		"card_id" => $row[ 'card_id' ],
		"card_type" => $row[ 'card_type' ],
		"card_name" => $row[ 'card_name' ],
		"canvas_back" => $row[ 'canvas_back' ],
		"canvas_front" => $row[ 'canvas_front' ],
		"widgets_front" => $row[ 'widgets_front' ],
		"widgets_back" => $row[ 'widgets_back' ],
		"links_front" =>	$row[ 'links_front' ],
		"links_back" =>	$row[ 'links_back' ],
		"landscape" => $row[ 'landscape' ],
		"default_card" => $row[ 'default_card' ],
		"assigned_id" => $row[ 'assigned_id' ]
	);
//	$card = new YourCard(
//					$row[ 'card_id' ], $row[ 'card_type' ], $row[ 'card_name' ], $row[ 'canvas_back' ], $row[ 'canvas_front' ], $row[ 'widgets_front' ], $row[ 'widgets_back' ], $row[ 'links_front' ], $row[ 'links_back' ], $row[ 'landscape' ], $row[ 'default_card' ], $row[ 'assigned_id' ] );
	array_push( $data, $card );
};
echo  json_encode($data) ;
