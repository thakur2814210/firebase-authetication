<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
ob_start();
require_once '../../session_setup.php';
// if (!isset($_SESSION['user_id'])) {
//     echo json_encode('expired');
//     exit;
// }
include_once 'model.php';
require_once '../mg_get_card_details.php';
include_once '../../utilities/request_result.php';
require_once __DIR__ . '/../absolute_database_config.php';
require_once '../../ChromePhp.php';
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : $_REQUEST["user_id"];
$folder = $_REQUEST["folder"];
$request_result = new Result();
$request_result->message = $folder;

$allScannedCards = '';
if ($folder === 'pc') {
  $conditions = "left join card c on c.card_id = cl.card_id_requested
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
} elseif ($folder === 'sp') {
  $conditions = "left join card c on c.card_id = cl.card_id_requested
        left join card_contact cc on c.card_id = cc.card_id
        left join card_data cd on c.card_id = cd.card_id
        left join user u on c.user_id = u.user_id
        left join address a on a.address_id = u.personal_address_id
        left join card_contact ccr on ccr.card_id = cc.card_id
		left join card_user_data cud on cc.card_id = cud.card_id
		left join card_social_links csl ON cud.card_id = csl.card_id
		left join countries ctr on ctr.country_id = cud.country_id
		left join countries ctr2 on ctr2.country_id = u.personal_country_id
    where cl.user_requested_by = '$user_id' AND cl.link_status = 'ACCEPTED' AND (c.card_type='Professional' OR c.card_type='Product' OR c.card_type='Service')";
} else {
  $conditions = "
		join card c on c.card_id = cl.card_id_requested
    join card_contact cc on c.card_id = cc.card_id
		join card_data cd on c.card_id = cd.card_id
        join user u on c.user_id = u.user_id
        left join card_contact ccr on ccr.card_id = c.card_id
        left join address a on a.address_id = u.personal_address_id
		left join card_user_data cud on cc.card_id = cud.card_id
		left join card_social_links csl ON cud.card_id = csl.card_id
		left join countries ctr on ctr.country_id = cud.country_id
		left join countries ctr2 on ctr2.country_id = u.personal_country_id
		inner join card_folder cf on cf.card_id = c.card_id and cf.folder_id='$folder'
		where cl.user_requested_by = '$user_id'";

  $scannedConditions = "
		JOIN scanned_card c on c.card_id = cf.scanned_card_id
		JOIN scanned_card_data cd on c.card_id = cd.card_id
		JOIN user u on u.user_id = c.user_id
		LEFT JOIN scanned_card_user_data cud on cud.card_id = c.card_id -- this join is unnecessary as scanned cards have the same owner
		LEFT JOIN address a on a.address_id = u.personal_address_id
		LEFT JOIN  countries ctr on ctr.country_id = cud.country_id
		LEFT JOIN  countries ctr2 on ctr2.country_id = u.personal_country_id
		WHERE cf.folder_id='$folder' and cf.user_id = '$user_id'";

  $allScannedCards = "
		UNION
		SELECT 'ScannedCard',
			c.card_name,
			c.card_id,
			c.card_type,
			c.distributed_brand,
			c.category,
			c.sub_category,
			c.assigned_id,
			c.timestamp,
			'0.0' AS 'your_rating',
			'0.0' AS 'average_rating',
			'1' AS 'cc.private',
			cd.canvas_front,
			NULL AS 'cd.canvas_back',
			NULL AS 'cd.links_front',
			NULL AS 'cd.links_back',
			cd.landscape,
			cud.first_name,
			cud.last_name,
			cud.address_1,
			cud.city,
			cud.email_address,
			cud.phone_number,
			NULL AS 'csl.sl_facebook',
			NULL AS 'csl.sl_twitter',
			NULL AS 'csl.sl_google',
			NULL AS 'csl.sl_linkedin',
			NULL AS 'csl.sl_instagram',
			NULL AS 'csl.sl_youtube',
			NULL AS 'csl.description',
			NULL AS 'cud.mobile_number',
			cud.country_id as card_country_id,
			cud.website_link,
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
		FROM card_folder cf $scannedConditions";
}
//	left join card_comment ccm on ccm.card_id = c.card_id
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
			FROM card_link cl $conditions";
//, COUNT(ccm.comment) as comments_number
$allCards = $allCards . " group by c.card_id, cd.canvas_front, cd.canvas_back, cd.links_front, cd.links_back, cd.landscape";
$allCards = $allCards . $allScannedCards;
$allCards = $allCards . " order by average_rating desc";
// echo $allCards.'<br>';

