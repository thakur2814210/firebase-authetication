<?php
require_once('session_setup.php');
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	header( "Location: index.php" );
	exit;
}
if (isset($_SESSION['card']['creation_started']) && $_SESSION['card']['creation_started'] == true){
	unset($_SESSION['card']['creation_started']);
	header("Location: my-own-cards.php");
	exit;
}
if ( isset( $_SESSION[ 'card' ][ 'default' ] ) )
{
	include ('includes/create-business-card/cancel_card_creation.php');
	unset( $_SESSION[ 'card' ] );
}
if ( !isset( $_SESSION[ 'card' ][ 'card_id' ] ) )
{
	header( "Location: create-card-1.php" );
}
if ( !isset( $_GET[ 'type' ] ) )
{
	header( "Location: create-card-1.php" );
}
else
{
	$card_type = $_GET[ 'type' ];
	$_SESSION[ 'card' ][ 'card_type' ] = $card_type;
}
include_once('includes/head.php');
require_once('includes/database_config.php');
include_once('utilities/request_result.php');
include_once('utilities/mail.class.php');
require_once 'includes/profile/get_profile_details.php';
$request_result = new Result();
?>

<body data-creation-step="2">
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
						<h3>Create a New Business Card</h3>
					</div>
        </div>
        <div class="user-main-info">
					<div class="business-card-type">
						<p>You have selected to create a <?php echo ucfirst( $card_type ); ?> card. To choose another type of card, please <a href="create-card-1.php">click here</a></p>
						<ul>
							<li class="active"><a href="#" id="card_type_id"><?php echo ucfirst( $card_type ); ?></a></li>
						</ul>
						<div class="form-group">
							<label for="card_name">Card name <span style="color: red; font-size: 20px">*</span>
								<span class="warn_required">This field is required</span>
							</label>
							<input class="form-control card_data" id="card_name" placeholder="Choose a name for this card" type="text" />
							<?php if ( $card_type != 'Personal' && $card_type != 'Corporate' )
							{
								?>
								<label for="distributed_brand">Distributed brand <span style="color: red; font-size: 20px">*</span>
									<span class="warn_required">This field is required</span>
								</label>
								<input class="form-control card_data" id="distributed_brand" placeholder="Distributed brand" type="text" />
								<label for="category">Category <span style="color: red; font-size: 20px">*</span>
									<span class="warn_required">This field is required</span>
								</label>
								<input class="form-control card_data" id="category" placeholder="Choose a category" type="text" />
								<label for="sub_category">Subcategory <span style="color: red; font-size: 20px">*</span>
									<span class="warn_required">This field is required</span>
								</label>
								<input class="form-control card_data" id="sub_category" placeholder="Choose a subcategory" type="text" />
<?php } ?>
							<button id="set-card-name"></button>
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
	<script type="text/javascript" src="assets/mg_js/jquery.ui.rotatable.js"></script>
	<script type="text/javascript" src="assets/js/multidraggable.js"></script>
	<script type="text/javascript" src="<?php echo force_reload( 'create-card-intexted.js' ); ?>"></script>
	<script type="text/javascript" src="assets/js/star-rating.js"></script>
</body>
</html>
