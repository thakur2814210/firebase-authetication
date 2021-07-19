<?php
  require_once 'includes/absolute_database_config.php';
  include_once(__DIR__ . '../../utilities/request_result.php');
  require_once(__DIR__ . '../../utilities/stripe_config.php');

  $request_result = new Result();

  $user_id = $_SESSION["user_id"];
  $shorcut = $_GET["shortcut"];
  $plan_monthly;
  $plan_yearly;

  $query = sprintf("SELECT * FROM plan WHERE shortcut='%s'",$shorcut."_m");
  $result = $request_result->handleQuery($conn,$query);
  while($row = $result->fetch_object()){
    $plan_id = $row->stripe_plan_id;
  }

  $plan_monthly = Stripe_Plan::retrieve($plan_id);

  $query = sprintf("SELECT * FROM plan WHERE shortcut='%s'",$shorcut."_y");
  $result = $request_result->handleQuery($conn,$query);
  while($row = $result->fetch_object()){
    $plan_id = $row->stripe_plan_id;
  }

  $plan_yearly = Stripe_Plan::retrieve($plan_id);
?>