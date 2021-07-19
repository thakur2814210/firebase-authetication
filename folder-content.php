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
	<div class="overlay-info" id="share-with-contacts">
		<a href="#" data-popup-id="share-with-contacts" class="close-btn closePopup card-sharing-with-contacts"></a>
		<!--<a href="#" class="close-btn closePopup folder-creation-sub"></a>-->
		<div class="sharing-mail-contacts inline-button">
			<h3>Share cards with Cardition contacts</h3>
			<p id="contacts-tip">Please, select the contacts you want share the card with and press 'Share this card' button to send them your card image.</p>
			<div id="contacts-wrapper">
				<!--<input id="list-scroller" type="text" />-->
				<ul id="mail-contacts">

				</ul>
			</div>
			<input type="hidden" id="card_ids" value="" />
			<button id="share-card-with" class="get-started-btn save-settings" onclick="shareCardWithContacts()">Share this card</button>
			<button id="cancel-share-card-with" class="get-started-btn save-settings">Cancel</button>
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
    <!-- sidebar-sec start-->
		<?php require_once('includes/inc_sidebar.php'); ?>
    <!-- sidebar-sec end -->

    <div id="main">
			<div class="folder-pages">
				<div class="top-sec">
					<h3 id="page-title" data-folder-id=""></h3>
					<a href="#" class="get-started-btn createNewFolder">Create New Folder</a>
				</div>
				<div class="card-control-sec">
					<ul class="controls-list">
						<li class="card-selector tooltip-link"><a href="#" class="card-selector checkboxStyle"><strong>Select</strong><input name="checkboxG6" id="checkboxG6" class="css-checkbox commanCheckBox" type="checkbox">
								<label for="checkboxG6" class="css-label"></label></a>
<!--							<ul class="drop-down cbx-selector">
								<li><a href="#" id="allSelectedFolder">All</a></li>
								<li><a href="#" id="unSelectBox">None</a></li>
							</ul>-->
						</li><li class="delete-icon tooltip-link">
							<a href="#" id="delete_selected_cards"><img src="assets/sat_images/recycle.png" alt=""><strong>Delete</strong></a>
						</li><li class="folder-list"><a href="#" id="select-folder-dropdown"><img src="assets/sat_images/empty-folder.png" alt="" /></a>
							<ul class="drop-down select-folder">
								<li><strong>Copy selected to:</strong></li>
								<li id="new-folder"><a href="#">Create New Folder</a></li>
							</ul>
						</li><li id="sort_by_container"><a href="#" id="sort-folder-dropdown">Sort By</a>
						</li><li id="filter-sel"><a href="#" id="filter-folder-dropdown">Filter By</a>
							<ul id="mg-accordion" class="drop-down select-filter">
							</ul>
						</li><li><a href="#" id="view-folder-dropdown">View</a>
							<ul class="drop-down">
								<li><a href="#" id="card-size-1">Normal</a></li>
								<li><a href="#" id="card-size-double">Large</a></li>
								<li><a href="#" id="viewCardDetail">Details</a></li>
							</ul>
						</li><li class="email-icon tooltip-link">
							<a href="#" id="email_selected_cards"><img src="assets/sat_images/email.png" alt=""><strong>Send email</strong></a>
						</li><li class="share-icon tooltip-link">
							<a href="#" id="share_selected_cards"><img src="assets/mg_images/share_fc_1.png" alt=""><strong>Share cards</strong></a>
						</li>
					</ul>
					<div class="search-field">
						<input id="search-for" placeholder="Search" type="text">
						<a href="#" id="search-cards" class="searchSecLink"><img src="assets/sat_images/search-icon2.png" alt=""></a>
					</div>
				</div>
				<table class="table table-fix-head detail-table">
					<tr>
						<th>Name & Company</th>
						<th>Email</th>
						<th>Telephone</th>
						<th>Website</th>
						<th>BCN</th>
						<th>Card Type</th>
					</tr>    
				</table>
			</div>
			<div class="user-main-info manage-folders">
				<button class="toggle-btn mobileListBtn"><span>toggle menu</span></button>
				<h4 id="result-count-in-folder"></h4>
				<div class="my-cards">

				</div>
			</div>

			<div class="card-holder-table detail-table">
				<table id="card-details-table" class="table table-hover">
				</table>
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
<!--<script type="text/javascript" src="assets/js/jquery.history.js"></script>-->
<script type="text/javascript" src="card-contacts/shared.js"></script>
<script type="text/javascript" src="<?php echo force_reload( 'assets/mg_js/mg_contacts.js' );  ?>"></script>
<script type="text/javascript" src="assets/js/star-rating.js"></script>
<script>
	jQuery(document).ready(function () {
		loadDataFilterable("<?php echo filter_input( INPUT_GET, 'folder' ); ?>");
	});
</script>
</body>
</html>		