<?php
require_once('session_setup.php');
if (!isset($_SESSION['user_id'])) 
{ 
	header("Location: index.php");
	exit; 
}
if ( isset( $_SESSION[ 'card' ] ) )
{
	include ('includes/create-business-card/cancel_card_creation.php');
	unset( $_SESSION[ 'card' ] );
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
								<input type="file" name="file" id="inputFile">
								<!--<span class='btn btn-default file-picker-button'>Select File</span>-->
								<a href="#" class="get-started-btn file-picker-button">Change picture</a>
								<button onclick="fileUpload(this.form, 'includes/profile/file_upload.php', 'upload'); return false;" id="" class='submit-upload get-started-btn picture-upload disabled'>Upload</button>
							</div>
							<div id="upload"></div>
						</form>
					</div>
					<form id="frmCompanyInfo">    
						<div class="inline-form">
							<div class="form-group no-margin">
								<label for="first_name">Company Name</label>
								<input id="company_name" name="company_name" class="form-control" type="text" value="<?php echo $company_name; ?>" />
							</div>
							<div class="form-group">
								<label for="last_name">Department Name</label>
								<input id="department_name" name="department_name" class="form-control" type="text" value="<?php echo $department_name; ?>" />
							</div>
							<div class="form-group no-margin">
								<label for="land_line">Designation/Postion</label>
								<input id="position" name="position" class="form-control" type="text" value="<?php echo $position; ?>" />
							</div>
							<div class="form-group">
								<label for="mobile">Corporate Code</label>
								<input id="company_address_1" name="company_address_1" class="form-control" type="text" value="<?php echo $corporate_code; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="address_1">Company Address 1</label>
							<input type="text" id="company_address_2" name="company_address_2" class="form-control" value="<?php echo $company_address_1; ?>" />
						</div>
						<div class="form-group">
							<label for="address_2">Company Address 2</label>
							<input type="text" id="company_city" name="company_city" class="form-control" value="<?php echo $company_address_2; ?>" />
						</div>
						<div class="inline-form">
							<div class="form-group no-margin">
								<label for="city_name">City</label>
								<input class="form-control" id="company_city" name="company_city" type="text" value="<?php echo $company_city; ?>" />
							</div>
							<div class="form-group">
								<label for="">Country</label>
								<div class="custom-dropdown">
									<select id="company_country" name="company_country" class="custom-select form-control">
										<option value="" <?php if ( $personal_country == "" ) echo 'selected'; ?> >&nbsp;-&nbsp;</option>
										<?php
										$all_countries = mysqli_query( $conn, "select * from countries;" );
										while ( $country = mysqli_fetch_array( $all_countries ) )
										{
											?>
											<option value="<?php echo $country[ 'country_id' ]; ?>" <?php if ( $personal_country == $country[ 'country_id' ] ) echo 'selected'; ?> ><?php echo $country[ 'country' ]; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="zip_code">Postal code/Zip</label>
							<input class="form-control" id="company_post_code" name="company_post_code" type="text" value="<?php echo $company_post_code; ?>" />
						</div>
						<div class="inline-form">
							<div class="form-group no-margin">
								<label for="land_line">Landline</label>
								<input class="form-control" id="company_phone" name="company_phone" type="tel" value="<?php echo $company_phone; ?>" />
							</div>
							<div class="form-group">
								<label for="mobile">Mobile</label>
								<input id="company_mobile" name="company_mobile" class="form-control" id="mobile" type="tel" value="<?php echo $company_mobile; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="email">E-mail</label>
							<input class="form-control" id="company_email_address" name="company_email_address" type="email" value="<?php echo $company_email_address; ?>" />
						</div>
						<div class="form-group">
							<label for="site_address">Website (if any)</label>
							<input class="form-control" id="company_website_link" name="company_website_link" type="text" value="<?php echo $company_website_link; ?>" />
						</div>    
						<button id="save-company-info" type="submit" class="get-started-btn">Save company info</button>
						<button id="back_to_personal_info" class="submit-btn"><img src="assets/sat_images/submit-link-left.png" alt=""></button>
					</form>
				</div>
			</div>
    </div>
	</div>
	<!--main end -->

</div>
<!-- wrapper-end -->

<?php require_once('includes/inc_search_sec.php') ?>
<!-- search-pages end-->
<script>
	jQuery('.inner-sidebar-links li.personal').removeClass('active');
	jQuery('.inner-sidebar-links li.company').addClass('active');
</script>
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