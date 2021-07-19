<?php
require_once('session_setup.php');
if ( isset( $_SESSION[ 'user_id' ] ) )
{
//	header( "Location: home.php" );
//	exit;
}
if ( isset( $_SESSION[ 'card' ] ) )
{
	include ('includes/create-business-card/cancel_card_creation.php');
	unset( $_SESSION[ 'card' ] );
}
include 'includes/head.php';
include 'config/config.php';
error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );
//include 'includes/nav_not_logged_in.php'; 
?>
<body>
	<!-- wrapper-start -->
	<div class="">

		<div class="overlay" id="overLay"></div>
		<div class="super-overlay" id="superOverLay"></div>
 
		<div class="overlay-info" id="signIn">
			<a href="#" class="close-btn closePopup"></a>
			<div class="sign-in">
        <h3>Sign in</h3>
        <form id="frmLogin">
					<div class="form-group">
						<input type="email" class="form-control email-field" id="login_email_address" placeholder="Email address">
					</div>
					<div class="form-group">
						<input type="password" class="form-control password-field" id="login_password" placeholder="Password">
						<a href="recover_password.php" class="forgot-password">Forgot Password?</a>
					</div>
					<button type="submit" id="login" class="get-started-btn">Take me in</button>
					<a href="#" class="sign-up-btn singnUpPopup">New user? Sign up now</a>
        </form>
			</div>
		</div>
		<div class="overlay-info" id="signUp">
			<a href="#" class="close-btn closePopup"></a>
			<div class="sign-up-wrap">
        <div class="sign-up-form">
					<h3>Sign up</h3>
					<form id="frmRegister">
						<div class="form-group">
							<input type="text" class="form-control full-name" name="full_name" id="full_name" placeholder="Full name">
						</div>
						<div class="form-group">
							<input type="email" class="form-control email-field" name="register_email_address" id="register_email_address" placeholder="Email address">
						</div>
						<div class="form-group">
							<input type="password" class="form-control password-field" name="register_password" id="register_password" placeholder="Password">
						</div>
						<button type="submit" id="sign-up" class="sign-up-btn">Take me in</button>
					</form>
        </div>
        <div class="bc-folder">
					<h2>What is Cardition?</h2>
					<p>A fast and easy way to upload your cards with the help of BC numbers at your personalized Cardition online account.</p>
					<p>BCN stands for Business Card Number, it is an unique number for a business card or a product card. With the help of the BCN, anyone can download and save your contact details or product details in the form of a digital card online at one place. </p>
        </div>
			</div>
		</div>

		<?php
		if ( isset( $_SESSION[ 'user_id' ] ) )
		{
			require_once('includes/absolute_database_config.php');
			$user_id = $_SESSION[ 'user_id' ];
			$query = "SELECT first_name, last_name FROM user WHERE user_id='$user_id'";
			$result = mysqli_query( $conn, $query );
			$uname = mysqli_fetch_row( $result );
			include_once('includes/inc_header_logged.php');
		}
		else
		{
			include_once('includes/inc_header_not_logged.php');
		}
		?>

		<!--main start -->
		<div id="main-wrap" class="main-inner-wrap">
			<div id="main">
        <div class="main-banner contact-banner">
					<div class="container">
						<div class="banner-info">
							<h1>Reach out to us <em>We'd love to hear from you</em></h1>
							<h3>If its a non urgent issue then you could also email us. <em>We’ll get back to you in 42 hours</em></h3>
							<a href="#" class="get-started-btn">Email us</a>
						</div>
					</div> 
        </div>
        <div class="page-title">
					<div class="container">
						<h2>Contact</h2>
					</div>
        </div>
        <div class="contact-wrap">
					<div class="container">
						<div class="contact-sec">
							<div class="contact-form">
								<form class="contact-form-sec">
									<div class="form-group">
										<label for="your_name">How do you like us to call you?</label>
										<input type="text" class="form-control" id="your_name" placeholder="Please write your name here">
									</div>
									<div class="form-group">
										<label for="your_email">Email address</label>
										<input type="email" class="form-control" id="your_email" placeholder="Enter your email address">
									</div>
									<div class="form-group">
										<label for="message">How can we help you?</label>
										<textarea name="" id="message" class="form-control" cols="30" rows="5" placeholder="Please write your message here"></textarea>
									</div>
									<button id="send-mail" class="sign-up-btn">Send form</button>
								</form>
							</div>
							<div class="contact-info">
								<div class="contact-sec-address">
									<img src="assets/sat_images/contact-address.png" alt="">
									<p>Our BCN: <strong>CARDITION</strong></p>
<!--									<address>90802 California, Long beach PO Box 68789, 300 East Ocean Boulevard</address>
									<p><a href="tel:+91 123 45 6789">+91 123 45 6789</a> <strong><a href="#">query@cardition.com</a></strong></p>-->
								</div>
							</div>
						</div>
					</div>
        </div>
			</div>
		</div>
		<!--main end -->


	</div>
	<!-- wrapper-end -->

	<?php include 'includes/footer.php'; ?>
	<script src="<?php echo force_reload( 'index.js' ); ?>"></script>
	<script type="text/javascript">
		jQuery(document).ready(function () {   
			jQuery('.navbar-nav li.contact').addClass('active');
		});
	</script>

</body>
</html>
