<?php

//define('BASEURL', 'http://www.cardition.com/');
//define('BASEURL', 'http://bcfoldercrazy.webintenerife.com/');
	define('API_ACCESS_KEY', 'AIzaSyB1Q-z5srzpA2zbjKExgw1De7IHNeT7ixk'); //Firebase

function sendPush($to, $title, $message, $card_id, $user_id) {
	//define('API_ACCESS_KEY', 'AIzaSyAxpdnYiSgGMVtIYckiQryDLYVI_zJbSSg');//Google

	$registrationIds = array(
		$to);
	$msg = array
		(
		'message' => $message,
		'title' => $title,
		'vibrate' => 1,
		'sound' => 1,
		'style' => 'inbox',
		'cardId' => $card_id,
		'userId' => $user_id
// you can also add images, additionalData
	);
	$fields = array
		(
		'registration_ids' => $registrationIds,
		'data' => $msg
	);
	$headers = array
		(
		'Authorization: key=' . API_ACCESS_KEY,
		'Content-Type: application/json'
	);
	$ch = curl_init();
//	curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
	curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	$result = curl_exec($ch);
	curl_close($ch);
//	echo $result;
}

function sendPushPicture($to, $title, $message, $card_id, $bcn, $user_id) {
	//define('API_ACCESS_KEY', 'AIzaSyAxpdnYiSgGMVtIYckiQryDLYVI_zJbSSg');//Google
//	define('API_ACCESS_KEY', 'AIzaSyB1Q-z5srzpA2zbjKExgw1De7IHNeT7ixk'); //Firebase
	$registrationIds = array(
		$to);
	$msg = array
		(
		'message' => $message,
		'title' => $title,
		'vibrate' => 1,
		'sound' => 1,
		'style' => 'picture',
		'cardId' => $card_id,
		'userId' => $user_id,
		'picture' => 'http://www.cardition.com/export/' . $card_id . '-front.png',
		'summaryText' => 'Card ' . $bcn . ' added'

// you can also add images, additionalData
	);
	$fields = array
		(
		'registration_ids' => $registrationIds,
		'data' => $msg
	);
	$headers = array
		(
		'Authorization: key=' . API_ACCESS_KEY,
		'Content-Type: application/json'
	);
	$ch = curl_init();
//	curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
	curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	$result = curl_exec($ch);
	curl_close($ch);
//	echo $result;
}

function sendPushRequest($to, $title, $message, $request_id, $user_id) {
//	mail('webintenerife@gmail.com', 'sending push', 'Entering sendPushRequest() function for request '.$request_id);
	//define('API_ACCESS_KEY', 'AIzaSyAxpdnYiSgGMVtIYckiQryDLYVI_zJbSSg');//Google
//	define('API_ACCESS_KEY', 'AIzaSyB1Q-z5srzpA2zbjKExgw1De7IHNeT7ixk'); //Firebase
	$registrationIds = array(
		$to);
	$msg = array(
		'message' => $message,
		'title' => $title,
		'vibrate' => 1,
		'sound' => 1,
		'requestId' => $request_id,
		'userId' => $user_id,
		'actions' => array(
			array("title" => "ACCEPT", "callback" => "app.acceptRequest", "foreground" => true),
			array("title" => "REJECT", "callback" => "app.refuseRequest", "foreground" => true)
		),
		'style' => 'inbox',
		// you can also add images, additionalData
	);
	$fields = array
		(
		'registration_ids' => $registrationIds,
		'data' => $msg
	);
	$headers = array
		(
		'Authorization: key=' . API_ACCESS_KEY,
		'Content-Type: application/json'
	);
	$ch = curl_init();
//	curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
	curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	$result = curl_exec($ch);
	curl_close($ch);
//	echo $result;
}

class PushNotifications {

	public $recipients = array();
	public $title;
	public $message;

	public function __construct() {
//		mail('marqus.gs@gmail.com', 'PN created', 'PushNotifications class has been created');
	}

	public function sendPush() {
		//define('API_ACCESS_KEY', 'AIzaSyAxpdnYiSgGMVtIYckiQryDLYVI_zJbSSg');//Google
//		define('API_ACCESS_KEY', 'AIzaSyB1Q-z5srzpA2zbjKExgw1De7IHNeT7ixk'); //Firebase
//		$registrationIds = array(
//			$this->recipients);
		$msg = array
			(
			'message' => $this->message,
			'title' => $this->title,
			'vibrate' => 1,
			'sound' => 1

// you can also add images, additionalData
		);
		$fields = array
			(
//			'registration_ids' => $registrationIds,
			'registration_ids' => $this->recipients,
			'data' => $msg
		);
		$headers = array
			(
			'Authorization: key=' . API_ACCESS_KEY,
			'Content-Type: application/json'
		);
		$ch = curl_init();
//	curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
		curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch);
		curl_close($ch);
		echo $result;
	}

}
