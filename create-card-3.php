<?php
//error_reporting( E_ALL );
//ini_set( 'display_errors', 'On' );
require_once('session_setup.php');
require_once('includes/absolute_database_config.php');
$background_absolute_path = $_SERVER['DOCUMENT_ROOT'] . '/includes/create-business-card/uploads/background/';
if (!isset($_SESSION['user_id'])) {
	header("Location: index.php");
	exit;
}
if (isset($_SESSION['card']['default'])) {
	include ('includes/create-business-card/cancel_card_creation.php');
	unset($_SESSION['card']);
}

$_SESSION['card']['creation_started'] = true;
if (isset($_GET['card_id'])) {
	$_SESSION['card']['card_id'] = $_GET['card_id'];
	$_SESSION['card']['card_type'] = $_GET['card_type'];
	$query = "SELECT card_name, distributed_brand, category, sub_category FROM card WHERE card_id = '" . $_SESSION['card']['card_id'] . "'";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_row($result);
	$_SESSION['card']['card_name'] = $row[0];
	$_SESSION['card']['distributed_brand'] = $row[1];
	$_SESSION['card']['category'] = $row[2];
	$_SESSION['card']['sub_category'] = $row[3];
	$_SESSION['card']['edit_mode'] = (!empty($_GET['edit_mode'])) ? true : false;
	if ($_SESSION['card']['edit_mode'] === true) {
		$query = "SELECT assigned_id FROM card WHERE card_id = '" . $_SESSION['card']['card_id'] . "'";
		$result = mysqli_query($conn, $query);
		$bcn = mysqli_fetch_row($result);
		$_SESSION['card']['bcn'] = $bcn[0];
	}
} else if (!isset($_SESSION['card']['card_id']) || !isset($_SESSION['card']['card_type'])) {
	header("Location: create-card-0.php");
} else if (!isset($_GET['card_name'])) {
	header("Location: create-card-0.php");
} else {
	$card_name = $_GET['card_name'];
	$_SESSION['card']['card_name'] = $card_name;
}
if (isset($_GET['distributed_brand']) &&
		isset($_GET['category']) &&
		isset($_GET['sub_category'])) {
	$_SESSION['card']['distributed_brand'] = filter_input(INPUT_GET, 'distributed_brand', FILTER_SANITIZE_STRING);
	$_SESSION['card']['category'] = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_STRING);
	$_SESSION['card']['sub_category'] = filter_input(INPUT_GET, 'sub_category', FILTER_SANITIZE_STRING);
}
include_once('includes/head.php');
require_once('includes/database_config.php');
include_once('utilities/request_result.php');
include_once('utilities/mail.class.php');
require_once 'includes/profile/get_profile_details.php';
require_once 'includes/create-business-card/get_business_card_details.php';
?>

<body data-creation-step="3" style="cursor: auto;">
	<!-- wrapper-start -->
	<div class="wrapper">
		<div id="overLay" class="overlay"></div>
		<div class="super-overlay" id="superOverLay"></div>
		<div class="overlay-info" id="buy-premium-id" data-card-id="">
			<a href="#" class="close-btn closePopup"></a>
			<div class="card-setting inline-button">
				<h3>Buy Premium ID (&pound;10.00)</h3>
				<p>Please enter the ID you would like to purchase:</p>
				<form id="premium-id-data">
					<input id="premium_id" value="" name="premium_id" autofocus type="text" class="form-control" maxlength="30"> <span id="premium-id-result" style="position:absolute; right:8px; top:8px; width:14px; height:14px;"></span>
				</form>
				<div id="na_bcn_msg"></div>
				<div style="margin-top:20px; text-align: left;">
					<small><strong>Please note:</strong>
						<ol style="margin-top: 10px">
							<li>No spaces, dashes or special characters</li>
							<li>Underscores allowed</li>
							<li>Characters are limited to 30</li>
						</ol>
					</small>
				</div>
