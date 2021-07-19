<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../ChromePhp.php';
require_once('../../session_setup.php');
ob_start();
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	echo 'expired';
	exit;
}
include_once('model.php');
require_once ('../mg_get_card_details.php');
//include_once('../shared/check_card_settings.php');
include_once('../../utilities/request_result.php');
require_once __DIR__ . '/../absolute_database_config.php';
$user_id = $_SESSION[ "user_id" ];
//$search_terms = str_replace(' ', ',',  $_POST[ "term" ]);
$search_terms = "'".str_replace(' ', "','",  $_REQUEST[ "term" ])."'";
//$search_terms = "'".str_replace(' ', "','",  $_GET[ "term" ])."'";
$request_result = new Result();
$term = $_REQUEST["term"];
//$term = $search_terms;

//$request_result->message = $search_term;
//$columns = "c.card_id,c.card_name,c.card_type,c.distributed_brand,c.category,c.sub_category,c.assigned_id,cd.canvas_front,cd.canvas_back,cd.links_front,cd.links_back,cd.landscape,u.first_name,u.last_name,u.profile_image,u.company_name,u.department_name,u.position,u.phone,u.mobile,u.email_address,u.title,u.website_link";
//
//$allCards = "SELECT DISTINCT $columns, round(sum(cc.rating) / count(cc.rating), 1) as average_rating FROM card c "
//	. "LEFT JOIN card_data cd ON c.card_id = cd.card_id "
//	. "LEFT JOIN user u ON c.user_id = u.user_id "
//	. "LEFT JOIN card_contact cc on cc.card_id = c.card_id WHERE (";
//$terms = explode(' ', $term);
//foreach ($terms as $t) {
////	ChromePhp::log("term is ".$t);
//	$allCards .= "c.card_name LIKE '%".$t."%' OR c.distributed_brand LIKE '%".$t."%' OR c.category LIKE '%".$t."%' OR c.sub_category LIKE '%".$t."%' OR u.user_id LIKE '%".$t."%' OR u.first_name LIKE '%".$t."%' OR u.last_name LIKE '%".$t."%' OR u.email_address LIKE '%".$t."%' OR u.website_link LIKE '%".$t."%' OR u.company_name LIKE '%".$t."%' OR u.department_name LIKE '%".$t."%' OR u.position LIKE '%".$t."%' OR u.company_email_address LIKE '%".$t."%' OR u.company_website_link LIKE '%".$t."%' OR ";
//}
//$allCards = substr($allCards, 0, strlen($allCards)-4);
//$allCards .= ") AND c.user_id != '$user_id'";
//
//$allCards .= " group by c.card_id";


$columns = "c.card_id,c.card_name,c.card_type,c.distributed_brand,c.category,c.sub_category,c.assigned_id,cd.canvas_front,cd.canvas_back,cd.links_front,cd.links_back,cd.landscape,cuda.country_id as card_country_id, ctr.country as card_country_name,u.personal_country_id, ctr2.country as personal_country_name,u.first_name,u.last_name,u.profile_image,u.company_name,u.department_name,u.position,u.phone,u.mobile,u.email_address,u.title,u.website_link";
$allCards = "SELECT DISTINCT $columns, round(coalesce(sum(cc.rating) / count(cc.rating), 0), 1) as average_rating FROM card c"
	. " JOIN card_data cd ON c.card_id = cd.card_id"
	. " JOIN user u ON c.user_id = u.user_id"
	. " LEFT JOIN card_contact cc on cc.card_id = c.card_id"
	. " left join card_user_data_addresses cuda on cc.card_id = cuda.card_id"
	. " left join countries ctr on ctr.country_id = cuda.country_id"
	. " left join countries ctr2 on ctr2.country_id = u.personal_country_id"
	. " WHERE (";
