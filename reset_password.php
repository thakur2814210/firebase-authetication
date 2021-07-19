<?php
include 'includes/head.php';
require_once 'includes/absolute_database_config.php';
include_once('utilities/request_result.php');
include_once('utilities/mail.class.php');
$request_result = new Result();
$sent_token = trim($_GET["token"]);
$query = "SELECT * FROM email_token WHERE token = '" . $sent_token . "'" . " AND type='reset'";
$result = $request_result->handleQuery($conn, $query);
$token_exists = mysqli_num_rows($result);
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
				<div  class="overlay-info" style='display: block; padding: 20px 30px;'>
					<?php
					if ($token_exists != null && $token_exists != 0) {
						?>
						<h2>Change Password <span class="extra-title muted"></span></h2>
						<div class='sign-in'>
							<form id="frmReset">
								<div class="control-group">
									<label for="new_password" class="control-label">New Password</label>
									<div class="controls form-group">
										<input type="password" name="new_password" id="new_password" class="form-control password-field" />
									</div>
								</div>
								<div class="control-group">
									<label for="confirm_password" class="control-label">Confirm Password</label>
									<div class="controls form-group">
										<input type="password" name="confirm_password" id="confirm_password" class="form-control password-field" />
									</div>
								</div>      
							</form>
							<p><a href="#" id='password_modal_save' class="get-started-btn">Save changes</a></p>
						</div>
						<?php
					} else {
						?>
						<h2>Sorry, you don't seem to have requested authorization privileges to access this page.</h2>
						<h2>Please, contact the support team.</h2>
						<?php
					}
					?>
				</div>

			</div>
			<?php include 'includes/footer.php'; ?>
			<script type="text/javascript" src="<?php echo force_reload('reset_password.js'); ?>"></script>