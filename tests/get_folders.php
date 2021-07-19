<?php
require_once '../includes/absolute_database_config.php';
include_once('../utilities/request_result.php');
include_once '../includes/card-contacts/model.php';
require_once '../includes/mg_get_card_details.php';
require_once('../includes/shared_functions.php');
$user_id = '5c0e1e2775d821.18193183'; //fabien.beugre@cardition.com
$user_id = '5c0e1c7e29a197.28946028'; //support@cardition.com

// $query = "SELECT f.folder_id, f.description, f.user_id, COUNT(cf.folder_id) AS card_amount "
// . "FROM folder f "
// . "LEFT JOIN card_folder cf ON f.folder_id=cf.folder_id AND f.user_id=cf.user_id "
// . "WHERE f.user_id = '$user_id' "
// . "GROUP BY f.folder_id ";
// echo "$query<br>";
// $result = mysqli_query($conn, $query);

// $folders = array();
// while ($row = mysqli_fetch_array($result)) {
//   $row['description'] != '' ? $description = $row['description'] : $description = 'Anonymous folder';
//   array_push($folders, array(
//     "folder_id" => $row['folder_id'],
//     "description" => $description,
//     "cards_amount" => $row['card_amount']
//   ));

// }
// foreach ($folders as $folder){
//   echo "<ul>
//   <li>{$folder['folder_id']}</li>
//   <li>{$folder['description']}</li>
//   <li>{$folder['cards_amount']}</li>
//   </ul><br>";
// }

// $conditions = "LEFT JOIN card_folder cf ON cf.user_id='$user_id' INNER JOIN card c on c.card_id = cc.card_id WHERE c.user_id != '$user_id' AND cc.user_id = '$user_id' AND (c.card_type='Personal' OR c.card_type='Corporate')";
// $query = "SELECT cc.card_id, cc.user_id, c.card_type FROM card_contact cc $conditions";

// $query .= " group by cc.card_id";
// echo "$query<br><br><br><br>";


// echo "FOLLOWING QUERY SHOULD RETURN ALL CARDS IN PERSONAL FOLDER: ITS LENGTH SHOULD BE EQUAL TO THE CARDS ACTUALLY SHOWN IN THE FOLDER<BR><BR>";


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
				cud.user_id,
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

$allCards = $allCards . " order by average_rating desc";

echo "$allCards<br><br><br>";

$result = mysqli_query($conn, $allCards);
$cards = array();
$cards_number = array();
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
    } else {
      while ($link_row = mysqli_fetch_array($link_query_result)) {
        $link_status = $link_row["link_status"];
      }
    }
    if ($link_status === 'ACCEPTED') {
      array_push($cards_number, $row["card_id"]);
    }


    $card = new SearchedCardInFolder(
      // $row["user_id"],
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
    array_push($cards, $card);
  }
}
echo "cards are ".count($cards)."<br><br>";
echo "cards_number is ".count($cards_number)."<br><br>";
// $card_ids = '(';
// for ($i=0; $i < count($cards); $i++) {
//   if (empty($cards[$i]->first_name)){
//     $card_ids .= "'" . $cards[$i]->card_id . "',";
//   }


//   // echo "$i: ". $cards[$i]->card_id . '<br>' . 'User_id: ' . $cards[$i]->user_id . '<br>' . 'Full name: ' . $cards[$i]->first_name . ' ' . $cards[$i]->last_name . '<br><br>';
// }
// $card_ids = rtrim($card_ids, ',');
// $card_ids .= ')';
// echo $card_ids;


// SELECT card_id_requested, link_status, date_accepted FROM card_link WHERE card_id_requested IN ('5c1670f992e82', '5cb218a15b9af','5d0779474a58e')
// SELECT * FROM card WHERE card_id IN ('15c0e958311425','15c0e95f2dc4df','15c0e9616aa7b6','15c0e96543a375','15c0e967abd721','15c0e96a2d3b10','15c0e96ce2ae02','15c0e96f1d0f01','15c0e971a51b7d','15c0e97400a0fe','15c0e976935620','15c0e97a03ef9c','15c0e97c519c75','15c0e97ee737da','15c0e98ae3334f','15c0e98e139f6b','15c991f5844f3e','5c1670f992e82','5c2d2dc2d8764','5c5845636ea72','5c54568facfdc','5c8446c481f1f','15c0e982ee27ae','5ca750f69b26a','5ca7512050b79','5ca751f78ed4d','5cb218a15b9af','5cc3491532c78','5cc6a7ecb1278','5c82331cdb28d','5c81332e5a9a2','5c812b9790f4b','5c4206cb06dd5','5c73185560b5f','5d0779474a58e','5ca74fc74ac85','5d5a81dabbccf','5c82239d6ed1c')
