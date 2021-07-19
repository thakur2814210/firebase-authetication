<?php
  require_once 'includes/absolute_database_config.php';
  include_once('utilities/request_result.php');
  include_once('utilities/mail.class.php');
  require_once('utilities/stripe_config.php');

  $request_result = new Result();

  $user_id = $_SESSION["user_id"];
  $card_id = $_GET["card_id"];
  $has_subscription = true;
  $shortcut_param = $_GET["shortcut"];
  $shortcut_db;
  $plan_id = "";

  $old_plan;
  $new_plan;

  $query = sprintf("SELECT * FROM subscription WHERE card_id='%s'",$card_id);
  $result = $request_result->handleQuery($conn,$query);
  while($row = $result->fetch_object()){
    $plan_id = $row->plan_id;
  }
  if($plan_id == ""){
    $has_subscription = false;
  }else{
    $query = sprintf("SELECT * FROM plan WHERE plan_id = '%s'",$plan_id);
    $result = $request_result->handleQuery($conn,$query);
    while($row = $result->fetch_object()){
      $old_plan = $row;
      $shortcut_db = $row->shortcut;
    }
  }

  $query = sprintf("SELECT * FROM plan WHERE shortcut = '%s'",$shortcut_param);
  $result = $request_result->handleQuery($conn,$query); 
  while($row = $result->fetch_object()){
    $new_plan = $row;
  }

  $plan_result = Stripe_Plan::retrieve($new_plan->stripe_plan_id);
?>