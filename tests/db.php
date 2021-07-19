<?php

//$hostname = "https://www.cardition.com/";
//$db_host = "localhost";
//$db_username = "miafunfo_bc";
//$db_password = "miacarla01";
//$db_name = "miafunfo_bc";

//$senderID = "AIzaSyAxpdnYiSgGMVtIYckiQryDLYVI_zJbSSg";

$hostname = "http://www.cardition.com/";
//$hostname = "http://bcfolder.webintenerife.com/";

$db_username = "cp829811_cardit";
$db_password = "E*O3fV#U]h#R";
$db_name = "cp829811_miafunfo_bc";

$db_host = "localhost";
//$db_username = "all_root";
//$db_password = "marmE11ata";
//$db_name = "webinten_bccrazy";
$conn = mysqli_connect( $db_host, $db_username, $db_password, $db_name );
if ( mysqli_connect_errno() )
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$baseurl = $hostname;