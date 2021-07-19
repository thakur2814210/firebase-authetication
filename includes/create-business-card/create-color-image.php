<?php

error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );
require_once '../../session_setup.php';
$background_absolute_path = $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/create-business-card/uploads/background/';
$logo_absolute_path = $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/create-business-card/uploads/';
function hex2RGB( $hexStr, $returnAsString = false, $seperator = ',' )
{
	$hexStr = preg_replace( "/[^0-9A-Fa-f]/", '', $hexStr ); // Gets a proper hex string
	$rgbArray = array();
	if ( strlen( $hexStr ) == 6 )
	{ //If a proper hex code, convert using bitwise operation. No overhead... faster
		$colorVal = hexdec( $hexStr );
		$rgbArray[ 'red' ] = 0xFF & ($colorVal >> 0x10);
		$rgbArray[ 'green' ] = 0xFF & ($colorVal >> 0x8);
		$rgbArray[ 'blue' ] = 0xFF & $colorVal;
	}
	elseif ( strlen( $hexStr ) == 3 )
	{ //if shorthand notation, need some string manipulations
		$rgbArray[ 'red' ] = hexdec( str_repeat( substr( $hexStr, 0, 1 ), 2 ) );
		$rgbArray[ 'green' ] = hexdec( str_repeat( substr( $hexStr, 1, 1 ), 2 ) );
		$rgbArray[ 'blue' ] = hexdec( str_repeat( substr( $hexStr, 2, 1 ), 2 ) );
	}
	else
	{
		return false; //Invalid hex color code
	}
	return $returnAsString ? implode( $seperator, $rgbArray ) : $rgbArray; // returns the rgb string or the associative array
}
$color = filter_input( INPUT_GET, 'theColor' );
$landscape = filter_input( INPUT_GET, 'landscape' );
$bgWidth = filter_input( INPUT_GET, 'bgWidth' );
$bgHeight = filter_input( INPUT_GET, 'bgHeight' );
//$color = filter_input( INPUT_POST, 'theColor' );
//$landscape = filter_input( INPUT_POST, 'landscape' );
//$bgWidth = filter_input( INPUT_POST, 'bgWidth' );
//$bgHeight = filter_input( INPUT_POST, 'bgHeight' );
if ( $landscape === 0 )
{
	$orientation = 'portrait';
}
else
{
	$orientation = 'landscape';
}
$rgb = hex2RGB( $color, true );
$rgb_color = explode( ',', $rgb );
$my_img = imagecreate( $bgWidth, $bgHeight );
$my_img_portrait = imagecreate($bgHeight, $bgWidth);
imagecolorallocate( $my_img, $rgb_color[ 0 ], $rgb_color[ 1 ], $rgb_color[ 2 ] );
imagecolorallocate($my_img_portrait, $rgb_color[0], $rgb_color[1], $rgb_color[2]);
$card_id = $_SESSION['card']['card_id'];
$fname = $card_id . "-front.png";
$fname_portrait = $card_id . "-front-portrait.png";
/*
 * if in edit_mode
 * if user change background color we need to rename existing image in order to can go back to previous one
 * before to upload the new one
 */

if ( isset( $_SESSION[ 'card' ][ 'edit_mode' ] ) )
{
	if ( file_exists( $background_absolute_path . $fname ) )
	{
		rename( $background_absolute_path . $fname, $background_absolute_path . $_SESSION[ 'card' ][ 'card_id' ] . '*.png' );
	}
}

$save = "uploads/background/$fname";
$save_portrait = "uploads/background/$fname_portrait";
imagepng($my_img_portrait, $save_portrait);
if ( imagepng( $my_img, $save ) )
{
	echo $fname;
}
