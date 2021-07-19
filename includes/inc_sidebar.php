<!-- sidebar-sec start-->

<div class="inner-sidebar" id="profile">
	<div class="inner-sidebar-info">
		<a href="#" class="close-btn closeInnerSideBar"></a>
		<ul class="inner-sidebar-links">
			<li class="personal active"><a href="personal-info.php">Personal info</a></li>
			<li class="company"><a href="company-info.php">Company info</a></li>
		</ul>
	</div>    
</div>
<div class="inner-sidebar" id="manageCard">
	<div class="inner-sidebar-info">
		<a href="#" class="close-btn closeInnerSideBar"></a>
		<ul class="inner-sidebar-links">
			<li><a href="my-own-cards.php">My Own Cards</a></li>
			<li><a href="my-cards-holder.php">My Card(s) Holders</a></li>
			<li><a href="card-request.php">Card Requests</a></li>
		</ul>
	</div>    
</div>
<div class="inner-sidebar" id="folders">
	<div class="spinner-overlay spinner-sidebar">
		<!--<img src="assets/images/spinner_bc.gif" />-->
		<div class="cssload-loader">
			<div class="cssload-side"></div>
			<div class="cssload-side"></div>
			<div class="cssload-side"></div>
			<div class="cssload-side"></div>
			<div class="cssload-side"></div>
			<div class="cssload-side"></div>
			<div class="cssload-side"></div>
			<div class="cssload-side"></div>
		</div>
	</div>
	<div class="inner-sidebar-info">
		<a href="#" class="close-btn closeInnerSideBar"></a>
		<ul class="inner-sidebar-links">
		</ul>
	</div>
</div>
<div class="user-sidebar" id="minSideBar">
	<div class="sidebar-head">
		<a href="index.php" class="user-logo"><img src="assets/sat_images/user-logo.png" alt=""></a>
		<a href="#" class="sidebar-toggle " id="sideBarToggle">
			<span class="hidden-phone">
				<img src="assets/sat_images/nav-btn-white.png" alt="" class="normal-state">
				<img src="assets/sat_images/nav-btn-white2.png" alt="" class="active-state">
			</span>
			<span class="visible-phone">
				<img src="assets/sat_images/nav-btn-white.png" alt="" class="active-state">
				<img src="assets/sat_images/nav-btn-white2.png" alt="" class="normal-state">
			</span>
		</a>
	</div>
	<div class="sidebar-info">
		<ul class="profile-links">
			<li class="user-profile">
				<a href="#profile" class="profile_small link-icon innerSideBarLink"><img class="img-circle" src="<?php echo $profile_image; ?>" alt=""></a>
				<div class="link-title"><span class="avatar-name"><?php echo $first_name . " " . $last_name; ?> </span><strong><a href="#profile" class="innerSideBarLink">My Profile</a></strong></div>
			</li>
			<li>
				<a href="#manageCard" class="link-icon innerSideBarLink"><img src="assets/sat_images/manage-cards.png" alt=""></a>
				<div class="link-title"><a href="#manageCard" class="innerSideBarLink">Manage My Cards </a></div>
			</li>
			<li>
				<a href="#folders" class="link-icon innerSideBarLink"><img src="assets/sat_images/create-folder.png" alt=""></a>
				<div class="link-title"><a href="#folders" class="innerSideBarLink">folders</a></div>
			</li>
		</ul>
		<a href="create-card-0.php" class="business-card disabled btn-new-card">Create <i>Digital Card</i></a>
		<p>{Please fill in your Personal Info to create your card}</p>
		<ul class="sidebar-nav">
			<li><a href="setting.php"><img src="assets/sat_images/setting.png" alt=""><strong>Settings</strong></a></li>
			<li><a href="notifications.php"><span class="notification-link"><img src="assets/sat_images/notification.png" alt=""><i></i></span><strong>Notifications</strong></a></li>
			<li><a href="includes/auth/logout.php"><img src="assets/sat_images/logout.png" alt=""><strong>Logout</strong></a></li>
		</ul>
	</div>
</div>

<!-- sidebar-sec end -->
