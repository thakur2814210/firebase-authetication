function goToLogin() {
	jQuery('#singnInPopup').trigger('click');
}
jQuery(document).ready(function ($) {
//	$(document).on("scroll", onScroll);
//	function onScroll(event) {
//		var scrollPos = jQuery(document).scrollTop();
//		jQuery('.navbar-nav li a:not(.no-scroll)').each(function () {
//			var currLi = jQuery(this).parent('li');
//			var currLink = jQuery(this);
//			var el = jQuery(this).attr('href');
//			var refElement = jQuery(currLink.attr("href"));
//			if (refElement) {
//				if (refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {
////					jQuery('.navbar-nav li').removeClass("active");
//					currLi.addClass("active");
//				} else {
//					currLi.removeClass("active");
//				}
//			}
//		});
//	}
	jQuery('navbar-nav li:not(.no-scroll)').on('click', function () {
		var target = jQuery(this).attr('href');
		jQuery('html,body').stop(true, true).animate({
			scrollTop: jQuery(target).offset().top - jQuery('#header-wrap').innerHeight()
		}, 500, function () {
			onScroll();
		});
	});
//	var relHash = ('[href*=#]:not([href=#])');
//	jQuery('.page-navigation a' + relHash).click(function () {
//		if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
//			var target = jQuery(this.hash);
//			var hash = jQuery(this).attr('href');
//
////			jQuery('.navbar-nav li').removeClass('active');
////			jQuery(this).parent('li').addClass('active');
////			console.log(jQuery(this).parent('li').attr('class'));
////			jQuery('.link-items a').removeClass('active');
////			jQuery('.link-items a[href='+ hash +']').addClass('active');
//
//			target = target.length ? target : jQuery('[name=' + this.hash.slice(1) + ']');
//			if (target.length) {
//				jQuery('html,body').stop(true, true).animate({
//					scrollTop: target.offset().top - jQuery('#header-wrap').innerHeight()
//				}, 500);
//				onScroll();
//				return false;
//			}
//		}
//	});

	jQuery.validator.addMethod("pwcheck", function (value) {
		return true;
//		return /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/.test(value); 
	});
	InitializeLoginValidation();
	InitializeRegisterValidation();
	jQuery('#full_name').val('');
	jQuery('#register_email_address').val('');
	jQuery('#register_password').val('');
	jQuery('#full_name').focusout(function () {
		jQuery('#full_name').valid();
	});
	jQuery('#register_email_address').focusout(function () {
		jQuery('#register_email_address').valid();
	});
	jQuery('#register_password').focusout(function () {
		jQuery('#register_password').valid();
	});
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
		e.preventDefault();
		var email_address = jQuery('#login_email_address').val();
		var password = jQuery('#login_password').val();
		if (email_address == '' || password == '') {
			swal({
				title: "Warning!",
				text: "Fill in all required fields, please.",
				html: true,
				type: "warning",
				showCancelButton: false,
				confirmButtonColor: "#ff6603",
				confirmButtonText: "Ok",
				closeOnConfirm: true
			});
			return;
		}
		loaderText('Taking you in...');
		loader.show();
		var account_deleted = jQuery.cookie('accountDeleted');
		if (jQuery('#frmLogin').valid()) {
//			var email_address = jQuery('#login_email_address').val();
			if (account_deleted == email_address) {
				swal({
					title: "Warning!",
					text: "This account has been deleted. Please, signup again.",
					html: true,
					type: "warning",
					showCancelButton: false,
					confirmButtonColor: "#ff6603",
					confirmButtonText: "Ok",
					closeOnConfirm: true
				});
				loader.hide();
				jQuery('.singnUpPopup').trigger('click');
				return;
			}
//			var password = jQuery('#login_password').val();
			if (email_address === '' || password === '')
			{
				loader.hide();
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
			console.log('calling login.php');
			jQuery.ajax({
				type: "POST",
				url: "includes/home/login.php",
				data: {
					email_address: email_address,
					password: password
				},
				success: function (data) {
					console.log('login data');
					console.log(data);
					var response = JSON.parse(data);
					if (response.success) {
						loader.hide();
						console.log('login response');
						console.log(response);
						if (!response.login_success) {
							swal({
								title: "Error!",
								text: "Invalid combination of email address and password. Please, retry.",
								html: true,
								type: "error",
								showCancelButton: false,
								confirmButtonColor: "#ff6603"
							});
							return;
						} else {
							if (response.verified !== "1") {
								swal({
									title: "Account needs verification!",
									text: "Your account has not been verified yet! Follow the link sent via email to verify your account.",
									html: true,
									type: 'warning',
									showCancelButton: true,
									confirmButtonColor: "#ff6603",
									confirmButtonText: 'Resend email'
								}, (result) => {
									console.log('swal', result);
									if (result) {
										CallEmailResend(email_address);
									}
								});
								return;
							} else {
								if (response.login_success && response.verified == "1") {
									localStorage["first_name"] = response.first_name;
									if (response.sharedCard != '') {
										var form = $(document.createElement('form'));
										$(form).attr("action", "member_card_viewer.php");
										$(form).attr("method", "post");
										$(form).css("display", "block");

										var input_sharedCard = $("<input>")
												.attr("type", "text")
												.attr("name", "viewcard")
												.val(response.sharedCard);
										$(form).append($(input_sharedCard));

										form.appendTo(document.body);
										$(form).submit();
//										window.location.href = globalURL + 'guest_card_viewer.php?viewcard=' + response.sharedCard;
									}else if(response.rejectRequest != ''){
										jQuery.cookie('rejectRequest', response.rejectRequest);
										window.location.href = globalURL + 'card-request.php';
									}else if(response.approveRequest != ''){
										jQuery.cookie('approveRequest', response.approveRequest);
										window.location.href = globalURL + 'card-request.php';
									}else if(response.addCard != ''){
										jQuery.cookie('addCard', response.addCard);
//										console.log('cookie is '+response.addCard);
										window.location.href = globalURL + 'dashboard.php';
									}else if(response.viewCardDetails != ''){
										jQuery.cookie('viewCardDetails', response.viewCardDetails);
										window.location.href = globalURL + 'dashboard.php';
									} else if (response.profile_completed === "true") {
//										console.log('response.profile_completed = ' + response.profile_completed);
//										console.log('globalUrl = ' + globalURL);
//										console.log('going to dashboard');
//										window.location.href = globalURL + 'dashboard.php?cid=' + response.sharedCard;
										window.location.href = 'dashboard.php';
									} else {
//										console.log('response.profile_completed = ' + response.profile_completed);
//										console.log('globalUrl = ' + globalUrl);
										window.location.href = globalURL + 'personal-info.php';
									}
								}
							}
						}
					} else {
						loader.hide();
//						showErrorModal();
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					//				l.stop();
					//				showErrorModal();
				}
			});
		}
	});
	jQuery(document).on("click", "#sign-up", function (e) {
		e.preventDefault();
		var full_name = jQuery("#full_name").val();
		var register_email_address = jQuery("#register_email_address").val();
		var register_password = jQuery("#register_password").val();
		if (full_name == '' || register_email_address == '' || register_password == '') {
			swal({
				title: "Warning!",
				text: "Fill in all required fields, please.",
				html: true,
				type: "warning",
				showCancelButton: false,
				confirmButtonColor: "#ff6603",
				confirmButtonText: "Ok",
				closeOnConfirm: true
			});
			return;
		}
		loaderText('Registering your data...');
		loader.show();
		if (jQuery('#frmRegister').valid()) {
			var full_name = jQuery("#full_name").val();
			var first_name = full_name.substr(0, full_name.indexOf(' '));
			var last_name = full_name.substr(full_name.indexOf(' ') + 1);
			if (last_name == "") {
				var last_name = first_name;
			}
			var register_email_address = jQuery("#register_email_address").val();
			var register_password = jQuery("#register_password").val();
			jQuery('#register_email_address').valid();
			jQuery('#register_password').valid();
			if (jQuery('.form-wrapper').hasClass('error-shadow'))
			{
				loader.hide();
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
//			console.log(first_name);
//			console.log(last_name);
			jQuery.ajax({
				type: "post",
				url: "includes/home/register.php",
				data: {
					first_name: first_name,
					last_name: last_name,
					register_email_address: register_email_address,
					register_password: register_password
				},
				success: function (data) {
//					console.log('data');
//					console.log(data);
					var response = JSON.parse(data);
					if (response.success) {
						loader.hide();
						if (!response.email_exists) {
							swal({
								title: "Welcome!",
								text: "You have been successfully registered. Please check your inbox for a confirmation email.",
//								text: "You have been successfully registered.<br><span style='color: #fd6f02; font-weight: bold;'>Now you can enter the site clicking on Sign in button and using your email and password.</span>",
								html: true,
								type: "success",
								showCancelButton: false,
								confirmButtonColor: "#ff6603"
							}, function (isConfirm) {
								if (isConfirm) {
									jQuery.removeCookie('accountDeleted', {path: '/'});
									closePopup();
									jQuery('#singnInPopup').trigger('click');
									return;
								}
							});
						} else {
							loader.hide();
							swal({
								title: "Email already exists!",
								text: "The email " + register_email_address + " you entered already exists on our system. Please enter your password, or choose to reset it if you have forgotten it.",
								html: true,
								type: "warning",
								showCancelButton: false,
								confirmButtonColor: "#ff6603"
							});
							return;
						}
					} else {
						loader.hide();
						swal({
							title: "Error!",
							text: "Error occurred: registration failed.",
							html: true,
							type: "error",
							showCancelButton: false,
							confirmButtonColor: "#ff6603"
						});
						return;
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					loader.hide();
					swal({
						title: "Error!",
						text: "Error occurred: " + textStatus + ' - ' + errorThrown,
						html: true,
						type: "error",
						showCancelButton: false,
						confirmButtonColor: "#ff6603"
					});
					return;
				}
				
			});
		}
	});
	jQuery(document).on("click", "#forgot_password", function () {
		//if (jQuery('#frmLogin').valid()) {
//		var l = Ladda.create(document.querySelector("#forgot_password"));
		loaderText('Resetting your password...');
		loader.show();
		jQuery.ajax({
			type: "POST",
			url: "includes/auth/reset_password.php",
			data: {
				email_address: jQuery("#email_address").val()
			},
			beforeSend: function () {
				loader.show();
			},
			success: function (data) {
				loader.hide();
				var response = JSON.parse(data);
				if (response.success) {
					if (response.email_exists) {
						swal({
							title: "Email sent",
							text: "You'll receive an email shortly with detailed instructions to resetting your password!",
							html: true,
							type: "info",
							showCancelButton: false,
							confirmButtonColor: "#ff6603",
							confirmButtonText: "Ok",
							closeOnConfirm: true
						});
					} else {
						swal({
							title: "Error!",
							text: "The email address you entered has not been registered on our system before.",
							html: true,
							type: "error",
							showCancelButton: false,
							confirmButtonColor: "#ff6603",
							confirmButtonText: "Ok",
							closeOnConfirm: true
						});
					}
				} else {
					showErrorModal();
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				//stops spinner
//				console.log("closing");
				setTimeout(function () {
					jQuery.loader('close');
				}, timeOut);
				showErrorModal();
			}
		});
		//}
	});

	jQuery(document).on('click', '#send-mail', function (e) {
		e.preventDefault();
		loaderText('Sending email...');
		loader.show();
		var name = jQuery('#your_name').val();
		var email = jQuery('#your_email').val();
		var message = jQuery('#message').val();
		if (name === '' || email === '' || message === '') {
			loader.hide();
			swal({
				title: "Warning!",
				text: "Please, fill in all fields.",
				html: true,
				type: "warning",
				showCancelButton: false,
				confirmButtonColor: "#ff6603",
				confirmButtonText: "Ok",
				closeOnConfirm: true
			});
			return;
		}
		jQuery.ajax({
			type: 'post',
			url: 'includes/mg_send_mail.php',
			data: {name: name, email: email, message: message},
			success: function (result)
			{
				loader.hide();
				var response = JSON.parse(result);
				if (response.state === 'error') {
					swal({
						title: "Email not sent!",
						text: "An error occurred sending your email. Please, retry.",
						html: true,
						type: "warning",
						showCancelButton: false,
						confirmButtonColor: "#ff6603",
						confirmButtonText: "Ok",
						closeOnConfirm: true
					});
				} else {
					swal({
						title: "Email successfully sent.",
						text: "Your message has been correctly sent. We'll contact you soon.",
						html: true,
						type: "success",
						showCancelButton: false,
						confirmButtonColor: "#ff6603",
						confirmButtonText: "Ok",
						closeOnConfirm: true
					});
				}
			},
			error: function (result)
			{
				console.log('error sending email');
			}
		});

	});

});
//function checkUserHasCard() {
//	jQuery.ajax({
//		type: "POST",
//		url: "includes/card-link/c_check_reciprocity_user_has_card.php",
//		success: function (data) {
//			var response = JSON.parse(data);
//			if (parseInt(response.result) > 0) {
//				window.location.href = globalURL + "home.php";
//			} else {
//				window.location.href = globalURL + "profile.php";
//			}
//		},
//		error: function (jqXHR, textStatus, errorThrown) {
//			showErrorModal();
//		}
//	});
//}
function CallEmailResend(email_address) {
	loaderText('Sending email...');
	loader.show();

	jQuery.ajax({
		type: "POST",
		url: "includes/home/resend_verification_email.php",
		data: {
			email_address: email_address
		},
		success: function (result) {
			loader.hide();
			var response = JSON.parse(result);

			if (response.success === 1) {
				swal({
					title: 'Email sent',
					text: 'Another verification email has been sent to your account',
					type: 'success'
				});
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			//stops spinner
			setTimeout(function () {
				jQuery.loader('close');
			}, timeOut);

			showErrorModal();
		}
	});
}

function InitializeLoginValidation() {
	jQuery('#frmLogin').validate({
		rules: {
			login_email_address: {
				required: true,
				email: true
			},
			login_password: {
				required: true
			},
		},
		highlight: function (element) {
			jQuery(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function (element) {
			jQuery(element).closest('.form-group').removeClass('has-error');
		},
//		errorElement: 'span',
//		errorClass: 'help-block',
//		errorPlacement: function (error, element) {
//			if (element.parent('.input-group').length) {
//				error.insertAfter(element.parent());
//			} else {
//				error.insertAfter(element);
//			}
//		}
	});
}
function InitializeRegisterValidation() {
	jQuery('#frmRegister').validate({
		rules: {
			full_name: {
				required: true
			},
			register_email_address: {
				required: true,
				email: true,
			},
			register_password: {
				required: true,
				pwcheck: '^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$'
//				minlength: 5,
			}
		},
		messages: {
			full_name: {
				required: 'You must specify your full name.'
			},
			register_email_address: {
				required: 'You must set an email address.',
				email: 'Insert a valid email address, please.'
			},
			register_password: {
				required: "You must set a password.",
				pwcheck: 'Password must be 8 characters long with at leat 1 uppercase letter and 1 numeric value.'
			}
		},
		highlight: function (element) {
			jQuery(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function (element) {
			jQuery(element).closest('.form-group').removeClass('has-error');
		},
//		errorElement: 'span',
//		errorClass: 'help-block',
//		errorPlacement: function (error, element) {
//			if (element.parent('.input-group').length) {
//				error.insertAfter(element.parent());
//			} else {
//				error.insertAfter(element);
//			}
//		}
	});
}
