<?php
  require_once '../database_config.php';
  include_once('../../utilities/request_result.php');
  include_once('../../utilities/mail.class.php');
  require_once('../../utilities/stripe_config.php');

  require_once('../../session_setup.php');

  $r = new Result();

  $token  = $_POST['stripeToken'];
  $user_id = $_SESSION["user_id"];
  $sub_plan = $_GET["plan_id"];
  $db_plan = $_GET["db_plan_id"];
  $card_id = $_GET["card_id"];
  $stripe_customer_id;

  $query = sprintf("select email_address, concat(last_name, ' ', first_name) as user_name from user where user_id = '%s'",$user_id);
  $result = $r->handleQuery($conn,$query);
  while($row = $result->fetch_object()){
    $user_email = $row->email_address;
	$user_name = $row->user_name;
  }

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
      'card'  => $token,
      'email' => $user_email,
      'description' => $user_name,
    ));
    $stripe_customer_id = $customer->id;
    $query = sprintf("UPDATE user SET stripe_customer_id='%s' WHERE user_id='%s'",$stripe_customer_id,$user_id);
    $result = $r->handleQuery($conn,$query);
  }else{
    $customer = Stripe_Customer::retrieve($stripe_customer_id);
  }

  //add subscription with stripe api
  $subscription = $customer->subscriptions->create(array("plan"=> $sub_plan));

  //add subscription to db
  $query = sprintf("INSERT INTO subscription (stripe_subscription_id,plan_id,card_id) VALUES ('%s','%s','%s')",$subscription->id,$db_plan,$card_id);
  $result = $r->handleQuery($conn,$query);

  //get the plan from db
  $query = sprintf("SELECT * FROM plan WHERE plan_id='%s'",$db_plan);
  $result = $r->handleQuery($conn,$query);
  while($row = $result->fetch_object()){
    $plan_db_obj = $row;
  }

  //translate plan to card settings
  $o = (object)array('visible_pp_search'=>0,'share_among_users'=>0,'allow_rating'=>0);
  if (strpos($plan_db_obj->shortcut, '1') !== false){$o->share_among_users = 1;}
  if (strpos($plan_db_obj->shortcut, '2') !== false){$o->visible_pp_search = 1;}
  if (strpos($plan_db_obj->shortcut, '3') !== false){$o->allow_rating = 1;}

  //update settings table to reflect new plan
  $query = sprintf("UPDATE professional_card_setting SET visible_pp_search='%d',share_among_users='%d',allow_rating='%d' WHERE card_id='%s'",
    $o->visible_pp_search,$o->share_among_users,$o->allow_rating,$card_id);
  $result = $r->handleQuery($conn,$query);

//  $url = $config->getBaseUrl()."manage-your-cards.php";
  $url = $config->getBaseUrl()."my-own-cards.php";

  if($r->success == 1){
    echo '<script>window.location.href = "'.$url.'";</script>';
  }else{
    echo json_encode($r);
  }

?>