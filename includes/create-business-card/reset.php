<?php

require_once('../../session_setup.php');


// add to db
require_once '../database_config.php';
$bcard = mysqli_query($conn, "SELECT * FROM card_data WHERE user_id = '".$_SESSION['user_id']."' AND card_id = '".$_SESSION['card_id']."'");
$update = mysqli_query($conn, "UPDATE card_data SET canvas_front = '', canvas_back = '', links_front = '', links_back = '', widgets_front = '', widgets_back = '' WHERE user_id = '".$_SESSION['user_id']."' AND card_id = '".$_SESSION['card_id']."'");

// notify
if ($update)
echo  'Your Business Card has been reset successfully!';