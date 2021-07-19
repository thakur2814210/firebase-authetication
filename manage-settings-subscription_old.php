<?php
require_once('session_setup.php');
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	header( "Location: index.php" );
	exit;
}
?> 
<?php
include 'includes/head.php';
include 'includes/nav_logged_in.php';
include 'includes/stripe/get_current_subscription.php';
?>

<div class="row">
	<div class="col-lg-6 col-lg-offset-3  col-md-6 col-md-offset-3  col-sm-8 col-sm-offset-2 ">

				<?php if ( $has_subscription ) : ?>

			<div class="panel panel-danger">
				<div class="panel-heading">Subscription change detected</div>
				<div class="panel-body">
	<?php echo sprintf( "Your plan will change from '%s' to '%s'.", $old_plan->plan_name, $new_plan->plan_name ); ?><br><br>

					Do you want to continue with the change?<br>
					<button class="btn btn-primary" id="btnContinue">Continue</button>
				</div>
			</div>

			<script type="text/javascript">
				jQuery(document).on("click", "#btnContinue", function () {
					jQuery.loader({
						className: "blue-with-image-2",
						content: ''
					});
					var url = "<?php echo "includes/stripe/update_subscription.php?plan_id=" . $plan_result[ 'id' ] . "&new_plan_id=" . $new_plan->plan_id . "&card_id=" . $card_id; ?>";
					jQuery.post(url, {}, function (data) {
						var resp = JSON.parse(data);
						if ( resp.success ) {
							window.location.href = globalURL + "manage-your-cards.php";
						}
					});
				});
			</script>


<?php else : ?>

			<div class="panel panel-default">

				<div class="panel-heading">
					New subscription detected
				</div>

				<div class="panel-body">
					Pay for your new subscription<br> 
				</div>

				<div class="container">
					<div class="row">
						<div class="col-xs-12 col-md-4">
							<div class="panel panel-default">
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
												<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
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
										<button type="submit" class="btn btn-primary">Continue</button>
									</form>
								</div>
							</div>
							<!--             <ul class="nav nav-pills nav-stacked">
															<li class="active"><a href="#"><span class="badge pull-right"><span class="glyphicon glyphicon-usd"></span>4200</span> Final Payment</a>
															</li>
													</ul>
													<br/>
													<a href="http://www.jquery2dotnet.com" class="btn btn-success btn-lg btn-block" role="button">Pay</a> -->
						</div>
					</div>
				</div>

			</div>

<?php endif; ?>

	</div>
</div>

<!-- Load page specific assets -->
<!-- css -->
<!-- js -->
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="assets/js/handlebars-v2.0.0.js" type="text/javascript"></script>

<!-- payment validation library created by stripe -->
<script src="assets/js/jquery.payment.js" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="assets/css/cc-details-bootsnip.css">
<script type="text/javascript" src="assets/mimiz-jquery-loader/jquery.loader.js"></script>

<script src="card-contacts/shared.js" type="text/javascript"></script> 
<script src="manage-settings-subscription.js" type="text/javascript"></script>

<?php include 'includes/footer.php'; ?>

</body>
</html>