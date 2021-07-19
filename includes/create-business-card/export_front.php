<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../session_setup.php';
require_once '../absolute_database_config.php';
require_once __DIR__ . '/../../ChromePhp.php';
$export_absolute_path = $_SERVER['DOCUMENT_ROOT'] . '/';
$background_absolute_path = $_SERVER['DOCUMENT_ROOT'] . '/includes/create-business-card/uploads/background/';
$logo_absolute_path = $_SERVER['DOCUMENT_ROOT'] . '/includes/create-business-card/uploads/';
if (!isset($_SESSION['user_id'])) {
    echo json_encode('expired');
    exit;
}
$_SESSION['preview_active'] = true;
if (isset($_SESSION['card']['default'])) {
    $_SESSION['card']['card_name'] = 'Default card';
    $_SESSION['card']['card_type'] = 'Personal';
    $_SESSION['card']['distributed_brand'] = '';
    $_SESSION['card']['category'] = '';
    $_SESSION['card']['sub_category'] = '';
}
$user = mysqli_query($conn, "SELECT user_id, personal_address_id, company_address_id FROM user WHERE user_id = '" . $_SESSION['user_id'] . "';");
$u = mysqli_fetch_row($user);
$address = mysqli_query($conn, "SELECT address_id, country_id FROM address WHERE address_id = '" . $u[1] . "';");
$a = mysqli_fetch_row($address);
$country = mysqli_query($conn, "SELECT country_id, code FROM countries WHERE country_id = '" . $a[1] . "';");
$c = mysqli_fetch_row($country);
//ChromePhp::log('country: ' . $c[1]);
//ChromePhp::log('card_name is: ' . $_SESSIONC['card']['card_name']);

//check if user has a main card
function get_user_main_card($user_id, $conn)
{
    $query = "select card_id from card where user_id='$user_id' and main_card=1";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        return false;
    }
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_row($result);
        return $row[0];
    } else {
        return false;
    }
}
// generate code function
function genCode($conn)
{
    $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $bcn = '';
    for ($i = 0; $i < 6; $i++) {
        $bcn .= $chars[rand(0, strlen($chars) - 1)];
    }
    $query = "SELECT card_id FROM card WHERE assigned_id='$bcn'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        // regenerate code
        genCode($conn);
    } else {
        return $bcn;
    }
}

$landscape = $_POST['landscape'];
/*
 * ADD CODE TO ALLOW THE USER TO GO BACK TO PREVIOUS CARD:
 * CHECK IF CARD ID EXISTS
 * IF IT EXISTS
 *         CREATE A NEW RECORD CARD_ID + _EDITED -> DON'T OVVERRIDE CARD IN TABLE!!!
 *         IF USER CLICK SAVE APPEND PROCESS_COMPLETED TO THE LINK IN BUTTON SAVE
 *         THEN IN CARD_SAVE.PHP DO ALL STUFF, DELETE OLD RECORD AND RENAME THE NEW ONE DROPPING '_EDITED'
 *         THEN IN CANCEL-CARD-CREATION.PHP DELETE NEW RECORD
 *         CHANGE NAME TO THE 2 IMAGES IN EXPORT, CARD_ID IN CARD AND CARD_DATA
 */

