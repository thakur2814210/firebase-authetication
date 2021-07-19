<?php

$folder_id = trim($_REQUEST['folder_id']);

//cadd to db
require_once '../absolute_database_config.php';

mysqli_query($conn, "delete from card_folder where folder_id = '".$folder_id."'");
mysqli_query($conn, "delete from folder where folder_id = '".$folder_id."'");