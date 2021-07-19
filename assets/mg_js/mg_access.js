function InitSignupValidation() {
	jQuery('#frmRegister').validate({
		rules: {
			email: {
				required: true,
				email: true
			},
			user_pass: {
				required: true,
				pwcheck: '^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$'
			}
//			user_pass_confirm: {
//				required: true,
//				equalTo: '#user_pass'
//			}
		},
		messages: {
			email: {
				required: 'You must set an email address.',
				email: 'Insert a valid email address, please.'
			},
			user_pass: {
				required: "You must set a password.",
				pwcheck: 'Password must be 8 characters long with at leat 1 uppercase letter and 1 numeric value.'
			}
//			user_pass_confirm: {
//				required: "You must verify your password.",
//				equalTo: 'Passwords don\'t match.'
//			}
		},
		highlight: function (element) {
			jQuery(element).parent().closest('.input-wrapper').addClass('error-shadow');
		},
		unhighlight: function (element) {
			jQuery(element).parent().closest('.input-wrapper').removeClass('error-shadow');
		}
	});
}
jQuery(document).ready(function ($) {
//	InitializeLoginValidation();
//	InitializeRegisterValidation();
	InitSignupValidation();
	jQuery('#full_name').val('');
	jQuery('#email').val('');
	jQuery('#user_pass').val('');
	jQuery('#email-register').focusout(function () {
		$('#email-register').valid();
	});
	jQuery('#user_pass').focusout(function () {
		$('#user_pass').valid();
	});
	$.validator.addMethod("pwcheck", function (value) {
		return /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/.test(value); // consists of only these
	});
//	jQuery('#user_pass_confirm').focusout(function () {
//		$('#user_pass_confirm').valid();
//	});

	jQuery("#frmLogin input").keypress(function (e) {
		if (e.which == 13) {
			jQuery("#login").click();
		}
	});
	jQuery("#frmRegister input").keypress(function (e) {
		if (e.which == 13) {
			jQuery("#sign-up").click();
		}
	});
	jQuery(document).on("click", "#login", function (e) {
		var email_address = jQuery('#email-login').val();
		var password = jQuery('#password').val();
		e.preventDefault();
//		var login_token = jQuery('[name=login_token]').val();
//		alert(login_token);
		if(email_address === '' || password === '')
		{
//			e.preventDefault();
			swal({
				title: "Error!",
				text: "Please, correct errors before to submit your data.",
				html: true,
				type: "error",
				showCancelButton: false,
				confirmButtonColor: "#ff6603"
			});
			return;
		}
		jQuery.ajax({
			type: 'post',
			url: baseurl+'User/mg_check_user_data',
			data: {"email_address": email_address, "login_pass": password},
			success: function (result) 
			{
				if (result === 'false')
				{
					swal({
						title: "Error!",
						text: "Email address and password don't match. Please, revise your data and try again.",
						html: true,
						type: "error",
						showCancelButton: false,
						confirmButtonColor: "#ff6603"
					});
					return;
				}
				else 
				{
					jQuery('#frmLogin').submit();
				}
			},
			error: function (result)
			{

			}
		});

	});
	jQuery('#signin').on('click', function(e){
		e.preventDefault();
		openSignin();
	});
	
	jQuery('#signup').on('click', function(e){
		e.preventDefault();
		openSignup();
	});
	jQuery('.close').on('click', function(e){
		e.preventDefault();
		closeForm();
	});
	jQuery(document).on("click", "#sign-up-go", function (e) {
		e.preventDefault();
		jQuery('#signin-wrapper').slideUp();
		jQuery('.overlay').fadeOut(function()
		{
			jQuery('.navbar').css({'position': 'fixed', 'z-index': 1030});
			openSignup();
		});
	});

	var openSignin = function(){
		jQuery('.navbar').css({'position': 'relative', 'z-index': 0});
		jQuery('#signin-wrapper').slideDown();
		jQuery('.overlay').fadeIn();
	};
	var openSignup = function(){
		jQuery('.navbar').css({'position': 'relative', 'z-index': 0});
		jQuery('#signup-wrapper').slideDown();
		jQuery('.overlay').fadeIn();
	};
	var closeForm = function(){
		if (jQuery('#signup-wrapper').is(':visible'))
		{
			jQuery('#signup-wrapper').slideUp();
			jQuery('.overlay').fadeOut(function()
			{
				jQuery('.navbar').css({'position': 'fixed', 'z-index': 1030});
			});
		}
		else 
		{
			jQuery('#signin-wrapper').slideUp();
			jQuery('.overlay').fadeOut(function()
			{
				jQuery('.navbar').css({'position': 'fixed', 'z-index': 1030});
			});
		}
	};
	jQuery(document).on("click", "#sign-up", function (e) {
		e.preventDefault();
		$('#email-register').valid();
		$('#user_pass').valid();
		$('#user_pass_confirm').valid();
		var email_address = jQuery('#email-register').val();
		var password = jQuery('#user_pass').val();
		var password_check = jQuery('#user_pass_confirm').val();
		if (jQuery('.input-wrapper').hasClass('error-shadow'))
		{
			swal({
				title: "Error!",
				text: "Please, correct errors before to submit your data.",
				html: true,
				type: "error",
				showCancelButton: false,
				confirmButtonColor: "#ff6603"
			});
			return;
		}
		jQuery.ajax({
			type: 'post',
			url: baseurl + 'User/check_if_email_exists',
			data: {email_address: email_address},
			success: function (result)
			{
				if (result === "false")
				{
					jQuery('#frmRegister').submit();
					jQuery('#full_name').val('');
					jQuery('#email').val('');
					jQuery('#user_pass').val('');
					jQuery('#user_pass_confirm').val('');
				}
				else
				{
					swal({
						title: "Warning!",
						text: "This email address is already in use. Choose another one, please.",
						html: true,
						type: "warning",
						showCancelButton: false,
						confirmButtonColor: "#ff6603"
					});
				}
			},
			error: function (result)
			{
//			swal({
//				title: "Warning!",
//				text: "Error connecting to the server.",
//				type: "warning",
//				showCancelButton: false
//			});
			}
		});

	});
	jQuery(document).on("click", "#reset_password_button", function (e) {
		e.preventDefault();
		if (jQuery('.input-wrapper').hasClass('error-shadow'))
		{
			swal({
				title: "Error!",
				text: "Please, correct errors before to submit your data.",
				html: true,
				type: "error",
				showCancelButton: false,
				confirmButtonColor: "#ff6603"
			});
			return;
		}
		else if ($('#user_pass').valid() && $('#user_pass_confirm').valid())
		{
			var password = jQuery('#user_pass').val();
			var password_check = jQuery('#user_pass_confirm').val();
			$('form').submit();
		}
	});
	
	function checkUserHasCard() {
		jQuery.ajax({
			type: "POST",
			url: "includes/card-link/c_check_reciprocity_user_has_card.php",
			success: function (data) {
				var response = JSON.parse(data);
				if (parseInt(response.result) > 0) {
					window.location.href = globalURL + "home.php";
				}
				else {
					window.location.href = globalURL + "profile.php";
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				showErrorModal();
			}
		});
	}


});


