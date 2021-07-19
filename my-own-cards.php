<?php
require_once('session_setup.php');
if (!isset($_SESSION['user_id'])) {
	header("Location: index.php");
	exit;
}
include_once('includes/head.php');
require_once('includes/database_config.php');
include_once('utilities/request_result.php');
include_once('utilities/mail.class.php');
require_once 'includes/profile/get_profile_details.php';
if (isset($_SESSION['card'])) {
	include ('includes/create-business-card/cancel_card_creation.php');
	unset($_SESSION['card']);
}  else {
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

					<input type="checkbox" name="share_among_users" id="share_among_users" class="css-checkbox" />
					<label for="share_among_users" class="css-label">This card can be shared among Cardition members</label>

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

	<div class="overlay-info" id="card-sharing-panel">
		<a href="#" data-popup-id="card-sharing-panel" class="close-btn closePopup"></a>
		<div class="card-sharing inline-button">
			<h3>Share card <strong id='new-card-name'></strong></h3>
			<div class="preview-save inline-button">
				<div id="img-flipper" class="">
					<div>
						<div id='new-card-data' data-card-id="" data-card-owner="">
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
				<!--<h3>Card name: <strong id='new-card-name'></strong></h3>-->
				<h3>BCN: <strong id='new-card-bcn'></strong></h3>
			</div>
			<input type="hidden" id="owner" name="owner" value="" />
			<button class="get-started-btn save-settings share-gmail" onclick="getGmailContacts()">
				<span style="margin-left: 20px;">Google contacts</span></button>
			<button class="get-started-btn save-settings share-outlook" onclick="getOutlookContacts()">
				<span style="margin-left: 20px;">Outlook contacts</span></button>
			<button class="get-started-btn save-settings share-mine" onclick="setContacts()">
				<span style="margin-left: 20px;">Let me type in</span>
			</button>
			<div id="sharing-mail-contacts">
				<div id="contacts-header">
					<img class="img-responsive" src="assets/sat_images/user-logo.png" /><img class="img-responsive" src="assets/sat_images/cardition.png" /><span id="list-owner"></span>
				</div>
				<p id="contacts-tip">Please, select the contacts you want share the card with and press 'Share this card' button to send them your card image.</p>
				<p id="alphabet-nav">
					<a class="alpha" href="#a">A</a>&nbsp;
					<a class="alpha" href="#b">B</a>&nbsp;
					<a class="alpha" href="#c">C</a>&nbsp;
					<a class="alpha" href="#d">D</a>&nbsp;
					<a class="alpha" href="#e">E</a>&nbsp;
					<a class="alpha" href="#f">F</a>&nbsp;
					<a class="alpha" href="#g">G</a>&nbsp;
					<a class="alpha" href="#h">H</a>&nbsp;
					<a class="alpha" href="#i">I</a>&nbsp;
					<a class="alpha" href="#j">J</a>&nbsp;
					<a class="alpha" href="#l">L</a>&nbsp;
					<a class="alpha" href="#m">M</a>&nbsp;
					<a class="alpha" href="#n">N</a>&nbsp;
					<a class="alpha" href="#o">O</a>&nbsp;
					<a class="alpha" href="#p">P</a>&nbsp;
					<a class="alpha" href="#q">Q</a>&nbsp;
					<a class="alpha" href="#r">R</a>&nbsp;
					<a class="alpha" href="#s">S</a>&nbsp;
					<a class="alpha" href="#y">T</a>&nbsp;
					<a class="alpha" href="#u">U</a>&nbsp;
					<a class="alpha" href="#v">V</a>&nbsp;
					<a class="alpha" href="#w">W</a>&nbsp;
					<a class="alpha" href="#x">X</a>&nbsp;
					<a class="alpha" href="#y">Y</a>&nbsp;
					<a class="alpha" href="#z">Z</a>
				</p>
				<div id='email-wrapper' style='display: none; margin-bottom: 20px;'>
					<label for="new_email_address" style="text-align: left;">Type an email address and press 'Enter' to add it to your list.</label>
					<input id="new_email_address" name="new_email_address" class="form-control" type="email" value=""  />
				</div>
				<div id="contacts-wrapper">
					<!--<input id="list-scroller" type="text" />-->
					<ul id="mail-contacts">

					</ul>

				</div>
				<button id="share-card-with" class="get-started-btn save-settings" onclick="shareCard()">Share this card</button>
				<button id="cancel" class="get-started-btn save-settings" onclick="closeContacts()">Cancel</button>
			</div>
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
<script type="text/javascript" src="<?php echo force_reload('profile.js'); ?>"></script>
<!-- Load dependency assets -->
<!-- TODO: include older version of handlebars-->
<script type="text/javascript" src="assets/js/handlebars-v2.0.0.js"></script>
<script type="text/javascript" src="assets/js/jquery.history.js"></script>
<script type="text/javascript" src="card-contacts/shared.js"></script>
<script type="text/javascript" src="assets/js/star-rating.js"></script>
<script src="https://apis.google.com/js/client.js"></script>
<script src="//js.live.net/v5.0/wl.js"></script>
<script type="text/javascript" src="<?php echo force_reload('assets/mg_js/mg_contacts.js'); ?>"></script>
<script type="text/javascript">
					jQuery(document).ready(function () {
<?php
if (isset($_SESSION['created_card_id'])) {
	echo "editCardSettings('{$_SESSION['created_card_id']}', '{$_SESSION['created_card_type']}');";
	unset($_SESSION['created_card_id']);
	unset($_SESSION['created_card_type']);
}
?>
						loadCards();
					});
</script>
</body>
</html>
