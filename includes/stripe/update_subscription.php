<?php
  require_once '../database_config.php';
  include_once('../../utilities/request_result.php');
  include_once('../../utilities/mail.class.php');
  require_once('../../utilities/stripe_config.php');

  require_once('../../session_setup.php');

  $r = new Result();

  $user_id = $_SESSION["user_id"];
  $card_id = $_GET["card_id"];

  $new_plan_id = $_GET["new_plan_id"];
  
  $subscription_db_obj;
  $plan_db_obj;
  $stripe_customer_id;

  $query = sprintf("SELECT stripe_customer_id FROM user WHERE user_id = '%s'",$user_id);
  $result = $r->handleQuery($conn,$query);
  while($row = $result->fetch_object()){
    $stripe_customer_id = $row->stripe_customer_id;
  }
  $notStripeCustomer = false;
  if($stripe_customer_id === NULL){
    $notStripeCustomer = true;
  }
  
  if($notStripeCustomer){
    $customer = Stripe_Customer::create(array(
      'card'  => $token
    ));
    $stripe_customer_id = $customer->id;
    $query = sprintf("UPDATE user SET stripe_customer_id='%s' WHERE user_id='%s'",$stripe_customer_id,$user_id);
    $result = $r->handleQuery($conn,$query);
  }else{
    $customer = Stripe_Customer::retrieve($stripe_customer_id);
  }

  //get current subscription from db
  $query = sprintf("SELECT * FROM subscription WHERE card_id='%s'",$card_id);
  $result = $r->handleQuery($conn,$query);
  while($row = $result->fetch_object()){
    $subscription_db_obj = $row;
  }

  //get the new plan from db
  $query = sprintf("SELECT * FROM plan WHERE plan_id='%s'",$new_plan_id);
  $result = $r->handleQuery($conn,$query);
  while($row = $result->fetch_object()){
    $plan_db_obj = $row;
  }

  //translate plan to card settings
  $o = (object)array('visible_pp_search'=>0,'share_among_users'=>0,'allow_rating'=>0);
  if (strpos($plan_db_obj->shortcut, '1') !== false){$o->share_among_users = 1;};
  if (strpos($plan_db_obj->shortcut, '2') !== false){$o->visible_pp_search = 1;};
  if (strpos($plan_db_obj->shortcut, '3') !== false){$o->allow_rating = 1;};

  //update settings table to reflect new plan
  $query = sprintf("UPDATE professional_card_setting SET visible_pp_search='%d',share_among_users='%d',allow_rating='%d' WHERE card_id='%s'",
    $o->visible_pp_search,$o->share_among_users,$o->allow_rating,$card_id);
  $result = $r->handleQuery($conn,$query);

  //update subscription stored on stripe
  $subscription = $customer->subscriptions->retrieve($subscription_db_obj->stripe_subscription_id);
  $subscription->plan = $plan_db_obj->stripe_plan_id;
  $subscription->save();

  //update our subscription in db with new plan
  $query = sprintf("UPDATE subscription SET plan_id='%s' WHERE card_id='%s'",$new_plan_id,$card_id);
  $result = $r->handleQuery($conn,$query);

  echo json_encode($r);

?>