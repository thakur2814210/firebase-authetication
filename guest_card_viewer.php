<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );
require_once('session_setup.php');
require_once('config/config.php');

function validateUrl($url) {
    return preg_match('%^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?$%iu'
, $url);
}

function formatUrlRef($url) {
	// Remove all illegal characters from a url
	$url = filter_var($url, FILTER_SANITIZE_URL);

	if(validateUrl($url)) {
		return $url;
	}

	// prepend those bars to let the browser decide the best protocol for this url
	return '//' . $url;
}

$config = new Config();

require_once 'includes/database_config.php';
if ( isset( $_SESSION[ 'card' ] ) )
{
	include ('includes/create-business-card/cancel_card_creation.php');
	unset( $_SESSION[ 'card' ] );
}
//check if it is shared sending an email
if ( isset( $_GET[ 'viewcard' ] ) )
{
	$card_view = $_GET[ 'viewcard' ];
	$query = "SELECT card_name, card_bcn, card_owner, card_image, card_image_back, img_flip_class FROM shared_cards WHERE id='$card_view'";
	$result = mysqli_query( $conn, $query );
	$row = mysqli_fetch_row( $result );
	$card_name = $row[ 0 ];
	$card_bcn = $row[ 1 ];
	$card_owner = $row[ 2 ];
	$card_image = $row[ 3 ];
	$card_image_back = $row[ 4 ];
	$img_flip_class = $row[ 5 ];
}
//checks if it is shared through Whatsapp etc.
if ( isset( $_GET[ 'view_card' ] ) )
{
	$card_view = $_GET[ 'view_card' ];
	$query = "SELECT c.card_name, c.assigned_id, u.first_name, u.last_name, cd.canvas_front, cd.canvas_back, c.user_id, csl.sl_facebook, csl.sl_twitter, csl.sl_google, csl.sl_linkedin, csl.sl_instagram, csl.sl_youtube
				FROM card c
				LEFT JOIN user u ON u.user_id = c.user_id
				LEFT JOIN card_data cd ON cd.card_id = '$card_view'
				LEFT JOIN card_social_links csl ON csl.card_id = '$card_view'
				WHERE c.card_id='$card_view'";
//	$query = "SELECT card_name, card_bcn, card_owner, card_image, card_image_back, img_flip_class FROM shared_cards WHERE id='$card_view'";
	$result = mysqli_query( $conn, $query );
	$row = mysqli_fetch_row( $result );
	$card_name = $row[ 0 ];
	$card_bcn = $row[ 1 ];
	$card_owner = $row[ 2 ] . ' ' . $row[3];
	$card_image = $row[ 4 ];
	$card_image_back = $row[ 5 ];
	$user_id = $row[6];

	$sl_facebook = $row[7] ?? '';
	$sl_twitter = $row[8] ?? '';
	$sl_google = $row[9] ?? '';
	$sl_linkedin = $row[10] ?? '';
	$sl_instagram = $row[11] ?? '';
	$sl_youtube = $row[12] ?? '';

//	$img_flip_class = $row[ 5 ];
}

$og_title = $card_name;
$og_description = 'View and save this card in Cardit';
$og_url = $config->hostname . '/guest_card_viewer.php?view_card=' . $card_view;
$og_image_url = $config->hostname . $card_image;
$og_image_width = 1200;
$og_image_height = 630;
$og_type = 'article';

if (isset($card_image)) {
	$ext = pathinfo($card_image, PATHINFO_EXTENSION);

	if ($ext === 'jpg' || $ext === 'jpeg') {
		$og_image_type = 'image/jpeg';
	} elseif ($ext === 'png') {
		$og_image_type = 'image/png';
	} elseif ($ext === 'gif') {
		$og_image_type = 'image/gif';
	}
}

$host = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$actual_link = "$host$_SERVER[REQUEST_URI]";
$dynamic_url = sprintf($config->firebase_dynamic_url, $actual_link);
$vcf_url = sprintf("%s/cardition_api/save_vcard.php?card_id=%s", $host, $card_view);

