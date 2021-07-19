<?php
//error_reporting( E_ALL );
//ini_set( 'display_errors', 'On' );
require_once('session_setup.php');
if (!isset($_SESSION['user_id'])) {
	header("Location: index.php");
	exit;
}
if (isset($_SESSION['card']['creation_started']) && $_SESSION['card']['creation_started'] == true){
	unset($_SESSION['card']['creation_started']);
	header("Location: my-own-cards.php");
	exit;
}
if (isset($_SESSION['card'])) {
	include ('includes/create-business-card/cancel_card_creation.php');
	unset($_SESSION['card']);
}
$_SESSION['card']['card_id'] = uniqid(true);

include_once('includes/head.php');
require_once('includes/absolute_database_config.php');
include_once('utilities/request_result.php');
include_once('utilities/mail.class.php');
require_once 'includes/profile/get_profile_details.php';
require_once 'includes/create-business-card/get_default_card_details.php';
$request_result = new Result();
?>

<body data-creation-step="0">
	<!-- wrapper-start -->
	<div class="wrapper">

		<div class="overlay" id="overLay"></div>
		<div class="super-overlay" id="superOverLay"></div>
		<div class="overlay-info" id="buy-premium-id" data-card-id="">
			<a href="#" class="close-btn closePopup"></a>
			<div class="card-setting inline-button">
				<h3>Buy Premium ID (&pound;10.00)</h3>
				<p>Please enter the ID you would like to purchase:</p>
				<form id="premium-id-data">
					<input id="premium_id" value="" name="premium_id" autofocus type="text" class="form-control" maxlength="30"> <span id="premium-id-result" style="position:absolute; right:8px; top:8px; width:14px; height:14px;"></span>
				</form>
				<div id="na_bcn_msg"></div>
				<div style="margin-top:20px; text-align: left;">
					<small><strong>Please note:</strong>
						<ol style="margin-top: 10px">
							<li>No spaces, dashes or special characters</li>
							<li>Underscores allowed</li>
							<li>Characters are limited to 30</li>
						</ol>
					</small>
				</div>
<?php
if (isset($_SESSION['card']['edit_mode'])) {
	$url = 'my-own-cards.php?process_completed=true&buying=true';
} else {
	$url = 'my-own-cards.php?buying=true';
}
?>
				<button data-url="<?php echo $url; ?>" data-card-id="<?php echo $_SESSION['card']['card_id']; ?>" id="btnBuyPrem" class="get-started-btn save-settings disabled">Buy BCN</button>
			</div>
		</div>

		<div class="overlay-info" id="createFolder">
			<a href="#" class="close-btn closePopup folder-creation"></a>
			<!--<a href="#" class="close-btn closePopup folder-creation-sub"></a>-->
			<div class="create-folder inline-button">
				<h3>Create New Folder</h3>
				<input type="email" class="form-control" id="new_folder_name" placeholder="Please enter a new folder name here">
				<button id="create_new_folder" class="sign-up-btn" data-page="dashboard">Create</button>
				<button id="cancel_new_folder_creation" class="get-started-btn">Cancel</button>
			</div>
		</div>
		<!--PREVIEW-SAVE-->
		<div class="overlay-info" id="preViewSave">
			<a href="#" class="close-btn saving-card closePopup"></a>
			<div class="preview-save inline-button">
<?php
if (isset($orientation)) {
	if ($orientation === 'landscape') {
		$class = "img-flip aboutCardposition";
	} else {
		$class = "img-flip aboutCardposition portrait";
	}
}
?>
				<div class="<?php echo $class; ?>">
					<div>
						<div id='new-card-data'>
							<div>
								<img id="linkable" src="" alt="">
							</div>
							<div id="default_links">
							</div>
						</div>
						<div id="back-side-content-id2" class="back-side-content">
							<img src="" alt="">
						</div>
					</div>
				</div>
				<div class="card-info">
					<ul>
						<li><a class="front-side frontSideLink" href="#">Front side</a></li>
						<li><a class="back-side backSideLink" href="#">Back side</a></li>
					</ul>
				</div>
				<h3>Your Card BCN Is: <strong id='new-card-bcn'></strong></h3>
<?php
if (isset($_SESSION['card']['edit_mode'])) {
	$url = 'my-own-cards.php?process_completed=true';
} else {
	$url = 'my-own-cards.php';
}
?>
				<a href="#" data-url="<?php echo $url; ?>" id="save-bc" class="get-started-btn">Save</a>
				<a href="my-own-cards.php" data-url="" id="cancel-bc" class="get-started-btn">Cancel</a>
				<a href="#" data-card-id="<?php echo $_SESSION['card']['card_id'] ?>" id="buy-bcn" class="sign-up-btn">Save and Buy Premium BCN (£ 10.00)</a>
				<!--				<button class="get-started-btn" id="save-card">Save</button>
								<button class="sign-up-btn">Buy Premium ID (£ 10.00)</button>-->
			</div>
		</div>
				<?php require_once('includes/inc_card_details.php'); ?>
				<?php require_once('includes/inc_card_owner_profile.php'); ?>

		<!--header start -->
		<div id="user-header-wrap">
			<ul class="user-header">
				<li id="searchCardLink">
					<input type="text" placeholder="Search Card(s)">
					<a href="#"><img src="assets/sat_images/search-icon.png" alt=""></a>
				</li>
				<li><input type="text" id="search-bcn" placeholder="Add Card with BCN"><a id="add-bcn" href="#" class="userDetailLink"><img src="assets/sat_images/add-bcn-card.png" alt=""></a></li>
				<li><a href="dashboard.php" class="go-to-btn"><img src="assets/sat_images/go-to-dashdboard.png" alt=""></a></li>
			</ul>
		</div>
		<!--header end -->

		<!--main start -->
		<div id="user-main-wrap">
			<!-- sidebar-sec start-->
