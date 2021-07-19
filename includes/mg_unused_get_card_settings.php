<?php
if ( !empty( $_REQUEST[ 'card_id' ] ) )
{
	$_SESSION[ 'card' ][ 'card_id' ] = $_REQUEST[ 'card_id' ];
}
require_once 'create-business-card/get_business_card_details_rev_2.php';
echo $card->allow_rating;
