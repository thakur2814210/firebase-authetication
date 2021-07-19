<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );
ob_start();
require_once __DIR__.'/../absolute_database_config.php';
include_once(__DIR__.'/../../utilities/request_result.php');
$request_result = new Result();
//used to record db operations
$mysqli_errors = array();
$mysqli_success = array();
$orientation = 'landscape';
$card_id = "";
//$user_id = $_SESSION[ 'user_id' ];
$user_id = '570f8bfa130d06.17915162';
//$_SESSION[ 'card' ][ 'card_id' ] = uniqid();
//if ( isset( $_SESSION[ 'card' ][ 'card_id' ] ) )
//{
//	$card_id = $_SESSION[ 'card' ][ 'card_id' ];
//}
//else
//{
//	//TODO: Don't just take a random one here
//	$query = "SELECT card_id FROM card LIMIT 1";
//	$result = mysqli_query( $conn, $query );
//	if ( !$result )
//	{
//		$request_result->success = 0;
//		array_push( $mysqli_errors, array( 'mysqli_error' => mysqli_error( $conn ), 'executed_query' => $query ) );
//	} else
//	{
//		$request_result->success = 1;
//		array_push( $mysqli_success, $query );
//	}
//	while ( $row = mysqli_fetch_array( $result ) )
//	{
//		$card_id = $row[ 'card_id' ];
//	}
//}
$count = mysqli_affected_rows( $conn );
if ( $count > 0 )
{

} else
{

}
$query = "select
        u.user_id,
        u.title,
        u.last_name,
        u.first_name,
        u.phone,
        u.mobile,
        u.email_address,
        u.password,
        u.website_link,
        u.additional_info,
        u.personal_address_id,
        u.company_name,
        u.department_name,
        u.position,
        u.corporate_code,
        u.company_address_id,
        u.company_phone,
        u.company_mobile,
        u.company_email_address,
        u.company_website_link,
        pa.address_1 as personal_address_1,
        pa.address_2 as personal_address_2,
        pa.city as personal_city,
        pa.country_id as personal_country,
        pa.post_code as personal_post_code,
        ca.address_1 as company_address_1,
        ca.address_2 as company_address_2,
        ca.city as company_city,
        ca.country_id as company_country,
        ca.post_code as company_post_code,
		        dc.canvas_back,
        dc.canvas_front,
        dc.widgets_front,
        dc.widgets_back,
        dc.links_front,
        dc.links_back
		from user u
		inner join default_card dc on dc.card_id = '1'
		left join address pa on u.personal_address_id = pa.address_id
		left join address ca on u.company_address_id = ca.address_id
		where u.user_id = '$user_id'";



//left join default_card dc on dc.card_id = '1'
echo $query;

$bcard = mysqli_query( $conn, $query );
echo "<br>";
echo "<br>";
echo ('error '.mysqli_error($conn));
while ( $row = mysqli_fetch_array( $bcard ) )
{

//	$row = mysqli_fetch_row( $bcard );
//	$assigned_id = $row[ 'assigned_id' ];
	$title = $row[ 'title' ];
	$last_name = $row[ 'last_name' ];
	$first_name = $row[ 'first_name' ];
	$phone = $row[ 'phone' ];
	$mobile = $row[ 'mobile' ];
	$email_address = $row[ 'email_address' ];
	$website_link = $row[ 'website_link' ];
	$personal_address_1 = $row[ 'personal_address_1' ];
	$personal_address_2 = $row[ 'personal_address_2' ];
	$personal_city = $row[ 'personal_city' ];
	$query = "SELECT country FROM countries WHERE country_id='".$row[ 'personal_country' ]."'";
	$result = mysqli_query($conn, $query);
	$c_row = mysqli_fetch_row($result);
	$personal_country = $c_row[0];
	$personal_post_code = $row[ 'personal_post_code' ];
	$company_name = $row[ 'company_name' ];
	$department_name = $row[ 'department_name' ];
	$position = $row[ 'position' ];
	$corporate_code = $row[ 'corporate_code' ];
	$company_phone = $row[ 'company_phone' ];
	$company_mobile = $row[ 'company_mobile' ];
	$company_email_address = $row[ 'company_email_address' ];
	$company_website_link = $row[ 'company_website_link' ];
	$company_address_1 = $row[ 'company_address_1' ];
	$company_address_2 = $row[ 'company_address_2' ];
	$company_city = $row[ 'company_city' ];
	$company_country = $row[ 'company_country' ];
	$company_post_code = $row[ 'company_post_code' ];
//	$distributed_brand = $row[ 'distributed_brand' ];
	$orientation = 'landscape';
	$canvas_back = $row[ 'canvas_back' ];
	$canvas_front = $row[ 'canvas_front' ];
	$widgets_front = $row[ 'widgets_front' ];
	$widgets_back = $row[ 'widgets_back' ];
	$links_front = $row[ 'links_front' ];
	$links_back = $row[ 'links_back' ];
//	$premium_paid = $row[ 'premium_paid' ];
}
$query2 = "select * from default_card where card_id='1'";
$defcard = mysqli_query($conn, $query2);
//while ($row = mysqli_fetch_array($defcard)){
//	echo "<pre>";
//	var_dump($row);
//	echo "</pre>";
//	$canvas_back = $row[ 'canvas_back' ];
//	$canvas_front = $row[ 'canvas_front' ];
//	$widgets_front = htmlspecialchars($row[ 'widgets_front' ]);
//	$widgets_back = $row[ 'widgets_back' ];
//	$links_front = $row[ 'links_front' ];
//	$links_back = $row[ 'links_back' ];
//}
echo '$widgets_front';
echo htmlspecialchars($widgets_front);
//$query = sprintf( "
//    SELECT *
//    FROM
//    (
//			SELECT c.card_id,cl.link_status,cl.user_requested_by
//			FROM card c
//			LEFT OUTER JOIN card_link cl
//			ON c.card_id = cl.card_id_requested
//		) q
//    WHERE card_id = '%s'
//    AND (user_requested_by='%s' OR link_status IS NULL)", $card_id, $_SESSION[ 'user_id' ] );
//$linkResult = mysqli_query( $conn, $query );
//if ( !$linkResult )
//{
//	$request_result->success = 0;
//	array_push( $mysqli_errors, array( 'mysqli_error' => mysqli_error( $conn ), 'executed_query' => $query ) );
//} else
//{
//	$request_result->success = 1;
//	array_push( $mysqli_success, $query );
//}
//while ( $statusRow = mysqli_fetch_array( $linkResult ) )
//{
//	$link_status = $statusRow[ 'link_status' ];
//};

// print_r($mysqli_success);
// print_r($mysqli_errors);
ob_end_flush();
