<?php

require_once('../../session_setup.php');
if (!isset($_SESSION['user_id'])) {
  echo json_encode('expired');
  exit;
}
include_once('../card-contacts/model.php');
require_once('../shared_functions.php');
require_once '../absolute_database_config.php';
include_once('../../utilities/request_result.php');
$user_id = $_SESSION['user_id'];
$request_result = new Result();
$request_result->result = array();


$conditions1 = "left join card c on c.card_id = cl.card_id_requested
        left join card_contact cc on c.card_id = cc.card_id
        left join card_data cd on c.card_id = cd.card_id
        left join user u on c.user_id = u.user_id
        left join address a on a.address_id = u.personal_address_id
        left join card_contact ccr on ccr.card_id = cc.card_id
		left join card_user_data cud on cc.card_id = cud.card_id
		left join card_social_links csl ON cud.card_id = csl.card_id
		left join countries ctr on ctr.country_id = cud.country_id
		left join countries ctr2 on ctr2.country_id = u.personal_country_id
    where cl.user_requested_by = '$user_id' AND (c.card_type='Personal' OR c.card_type='Corporate')";

$allCards = "SELECT 'NormalCard' As Type,
				c.card_name,
				c.card_id,
				c.card_type,
				c.distributed_brand,
				c.category,
				c.sub_category,
				c.assigned_id,
				c.timestamp,
				cc.rating as your_rating, round(sum(ccr.rating) / count(ccr.rating), 1) as average_rating,
				cc.private,
				cd.canvas_front,
				cd.canvas_back,
				cd.links_front,
				cd.links_back,
				cd.landscape,
				cud.first_name,
				cud.last_name,
				cud.address_1,
				cud.city,
				cud.email_address,
				cud.phone_number,
				cud.mobile_number,
				cud.country_id as card_country_id,
				cud.website_link,
				csl.sl_facebook,
				csl.sl_twitter,
				csl.sl_google,
				csl.sl_linkedin,
				csl.sl_instagram,
				csl.sl_youtube,
				csl.description,
				u.first_name as user_first_name,
				u.last_name as user_last_name,
				u.profile_image,
				u.company_name,
				u.department_name,
				u.position,
				u.title,
				u.personal_country_id,
				a.city as personal_city,
				a.post_code as post_code,
				ctr.country as card_country_name,
				ctr2.country as personal_country_name
      FROM card_link cl ";
      $query1 = $allCards . $conditions1;
$query1 = $query1 . " group by c.card_id, cd.canvas_front, cd.canvas_back, cd.links_front, cd.links_back, cd.landscape";

$query1 = $query1 . " order by average_rating desc";
$result = mysqli_query($conn, $query1);

$cards_number = array();
if ($result) {
  while ($row = mysqli_fetch_array($result)) {

    $card_id = $row["card_id"];
    $card_type = $row["card_type"];
    $can_show = GetSetting($row["card_id"], $row["card_type"], "visible_in_search", $conn);
    $visible_in_user_folder = GetSetting($row["card_id"], $row["card_type"], "mutual_contacts", $conn);
    $seen_in_user_folder = false;
    if ($visible_in_user_folder->requested_setting) {
      $seen_in_user_folder = true;
    }

    if ($can_show->requested_setting) {
      $link_status = "NONE";
      $cardLinkQuery = sprintf("
            SELECT link_status
            FROM card_link
            WHERE card_id_requested = '%s'
            AND user_requested_by = '%s' ORDER BY date_accepted DESC LIMIT 1", $card_id, $user_id);
      $link_query_result = mysqli_query($conn, $cardLinkQuery);
      if (!$link_query_result) {
      } else {
        while ($link_row = mysqli_fetch_array($link_query_result)) {
          $link_status = $link_row["link_status"];
        }
      }
      if ($link_status === 'ACCEPTED') {
        array_push($cards_number, $row["card_id"]);
      }
    }
  }
}
array_push($request_result->result, array(
  'folder_id' => 'pc',
  'description' => 'Personal / Corporate',
  'cards_amount' => count($cards_number)
));

$conditions2 = "left join card c on c.card_id = cl.card_id_requested
        left join card_contact cc on c.card_id = cc.card_id
        left join card_data cd on c.card_id = cd.card_id
        left join user u on c.user_id = u.user_id
        left join address a on a.address_id = u.personal_address_id
        left join card_contact ccr on ccr.card_id = cc.card_id
		left join card_user_data cud on cc.card_id = cud.card_id
		left join card_social_links csl ON cud.card_id = csl.card_id
		left join countries ctr on ctr.country_id = cud.country_id
    left join countries ctr2 on ctr2.country_id = u.personal_country_id
    LEFT JOIN card_folder cf ON cf.user_id='$user_id'
    where cl.user_requested_by = '$user_id' AND cl.link_status = 'ACCEPTED' AND (c.card_type='Professional' OR c.card_type='Product' OR c.card_type='Service')";
$query2 = $allCards . $conditions2;

$query2 = $query2 . " group by c.card_id, cd.canvas_front, cd.canvas_back, cd.links_front, cd.links_back, cd.landscape";
$query2 = $query2 . " order by average_rating desc";

$result = mysqli_query($conn, $query2);
$cards_number = array();
if ($result) {
  while ($row = mysqli_fetch_array($result)) {
    $cards_ids[] = $row["card_id"];
    $can_show = GetSetting($row["card_id"], $row["card_type"], "visible_in_search", $conn);
    if ($can_show->requested_setting) {
      $link_status = "NONE";
      $cardLinkQuery = sprintf("
            SELECT link_status
            FROM card_link
            WHERE card_id_requested = '%s'
            AND user_requested_by = '%s'", $row["card_id"], $user_id);
      $link_query_result = mysqli_query($conn, $cardLinkQuery);
      if (!$link_query_result) {
      } else {
        while ($link_row = mysqli_fetch_array($link_query_result)) {
          $link_status = $link_row["link_status"];
        }
      }
      if ($link_status === 'ACCEPTED') {
        array_push($cards_number, $row["card_id"]);
      }
    }
  }
}
array_push($request_result->result, array(
  'cards' => implode(' - ', $cards_number),
  'folder_id' => 'sp',
  'description' => 'Service / Product',
  'cards_amount' => count($cards_number)
));

/* CARDS FOR OTHER FOLDERS */
$query = "SELECT f.folder_id, f.description, f.user_id, COUNT(cf.folder_id) AS card_amount "
  . "FROM folder f "
  . "LEFT JOIN card_folder cf ON f.folder_id=cf.folder_id AND f.user_id=cf.user_id "
  . "WHERE f.user_id = '$user_id' "
  . "GROUP BY f.folder_id ";
// echo $query . "<br>";
$result = $request_result->handleQuery($conn, $query);
//$result = mysqli_query($conn, $query);
//$folders = array();
if ($result) {
  $request_result->success = 1;
} else {
  $request_result->success = 0;
  echo json_encode($request_result);
}
while ($row = mysqli_fetch_array($result)) {
  $row['description'] != '' ? $description = $row['description'] : $description = 'Anonymous folder';
  //	ChromePhp::log( 'mg_get_folders  card_amount for custom folder ' + $description + ' is ' + $row[ 'card_amount' ] );
  array_push($request_result->result, array(
    "folder_id" => $row['folder_id'],
    "description" => $description,
    "cards_amount" => $row['card_amount']
  ));
  //	array_push($folders, array("folder_id"=>$row['folder_id'],"description"=>$description));
}


//$result = $r->handleQuery( $conn, $query );
echo json_encode($request_result);
