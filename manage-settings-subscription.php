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
include 'includes/stripe/get_current_subscription.php';
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
		<div class="main">
			<div class="folder-pages">
				<div class="top-sec">
					<h3>Cardition subscription plans</h3>
				</div>
			</div>

			<div class="container">
				<div class="bcn-card-sec">
					<?php if ( $has_subscription ) : ?>

						<div class="payment-data panel-default">
							<div class="panel-heading"><strong>Subscription change detected</strong></div>
							<div class="panel-body">
								<?php echo sprintf( "Your plan will change from '%s' to '%s'.", $old_plan->plan_name, $new_plan->plan_name ); ?><br><br>
								Do you want to continue with the change?<br>
								<div style="text-align:center">
									<button id="btnContinue" class="get-started-btn centered-btn btn-subscription">Continue</button>
									<button id="btnBack" class="get-started-btn centered-btn btn-subscription">Cancel</button>
								</div>
							</div>
						</div>

						<script type="text/javascript">
							jQuery(document).on("click", "#btnContinue", function () {
								var url = "<?php echo "includes/stripe/update_subscription.php?plan_id=" . $plan_result[ 'id' ] . "&new_plan_id=" . $new_plan->plan_id . "&card_id=" . $card_id; ?>";
								jQuery.post(url, {}, function (data) {
									var resp = JSON.parse(data);
									if ( resp.success ) {
										window.location.href = globalURL + "my-own-cards.php";
									}
								});
							});
							jQuery(document).on('click', '#btnBack', function (e) {
								e.preventDefault();
								window.location.href = 'my-own-cards.php';
							});
						</script>


					<?php else : ?>
						<div class="payment-data panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">
									Payment Details
								</h3>
							</div>
							<div class="panel-body">
								<form role="form" action="<?php echo "includes/stripe/subscribe_to_plan.php?plan_id=" . $plan_result[ 'id' ] . "&db_plan_id=" . $new_plan->plan_id . "&card_id=" . $card_id; ?>" method="POST" id="payment-form">
									<span class="payment-errors label label-danger"></span>
									<div class="form-group">
										<label for="cardNumber">
											CARD NUMBER</label>
										<div class="input-group">
											<input type="text" class="form-control" placeholder="Valid Card Number" size="20" data-stripe="number" id="cc_number" required autofocus />
											<span class="input-group-addon credit-card-icon"><span class="glyphicon glyphicon-lock"></span></span>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-7 col-md-7">
											<div class="form-group">
												<label for="expityMonth">EXPIRY DATE</label><div class="test-space"></div>
												<div class="col-xs-6 col-lg-6 pl-ziro">
													<input maxlength="2" type="text" class="form-control" placeholder="MM" size="2" data-stripe="exp-month" required />
												</div>
												<div class="col-xs-6 col-lg-6 pl-ziro">
													<input maxlength="2" type="text" class="form-control" placeholder="YY" size="4" data-stripe="exp-year" required />
												</div>
											</div>
										</div>
										<div class="col-xs-5 col-md-5 pull-right">
											<div class="form-group">
												<label for="cvCode">
													CVC CODE</label>
												<input maxlength="3" type="text" class="form-control" placeholder="CVC" required  size="4" data-stripe="cvc" id="cvc_number" />
											</div>
										</div>
									</div>
									<div style="text-align:center">
										<button id="btnContinue" class="get-started-btn centered-btn btn-subscription orange">Continue</button>
										<a class="btn btn-lg  get-started-btn centered-btn btn-subscription" href="my-own-cards.php">Cancel</a>
									</div>
								</form>
							</div>
						</div>
					<?php endif; ?>
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
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript" src="assets/js/lodash.compat.min.js"></script>
<script type="text/javascript" src="<?php echo force_reload( 'profile.js' ); ?>"></script>
<!-- Load dependency assets -->
<!-- TODO: include older version of handlebars-->
<script type="text/javascript" src="assets/js/handlebars-v2.0.0.js"></script>
<script type="text/javascript" src="assets/js/jquery.history.js"></script>
<script type="text/javascript" src="card-contacts/shared.js"></script>
<script type="text/javascript" src="assets/js/star-rating.js"></script>
<script src="manage-settings-subscription.js" type="text/javascript"></script>
</body>
</html>		