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
include 'includes/stripe/get_plan_details.php';
require_once 'includes/profile/get_profile_details.php';
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
		<div class="main">
			<div class="folder-pages">
				<div class="top-sec">
					<h3>Choose your plan</h3>
				</div>
			</div>

			<div class="container">
				<div class="bcn-card-sec">
					<div class="about-bnc">
						<!-- PRICE ITEM -->
						<div class="price panel-red">
							<div class="panel-heading  text-center">
								<h3>Monthly Plan</h3>
							</div>
							<div class="panel-body text-center">
								<p class="lead" style="font-size:30px"><strong>&pound;<?php
										$num = $plan_monthly->amount;
										$after = substr( $num, -2 );
										$before = substr( $num, 0, strlen( $num ) - 2 );
										if ( $before == "" )
										{
											$before = "0";
										}
										$result = $before . "." . $after;
										echo $result;
										?> / month</strong></p>
								<div style="text-align:center">
									<a class="btn btn-lg  get-started-btn centered-btn btn-subscription orange" href="#" id="btn_monthly">Continue</a>
									<a class="btn btn-lg  get-started-btn centered-btn btn-subscription" href="my-own-cards.php" id="btn_monthly">Cancel</a>
								</div>
							</div>
							<ul class="list-group list-group-flush text-center">
					<!-- 			<li class="list-group-item"><i class="icon-ok text-danger"></i> Personal use</li>
								<li class="list-group-item"><i class="icon-ok text-danger"></i> Unlimited projects</li>
								<li class="list-group-item"><i class="icon-ok text-danger"></i> 27/7 support</li> -->
							</ul>
						</div>
					</div>

					<div class="virtual-digital-cards">
						<!-- PRICE ITEM -->
						<div class="price panel-blue">
							<div class="panel-heading arrow_box text-center">
								<h3>Yearly Plan</h3>
							</div>
							<div class="panel-body text-center">
								<p class="lead" style="font-size:30px"><strong>&pound;<?php
										$num = $plan_yearly->amount;
										$after = substr( $num, -2 );
										$before = substr( $num, 0, strlen( $num ) - 2 );
										if ( $before == "" )
										{
											$before = "0";
										}
										$result = $before . "." . $after;
										echo $result;
										?> / year</strong></p>
								<div style="text-align:center">
									<a class="btn btn-lg get-started-btn centered-btn btn-subscription orange" href="#" id="btn_yearly">Continue</a>
									<a class="btn btn-lg get-started-btn centered-btn btn-subscription" href="my-own-cards.php" id="btn_yearly">Cancel</a>
								</div>
							</div>
							<ul class="list-group list-group-flush text-center">
					<!-- 			<li class="list-group-item"><i class="icon-ok text-info"></i> Personal use</li>
								<li class="list-group-item"><i class="icon-ok text-info"></i> Unlimited projects</li>
								<li class="list-group-item"><i class="icon-ok text-info"></i> 27/7 support</li> -->
							</ul>
						</div>
					</div>
				</div>
			</div>

		</div>
		<!--end main-->
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
	jQuery(document).ready(function (jQuery) {

	});
	jQuery(document).on("click", "#btn_monthly", function () {
		loader.show();
		window.location.href = "manage-settings-subscription.php" + window.location.search + "_m";
	});
	jQuery(document).on("click", "#btn_yearly", function () {
		loader.show();
		window.location.href = "manage-settings-subscription.php" + window.location.search + "_y";
	});
</script>

</body>
</html>		