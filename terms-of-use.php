<?php
require_once('session_setup.php');
include 'includes/head.php';
include 'config/config.php';  
if ( isset( $_SESSION[ 'card' ] ) )
{
	include ('includes/create-business-card/cancel_card_creation.php');
	unset( $_SESSION[ 'card' ] );
}
error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );
//include 'includes/nav_not_logged_in.php'; 
?>
<body class="terms-page">
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
					<p>A fast and easy way to upload your cards with the help of BC numbers at your personalized Cardition account.</p>
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

        <div class="main-banner terms-banner">
					<div class="container">
						<div class="banner-info">
							<h1>Lets talk some <em>serious business now</em></h1>
							<h3>To avoid any kind of nuisance and discrepancy, we fixed some <em>terms and conditions, please have a read below</em></h3>
							<a href="#" class="get-started-btn">Get started</a>
						</div>
					</div> 
        </div>
        <div class="page-title">
					<div class="container">
						<h2>Terms of use</h2>
					</div>
        </div>
        <div class="container">
					<div class="page-info">
						<h4>Terms and Conditions ("Terms")</h4>
						<p class="no-margin">Last updated: October 13, 2015</p>
						<p>Please read these Terms and Conditions ("Terms", "Terms and Conditions") carefully before using the www.cardition.com website (the "Service") operated by Cardition ("us", "we", or "our").</p>
						<p>Your access to and use of the Service is conditioned on your acceptance of and compliance with these Terms. These Terms apply to all visitors, users and others who access or use the Service.</p>
						<p>By accessing or using the Service you agree to be bound by these Terms. If you disagree with any part of the terms then you may not access the Service.</p>
						<h4>Accounts</h4>
						<p>When you create an account with us, you must provide us information that is accurate, complete, and current at all times. Failure to do so constitutes a breach of the Terms, which may result in immediate termination of your account on our Service.</p>
						<p>You are responsible for safeguarding the password that you use to access the Service and for any activities or actions under your password, whether your password is with our Service or a third-party service.</p>
						<p>You agree not to disclose your password to any third party. You must notify us immediately upon becoming aware of any breach of security or unauthorized use of your account.</p>
						<h4>Links To Other Web Sites</h4>
						<p>Our Service may contain links to third-party web sites or services that are not owned or controlled by Cardition.</p>
						<p>Cardition has no control over, and assumes no responsibility for, the content, privacy policies, or practices of any third party web sites or services. You further acknowledge and agree that Cardition shall not be responsible or liable, directly or indirectly, for any damage or loss caused or alleged to be caused by or in connection with use of or reliance on any such content, goods or services available on or through any such web sites or services.</p>
						<p>We strongly advise you to read the terms and conditions and privacy policies of any third-party web sites or services that you visit.</p>
						<h4>Termination</h4>
						<p>We may terminate or suspend access to our Service immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the Terms.</p>
						<p>All provisions of the Terms which by their nature should survive termination shall survive termination, including, without limitation, ownership provisions, warranty disclaimers, indemnity and limitations of liability.</p>
						<p>We may terminate or suspend your account immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the Terms.</p>
						<p>Upon termination, your right to use the Service will immediately cease. If you wish to terminate your account, you may simply discontinue using the Service.</p>
						<p>All provisions of the Terms which by their nature should survive termination shall survive termination, including, without limitation, ownership provisions, warranty disclaimers, indemnity and limitations of liability.</p>
						<h4>Governing Law</h4>
						<p>These Terms shall be governed and construed in accordance with the laws of United Kingdom, without regard to its conflict of law provisions.</p>
						<p>Our failure to enforce any right or provision of these Terms will not be considered a waiver of those rights. If any provision of these Terms is held to be invalid or unenforceable by a court, the remaining provisions of these Terms will remain in effect. These Terms constitute the entire agreement between us regarding our Service, and supersede and replace any prior agreements we might have between us regarding the Service.</p>
						<h4>Changes</h4>
						<p>We reserve the right, at our sole discretion, to modify or replace these Terms at any time. If a revision is material we will try to provide at least 30 days notice prior to any new terms taking effect. What constitutes a material change will be determined at our sole discretion.</p>
						<p>By continuing to access or use our Service after those revisions become effective, you agree to be bound by the revised terms. If you do not agree to the new terms, please stop using the Service.</p>
						<p>Our Terms and Conditions agreement was created by TermsFeed.</p>
						<h4>Contact Us</h4>
						<p>If you have any questions about these Terms, please <a href="index.php#contact" class="contact-link">contact us.</a></p>
					</div>
        </div>
        <div class="get-started-sec">
					<div class="container">
						<a href="#" class="get-started-btn">Get started</a>
					</div>
        </div>
			</div> 
		</div>
		<!--main-wrap end -->

	</div>
	<!-- wrapper-end -->

	<?php include 'includes/footer.php'; ?>
	<script src="<?php echo force_reload( 'index.js' ); ?>"></script>
</body>
</html>
