<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script type="text/javascript">
    var globalURL = "http://frontend.cardition.com/";
    timeOut = 1000;
</script>

<title>Cardition</title>


<!--<link rel="stylesheet" type="text/css" href="assets/sat_css/bootstrap.min.css" />-->
<link rel="stylesheet" type="text/css" href="assets/remote/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="assets/sat_css/jquery.mCustomScrollbar.css" />
<link rel="stylesheet" type="text/css" href="assets/sat_css/style.css?version=20160201GMT170742.7123180" />
<link rel="stylesheet" type="text/css" href="assets/mimiz-jquery-loader/jquery.loader.css">
<link rel="stylesheet" type="text/css" href="assets/colpick/css/colpick.css">
<link rel="stylesheet" type="text/css" href="assets/remote/codemirror.min.css" />
<link rel="stylesheet" type="text/css" href="assets/remote/monokai.min.css" />
<link rel="stylesheet" type="text/css" href="assets/context/src/jquery.contextMenu.css" />
<link rel="stylesheet" type="text/css" href="assets/summernote/summernote.css" />
<link rel="stylesheet" type="text/css" href="assets/css/sweetalert.css" rel="stylesheet" type="text/css">
<!--<link rel="stylesheet" type="text/css" href="" />-->
<!--<link rel="stylesheet" type="text/css" href="assets/css/style.css" />-->

<script type="text/javascript" src="assets/sat_js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="assets/sat_js/jquery-ui-1.8.23.custom.min.js"></script>
<script type="text/javascript" src="assets/sat_js/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/sat_js/modernizr.min.js"></script>
<script type="text/javascript" src="assets/sat_js/rater.min.js"></script>
<script type="text/javascript" src="assets/sat_js/jquery.selectBoxIt.min.js"></script>
<script type="text/javascript" src="assets/sat_js/jquery.mCustomScrollbar.js"></script>
<script type="text/javascript" src="assets/sat_js/script.js?version=20160201GMT170742.7124390"></script>
<!--<script type="text/javascript" src=""></script>-->
<!--<script type="text/javascript" src="assets/mg_js/mg_access.js"></script>-->
<script src="assets/compatibility/excanvas.js"></script>
<!--Image size limiter-->
<script type="text/javascript" src="assets/js/shared-upload.js"></script>
<!--<script type="text/javascript" src="assets/jquery-11/jquery-ui.min.js"></script>-->
<script type="text/javascript" src="assets/imgmaps/js/jquery.validate.js"></script>
<script type="text/javascript" src="assets/mimiz-jquery-loader/jquery.loader.js"></script>
<script type="text/javascript" src="assets/colpick/js/colpick.js"></script>
<script type="text/javascript" src="assets/remote/codemirror.min.js"></script>
<script type="text/javascript" src="assets/remote/xml.min.js"></script>
<script type="text/javascript" src="assets/remote/formatting.min.js"></script>
<!-- CONTEXT MENUS -->
<script type="text/javascript" src="assets/context/src/jquery.contextMenu.js"></script>
<script type="text/javascript" src="assets/context/src/jquery.ui.position.js"></script>
<!-- CARD FLIP -->
<script type="text/javascript" src="assets/flip/jquery.quickflip.source.js"></script>
<!-- WYSIWYG -->
<script type="text/javascript" src="assets/summernote/summernote.js"></script>
<!-- HTML2CANVAS -->
<script type="text/javascript" src="assets/remote/html2canvas.js"></script>
<!-- <script type="text/javascript" src="assets/canvas/html5.js"></script> -->
<script type="text/javascript" src="assets/canvas/createjs.js"></script>
<script type="text/javascript" src="assets/canvas/canvas2Image.js"></script>
<!-- assets/ladda -->
<script type="text/javascript" src="assets/ladda/dist/spin.js"></script>
<script type="text/javascript" src="assets/ladda/dist/ladda.js"></script>
<script type="text/javascript" src="assets/js/header.js"></script>
<script type="text/javascript" src="assets/js/sweetalert.min.js"></script>
<!--<link rel="stylesheet" href="" type="text/css" />-->

