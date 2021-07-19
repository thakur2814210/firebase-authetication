<?php
	require_once('../../session_setup.php');

	require_once '../database_config.php';
	include_once('models.php');
	include_once('../../utilities/request_result.php');

	$r = new Result();

    $card_id = trim($_GET['card_id']); 

    $query = sprintf(
        "SELECT pcs.visible_pp_search,pcs.share_among_users,pcs.allow_rating,p.plan_name
         FROM professional_card_setting pcs
         JOIN subscription s
         ON pcs.card_id = s.card_id
         JOIN plan p
         ON s.plan_id = p.plan_id
         WHERE pcs.card_id = '%s'",
         $card_id);

	$result = $r->handleQuery($conn,$query);

    $o = (object)array(
        'visible_pp_search' => '0', 
        'allow_rating' => '0', 
        'share_among_users' => '0'
    );
    
    while ($row = mysqli_fetch_array($result)) {
    	$o->visible_pp_search = $row['visible_pp_search']; 
    	$o->allow_rating = $row['allow_rating']; 
    	$o->share_among_users =$row["share_among_users"];
      $o->plan_name = $row["plan_name"];
    }
    $r->data = $o;
	echo json_encode($r);