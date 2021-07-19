<?php
//
require_once('session_setup.php');
if (isset($_SESSION['user_id'])) {
//	header( "Location: home.php" );
//	exit;
}
if (isset($_SESSION['card'])) {
	include ('includes/create-business-card/cancel_card_creation.php');
	unset($_SESSION['card']);
}
include 'includes/head.php';
include 'config/config.php';
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
						<input type="email" class="form-control" id="emaillog" placeholder="Email address">
					</div>
					<div class="form-group">
						<input type="password" class="form-control" id="passlog" placeholder="Password">
						<a href="recover_password.php" class="forgot-password">Forgot Password?</a>
					</div>
					<button id="btnlogin" class="get-started-btn">Take me in</button>
					<button id="btnlogout" class="btn btn-action">LOG OUT</button>
					<a href="#" class="sign-up-btn orange singnUpPopup">New user? Sign up now</a>
				</form>
			</div>
		</div>
		<div class="overlay-info" id="signUp">
			<a href="#" class="close-btn closePopup"></a>
			<div class="sign-up-wrap">
				<div class="sign-up-form">
					<h3>Sign up</h3>
					<form id="frmRegister">
						<!-- <div class="form-group">
							<input type="text" class="form-control full-name" name="full_name" id="txtname" placeholder="Full name">
						</div> -->
						<div class="form-group">
						<input id="txtemail" type="email" placeholder="Email">
						</div>
						<div class="form-group">
						<input id="txtpassword" type="password" placeholder="password">		
					</div>
						<p class="disclaimer">By clicking Create an account, you agree to our <a href="terms-of-use.php">Term of use</a> and that you have read our <a href="privacy-policy.php">Privacy Policy</a>.</p>
						<button id="btnsignup" class="btn btn-secondary">SIGN UP</button> 
					</form>
				</div>
				<div class="bc-folder">
					<h2>What is Cardition?</h2>
					<p>A fast and easy way to upload your cards with the help of BC numbers at your personalized folder online.</p>
					<p>BCN stands for Business Card Number, it is an unique number for a business card or a product card. With the help of the BCN, anyone can download and save your contact details or product details in the form of a digital card online at one place. </p>
				</div>
			</div>
		</div>

<?php
if (isset($_SESSION['user_id'])) {
	require_once('includes/absolute_database_config.php');
	$user_id = $_SESSION['user_id'];
	$query = "SELECT first_name, last_name FROM user WHERE user_id='$user_id'";
	$result = mysqli_query($conn, $query);
	$uname = mysqli_fetch_row($result);
	include_once('includes/inc_header_logged.php');

} else {
	include_once('includes/inc_header_not_logged.php');
}
?>

		<!--main start -->
		<div id="main-wrap" class="main-inner-wrap">
			<img src="assets/sat_images/scroll-down.gif" alt="" class="scroll-down" id="scrollDownLink">
			<div id="main">
				<div class="main-banner home-banner">
					<div class="banner-info">
						<img src="assets/sat_images/main-banner-new.png" alt="">
						<div class="banner-top-area">
							<h1>Create, Save, Organize and Share your digital cards online</h1>
							<p id="cardition-desc">Cardition is your online platform where your information are saved on cards, organized in folders and shared with the help of the digital card number: the <strong>BCN</strong></p>
<?php
if (isset($_SESSION['user_id'])) {
	?>
								<a href="dashboard.php" class="get-started-btn grey">Get started</a>
							<?php
							} else {
								?>
								<a href="#" onclick="goToLogin()" class="get-started-btn grey">Get started</a>
							<?php } ?>
						</div>
					</div>
				</div>
				<div class="video-container">
					<!--<center>-->
						<iframe width="854" height="480" src="https://www.youtube.com/embed/wr9AidK97y0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					<!--</center>-->
				</div>
				<div class="bcn-card-wrap" id="whatIsBcn">
					<div class="container">
						<div class="bcn-card-sec">
							<div class="about-bnc">
								<h2>What is BCN?</h2>
								<!--<p>BCN stands for Business Card Number, it is an unique number for a business card or a product card. With the help of the BCN, anyone can download and save your contact details or product details in the form of a digital card online at one place. That one place is cardition.com, the personalized online folder to keep all personal and professional contacts as well as products and services details in a digital card format</p>-->
								<p>BCN is the unique id for a digital card. With the help of the BCN, anyone can download and save your contact details or product details in the form of a digital card online at one place. That one place is Cardition.com, the personalized online folder to keep all personal and professional contacts as well as products and services details on digital cards format</p>
