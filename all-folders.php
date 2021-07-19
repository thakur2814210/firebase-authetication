<?php
require_once('session_setup.php');
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	header( "Location: index.php" );
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


<!-- wrapper-start -->
<div class="wrapper">

	<div class="overlay" id="overLay"></div>
	<div class="super-overlay" id="superOverLay"></div>
 
	<div class="overlay-info" id="createFolder">
		<a href="#" class="close-btn closePopup"></a>
		<div class="create-folder inline-button">
			<h3>Create New Folder</h3>
			<input type="email" class="form-control" id="new_folder_name" placeholder="Please enter a new folder name here">
			<button id="create_new_folder" class="sign-up-btn" data-page="allfolders">Create</button>
			<button id="cancel_new_folder_creation" data-page="allfolders" class="get-started-btn">Cancel</button>
		</div>
	</div>
	<div class="overlay-info" id="renameFolder">
		<a href="#" class="close-btn closePopup"></a>
		<div class="create-folder inline-button">
			<h3>Rename Folder</h3>
			<input type="email" class="form-control" id="change_folder_name" placeholder="Please enter a new folder name here">
			<button id="rename_folder" class="sign-up-btn" data-folder-id="0" data-page="allfolders">Rename</button>
			<button id="cancel_new_folder_creation" data-page="allfolders"class="get-started-btn">Cancel</button>
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
					<h3>All Folders</h3>
					<a href="#" class="get-started-btn createNewFolder">Create New Folder</a>
				</div>
			</div>
			<div class="user-main-info">
				<div id="commands">
					<ul class="rename-delete">
						<li><a href="#" id="delete-folder"><img src="assets/sat_images/recycle.png" alt="">Delete</a></li>
						<li><a href="#" id="rename-folder"><img src="assets/sat_images/rename2.png" alt=""> Rename</a></li>
					</ul>					
				</div>
				<!--<a href="#" onclick="getFolders('all-folders', fillPage)">getFolders</a>-->
				<ul class="all-folders">

				</ul>
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
<script type="text/javascript" src="<?php echo force_reload( 'all-folders.js' ); ?>"></script>
<script type="text/javascript" src="assets/js/star-rating.js"></script>
</body>
</html>		