<?php
if (isset($_SESSION['card']['edit_mode'])) {
	$url = 'my-own-cards.php?process_completed=true&buying=true';
} else {
	$url = 'my-own-cards.php?buying=true';
}
?>
				<button data-url="<?php echo $url; ?>" data-card-id="<?php echo $_SESSION['card']['card_id']; ?>" id="btnBuyPrem" class="get-started-btn save-settings disabled">Buy BCN</button>
			</div>
		</div>

		<!--PREVIEW-SAVE-->
		<div class="overlay-info" id="preViewSave">
			<a href="#" class="close-btn saving-card closePopup"></a>
			<div class="preview-save inline-button">
				<?php
				if (isset($orientation)) {
					if ($orientation === 'landscape') {
						$class = "img-flip aboutCardposition";
					} else {
						$class = "img-flip aboutCardposition portrait";
					}
				}
				?>
				<div class="<?php echo $class; ?>">
					<div>
						<div id='new-card-data'>
							<img src="" alt="">
						</div>
						<div id="back-side-content-id2" class="back-side-content">
							<img src="" alt="">
						</div>
					</div>
				</div>
				<div class="card-info">
					<ul>
						<li><a class="front-side frontSideLink" href="#">Front side</a></li>
						<li><a class="back-side backSideLink" href="#">Back side</a></li>
					</ul>
				</div>
				<h3>Your Card BCN Is: <strong id='new-card-bcn'></strong></h3>
				<?php
				if (isset($_SESSION['card']['edit_mode'])) {
					$url = 'my-own-cards.php?process_completed=true';
				} else {
					$url = 'my-own-cards.php';
				}
				?>
				<a href="#" data-url="<?php echo $url; ?>" id="save-bc" class="get-started-btn">Save</a>
				<a href="my-own-cards.php" data-url="my-own-cards.php" id="cancel-bc" class="get-started-btn">Cancel</a>
				<a href="#" data-card-id="<?php echo $_SESSION['card']['card_id'] ?>" id="buy-bcn" class="sign-up-btn">Save and Buy Premium BCN (£ 10.00)</a>
				<!--				<button class="get-started-btn" id="save-card">Save</button>
								<button class="sign-up-btn">Buy Premium ID (£ 10.00)</button>-->
			</div>
		</div>

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
						<!--<h3>Create a New Business Card (<?php // echo $_SESSION[ 'card' ][ 'card_id' ];     ?>)</h3>-->
					</div>
				</div>
				<ul class="top-bar">
					<li>
						<a href="#" id="import-card-image">
							<img src="assets/sat_images/import-card-img.png" alt="">
							<strong>Import <i>Card Image</i></strong>
						</a>
					</li>
					<li>
						<a href="#" id="add-background">
							<img src="assets/sat_images/background.png" alt="">
							<strong>Background</strong>
						</a>
					</li>
					<li>
						<a href="#" id="import-data">
							<img src="assets/sat_images/import-details.png" alt="">
							<strong>Import <i>Details</i></strong>
						</a>
					</li>
					<li>
						<a href="#" id='add-text-field' class='editing'>
							<input type="text" placeholder="abc" disabled="disabled" />
							<strong>Add <i>Text</i></strong>
						</a>
					</li>
					<li>
						<a href="#" id="add-logo">
							<img src="assets/sat_images/add-logo.png" alt="">
							<strong>Add <i>Logo</i></strong>
						</a>
					</li>
					<li>
						<a href="#" id="add-links">
							<img src="assets/sat_images/add-links.png" alt="">
							<strong>Add <i>Links</i></strong>
						</a>
					</li>
					<li>
<?php
if (isset($_SESSION['card']['edit_mode'])) {
	$url = 'my-own-cards.php?process_completed=true';
} else {
	$url = 'create-card-0.php';
}
?>
						<a href="#" data-url="<?php echo $url; ?>" id="discard" class="get-started-btn">Discard</a>
						<a id="preview-and-save" href="#" class="sign-up-btn addCardWithBCN">Preview & Save</a>
					</li>
				</ul>
				<div class="card-area">
					<ul class="card-postion" id="cardPosTion">
						<li><a href="#" class="front-side frontSideLink img-postion-link">Front side</a></li>
						<li><a href="#" class="back-side backSideLink img-postion-link">Back side</a></li>
<?php
if (isset($orientation)) {
	if ($orientation === 'landscape') {
		$class1 = "card-rotation cardRotaTion active";
		$class2 = "card-rotation cardRotaTion";
	} else {
		$class1 = "card-rotation cardRotaTion";
		$class2 = "card-rotation cardRotaTion active";
	}
}
if (!isset($_SESSION['card']['edit_mode'])) {
	?>
							<ul>
								<li id="landscapeButton" class="<?php echo $class1; ?>"><a href="#" class="landScapeCard">Landscape</a></li>
								<li id="portraitButton" class="<?php echo $class2; ?>"><a href="#" class="portraitCard">Portrait</a></li>
							</ul>
<?php } ?>
					</ul>
					<div class="row" style="max-width: 100%;">
						<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
<?php
if (isset($orientation)) {
	if ($orientation === 'landscape') {
		$class = "img-flip aboutCardposition";
	} else {
		$class = "img-flip aboutCardposition portrait";
	}
}
?>
							<div class="<?php echo $class; ?>">
								<div id="canvas-container">
									<!-- CARD CANVAS AREA -->
									<div id="first-drop">
						<?php
//										if ( isset( $canvas_front ) && $canvas_front != "" && $canvas_front != NULL )
//										{
						?>
										<!--<div id="droppable" class="panel panel-default" style="background: url(//<?php // echo $canvas_front . "?" . time(); ?>) #fff center center no-repeat;">-->
						<?php //	}else{?>
										<div id='droppable' class='panel panel-default' style='background: url(assets/images/card_default_white.jpg) #fff center center no-repeat;position: relative; top: 0px; left: 0px;'>
<?php
//}
if (isset($widgets_front) && $widgets_front != "" && $widgets_front != NULL) {
	echo $widgets_front;
}
?>
										</div>

							<?php
//											if ( isset( $canvas_back ) && $canvas_back != "" && $canvas_back != NULL )
//											{
							?>
												<!--<div id="back-side-content-id" class="back-side-content" style="background: url(<?php // echo $canvas_back . "?" . time(); ?>) #fff center center no-repeat;" >-->
							<?php
//												}
//												else
//												{
							?>
										<div id="back-side-content-id" class="back-side-content panel panel-default2" >
							<?php
//													}
							?>
<?php
if (isset($widgets_back) && $widgets_back != "" && $widgets_back != NULL) {
	?>
											<?php echo $widgets_back ?>
											<?php
										}
										?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--end col7-->
						<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
							<div class="upload-panel" id="background-panel">
								<div class="upload-panel-title">
									<h4 id="bg-panel-title">Background options</h4>
									<span style="cursor: pointer; color: /*#fd6f02*/#fff;" class="glyphicon glyphicon-remove close-panel"></span>
								</div>
								<div class="upload-panel-body">
									<form id="BForm" method="post" action="includes/create-business-card/background_upload.php" enctype="multipart/form-data">
										<a href="#" class="get-started-btn" id="select-background">Select a file</a>
										<!--<p style="font-size: 11px; margin-top: -10px; margin-bottom: 0px;">(Max 500kb)</p>-->
										<button class="upload-background get-started-btn picture-upload editing disabled" id="btn-upload-bg" onclick="fileUploadBackground(this.form, 'upload_background', jQuery('.aboutCardposition').hasClass('active')); return false;">Upload</button>
										<input type="file" name="file" id="inputFileB" placeholder="Upload Image" style="margin:10px 0;">
										<a href="#" class="get-started-btn colpicker" placeholder="">Pick a color</a>
										<a href="#" class="get-started-btn editing" id="set-color">Set color</a>
									</form>
									<div id="upload_background"></div>
								</div>
							</div>
							<div class="upload-panel" id="logo-panel">
								<div class="upload-panel-title">
									<h4>Logo options</h4>
									<span style="cursor: pointer; color: /*#fd6f02*/#fff;" class="glyphicon glyphicon-remove close-panel"></span>
								</div>
								<div class="upload-panel-body">
									<form id="LForm" method="post" action="includes/create-business-card/file_upload_h.php" enctype="multipart/form-data">
										<a href="#" class="get-started-btn" id="select-logo">Select a file</a>
										<button class="upload-logo get-started-btn picture-upload editing disabled" id="" onclick="fileUploadLogo(this.form, 'includes/create-business-card/file_upload_h.php', 'upload_logo'); return false;">Upload</button>
										<input type="file" name="file" id="inputFileL" placeholder="Upload Image" style="margin:10px 0;">
									</form>
									<div id="upload_logo"></div>
								</div>
							</div>
							<!-- /WIDGETS -->
							<div class="upload-panel" id="import-details">
								<div class="upload-panel-title">
									<h4>Import your data</h4>
									<span style="cursor: pointer; color: /*#fd6f02*/#fff;" class="glyphicon glyphicon-remove close-panel"></span>
								</div>
								<div class="col-md-6 col-xs-6">
<?php //if ($_SESSION["card_type"] == 'Personal') {   ?>
									<!-- NAME WIDGETS -->
									<h3 class="panel-title" style="padding:10px; margin-bottom:10px;">Personal Details:</h3>
									<!-- TITLE -->
									<div class="input-group insertables">
										<div class="wysi-adder"><input type="text" id="title-field" value="<?php echo $title; ?>" class="form-control" placeholder="Title"></div>
										<span class="input-group-addon click-container2"> <span id="title-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
									<!-- FIRST NAME -->
									<div class="input-group insertables">
										<div class="wysi-adder"><input type="text" id="first-name-field" value="<?php echo $first_name; ?>" class="form-control" placeholder="First Name"></div>
										<span class="input-group-addon click-container2"> <span id="first-name-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
									<!-- LAST NAME -->
									<div class="input-group insertables">
										<div class="wysi-adder"><input type="text" id="last-name-field" value="<?php echo $last_name; ?>" class="form-control" placeholder="Last Name"></div>
										<span class="input-group-addon click-container2"> <span id="last-name-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
<?php //} else {  ?>
<?php
if ($_SESSION['card']["card_type"] != 'Personal') {
	?>
										<!-- COMPANY WIDGETS -->
										<h3 class="panel-title" style="padding:10px; margin-bottom:10px;">Company Details:</h3>
										<!-- COMPANY NAME -->
										<div class="input-group insertables">
											<div class="wysi-adder"><input type="text" id="comp-name-field" value="<?php echo $company_name; ?>" class="form-control" placeholder="Company Name"></div>
											<span class="input-group-addon click-container2"> <span id="comp-name-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
										<!-- DEPARTMENT NAME -->
										<div class="input-group insertables">
											<div class="wysi-adder"><input type="text" id="comp-name-field" value="<?php echo $department_name; ?>" class="form-control" placeholder="Department Name"></div>
											<span class="input-group-addon click-container2"> <span id="comp-name-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
										<!-- CORPORATE CODE -->
										<div class="input-group insertables">
											<div class="wysi-adder"><input type="text" id="comp-name-field" value="<?php echo $corporate_code; ?>" class="form-control" placeholder="Corporate Code"></div>
											<span class="input-group-addon click-container2"> <span id="comp-name-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
										<!-- POSITION -->
										<div class="input-group insertables">
											<div class="wysi-adder"><input type="text" id="comp-name-field" value="<?php echo $position; ?>" class="form-control" placeholder="Position"></div>
											<span class="input-group-addon click-container2"> <span id="comp-name-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
<?php } //}      ?>
									<!-- ADDRESS WIDGETS -->
									<h3 class="panel-title" style="padding:10px; margin-bottom:10px;">Address Details:</h3>
<?php
if ($_SESSION['card']["card_type"] == 'Personal') {
	?>
										<!-- STREET ADDRESS -->
										<div class="input-group insertables">
											<div class="wysi-adder"><input type="text" id="address-field" value="<?php echo $personal_address_1; ?>" class="form-control" placeholder="Address 1"></div>
											<span class="input-group-addon click-container2"> <span id="address-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
										<div class="input-group insertables">
											<div class="wysi-adder"><input type="text" id="address-field" value="<?php echo $personal_address_2; ?>" class="form-control" placeholder="Address 2"></div>
											<span class="input-group-addon click-container2"> <span id="address-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
										<!-- CITY/TOWN -->
										<div class="input-group insertables">
											<div class="wysi-adder"><input type="text" id="city-field" value="<?php echo $personal_city; ?>" class="form-control" placeholder="City"></div>
											<span class="input-group-addon click-container2"> <span id="city-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
										<!-- POSTAL CODE -->
										<div class="input-group insertables">
											<div class="wysi-adder"><input type="text" id="post-code-field" value="<?php echo $personal_post_code; ?>" class="form-control" placeholder="Postal Code"></div>
											<span class="input-group-addon click-container2"> <span id="post-code-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
										<!-- COUNTRY -->
										<div class="input-group insertables">
											<div class="wysi-adder"><input type="text" id="post-code-field" value="<?php echo $personal_country; ?>" class="form-control" placeholder="Postal Code"></div>
											<span class="input-group-addon click-container2"> <span id="post-code-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
	<?php
} else {
	?>
										<!-- STREET ADDRESS -->
										<div class="input-group insertables">
											<div class="wysi-adder"><input type="text" id="address-field" value="<?php echo $company_address_1; ?>" class="form-control" placeholder="Address 1"></div>
											<span class="input-group-addon click-container2"> <span id="address-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
										<div class="input-group insertables">
											<div class="wysi-adder"><input type="text" id="address-field" value="<?php echo $company_address_2; ?>" class="form-control" placeholder="Address 2"></div>
											<span class="input-group-addon click-container2"> <span id="address-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
										<!-- CITY/TOWN -->
										<div class="input-group insertables">
											<div class="wysi-adder"><input type="text" id="city-field" value="<?php echo $company_city; ?>" class="form-control" placeholder="City"></div>
											<span class="input-group-addon click-container2"> <span id="city-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
										<!-- POSTAL CODE -->
										<div class="input-group insertables">
											<div class="wysi-adder"><input type="text" id="post-code-field" value="<?php echo $company_post_code; ?>" class="form-control" placeholder="Postal Code"></div>
											<span class="input-group-addon click-container2"> <span id="post-code-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
<?php } ?>
								</div>
								<div class="col-md-6 col-xs-6">
									<!-- CONTACT WIDGETS -->
									<h3 class="panel-title" style="padding:10px; margin-bottom:10px;">Contact Details:</h3>
<?php
if ($_SESSION['card']["card_type"] == 'Personal') {
	?>
										<!-- GENERAL PHONE NUMBER -->
										<div class="input-group insertables">
											<div class="wysi-adder"><input type="text" id="phone-field" value="<?php echo $phone; ?>" class="form-control" placeholder="Phone Number"></div>
											<span class="input-group-addon click-container2"> <span id="phone-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
										<!-- CELLPHONE NUMBER -->
										<div class="input-group insertables">
											<div class="wysi-adder"><input type="text" id="cell-field" value="<?php echo $mobile; ?>" class="form-control" placeholder="Cellphone Number"></div>
											<span class="input-group-addon click-container2"> <span id="cell-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
										<!-- EMAIL ADDRESS -->
										<div class="input-group insertables">
											<div class="wysi-adder"><input type="text" id="email-field" value="<?php echo $email_address; ?>" class="form-control" placeholder="Email Address"></div>
											<span class="input-group-addon click-container2"> <span id="email-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
										<?php
									} else {
										?>
										<!-- GENERAL PHONE NUMBER -->
										<div class="input-group insertables">
											<div class="wysi-adder"><input type="text" id="phone-field" value="<?php echo $company_phone; ?>" class="form-control" placeholder="Phone Number"></div>
											<span class="input-group-addon click-container2"> <span id="phone-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
										<!-- CELLPHONE NUMBER -->
										<div class="input-group insertables">
											<div class="wysi-adder"><input type="text" id="cell-field" value="<?php echo $company_mobile; ?>" class="form-control" placeholder="Cellphone Number"></div>
											<span class="input-group-addon click-container2"> <span id="cell-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
										<!-- EMAIL ADDRESS -->
										<div class="input-group insertables">
											<div class="wysi-adder"><input type="text" id="email-field" value="<?php echo $company_email_address; ?>" class="form-control" placeholder="Email Address"></div>
											<span class="input-group-addon click-container2"> <span id="email-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
<?php } ?>
									<!-- SOCIAL MEDIA WIDGETS -->
									<h3 class="panel-title" style="padding:10px; margin-bottom:10px;">Website Details:</h3>
<?php
if ($_SESSION['card']["card_type"] == 'Personal') {
	?>
										<!-- WEBSITE LINK -->
										<div class="input-group insertables">
											<div class="wysi-adder"><input type="text" id="web-link-field" value="<?php echo $website_link; ?>" class="form-control" placeholder="Website Link"></div>
											<span class="input-group-addon click-container2"> <span id="web-link-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
										<?php
									} else {
										?>
										<!-- WEBSITE LINK -->
										<div class="input-group insertables">
											<div class="wysi-adder"><input type="text" id="web-link-field" value="<?php echo $company_website_link; ?>" class="form-control" placeholder="Website Link"></div>
											<span class="input-group-addon click-container2"> <span id="web-link-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
<?php } ?>
<?php
if ($_SESSION['card']["card_type"] == 'Product' || $_SESSION['card']["card_type"] == 'Professional') {
	?>
										<!-- DISTRIBUTED BRAND -->
										<h3 class="panel-title" style="padding:10px; margin-bottom:10px;">Distributed Brand:</h3>
										<div class="input-group insertables">
											<div class="wysi-adder"><input type="text" id="add-info-field" value="<?php echo $distributed_brand; ?>" class="form-control" placeholder="Distributed Brand"></div>
											<span class="input-group-addon click-container2"> <span id="add-info-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
									<?php }; ?>
									<!-- ADDITIONAL INFORMATION WIDGET -->
									<h3 class="panel-title" style="padding:10px; margin-bottom:10px;">Additional Info:</h3>
									<div class="input-group insertables">
										<div class="wysi-adder"><input type="text" id="add-info-field" value="" class="form-control" placeholder="Additional Information"></div>
										<span class="input-group-addon click-container2"> <span id="add-info-plus" class="glyphicon glyphicon-plus plus editing" style="cursor: pointer"></span> </span> </div>
								</div>
								<div class="clearfix"></div>
							</div>
							<!-- /WIDGETS -->
						</div>
						<!--end col5-->
					</div>
					<!--end row-->
				</div>
				<!--end card-area-->
			</div>
			<!--main end -->
		</div>
		<!--user-main-wrap end -->
	</div>
	<!-- wrapper-end -->

<?php require_once('includes/inc_search_sec.php') ?>
	<!-- search-pages end-->
									<?php include 'includes/footer.php'; ?>
	<script type="text/javascript" src="assets/js/lodash.compat.min.js"></script>
	<script type="text/javascript" src="<?php echo force_reload('profile.js'); ?>"></script>
	<!-- Load dependency assets -->
	<!-- TODO: include older version of handlebars-->
	<link rel="stylesheet" type="text/css" href="assets/css/jquery.ui.rotatable.css">
	<script type="text/javascript" src="assets/js/handlebars-v2.0.0.js"></script>
	<script type="text/javascript" src="assets/js/jquery.history.js"></script>
	<script type="text/javascript" src="card-contacts/shared.js"></script>
	<script type="text/javascript" src="assets/js/bc-image-upload.js"></script>
	<link rel="stylesheet" type="text/css" href="assets/imgmaps/css/imgareaselect-default.css" />
	<script type="text/javascript" src="assets/imgmaps/js/jquery.imgareaselect.pack.js"></script>
	<script type="text/javascript" src="assets/imgmaps/js/create.links.js"></script>
	<script type="text/javascript" src="assets/imgmaps/js/jquery.validate.js"></script>
	<script type="text/javascript" src="assets/js/jquery.ui.touch-punch.min.js"></script>
	<script type="text/javascript" src="assets/mg_js/jquery.ui.rotatable.js"></script>
	<script type="text/javascript" src="assets/js/multidraggable.js"></script>
	<script type="text/javascript" src="<?php echo force_reload('create-card-intexted.js'); ?>"></script>
	<script type="text/javascript" src="assets/js/star-rating.js"></script>
									<?php
									if (isset($_SESSION['preview_active']) && $_SESSION['preview_active'] == true) {
//			echo "<script type='text/javascript'>openPrviewSave();</script>";
									}
									?>
</body>
</html>