<?php require_once('includes/inc_sidebar.php'); ?>
			<!-- sidebar-sec end -->
			<div id="main">
				<div class="folder-pages">
					<div class="top-sec">
						<h3>Create a New Business Card</h3>
					</div>
				</div>
				<div class="cards-layout">
					<div class="design-scratch">
						<div id="disabling-overlay"></div>
						<div class="default-design card-type">
							<img src="assets/sat_images/default-design.png" alt="">
							<h4><a href="#" onclick="saveCard(true, true);">Default design</a></h4>
							<p>In a hurry? Please select a default design for <strong>your business card and make changes</strong></p>
						</div>
						<div class="start-scratch card-type">
							<img src="assets/sat_images/start-scratch.png" alt="">
							<h4><a href="create-card-1.php">Start from scratch</a></h4>
							<p>Have a designer instinct and want to design your card <strong>your own way? What are you waiting for...</strong></p>
						</div>
					</div>
					<div class="comming-soon card-type">
						<img src="assets/sat_images/comming-soon.png" alt="">
						<h3>Coming Soon</h3>
						<h4><a href="#">Choose from our design templates</a></h4>
						<p>Use a design from our large library of high quality <strong>templates and reflect like a Pro</strong></p>
					</div>
				</div>

				<div id="hidden-card" class="img-flip aboutCardposition">
					<div id="canvas-container">
						<!-- CARD CANVAS AREA -->
						<div id="first-drop">
							<div id="droppable" class="panel panel-default" style="background: url(includes/create-business-card/uploads/background/default/default-card-front.png) #fff center center no-repeat;">
<?php
if (isset($widgets_front) && $widgets_front != "" && $widgets_front != NULL) {
	if (!empty($phone)) {
		$phone = 't: ' . $phone;
	}
	if (!empty($phone)) {
		$mobile = 'm: ' . $mobile;
	}
	$needles = array(
		"Full name",
		"Address",
		"Zip code and City",
		"Country",
		"Phone number",
		"Mobile number",
		"Email address");
	$replacements = array(
		"$first_name $last_name",
		"$personal_address_1",
		"$personal_post_code $personal_city",
		"$personal_country",
		"$phone",
		"$mobile",
		"$email_address");
	echo str_replace($needles, $replacements, $widgets_front);
}
?>
							</div>
							<div id="back-side-content-id" class="back-side-content" style="background: url(includes/create-business-card/uploads/background/default/default-card-back.png) #fff center center no-repeat;" >
							</div>
								<?php
								if (isset($widgets_back) && $widgets_back != "" && $widgets_back != NULL) {
									?>
									<?php echo $widgets_back ?>
								<?php } ?>
						</div>
					</div>
				</div>
			</div>


		</div>


	</div>
	<!--user-main-wrap end -->

</div>
<!-- wrapper-end -->

							<?php require_once('includes/inc_search_sec.php') ?>
<!-- search-pages end-->

<?php include 'includes/footer.php'; ?>
<script type="text/javascript" src="assets/js/lodash.compat.min.js"></script>
<script type="text/javascript" src="<?php echo force_reload('profile.js'); ?>"></script>
<!-- Load dependency assets -->
<!-- TODO: include older version of handlebars-->
<script type="text/javascript" src="assets/imgmaps/js/jquery.imgareaselect.pack.js"></script>
<script type="text/javascript" src="assets/imgmaps/js/create.links.js"></script>
<script type="text/javascript" src="assets/js/handlebars-v2.0.0.js"></script>
<script type="text/javascript" src="assets/js/jquery.history.js"></script>
<script type="text/javascript" src="card-contacts/shared.js"></script>
<script type="text/javascript" src="assets/mg_js/jquery.ui.rotatable.js"></script>
<script type="text/javascript" src="assets/js/multidraggable.js"></script>
<script type="text/javascript" src="<?php echo force_reload('create-card-intexted.js'); ?>"></script>
<script type="text/javascript" src="assets/js/star-rating.js"></script>
<script>
								jQuery(document).ready(function ($) {
									var landline_width,
											landline_height,
											landline_top,
											landline_left,
											mobile_width,
											mobile_height,
											mobile_top,
											mobile_left,
											email_width,
											email_height,
											email_top,
											email_left;
									landline_width = $('#landline').width();
									landline_height = $('#landline').height();
									landline_top = $('#landline').css('top');
									landline_left = $('#landline').css('left');
									mobile_width = $('#mobile').width();
									mobile_height = $('#mobile').height();
									mobile_top = $('#mobile').css('top');
									mobile_left = $('#mobile').css('left');
									email_width = $('#email').width();
									email_height = $('#email').height();
									email_top = $('#email').css('top');
									email_left = $('#email').css('left');
									if (landline_height == 0) {
										landline_height = 10;
									}
									if (mobile_height == 0) {
										mobile_height = 10;
									}
									if (email_height == 0) {
										email_height = 10;
									}
									console.log('landline_width is ' + landline_width);
									console.log('landline_height is ' + landline_height);
									console.log('landline_top is ' + landline_top);
									console.log('landline_left is ' + landline_left);
									console.log('mobile_width is ' + mobile_width);
									console.log('mobile_height is ' + mobile_height);
									console.log('mobile_top is ' + mobile_top);
									console.log('mobile_left is ' + mobile_left);
									console.log('email_width is ' + email_width);
									console.log('email_height is ' + email_height);
									console.log('email_top is ' + email_top);
									console.log('email_left is ' + email_left);
//		$('#duck').imgAreaSelect({ x1: 120, y1: 90, x2: 280, y2: 210 });
								});
</script>
</body>
</html>
