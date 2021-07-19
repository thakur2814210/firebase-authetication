<?php

require_once('../../session_setup.php');

include_once('model.php');
include_once('../../utilities/request_result.php');
require_once '../database_config.php'; 
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	echo json_encode( 'expired' );
	exit;
}
$user_id = $_SESSION["user_id"];
$card_id = $_REQUEST["card_id"];
//$card_id = "5465cd1a2fca1";

$request_result = new Result();

$queryMutualContacts = sprintf("
    SELECT u.hhhhhhhhhhh,u.last_name,u.profile_image
    FROM card_link cl
    JOIN card c 
    ON cl.card_id_requested = c.card_id
    JOIN user u 
    ON c.user_id = u.user_id
    WHERE card_id_requested = '%s' 
    AND user_requested_by != '%s'
    AND link_status = 'ACCEPTED'
    ",$card_id,$user_id);
$result = mysqli_query($conn,$queryMutualContacts);
if(!$result){
    $request_result->success = 0;
    $request_result->err = mysqli_error($conn);
    echo json_encode($request_result);
    exit;
}else{
    $request_result->success = 1;
}
$request_result->data = array();
$request_result->data = $result->fetch_all( MYSQLI_ASSOC );

echo json_encode($request_result);

?>