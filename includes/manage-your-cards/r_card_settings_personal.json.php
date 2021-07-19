<?php

class Result
{
	var $code;
	var $card_id;
	var $message;
	var $data;
	var $err;
}
require_once('../../session_setup.php');
$card_id = trim( $_GET[ 'card_id' ] );
//    $card_type = trim($_GET['card_type']); 
require_once '../database_config.php';

$select_query = "";

//    if($card_type == "Personal" || $card_type == "Corporate"){
//$select_query = "SELECT user_id,card_id,global_search,share_among_users,seen_in_user_folder,requires_reciprocity,need_approval FROM personal_card_setting WHERE card_id = '".$card_id."'";
$select_query = "SELECT user_id,card_id,global_search,seen_in_user_folder,requires_reciprocity,need_approval, share_among_users FROM personal_card_setting WHERE card_id = '" . $card_id . "'";
//    }else if($card_type == "Professional" || $card_type == "Product"){
//    	//$select_query = "SELECT visible_pp_search,share_among_users,allow_rating FROM professional_card_setting WHERE card_id = '".$card_id."'";
//        $select_query = "SELECT visible_pp_search,allow_rating FROM professional_card_setting WHERE card_id = '".$card_id."'";
//    }else{
//    	//TODO: return error (unsure about best way atm)
//    }


$result = mysqli_query( $conn, $select_query );

$newResult = new Result();
$newResult->card_id = $card_id;

if ( !$result )
{
	$newResult->err = mysqli_error( $conn );
}
$newResult->data = array();
//    if($card_type == "Personal" || $card_type == "Corporate"){
while ( $row = mysqli_fetch_array( $result ) )
{
	$a = array(
			'card_id' => $row[ 'card_id' ],
			'global_search' => $row[ 'global_search' ],
			'need_approval' => $row[ 'need_approval' ],
			'requires_reciprocity' => $row[ "requires_reciprocity" ],
			'seen_in_user_folder' => $row[ 'seen_in_user_folder' ],
			'share_among_users' => $row[ "share_among_users" ],
			'user_id' => $row[ 'user_id' ]
	);

	$o = ( object ) $a;

	array_push( $newResult->data, $o );
}
//    }else{
//        while ($row = mysqli_fetch_array($result)) {
//            $a = array(
//                'visible_pp_search' => $row['visible_pp_search'], 
//                'allow_rating' => $row['allow_rating']//,
//                //'share_among_users' => $row["share_among_users"]
//                );
//            
//            $o = (object)$a;
//            
//            array_push( $newResult->data,$o);
//        }
//    }

$count = mysqli_affected_rows( $conn );
if ( $count > 0 )
{
	$newResult->code = 1;
	$newResult->message = "The card settings were successfully retrieved from the db";
}
else
{
	$newResult->code = 0;
	$newResult->message = "The card settings were unsuccessfully retrieved from the db";
}

echo json_encode( $newResult );
?>
