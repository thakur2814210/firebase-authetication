<?php
require_once('../../session_setup.php');
//cadd to db
require_once '../database_config.php';
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	echo 'expired';
	exit;
}
$temp = '';
$folder = mysqli_query($conn, "
    select f.folder_id, f.description, cf.card_id
    from folder f
    left join card_folder cf on cf.user_id = f.user_id and cf.folder_id = f.folder_id
    where f.user_id = '".$_SESSION['user_id']."' group by description order by description");
//$folder = mysqli_query($conn, "
//    select f.folder_id, f.description, cf.card_id
//    from folder f
//    left join card_folder cf on cf.user_id = f.user_id and cf.folder_id = f.folder_id and cf.card_id = '".$_SESSION['card_id']."'
//    where f.user_id = '".$_SESSION['user_id']."'
//    order by description;
//");
$folders_exist = false;
while ($row = mysqli_fetch_array($folder)) {
    $folders_exist = true;
    $temp .= "<li class='folsel'><input id='cbx-".$row['folder_id']."' type='checkbox' class='css-checkbox mg-checkbox' value='".$row['folder_id']."' ";
//    if ($row['card_id'] != null) {
//        $temp .= "checked='checked'";
//    }
    $temp .= " />";
    $temp .= "<label for='cbx-".$row['folder_id']."' class='checkbox css-label mg-checkbox'>" . $row['description'] . "</label></li>";
}
if ($folders_exist) {
    $temp .= "<li class='apply'><a href='#' id='apply' style='cursor: pointer;' onclick='applyClick();'>Apply</a></li>";
}
//$temp .= "<li id='manage-folders' style='cursor: pointer; margin-bottom: 10px;' onclick='manageFoldersClick();'>Manage Folders</li>";
echo $temp;
