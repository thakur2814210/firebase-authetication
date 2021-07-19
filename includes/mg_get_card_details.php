<?php
require_once 'absolute_database_config.php';
include_once 'view-business-card/models.php';
include_once __DIR__ . '/shared_functions.php';
// $user_id = $_SESSION[ "user_id" ];
// $user_id = "5b07d66a932d65.95130100";
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : "5b07d66a932d65.95130100";

$orientation = 'landscape';
if (!isset($_REQUEST['card_id'])) {
    return;
}
$card_id = $_REQUEST['card_id'];
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
        c.card_type,
        c.card_name,
        c.distributed_brand,
        cd.landscape,
        cd.canvas_back,
        cd.canvas_front,
        cd.widgets_front,
        cd.widgets_back,
        cd.links_front,
        cd.links_back
        from card c
        inner join card_data cd on c.card_id = cd.card_id
        inner join user u on u.user_id = c.user_id and c.card_id = cd.card_id
        left join address pa on u.personal_address_id = pa.address_id
        left join address ca on u.company_address_id = ca.address_id
        WHERE u.user_id != '" . $_SESSION['user_id'] . "'
        AND cd.card_id = '" . $card_id . "'";
$bcard = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($bcard)) {
    $title = $row['title'];
    $last_name = $row['last_name'];
    $first_name = $row['first_name'];
    $phone = $row['phone'];
    $mobile = $row['mobile'];
    $email_address = $row['email_address'];
    $website_link = $row['website_link'];
    $personal_address_1 = $row['personal_address_1'];
    $personal_address_2 = $row['personal_address_2'];
    $personal_city = $row['personal_city'];
    $personal_country = $row['personal_country'];
    $personal_post_code = $row['personal_post_code'];
    $company_name = $row['company_name'];
    $department_name = $row['department_name'];
    $position = $row['position'];
    $corporate_code = $row['corporate_code'];
    $company_phone = $row['company_phone'];
    $company_mobile = $row['company_mobile'];
    $company_email_address = $row['company_email_address'];
    $company_website_link = $row['company_website_link'];
    $company_address_1 = $row['company_address_1'];
    $company_address_2 = $row['company_address_2'];
    $company_city = $row['company_city'];
    $company_country = $row['company_country'];
    $company_post_code = $row['company_post_code'];
    $distributed_brand = $row['distributed_brand'];
    if ($row['landscape'] != null) {
        if ($row['landscape']) {
            $orientation = 'landscape';
        } else {
            $orientation = 'portrait';
        }
        ;
    }
    ;
    $canvas_back = $row['canvas_back'];
    $canvas_front = $row['canvas_front'];
    $widgets_front = $row['widgets_front'];
    $widgets_back = $row['widgets_back'];
    $links_front = $row['links_front'];
    $links_back = $row['links_back'];
    $card_type = $row["card_type"];
}
;
$settings = GetSetting($card_id, $card_type, "visible_in_search", $conn);
$link_status = "NONE";
$cardLinkQuery = sprintf("
            SELECT link_status
            FROM card_link
            WHERE card_id_requested = '%s'
            AND user_requested_by = '%s'", $card_id, $user_id);
$link_query_result = mysqli_query($conn, $cardLinkQuery);
if (!$link_query_result) {
    $request_result->success = 0;
    $request_result->message = $cardLinkQuery;
    $request_result->err = mysqli_error($conn);
//    echo $request_result->err;
} else {
    //$request_result->success = 1;
    while ($link_row = mysqli_fetch_array($link_query_result)) {
        $link_status = $link_row["link_status"];
    }
}
$card = new CardStatusPermissions(
    $card_id, $card_type, $link_status, $settings);
echo json_encode($card);
