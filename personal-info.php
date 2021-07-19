<?php
require_once('session_setup.php');
require_once('ChromePhp.php');
if (!isset($_SESSION['user_id'])) {
	header( "Location: index.php" );
	exit;
}
if (isset($_SESSION['card'])) {
	include ('includes/create-business-card/cancel_card_creation.php');
	unset($_SESSION['card']);
}
include_once('includes/head.php');
require_once('includes/database_config.php');
include_once('utilities/request_result.php');
include_once('utilities/mail.class.php');
require_once 'includes/profile/get_profile_details.php';
$request_result = new Result();
?>
<body>
	<!-- wrapper-start -->
	<div class="wrapper">

		<div class="overlay" id="overLay"></div>
		<div class="super-overlay" id="superOverLay"></div>
		<div class="overlay-info" id="createFolder">
			<a href="#" class="close-btn closePopup folder-creation-sub"></a>
			<div class="create-folder inline-button">
				<h3>Create New Folder</h3>
				<input type="email" class="form-control" id="new_folder_name" placeholder="Please enter a new folder name here">
				<button id="create_new_folder" class="sign-up-btn" data-page="profile">Create</button>
				<button id="cancel_new_folder_creation" class="get-started-btn">Cancel</button>
			</div>
		</div>
<?php require_once('includes/inc_card_details.php'); ?>
		<?php require_once('includes/inc_card_owner_profile.php'); ?>

		<!--	<div class="overlay-info" id="avatarSlideSec">
				<a href="#" class="close-btn closePopup"></a>
				<div class="avatar-slide">
					<h3 id='card-owner-name-profile'></h3>
					<img id='card-owner-profile-image' class="card-detail-profile-image" src="" alt="">
					<p id='card-owner-full-name'></p>
					<p id='card-owner-role'>Principal Director</p>
					<p id='card-owner-email-address'></p>
					<p id='card-owner-phones' class="tell-link"></p>
					<p id='card-owner-website-link'></p>
					<span id='card-owner-card-status'></span>
					<p class="card-added"><img src="assets/sat_images/card-added.png" alt="">Card Already Added</p>
				</div>
			</div>-->

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
						<h3>My Profile</h3>
					</div>
				</div>
				<div class="profile-sec">
					<div class="profile-form">
						<div class="about-profile">
							<div id="profile_big" class="profile-logo"><img class="img-circle" src="<?php echo $profile_image; ?>" alt=""></div>
							<form id="profile-pic-form" class="form-inline" enctype="multipart/form-data" action="includes/profile/file_upload.php" method="post">
								<div class="fake-picker">
									<input type="file" name="file" id="inputFile" placeholder="Upload Image">
									<!--<span class='btn btn-default file-picker-button'>Select File</span>-->
									<a href="#" class="get-started-btn file-picker-button">Change picture</a>
									<button onclick="fileUpload(this.form, 'includes/profile/file_upload.php', 'upload'); return false;" id="" class='submit-upload get-started-btn picture-upload disabled'>Upload</button>
								</div>
								<div id="upload"></div>
							</form>
						</div>
						<form id="frmPersonalInfo">
							<div class="salution-select">
								<div class="form-group">
									<label for="">Salutation <span style="color: red; font-size: 20px">*</span></label>
									<div class="custom-dropdown">
										<select name="ddTitle" id="ddTitle" class="custom-select form-control">
											<option <?php if ($title == 'Mr') echo 'selected'; ?> >Mr</option>
											<option <?php if ($title == 'Mrs') echo 'selected'; ?> >Mrs</option>
											<option <?php if ($title == 'Miss') echo 'selected'; ?> >Miss</option>
											<option <?php if ($title == 'Doctor') echo 'selected'; ?> >Doctor</option>
											<option <?php if ($title == 'Professor') echo 'selected'; ?> >Professor</option>
										</select>
									</div>
								</div>
							</div>    
							<div class="inline-form">
								<div class="form-group no-margin">
									<label for="first_name">First Name <span style="color: red; font-size: 20px">*</span></label>
									<input class="form-control" name="first_name" id="first_name" type="text" value="<?php echo $first_name; ?>">
								</div>
								<div class="form-group">
									<label for="last_name">Last Name <span style="color: red; font-size: 20px">*</span></label>
									<input class="form-control" id="last_name" name="last_name" type="text" value="<?php echo $last_name; ?>">
								</div>
								<div class="form-group no-margin">
									<label for="phone">Landline</label>
									<input class="form-control" id="phone" name="phone" type="tel" value="<?php echo $phone; ?>">
								</div>
								<div class="form-group">
									<label for="mobile">Mobile</label>
									<input class="form-control" id="mobile" name="mobile" type="tel" value="<?php echo $mobile; ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="email_address">E-mail <span style="color: red; font-size: 20px">*</span></label>
								<input class="form-control" id="email_address" name="email_address" type="email" value="<?php echo $email_address; ?>" readonly>
							</div>
							<div class="form-group">
								<label for="website_link">Website (if any)</label>
								<input class="form-control" id="website_link" name="website_link" type="text" value="<?php echo $website_link; ?>">
							</div>
							<div class="form-group">
								<label for="personal_address_1">Address 1</label>
								<input type='text' name="personal_address_1" id="personal_address_1" class="form-control" value="<?php echo $personal_address_1; ?>" />
							</div>
							<div class="form-group">
								<label for="personal_address_2">Address 2</label>
								<input type='text' name="personal_address_2" id="personal_address_2" class="form-control"  />
							</div>
							<div class="inline-form">
								<div class="form-group no-margin">
									<label for="personal_city">City <span style="color: rgba(0,0,0,0); font-size: 20px">*</span></label>
									<input class="form-control" name="personal_city" id="personal_city" type="text" value="<?php echo $personal_city; ?>">
								</div>
								<div class="form-group">
									<label for="personal_country">Country <span style="color: red; font-size: 20px">*</span></label>
									<div class="custom-dropdown">
										<select id="personal_country" name="personal_country" name="test" class="custom-select form-control">
											<option value="" <?php if ($personal_country == "") echo 'selected'; ?> >&nbsp;-&nbsp;</option>
