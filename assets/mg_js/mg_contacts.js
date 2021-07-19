
/*******************************************************************************
 * 
 *											SHARE MY OWN CARDS
 * 
 *******************************************************************************/
function shareThisCard(card_id, card_type) {
//	console.log('editing card settings');
	card_type = card_type.toLowerCase();
//	console.log(frmSelector);
	jQuery.ajax({
		type: "GET",
		url: 'includes/mg_get_card_detail_for_sharing.php',
		data: {card_id: card_id},
		success: function (data) {
			console.log(data);
			var card = JSON.parse(data);
			var myclass;
			if ( card.orientation === 'landscape' ) {
				myclass = 'img-flip aboutCardposition';
			} else {
				myclass = 'img-flip aboutCardposition portrait';
			}
			jQuery('#img-flipper').addClass(myclass);
			jQuery('#new-card-data img').attr('src', card.canvas_front);
			jQuery('#new-card-data').attr('data-card-id', card_id);
			jQuery('#new-card-data').attr('data-card-owner', card.card_owner);
			jQuery('#back-side-content-id2 img').attr('src', card.canvas_back);
			jQuery('#new-card-name').text('"'+card.card_name+'"');
			jQuery('#new-card-bcn').text(card.bcn);
			jQuery('body').addClass('active');
			jQuery('#overLay').slideDown();
			jQuery('#card-sharing-panel').slideDown();
			jQuery("html, body").animate({scrollTop: 0}, "slow");
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.log(textStatus, errorThrown);
		}
	});
}

function getGmailContacts() {
	var config = {
//			'client_id': '136751526115-a1afot2u2q7h00lrj1q0il0rheeec0eu.apps.googleusercontent.com',
		'client_id': '136751526115-a1afot2u2q7h00lrj1q0il0rheeec0eu.apps.googleusercontent.com',
		'scope': 'https://www.google.com/m8/feeds https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.profile'
	};
	gapi.auth.authorize(config, function () {
		fetch(gapi.auth.getToken());
	});
}

function fetch(token) {
//						jQuery.ajax({
//							type: 'get',
//							url: 'https://www.googleapis.com/plus/v1/people/me?fields=image&key=AIzaSyC3Bw6zOAmw_ifGG83F5Xqpp8CxT4dZUrE',
////							url: 'https://www.googleapis.com/auth/plus.me?fields=image&key=AIzaSyC3Bw6zOAmw_ifGG83F5Xqpp8CxT4dZUrE',
//							success: function (result)
//							{
//								console.log(result);
//							},
//							error: function (result)
//							{
//								jQuery('div').html(result).fadeIn('slow');
//							}
//						});
	var listOwner;
	listOwner = 'Your contacts from Gmail';
	jQuery('#list-owner').text(listOwner);
	jQuery('#sharing-mail-contacts').slideDown();
	jQuery.ajax({
		url: "https://www.google.com/m8/feeds/contacts/default/full?access_token=" + token.access_token + "&max-results=1000&alt=json",
		dataType: "jsonp",
		success: function (contacts) {
//			console.log(JSON.stringify(contacts));
			console.log(contacts);
			// display all your data in console
			if ( contacts['feed']['entry'] !== '' ) {
				var profileImageUrl, emailOwner, emailAddress;
//				listOwner = contacts['feed']['title']['$t'] + ' from Gmail';
//									if ( contacts['feed']['link'][1]['href'] != null ) {
//										profileImageUrl = contacts['feed']['link'][1]['href'] + '?v=3.0&access_token=' + token.access_token;
//										jQuery.ajax({
//											type: 'get',
//											url: profileImageUrl,
//											success: function (result)
//											{
//												console.log(result);
//											},
//										});
//									}
				var email_array = [];
				for ( var i = 0;i < contacts['feed']['entry'].length;i++ ) {
					if ( contacts['feed']['entry'][i]['gd$email'] != null ) {
						emailAddress = contacts['feed']['entry'][i]['gd$email'][0]['address'].toLowerCase();
						if ( contacts['feed']['entry'][i]['title']['$t'] != null &&
										contacts['feed']['entry'][i]['title']['$t'] != '' ) {
							emailOwner = contacts['feed']['entry'][i]['title']['$t'];
						} else {
							emailOwner = '';
						}
						email_array.push(emailAddress + '#' + emailOwner);
					}
				}
				email_array.sort();
				var first_letter;
				for ( var i = 0;i < email_array.length;i++ ) {
					var els = email_array[i].split('#');
					var new_first_letter = els[0].substring(0, 1).toLowerCase();
					if ( new_first_letter != first_letter )
					{
						first_letter = new_first_letter;
						var html = '<li class="contacts" id="' + first_letter + '"><input name="checkboxG5" id="checkbox' + i + '" value="' + els[0] + '" class="css-checkbox" type="checkbox"><label for="checkbox' + i + '" class="css-label"><span class="contact-address">' + els[0] + '</span> <span class="contact-name">(' + els[1] + ')</span></label></li>';
						jQuery('#mail-contacts').append(html);
					} else {
						var html = '<li class="contacts"><input name="checkboxG5" id="checkbox' + i + '" value="' + els[0] + '" class="css-checkbox" type="checkbox"><label for="checkbox' + i + '" class="css-label"><span class="contact-address">' + els[0] + '</span> <span class="contact-name">(' + els[1] + ')</span></label></li>';
						jQuery('#mail-contacts').append(html);
					}
				}
				jQuery('#mail-contacts li').each(function () {
					if ( jQuery(this).html() === '' ) {
						jQuery(this).remove();
					}
				});
				jQuery('#contacts-wrapper').mCustomScrollbar();
			}
		}
	});
}

