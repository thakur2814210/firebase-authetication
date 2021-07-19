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
if ( isset( $_SESSION[ 'card' ] ) )
{
	include ('includes/create-business-card/cancel_card_creation.php');
	unset( $_SESSION[ 'card' ] );
}
?>


<!-- wrapper-start -->
<div class="wrapper">

	<div class="overlay" id="overLay"></div>
	<div class="super-overlay" id="superOverLay"></div>

	<div class="overlay-info" id="card-personal-settings">
    <a href="#" class="close-btn closePopup"></a>
    <div class="card-setting inline-button">
			<h3>Modify Card Settings</h3>
			<form id="settings-checkboxes-personal" data-card-id="">
				<div class="checkboxes">
					<input type="checkbox" name="global_search" id="global_search" class="css-checkbox" />
					<label for="global_search" class="css-label">This card is visible in the global search</label>

					<input type="checkbox" name="seen_in_user_folder" id="seen_in_user_folder" class="css-checkbox" />
					<label for="seen_in_user_folder" class="css-label">The holders of this card are not hidden</label>

					<input type="checkbox" name="need_approval" id="need_approval" class="css-checkbox" />
					<label for="need_approval" class="css-label">User would need approval before viewing and saving this card</label>

					<input type="checkbox" name="requires_reciprocity" id="requires_reciprocity" class="css-checkbox" />
					<label for="requires_reciprocity" class="css-label">This card requires reciprocity when saved by an user (A card of the user will be automatically sent to me)</label>

				</div>
			</form>
			<button id="save-personal-card-settings" class="get-started-btn save-settings">Save my settings</button>
    </div>
	</div>
	<div class="overlay-info" id="card-professional-settings">
    <a href="#" class="close-btn closePopup"></a>
    <div class="card-setting inline-button">
			<h3>Modify Card Settings</h3>
			<form id="settings-checkboxes-professional" data-card-id="">
				<div class="checkboxes">
					<input type="checkbox" name="visible_pp_search" id="visible_pp_search" class="css-checkbox" />
					<label for="visible_pp_search" class="css-label">Allow to find this card by category in the "card search".  <span class="plan label orange"></span></label>

					<input type="checkbox" name="allow_rating" id="allow_rating" class="css-checkbox" />
					<label for="allow_rating" class="css-label">Allow ratings and comments for this card. <span class="plan label orange"></span></label>

				</div>
			</form>
			<button id="save-professional-card-settings" class="get-started-btn save-settings">Save my settings</button>
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
					<h3>My Own Cards</h3>
					<a href="create-card-0.php" class="get-started-btn">Create New Card</a>
				</div>
			</div>
			<div class="user-main-info">
				<div class="my-cards">

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
<script type="text/javascript">
	jQuery(document).ready(function () {
<?php
if ( isset( $_SESSION[ 'created_card_id' ] ) )
{
	echo "editCardSettings('{$_SESSION[ 'created_card_id' ]}', '{$_SESSION[ 'created_card_type' ]}');";
	unset($_SESSION[ 'created_card_id' ]);
	unset($_SESSION[ 'created_card_type' ]);
}
?>
		loadCards();
	});
</script>
</body>
</html>
