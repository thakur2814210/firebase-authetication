<?php

require_once '../database_config.php';
include_once('../../utilities/request_result.php');
include_once('../../utilities/mail.class.php');
require_once('../../utilities/stripe_config.php');

require_once('../../session_setup.php');
$request_result = new Result();
$token = $_POST[ 'stripeToken' ];
$user_id = $_SESSION[ "user_id" ];
//$card_id = $_SESSION[ 'card' ][ "card_id" ];
$card_id = $_POST[ "card_id" ];
$prem_id = $_SESSION[ "prem_id" ];
$stripe_customer_id;

$query = sprintf( "select email_address, concat(last_name, ' ', first_name) as user_name from user where user_id = '%s'", $user_id );
$result = $request_result->handleQuery( $conn, $query );
while ( $row = $result->fetch_object() )
{
	$user_email = $row->email_address;
	$user_name = $row->user_name;
}

$query = sprintf( "SELECT stripe_customer_id FROM user WHERE user_id = '%s'", $user_id );
$result = $request_result->handleQuery( $conn, $query );
while ( $row = $result->fetch_object() )
{
	$stripe_customer_id = $row->stripe_customer_id;
}
$notStripeCustomer = false;
if ( $stripe_customer_id === NULL )
{
	$notStripeCustomer = true;
}

if ( $notStripeCustomer )
{
	$customer = Stripe_Customer::create( array(
							'card' => $token,
							'email' => $user_email,
							'description' => $user_name,
					) );
	$stripe_customer_id = $customer->id;
	$query = sprintf( "UPDATE user SET stripe_customer_id='%s' WHERE user_id='%s'", $stripe_customer_id, $user_id );
	$result = $request_result->handleQuery( $conn, $query );
}

$charge = Stripe_Charge::create( array(
						'customer' => $stripe_customer_id,
						'amount' => 1000,
						'currency' => 'gbp'
				) );

$query = sprintf( "UPDATE card SET premium_paid=true,assigned_id='%s',stripe_charge_id='%s' WHERE card_id='%s'", $prem_id, $charge->id, $card_id );
$result = mysqli_query( $conn, $query );
//$result = $request_result->handleQuery( $conn, $query );
if ( $result )
{
	//unset($_SESSION["prem_id"]);
}
$url = $config->getBaseUrl() . "my-own-cards.php";
echo '<script>window.location.href = "' . $url . '";</script>';
?>