function scrollContactList(value) {
////	console.log('listScroller keyup');
//	var str_len = value.length;
////	console.log('str_len '+str_len);
//	jQuery('#mail-contacts li').each(function(){
//		var txt = jQuery(this).text();
//		console.log('txt ' + txt);
//		var sub_str = txt.substring(0, str_len);
//		console.log('substring '+sub_str);
//		console.log('value '+value);
//		if (sub_str === value){
//			var index = jQuery(this).index;
//			console.log('index '+index);
//			jQuery('#mail-contacts').animate({
//				scrollTop: $('#mail-contacts li:nth-child('+index+')').position().top
//			}, 'slow');
//			return false;
//		}
//	});
}

function closeContacts() {
	jQuery('#mail-contacts').children().remove();
//	jQuery('#new-card-data img').attr('src', '');
//	jQuery('#back-side-content-id2 img').attr('src', '');
	jQuery('#sharing-mail-contacts').fadeOut();
}

function getOutlookContacts() {
	WL.init({
		client_id: '000000004C18CE0B',
		redirect_uri: 'http://www.cardition.com/my-own-cards.php',
		scope: ["wl.basic", "wl.contacts_emails"],
		response_type: "token"
	});
	WL.login({
//			scope: ["wl.basic", "wl.contacts_emails"]
		scope: ["wl.signin", "wl.basic", "wl.birthday", "wl.emails"]
	}).then(function (response)
	{
		var listOwner;
		listOwner = 'Your contacts from Outlook';
		jQuery('#list-owner').text(listOwner);
		jQuery('#sharing-mail-contacts').slideDown();
		WL.api({
			path: "me/contacts",
			method: "GET"
		}).then(function (response) {
			var profileImageUrl, emailOwner, emailAddress;
			var email_array = [];
			for ( var i = 0;i < response.data.length;i++ ) {
				if ( response.data[i].emails.preferred ) {
					emailAddress = response.data[i].emails.preferred.toLowerCase();
				}
				if ( response.data[i].first_name ) {
					emailOwner = response.data[i].first_name;
				}
				if ( response.data[i].last_name ) {
					emailOwner += ' ' + response.data[i].last_name;
				}
				email_array.push(emailAddress + '#' + emailOwner);
			}
			email_array.sort();
			var first_letter;
			for ( var i = 0;i < email_array.length;i++ ) {
				var els = email_array[i].split('#');
				var new_first_letter = els[0].substring(0, 1).toLowerCase();
				if ( new_first_letter != first_letter )
				{
					first_letter = new_first_letter;
					var html = '<li class="contacts" id="' + first_letter + '"><input name="checkboxG5" id="checkbox' + i + '" value="' + els[0] + '" class="css-checkbox" type="checkbox"><label for="checkbox' + i + '" class="css-label"><span class="contact-address">' + els[0] + '</span> <span class="contact-name">(' + els[1] + ')</span></label></li>';
//					console.log(html);
//					if ( html !== '' ) {
					jQuery('#mail-contacts').append(html);
//					}
				} else {
					var html = '<li class="contacts"><input name="checkboxG5" id="checkbox' + i + '" value="' + els[0] + '" class="css-checkbox" type="checkbox"><label for="checkbox' + i + '" class="css-label"><span class="contact-address">' + els[0] + '</span> <span class="contact-name">(' + els[1] + ')</span></label></li>';
//					console.log(html);
//					if ( html !== '' ) {
					jQuery('#mail-contacts').append(html);
//					}
				}
			}
			jQuery('#mail-contacts li').each(function () {
				if ( jQuery(this).html() === '' ) {
					jQuery(this).remove();
				}
			});
			jQuery('#contacts-wrapper').mCustomScrollbar();
		},
						function (responseFailed) {
							console.log(responseFailed);
						});
	},
					function (responseFailed) {
						console.log("Error signing in: " + responseFailed.error_description);
					});
}