//$columns = "c.card_id,c.card_name,c.card_type,c.distributed_brand,c.category,c.sub_category,c.assigned_id,cd.canvas_front,cd.canvas_back,cd.links_front,cd.links_back,cd.landscape,cuda.country_id as card_country_id, ctr.country as card_country_name,u.personal_country_id, ctr2.country as personal_country_name,u.first_name,u.last_name,u.profile_image,u.company_name,u.department_name,u.position,u.phone,u.mobile,u.email_address,u.title,u.website_link, cc.rating as your_rating,round(sum(cc.rating) / count(cc.rating), 1) as average_rating";
//$card_query = "SELECT DISTINCT $columns, round(sum(cc.rating) / count(cc.rating), 1) as average_rating FROM card c "
//	. "inner JOIN card_data cd ON c.card_id = cd.card_id "
//	. "inner JOIN user u ON c.user_id = u.user_id "
//	. "LEFT JOIN card_contact cc on cc.card_id = c.card_id "
//	. "left join card_user_data_addresses cuda on cc.card_id = cuda.card_id "
//	. "left join countries ctr on ctr.country_id = cuda.country_id "
//	. "left join countries ctr2 on ctr2.country_id = u.personal_country_id "
//	. "WHERE ( ";
$terms = explode(' ', $term);
foreach ($terms as $t) {
	if ($t !== '') {
		$allCards .= " c.card_name LIKE '%$t%' OR"
		. " c.distributed_brand LIKE '%$t%' OR"
		. " c.category LIKE '%$t%' OR"
		. " c.sub_category LIKE '%$t%' OR"
		. " u.user_id LIKE '%$t%' OR"
		. " u.first_name LIKE '%$t%' OR"
		. " u.last_name LIKE '%$t%' OR"
		. " u.email_address LIKE '%$t%' OR"
		. " u.website_link LIKE '%$t%' OR"
		. " u.company_name LIKE '%$t%' OR"
		. " u.department_name LIKE '%$t%' OR"
		. " u.position LIKE '%$t%' OR"
		. " u.company_email_address LIKE '%$t%' OR"
		. " u.company_website_link LIKE '%$t%' OR";
	} else {
		// If this term is empty, do not meet conditions for it, so return empty set
		$allCards .= "FALSE";
	}
}
$allCards = rtrim($allCards, ' OR');
$allCards .= ") AND c.user_id != '" . $user_id . "' AND c.card_type!='scanned'";
//$allCards = $allCards . " group by $columns";
$allCards = $allCards . " group by c.card_id, cd.canvas_front, cd.canvas_back, cd.links_front, cd.links_back, cd.landscape";

ChromePhp::log('$allCards');
ChromePhp::log($allCards);

$result = mysqli_query( $conn, $allCards );
if ( !$result ){
	$request_result->success = 0;
	$request_result->err = mysqli_error( $conn );
}else{
	$request_result->success = 1;
}
$request_result->result = array();
//TODO: Revisit table structure that causes unnecessary complex query, the querying method used now resolves the complexity, but negatively effects performance
//ChromePhp::log('QUERY RESULTS:');
$i = 0;
while ( $row = mysqli_fetch_array( $result ) ){
	$i++;
//	ChromePhp::log("result n. $i");
//	ChromePhp::log("card_type: ".$row['card_type']);
//	ChromePhp::log($row);
	$can_show = GetSetting( $row[ "card_id" ], $row[ "card_type" ], "visible_in_search", $conn );
	$visible_in_user_folder = GetSetting( $row[ "card_id" ], $row[ "card_type" ], "mutual_contacts", $conn );
	
	//check out the gem that is the first comment in this page
	//http://php.net/manual/en/language.types.boolean.php
	
	$seen_in_user_folder = false;
	if ($visible_in_user_folder->requested_setting){
		$seen_in_user_folder = true;
	}
	if ( $can_show->requested_setting )	{
////	if ( 0==0 )	{
		$link_status = "NONE";
		$cardLinkQuery = sprintf( "
            SELECT link_status 
            FROM card_link 
            WHERE card_id_requested = '%s'
            AND user_requested_by = '%s'", $row[ "card_id" ], $user_id );
		//echo $cardLinkQuery;
		$link_query_result = mysqli_query( $conn, $cardLinkQuery );
		if ( !$link_query_result )
		{
			$request_result->success = 0;
			$request_result->message = $cardLinkQuery;
			$request_result->err = mysqli_error( $conn );
		}
		else
		{
			$request_result->success = 1;
			while ( $link_row = mysqli_fetch_array( $link_query_result ) )
			{
				$link_status = $link_row[ "link_status" ];
//				ChromePhp::log('link_status is '.$link_status);
			}
		}
		$card = new SearchedCard(
						$row[ "card_id" ], $row[ "card_name" ], $row[ "card_type" ], $row[ "canvas_front" ], $row[ "canvas_back" ], $row[ "links_front" ], $row[ "links_back" ], $row[ "company_name" ], $row[ "department_name" ], $row[ "position" ], $row[ "first_name" ], $row[ "last_name" ], $row['profile_image'], $row[ "distributed_brand" ], $row[ "category" ], $row[ "sub_category" ], $row[ "phone" ], $row[ "mobile" ], $row[ "email_address" ], $row[ "title" ], $row[ "website_link" ], $row[ "landscape" ], $link_status, $can_show, $seen_in_user_folder, $row[ "average_rating" ], $row['assigned_id'] );
		array_push( $request_result->result, $card );
	}
}
echo json_encode( $request_result );
ob_end_flush();