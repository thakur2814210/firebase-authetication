<?php require_once('session_setup.php');
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	header( "Location: index.php" );
	exit;
}  
include 'includes/head.php';
include 'includes/nav_logged_in.php';
include 'includes/stripe/get_plan_details.php' 
?>

<div class="col-xs-6 col-sm-4 col-sm-offset-2 col-md-4 col-md-offset-2 col-lg-3 col-lg-offset-3">
	<!-- PRICE ITEM -->
	<div class="panel price panel-red">
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
		</div>
		<ul class="list-group list-group-flush text-center">
<!-- 			<li class="list-group-item"><i class="icon-ok text-danger"></i> Personal use</li>
			<li class="list-group-item"><i class="icon-ok text-danger"></i> Unlimited projects</li>
			<li class="list-group-item"><i class="icon-ok text-danger"></i> 27/7 support</li> -->
		</ul>
		<div class="panel-footer">
			<a class="btn btn-lg btn-block btn-primary" href="#" id="btn_monthly">Continue</a>
		</div>
	</div>
</div>

<div class="col-xs-6 col-sm-4 col-md-4 col-lg-3">
	<!-- PRICE ITEM -->
	<div class="panel price panel-blue">
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
		</div>
		<ul class="list-group list-group-flush text-center">
<!-- 			<li class="list-group-item"><i class="icon-ok text-info"></i> Personal use</li>
			<li class="list-group-item"><i class="icon-ok text-info"></i> Unlimited projects</li>
			<li class="list-group-item"><i class="icon-ok text-info"></i> 27/7 support</li> -->
		</ul>
		<div class="panel-footer">
			<a class="btn btn-lg btn-block btn-primary" href="#" id="btn_yearly">Continue</a>
		</div>
	</div>
</div>

<!-- Load page specific assets -->
<!-- js -->
<script type="text/javascript">
	jQuery(document).ready(function (jQuery) {

	});
	jQuery(document).on("click", "#btn_monthly", function () {
		jQuery.loader({
			className: "blue-with-image-2",
			content: ''
		});
		window.location.href = "manage-settings-subscription.php" + window.location.search + "_m";
	});
	jQuery(document).on("click", "#btn_yearly", function () {
		jQuery.loader({
			className: "blue-with-image-2",
			content: ''
		});
		window.location.href = "manage-settings-subscription.php" + window.location.search + "_y";
	});
</script>

<link rel="stylesheet" type="text/css" href="assets/mimiz-jquery-loader/jquery.loader.css">
<script type="text/javascript" src="assets/mimiz-jquery-loader/jquery.loader.js"></script>

<?php include 'includes/footer.php'; ?>

</body>
</html>