function setContacts() {
	jQuery('#email-wrapper').show();
	jQuery('#alphabet-nav').hide();
	jQuery('#mail-contacts').children().remove();
	var listOwner;
	listOwner = 'Your contacts';
	jQuery('#list-owner').text(listOwner);
	jQuery('#sharing-mail-contacts').slideDown();
	jQuery('#new_email_address').focus();
}

function shareCard() {
	var emails = [];
	if ( jQuery('#list-owner').text() === 'Your contacts' ) {
		jQuery('#mail-contacts li').each(function () {
			emails.push(jQuery(this).text());
		});
	} else {
		jQuery('#mail-contacts li input').filter(':checked').each(function () {
			emails.push(jQuery(this).val());
		});
	}
	var email_addresses = emails.join(',');
	var card_owner = jQuery('#new-card-data').attr('data-card-owner');
	var card_id = jQuery('#new-card-data').attr('data-card-id');
	var card_bcn = jQuery('#new-card-bcn').text();
	var card_name = jQuery('#new-card-name').text();
	var card_image = jQuery('#new-card-data img').attr('src');
	var card_image_back = jQuery('#back-side-content-id2 img').attr('src');
	var img_flip_class = jQuery('#img-flipper').attr('class');
	var msg = '';
	if ( jQuery('#list-owner').text() === 'Your contacts' ) {
		msg = "Your email list is empty! Please type at least an email address.";
	} else {
		msg = "Select at least one email address to share with!";
	}

	if ( email_addresses == null || email_addresses == '' ) {
		swal({
			title: "Warning!",
			text: msg,
			html: true,
			type: "warning",
			showCancelButton: false,
			confirmButtonColor: "#ff6603",
			confirmButtonText: "Ok",
			closeOnConfirm: true
		});
		return;
	}
	loaderText("Sharing your card with selected contact(s)...");
	loader.show();
	jQuery.ajax({
		type: 'post',
		url: 'includes/mg_sharing_send_email.php',
		data: {
			email_addresses: email_addresses,
			card_id: card_id,
			card_bcn: card_bcn,
			card_name: card_name,
			card_image: card_image,
			card_image_back: card_image_back,
			card_owner: card_owner,
			img_flip_class: img_flip_class
		},
		error: function (result)
		{
			loader.hide();
			console.log('Server error sending sharing email');
		}
	});
	loader.hide();
	swal({
		title: "Card shared",
		text: "You shared your card with all selected contacts.",
		html: true,
		type: "info",
		showCancelButton: false,
		confirmButtonColor: "#ff6603",
		confirmButtonText: "Ok",
		closeOnConfirm: true
	}, function (isClosed) {
		if ( isClosed ) {
			jQuery('#mail-contacts li input').filter(':checked').each(function () {
				jQuery(this).prop('checked', false);
			});
			closeContacts();
		}
	});
}

/*
  * FUNCTION CALLED BY POPUP IN FOLDER_CONTENT AFTER USER HAS SELECTED A CARD AND CLICKED THE SHARE ICON
 */
function shareCardWithContacts(){
	var emails = [];
	jQuery('#mail-contacts li input').filter(':checked').each(function () {
		emails.push(jQuery(this).val());
	});
	var date_string = getDateTime();
	var emails_string = emails.join();
	var card_ids = jQuery('#card_ids').val();
	jQuery.ajax({
		type: 'post',
		url: 'includes/mg_sharing_send_email_bc_contacts.php',
		data: {card_ids: card_ids, emails: emails_string, date_string: date_string},
		success: function (result) 
		{
			
		},
		error: function (result)
		{
			console.log('error sending email to contacts');
		}
	});
	swal({
		title: "Card shared",
		text: "Card(s) successfully shared!",
		html: true,
		type: "info",
		showCancelButton: false,
		confirmButtonColor: "#ff6603",
		confirmButtonText: "Ok",
		closeOnConfirm: true		
	}, function (isClosed){
		if (isClosed){
			jQuery('#mail-contacts').children().remove();
			closePopup();
		}
	});
}

