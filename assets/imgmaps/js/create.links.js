function createLinks(lk_email, email_width, email_height, email_x1, email_y1, email_x2, email_y2) {
	jQuery('#default_links').append(
					"<div class='linky transparent' "
					+ "style='"
					+ "position: absolute; "
					+ "left:" + email_x1 + "px; "
					+ "top:" + email_y1 + "px; "
					+ "height:" + email_height + "px; "
					+ "width:" + email_width + "px; "
					+ "background-color: white; "
					+ "opacity: 0.75; "
					+ "color: black; "
					+ "font-weight: bold; "
					+ "padding: 5px; "
					+ "font-size: 14px; "
					+ "word-wrap: break-word; "
					+ "line-height: 12px; "
					+ "' "
					+ "id='link-email' "
					+ "data-link-type='email' "
					+ "data-link-value='" + lk_email + "' "
					+ "data-link-coordinates='" + email_x1 + ", " + email_y1 + ", " + email_x2 + ", " + email_y2 + ""
					+ "'>"
					+ "<p>" + lk_email + "</p>"
					+ "</div>"
					);
//	RefreshImage2();
	console.log('coordinates: ' + email_x1 + ' ' + email_y1 + ' ' + email_x2 + ' ' + email_y2);
	setTimeout(function () {
		var ias = jQuery('#default_links').imgAreaSelect(email_x1, email_y1, email_x2, email_y2);
		ias.setOptions({hide: true});
		ias.update();
	}, 500);
}
//function RefreshImage2() {
//	var ias = jQuery('#default_links').imgAreaSelect({instance: true});
//	ias.setOptions({hide: true});
//	ias.update();
//}
function frmLinkTypeValid() {
	if ( jQuery('#frmLinkType').valid() ) {
		var target;
		if ( jQuery('.aboutCardposition').hasClass('active') ) {
			target = '#links-b';
		} else {
			target = '#links-f';
		}
		lastId = jQuery(target).children().last().attr('id');
		if ( lastId == null ) {
			lastId = 1;
		} else {
			lastId++;
		}
		enteredValue = null;
		linkType = null;
		switch (jQuery('#ddLinkType')[0].selectedIndex) {
			case 0:
				linkType = "url";
				enteredValue = jQuery('#url').val().replace('http://', '');
				break;
			case 1:
				linkType = "email";
				enteredValue = jQuery('#email').val();
				break;
			case 2:
				linkType = "phone";
				enteredValue = jQuery('#phone').val();
				break;
		}
		var bestId;
		if ( jQuery('.aboutCardposition').hasClass('active') ) {
			bestId = jQuery('#link-canvas-back').find('.linky').length + 2;
		} else {
			bestId = jQuery('#link-canvas').find('.linky').length + 2;
		}
		jQuery(target).append(
						"<div class='linky transparent' "
						+ "style='"
						+ "position: absolute; "
						+ "left:" + jQuery('#x1').val() + "px; "
						+ "top:" + jQuery('#y1').val() + "px; "
						+ "height:" + jQuery('#h').val() + "px; "
						+ "width:" + jQuery('#w').val() + "px; "
						+ "background-color: white; "
						+ "opacity: 0.75; "
						+ "color: black; "
						+ "font-weight: bold; "
						+ "padding: 5px; "
						+ "font-size: 14px; "
						+ "word-wrap: break-word; "
						+ "line-height: 12px; "
						+ "' "
						+ "id='link-" + bestId + "' "
						+ "data-link-type='" + linkType + "' "
						+ "data-link-value='" + enteredValue + "' "
						+ "data-link-coordinates='" + jQuery('#x1').val() + ", " + jQuery('#y1').val() + ", " + jQuery('#x2').val() + ", " + jQuery('#y2').val() + ""
						+ "'>"
						+ "<p>" + enteredValue + "</p>"
						+ "</div>"
						);
		RefreshImage();
		jQuery('#link-panel input[type="text"]').val('').trigger('blur');
	}
}
function ModalDisplayChoice(choice) {
	jQuery('#webUrl').hide();
	jQuery('#emailAddress').hide();
	jQuery('#phoneNumber').hide();
	switch (choice) {
		case 0:
			jQuery('#webUrl').show();
			break;
		case 1:
			jQuery('#emailAddress').show();
			break;
		case 2:
			jQuery('#phoneNumber').show();
			break;
	}
}
function CloseModal() {
	jQuery('#modalConfirm').modal('hide');
}
function RefreshImage() {
	jQuery('#areaSelected').val('');
//	jQuery('#modalCreateLink').modal('hide');
	if ( jQuery('.aboutCardposition').hasClass('active') ) {
		var ias = jQuery('#link-canvas-back').imgAreaSelect({instance: true});
	} else {
		var ias = jQuery('#link-canvas').imgAreaSelect({instance: true});
	}
	ias.setOptions({hide: true});
	ias.update();
}
function AddValidationRules() {
	jQuery('#frmLinkType').validate({
		rules: {
			email: {
				email: true,
				required: true
			},
			url: {
				forgiving_url: true,
				required: true
			},
		},
		highlight: function (element) {
			jQuery(element).closest('.input-group').addClass('has-error');
		},
		unhighlight: function (element) {
			jQuery(element).closest('.input-group').removeClass('has-error');
		},
		errorElement: 'span',
		errorClass: 'help-block',
		errorPlacement: function (error, element) {
			if ( element.parent('.input-group').length ) {
				error.insertAfter(element.parent());
			} else {
				error.insertAfter(element);
			}
		}
	});
}
jQuery(document).ready(function (jQuery) {
	jQuery.validator.addMethod("forgiving_url", function (value, element) {
		//bit more forgiving regex than one below 
		//var urlValidator = /(\S+\.[^/\s]+(\/\S+|\/|))/g;
		var urlValidator = /(^|\s)((https?:\/\/)?[\w-]+(\.[\w-]+)+\.?(:\d+)?(\/\S*)?)/gi;
		var val = value;
		if ( urlValidator.test(val) ) {
			console.log("First Test", val);
			return true;
		} else if ( urlValidator.test("http://" + val) ) {
			console.log("Second Test", "http://" + val);
			return true;
		} else {
			console.log("Not a valid url", val, "http://" + val);
			return false;
		}
	}, 'Please check if you specified a valid url');
	AddValidationRules();
});
/*
 called when selection changes,
 image object and selection coordination will be
 passed as arguments.
 */
function preview(img, selection) {
	/*
	 check if selection is made
	 */
	if ( !selection.width || !selection.height )
		return;
	/*
	 setting value for coordinator input fields.
	 */
	jQuery('#areaSelected').val('areaSelected');
	jQuery('#x1').val(selection.x1);
	jQuery('#y1').val(selection.y1);
	jQuery('#x2').val(selection.x2);
	jQuery('#y2').val(selection.y2);
	jQuery('#w').val(selection.width);
	jQuery('#h').val(selection.height);
}
jQuery(function () {
	jQuery('#link-canvas').imgAreaSelect(
					{
						handles: true,
						fadeSpeed: 200,
						onSelectChange: preview,
						parent: '#link-canvas'
					});
	jQuery('#link-canvas-back').imgAreaSelect(
					{
						handles: true,
						fadeSpeed: 200,
						onSelectChange: preview,
						parent: '#link-canvas-back'
					});
});
