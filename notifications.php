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
		<div id="main">
			<div class="folder-pages">
				<div class="top-sec">
					<h3>Activity notifications</h3>
				</div>
<!--				<ul class="nav nav-tabs tabs-in-on" role="tablist">
					<li role="presentation" class="active inComingTabLink"><a href="#activity" aria-controls="incoming" role="tab" data-toggle="tab">Activity</a></li>
					<li role="presentation" class="outGoingTabLink"><a href="#alerts" aria-controls="outgoing" role="tab" data-toggle="tab">Notification alerts</a></li>
				</ul>-->
			</div>
			<div class="tab-content in_on_request" style="padding-top: 120px !important;">
				<div role="tabpanel" class="tab-pane active" id="activity">
					<table id="grid_activity" class="table table-hover" width="100%">
<!--						<thead class="cards-holder">
							<tr>
								<th>Author</th>
								<th>Activity description</th>
								<th>Date</th>
								<th>&nbsp;</th>
							</tr>
						</thead>-->

					</table>
					<div class="table-bottom-info">
						<div class="prev-next">
						</div>
					</div>
				</div>
        <div role="tabpanel" class="tab-pane outgoing-tab" id="alerts">
					<div class="notification-alert">
						<h4>Email me when:</h4>
						<ul>
							<li>
								<input name="checkboxG21" id="checkboxG21" class="css-checkbox" type="checkbox">
								<label for="checkboxG21" class="css-label">someone send me a card link request</label>
							</li>
							<li>
								<input name="checkboxG22" id="checkboxG22" class="css-checkbox" type="checkbox">
								<label for="checkboxG22" class="css-label">someone rate my card</label>
							</li>
							<li>
								<input name="checkboxG23" id="checkboxG23" class="css-checkbox" type="checkbox">
								<label for="checkboxG23" class="css-label">someone add my card</label>
							</li>
							<li>
								<input name="checkboxG24" id="checkboxG24" class="css-checkbox" type="checkbox">
								<label for="checkboxG24" class="css-label">someone add a mutual contact</label>
							</li>
							<li>
								<input name="checkboxG25" id="checkboxG25" class="css-checkbox" type="checkbox">
								<label for="checkboxG25" class="css-label">someone add a comment on my card</label>
							</li>
						</ul>
					</div>    
				</div>   

			</div>
		</div>
	</div>
</div>
<!--main end -->


</div>
<!--user-main-wrap end -->

</div>
<!-- wrapper-end -->

<?php require_once('includes/inc_search_sec.php') ?>
<!-- search-pages end-->

<?php include 'includes/footer.php'; ?>
<script type="text/javascript" src="assets/js/lodash.compat.min.js"></script>
<script type="text/javascript" src="<?php echo force_reload( 'profile.js' ); ?>"></script>
<!--<script type="text/javascript" src="https://cdn.datatables.net/s/dt/jqc-1.11.3,dt-1.10.10/datatables.min.js"></script>-->
<!--<script type="text/javascript" src="https://cdn.datatables.net/s/dt/jq-2.1.4,dt-1.10.10/datatables.min.js"></script>-->
<script type="text/javascript" src="assets/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="assets/css/dataTables.bootstrap.css">
<!-- Load dependency assets -->
<!-- TODO: include older version of handlebars-->
<script type="text/javascript" src="assets/js/handlebars-v2.0.0.js"></script>
<script type="text/javascript" src="assets/js/jquery.history.js"></script>
<script type="text/javascript" src="card-contacts/shared.js"></script>
<script type="text/javascript" src="assets/sat_js/jquery.mCustomScrollbar.js"></script>
<script type="text/javascript" src="<?php echo force_reload( 'notifications.js' ); ?>"></script>
<script type="text/javascript" src="assets/js/star-rating.js"></script>
</body>
</html>		