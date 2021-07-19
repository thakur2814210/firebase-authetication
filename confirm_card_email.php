<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once 'includes/absolute_database_config.php';
include_once 'ChromePhp.php';
session_start();
$token = filter_input(INPUT_GET, 't');
if ($token) {
	$query_get_email = "select id, email_address, card_id from email_to_verify where token='$token'";
	ChromePhp::log('query get email');
	ChromePhp::log($query_get_email);
	$result = mysqli_query($conn, $query_get_email);
	ChromePhp::log('get email error');
	ChromePhp::log(mysqli_error($conn));
	if ($result) {
		$row = mysqli_fetch_row($result);
		$email_id = $row[0];
		$email_address = $row[1];
		$card_id = $row[2];
	}
	if (!empty($email_address)) {
		$update_email_veriication = "update email_verification SET verified='1' where id='$email_id'";
		ChromePhp::log($del_query);
		mysqli_query($conn, $del_query);
		ChromePhp::log(mysqli_error($conn));
		$query_update_card = "update card SET verified='1' where card_id='$card_id'";
		ChromePhp::log($query_update_card);
		mysqli_query($conn, $query_update_card);
		ChromePhp::log(mysqli_error($conn));
		$query_user = "select user_id from card where card_id='$card_id' LIMIT 1";
		ChromePhp::log($query_user);
		$result = mysqli_query($conn, $query_user);
		ChromePhp::log(mysqli_error($conn));
		$row = mysqli_fetch_row($result);
		$user_id = $row[0];
		$_SESSION['user_id'] = $user_id;
		$response = 'ok';
	} else {
		$response = 'failed';
	}
}
include 'includes/head.php';
?>
<body>
	<!--header start -->
	<div id="header-wrap">
		<div class="container">
			<nav class="page-navigation">
				<div class="navigation-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#toggle-nav" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand logo1" href="index.php"><img src="assets/sat_images/cardition.png" alt=""></a>
					<a class="navbar-brand logo2" href="index.php"><img src="assets/sat_images/user-logo-new.png" alt=""></a>
				</div>
			</nav>
		</div>
	</div>
	<!--header end -->
	<div id="main-wrap" class="main-inner-wrap">
		<div id="main">
			<div class="main-banner home-banner">
				<div class="banner-info">
					<center><img src="assets/sat_images/main-banner.png" alt="" style="width: 80%"></center>
					<div class="banner-top-area">
						<?php if ($response == 'ok') { ?>
							<h1>You have successfully confirmed your email address!</h1>
							<p>Click button below to go to your dashboard.</p>
							<p><a href="<?php echo 'dashboard.php' ?>" class="get-started-btn">Take me in</a></p>
						<?php } else { ?>
							<h1>Error Occurred!</h1>
							<p>You either followed an incorrect registration link or your verification period expired!</p>
							<!--<p><a href="<?php // echo $config->getBaseUrl() . 'index.php' ?>" class="get-started-btn">Retry!</a></p>-->
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
