<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

include 'ChromePhp.php';
include_once('includes/head.php');
require_once('includes/database_config.php');
include_once('utilities/request_result.php');
include_once('utilities/mail.class.php');
$request_result = new Result();
?>
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
<!--				<a class="navbar-brand logo1" href="index.php"><img src="assets/sat_images/cardition.png" alt=""></a>
				<a class="navbar-brand logo2" href="index.php"><img src="assets/sat_images/user-logo.png" alt=""></a>-->
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
					<?php
					if (isset($_POST['recover_email_address'])) {
						$email_address = $_POST['recover_email_address'];
						$query = "SELECT * FROM user WHERE email_address = '" . $email_address . "'";
						$result = $request_result->handleQuery($conn, $query);
						$email_exist = mysqli_num_rows($result);
						ChromePhp::log('$email_exists = ' . $email_exist);
						while ($row = mysqli_fetch_assoc($result)) {
							$user_id = $row['user_id'];
						}
						if ($email_exist != null && $email_exist != 0) {
							ChromePhp::log('building link...');
							$token = sha1($email_address . time() . rand(0, 1000000));
							ChromePhp::log('token is ' . $token);
							$query = sprintf("INSERT INTO email_token (user_id, token, type) VALUES ('%s','%s','%s')", $user_id, $token, 'reset'
							);
							$result = $request_result->handleQuery($conn, $query);
							ChromePhp::log('query is ' . $query);
							ChromePhp::log('error is ' . mysqli_error($conn));
							$url = $url = $config->getBaseUrl() . "reset_password.php?token=" . $token;
							ChromePhp::log('url is ' . $url);
							$msg = <<<MSG
											You're receiving this email because you have requested to reset your password at cardition.com. Use the link below to access the reset password page, please.<br><br><a href="$url">$url</a><br><br>Thank you from cardition.com Support Team
MSG;
							ChromePhp::log('message is ' . $msg);
							$mailer = new Mailer();
							$mail_result = $mailer->send_mail(
								"info@cardition.com", "Cardition", $email_address, "Cardition - reset password link", $msg
							);
							?>
							<h2>Lost your password?</h2>
							<div>
								<p>
									We have sent you an email with instructions on how 
									to recover your account.
								</p>
							</div>					
							<?php
						} else {
							?>
							<div class="overlay-info" style='display: block; padding: 20px 30px;'>
								<p>
									Woops! We're not able to recognize this email address. Please, be sure to use the email address used to register your account at cardition.com.
								</p>
								<div class='sign-in'>
									<form id='frmRecover' method='post'>
										<div class="form-group">
											<input type='email' class="form-control email-field" name='recover_email_address' id='recover_email_address' value='' />
										</div>
										<br>
										<p><a href="#" id='recover_password' class="get-started-btn">Send the email</a></p>
									</form>
								</div>					
							</div>					
							<?php
						}
					} else {
						?>
						<div class="overlay-info" style='display: block; padding: 20px 30px;'>
							<h2>Lost your password?</h2>
							<p>Please, type in your registered email address: we'll send you an email with the link to reset your password.</p>
							<div class='sign-in'>
								<form id='frmRecover' method='post'>
									<div class="form-group">
										<input type='email' class="form-control email-field" name='recover_email_address' id='recover_email_address' value='' />
									</div>
									<br>
									<p><a href="#" id='recover_password' class="get-started-btn">Send the email</a></p>
								</form>
							</div>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
include 'includes/footer.php';