$result = mysqli_query($conn, $allCards);
// echo mysqli_error($conn);

if (!$result) {
  $request_result->success = 0;
  $request_result->success = 0;
  $request_result->err = mysqli_error($conn);
} else {
  $request_result->success = 1;
}

$request_result->result = array();

while ($row = mysqli_fetch_array($result)) {

  $card_id = $row["card_id"];
  $card_type = $row["card_type"];
  $can_show = GetSetting($row["card_id"], $row["card_type"], "visible_in_search", $conn);
  $visible_in_user_folder = GetSetting($row["card_id"], $row["card_type"], "mutual_contacts", $conn);
  $seen_in_user_folder = false;
  if ($visible_in_user_folder->requested_setting) {
    $seen_in_user_folder = true;
  }

  if (strtolower($card_type) == 'personal' || strtolower($card_type) == 'corporate') {
    $comments_query = "SELECT COUNT(*) FROM card_comment WHERE card_id='$card_id' AND user_id='$user_id'";
  } else {
    $comments_query = "SELECT COUNT(*) FROM card_comment WHERE card_id='$card_id'";
  }

  $result3 = mysqli_query($conn, $comments_query);
  $row3 = mysqli_fetch_row($result3);
  $comments_number = $row3[0];

  //check out the gem that is the first comment in this page
  //http://php.net/manual/en/language.types.boolean.php
  if ($can_show->requested_setting) {
    $link_status = "NONE";
    $cardLinkQuery = sprintf("
            SELECT link_status
            FROM card_link
            WHERE card_id_requested = '%s'
            AND user_requested_by = '%s' ORDER BY date_accepted DESC LIMIT 1", $card_id, $user_id);
    $link_query_result = mysqli_query($conn, $cardLinkQuery);
    if (!$link_query_result) {
      //            $request_result->success = 0;
      //            $request_result->message = $cardLinkQuery;
      //            $request_result->err = mysqli_error( $conn );
    } else {
      //            $request_result->success = 1;
      while ($link_row = mysqli_fetch_array($link_query_result)) {
        $link_status = $link_row["link_status"];
        //                ChromePhp::log('while row: '.$row['card_name'].' id: '.$row['card_id'].' link_status is '.$link_status);
      }
    }
    if ($link_status === 'ACCEPTED') {
      $card = new SearchedCardInFolder(
        $row["card_id"],
        $row["card_name"],
        $row["card_type"],
        $row["canvas_front"],
        $row["canvas_back"],
        $row["links_front"],
        $row["links_back"],
        $row["company_name"],
        $row["department_name"],
        $row["position"],
        $row["first_name"],
        $row["last_name"],
        $row['profile_image'],
        $row["distributed_brand"],
        $row["category"],
        $row["sub_category"],
        // $row["phone"],
        // $row["mobile"],
        isset($row["phone"]) ? $row["phone"] : '',
        isset($row["mobile"]) ? $row["mobile"] : '',
        $row["email_address"],
        $row["title"],
        $row["website_link"],
        $row["landscape"],
        $link_status,
        $can_show,
        $seen_in_user_folder,
        $row["your_rating"],
        $row["average_rating"],
        $row['assigned_id']
      );

      array_push($request_result->result, $card);
    }
  }
}
// $output = json_encode($request_result);
// switch (json_last_error()) {
//   case JSON_ERROR_NONE:
//     echo ' - No errors';
//     break;
//   case JSON_ERROR_DEPTH:
//     echo ' - Maximum stack depth exceeded';
//     break;
//   case JSON_ERROR_STATE_MISMATCH:
//     echo ' - Underflow or the modes mismatch';
//     break;
//   case JSON_ERROR_CTRL_CHAR:
//     echo ' - Unexpected control character found';
//     break;
//   case JSON_ERROR_SYNTAX:
//     echo ' - Syntax error, malformed JSON';
//     break;
//   case JSON_ERROR_UTF8:
//     echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
//     break;
//   default:
//     echo ' - Unknown error';
//     break;
// }
// echo '<pre>';
// var_dump($request_result->result);
// echo '</pre>';
function utf8ize($item)
{
  if (is_array($item))
    foreach ($item as $k => $v)
      $item[$k] = utf8ize($v);

  else if (is_object($item))
    foreach ($item as $k => $v)
      $item->$k = utf8ize($v);

  else
    return utf8_encode($item);

  return $item;
}
echo json_encode(utf8ize($request_result));
