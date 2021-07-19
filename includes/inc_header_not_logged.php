<!--header start -->
<script type='text/javascript'>
	jQuery(document).ready(function(){
		var page = location.pathname.substring(1).split('.').shift();
		jQuery('.navbar-nav li').removeClass('active');
		jQuery('#'+page).addClass('active');
	});
</script>

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
                <!--<a class="navbar-brand logo1" href="index.php"><img src="assets/sat_images/cardition.png" alt=""></a>-->
                <a class="navbar-brand logo1" href="index.php"><img src="assets/sat_images/cardition.png" alt=""></a>
                <a class="navbar-brand logo2" href="index.php"><img src="assets/sat_images/user-logo-new.png" alt=""></a>
           </div>
            <div class="slide-navigation" id="toggle-nav">
                <ul class="nav navbar-nav navbar-right"> 
                 <!--<li class='whatIsBcn'><a href="index.php#whatIsBcn">What is BCN?</a></li>
                    <li class='featuresSec'><a href="index.php#featuresSec">Features</a></li>
                    <li class='faq'><a href="faq.php">FAQ</a></li>
                    <li class='contact'><a href="contact.php">Contact</a></li>
                    <li class='faq'><a href="#faq">FAQ</a></li>
                    <li class='contact'><a href="#contact">Contact</a></li>-->
                    <li><a class="no-scroll" href="#" id="singnInPopup">Sign in</a></li>
                    <li class='no-scroll signup-button'><a href="#" id="signupButton" class="singnUpPopup">Join now</a><br><div style="width:100%;text-align: center; color: #FC9726;margin-top:-20px;">It is free!</div></li>
                   <!--<li class='no-scroll signup-button'><a href="#" id="signupButton" class="singnUpPopup">Signup</a></div></li>-->
                </ul>
            </div>
        </nav>
    </div>
</div>
<!--header end -->