jQuery(document).ready(function () {

	jQuery(document).on('click', '#mail-contacts li span a.delete', function(e){
		e.preventDefault();
//		var item = jQuery(this).parent().parent();
//		var index = jQuery('li').index(item);
		var index = jQuery(this).parents('li').index();
		console.log(index);
		jQuery('#mail-contacts li').eq(index).remove();
	});
	
	jQuery(document).on('keypress', '#new_email_address', function (e) {
		var key = e.which;
		// e.preventDefault();
		if ( key === 13 )  // the enter key code
		{
			var email_address = jQuery("#new_email_address").val( );
			jQuery("#new_email_address").val('');
			jQuery('#mail-contacts').append('<li>' + email_address + '<span style="margin-top: 10px; float: right;"><a href="#" class="delete"></a></span></li>');
			return false;
		}
	});
	
	jQuery(document).on('click', '#add-email', function(){
		var email_address = jQuery("#new_email_address").val( );
		jQuery("#new_email_address").val('');
		jQuery('#mail-contacts').append('<li>' + email_address + '<span style="margin-top: 10px; float: right;"><a href="#" class="delete"></a></span></li>');
		return false;
	});

	jQuery('#list-scroller').on('keyup', function () {
//		var value = jQuery(this).val();
//		scrollContactList(value);
	});

	jQuery('.alpha').on('click', function (e) {
		e.preventDefault();
		var letter = jQuery(this).text().toLowerCase();
		var container = jQuery('#contacts-wrapper');
		var scrollTo = jQuery("li#" + letter);
		console.log('container top ' + container.offset().top);
		console.log('scroll top ' + scrollTo.offset().top);
		container.animate({scrollTop: scrollTo.offset().top - container.offset().top + container.scrollTop()});
		container.mCustomScrollbar('scrollTo', scrollTo.offset().top - container.offset().top);
	});

	/*
	 * RETRIEVES CONTACTS FROM THE DATABASE AND OPENS THE POPUP TO ALLOW USER TO SELECT USER TO SHARE CARD WITH
	 */
	jQuery('#share_selected_cards').on('click', function () {
		var folder_id = jQuery('#page-title').data('folder-id');
		var card_ids = [];
		jQuery('.my-cards .card-box input.css-checkbox:checked').each(function ( ) {
			card_ids.push(jQuery(this).attr('id').substr(4));
		});
		if ( card_ids.length === 0 ) {
			swal({
				title: "Warning!",
				text: "Please select at least one card, please.",
				html: true,
				type: "warning",
				showCancelButton: false,
				confirmButtonColor: "#ff6603",
				confirmButtonText: "Ok",
				closeOnConfirm: true
			});
			return;
		} else {
			card_ids_string = card_ids.join();
			console.log(card_ids_string);
			loaderText('Retrieving your Cardition contacts...');
			loader.show();
			console.log('starting ajax');
			jQuery.ajax({
				type: 'post',
				url: 'includes/mg_sharing_with_contacts.php',
				data: {card_ids: card_ids_string},
				success: function (result) {
					var response = JSON.parse(result);
					if (response.outcome == 'no_share')
					{
						loader.hide();
						var bcns = response.bcns.join(', ');
						swal({
							title: "Warning!",
							text: "Sorry, following cards cannot be shared: BCN: "+ bcns+"<br>Please, uncheck them and retry.",
							html: true,
							type: "warning",
							showCancelButton: false,
							confirmButtonColor: "#ff6603",
							confirmButtonText: "Ok",
							closeOnConfirm: true		
						});
						return;
					}
					console.log('returning from ajax');
					var first_letter;
					var contacts = response.contacts;
//					console.log('contacts');
//					console.log(contacts);
					jQuery.each(contacts, function (i, contact) {
						var new_first_letter = contact.user_name.substring(0, 1).toLowerCase();
						if ( new_first_letter != first_letter )
						{
							first_letter = new_first_letter;
							var html = '<li class="contact" id="' + first_letter + '"><input name="checkboxG5" id="checkbox' + i + '" value="' + contact.email_address + '" class="css-checkbox" type="checkbox"><label for="checkbox' + i + '" class="css-label"><span class="profile_xsmall"><img class="img-circle" src="' + contact.profile_image + '" /></span> <span class="contact-name">' + contact.user_name + '</span> <span class="contact-address">(' + contact.email_address + ')</span></label></li>';
							jQuery('#mail-contacts').append(html);
						} else {
							var html = '<li class="contact"><input name="checkboxG5" id="checkbox' + i + '" value="' + contact.email_address + '" class="css-checkbox" type="checkbox"><label for="checkbox' + i + '" class="css-label"><span class="profile_xsmall"><img class="img-circle" src="' + contact.profile_image + '" /></span> <span class="contact-name">' + contact.user_name + '</span> <span class="contact-address">(' + contact.email_address + ')</span></label></li>';
							jQuery('#mail-contacts').append(html);
						}
					});
					jQuery('#card_ids').val(card_ids_string);
					loader.hide();
					jQuery('body').addClass('active');
					jQuery('#overLay').slideDown();
					jQuery('#share-with-contacts').slideDown();
					jQuery("html, body").animate({scrollTop: 0}, "slow");
					jQuery('#contacts-wrapper').mCustomScrollbar();
				},
				error: function (result)
				{
					console.log('error');
					loader.hide();
				}
			});
		}
	});

	jQuery(document).on('click', '#cancel-share-card-with', function ( ) {
		jQuery('#share-with-contacts #mail-contacts').children().remove();
		closePopup( );
	});

});

/*******************************************************************************
 * 
 *											END SHARE MY OWN CARDS
 * 
 *******************************************************************************/