<script>
	( function ( i, s, o, g, r, a, m ) {
		i['GoogleAnalyticsObject'] = r;
		i[r] = i[r] || function () {
			( i[r].q = i[r].q || [ ] ).push( arguments )
		}, i[r].l = 1 * new Date();
		a = s.createElement( o ),
						m = s.getElementsByTagName( o )[0];
		a.async = 1;
		a.src = g;
		m.parentNode.insertBefore( a, m )
	} )( window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga' );
	ga( 'create', 'UA-57697833-1', 'auto' );
	ga( 'send', 'pageview' );
	var trackOutboundLink = function ( url ) {
		ga( 'send', 'event', 'outbound', 'click', url, { 'hitCallback':
							function () {
								document.location = url;
							}
		} );
	}
</script>
</head>
<body><script>
	var user_id = "55a68e17697a68.73286341";
</script>


<!-- wrapper-start -->
<div class="wrapper">

	<div class="overlay" id="overLay"></div>

	<div class="overlay-info" id="createFolder">
			<a href="#" class="close-btn closePopup"></a>
			<div class="create-folder inline-button">
					<h3>Create New Folder</h3>
					<input type="email" class="form-control" id="new_folder_name" placeholder="Please enter a new folder name here">
					<button id="create_new_folder" class="sign-up-btn">Create</button>
					<button id="cancel_new_folder_creation" class="get-started-btn">Cancel</button>
			</div>
	</div>
		<div class="overlay-info" id="userCardDetail">
		<!-- end user-card-details-->
	</div>
	<!--end userCardDetail-->
	
	<div class="overlay-info" id="avatarSlideSec">
    <a href="#" class="close-btn closePopup"></a>
    <div class="avatar-slide">
			<h3 id='card-owner-name-profile'></h3>
			<img id='card-owner-profile-image' class="card-detail-profile-image" src="" alt="">
			<p id='card-owner-full-name'></p>
			<p id='card-owner-role'>Principal Director</p>
			<p id='card-owner-email-address'></p>
			<p id='card-owner-phones' class="tell-link"></p>
			<p id='card-owner-website-link'></p>
			<span id='card-owner-card-status'></span>
			<!--<p class="card-added"><img src="assets/sat_images/card-added.png" alt="">Card Already Added</p>-->
    </div>
	</div>

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
			<div class="inner-sidebar-info">
				<a href="#" class="close-btn closeInnerSideBar"></a>
				<ul class="inner-sidebar-links">
					<li><a href="all-folders.php">all folders</a></li>
					<li><a href="personal-corporate.php">personal / corporate</a></li>
					<li><a href="service-product.php">service / product</a></li>
					<li><a href="new-folder.php">new folder_abc</a></li>
				</ul>
			</div>
    </div>
    <div class="user-sidebar" id="minSideBar">
			<div class="sidebar-head">
				<a href="index.php" class="user-logo"><img src="assets/sat_images/user-logo-new.png" alt=""></a>
				<a href="#" class="sidebar-toggle " id="sideBarToggle"><span class="hidden-phone"><img src="assets/sat_images/nav-btn.png" alt="" class="normal-state"><img src="assets/sat_images/nav-btn2.png" alt="" class="active-state"></span><span class="visible-phone"><img src="assets/sat_images/nav-btn.png" alt="" class="active-state"><img src="assets/sat_images/nav-btn2.png" alt="" class="normal-state"></span></a>
			</div>
			<div class="sidebar-info">
				<ul class="profile-links">
					<li class="user-profile">
						<a href="#profile" id="profile_small" class="link-icon innerSideBarLink"><img class="img-circle" src="includes/profile/uploads/55a68e17697a68.73286341-profile.jpg?20160201GMT170742.7271290" alt=""></a>
						<div class="link-title"><span class="avatar-name">Marco Gasi </span><strong><a href="#profile" class="innerSideBarLink">My Profile</a></strong></div>
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
				<a href="create-card-7.php" class="business-card disabled">Create <i>Business Card</i></a>
				<p>{Please fill in your Personal Info to create your card}</p>
				<ul class="sidebar-nav">
					<li><a href="setting.php"><img src="assets/sat_images/setting.png" alt=""><strong>Settings</strong></a></li>
					<li><a href="notification-activity.php"><span class="notification-link"><img src="assets/sat_images/notification.png" alt=""><i></i></span><strong>Notifications</strong></a></li>
					<li><a href="includes/auth/logout.php"><img src="assets/sat_images/logout.png" alt=""><strong>Logout</strong></a></li>
				</ul>
			</div>
    </div>

    <!-- sidebar-sec end -->
    <!-- sidebar-sec end -->

    <div id="main">
			<div class="folder-pages">
				<div class="top-sec">
					<h3>My Profile</h3>
				</div>
			</div>
			<div class="profile-sec">
				<div class="profile-form">
					<div class="about-profile">
						<div id="profile_big" class="profile-logo"><img class="img-circle" src="includes/profile/uploads/55a68e17697a68.73286341-profile.jpg?20160201GMT170742.7271290" alt=""></div>
						<form id="profile-pic-form" class="form-inline" enctype="multipart/form-data" action="includes/profile/file_upload.php" method="post">
							<div class="fake-picker">
								<input type="file" name="file" id="inputFile" placeholder="Upload Image">
								<!--<span class='btn btn-default file-picker-button'>Select File</span>-->
								<a href="#" class="get-started-btn file-picker-button">Change picture</a>
								<button onclick="fileUpload(this.form, 'includes/profile/file_upload.php', 'upload'); return false;" id="submit-upload" class='get-started-btn disabled'>Upload</button>
							</div>
							<div id="upload"></div>
						</form>
					</div>
					<form id="frmPersonalInfo">
						<div class="salution-select">
							<div class="form-group">
								<label for="">Salutation</label>
								<div class="custom-dropdown">
									<select name="ddTitle" id="ddTitle" class="custom-select form-control">
										<option selected >Mr</option>
										<option  >Mrs</option>
										<option  >Miss</option>
										<option  >Doctor</option>
										<option  >Professor</option>
									</select>
								</div>
							</div>
						</div>    
						<div class="inline-form">
							<div class="form-group no-margin">
								<label for="first_name">First Name</label>
								<input class="form-control" name="first_name" id="first_name" placeholder="First name" type="text" value="Marco">
							</div>
							<div class="form-group">
								<label for="last_name">Last Name</label>
								<input class="form-control" id="last_name" name="last_name" placeholder="Last name" type="text" value="Gasi">
							</div>
							<div class="form-group no-margin">
								<label for="land_line">Landline</label>
								<input class="form-control" id="phone" name="phone" placeholder="Landline" type="tel" value="0034922073394">
							</div>
							<div class="form-group">
								<label for="mobile">Mobile</label>
								<input class="form-control" id="mobile" name="mobile" placeholder="Mobile" type="tel" value="0034617535843">
							</div>
						</div>
						<div class="form-group">
							<label for="email">E-mail</label>
							<input class="form-control" id="email_address" name="email_address" placeholder="Email address" type="email" value="marqus.gs@gmail.com">
						</div>
						<div class="form-group">
							<label for="site_address">Website (if any)</label>
							<input class="form-control" id="website_link" name="website_link" placeholder="Website (if any)" type="text" value="www.webintenerife.com">
						</div>
						<div class="form-group">
							<label for="address_1">Address 1</label>
							<input type='text' name="personal_address_1" id="personal_address_1" class="form-control" placeholder="Personal address" value="Calle V. B. Mazzini, 2" />
						</div>
						<div class="form-group">
							<label for="address_2">Address 2</label>
							<input type='text' name="personal_address_2" id="personal_address_2" class="form-control" placeholder="-" />
						</div>
						<div class="inline-form">
							<div class="form-group no-margin">
								<label for="city_name">City</label>
								<input class="form-control" name="personal_city" id="personal_city" placeholder="City" type="text" value="El Monte o Guargacho">
							</div>
							<div class="form-group">
								<label for="">Country</label>
								<div class="custom-dropdown">
									<select id="personal_country" name="personal_country" name="test" class="custom-select form-control">
										<option value=""  >Country</option>
																					<option value="1"  >Afghanistan</option>
																					<option value="2"  >Albania</option>
																					<option value="3"  >Algeria</option>
																					<option value="4"  >Andorra</option>
																					<option value="5"  >Angola</option>
																					<option value="6"  >Antigua and Barbuda</option>
																					<option value="7"  >Argentina</option>
																					<option value="8"  >Armenia</option>
																					<option value="9"  >Australia</option>
																					<option value="10"  >Austria</option>
																					<option value="11"  >Azerbaijan</option>
																					<option value="12"  >Bahamas, The</option>
																					<option value="13"  >Bahrain</option>
																					<option value="14"  >Bangladesh</option>
																					<option value="15"  >Barbados</option>
																					<option value="16"  >Belarus</option>
																					<option value="17"  >Belgium</option>
																					<option value="18"  >Belize</option>
																					<option value="19"  >Benin</option>
																					<option value="20"  >Bhutan</option>
																					<option value="21"  >Bolivia</option>
																					<option value="22"  >Bosnia and Herzegovina</option>
																					<option value="23"  >Botswana</option>
																					<option value="24"  >Brazil</option>
																					<option value="25"  >Brunei</option>
																					<option value="26"  >Bulgaria</option>
																					<option value="27"  >Burkina Faso</option>
																					<option value="28"  >Burundi</option>
																					<option value="29"  >Cambodia</option>
																					<option value="30"  >Cameroon</option>
																					<option value="31"  >Canada</option>
																					<option value="32"  >Cape Verde</option>
																					<option value="33"  >Central African Republic</option>
																					<option value="34"  >Chad</option>
																					<option value="35"  >Chile</option>
																					<option value="36"  >China, People's Republic of</option>
																					<option value="37"  >Colombia</option>
																					<option value="38"  >Comoros</option>
																					<option value="39"  >Congo, (Congo ? Kinshasa)</option>
																					<option value="40"  >Congo, (Congo ? Brazzaville)</option>
																					<option value="41"  >Costa Rica</option>
																					<option value="42"  >Cote d'Ivoire (Ivory Coast)</option>
																					<option value="43"  >Croatia</option>
																					<option value="44"  >Cuba</option>
																					<option value="45"  >Cyprus</option>
																					<option value="46"  >Czech Republic</option>
																					<option value="47"  >Denmark</option>
																					<option value="48"  >Djibouti</option>
																					<option value="49"  >Dominica</option>
																					<option value="50"  >Dominican Republic</option>
																					<option value="51"  >Ecuador</option>
																					<option value="52"  >Egypt</option>
																					<option value="53"  >El Salvador</option>
																					<option value="54"  >Equatorial Guinea</option>
																					<option value="55"  >Eritrea</option>
																					<option value="56"  >Estonia</option>
																					<option value="57"  >Ethiopia</option>
																					<option value="58"  >Fiji</option>
																					<option value="59"  >Finland</option>
																					<option value="60"  >France</option>
																					<option value="61"  >Gabon</option>
																					<option value="62"  >Gambia, The</option>
																					<option value="63"  >Georgia</option>
																					<option value="64"  >Germany</option>
																					<option value="65"  >Ghana</option>
																					<option value="66"  >Greece</option>
																					<option value="67"  >Grenada</option>
																					<option value="68"  >Guatemala</option>
																					<option value="69"  >Guinea</option>
																					<option value="70"  >Guinea-Bissau</option>
																					<option value="71"  >Guyana</option>
																					<option value="72"  >Haiti</option>
																					<option value="73"  >Honduras</option>
																					<option value="74"  >Hungary</option>
																					<option value="75"  >Iceland</option>
																					<option value="76"  >India</option>
																					<option value="77"  >Indonesia</option>
																					<option value="78"  >Iran</option>
																					<option value="79"  >Iraq</option>
																					<option value="80"  >Ireland</option>
																					<option value="81"  >Israel</option>
																					<option value="82"  >Italy</option>
																					<option value="83"  >Jamaica</option>
																					<option value="84"  >Japan</option>
																					<option value="85"  >Jordan</option>
																					<option value="86"  >Kazakhstan</option>
																					<option value="87"  >Kenya</option>
																					<option value="88"  >Kiribati</option>
																					<option value="89"  >Korea, North</option>
																					<option value="90"  >Korea, South</option>
																					<option value="91"  >Kuwait</option>
																					<option value="92"  >Kyrgyzstan</option>
																					<option value="93"  >Laos</option>
																					<option value="94"  >Latvia</option>
																					<option value="95"  >Lebanon</option>
																					<option value="96"  >Lesotho</option>
																					<option value="97"  >Liberia</option>
																					<option value="98"  >Libya</option>
																					<option value="99"  >Liechtenstein</option>
																					<option value="100"  >Lithuania</option>
																					<option value="101"  >Luxembourg</option>
																					<option value="102"  >Macedonia</option>
																					<option value="103"  >Madagascar</option>
																					<option value="104"  >Malawi</option>
																					<option value="105"  >Malaysia</option>
																					<option value="106"  >Maldives</option>
																					<option value="107"  >Mali</option>
																					<option value="108"  >Malta</option>
																					<option value="109"  >Marshall Islands</option>
																					<option value="110"  >Mauritania</option>
																					<option value="111"  >Mauritius</option>
																					<option value="112"  >Mexico</option>
																					<option value="113"  >Micronesia</option>
																					<option value="114"  >Moldova</option>
																					<option value="115"  >Monaco</option>
																					<option value="116"  >Mongolia</option>
																					<option value="117"  >Montenegro</option>
																					<option value="118"  >Morocco</option>
																					<option value="119"  >Mozambique</option>
																					<option value="120"  >Myanmar (Burma)</option>
																					<option value="121"  >Namibia</option>
																					<option value="122"  >Nauru</option>
																					<option value="123"  >Nepal</option>
																					<option value="124"  >Netherlands</option>
																					<option value="125"  >New Zealand</option>
																					<option value="126"  >Nicaragua</option>
																					<option value="127"  >Niger</option>
																					<option value="128"  >Nigeria</option>
																					<option value="129"  >Norway</option>
																					<option value="130"  >Oman</option>
																					<option value="131"  >Pakistan</option>
																					<option value="132"  >Palau</option>
																					<option value="133"  >Panama</option>
																					<option value="134"  >Papua New Guinea</option>
																					<option value="135"  >Paraguay</option>
																					<option value="136"  >Peru</option>
																					<option value="137"  >Philippines</option>
																					<option value="138"  >Poland</option>
																					<option value="139"  >Portugal</option>
																					<option value="140"  >Qatar</option>
																					<option value="141"  >Romania</option>
																					<option value="142"  >Russia</option>
																					<option value="143"  >Rwanda</option>
																					<option value="144"  >Saint Kitts and Nevis</option>
																					<option value="145"  >Saint Lucia</option>
																					<option value="146"  >Saint Vincent and the Grenadines</option>
																					<option value="147"  >Samoa</option>
																					<option value="148"  >San Marino</option>
																					<option value="149"  >Sao Tome and Principe</option>
																					<option value="150"  >Saudi Arabia</option>
																					<option value="151"  >Senegal</option>
																					<option value="152"  >Serbia</option>
																					<option value="153"  >Seychelles</option>
																					<option value="154"  >Sierra Leone</option>
																					<option value="155"  >Singapore</option>
																					<option value="156"  >Slovakia</option>
																					<option value="157"  >Slovenia</option>
																					<option value="158"  >Solomon Islands</option>
																					<option value="159"  >Somalia</option>
																					<option value="160"  >South Africa</option>
																					<option value="161" selected >Spain</option>
																					<option value="162"  >Sri Lanka</option>
																					<option value="163"  >Sudan</option>
																					<option value="164"  >Suriname</option>
																					<option value="165"  >Swaziland</option>
																					<option value="166"  >Sweden</option>
																					<option value="167"  >Switzerland</option>
																					<option value="168"  >Syria</option>
																					<option value="169"  >Tajikistan</option>
																					<option value="170"  >Tanzania</option>
																					<option value="171"  >Thailand</option>
																					<option value="172"  >Timor-Leste (East Timor)</option>
																					<option value="173"  >Togo</option>
																					<option value="174"  >Tonga</option>
																					<option value="175"  >Trinidad and Tobago</option>
																					<option value="176"  >Tunisia</option>
																					<option value="177"  >Turkey</option>
																					<option value="178"  >Turkmenistan</option>
																					<option value="179"  >Tuvalu</option>
																					<option value="180"  >Uganda</option>
																					<option value="181"  >Ukraine</option>
																					<option value="182"  >United Arab Emirates</option>
																					<option value="183"  >United Kingdom</option>
																					<option value="184"  >United States</option>
																					<option value="185"  >Uruguay</option>
																					<option value="186"  >Uzbekistan</option>
																					<option value="187"  >Vanuatu</option>
																					<option value="188"  >Vatican City</option>
																					<option value="189"  >Venezuela</option>
																					<option value="190"  >Vietnam</option>
																					<option value="191"  >Yemen</option>
																					<option value="192"  >Zambia</option>
																					<option value="193"  >Zimbabwe</option>
																					<option value="194"  >Abkhazia</option>
																					<option value="195"  >China, Republic of (Taiwan)</option>
																					<option value="196"  >Nagorno-Karabakh</option>
																					<option value="197"  >Northern Cyprus</option>
																					<option value="198"  >Pridnestrovie (Transnistria)</option>
																					<option value="199"  >Somaliland</option>
																					<option value="200"  >South Ossetia</option>
																					<option value="201"  >Ashmore and Cartier Islands</option>
																					<option value="202"  >Christmas Island</option>
																					<option value="203"  >Cocos (Keeling) Islands</option>
																					<option value="204"  >Coral Sea Islands</option>
																					<option value="205"  >Heard Island and McDonald Islands</option>
																					<option value="206"  >Norfolk Island</option>
																					<option value="207"  >New Caledonia</option>
																					<option value="208"  >French Polynesia</option>
																					<option value="209"  >Mayotte</option>
																					<option value="210"  >Saint Barthelemy</option>
																					<option value="211"  >Saint Martin</option>
																					<option value="212"  >Saint Pierre and Miquelon</option>
																					<option value="213"  >Wallis and Futuna</option>
																					<option value="214"  >French Southern and Antarctic Lands</option>
																					<option value="215"  >Clipperton Island</option>
																					<option value="216"  >Bouvet Island</option>
																					<option value="217"  >Cook Islands</option>
																					<option value="218"  >Niue</option>
																					<option value="219"  >Tokelau</option>
																					<option value="220"  >Guernsey</option>
																					<option value="221"  >Isle of Man</option>
																					<option value="222"  >Jersey</option>
																					<option value="223"  >Anguilla</option>
																					<option value="224"  >Bermuda</option>
																					<option value="225"  >British Indian Ocean Territory</option>
																					<option value="226"  >British Sovereign Base Areas</option>
																					<option value="227"  >British Virgin Islands</option>
																					<option value="228"  >Cayman Islands</option>
																					<option value="229"  >Falkland Islands (Islas Malvinas)</option>
																					<option value="230"  >Gibraltar</option>
																					<option value="231"  >Montserrat</option>
																					<option value="232"  >Pitcairn Islands</option>
																					<option value="233"  >Saint Helena</option>
																					<option value="234"  >South Georgia & South Sandwich Islands</option>
																					<option value="235"  >Turks and Caicos Islands</option>
																					<option value="236"  >Northern Mariana Islands</option>
																					<option value="237"  >Puerto Rico</option>
																					<option value="238"  >American Samoa</option>
																					<option value="239"  >Baker Island</option>
																					<option value="240"  >Guam</option>
																					<option value="241"  >Howland Island</option>
																					<option value="242"  >Jarvis Island</option>
																					<option value="243"  >Johnston Atoll</option>
																					<option value="244"  >Kingman Reef</option>
																					<option value="245"  >Midway Islands</option>
																					<option value="246"  >Navassa Island</option>
																					<option value="247"  >Palmyra Atoll</option>
																					<option value="248"  >U.S. Virgin Islands</option>
																					<option value="249"  >Wake Island</option>
																					<option value="250"  >Hong Kong</option>
																					<option value="251"  >Macau</option>
																					<option value="252"  >Faroe Islands</option>
																					<option value="253"  >Greenland</option>
																					<option value="254"  >French Guiana</option>
																					<option value="255"  >Guadeloupe</option>
																					<option value="256"  >Martinique</option>
																					<option value="257"  >Reunion</option>
																					<option value="258"  >Aland</option>
																					<option value="259"  >Aruba</option>
																					<option value="260"  >Netherlands Antilles</option>
																					<option value="261"  >Svalbard</option>
																					<option value="262"  >Ascension</option>
																					<option value="263"  >Tristan da Cunha</option>
																					<option value="268"  >Australian Antarctic Territory</option>
																					<option value="269"  >Ross Dependency</option>
																					<option value="270"  >Peter I Island</option>
																					<option value="271"  >Queen Maud Land</option>
																					<option value="272"  >British Antarctic Territory</option>
																			</select>
								</div>
							</div>
						</div>    
						<div class="form-group">
							<label for="zip_code">Pincode/Zip</label>
							<input class="form-control" id="personal_post_code" name="personal_post_code" placeholder="Pincode/Zip" type="tel" value="38632">
						</div>
						<button id="save-personal-info" type="submit" class="get-started-btn">Save personal info</button>
						<button id="go_to_company_info" class="get-started-btn">Continue to company info</button>
						<p class="create-business-card"><a class="disabled" href="#">Create Business Card</a></p>
					</form>
				</div>
			</div>
    </div>
	</div>
	<!--main end -->

</div>
<!-- wrapper-end -->


<!-- search-pages strat-->
<div class="search-sec" id="searchSecArea">
	<div class="search-cards-layout" id="searchCardsLayout">
		<a href="#" class="close-btn closeSearchSec"></a>
		<div class="search-info">
			<div class="search-card">
				<div class="inputCursor">
					<span class="span"></span>
					<div id="cursor"></div>
				</div>
				<input id="searchbox" type="text" placeholder="Search Card(s)...." class="text-input">
				<button id="btnSearch" onclick="searchPressed();"></button>
			</div>
			<h4 id="result-count"></h4>
			<ul class="card-links">
				<li><img src="assets/sat_images/remove-card.png" alt="">Link Request Approval Pending</li>
				<li><img src="assets/sat_images/card-added.png" alt="">Card Already Added</li>
				<li><img src="assets/sat_images/send-card.png" alt="">Send Card Link Request</li>
				<li><img src="assets/sat_images/add-card.png" alt="">Add Card/Contact</li>
			</ul>
			<div class="card-box-sec">
				
			</div><!--card-box-sec-->		
		</div>
	</div>
</div>
<!-- search-pages end-->

<script src="assets/js/lodash.compat.min.js"></script>
<script type="text/javascript" src="profile.js?version=20160201GMT170742.7385350"></script>
<!-- Load dependency assets -->
<!-- TODO: include older version of handlebars-->
<script src="assets/js/handlebars-v2.0.0.js" type="text/javascript"></script>
<!--<script src="assets/js/handlebars.js" type="text/javascript"></script>-->
<script src="assets/js/jquery.history.js" type="text/javascript"></script>
<!--<script src="components/status-bar.js" type="text/javascript"></script>-->
<script src="card-contacts/shared.js" type="text/javascript"></script>
<!--<script src="" type="text/javascript"></script>-->
<script src="assets/js/star-rating.js" type="text/javascript"></script>
<script>
	jQuery('.inner-sidebar-links li.company').removeClass('active');
	jQuery('.inner-sidebar-links li.personal').addClass('active');
</script>

<!--<script type="text/javascript" src="profile.js"></script>-->
<!--footer start -->
<div id="footer-wrap">
	<div class="container">
        <footer id="footer">
        	<ul class="ft-links">
                <li><a href="bcn.html">What is BCN?</a></li>
                <li><a href="features.html">Features</a></li>
                <li><a href="faq.html">FAQ</a></li>
                <li><a href="privacy-policy.html">Privacy policy</a></li>
                <li><a href="terms-of-use.html">Terms of use</a></li>    
            </ul>
            <a href="index.html" class="ft-logo"><img src="assets/sat_images/cardition.png" alt=""></a>
            <p>© Copyright 2015 cardition.com - All Rights Reserved. Designed by <a href="#">Cidify</a>, <img src="assets/sat_images/heart-img.png" alt="">with exclusive for <a href="#" class="site-link">cardition.com</a></p>
        </footer>
    </div>
</div>
<!--footer end -->

<script src="assets/js/bcfolder.js"></script>
<!--<script src=""></script>-->
<!--<script src="assets/js/modal-shortcuts.js"></script>-->
</body>
</html>