<?php
if (isset($_SESSION['user_id'])) {
	?>
									<!--<a href="create-card.php" class="get-started-btn">Get your BCN now</a>-->
<?php
} else {
	?>
									<!--<a href="#" onclick="goToLogin()" class="get-started-btn">Get your BCN now</a>-->
								<?php } ?>
							</div>
							<div class="virtual-digital-cards">
								<h2>How to create virtual digital cards?</h2>
								<ul>
									<li>
										<a href="#"><img src="assets/sat_images/create-card1.png" alt=""></a>
										<p>Scan or take a picture of your paper visiting card and upload the image on <a href="#">Cardition.com</a></p>
									</li>
									<li>
										<a href="#"><img src="assets/sat_images/create-card2.png" alt=""></a>
										<p>Design it directly from your dashboard once you login</p>
									</li>
									<li>
										<a href="#"><img src="assets/sat_images/create-card3.png" alt=""></a>
										<p>Design it by using other online design tools or website and import here</p>
									</li>
								</ul>
<?php
if (isset($_SESSION['user_id'])) {
	?>
									<!--<a href="create-card.php" class="get-started-btn">Design your card</a>-->
<?php
} else {
	?>
									<!--<a href="#" onclick="goToLogin()" class="get-started-btn">Design your card</a>-->
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<div class="block block1" id="featuresSec">
					<div class="container">
						<div class="block-sec">
							<div class="box-layout">
								<div class="home-info-box">
									<img src="assets/sat_images/fast-easy.png" alt="">
									<h3 class='no-white'>Its fast and easy</h3>
									<p class='no-white'>to upload your contacts or products cards<br>with their unique BCN at Cardition.com</p>
								</div>
							</div>
							<div class="box-layout">
								<div class="home-info-box">
									<img src="assets/sat_images/access-anywhere.png" alt="">
									<h3>Access and Share anywhere</h3>
									<p>Have access to your saved cards<br>anywhere on your online account<br>and share your saved cards with<br>your contacts</p>
								</div>  
							</div>
						</div>
					</div>
				</div>
				<div class="block block2">
					<div class="container">
						<div class="block-sec">
							<div class="box-layout">
								<div class="home-info-box">
									<img src="assets/sat_images/eassy-search.png" alt="">
									<h3 class='no-white'>Easy search</h3>
									<p class='no-white'>Searching a card in your online <em>folder is quick by using filters</em></p>
								</div>
							</div>
							<div class="box-layout">
								<div class="home-info-box">
									<img src="assets/sat_images/folder.png" alt="">
									<h3 class='no-white'>Organize cards in your Wolders</h3>
									<p class='no-white'>With Cardition.com, organise your cards<br>by thematics in your Web folders </em></p>
								</div>  
							</div>
						</div>
					</div>
				</div>
				<div class="block block3">
					<div class="container">
						<div class="block-sec">
							<div class="box-layout">
								<div class="home-info-box">
									<img src="assets/sat_images/rating-shine.png" alt="">
									<h3 class='no-white'>Get rating and shine</h3>
									<p class='no-white'>Get rating from your card holders <em>for your products/services</em></p>
								</div>
							</div>
							<div class="box-layout">
								<div class="home-info-box">
									<img src="assets/sat_images/update-sync.png" alt="">
									<h3 class='no-white'>Easy update and sync</h3>
									<p class='no-white'>Update your card in Cardition and <em>your card holder will get automatic update</em></p>
								</div>  
							</div>
						</div>
					</div>    
				</div>
				<div class="get-started-sec">
<?php
if (isset($_SESSION['user_id'])) {
	?>
						<a href="dashboard.php" class="get-started-btn grey">Get started</a>
<?php
} else {
	?>
						<a href="#" onclick="goToLogin()" class="get-started-btn grey">Get started</a>
					<?php } ?>
					<!--<a href="#" class="get-started-btn">Get started</a>-->
				</div>
				<!--        <div id="faq">
									<div class="main-banner faq-banner">
										<div class="container">
											<div class="banner-info">
												<h1>No one is dumb who is curious. The people <em>who don’t ask questions remain clueless always</em></h1>
												<h3>Here’s a list of frequently asked questions about Cardition and our answers, <em>so you don’t have to go wondering what’s the catch!</em></h3>
												<a href="#" class="get-started-btn">Get started</a>
											</div>
										</div> 
									</div>
									<div class="page-title">
										<div class="container">
											<h2>Frequently Asked Questions</h2>
										</div>
									</div>
									<div class="container">
										<div class="page-info faq-info">
											<h3>What is Cardition?</h3>
											<p>BCN stands for Business Card Number, it is an unique number for a business card or a product card. With the help of the BCN, anyone can download and save your contact details or product details in the form of a digital card online at one place. That one place is cardition.com, the personalized online folder to keep all personal and professional contacts as well as products and services details in a digital card format</p>
											<h3>What does BC Number or BCN stands for?</h3>
											<p>BCN stands for Business Card Number, it is an unique number for a business card or a product card. With the help of the BCN, anyone can download and save your contact details or product details in the form of a digital card online at one place. That one place is cardition.com, the personalized online folder to keep all personal and professional contacts as well as products and services details in a digital card format</p>
											<h3>How could I create a virtual digital cards?</h3>
											<p>BCN stands for Business Card Number, it is an unique number for a business card or a product card. With the help of the BCN, anyone can download and save your contact details or product details in the form of a digital card online at one place. That one place is cardition.com, the personalized online folder to keep all personal and professional contacts as well as products and services details in a digital card format</p>
											<h3>Once Purchased the BCN, for how long I can use the same BCN with the amount paid once?</h3>
											<p>BCN stands for Business Card Number, it is an unique number for a business card or a product card. With the help of the BCN, anyone can download and save your contact details or product details in the form of a digital card online at one place. That one place is cardition.com, the personalized online folder to keep all personal and professional contacts as well as products and services details in a digital card format</p>
											<h3>Will I get the bill/invoice for the BCN purchase?</h3>
											<p>BCN stands for Business Card Number, it is an unique number for a business card or a product card. With the help of the BCN, anyone can download and save your contact details or product details in the form of a digital card online at one place. That one place is cardition.com, the personalized online folder to keep all personal and professional contacts as well as products and services details in a digital card format</p>
											<h3>How can I delete my account from Cardition.com?</h3>
											<p>BCN stands for Business Card Number, it is an unique number for a business card or a product card. With the help of the BCN, anyone can download and save your contact details or product details in the form of a digital card online at one place. That one place is cardition.com, the personalized online folder to keep all personal and professional contacts as well as products and services details in a digital card format</p>
											<h3>Can I use my Paypal account to purchase BCN?</h3>
											<p>BCN stands for Business Card Number, it is an unique number for a business card or a product card. With the help of the BCN, anyone can download and save your contact details or product details in the form of a digital card online at one place. That one place is cardition.com, the personalized online folder to keep all personal and professional contacts as well as products and services details in a digital card format</p>
											<h3>Can I make my card a private from others to restrict the view?</h3>
											<p>BCN stands for Business Card Number, it is an unique number for a business card or a product card. With the help of the BCN, anyone can download and save your contact details or product details in the form of a digital card online at one place. That one place is cardition.com, the personalized online folder to keep all personal and professional contacts as well as products and services details in a digital card format</p>
											<h3>Can I send a private card holder a ‘Card Link Request’?</h3>
											<p>BCN stands for Business Card Number, it is an unique number for a business card or a product card. With the help of the BCN, anyone can download and save your contact details or product details in the form of a digital card online at one place. That one place is cardition.com, the personalized online folder to keep all personal and professional contacts as well as products and services details in a digital card format</p>
											<p>If you have more questions, which we have not covered in our <a href="#">FAQ</a> section, then feel free to write us at: <a href="#">queries@cardition.com</a>. We will get back to you within 42 hours.</p>
										</div>
									</div>
									<div class="get-started-sec">
										<a href="#" class="get-started-btn">Get started</a>
									</div>
								</div>
								<div id="contact">
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
														<p>Our BCN: <strong>bcfolder</strong></p>
														<address>90802 California, Long beach PO Box 68789, 300 East Ocean Boulevard</address>
														<p><a href="tel:+91 123 45 6789">+91 123 45 6789</a> <strong><a href="#">query@cardition.com</a></strong></p>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>-->
			</div>
		</div>
		<!--main end --> 

	</div>
	<!-- wrapper-end -->
<?php include 'includes/footer.php'; ?>
  
<script src="https://www.gstatic.com/firebasejs/8.7.1/firebase-app.js"></script>
  
  <script src="https://www.gstatic.com/firebasejs/8.7.1/firebase-analytics.js"></script>
  <script src="https://www.gstatic.com/firebasejs/8.7.1/firebase-auth.js"></script>
  <script src="https://www.gstatic.com/firebasejs/8.7.1/firebase-firestore.js"></script>

	<script src="<?php echo force_reload('profile.js'); ?>"></script>
	<script src="<?php echo force_reload('index.js'); ?>"></script>
	<script src="<?php echo force_reload('firebase.js'); ?>"></script>
	<script>
						jQuery(document).ready(function ($) {
//		$(document).on("scroll", onScroll);
//		function onScroll(event) {
//			var scrollPos = jQuery(document).scrollTop();
//			jQuery('.navbar-nav li a:not(.no-scroll)').each(function () {
//				var currLi = jQuery(this).parent('li');
//				var currLink = jQuery(this);
//				var el = jQuery(this).attr('href');
//				var refElement = jQuery(currLink.attr("href"));
//				if ( refElement ) {
//					if ( refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos ) {
////					jQuery('.navbar-nav li').removeClass("active");
//						currLi.addClass("active");
//					} else {
//						currLi.removeClass("active");
//					}
//				}
//			});
//		}
						});
	</script>
</body>
</html>
