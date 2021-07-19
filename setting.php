<?php
require_once('session_setup.php');
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	header( "Location: index.php" );
	exit;
}
include_once('includes/head.php');
require_once('includes/database_config.php');
include_once('utilities/request_result.php');
include_once('utilities/mail.class.php');
require_once 'includes/profile/get_profile_details.php';
$request_result = new Result();
?>


<body  class="setting-page inner-pages">
<!-- wrapper-start -->
<div class="wrapper">

	<div class="overlay" id="overLay"></div>

	<div class="overlay-info" id="settingUpDate">
    <a href="#" class="close-btn closePopup"></a>
    <div class="seeting-update">
			<img src="assets/sat_images/setting-update.png" alt="">
			<p>Your settings have been Successfully Updated!</p>
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
		<div class="folder-pages">
			<div class="top-sec">
				<h3>Settings</h3>
			</div>
			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#update-password" aria-controls="update-password" role="tab" data-toggle="tab">Update Password</a></li>
				<li role="presentation"><a href="#subscription-preferences" aria-controls="subscription-preferences" role="tab" data-toggle="tab">Subscription Preferences</a></li>
			</ul>
		</div>
		<!-- Tab panes -->
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="update-password">
				<form class="update-password">
					<div class="form-group no-margin">
						<input type="hidden" name="email_address" id="email_address" value="<?php echo $_SESSION['user_email'] ?>" />"
						<label for="new_password">New Password</label>
						<input class="form-control" id="new_password" placeholder="" type="password">
					</div>
					<div class="form-group">
						<label for="confirm_password">Confirm Password</label>
						<input class="form-control" id="confirm_password" placeholder="" type="password">
					</div>
					<button class="get-started-btn" id="update_password">Update Password</button>
				</form>
				<!--class settingUpDateLink-->
			</div>
			<div role="tabpanel" class="tab-pane" id="subscription-preferences">
				<div class="subscription-preferences">
					<h4>Subscribe me to:</h4>
					<ul>
						<li>
							<input name="checkboxG26" id="checkboxG26" class="css-checkbox" type="checkbox">
							<label for="checkboxG26" class="css-label">Cardition Newsletter <strong>Periodic news, announcements and product updates</strong></label>
						</li>
					</ul>
					<a href="#" id="update_preferences" class="get-started-btn">Update Preferences</a>
					<p class="delete-account"><a id="delete-account" href="#">Delete Account</a></p>
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
<script type="text/javascript" src="<?php echo force_reload( 'profile.js' ); ?>"></script>
<!-- Load dependency assets -->
<!-- TODO: include older version of handlebars-->
<script type="text/javascript" src="assets/js/handlebars-v2.0.0.js"></script>
<script type="text/javascript" src="assets/js/jquery.history.js"></script>
<script type="text/javascript" src="card-contacts/shared.js"></script>
<script type="text/javascript" src="assets/js/star-rating.js"></script>
</body>
</html>		