<?php
$all_countries = mysqli_query($conn, "select * from countries;");
while ($country = mysqli_fetch_array($all_countries)) {
	?>
												<option value="<?php echo $country['country_id']; ?>" <?php if ($personal_country == $country['country_id']) echo 'selected'; ?> ><?php echo $country['country']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>    
							<div class="form-group">
								<label for="zip_code">Postal code/Zip</label>
								<input class="form-control" id="personal_post_code" name="personal_post_code" type="tel" value="<?php echo $personal_post_code; ?>">
							</div>
							<button id="save-personal-info" type="submit" class="get-started-btn">Save personal info</button>
							<button id="go_to_company_info" class="get-started-btn">Continue to company info</button>
							<p class="create-business-card"><a class="disabled" href="create-card-0.php">Create Digital Card</a></p>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!--main end -->

	</div>
	<!-- wrapper-end -->


<?php require_once('includes/inc_search_sec.php'); ?>
	<!-- search-pages end-->

<!--<script type="text/javascript" src="profile.js"></script>-->
<?php include 'includes/footer.php'; ?>
	<script type="text/javascript" src="assets/js/lodash.compat.min.js"></script>
	<script type="text/javascript" src="<?php echo force_reload('profile.js'); ?>"></script>
	<!-- Load dependency assets -->
	<!-- TODO: include older version of handlebars-->
	<script type="text/javascript" src="assets/js/handlebars-v2.0.0.js"></script>
	<script type="text/javascript" src="assets/js/jquery.history.js"></script>
	<script type="text/javascript" src="card-contacts/shared.js"></script>
	<script type="text/javascript" src="assets/js/star-rating.js"></script>

</body>
</html>