<?php
/*
 * WEBSITE SCRIPT
 * SHARE CARDS USER HOLDS IN HIS FOLDERS WITH OTHER BCFOLDER'S MEMBERS
 * CALLED BY MG_CONTACTS.JS IN FUNCTION shareCardWithContacts()
 */

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once('../session_setup.php');
require_once 'database_config.php';
include_once('../utilities/mail.class.php');
require_once __DIR__ . '/../ChromePhp.php';
require_once('push_notifications.php');
//$site_url = "https://www.cardition.com/";
$site_url = "http://www.cardition.com/";
$user_id = $_SESSION['user_id'];
$query = "SELECT first_name, last_name, email_address, profile_image FROM user WHERE user_id='$user_id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_row($result);
$sender_name = $row[0] . ' ' . $row[1];
$sender_email = $row[2];
$user_name = $sender_name;
$author_profile = $row[3];
if ($author_profile == '') {
	$author_profile = 'assets/img/def_avatar.gif';
}
$date_string = $_POST['date_string'];
$card_ids = explode(',', $_POST['card_ids']);
$email_addresses = explode(',', $_POST['emails']);
if (count($card_ids) == 1) {
	$one_card_only = true;
}
/*
 * select cards data
 */
$cards = array();
$card = array();
foreach ($card_ids as $c) {
	$query = "SELECT c.user_id, c.assigned_id, c.card_name, c.card_type, cd.canvas_front FROM card c LEFT JOIN card_data cd ON cd.card_id='$c' WHERE c.card_id='$c'";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_row($result);
	$card_bcn = $row[1];
	$card_user_id = $row[0];
	$card_name = $row[2];
	$card_type = $row[3];
	$card_picture = $row[4];

	if ($card_type = 'Personal' || $card_type == 'Corporate') {
		$query = "SELECT share_among_users FROM personal_card_setting WHERE card_id='$c'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_row($result);
		$share_among_users = $row[0];
	}

	if (($card_type == 'Product' || $card_type == 'Service' || $card_type == 'Professional' ) || $share_among_users == 1) {
		$query = "SELECT first_name, last_name FROM user WHERE user_id='$card_user_id'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_row($result);
		$card_owner = $row[0] . ' ' . $row[1];
		$card['card_user_id'] = $card_user_id;
		$card['card_owner'] = $card_owner;
		$card['card_name'] = $card_name;
		$card['card_bcn'] = $card_bcn;
		$card['card_id'] = $c;
		$card['card_picture'] = $card_picture;
		$cards[] = $card;
	}
}

/*
 * select recipients' data
 */
$recipients = array();
$recipient = array();
foreach ($email_addresses as $e) {
	$query = "SELECT first_name, last_name, user_id, reg_id, profile_image FROM user WHERE email_address='$e'";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_row($result);
//	$recipient[ 'name' ] = $row[ 0 ] . ' ' . $row[ 1 ];
	$recipient['name'] = $row[0];
	$recipient['recipient_id'] = $row[2];
	$recipient['email_address'] = $e;
	$recipient['recipient_reg_id'] = $row[3];
	$recipient_profile_image = $row[4];
	if ($recipient_profile_image == '') {
		$recipient_profile_image = 'assets/img/def_avatar.gif';
	}
	$recipient['recipient_profile_image'] = $recipient_profile_image;
	$recipient['recipient_full_name'] = $row[0] . ' ' . $row[1];
	$recipients[] = $recipient;
}

/*
 * build message
 * we also check if the recipient is != from card owner
 * and if recipient owns all cards is put in a blacklist: he wont receive email
 */
