<?php
  require_once 'includes/absolute_database_config.php';
  include_once('utilities/request_result.php');
  include_once('utilities/mail.class.php');
  require_once('utilities/stripe_config.php');

  $request_result = new Result();

  $user_id = $_SESSION["user_id"];
  $card_id = $_GET["card_id"];
  $has_subscription = true;
  $plan_id = "";
  $subscription_id = "";
  $stripe_subscription_id = "";
  $plan;

  $query = sprintf("SELECT * FROM subscription WHERE card_id='%s'",$card_id);
  $result = $request_result->handleQuery($conn,$query);
  while($row = $result->fetch_object()){
    $plan_id = $row->plan_id;
    $subscription_id = $row->subscription_id;
    $stripe_subscription_id = $row->stripe_subscription_id;
  }
  if($plan_id == ""){
    $has_subscription = false;
  }else{
    $query = sprintf("SELECT * FROM plan WHERE plan_id = '%s'",$plan_id);
    $result = $request_result->handleQuery($conn,$query);
    while($row = $result->fetch_object()){
      $plan = $row;
      $shortcut_db = $row->shortcut;
    }
  }
?>