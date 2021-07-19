<?php
require_once('session_setup.php');
include 'includes/head.php';
include 'config/config.php';
if ( isset( $_SESSION[ 'card' ] ) )
{
	include ('includes/create-business-card/cancel_card_creation.php');
	unset( $_SESSION[ 'card' ] );
}
?>
<body>
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
  $user_id = $_SESSION['user_id'];
	$query = "SELECT first_name, last_name FROM user WHERE user_id='$user_id'";
	$result = mysqli_query($conn, $query);
	$uname = mysqli_fetch_row($result);
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
        <div class="main-banner faq-banner">
            <div class="container">
                <div class="banner-info">
                    <h1>No one is dumb who is curious. The people <em>who don’t ask questions remain clueless always</em></h1>
                    <h3>Here’s a list of frequently asked questions about Cardition and our answers, <em>so you don’t have to go wondering what’s the catch!</em></h3>
							<?php
							if (isset($_SESSION['user_id'])){
							?>
							<a href="dashboard.php" class="get-started-btn">Get started</a>
							<?php }else{ ?>
							<a href="#" onclick="goToLogin()" class="get-started-btn">Get started</a>
							<?php } ?>
                    <!--<a href="#" class="get-started-btn">Get started</a>-->
                </div>
            </div> 
        </div>
        <div class="page-title">
            <div class="container">
                <h2>Frequently Asked Questions</h2>
            </div>
        </div>
        <div class="container">
            <div class="page-info faq-info">
                <h3>What is Cardition?</h3>
                <p>BCN stands for Business Card Number, it is an unique number for a business card or a product card. With the help of the BCN, anyone can download and save your contact details or product details in the form of a digital card online at one place. That one place is cardition.com, the personalized online folder to keep all personal and professional contacts as well as products and services details in a digital card format</p>
                <h3>What does BC Number or BCN stands for?</h3>
                <p>BCN stands for Business Card Number, it is an unique number for a business card or a product card. With the help of the BCN, anyone can download and save your contact details or product details in the form of a digital card online at one place. That one place is cardition.com, the personalized online folder to keep all personal and professional contacts as well as products and services details in a digital card format</p>
                <h3>How could I create a virtual digital cards?</h3>
                <p>BCN stands for Business Card Number, it is an unique number for a business card or a product card. With the help of the BCN, anyone can download and save your contact details or product details in the form of a digital card online at one place. That one place is cardition.com, the personalized online folder to keep all personal and professional contacts as well as products and services details in a digital card format</p>
                <h3>Once Purchased the BCN, for how long I can use the same BCN with the amount paid once?</h3>
                <p>BCN stands for Business Card Number, it is an unique number for a business card or a product card. With the help of the BCN, anyone can download and save your contact details or product details in the form of a digital card online at one place. That one place is cardition.com, the personalized online folder to keep all personal and professional contacts as well as products and services details in a digital card format</p>
                <h3>Will I get the bill/invoice for the BCN purchase?</h3>
                <p>BCN stands for Business Card Number, it is an unique number for a business card or a product card. With the help of the BCN, anyone can download and save your contact details or product details in the form of a digital card online at one place. That one place is cardition.com, the personalized online folder to keep all personal and professional contacts as well as products and services details in a digital card format</p>
                <h3>How can I delete my account from Cardition.com?</h3>
                <p>BCN stands for Business Card Number, it is an unique number for a business card or a product card. With the help of the BCN, anyone can download and save your contact details or product details in the form of a digital card online at one place. That one place is cardition.com, the personalized online folder to keep all personal and professional contacts as well as products and services details in a digital card format</p>
                <h3>Can I use my Paypal account to purchase BCN?</h3>
                <p>BCN stands for Business Card Number, it is an unique number for a business card or a product card. With the help of the BCN, anyone can download and save your contact details or product details in the form of a digital card online at one place. That one place is cardition.com, the personalized online folder to keep all personal and professional contacts as well as products and services details in a digital card format</p>
                <h3>Can I make my card a private from others to restrict the view?</h3>
                <p>BCN stands for Business Card Number, it is an unique number for a business card or a product card. With the help of the BCN, anyone can download and save your contact details or product details in the form of a digital card online at one place. That one place is cardition.com, the personalized online folder to keep all personal and professional contacts as well as products and services details in a digital card format</p>
                <h3>Can I send a private card holder a ‘Card Link Request’?</h3>
                <p>BCN stands for Business Card Number, it is an unique number for a business card or a product card. With the help of the BCN, anyone can download and save your contact details or product details in the form of a digital card online at one place. That one place is cardition.com, the personalized online folder to keep all personal and professional contacts as well as products and services details in a digital card format</p>
                <p>If you have more questions, which we have not covered in our <a href="#">FAQ</a> section, then feel free to write us at: <a href="#">queries@cardition.com</a>. We will get back to you within 42 hours.</p>
            </div>
        </div>
        <div class="get-started-sec">
							<?php
							if ($_SESSION['user_id']){
							?>
							<a href="dashboard.php" class="get-started-btn">Get started</a>
							<?php }else{ ?>
							<a href="#" onclick="goToLogin()" class="get-started-btn">Get started</a>
							<?php } ?>
            <!--<a href="#" class="get-started-btn">Get started</a>-->
        </div>
    </div>
</div>
<!--main-wrap end -->

</div>
<!-- wrapper-end -->

<script src="<?php echo force_reload('index.js'); ?>"></script>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('.navbar-nav li.faq').addClass('active');
	});
</script>
<?php include 'includes/footer.php';?>
</body>
</html>
