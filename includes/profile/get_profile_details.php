<?php

require_once 'includes/absolute_database_config.php';
$result = mysqli_query($conn, 
    "select
    u.*, 
    pa.address_1 as personal_address_1,
    pa.address_2 as personal_address_2,
    pa.city as personal_city,
    pa.country_id as personal_country,
    pa.post_code as personal_post_code,
    ca.address_1 as company_address_1,
    ca.address_2 as company_address_2,
    ca.city as company_city,
    ca.country_id as company_country,
    ca.post_code as company_post_code
    from user u
    left join address pa on u.personal_address_id = pa.address_id 
    left join address ca on u.company_address_id = ca.address_id 
    where u.user_id = '".$_SESSION['user_id']."';");

while ($row = mysqli_fetch_array($result)) {
	
	if ($row['profile_image'] != "" && $row['profile_image'] != NULL) {
	date_default_timezone_set('Europe/London');
	$now = getTimestamp();
	$profile_image = $row['profile_image'].'?'.$now;	
	} else {
//		$profile_image = 'assets/img/anon.jpg';
		$profile_image = 'assets/img/def_avatar.gif';
	}
	
    $title = $row['title'];
    $last_name = $row['last_name'];
    $first_name = $row['first_name'];        
    $phone = $row['phone'];        
    $mobile = $row['mobile'];        
    $email_address = $row['email_address'];         
    $website_link = $row['website_link'];        
    $personal_address_1 = $row['personal_address_1'];
    $personal_address_2 = $row['personal_address_2'];
    $personal_city = $row['personal_city'];
    $personal_country = $row['personal_country'];
    $personal_post_code = $row['personal_post_code'];
    $company_name = $row['company_name'];
    $department_name = $row['department_name'];
    $position = $row['position'];
    $corporate_code = $row['corporate_code'];
    $company_phone = $row['company_phone'];        
    $company_mobile = $row['company_mobile'];        
    $company_email_address = $row['company_email_address'];        
    $company_website_link = $row['company_website_link'];        
    $company_address_1 = $row['company_address_1'];
    $company_address_2 = $row['company_address_2'];
    $company_city = $row['company_city'];
    $company_country = $row['company_country'];
    $company_post_code = $row['company_post_code']; 
};
