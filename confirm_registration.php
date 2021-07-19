<?php
include 'includes/head.php';
include 'includes/auth/confirm_registration.php';
?>
<body>
	<!--header start -->
	<div id="header-wrap">
		<div class="container">
			<nav class="page-navigation">
				<div class="navigation-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#toggle-nav" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand logo1" href="index.php"><img src="assets/sat_images/cardition.png" alt=""></a>
					<a class="navbar-brand logo2" href="index.php"><img src="assets/sat_images/user-logo-new.png" alt=""></a>
				</div>
			</nav>
		</div>
	</div>
	<!--header end -->
	<div id="main-wrap" class="main-inner-wrap">
		<div id="main">
			<div class="main-banner home-banner">
				<div class="banner-info">
					<center><img src="assets/sat_images/main-banner.png" alt="" style="width: 80%"></center>
					<div class="banner-top-area">
						<?php if ($token_exists) { ?>
							<h1>Successfully registered!</h1>
							<p>You can now continue to log in.</p>
							<p><a href="<?php echo $config->getBaseUrl() . 'index.php' ?>" class="get-started-btn">Login</a></p>
						<?php } else { ?>
							<h1>Error Occurred!</h1>
							<p>You either followed an incorrect registration link or your verification period expired!</p>
							<p><a href="<?php echo $config->getBaseUrl() . 'index.php' ?>" class="get-started-btn">Retry!</a></p>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
