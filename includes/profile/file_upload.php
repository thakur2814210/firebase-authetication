<?php  

require_once('../../session_setup.php');

$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
//$nameGen = uniqid();
$nameGen = $_SESSION['user_id'] . "-profile";
$filename = $_FILES["file"]["tmp_name"];
list($width, $height) = getimagesize($filename);

if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/x-png")
|| ($_FILES["file"]["type"] == "image/png"))
&& in_array($extension, $allowedExts)) {
	
	if ($_FILES["file"]["error"] > 0) {
	  
	} else {
		
		if (move_uploaded_file($_FILES["file"]["tmp_name"], $image = "uploads/" . $nameGen . "." . $extension)) {
		
		$imageRelativePath = "includes/profile/" . $image;
		
		//cadd to db
		require_once '../database_config.php'; 
		
		$update = mysqli_query($conn, 
		"UPDATE user 
		SET  profile_image = '".$imageRelativePath."'       
		WHERE user_id = '".$_SESSION['user_id']."'");	
		
		if ($update) {
			echo $imageRelativePath;	
		}
		
		}
	}
} else {
	
	echo "Invalid file";
}
?>

      
    


