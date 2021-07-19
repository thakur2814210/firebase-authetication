<?php
require_once('../../session_setup.php');
function getTimestamp()
{
	date_default_timezone_set( 'Europe/London' );
	return date( "YmdTHis" ) . substr( ( string ) microtime(), 1, 8 );
}
function resize_image($source_pic, $destination_pic, $new_width = 800, $new_height = 600, $type = 'jpg', $unlink_src = false) {

	if ($type == 'jpg') {
		$src = imagecreatefromjpeg($source_pic);
	} else {
		$src = imagecreatefrompng($source_pic);
	}
	list($width, $height) = getimagesize($source_pic);


	$tmp = imagecreatetruecolor($new_width, $new_height);
	imagecopyresampled($tmp, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);


//	if ($type == 'jpg'){
//		imagejpeg( $tmp, $destination_pic, 100 );
//	}else{
	imagepng($tmp, $destination_pic, 0);
//	}
	imagedestroy($src);
	imagedestroy($tmp);

	if ($unlink_src) {
		unlink($source_pic);
	}
}
//function resize_image( $source_pic, $destination_pic = "auto", $new_width = 800, $new_height = 600, $unlink_src = false )
//{
//	$src = imagecreatefromjpeg( $source_pic );
//	list($width, $height) = getimagesize( $source_pic );
//
//	$x_ratio = $new_width / $width;
//	$y_ratio = $new_height / $height;
//
//	if ( ($x_ratio * $height) < $new_height )
//	{
//		$tn_height = ceil( $x_ratio * $height );
//		$tn_width = $new_width;
//	}
//	else
//	{
//		$tn_width = ceil( $y_ratio * $width );
//		$tn_height = $new_height;
//	}
//
//	$tmp = imagecreatetruecolor( $tn_width, $tn_height );
//	imagecopyresampled( $tmp, $src, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height );
//
//	if ( $destination_pic == "auto" )
//	{
//		$filearr = explode( ".", $source_pic );
//		$basename = array_shift( $filearr );
//		$destination_pic = $basename . "_resized.jpg";
//	}
//
//	imagejpeg( $tmp, $destination_pic, 100 );
//	imagedestroy( $src );
//	imagedestroy( $tmp );
//
//	if ( $unlink_src )
//	{
//		unlink( $source_pic );
//	}
//}
$now = getTimestamp();
$allowedExts = array(
		"gif",
		"jpeg",
		"jpg",
		"png" );
$temp = explode( ".", $_FILES[ "file" ][ "name" ] );
$extension = strtolower( end( $temp ) );
$_SESSION['card']['back_extension'] = $extension;
//$nameGen = $_SESSION[ 'card' ][ 'card_id' ].'-back.'; //uniqid();
$nameGen = get_card_picture_name($_SESSION[ 'card' ][ 'card_id' ], $extension, true);
$filename = $_FILES[ "file" ][ "tmp_name" ];
//print_r($_FILES);
list($width, $height) = getimagesize( $filename );
$filesize = filesize( $filename );
//if ($width > 680 || $height > 375) {
//if ( $filesize > 1000000 )
//{
//	echo '<div class="alert alert-danger alert-dismissible custom-alert" role="alert">
//          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
//          Image size too large! (Maximum allowed size is 2MB)
//        </div>';
//}
//else
//{
	if ( (($_FILES[ "file" ][ "type" ] == "image/gif") || ($_FILES[ "file" ][ "type" ] == "image/jpeg") || ($_FILES[ "file" ][ "type" ] == "image/jpg") || ($_FILES[ "file" ][ "type" ] == "image/pjpeg") || ($_FILES[ "file" ][ "type" ] == "image/x-png") || ($_FILES[ "file" ][ "type" ] == "image/png")) && in_array( $extension, $allowedExts ) )
	{
		if ( $_FILES[ "file" ][ "error" ] > 0 )
		{
			
		}
		else
		{
			move_uploaded_file( $_FILES[ "file" ][ "tmp_name" ], $image = "uploads/background/" . $nameGen . $extension );
//			resize_image($image, $image, 538, 339);
//			$imageRelativePath = "includes/create-business-card/" . $image;
			$image2 = "uploads/background/" . $nameGen . "-back.png";
			resize_image($image, $image2, 538, 339, $extension);
			$imageRelativePath = "includes/create-business-card/uploads/background/" . $nameGen . "-back.png";			
			echo $imageRelativePath;
		}
	}
//	else
//	{
//
//		echo '<div class="alert alert-danger alert-dismissible custom-alert" role="alert">
//              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
//              Invalid file!
//            </div>';
//	}
//}
