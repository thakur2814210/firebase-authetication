<?php include 'includes/head.php'; ?><?php include 'includes/nav_not_logged_in.php'; ?>

<html>
<head>
	<title>Registration Confirmation</title>
</head>
<body>
	<?php include 'includes/auth/confirm_registration.php'; ?>
	<?php if($token_exists){
		echo '<div class="jumbotron">
				  <h1>Successfully registered!</h1>
				  <p>You can now continue to log in.</p>
				  <p><a class="btn btn-primary btn-lg" href="'.$config->getBaseUrl().'index.php" role="button">Login</a></p>
			  </div>';
		}else{
		echo '<div class="jumbotron">
				  <h1>Error Occurred!</h1>
				  <p>You either followed an incorrect registration link or your verification period expired!</p>
				  <p><a class="btn btn-primary btn-lg" href="'.$config->getBaseUrl().'index.php" role="button">Retry!</a></p>
			  </div>';
		}
	?>
</body>
</html>