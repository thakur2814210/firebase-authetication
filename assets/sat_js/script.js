jQuery.noConflict();
var loader;
var loadingExtension = {
	oldShowLoading: jQuery.fancybox.showLoading,
	oldHideLoading: jQuery.fancybox.hideLoading,
	showLoading: function () {
//		loader.show();
//		alert('ciao');
	},
	hideLoading: function () {
		loader.hide();
	}
};
jQuery.extend(jQuery.fn.fancybox, loadingExtension);
function loaderText(msg){
		jQuery('#spinner-title').text(msg);
	}

jQuery(document).ready(function () {

	jQuery('body').append(
//					"<div id='spinner'><img src='assets/images/spinner.gif' alt='Loading...'/></div>"
					'<div id="spinner" class="spinner-overlay spinner-page"><p id="spinner-title"></p><div class="cssload-loader"><div class="cssload-side"></div><div class="cssload-side"></div><div class="cssload-side"></div><div class="cssload-side"></div><div class="cssload-side"></div><div class="cssload-side"></div><div class="cssload-side"></div><div class="cssload-side"></div></div></div>'
					);
	loader = jQuery('#spinner').hide();
//	jQuery('#spinner').css(
//					{
//						'display': 'none',
//						'width': '100px',
//						'height': '100px',
//						'position': 'fixed',
//						'top': '50%',
//						'left': '50%',
//						'text-align': 'center',
//						'margin-left': '-50px',
//						'margin-top': '-100px',
//						'z-index': '20',
//						'overflow': 'auto'
//					});

//	jQuery(document).ajaxStart(function ()
//	{
//		loading.show();
//	}).ajaxStop(function ()
//	{
//		loading.hide();
//	});	

	/*header-sec
	 -----------------------------------------*/

	function headerFix() {
		if ( jQuery(window).scrollTop() > 10 ) {
			jQuery('#header-wrap').addClass('scrolling')
			jQuery('#scrollDownLink').fadeOut('slow')
		} else {
			jQuery('#header-wrap').removeClass('scrolling')
			jQuery('#scrollDownLink').fadeIn('slow')
		}
	}

	headerFix();
	jQuery(window).scroll(function () {
		headerFix();
	})
//	var relHash = ('[href*=#]:not([href=#])')
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
//				return false;
//			}
//		}
//	});

	/*home-page
	 =========================*/
	// jQuery('.home-banner').height(window).height();

	/*popup-sec
	 =======================*/
	jQuery('#singnInPopup').click(function () {
		jQuery('body').addClass('active')
		jQuery('#overLay').slideDown()
		jQuery('#signIn').slideDown()
		jQuery("html, body").animate({scrollTop: 0}, "slow");
		return false;
	});
	jQuery('.singnUpPopup').click(function () {
		jQuery('body').addClass('active')
		jQuery('#overLay').slideDown()
		jQuery('.overlay-info').slideUp()
		jQuery('#signUp').slideDown()
		jQuery("html, body").animate({scrollTop: 0}, "slow");
		return false;
	});
	jQuery(document).on('click', '.createNewFolder', function () {
		jQuery('body').addClass('active')
		jQuery('#overLay').slideDown()
		jQuery('#createFolder').slideDown()
		jQuery("html, body").animate({scrollTop: 0}, "slow");
		return false;
	});
//	jQuery('.modifyCardSetting a').click(function () {
//		jQuery('body').addClass('active')
//		jQuery('#overLay').slideDown()
//		jQuery('#cardSetting').slideDown()
//		jQuery("html, body").animate({scrollTop: 0}, "slow");
//		return false;
//	});
//	jQuery('.addCardWithBCN').click(function () {
//		jQuery('body').addClass('active')
//		jQuery('#overLay').slideDown()
//		jQuery('#preViewSave').slideDown()
//		jQuery("html, body").animate({scrollTop: 0}, "slow");
//		return false;
//	});
//	jQuery('.userDetailLink, .cradDetailLink').click(function () {
//		jQuery('body').addClass('active')
//		jQuery('#overLay').slideDown()
//		jQuery('#userCardDetail').slideDown()
//		jQuery("html, body").animate({scrollTop: 0}, "slow");
//		jQuery('.search-sec').css({
//			'overflow-y': 'hidden',
//		})
//		return false;
//	});
	jQuery('#settingUpDateLink').click(function () {
		jQuery('body').addClass('active');
		jQuery('#overLay').slideDown();
		jQuery('#settingUpDate').slideDown();
		jQuery("html, body").animate({scrollTop: 0}, "slow");
		return false;
	})
//	jQuery('.avatar-slide').click(function () {
//		jQuery('body').addClass('active');
//		jQuery('#overLay').slideDown();
//		jQuery('#avatarSlideSec').slideDown();
//		jQuery("html, body").animate({scrollTop: 0}, "slow");
//		return false;
//	});
	jQuery(document).on('click', '.closePopup', function () {
		//		if (jQuery(this).hasClass('folder-creation-sub')) { //if the popup must be open within a previous popup
//		if (jQuery(this).parent().parent().attr('id') === 'userCarDetails') { //if the popup must be open within a previous popup
//			closePopup(function () {
//				jQuery('#userCardDetail').slideDown();
//				jQuery('#createFolder').slideUp();
//			});
//		}else if (jQuery(this).parent().attr('id') === 'buy-premium-id') {
//			closePopup(function () {
//				jQuery('#preViewSave').slideDown();
//				jQuery('#buy-premium-id').slideUp();
//			});
//		} else {
//			closePopup();
//		}
		var data_popup_id = jQuery(this).attr('data-popup-id');
		if (data_popup_id === 'card-sharing-panel' || data_popup_id === 'share-with-contacts'){
			jQuery('#mail-contacts').children().remove();
		}

		if ( jQuery(this).parent().attr('id') === 'avatarSlideSec' ) { //if the popup must be open within a previous popup
			var st = jQuery('#searchSecArea').css('display');
			if ( jQuery('#searchSecArea').css('display') == 'block' ||
							jQuery('#userCardDetail').css('display') == 'block' ) {
				console.log('bak to search');
				closePopup(function () {
					jQuery('#avatarSlideSec').slideUp();
//					jQuery('#superOverLay').slideUp();
					jQuery('#overLay').slideUp();
				});
			} else {
				console.log('closing avatarSlideSec');
				jQuery('#avatarSlideSec').slideUp();
				jQuery('#overLay').slideUp();
				jQuery('body').removeClass('active');
			}
		} else if ( jQuery(this).parent().parent().attr('id') === 'userCarDetails' ) { //if the popup must be open within a previous popup
			closePopup(function () {
				console.log('closing userCarddetails');
				jQuery('#userCardDetail').slideDown();
				jQuery('#createFolder').slideUp();
				jQuery('body').removeClass('active');
			});
		} else if ( jQuery(this).parent().attr('id') === 'buy-premium-id' ) {
			closePopup(function () {
				console.log('closing preview');
				jQuery('#preViewSave').slideDown();
				jQuery('#buy-premium-id').slideUp();
				jQuery('body').removeClass('active');
			});
		} else if ( jQuery(this).parent().attr('id') === 'searchCardsLayout' ) {
			closePopup(function () {
				console.log('closing search');
				jQuery('#searchSecArea').slideDown();
				jQuery('body').removeClass('active');
			});
		} else {
			closePopup();
		}
	});

	/*create-card-pages
	 ===============================*/
	jQuery(document).on('click', '.backSideLink', function (e) {
		e.preventDefault();
//		console.log('back');
		jQuery('.aboutCardposition').addClass('active');
		return false;
	});
	jQuery(document).on('click', '.frontSideLink', function (e) {
		e.preventDefault();
//		console.log('front');
		jQuery('.aboutCardposition').removeClass('active');
		return false;
	});
	jQuery('.portraitCard').click(function () {
		jQuery('.aboutCardposition').addClass('portrait');
		jQuery('.card-area .cardRotaTion').removeClass('active');
		jQuery(this).parent().addClass('active');
		jQuery('.fake-img').attr('src', 'assets/images/card_default_white_portrait.jpg');
		return false;
	});
	jQuery('.landScapeCard').click(function () {
		jQuery('.aboutCardposition').removeClass('portrait');
		jQuery('.card-area .cardRotaTion').removeClass('active');
		jQuery(this).parent().addClass('active');
		jQuery('.fake-img').attr('src', 'assets/images/card_default_white.jpg');
		return false;
	});

	/*sidebar-sec
	 ==============================*/
	// if(jQuery(window).width() < 767){
	// 	jQuery(window).on('load resize', function () {
	// 		jQuery('body').addClass('pageLayOut')			
	// 	});
	// }else{
	// 	jQuery('body').removeClass('pageLayOut')
	// }
	jQuery('#sideBarToggle').click(function () {
		if ( jQuery('body').hasClass('pageLayOut') ) {
			jQuery('body').removeClass('pageLayOut')
			jQuery('#user-main-wrap, .inner-sidebar, .profile-links li').removeClass('active');
		} else {
			jQuery('body').addClass('pageLayOut')
		}
		return false;
	})

//MOVED TO SEARCH-PAGE.JS
//	jQuery('.innerSideBarLink').click(function () {
//		var curItem = jQuery(this).attr('href');
//		if (jQuery(this).parents('li').hasClass('active')) {
//			jQuery('.profile-links > li').removeClass('active');
//			jQuery('#user-main-wrap').removeClass('active');
//			jQuery('.inner-sidebar').removeClass('active');
//		} else {
//			jQuery('.profile-links > li').removeClass('active');
//			jQuery(this).parents('li').addClass('active');
//			jQuery('#user-main-wrap').addClass('active');
//			jQuery('.inner-sidebar').removeClass('active');
//			jQuery(curItem).addClass('active');
//		}
//		return false;
//	})

	jQuery('.closeInnerSideBar').click(function () {
		jQuery('#user-main-wrap').removeClass('active')
		jQuery('.profile-links > li').removeClass('active')
		jQuery('.inner-sidebar').removeClass('active')
		return false;
	});


	/*card-pages
	 ========================*/
//	jQuery('.controls-list > li > a').click(function () {
//		if (jQuery(this).hasClass('active')) {
//			return false;
//		} else {
//			jQuery('.controls-list').toggleClass('active');
//		}
//		if (jQuery(this).parent().hasClass('active')) {
//			jQuery('.controls-list > li').removeClass('active');
//		} else {
//			jQuery('.controls-list > li').removeClass('active');
//			jQuery(this).parent('li').addClass('active');
//		}
//		return false;
//	});
	jQuery('body').click(function (e) {
		if ( e.target.id == ".select-folder" ) {
			return;
		}
		//For descendants of menu_content being clicked, remove this check if you do not want to put constraint on descendants.
		if ( jQuery(e.target).closest('.select-folder').length ) {
			return;
		}
		if ( jQuery(e.target).closest('.select-filter').length ) {
			return;
		}
		jQuery('.controls-list > li').removeClass('active');
	});
	jQuery('a.card-selector').click(function(){
//		jQuery('.controls-list > li').removeClass('active');
//		updateCheckbox();
		var total_cards = jQuery('.card-box').filter(':visible');
		if ( total_cards.length == 0 ) {
			jQuery('.commanCheckBox').prop('checked', false);
			jQuery('a.card-selector').removeClass('select-all');
			jQuery('.checkboxStyle').removeClass('active');
		} else {
			var checked_cards = total_cards.filter('.active');
			if ( checked_cards.length == 0 ) {
				jQuery('.checkboxStyle').removeClass('active');
				jQuery(this).removeClass('select-all');
			}else{
				jQuery('.checkboxStyle').addClass('active');
//				jQuery('.card-box').addClass('active');
			}
		}
		if ( jQuery(this).hasClass('select-all') ) {
			jQuery(this).removeClass('select-all');
			jQuery('.css-checkbox:not(.mg-checkbox)').prop("checked", false);
			jQuery('.card-box').removeClass('active');
		} else {
			jQuery(this).addClass('select-all');
			jQuery('.css-checkbox:not(.mg-checkbox)').prop("checked", true);
			jQuery('.card-box').addClass('active');
		}
//		return false;
	});
//	jQuery('.drop-down.cbx-selector a').click(function () {
//		jQuery('.controls-list > li').removeClass('active');
//		if ( jQuery(this).is('#allSelectedFolder') ) {
//			jQuery('.css-checkbox:not(.mg-checkbox)').prop("checked", true);
//			jQuery('.card-box').addClass('active')
//		} else {
//			jQuery('.css-checkbox:not(.mg-checkbox)').prop("checked", false);
//			jQuery('.card-box').removeClass('active')
//		}
//		return false;
//	});
	jQuery('#card-size-double').click(function () {
		if ( jQuery(this).parent().hasClass('active') ) {
			jQuery('.controls-list > li').removeClass('active');
		} else {
			jQuery('.controls-list > li').removeClass('active');
			jQuery(this).parent('li').addClass('active');
		}
		jQuery('.user-main-info').addClass('doubleCardSize')
		jQuery('.detail-table').hide();
		jQuery('.manage-folders').show();
		return false;
	});
	jQuery('#card-size-1').click(function () {
		if ( jQuery(this).parent().hasClass('active') ) {
			jQuery('.controls-list > li').removeClass('active');
		} else {
			jQuery('.controls-list > li').removeClass('active');
			jQuery(this).parent('li').addClass('active');
		}
		jQuery('.user-main-info').removeClass('doubleCardSize')
		jQuery('.detail-table').hide();
		jQuery('.manage-folders').show();
		return false;
	});

	jQuery('#viewCardDetail').click(function () {
		if ( jQuery(this).parent().hasClass('active') ) {
			jQuery('.controls-list > li').removeClass('active');
		} else {
			jQuery('.controls-list > li').removeClass('active');
			jQuery(this).parent('li').addClass('active');
		}
		jQuery('.manage-folders').hide();
		jQuery('.detail-table').show();
		return false;
	});

	/* card-box border on clicking checkbox */
	jQuery(document).on('click', '.my-cards .css-checkbox', function () {
		var total_cards = jQuery('.card-box').filter(':visible');
		if ( jQuery(this).prop("checked") == true ) {
			jQuery(this).parents('.card-box').addClass('active');
//			var checked_cards = jQuery('.card-box').filter('.active');
			var checked_cards = total_cards.filter('.active');
			console.log(checked_cards.length+' = '+total_cards.length+' at line 366');
			if (checked_cards.length == total_cards.length){
				jQuery('a.card-selector').addClass('select-all');	
				jQuery('.commanCheckBox').prop('checked', true);
				jQuery('.checkboxStyle').removeClass('active');
			}else{
				jQuery('a.card-selector').removeClass('select-all');	
				jQuery('.commanCheckBox').prop('checked', false);
				jQuery('.checkboxStyle').addClass('active');
			}
		} else {
			jQuery(this).parents('.card-box').removeClass('active')
			var checked_cards = jQuery('.card-box').filter('.active');
			console.log(checked_cards.length+' = '+total_cards.length+' at line 379');
			if (checked_cards.length == total_cards.length){
				jQuery('.commanCheckBox').prop('checked', true);
				jQuery('a.card-selector').addClass('select-all');
				jQuery('.checkboxStyle').removeClass('active');
			}else{
				jQuery('.commanCheckBox').prop('checked', false);
				jQuery('a.card-selector').removeClass('select-all');
				jQuery('.checkboxStyle').addClass('active');
			}
		}
		if ( !jQuery('.card-box').hasClass('active') ) {
			jQuery('.checkboxStyle').removeClass('active');
		}
	});

	jQuery('.checkboxStyle').click(function () {
		if ( jQuery(this).hasClass('active') ) {
			jQuery(this).removeClass('active')
			jQuery('.card-box').removeClass('active')
			jQuery('.css-checkbox').prop('checked', false);

		}
	});

	/* selected card-box border on page load */
	jQuery('.css-checkbox').each(function () {
		if ( jQuery(this).prop("checked") == true ) {
			jQuery(this).parents('.card-box').addClass('active');
			jQuery('.checkboxStyle').addClass('active')
		}
	});

	/*search popup pages
	 ========================*/

	jQuery('#searchCardLink a').click(function () {
		jQuery('#searchSecArea').show(400)
		jQuery('#searchCardsLayout').show(400)
		jQuery('body').css({
			'overflow': 'hidden',
		})
		jQuery('.text-input').focus()
		return false;
	})
	jQuery('#searchCardLink input').focus(function () {
		jQuery('#searchSecArea').show(400)
		jQuery('#searchCardsLayout').show(400)
		jQuery('body').css({
			'overflow': 'hidden',
		})
		jQuery('.text-input').focus()
		return false;
	})

	// jQuery('#singleSearchArea').click(function(){
	// 	jQuery('#searchSecArea').show(400)
	// 	jQuery('#singleCard').show(400)
	// 	jQuery('body').css({
	// 		'overflow': 'hidden',
	// 	})
	// 	return false;
	// })

	jQuery(document).on('click', '.closeSearchSec', function () {
		jQuery('#searchSecArea').hide(400)
		jQuery('.search-cards-layout').hide(400)
		jQuery('#singleCard').hide(400)
		jQuery('body').css({
			'overflow': 'visible'
		});
		jQuery('body').removeClass('active');
		jQuery('#searchCardLink input').blur();
		return false;
	});
	jQuery(document).on('click', '#selectFolder', function () {
		jQuery(this).parent().toggleClass('active');
		return false;
	});

	/* scrolling indicator
	 =====================*/
//	jQuery.fn.scrollEnd = function (callback, timeout) {
//		jQuery(this).scroll(function () {
//			var jQuerythis = jQuery(this);
//			if (jQuerythis.data('scrollTimeout')) {
//				clearTimeout(jQuerythis.data('scrollTimeout'));
//			}
//			jQuerythis.data('scrollTimeout', setTimeout(callback, timeout));
//		});
//	};

//	jQuery(window).scroll(function () {
//		jQuery('#scrollDownLink').stop().hide()
//	})

//	jQuery(window).scrollEnd(function () {
//	    jQuery('#scrollDownLink').stop().show()
//	}, 0);

	/*table-fix-head
	 ===================*/
//	jQuery('.outGoingTabLink').click(function () {
//		jQuery('.incoming-head').hide();
//		jQuery('.outgoing-head').show();
//	});
//	jQuery('.inComingTabLink').click(function () {
//		jQuery('.incoming-head').show();
//		jQuery('.outgoing-head').hide();
//	});

	/*card-rating
	 ------------------*/
	// if (jQuery('.rate').length > 0 ){
	//        var options = {
	//            max_value: 6,
	//            step_size: 0.5,
	//            selected_symbol_type: 'hearts',
	//            url: 'http://localhost/test.php',
	//            initial_value: 3,
	//        }
	// 	jQuery(".rate").rate();
	// 	jQuery(".rate").rate("setFace", 5, 'ðŸ˜Š');
	//        jQuery(".rate").rate("setFace", 1, 'ðŸ˜’');
	// }

	/*Custom scrollbar-sec
	 =====================*/
	if ( jQuery('.custom-select').length > 0 ) {
		jQuery('.custom-select').selectBoxIt();
	}
//	if (jQuery('.scrolling-area').length > 0 ){
//		alert('scroll');
//	    jQuery('.scrolling-area').mCustomScrollbar();
//	}
	if ( jQuery(".horizontal-scroll").length > 0 ) {
		jQuery(".horizontal-scroll").mCustomScrollbar({
			axis: "x",
			theme: "light-3",
			advanced: {autoExpandHorizontalScroll: true}
		});
	}
	// if (jQuery('.scrollingTable').length > 0 ){
	//    	var offset = jQuery('.title-folder').offset().top;
	//    		userHeader = jQuery('.user-header').height()
	//    		folderPages = jQuery('.folder-pages').height()
	//  			winHeight = jQuery(window).height();
	// 	jQuery(window).scroll(function(){
	//      		if( jQuery(this).scrollTop()+userHeader+folderPages > offset ){
	//        		jQuery('#user-main-wrap').addClass('tableOnScroll')
	//      		}else{
	//        		jQuery('#user-main-wrap').removeClass('tableOnScroll')
	//      		}
	// 	});
	// }

	/*input-functions-here
	 =========================*/
	jQuery('[type="email"], [type="text"], [type="password"], [type="tel"], textarea').focus(function () {
		var jQueryexistingType = jQuery(this).attr('placeholder');
		jQuery(this).attr('placeholder', '').blur(function () {
			jQuery(this).attr('placeholder', jQueryexistingType);
		});
	});

	jQuery('.mobileListBtn').click(function () {
		jQuery('.card-control-sec').slideToggle();
		jQuery('.manage-folders').toggleClass('active');
		jQuery(this).toggleClass('active');
	});

	/* 	var cursor;
	 
	 jQuery('.inputCursor').click(function() {
	 jQuery('.text-input').focus();
	 
	 cursor = window.setInterval(function() {
	 if (jQuery('#cursor').css('visibility') === 'visible') {
	 jQuery('#cursor').css({ visibility: 'hidden' });
	 } else {
	 jQuery('#cursor').css({ visibility: 'visible' });  
	 }  
	 }, 500);
	 
	 });
	 
	 jQuery('.text-input').keyup(function() {
	 jQuery('.inputCursor .span').text(jQuery(this).val());
	 
	 });
	 
	 
	 jQuery('.text-input').blur(function() {
	 clearInterval(cursor);
	 jQuery('#cursor').css({ visibility: 'visible' });
	 });
	 */

	/*
	 * ***************************************************************************** 
	 * ***************************************************************************** 
	 * *****************************************************************************
	 * ********************** INDEX.JS *********************************************
	 * ***************************************************************************** 
	 * ***************************************************************************** 
	 * ***************************************************************************** 
	 */
//	jQuery.validator.addMethod("pwcheck", function (value) {
//		return /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}jQuery/.test(value); // consists of only these
//	});
//	InitializeLoginValidation();
//	InitializeRegisterValidation();
//	jQuery('#full_name').val('');
//	jQuery('#register_email_address').val('');
//	jQuery('#register_password').val('');
//	jQuery('#full_name').focusout(function () {
//		jQuery('#full_name').valid();
//	});
//	jQuery('#register_email_address').focusout(function () {
//		jQuery('#register_email_address').valid();
//	});
//	jQuery('#register_password').focusout(function () {
//		jQuery('#register_password').valid();
//	});
//
//	jQuery("#frmLogin input").keypress(function (e) {
//		if (e.which == 13) {
//			jQuery("#login").click();
//		}
//	});
//	jQuery("#frmRegister input").keypress(function (e) {
//		if (e.which == 13) {
//			jQuery("#sign-up").click();
//		}
//	});
//	jQuery(document).on("click", "#login", function (e) {
//		e.preventDefault();
//		if (jQuery('#frmLogin').valid()) {
//			var email_address = jQuery('#login_email_address').val();
//			var password = jQuery('#login_password').val();
//			if (email_address === '' || password === '')
//			{
//				swal({
//					title: "Error!",
//					text: "Please, correct errors before to submit your data.",
//					html: true,
//					type: "error",
//					showCancelButton: false,
//					confirmButtonColor: "#ff6603"
//				});
//				return;
//			}
//			jQuery.ajax({
//				type: "POST",
//				url: "includes/home/login.php",
//				data: {
//					email_address: email_address,
//					password: password
//				},
//				success: function (data) {
//					var response = JSON.parse(data);
//					if (response.success) {
//						console.log(response);
//						if (!response.login_success) {
//							swal({
//								title: "Error!",
//								text: "Invlid combination of email address and password. Please, retry.",
//								html: true,
//								type: "error",
//								showCancelButton: false,
//								confirmButtonColor: "#ff6603"
//							});
//							return;
//						} else {
//							if (response.verified !== "1") {
//								swal({
//									title: "Login Failed!",
//									text: "Your account has not been verified yet! Follow the link sent via email to verify your account.",
//									html: true,
//									type: "error",
//									showCancelButton: false,
//									confirmButtonColor: "#ff6603"
//								});
//								return;
//							} else {
//								if (response.login_success && response.verified == "1") {
//									swal({
//										title: "Login Success!",
//										text: "You are logged in!",
//										html: true,
//										type: "success",
//										showCancelButton: false,
//										confirmButtonColor: "#ff6603"
//									});
//									return;
//									localStorage["first_name"] = response.first_name;
//									checkUserHasCard();
//								}
//							}
//						}
//					} else {
//						showErrorModal();
//					}
//				},
//				error: function (jqXHR, textStatus, errorThrown) {
//					//				l.stop();
//					//				showErrorModal();
//				}
//			});
//		}
//	});
//	jQuery(document).on("click", "#sign-up", function () {
//		if (jQuery('#frmRegister').valid()) {
//			var full_name = jQuery("#full_name").val();
//			var first_name = full_name.substr(0, full_name.indexOf(' '));
//			var last_name = full_name.substr(full_name.indexOf(' ') + 1);
//			if (last_name == "") {
//				var last_name = first_name;
//			}
//			var register_email_address = jQuery("#register_email_address").val();
//			var register_password = jQuery("#register_password").val();
//			jQuery('#register_email_address').valid();
//			jQuery('#register_password').valid();
//			if (jQuery('.form-wrapper').hasClass('error-shadow'))
//			{
//				swal({
//					title: "Error!",
//					text: "Please, correct errors before to submit your data.",
//					html: true,
//					type: "error",
//					showCancelButton: false,
//					confirmButtonColor: "#ff6603"
//				});
//				return;
//			}
//			console.log(first_name);
//			console.log(last_name);
//			jQuery.ajax({
//				type: "POST",
//				url: "includes/home/register.php",
//				data: {
//					first_name: first_name,
//					last_name: last_name,
//					register_email_address: register_email_address,
//					register_password: register_password,
//				},
//				success: function (data) {
//					var response = JSON.parse(data);
//					if (response.success) {
//						if (!response.email_exists) {
//							swal({
//								title: "Welcome!",
//								text: "You have been successfully registered. Please check your inbox for a confirmation email.",
//								html: true,
//								type: "success",
//								showCancelButton: false,
//								confirmButtonColor: "#ff6603"
//							});
//							closePopup();
//							return;
//						} else {
//							swal({
//								title: "Email already exists!",
//								text: "The email "+register_email_address+" you entered already exists on our system. Please enter your password, or choose to reset it if you have forgotten it.",
//								html: true,
//								type: "warning",
//								showCancelButton: false,
//								confirmButtonColor: "#ff6603"
//							});
//							return;
//						}
//					} else {
//						showErrorModal();
//					}
//				},
//				error: function (jqXHR, textStatus, errorThrown) {
//					swal({
//						title: "Error!",
//						text: "Error occurred: " + textStatus + ' - ' + errorThrown,
//						html: true,
//						type: "error",
//						showCancelButton: false,
//						confirmButtonColor: "#ff6603"
//					});
//					return;
//				}
//			});
//		}
//	});
//	jQuery(document).on("click", "#forgot_password", function () {
//		//if (jQuery('#frmLogin').valid()) {
////		var l = Ladda.create(document.querySelector("#forgot_password"));
//		jQuery.ajax({
//			type: "POST",
//			url: "includes/auth/reset_password.php",
//			data: {
//				email_address: jQuery("#email_address").val()
//			},
//			beforeSend: function () {
//				l.start();
//			},
//			success: function (data) {
//				l.stop();
//				var response = JSON.parse(data);
//				if (response.success) {
//					if (response.email_exists) {
//						showModalWithTitleAndMessage({
//							title: 'Email sent!',
//							message: "You'll receive an email shortly with detailed instructions to resetting your password!"
//						});
//					} else {
//						showModalWithTitleAndMessage({
//							title: 'Error',
//							message: 'The email address you entered has not been registered on our system before.'
//						});
//					}
//				} else {
//					showErrorModal();
//				}
//			},
//			error: function (jqXHR, textStatus, errorThrown) {
//				//stops spinner
//				console.log("closing");
//				setTimeout(function () {
//					jQuery.loader('close');
//				}, timeOut);
//				showErrorModal();
//			}
//		});
//		//}
//	});
//

	/*
	 * ***************************************************************************** 
	 * ***************************************************************************** 
	 * *****************************************************************************
	 * ********************** RECOVER_PASSWORD *********************************************
	 * ***************************************************************************** 
	 * ***************************************************************************** 
	 * ***************************************************************************** 
	 */
	jQuery('#recover_password').on('click', function () {
		jQuery('#frmRecover').submit();
	});

	jQuery(document).on('mouseenter', '#hlp_hidden_card', function () {
		jQuery('#hlp_hidden_card_tip').fadeIn();
	});

	jQuery(document).on('mouseleave', '#hlp_hidden_card', function () {
		jQuery('#hlp_hidden_card_tip').fadeOut();
	});

});/*end of jQuery*/

function closePopup(callback) {
	if ( callback === undefined ) {
		console.log('closePopup callback is undefined');
		callback = '';
	}
	if ( callback === '' ) {
		console.log('closePopup callback is empty');
		jQuery('#overLay').slideUp(function () {
			jQuery('body').removeClass('active');
			jQuery('.search-sec').css({'overflow-y': 'auto'});
		});
		jQuery('.overlay-info').slideUp('slow');
	} else {
		console.log('executing callback');
		callback();
	}
	return false;
}
;

function checkUserHasCard() {
	jQuery.ajax({
		type: "POST",
		url: "includes/card-link/c_check_reciprocity_user_has_card.php",
		success: function (data) {
			var response = JSON.parse(data);
			if ( parseInt(response.result) > 0 ) {
				window.location.href = globalURL + "home.php";
			} else {
				window.location.href = globalURL + "profile.php";
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
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
				pwcheck: '^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}jQuery'
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
