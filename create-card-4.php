<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );
require_once('session_setup.php');
require_once('includes/absolute_database_config.php');
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	header( "Location: index.php" );
	exit;
}
if ( isset( $_SESSION[ 'card' ][ 'default' ] ) )
{
	include ('includes/create-business-card/cancel_card_creation.php');
	unset( $_SESSION[ 'card' ] );
}
if ( !isset( $_SESSION[ 'card' ][ 'card_id' ] ) )
{
	header( "Location: create-card-0.php" );
}
include_once('includes/head.php');
require_once('includes/database_config.php');
include_once('utilities/request_result.php');
include_once('utilities/mail.class.php');
require_once 'includes/profile/get_profile_details.php';
require_once 'includes/create-business-card/get_business_card_details.php';
?>
<body data-creation-step="4">
	<link rel="stylesheet" type="text/css" href="assets/imgmaps/css/imgareaselect-default.css" />
	<script type="text/javascript" src="assets/imgmaps/js/jquery.imgareaselect.pack.js"></script>
	<script type="text/javascript" src="assets/imgmaps/js/create.links.js"></script>
	<script type="text/javascript" src="assets/imgmaps/js/jquery.validate.js"></script>
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
				if ( isset( $_SESSION[ 'card' ][ 'edit_mode' ] ) )
				{
					$url = 'my-own-cards.php?process_completed=true&buying=true';
				}
				else
				{
					$url = 'my-own-cards.php?buying=true';
				}
				?>
				<button data-url="<?php echo $url; ?>" id="btnBuyPrem" class="get-started-btn save-settings">Buy BCN</button>
			</div>
		</div>

		<!--PREVIEW-SAVE-->
		<div class="overlay-info" id="preViewSave">
			<a href="#" class="close-btn saving-card closePopup"></a>
			<div class="preview-save inline-button">
				<?php
				if ( isset( $orientation ) )
				{
					if ( $orientation === 'landscape' )
					{
						$class = "img-flip aboutCardposition";
					}
					else
					{
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
				if ( isset( $_SESSION[ 'card' ][ 'edit_mode' ] ) )
				{
					$url = 'my-own-cards.php?process_completed=true';
				}
				else
				{
					$url = 'my-own-cards.php';
				}
				?>
				<a href="#" data-url="<?php echo $url; ?>" id="save-bc" class="get-started-btn">Save</a>
				<a href="#" data-url="my-own-cards.php" id="cancel-bc" class="get-started-btn">Cancel</a>
				<a href="#" data-card-id="<?php echo $_SESSION[ 'card' ][ 'card_id' ] ?>" id="buy-bcn" class="sign-up-btn">Save and Buy Premium BCN (£ 10.00)</a>
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
					</div>
        </div>
        <ul class="top-bar">
					<li>
						<a href="#" class="disabled">
							<img src="assets/sat_images/import-card-img.png" alt="">
							<strong>Import <i>Card Image</i></strong>
						</a>
					</li>
					<li>
						<a href="#" class="disabled">
							<img src="assets/sat_images/background.png" alt="">
							<strong>Background</strong>
						</a>
					</li>
					<li>
						<a href="#" class="disabled">
							<img src="assets/sat_images/import-details.png" alt="">
							<strong>Import <i>Details</i></strong>
						</a>
					</li>
					<li>
						<a href="#" class="disabled">
							<input type="text" placeholder="abc" disabled="disabled" />
							<strong>Add <i>Text</i></strong>
						</a>
					</li>
					<li>
						<a href="#" id="add-logo" class="disabled">
							<img src="assets/sat_images/add-logo.png" alt="">
							<strong>Add <i>Logo</i></strong>
						</a>
					</li>
					<li>
						<a href="#" id="add-link">
							<img src="assets/sat_images/add-links.png" alt="">
							<strong>Add <i>Link</i></strong>
						</a>
					</li>
					<li>
					<?php
						if ( isset( $_SESSION[ 'card' ][ 'edit_mode' ] ) )
						{
							$url = 'my-own-cards.php?process_completed=true';
						}
						else
						{
							$url = 'create-card-0.php';
						}
						?>
						<a href="#" data-url="<?php echo $url; ?>" id="discard" class="get-started-btn">Discard</a>
						<a id="save-links" href="#" class="sign-up-btn addCardWithBCN">Preview & Save</a>
					</li>
        </ul>
				<div class="card-area">
					<ul class="card-postion" id="cardPosTion">
						<li><a href="#" class="front-side frontSideLink img-postion-link">front side</a></li>
						<li><a href="#" class="back-side backSideLink img-postion-link">Back side</a></li>
						<ul>
							<?php
							if ( isset( $orientation ) )
							{
								if ( $orientation === 'landscape' )
								{
									$class1 = "card-rotation cardRotaTion active";
									$class2 = "card-rotation cardRotaTion";
								}
								else
								{
									$class1 = "card-rotation cardRotaTion";
									$class2 = "card-rotation cardRotaTion active";
								}
							}
							?>
						</ul>
					</ul>
					<div class="row" style="max-width: 100%;">
						<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
							<?php
							if ( isset( $orientation ) )
							{
								if ( $orientation === 'landscape' )
								{
									$class = "img-flip aboutCardposition";
								}
								else
								{
									$class = "img-flip aboutCardposition portrait";
								}
							}
							?>
							<div class="<?php echo $class; ?>">
								<div>
									<div>
										<div id="link-canvas" style="position:relative;">
											<div>
												<img id="photo" src="<?php echo $canvas_front . "?" . time(); ?>" />
											</div>
											<div id="links-f">
												<?php
												if ( isset($links_front) && $links_front != "" && $links_front != NULL )
												{
													echo $links_front;
												}
												?>
											</div>
										</div>
										<div id="link-canvas-back" class="back-side-content">
											<div>
												<img id="photo2" src="<?php echo $canvas_back . "?" . time(); ?>" />
											</div>
											<div id="links-b">
												<?php
												if ( $links_back != "" && $links_back != NULL )
												{
													echo $links_back;
												}
												?>
											</div>
										</div>
										<div id="coordinates">
											<input type="text" id="x1" value="-" style="display: none;" />
											<input type="text" id="w"  value="-" style="display: none;" />
											<input type="text" id="y1" value="-" style="display: none;" />
											<input type="text" id="h"  value="-" style="display: none;" />
											<input type="text" id="x2" value="-" style="display: none;" />
											<input type="text" id="y2" value="-" style="display: none;" />
											<input type="text" id="areaSelected" value="" style="display: none;" />
										</div>

									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">

							<div class="upload-panel" id="link-panel">
								<div class="upload-panel-title">
									<h4>Link options</h4>
									<span style="cursor: pointer; color: /*#fd6f02*/#fff;" class="glyphicon glyphicon-remove close-panel"></span>
								</div>
								<div class="upload-panel-body">
									<h4>How to add live link to your business card</h4>
									<ul>
										<li><span>Drag your mouse over your business card and select an area</span></li>
										<li><span>Then select from the dropdown below the type of link you want to create</span></li>
										<li><span>Type in the text box data following the pattern</span>
										<li><span>Finally click Add button</span></li>
									</ul>
									<label>
										<select onchange="ModalDisplayChoice(this.selectedIndex)" class="form-control" id="ddLinkType">
											<option>Web URL</option>
											<option>Email</option>
											<option>Phone Number</option>
										</select>
									</label>
									<form id="frmLinkType" style="padding: 10px;">
										<div class="form-group">
											<div class="modal-body">
												<div class="input-group" id="webUrl">
													<span class="input-group-addon"><span class="glyphicon glyphicon-globe"></span></span>
													<input type="text" id="url" name="url" class="form-control" placeholder="http://www.google.co.za" value="">
												</div>
												<div class="input-group" id="emailAddress" style="display: none;">
													<span class="input-group-addon">@</span>
													<input type="text" id="email" name="email" class="form-control" placeholder="someone@gmail.co.za" value="">
												</div>
												<div class="input-group" id="phoneNumber" style="display: none;">
													<span class="input-group-addon"><span class="glyphicon glyphicon-earphone"></span></span>
													<input type="text" id="phone" name="phone" class="form-control" placeholder="+442084387627" value="">
												</div>
												<a href="#" class="get-started-btn" id="add-link-button" onclick="frmLinkTypeValid();">Add</a>
												<a href="#" class="get-started-btn" id="reset" onclick="RefreshImage();">Reset</a>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
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
	<script type="text/javascript" src="<?php echo force_reload( 'profile.js' ); ?>"></script>
	<!-- Load dependency assets -->
	<!-- TODO: include older version of handlebars-->
	<script type="text/javascript" src="assets/js/handlebars-v2.0.0.js"></script>
	<script type="text/javascript" src="assets/js/jquery.history.js"></script>
	<script type="text/javascript" src="card-contacts/shared.js"></script>
	<script type="text/javascript" src="assets/js/bc-image-upload.js"></script>
	<link rel="stylesheet" type="text/css" href="assets/imgmaps/css/imgareaselect-default.css" />
	<script type="text/javascript" src="assets/imgmaps/js/jquery.imgareaselect.pack.js"></script>
	<script type="text/javascript" src="assets/imgmaps/js/create.links.js"></script>
	<script type="text/javascript" src="assets/imgmaps/js/jquery.validate.js"></script>
	<script type="text/javascript" src="assets/mg_js/jquery.touchSwipe.js"></script>
	<script type="text/javascript" src="assets/mg_js/jquery.ui.touch-punch.js"></script>
	<!--<script type="text/javascript" src="assets/js/jquery.touchy.js"></script>-->
	<script type="text/javascript" src="assets/mg_js/jquery.ui.rotatable.js"></script>
	<script type="text/javascript" src="assets/js/multidraggable.js"></script>
	<script type="text/javascript" src="<?php echo force_reload( 'create-card-intexted.js' ); ?>"></script>
	<script type="text/javascript" src="assets/js/star-rating.js"></script>
	<script>
//		jQuery('#link-canvas').touchyswipe({fingers: 2});
//		jQuery('#link-canvas-back').swipe({fingers: 2});
	</script>
</body>
</html>
