<?php
require_once('session_setup.php');
include 'includes/head.php';
include 'config/config.php';
if ( isset( $_SESSION[ 'card' ] ) ) 
{
	include ('includes/create-business-card/cancel_card_creation.php');
	unset( $_SESSION[ 'card' ] );
}
error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );
//include 'includes/nav_not_logged_in.php'; 
?>
<body class="privacy-page">
	<!-- wrapper-start -->
	<div class="">

		<div class="overlay" id="overLay"></div>
		<div class="super-overlay" id="superOverLay"></div>
 
		<div class="overlay-info" id="signIn">
			<a href="#" class="close-btn closePopup"></a>
			<div class="sign-in">
        <h3>Sign in</h3>
        <form id="frmLogin">
					<div class="form-group">
						<input type="email" class="form-control email-field" id="login_email_address" placeholder="Email address">
					</div>
					<div class="form-group">
						<input type="password" class="form-control password-field" id="login_password" placeholder="Password">
						<a href="recover_password.php" class="forgot-password">Forgot Password?</a>
					</div>
					<button type="submit" id="login" class="get-started-btn">Take me in</button>
					<a href="#" class="sign-up-btn singnUpPopup">New user? Sign up now</a>
        </form>
			</div>
		</div>
		<div class="overlay-info" id="signUp">
			<a href="#" class="close-btn closePopup"></a>
			<div class="sign-up-wrap">
        <div class="sign-up-form">
					<h3>Sign up</h3>
					<form id="frmRegister">
						<div class="form-group">
							<input type="text" class="form-control full-name" name="full_name" id="full_name" placeholder="Full name">
						</div>
						<div class="form-group">
							<input type="email" class="form-control email-field" name="register_email_address" id="register_email_address" placeholder="Email address">
						</div>
						<div class="form-group">
							<input type="password" class="form-control password-field" name="register_password" id="register_password" placeholder="Password">
						</div>
						<button type="submit" id="sign-up" class="sign-up-btn">Take me in</button>
					</form>
        </div>
        <div class="bc-folder">
					<h2>What is Cardition?</h2>
					<p>A fast and easy way to upload your cards with the help of BC numbers at your personalized Cardition account.</p>
					<p>BCN stands for Business Card Number, it is an unique number for a business card or a product card. With the help of the BCN, anyone can download and save your contact details or product details in the form of a digital card online at one place. </p>
        </div>
			</div>
		</div>

		<?php
		if ( isset( $_SESSION[ 'user_id' ] ) )
		{
			require_once('includes/absolute_database_config.php');
			$user_id = $_SESSION[ 'user_id' ];
			$query = "SELECT first_name, last_name FROM user WHERE user_id='$user_id'";
			$result = mysqli_query( $conn, $query );
			$uname = mysqli_fetch_row( $result );
			include_once('includes/inc_header_logged.php');
		}
		else
		{
			include_once('includes/inc_header_not_logged.php');
		}
		?>

		<!--main start -->
		<div id="main-wrap" class="main-inner-wrap">
			<div id="main">
        <div class="main-banner privacy-banner">
					<div class="container">
						<div class="banner-info">
							<h1>Behind the door lies the question: Have you ever heard of a thing called knocking?</h1>
							<h3>We respect your privacy and concerns. And we have tried <em>to cover all the points in our privacy policy below</em></h3>
							<a href="#" class="get-started-btn">Get started</a>
						</div>
					</div> 
        </div>
        <div class="page-title">
					<div class="container">
						<h2>Privacy policy</h2>
					</div>
        </div>
        <div class="container">
					<div class="page-info">
						<p>Last updated: December 10, 2018</p>

						<h4>1. Introduction</h4>
						<p>This policy applies where we are acting as a data controller with respect to the personal data of our website and mobile application users; in other words, where we determine the purposes and means of the processing of that personal data.</p>
						<p>We use cookies on our website. Insofar as those cookies are not strictly necessary for the provision of our website and mobile application, we will ask you to consent to our use of cookies when you first visit our website.</p>
						<p>Our website incorporates privacy controls which affect how we will process your personal data. By using the privacy controls, you can specify whether you would like to receive direct marketing communications and limit the publication of your information. You can access the privacy controls via www.cardition.com/privacy_controls.</p>
						<p>In this policy, "we", "us" and "our" refer to Cardition ltd.</p>
						
						<h4>2. Credit</h4>
						<p>This document was created using a template from Docular (<a href="https://docular.net">https://docular.net</a>).</h4>

						<h4>3. How we use your personal data</h4>
						<p>In this Section 3 we have set out:</p>
						<ol type="a">
							<li>(a) the general categories of personal data that we may process;</li>
							<li>(b) in the case of personal data that we did not obtain directly from you, the source and specific categories of that data;</li>
							<li>(c) the purposes for which we may process personal data;</li>
							<li>(d) the legal bases of the processing.</li>
						</ol>
						<p>We may process data about your use of our website and services ("<b>usage data</b>"). The usage data may include your IP address, geographical location, browser type and version, operating system, referral source, length of visit, age views and website navigation paths, as well as information about the timing, frequency and pattern of your service use. The source of the usage data is our analytics tracking system. This usage data may be processed for the purposes of analysing the use of the website and mobile application. The legal basis for this processing is our legitimate interests, namely monitoring and improving our website and mobile application.</p>
						
						<p>We may process your account data ("<b>account data</b>"). The account data may include your name and email address. The account data may be processed for the purposes of operating our website or mobile application, ensuring the security of our website and mobile application, maintaining back-ups of our databases and communicating with you. The legal basis for this processing is consent.</p>
						
						<p>We may process your information included in your personal profile on our website our mobile application ("<b>profile data</b>"). The profile data may include your name, address, telephone number, email address, profile pictures, gender, date of birth, relationship status, interests and hobbies, educational details and employment details. The profile data may be processed for the purposes of enabling and monitoring your use of our website and services. The legal basis for this processing is consent.</p>

						<p>We may process information that you post for publication on our website or through our mobile application ("<b>publication data</b>"). The publication data may be processed for the purposes of enabling such publication and administering our website and mobile application. The legal basis for this processing is consent.</p>

						<p>We may process information relating to our customer relationships, including customer contact information ("<b>customer relationship data</b>"). The customer relationship data may include your name, your employer, your job title or role, your contact details, and information contained in communications between us and you or your employer. The source of the customer relationship data is you or your employer. The customer relationship data may be processed for the purposes of managing our relationships with customers, communicating with customers, keeping records of those communications and promoting our products and services to customers. The legal basis for this processing is consent</p>

						<p>We may process information that you provide to us for the purpose of subscribing to our email notifications and/or newsletters ("<b>notification data</b>"). The notification data may be processed for the purposes of sending you the relevant notifications and/or newsletters. The legal basis for this processing is consent.</p>

						<p>We may process information contained in or relating to any communication that you send to us ("<b>correspondence data</b>"). The correspondence data may include the communication content and metadata associated with the communication. Our website will generate the metadata associated with communications made using the website contact forms. The correspondence data may be processed for the purposes of communicating with you and record-keeping. The legal basis for this processing is our legitimate interests, namely the proper administration of our website and business and communications with users.</p>

						<p>We may process any of your personal data identified in this policy where necessary for the establishment, exercise or defence of legal claims, whether in court proceedings or in an administrative or out-of-court procedure. The legal basis for this processing is our legitimate interests, namely the protection and assertion of our legal rights, your legal rights and the legal rights of others.</p>

						<p>We may process any of your personal data identified in this policy where necessary for the purposes of obtaining or maintaining insurance coverage, managing risks, or obtaining professional advice. The legal basis for this processing is our legitimate interests, namely the proper protection of our business against risks.</p>

						<p>In addition to the specific purposes for which we may process your personal data set out in this Section 3, we may also process any of your personal data where such processing is necessary for compliance with a legal obligation to which we are subject, or in order to protect your vital interests or the vital interests of another natural person.</p>

						<p>Please do not supply any other person's personal data to us, unless we prompt you to do so.</p>

						<h4>4. Providing your personal data to others</h4>
						<p>We may disclose your personal data to any member of our group of companies (this means our subsidiaries, our ultimate holding company and all its subsidiaries) insofar as reasonably necessary for the purposes, and on the legal bases, set out in this policy.</p>

						<p>Financial transactions relating to our website and mobile application may be handled by our payment services providers, [identify PSPs]. We will share transaction data with our payment services providers only to the extent necessary for the purposes of processing your payments, refunding such payments and dealing with complaints and queries relating to such payments and refunds. You can find information about the payment services providers' privacy policies and practices at <a href="https://stripe.com/gb/privacy">https://stripe.com/gb/privacy</a>.</p>

						<p>We may disclose your enquiry data to one or more of those selected third party suppliers of goods and services identified on our website for the purpose of enabling them to contact you so that they can offer, market and sell to you relevant goods and/or services. Each such third party will act as a data controller in relation to the enquiry data that we supply to it; and upon contacting you, each such third party will supply to you a copy of its own privacy policy, which will govern that third party's use of your personal data.</p>
						
						<p>In addition to the specific disclosures of personal data set out in this Section 4, we may disclose your personal data where such disclosure is necessary for compliance with a legal obligation to which we are subject, or in order to protect your vital interests or the vital interests of another natural person. We may also disclose your personal data where such disclosure is necessary for the establishment, exercise or defence of legal claims, whether in court proceedings or in an administrative or out-of-court procedure.</p>

						<h4>5. International transfers of your personal data</h4>
						<p>In this Section 5, we provide information about the circumstances in which your personal data may be transferred to countries outside the European Economic Area (EEA).</p>

						<p>The hosting facilities for our website are situated in US, UK, EU. The European Commission has made an "adequacy decision" with respect to the data protection laws of each of these countries. Transfers to each of these countries will be protected by appropriate safeguards, namely the use of standard data protection clauses adopted or approved by the European Commission, a copy of which you can obtain from <i>[source]</i>.</p>
						
						<p>You acknowledge that personal data that you submit for publication through our website or mobile application may be available, via the internet, around the world. We cannot prevent the use (or misuse) of such personal data by others.</p>

						<h4>6. Retaining and deleting personal data</h4>
						<p>This Section 6 sets out our data retention policies and procedure, which are designed to help ensure that we comply with our legal obligations in relation to the retention and deletion of personal data.</p>
						
						<p>Personal data that we process for any purpose or purposes shall not be kept for longer than is necessary for that purpose or those purposes.</p>
						
						<p>We will retain your personal data as follows:</p>
						<ol type="a">
							<li>(a) name, address, telephone number, email address, profile pictures will be retained for a minimum period of 0 year following account closing , and for a maximum period of 0 year following account closing.</li>
						</ol>

						<p>In some cases it is not possible for us to specify in advance the periods for which your personal data will be retained. In such cases, we will determine the period of retention based on the following criteria:</p>
						<ol type="a">
							<li>(a) the period of retention of name, address, telephone number, email address, profile pictures will be determined based on <i>[specify criteria]</i>.</li>
						</ol>

						<p>Notwithstanding the other provisions of this Section 6, we may retain your personal data where such retention is necessary for compliance with a legal obligation to which we are subject, or in order to protect your vital interests or the vital interests of another natural person.</p>

						<h4>7. Amendments</h4>
						<p>We may update this policy from time to time by publishing a new version on our website.</p>

						<p>You should check this page occasionally to ensure you are happy with any changes to this policy.</p>

						<p>We may notify you of significant changes to this policy by email or through the private messaging system on our website.</p>
						
						<h4>8. Your rights</h4>
						<p>In this Section 8, we have summarised the rights that you have under data protection law. Some of the rights are complex, and not all of the details have been included in our summaries. Accordingly, you should read the relevant laws and guidance from the regulatory authorities for a full explanation of these rights.</p>
						
						<p>Your principal rights under data protection law are:</p>
						<ol type="a">
							<li>(a) the right to access;</li>
							<li>(b) the right to rectification;</li>
							<li>(c) the right to erasure;</li>
							<li>(d) the right to restrict processing;</li>
							<li>(e) the right to object processing;</li>
							<li>(f) the right to data portability;</li>
							<li>(g) the right to complain to a supervisory authority;</li>
							<li>(h) the right to withdraw consent.</li>
						</ol>
						
						<p>You have the right to confirmation as to whether or not we process your personal data and, where we do, access to the personal data, together with certain additional information. That additional information includes details of the purposes of the processing, the categories of personal data concerned and the recipients of the personal data. Providing the rights and freedoms of others are not affected, we will supply to you a copy of your personal data. The first copy will be provided free of charge, but additional copies may be subject to a reasonable fee.</p>

						<p>You have the right to have any inaccurate personal data about you rectified and, taking into account the purposes of the processing, to have any incomplete personal data about you completed.</p>

						<p>In some circumstances you have the right to the erasure of your personal data without undue delay. Those circumstances include: the personal data are no longer necessary in relation to the purposes for which they were collected or otherwise processed; you withdraw consent to consent-based processing; you object to the processing under certain rules of applicable data protection law; the processing is for direct marketing purposes; and the personal data have been unlawfully processed. However, there are exclusions of the right to erasure. The general exclusions include where processing is necessary: for exercising the right of freedom of expression and information; for compliance with a legal obligation; or for the establishment, exercise or defence of legal claims.</p>

						<p>In some circumstances you have the right to restrict the processing of your personal data. Those circumstances are: you contest the accuracy of the personal data; processing is unlawful but you oppose erasure; we no longer need the personal data for the purposes of our processing, but you require personal data for the establishment, exercise or defence of legal claims; and you have objected to processing, pending the verification of that objection. Where processing has been restricted on this basis, we may continue to store your personal data. However, we will only otherwise process it: with your consent; for the establishment, exercise or defence of legal claims; for the protection of the rights of another natural or legal person; or for reasons of important public interest.</p>

						<p>You have the right to object to our processing of your personal data on grounds relating to your particular situation, but only to the extent that the legal basis for the processing is that the processing is necessary for: the performance of a task carried out in the public interest or in the exercise of any official authority vested in us; or the purposes of the legitimate interests pursued by us or by a third party. If you make such an objection, we will cease to process the personal information unless we can demonstrate compelling legitimate grounds for the processing which override your interests, rights and freedoms, or the processing is for the establishment, exercise or defence of legal claims.</p>

						<p>To the extent that the legal basis for our processing of your personal data is:</p>
						<ol type="a">
							<li>(a) consent; or</li>
							<li>(b) that the processing is necessary for the performance of a contract to which you are party or in order to take steps at your request prior to entering into a contract,</li>
						</ol>
						<p>and such processing is carried out by automated means, you have the right to receive your personal data from us in a structured, commonly used and machine-readable format. However, this right does not apply where it would adversely affect the rights and freedoms of others.</p>
						
						<p>If you consider that our processing of your personal information infringes data protection laws, you have a legal right to lodge a complaint with a supervisory authority responsible for data protection. You may do so in the EU member state of your habitual residence, your place of work or the place of the alleged infringement.</p>

						<p>To the extent that the legal basis for our processing of your personal information is consent, you have the right to withdraw that consent at any time. Withdrawal will not affect the lawfulness of processing before the withdrawal.</p>

						<p>You may exercise any of your rights in relation to your personal data by written notice to us, in addition to the other methods specified in this Section 8.</p>
						
						<h4>9. About cookies</h4>
						<p>A cookie is a file containing an identifier (a string of letters and numbers) that is sent by a web server to a web browser and is stored by the browser. The identifier is then sent back to the server each time the browser requests a page from the server.</p>

						<p>Cookies may be either "persistent" cookies or "session" cookies: a persistent cookie will be stored by a web browser and will remain valid until its set expiry date, unless deleted by the user before the expiry date; a session cookie, on the other hand, will expire at the end of the user session, when the web browser is closed.</p>

						<p>Cookies do not typically contain any information that personally identifies a user, but personal information that we store about you may be linked to the information stored in and obtained from cookies.</p>

						<h4>10. Cookies that we use</h4>
						<p>We use cookies for the following purposes:</p>
						<ol type="a">
							<li>(a) authentication - we use cookies to identify you when you visit our website and as you navigate our website;</li>
							<li>(b) status - we use cookies to help us to determine if you are logged into our website;</li>
							<li>(c) security - we use cookies as an element of the security measures used to protect user accounts, including preventing fraudulent use of login credentials, and to protect our website and services generally;</li>
							<li>(d) advertising - we use cookies to help us to display advertisements that will be relevant to you;</li>
							<li>(e) analysis - we use cookies to help us to analyse the use and performance of our website and services; and</li>
							<li>(f) cookie consent - we use cookies to store your preferences in relation to the use of cookies more generally (cookies used for this purpose are: <i>[identify cookies]</i>).</li>
						</ol>

						<h4>Cookies used by our service providers</h4>
						<p>Our service providers use cookies and those cookies may be stored on your computer when you visit our website.</p>

						<p>We use Google Analytics to analyse the use of our website. Google Analytics gathers information about website use by means of cookies. The information gathered relating to our website is used to create reports about the use of our website. Google's privacy policy is available at: <a href="https://www.google.com/policies/privacy/">https://www.google.com/policies/privacy/</a>.</p>
						
						<p>We publish Google AdSense interest-based advertisements on our website. These are tailored by Google to reflect your interests. To determine your interests, Google will track your behaviour on our website and on other websites across the web using cookies. You can view, delete or add interest categories associated with your browser by visiting: <a href="https://adssettings.google.com">https://adssettings.google.com</a>. You can also opt out of the AdSense partner network cookie using those settings or using the Network Advertising Initiative's multi-cookie opt-out mechanism at: <a href="http://optout.networkadvertising.org">http://optout.networkadvertising.org</a>. However, these opt-out mechanisms themselves use cookies, and if you clear the cookies from your browser your opt-out will not be maintained. To ensure that an opt-out is maintained in respect of a particular browser, you may wish to consider using the Google browser plug-ins available at: <a href="https://support.google.com/ads/answer/7395996">https://support.google.com/ads/answer/7395996</a>.</p>
						
						<p>We use <i>[identify service provider]</i> to <i>[specify service]</i>. This service uses cookies for <i>[specify purpose(s)]</i>. You can view the privacy policy of this service provider at <i>[URL]</i>.</p>

						<h4>Managing cookies</h4>
						<p>Most browsers allow you to refuse to accept cookies and to delete cookies. The methods for doing so vary from browser to browser, and from version to version. You can however obtain up-to-date information about blocking and deleting cookies via these links:</p>
						<ol type="a">
							<li>(a) <a href="https://support.google.com/chrome/answer/95647?hl=en">https://support.google.com/chrome/answer/95647?hl=en</a> (Chrome);</li>
							<li>(b) <a href="https://support.mozilla.org/en-US/kb/enable-and-disable-cookieswebsite-preferences">https://support.mozilla.org/en-US/kb/enable-and-disable-cookieswebsite-preferences</a> (Firefox);</li>
							<li>(c) <a href="http://www.opera.com/help/tutorials/security/cookies/">http://www.opera.com/help/tutorials/security/cookies/</a> (Opera);</li>
							<li>(a) <a href="https://support.microsoft.com/en-gb/help/17442/windows-internetexplorer-delete-manage-cookies">https://support.microsoft.com/en-gb/help/17442/windows-internetexplorer-delete-manage-cookies</a> (Internet Explorer);</li>
							<li>(a) <a href="https://support.apple.com/kb/PH21411">https://support.apple.com/kb/PH21411</a> (Safari);</li>
							<li>(a) <a href="https://privacy.microsoft.com/en-us/windows-10-microsoft-edge-andprivacy">https://privacy.microsoft.com/en-us/windows-10-microsoft-edge-andprivacy</a> (Edge).</li>
						</ol>

						<p>Blocking all cookies will have a negative impact upon the usability of many websites.</p>
						
						<p>If you block cookies, you will not be able to use all the features on our website.</p>

						<h4>Our details</h4>
						<p>This website is owned and operated by Cardition Ltd.</p>
						
						<p>We are registered in England and Wales under registration number 09711058, and our registered office is at 15 castellain road W9 1EY, London, UK.</p>
						
						<p>You can contact us:</p>
						<ol type="a">
							<li>(a) by post, to the postal address given above;</li>
							<li>(b) using our website contact form;</li>
							<li>(c) by telephone, on the contact number published on our website from time to time; or</li>
							<li>(d) by email, using the email address published on our website from time to time.</li>
						</ol>

						<h4>Data protection officer</h4>
						<p>Our data protection officer's contact details are: <a href="mailto:support@cardition.com">support@cardition.com</a>.</p>
					</div>
        </div>
        <div class="get-started-sec">
					<a href="#" class="get-started-btn">Get started</a>
        </div>
			</div> 
		</div>
		<!--main-wrap end --> 

	</div>
	<!-- wrapper-end -->

	<?php include 'includes/footer.php'; ?>
	<script src="<?php echo force_reload( 'index.js' ); ?>"></script>
</body>
</html>
