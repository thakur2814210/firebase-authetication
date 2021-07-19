<?php
//include_once "config/config.php";
include_once __DIR__ .'/../config/config.php';
$config = new Config();
$conn = mysqli_connect($config->db_host,$config->db_username,$config->db_password,$config->db_name);
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
