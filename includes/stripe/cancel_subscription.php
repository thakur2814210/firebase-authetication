<?php
  require_once '../database_config.php';
  include_once('../../utilities/request_result.php');
  require_once('../../utilities/stripe_config.php');

  require_once('../../session_setup.php');

  $r = new Result();

  $user_id = $_SESSION["user_id"];
  $subscription = $_GET["subscription"];
  $stripe_subscription_id = $_GET["stripe_subscription_id"];

  $query = sprintf("SELECT stripe_customer_id FROM user WHERE user_id = '%s'",$user_id);
  $result = $r->handleQuery($conn,$query);
  while($row = $result->fetch_object()){
    $stripe_customer_id = $row->stripe_customer_id;
  }

  $customer = Stripe_Customer::retrieve($stripe_customer_id);
  $customer->subscriptions->retrieve($stripe_subscription_id)->cancel();

  //get card_id from subscription
  $query = sprintf("SELECT card_id FROM subscription WHERE subscription_id='%s'",$subscription);
  $result = $r->handleQuery($conn,$query);
  while($row = $result->fetch_object()){
      $card_id = $row->card_id;
  }

  //update card settings to be blank
  $query = sprintf("UPDATE professional_card_setting SET visible_pp_search='%d',share_among_users='%d',allow_rating='%d' WHERE card_id='%s'",0,0,0,$card_id);
  $result = $r->handleQuery($conn,$query);

  //remove subscription
  $query = sprintf("DELETE FROM subscription WHERE subscription_id='%s'",$subscription);
  $result = $r->handleQuery($conn,$query);

  echo json_encode($r);

?>