<?php
require_once('../session_setup.php');
require_once 'absolute_database_config.php';
$id = filter_input(INPUT_POST, 'id');
$query = "DELETE FROM	 notifications WHERE id='$id'";
mysqli_query($conn, $query);
