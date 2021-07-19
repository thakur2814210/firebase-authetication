<?php
$allowedExts = array(
		"gif",
		"jpeg",
		"jpg",
		"png" );
$temp = explode( ".", $_FILES[ "file" ][ "name" ] );
$extension = end( $temp );
$nameGen = uniqid();
$filename = $_FILES[ "file" ][ "tmp_name" ];
if ( empty( $filename ) )
{
	//echo 'File Does Not Exist'.$filename;
}
else
{
	list($width, $height) = getimagesize( $filename );
	$filesize = filesize( $filename );
	//if ($width > 680 || $height > 375) {
	if ( $filesize > 1000000 )
	{
		echo '<div class="alert alert-danger alert-dismissible custom-alert" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          Image size too large! (Maximum allowed size is 2MB)
        </div>';
	}
	else
	{
		if ( (($_FILES[ "file" ][ "type" ] == "image/gif") || ($_FILES[ "file" ][ "type" ] == "image/jpeg") || ($_FILES[ "file" ][ "type" ] == "image/jpg") || ($_FILES[ "file" ][ "type" ] == "image/pjpeg") || ($_FILES[ "file" ][ "type" ] == "image/x-png") || ($_FILES[ "file" ][ "type" ] == "image/png")) && in_array( $extension, $allowedExts ) )
		{
			if ( $_FILES[ "file" ][ "error" ] > 0 )
			{
			}
			else
			{
				if ( file_exists( "uploads/" . $nameGen . "." . $extension ) )
				{
					echo $nameGen . "." . $extension . " <div style='panel-info'>already exists. </div>";
				}
				else
				{
					move_uploaded_file( $_FILES[ "file" ][ "tmp_name" ], $image = "uploads/" . $nameGen . "." . $extension );
					$imageRelativePath = "includes/create-business-card/" . $image;
//					echo $imageRelativePath;
//					outline: 1px dotted rgba(238, 238, 238, .6);
					echo "<div class='new-image' style='width:200px;  max-width:530px !important; height:auto; max-height:330px !important;position:absolute !important; top: 0; left: 0;'><span class='glyphicon glyphicon-remove del' style='cursor: pointer; position:absolute; top:0; right:0;z-index:4;'></span><img src='$imageRelativePath' style='width:100% !important; height:100% !important;' /></div>";
//					echo "<div class='new-image' style='width:" . $width . "px;  max-width:100% !important; height:" . $height . "px;  max-height:338px !important;position:absolute !important; top: 0; left: 0;'><span class='glyphicon glyphicon-remove del' style='cursor: pointer; position:absolute; top:0; right:0;'></span><img src='$imageRelativePath' width='100%' height='auto' style='width:100% !important; height:auto !important; height:100%;' /></div>";
				}
			}
		}
		else
		{
			echo '<div class="alert alert-danger alert-dismissible custom-alert" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          Invalid file!
        </div>';
		}
	}
}
?>
