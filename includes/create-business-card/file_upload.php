<?php  
$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
$nameGen = uniqid();
$filename = $_FILES["file"]["tmp_name"];

if(empty($filename)){
	//echo 'File Does Not Exist'.$filename;
} else {

    list($width, $height) = getimagesize($filename);

    if ($width > 375 || $height > 375) {

        echo '<div class="alert alert-danger alert-dismissible custom-alert" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          Image dimensions too large!
        </div>';

    } else {

        if ((($_FILES["file"]["type"] == "image/gif")
            || ($_FILES["file"]["type"] == "image/jpeg")
            || ($_FILES["file"]["type"] == "image/jpg")
            || ($_FILES["file"]["type"] == "image/pjpeg")
            || ($_FILES["file"]["type"] == "image/x-png")
            || ($_FILES["file"]["type"] == "image/png"))
            && in_array($extension, $allowedExts)) {

                if ($_FILES["file"]["error"] > 0) {

                } else {

                    if (file_exists("uploads/" . $nameGen . "." . $extension)) {

                        echo $nameGen . "." . $extension . " <div style='panel-info'>already exists. </div>";

                    } else {

                        move_uploaded_file($_FILES["file"]["tmp_name"], $image = "uploads/" . $nameGen . "." . $extension);

                        $imageRelativePath = "includes/create-business-card/" . $image;
                        echo "<div class='new-image' style='width:".$width."px; height:".$height."px;'>
                        <span class='glyphicon glyphicon-remove del' style='cursor: pointer; position:absolute; top:0; right:0;'></span>
                        <img src='$imageRelativePath' width='100%' height='auto' style='width:100% !important; height:auto !important; height:100%;' />
                    </div>";

                    }
                }

        } else {

        echo '<div class="alert alert-danger alert-dismissible custom-alert" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          Invalid file!
        </div>';

        }

    }
}
?>
