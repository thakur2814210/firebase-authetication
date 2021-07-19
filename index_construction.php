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


<?php
include_once('includes/inc_header_not_loggable.php');
?>

		<!--main start -->
		<div id="main-wrap" class="main-inner-wrap">
			<img src="assets/sat_images/scroll-down.gif" alt="" class="scroll-down" id="scrollDownLink">
			<div id="main">
				<div class="main-banner home-banner">
					<div class="banner-info">
						<img src="assets/sat_images/main-banner.png" alt="">
						<div class="banner-top-area">
							<h1>Virtual digital versions of your <em>paper business cards at one place</em></h1>
							<h3>A fast and easy way to upload your cards with the help of <em>BC numbers at your personalized Cardition online account</em></h3>

							<h3>Coming soon...</h3>
						</div>
					</div>
				</div>

			</div>
			<!--main end --> 

		</div>
		<!-- wrapper-end -->
<?php include 'includes/footer.php'; ?>


		<script src="<?php echo force_reload('profile.js'); ?>"></script>
		<script src="<?php echo force_reload('index.js'); ?>"></script>

</body>
</html>
