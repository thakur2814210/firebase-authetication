<?php
require_once('stripe-php-1.17.3/lib/Stripe.php');
$stripe = array(
  "secret_key"      => "sk_test_jK3Vlf5FipJAXdNtixvOAxX5",
  "publishable_key" => "pk_test_cYr0CXtII4dszYZ6SYDJC8u9"
);

Stripe::setApiKey($stripe['secret_key']);
?>