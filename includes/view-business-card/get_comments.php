<?php

require_once('../../session_setup.php');
//cadd to db
require_once '../database_config.php';
$query = "select comment_id, comment from card_comment where user_id = '" . $_SESSION[ 'user_id' ] . "' and card_id = '" . $_POST[ 'card_id' ] . "'";
$comments = mysqli_query( $conn, $query );
$result = array();
while ( $row = mysqli_fetch_array( $comments ) )
{
	$result[] = $row;
}
$temp = '';
foreach ( $result as $comment )
{
	$temp .= "<div class='tag-box-r' id='" . $comment[ 'comment_id' ] . "'>";
	$temp .= "<button type='button' class='close' onclick=\"deleteComment('" . $comment[ 'comment_id' ] . "');\"><span class=\"glyphicon glyphicon-remove\" style=\"cursor: pointer\"></span></button>";
	$temp .= "<p>" . $comment[ 'comment' ] . "</p>";
	$temp .= "</div>";
}
echo $temp;