$query = "SELECT COUNT(*) as total FROM card WHERE card_id='" . $_SESSION['card']['card_id'] . "'";
$result = mysqli_query($conn, $query);
if ($result) {

    function quote($str)
    {
        return sprintf("'%s'", $str);
    }

    $row = mysqli_fetch_assoc($result);
    $total = $row['total'];
//    if ( $total > 0 )
    $card_id = $_SESSION['card']['card_id'];
    Chromephp::log("card_id is $card_id");

    if (isset($_SESSION['card']['edit_mode'])) {
        /*     modifico il nome della vecchia card aggiungendo un asterisco e attribuisco alla nuova il nome di default:
         *     presumo infatti che la card sia salvata
         */

        //get all values of the old card

        $query = "SELECT * FROM card WHERE card_id='" . $_SESSION['card']['card_id'] . "'";
        $result = mysqli_query($conn, $query);
        $original_card = mysqli_fetch_assoc($result);
        //give the new card the id of the existing card
        $edited_card_id = $original_card['card_id'];
        $original_card_id = $original_card['card_id'] . '*';
        //change old card id with asterisk
        $original_card['card_id'] = $original_card_id;
        //duplicate the existing record adding the asterisk: so now in DB asterisk is old card
        $values = implode(',', array_map('quote', $original_card));
        $query = "INSERT INTO `card`(card_id, user_id, card_type, distributed_brand, category, sub_category, card_name, assigned_id, premium_paid, stripe_charge_id) VALUES ($values)";
        $result = mysqli_query($conn, $query);
        /*
         * rename card related images to add asterisk
         */
        $query = "select canvas_front, canvas_back from card_data where card_id='$card_id'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_row($result);
        $canvas_front = $row[0];
        $canvas_back = $row[1];
        $cf_arr = explode('.', $canvas_front);
        $old_card_front = $cf_arr[0] . '*' . $cf_arr[1];
        $cb_arr = explode('.', $canvas_back);
        $old_card_back = $cb_arr[0] . '*' . $cb_arr[1];
        ChromePhp::log('$canvas_front');
        ChromePhp::log($canvas_front);
        rename($export_absolute_path . $canvas_front, $export_absolute_path . $old_card_front);
        rename($export_absolute_path . $canvas_back, $export_absolute_path . $old_card_back);
        if (file_exists($logo_absolute_path . $edited_card_id . '.png')) {
            rename($logo_absolute_path . $edited_card_id . '.png', $logo_absolute_path . $edited_card_id . '*.png');
        }
        $data = $_REQUEST['base64data'];
        $data2 = addslashes(trim($_REQUEST['stringdata']));
        $image = explode('base64,', $data);
        $mt = microtime();
        $a = explode('.', $mt);
        $unique_time = str_replace(' ', '', end($a));
        $nameGen = $edited_card_id . "." . $unique_time . "-front";
        $insertImage = 'export/' . $nameGen . '.png';
        // store image
        $imagedisplay = file_put_contents('../../export/' . $nameGen . '.png', base64_decode($image[1]));
        //get all values of the old card_data to duplicate record
        $query = "SELECT canvas_front FROM card_data WHERE card_id='$edited_card_id'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_row($result);
        $old_canvas_path = explode('/', $row[0]);
        $old_canvas_front = end($old_canvas_path);
        if (file_exists($export_absolute_path . $old_canvas_front)) {
            unlink($export_absolute_path . $old_canvas_front);
        }
//        $original_card_data = mysqli_fetch_assoc($result);
        //        $original_card_data['card_id'] = $original_card_id;
        //        $original_card_data['canvas_front'] = "exports/$original_card_id-front.png";
        //        $original_card_data['canvas_back'] = "exports/$original_card_id-back.png";
        //        $values = implode(',', array_map('quote', $original_card_data));
        //        $query = "INSERT INTO card_data VALUES ($values)";
        //        mysqli_query($conn, $query);
        $query = "UPDATE card_data SET canvas_front='$insertImage', widgets_front='$data2' WHERE card_id='$edited_card_id'";
        mysqli_query($conn, $query);
    }
    /*
     * END IF EDIT MODE
     */else {
        $query = "insert into card (user_id, card_id, card_type, card_name, distributed_brand, category, sub_category) values ('" . $_SESSION['user_id'] . "', '" . $_SESSION['card']['card_id'] . "', '" . $_SESSION['card']['card_type'] . "', '" . $_SESSION['card']['card_name'] . "', '" . $_SESSION['card']['distributed_brand'] . "', '" . $_SESSION['card']['category'] . "', '" . $_SESSION['card']['sub_category'] . "')";
        mysqli_query($conn, $query);
        // SET AS DEFAULT IF NO OTHER CARDS EXIST FOR THIS USER
        $old_main_card_id = get_user_main_card($_SESSION['user_id'], $conn);
        if ($old_main_card_id === false) {
            $q_update_main_card = "update card set main_card='1' where card_id='$card_id'";
            mysqli_query($conn, $q_update_main_card);
        }
        $result = mysqli_query($conn, "SELECT user_id, default_card FROM user WHERE user_id = '" . $_SESSION['user_id'] . "'");
        if ($result) {
            $first_card = mysqli_fetch_row($result);

            if ($first_card[1] == null || $first_card == "") {
                mysqli_query($conn, "UPDATE user SET default_card = '" . $_SESSION['card']['card_id'] . "' WHERE user_id = '" . $_SESSION['user_id'] . "'");
            }
        }
        $data = $_REQUEST['base64data'];
        $data2 = addslashes(trim($_REQUEST['stringdata']));
        $image = explode('base64,', $data);
        $mt = microtime();
        $a = explode('.', $mt);
        $unique_time = str_replace(' ', '', end($a));
        $nameGen = $_SESSION['card']['card_id'] . "." . $unique_time . "-front";
        $insertImage = 'export/' . $nameGen . '.png';
// store image
        $imagedisplay = file_put_contents('../../export/' . $nameGen . '.png', base64_decode($image[1]));
        $query = "INSERT INTO card_data(user_id, card_id, landscape, canvas_front, widgets_front) VALUES ('" . $_SESSION['user_id'] . "', '" . $_SESSION['card']['card_id'] . "', '$landscape', '$insertImage', '$data2')";
//        ChromePhp::log('insert def 185');
        //        ChromePhp::log($query);
        mysqli_query($conn, $query);
//        ChromePhp::log('error: ' . mysqli_error($conn));
    }
}
/*
 * END IF CARD DOESN'T EXIST
 */