include 'includes/head.php';
//include 'config/config.php';
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
						<div class="form-group">
							<input type="text" class="form-control full-name" name="full_name" id="full_name" placeholder="Full name">
						</div>
						<div class="form-group">
							<input type="email" class="form-control email-field" name="register_email_address" id="register_email_address" placeholder="Email address">
						</div>
						<div class="form-group">
							<input type="password" class="form-control password-field" name="register_password" id="register_password" placeholder="Password">
						</div>
						<p class="disclaimer">By clicking Create an account, you agree to our <a href="terms-of-use.php">Term of use</a> and that you have read our <a href="privacy-policy.php">Privacy Policy</a>.</p>
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

		<?php if(true): ?>
			<!--main start -->
			<div id="main-wrap" class="main-inner-wrap">
				<div id="main">
					<div class="main-banner home-banner">
						<div class="overlay-info" style="display: block; z-index:1000; margin-top: -100px;" id="card-sharing-panel">
							<div class="card-sharing inline-button">
								<h2 id="title"><?php echo $card_owner; ?> </h2> <!--'s card "<?php echo $card_name; ?>"</h3> -->
								<h4 id="title" style="margin-bottom: -10px;">Please click below to install Cardit and save the card </h4><br>
								<?php
									echo "<a href='$dynamic_url'><img style='max-width:150px;height:auto;width:100%;display: inline;' alt='Get it on Google Play' src='https://play.google.com/intl/en_us/badges/images/generic/en_badge_web_generic.png'/></a>";
                                    echo "<a href='$dynamic_url'><img style='max-width:120px;height:auto;width:100%;display: inline;' alt='Get it on Apple Store' src='https://developer.apple.com//app-store/marketing/guidelines/images/badge-example-preferred.png'/></a>";
								?>
								<div class="preview-save inline-button">
									<!--<div id="img-flipper" class="<?php echo $img_flip_class; ?>">-->
									<div id="img-flipper" class="img-flip aboutCardposition">
										<div>
											<div id='new-card-data' data-card-id="" data-card-owner="">
												<img src="<?php echo $card_image; ?>" alt="" class="img-backdrop-shadow">
											</div>
											<div id="back-side-content-id2" class="back-side-content">
												<img src="<?php echo $card_image_back; ?>" alt="" class="img-backdrop-shadow">
											</div>
										</div>
									</div>
									<div class="card-info">
										<ul>
											<li><a class="front-side frontSideLink" href="#">Front side</a></li>
											<li><a class="back-side backSideLink" href="#">Back side</a></li>
										</ul>
									</div>

									<h4>Card's title: <strong id='new-card-name'><?php echo $card_name; ?></strong></h4>
									<?php
										echo "<a href='$vcf_url'><button class='btn get-started-btn fa' id='download_vcf' style='margin: 5px 2px 20px 2px; font-size: 15px'>&#xf095; Save contact details</button></a>"
									?>

									<h3>My social media links</h3>

									<div class="rounded-social-buttons" style="margin-top: 20px;">
										<?php
											if ($sl_facebook !== '') {
												$sl_facebook = formatUrlRef($sl_facebook);
												echo "<a class='social-button facebook' href='$sl_facebook'></a>";
											}
											if ($sl_twitter !== '') {
												$sl_twitter = formatUrlRef($sl_twitter);
												echo "<a class='social-button twitter' href='$sl_twitter'></a>";
											}
											if ($sl_google !== '') {
												$sl_google = formatUrlRef($sl_google);
												echo "<a class='social-button google' href='$sl_google'></a>";
											}
											if ($sl_linkedin !== '') {
												$sl_linkedin = formatUrlRef($sl_linkedin);
												echo "<a class='social-button linkedin' href='$sl_linkedin'></a>";
											}
											if ($sl_instagram !== '') {
												$sl_instagram = formatUrlRef($sl_instagram);
												echo "<a class='social-button instagram' href='$sl_instagram'></a>";
											}
											if ($sl_youtube !== '') {
												$sl_youtube = formatUrlRef($sl_youtube);
												echo "<a class='social-button youtube' href='$sl_youtube'></a>";
											}
										?>
										<!-- <a class="social-button pinterest" href="#"></a>
										<a class="social-button github" href="#"></a>
										<a class="social-button tumblr" href="#"></a> -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--main end -->
			</div>
		<?php else: ?>
			<!--main start -->
			<div id="main-wrap" class="main-inner-wrap">
				<div id="main">
					<div class="main-banner home-banner">
						<div class="overlay-info" style="display: block; z-index:1000; margin-top: -100px;" id="card-sharing-panel">
							<div class="card-sharing inline-button">
								<h3 id="title"><?php echo $card_owner; ?>'s card is no longer available because its sharing period expired. Please ask for a renewal of "<?php echo $card_name; ?>"</h3>
							</div>
						</div>
					</div>
				</div>
				<!--main end -->
			</div>
		<?php endif; ?>
		<!-- wrapper-end -->
	</div>
	<?php include 'includes/footer.php'; ?>
	<script src="<?php echo force_reload( 'profile.js' ); ?>"></script>
	<script src="<?php echo force_reload( 'index.js' ); ?>"></script>
</body>
</html>
