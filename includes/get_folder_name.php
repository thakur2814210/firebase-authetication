<?php
require_once 'a_test_database_config.php';
if (isset($_GET['folder_id'])) {
    $folder_id = $_GET['folder_id'];
    echo $folder_id;
    $result = mysqli_query($conn, "SELECT description FROM folder WHERE folder_id = '".$folder_id."'");
    if ($result) {

        var_dump($result);

        $folder = mysqli_fetch_row($result);
        $folder_name = $folder[1];

    } else {
        $folder_name = "Subfolder";
    }
}