$card_settings_query = "";
//ChromePhp::log('card type is ' . $_SESSION['card']['card_type']);
switch (strtolower($_SESSION['card']['card_type'])) {
    case 'personal':
        $card_settings_query = "INSERT INTO personal_card_setting (user_id,card_id) VALUES ('" . $_SESSION['user_id'] . "', '" . $_SESSION['card']['card_id'] . "')";
        break;
    case 'corporate':
        $card_settings_query = "INSERT INTO personal_card_setting (user_id,card_id) VALUES ('" . $_SESSION['user_id'] . "', '" . $_SESSION['card']['card_id'] . "')";
        break;
    case 'professional':
        $card_settings_query = "INSERT INTO professional_card_setting (user_id,card_id) VALUES ('" . $_SESSION['user_id'] . "', '" . $_SESSION['card']['card_id'] . "')";
        break;
    case 'service':
        $card_settings_query = "INSERT INTO professional_card_setting (user_id,card_id) VALUES ('" . $_SESSION['user_id'] . "', '" . $_SESSION['card']['card_id'] . "')";
        break;
    case 'product':
        $card_settings_query = "INSERT INTO professional_card_setting (user_id,card_id) VALUES ('" . $_SESSION['user_id'] . "', '" . $_SESSION['card']['card_id'] . "')";
        break;
    default:
        //TODO: return error (unsure about best way atm)
        break;
}
//ChromePhp::log($card_settings_query);
mysqli_query($conn, $card_settings_query);

/*
 * check BCN
 */
if (!isset($_SESSION['card']['bcn']) || $_SESSION['card']['bcn'] === '') {
    $existing_bcn = array();
    $result = mysqli_query($conn, "SELECT assigned_id FROM card");
    while ($row = mysqli_fetch_array($result)) {
        $existing_bcn[] = $row['assigned_id'];
    }
//    $assigned_id = genCode( $c[ 1 ], $existing_bcn );
    //    $assigned_id = genCode( 2, 4, $existing_bcn );
    //    $assigned_id = genCode( $existing_bcn );
    $assigned_id = genCode($conn);
} else {
    $assigned_id = $_SESSION['card']['bcn'];
}
//ChromePhp::log('bcn: ' - $assigned_id);
// check if code is set
$check_assigned_id = mysqli_query($conn, "SELECT user_id, card_id, assigned_id FROM card WHERE user_id = '" . $_SESSION['user_id'] . "' AND card_id = '" . $_SESSION['card']['card_id'] . "'");
$b = mysqli_fetch_row($check_assigned_id);

if ($b[2] == "" || $b[2] == null) {
//    ChromePhp::log('$check_assigned_id is null');
    $query = "UPDATE card SET assigned_id = '" . $assigned_id . "' WHERE user_id = '" . $_SESSION['user_id'] . "' AND card_id = '" . $_SESSION['card']['card_id'] . "'";
//    ChromePhp::log('update 247');
    //    ChromePhp::log($query);
    mysqli_query($conn, $query);
}
//ChromePhp::log("check_assigned_id is $check_assigned_id");
//ChromePhp::log('error is ' . mysqli_error($conn));
/*
 *  end check BCN
 */

echo json_encode(array(
    "image" => $insertImage,
    "bcn" => $assigned_id));