$black_list = array();
$white_list = array();
foreach ($recipients as $r) {
	$msg = "";
	$blocked = false;
	$counter = 0;
	$card_list = '';
	foreach ($cards as $c) {
		if ($c['card_user_id'] != $r['recipient_id']) {
//			if counter is even we open a new row
			$white_list[] = $c['card_bcn'];
			if ($counter % 2 == 0) {
				$card_item_opening = '<tr><td align="center" style="display: inline-block;">';
				if ($counter < count($cards)){
					$card_item_closing = '';
				}else{
					$card_item_closing = '</td></tr>';
				}
			} else {
				$card_item_opening = '';
				$card_item_closing = '</td></tr>';
			}
			$card_item = $card_item_opening;
			$card_item .= <<<ITM
                  <table width="150" border="0" cellspacing="0" cellpadding="0" align="left" class="full">
                    <tbody style="border-collapse: collapse !important;">
                      <tr>
                        <td valign="top" align="center" style="padding: 10px;">
                          <table width="210" border="0" cellspacing="0" cellpadding="0">
                            <tbody>
                              <tr>
                                <td colspan="3" valign="bottom">
                                  <img src="http://www.cardition.com/email-templates/images/shadow_top.png" width="220" style="vertical-align: bottom; display: block;" class="shadow_top">
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <img src="http://www.cardition.com/email-templates/images/shadow_left.png" width="4" height="140" style="display: block;" class="shadow_left">
                                </td>
                                <td>
                                  <img src="$site_url{$c['card_picture']}" width="210" height="140" style="display: block;" class="main-image">
                                </td>
                                <td>
                                  <img src="http://www.cardition.com/email-templates/images/shadow_right.png" width="4" height="140" style="display: block;" class="shadow_right">
                                </td>
                              </tr>
                              <tr>
                                <td colspan="3" valign="top">
                                  <img src="http://www.cardition.com/email-templates/images/shadow_bottom.png" width="220" style="vertical-align: top; display: block;" class="shadow_bottom">
                                </td>
                              </tr>
                              <tr>
                                <td colspan="3" align="center">
                                  <table width="97%" border="0" cellpadding="0" cellspacing="0">
                                    <tbody>
                                      <tr>
                                        <td valign="middle" width="80%">
                                          <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                            <tbody>
                                              <tr>
                                                <td width="20"></td>
                                                <td><p style="text-align:left; margin: 0; font-size: 15px; font-weight: 500;color:#585858;">BCN: {$c['card_bcn']}</p></td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </td>
                                        <td align="center" valign="middle">
                                          <table border="0" cellpadding="0" cellspacing="0">
                                            <tbody>
                                              <tr>
                                                <td align="center" valign="middle">
												  <a href="{$site_url}includes/card-link/addCard.php?cardId={$c['card_id']}" target="_blank" style="text-decoration:none;"><img src="http://www.cardition.com/email-templates/images/plus.png" width="50" height="50" class="plus-btn"></a>
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
ITM;
			$card_item .= $card_item_closing;
			$card_list .= $card_item;
//			$card_id = $c['card_id'];
//			$bcn = $c['card_bcn'];
//			$card_owner = $c['card_owner'];
//			$card_canvas = $c['card_picture'];
//			$url = $site_url . "member_card_viewer.php?viewcard=$card_id";
			$counter++;
		}
	}

	$tokens = array(
		'{{__RECIPIENT_FULL_NAME__}}',
		'{{__RECIPIENT_PROFILE__}}',
		'{{__AUTHOR_PROFILE__}}',
		'{{__AUTHOR_FULL_NAME__}}',
		'{{__CARD_LIST__}}',
		'{{__TARGET_URL__}}'
	);
	$replacements = array(
		$r['recipient_full_name'],
		BASEURL . $r['recipient_profile_image'],
		BASEURL . $author_profile,
		$user_name,
		$card_list,
		$url
	);

	$raw_message = file_get_contents('../email-templates/card_shared/index.html');

	$message = str_replace($tokens, $replacements, $raw_message);
	/*
	 * if this user has no card to receive because he owns each card
	 * then his email is put in black list
	 */
	if (count($white_list) == 0) {
		$black_list[] = $r['email_address'];
		$blocked = true;
	}

	$from = $sender_email;
//	$subject = "$sender_name wants to share some cards!";
	$subject = "Card(s) shared!";
	if (!$blocked) {
		$app_notification = "$sender_name shared with you some cards.";
		$query2 = sprintf("INSERT INTO notifications (notification_type, event, event_date, recipient_user_id, active_user_id) VALUES ('%s','%s', '%s', '%s')", 'card_shared', $app_notification, $date_string, $r['recipient_id'], $user_id);
		$result2 = mysqli_query($conn, $query2);

		/* limiting table to 20 records per user */
		$query3 = "SELECT COUNT(*) as total FROM notifications WHERE recipient_user_id='{$r['recipient_id']}'";
		$result3 = mysqli_query($conn, $query3);
		if ($result3) {
			$row = mysqli_fetch_assoc($result3);
			if ($row['total'] > 20) {
				$query4 = "DELETE FROM `notifications` WHERE recipient_user_id='{$r['recipient_id']}' AND id NOT IN (	SELECT id FROM (SELECT id	FROM `notifications` ORDER BY id DESC	LIMIT 20) foo )";
				$result4 = mysqli_query($conn, $query4);
			}
		}
		$mailer = new Mailer();
		$mail_result = $mailer->send_mail(
				$from, $sender_name, $r['email_address'], $subject, $message
		);

		sendPush($r['recipient_reg_id'], $subject, $app_notification, implode(';', $card_ids), $user_id);
	}
}
