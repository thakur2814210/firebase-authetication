<?php
require_once('session_setup.php');
require_once('ChromePhp.php');
ChromePhp::log('$_SESSION array in dashboard');
ChromePhp::log($_SESSION);
if (!isset($_SESSION['user_id'])) {
	header("Location: index.php");
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

<body class="dashboard-page inner-pages" style="overflow: visible;">
	<!-- wrapper-start -->
	<div class="wrapper">

		<div class="overlay" id="overLay"></div>
		<div class="super-overlay" id="superOverLay"></div>
		<div class="overlay-info" id="createFolder">
			<a href="#" class="close-btn closePopup"></a>
			<div class="create-folder inline-button">
				<h3>Create New Folder</h3>
				<input type="email" class="form-control" id="new_folder_name" placeholder="Please enter a new folder name here">
				<button id="create_new_folder" class="sign-up-btn" data-page="dashboard">Create</button>
				<button id="cancel_new_folder_creation" class="get-started-btn">Cancel</button>
			</div>
		</div>
<?php require_once('includes/inc_card_owner_profile.php'); ?>
		<?php require_once('includes/inc_card_details.php'); ?>

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

			<div id="main">
				<div class="folder-pages">
					<div class="top-sec">
						<h3>Dashboard</h3>
					</div>
					<!--					<ul class="nav nav-tabs tabs-in-on" role="tablist">
											<li role="presentation" class="active inComingTabLink"><a href="#incoming" aria-controls="incoming" role="tab" data-toggle="tab">Incoming</a></li>
											<li role="presentation" class="outGoingTabLink"><a href="#outgoing" aria-controls="outgoing" role="tab" data-toggle="tab">Outgoing</a></li>
										</ul>-->
				</div>
				<div class="tab-content in_on_request">
					<div role="tabpanel" class="tab-pane active" id="activity">
						<div class="scrollingTable scrolling-area scrollbar-style">
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
					</div>
					<!--THESE ARE INCOMING REQUEST NOW REPLACED BY NOTIFICATIONS
										<div role="tabpanel" class="tab-pane active" id="incoming">
											<div class="scrollingTable scrolling-area scrollbar-style">
												<table id="grid_in" class="table table-hover" width="100%">
													<thead class="cards-holder">
														<tr>
															<th>Name & Company</th>
															<th>Card Name</th>
															<th>Card Type</th>
															<th>Added On</th>
															<th>&nbsp;</th>
														</tr>
													</thead>
					
												</table>
												<div class="table-bottom-info">
													<div class="prev-next">
													</div>
												</div>
											</div>-->

					<div class="title-folder">
						<h3>My Folders</h3>
					</div>
					<div class="horizontal-scroll scrollbar-style">
						<ul class="all-folders">

						</ul>
					</div>


				</div>
				<!--					<div role="tabpanel" class="tab-pane outgoing-tab active" id="outgoing">
										<div class="scrollingTable scrolling-area scrollbar-style">
											<table id="grid_on" class="table table-hover" width="100%">
												<thead class="cards-holder">
													<tr>
														<th>Name & Company</th>
														<th>Card Name</th>
														<th>Card Type</th>
														<th>Added On</th>
														<th>&nbsp;</th>
													</tr>
												</thead>
				
											</table>
																			<div class="table-bottom-info">
																				<div class="prev-next">
																				</div>
																			</div>
										</div>
				
																	<div class="title-folder">
																		<h3>My Folders</h3>
																	</div>
																	<div class="horizontal-scroll scrollbar-style">
																		<ul class="all-folders">
										
																		</ul>
																	</div>
				
				
									</div>
				<div class="title-folder">
					<h3>My Folders</h3>
				</div>
				<div class="horizontal-scroll scrollbar-style">
					<ul class="all-folders">

					</ul>
				</div>
			</div>-->


				<!--</div>-->



			</div>


			<!-- sidebar-sec start-->
<?php require_once('includes/inc_sidebar.php'); ?>
			<!-- sidebar-sec end -->
		</div>
		<!--main end -->

	</div>
	<!-- wrapper-end -->
<?php include 'includes/footer.php'; ?>
	<?php require_once('includes/inc_search_sec.php') ?>
	<!-- search-pages end-->

	<script type="text/javascript" src="assets/js/lodash.compat.min.js"></script>
	<script type="text/javascript" src="<?php echo force_reload('profile.js'); ?>"></script>
	<!-- Load dependency assets -->
	<!-- TODO: include older version of handlebars-->
	<script type="text/javascript" src="assets/js/jquery.dataTables.min.js"></script>
	<link rel="stylesheet" type="text/css" href="assets/css/dataTables.bootstrap.css">
	<script type="text/javascript" src="assets/js/handlebars-v2.0.0.js"></script>
	<script type="text/javascript" src="assets/js/jquery.history.js"></script>
	<script type="text/javascript" src="card-contacts/shared.js"></script>
	<script type="text/javascript" src="<?php echo force_reload('card-request.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo force_reload('notifications.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo force_reload('dashboard.js'); ?>"></script>
	<script type="text/javascript" src="assets/js/star-rating.js"></script>

</body>
</html>		