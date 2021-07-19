<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

function resize_image_old($source_pic, $destination_pic, $type = 'jpg', $new_width = 538, $new_height = 339, $unlink_src = false) {
	if ($type == 'jpg') {
		$src = imagecreatefromjpeg($source_pic);
//	}else if ($extension == 'png')
	} else {
		$src = imagecreatefrompng($source_pic);
	}
	list($width, $height) = getimagesize($source_pic);
	$tmp = imagecreatetruecolor($new_width, $new_height);
	imagecopyresampled($tmp, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	if ($type == 'jpg') {
		imagejpeg($tmp, $destination_pic, 100);
	} else {
		imagepng($tmp, $destination_pic, 0);
	}
	imagedestroy($src);
	imagedestroy($tmp);
	if ($unlink_src) {
		unlink($source_pic);
	}
}

function resize_image($image_string, $card_id, $extension= 'jpg', $new_width = 538, $new_height = 339, $unlink_src = false){
	$data_front = explode('base64', $image_string);
	$canvas_front = base64_decode($data_front[1]);
	$new_image = imagecreatefromstring($canvas_front);
	if ($extension == 'jpg'){
		$source = 'temp.jpg';
		imagejpeg($new_image, $source, 0);
	}else if($extension == 'png'){
		$source = 'ciuccia.png';
		imagepng($new_image, $source, 0);
	}else{
		$source = 'ciuccia.gif';
		imagegif($new_image, $source, 0);
	}
	list($width, $height) = getimagesize($source);
	$tmp = imagecreatetruecolor($new_width, $new_height);
	imagecopyresampled($tmp, $new_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);		
	$final = get_card_picture_name($card_id,$extension);
	if ($extension == 'jpg'){
		imagejpeg($tmp, $final, 0);
	}else if($extension == 'png'){
		imagepng($tmp, $final, 0);
	}else{
		imagegif($tmp, $final, 0);
	}
	if($unlink_src){
		unlink($source);
	}
	return $final;
}

function get_card_picture_name($card_id, $ext, $back = false) {
	$mt = microtime();
	$a = explode('.', $mt);
	$unique_time = str_replace(' ', '', end($a));
	if (!$back) {
		$card_picture_name = $card_id . "." . $unique_time . '-front.' . $ext;
	} else {
		$card_picture_name = $card_id . "." . $unique_time . '-back.' . $ext;
	}
	return $card_picture_name;
}

if (isset($_POST['submit'])) {
	$imagestring_front = $_POST['encoded_image'];

	if (stristr($imagestring_front, '/jpeg;')) {
		$extension = 'jpg';
	} else if (stristr($imagestring_front, '/png;')) {
		$extension = 'png';
	} else {
		$extension = 'gif';
	}
	
//
//	if ($extension == 'jpg'){
//		$dest = 'ciuccia.jpg';
//		imagejpeg($new_image, $dest, 0);
//	}else if($extension == 'png'){
//		$dest = 'ciuccia.png';
//		imagepng($new_image, $dest, 0);
//	}else{
//		$dest = 'ciuccia.gif';
//		imagegif($new_image, $dest, 0);
//	}
//	
//	$final = get_card_picture_name(uniqid(), $extension);
	$my_image = resize_image($imagestring_front, uniqid(), $extension);
	echo "<img src=$my_image />";
}
?>
<form method="post">
	<textarea col="30" rows="30" name="encoded_image"></textarea>
	<br>
	<input type="submit" value="Upload Image" name="submit">
</form>