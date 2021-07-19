<?php
$servername = "cardition.com";
$username = "miafunfo_bc";
$password = "miacarla01";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?> 
