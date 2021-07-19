jQuery(document).ready(function () {
	PersonalInfoAddValidationRules();
//	PersonalAddressAddValidationRules();
	CompanyAddressAddValidationRules();
	UpdatePasswordAddValidation();
	//initFileUploads();
	check_profile_status();
});
function validationAbstraction(formSelector, rules) {
	jQuery(formSelector).validate({
		rules: rules,
		highlight: function (element) {
			jQuery(element).closest('.input-group').addClass('has-error');
		},
		unhighlight: function (element) {
			jQuery(element).closest('.input-group').removeClass('has-error');
		},
		errorElement: 'span',
		errorClass: 'help-block',
		errorPlacement: function (error, element) {
			if (element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else {
				error.insertAfter(element);
			}
		}
	});
}
function UpdatePasswordAddValidation() {
	validationAbstraction('#frmUpdatePassword', {
		new_confirm_password: {
			required: true,
			equalTo: "#new_password",
			minlength: 5
		}
	});
}
function PersonalInfoAddValidationRules() {
	validationAbstraction('#frmPersonalInfo', {
		email_address: {
			email: true,
			required: true
		},
		last_name: {
			required: true,
			minlength: 2
		},
		first_name: {
			required: true,
			minlength: 2
		},
		personal_country: {
			required: true
		}
	});
}
function CompanyAddressAddValidationRules() {
	validationAbstraction('#frmCompanyInfo', {
		company_country: {
			required: true
		},
		company_email_address: {
			email: true,
			required: true
		}
	});
}
jQuery(document).on('blur', '#personal_country', function () {
	jQuery('#personal_countrySelectBoxItContainer').closest('.form-group').removeClass('has-error').children('label.error').remove();
});
jQuery(document).on("click", "#save-personal-info", function (e) {
	e.preventDefault();
	if (jQuery('#personal_country').val() === '') {
		jQuery('#personal_countrySelectBoxItContainer').closest('.form-group').addClass('has-error').append('<label class="error" style="display: block !important;">Please, select your country</label>');
		return;
	}
	if (jQuery('#frmPersonalInfo').valid()) {
		jQuery.ajax({
			type: "POST",
			url: "includes/profile/save_profile.php",
			data: {
				to: 'personal',
				title: jQuery('#ddTitle').find(':selected').text(),
				last_name: jQuery("#last_name").val(),
				first_name: jQuery("#first_name").val(),
				phone: jQuery("#phone").val(),
				mobile: jQuery("#mobile").val(),
				email_address: jQuery("#email_address").val(),
				website_link: jQuery("#website_link").val(),
				personal_address_1: jQuery("#personal_address_1").val(),
				personal_address_2: jQuery("#personal_address_2").val(),
				personal_city: jQuery("#personal_city").val(),
				personal_country: jQuery("#personal_country").val(),
				personal_post_code: jQuery("#personal_post_code").val()
			},
			success: function (data) {
				//stops spinner
				console.log("closing");
				console.log(data);
				if (data == "success") {
					swal({
						title: "Personal Details Updated",
						text: "Your profile has been updated successfully.",
						html: true,
						type: "success",
						showCancelButton: false,
						confirmButtonColor: "#ff6603"
					});
					check_profile_status();
				} else if (data == "exists") {
					swal({
						title: "Email In Use",
						text: "This email address is already in use by another user.",
						html: true,
						type: "warning",
						showCancelButton: false,
						confirmButtonColor: "#ff6603"
					});
				} else {
					swal({
						title: "Update Failed",
						text: "An error has occurred. Please try again.",
						html: true,
						type: "error",
						showCancelButton: false,
						confirmButtonColor: "#ff6603"
					});
				}
			}
		});
	}
});
jQuery(document).on("click", "#save-company-info", function (e) {
	e.preventDefault();
	if (jQuery('#frmCompanyInfo').valid()) {
		jQuery.ajax({
			type: "POST",
			url: "includes/profile/save_profile.php",
			data: {
				to: 'company',
				company_name: jQuery("#company_name").val(),
				department_name: jQuery("#department_name").val(),
				position: jQuery("#position").val(),
				corporate_code: jQuery("#corporate_code").val(),
				company_phone: jQuery("#company_phone").val(),
				company_mobile: jQuery("#company_mobile").val(),
				company_email_address: jQuery("#company_email_address").val(),
				company_website_link: jQuery("#company_website_link").val(),
				company_address_1: jQuery("#company_address_1").val(),
				company_address_2: jQuery("#company_address_2").val(),
				company_city: jQuery("#company_city").val(),
				company_country: jQuery("#company_country").val(),
				company_post_code: jQuery("#company_post_code").val()
			},
			success: function (data) {
				console.log(data);
				if (data == "success") {
					console.log(data);
					swal({
						title: "Company Details Updated",
						text: "Your profile has been updated successfully.",
						html: true,
						type: "success",
						showCancelButton: false,
						confirmButtonColor: "#ff6603"
					});
				} else if (data == "exists") {
					console.log(data);
					swal({
						title: "Email In Use",
						text: "This email address is already in use by another user.",
						html: true,
						type: "warning",
						showCancelButton: false,
						confirmButtonColor: "#ff6603"
					});
				} else {
					console.log(data);
					swal({
						title: "Update Failed",
						text: "An error has occurred. Please try again.",
						html: true,
						type: "error",
						showCancelButton: false,
						confirmButtonColor: "#ff6603"
					});
				}
			}
		});
	}
});
jQuery(document).on("click", "#update-password", function () {
	if (jQuery('#frmUpdatePassword').valid()) {
		jQuery.ajax({
			type: "POST",
			url: "includes/profile/update_password.php",
			data: {
				password: jQuery("#new_password").val(),
			},
			success: function (data) {
				jQuery("#new_password").val('');
				jQuery("#new_confirm_password").val('');
				if (data == "success") {
					swal({
						title: "Password Update Successful",
						text: "Your password has been updated successfully.",
						html: true,
						type: "success",
						showCancelButton: false,
						confirmButtonColor: "#ff6603"
					});
				} else {
					swal({
						title: "Password Update Failed",
						text: "An error has occurred. Please try again.",
						html: true,
						type: "error",
						showCancelButton: false,
						confirmButtonColor: "#ff6603"
					});
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				swal({
					title: "Server error",
					text: "Can't communicate with the server.",
					html: true,
					type: "error",
					showCancelButton: false,
					confirmButtonColor: "#ff6603"
				});
			}
		});
	}
});
jQuery(document).on("click", "#upload-pic", function (e) {
	e.preventDefault();
	jQuery('#profile-pic-uploader').show();
});
function fileUpload(form, action_url, div_id) {
	//limit size of image upload
	/*var canContinue = upLoadLimiter(400, '#inputFile');
	 if (!canContinue) {
	 return;
	 };*/
	var picture = jQuery('#inputFile').val();
	if (picture === '') {
		return;
	}
	var extension = picture.split('.').pop();
	// Create the iframe...
	var iframe = document.createElement("iframe");
	iframe.setAttribute("id", "upload_iframe");
	iframe.setAttribute("name", "upload_iframe");
	iframe.setAttribute("width", "0");
	iframe.setAttribute("height", "0");
	iframe.setAttribute("border", "0");
	iframe.setAttribute("style", "width: 0; height: 0; border: none;");
	// Add to document...
	form.parentNode.appendChild(iframe);
	window.frames['upload_iframe'].name = "upload_iframe";
	iframeId = document.getElementById("upload_iframe");
	// Add event...
	var eventHandler = function () {
		if (iframeId.detachEvent)
			iframeId.detachEvent("onload", eventHandler);
		else
			iframeId.removeEventListener("load", eventHandler, false);
		// Message from server...
		if (iframeId.contentDocument) {
			content = iframeId.contentDocument.body.innerHTML;
		} else if (iframeId.contentWindow) {
			content = iframeId.contentWindow.document.body.innerHTML;
		} else if (iframeId.document) {
			content = iframeId.document.body.innerHTML;
		}
		document.getElementById(div_id).innerHTML = "";
		var d = new Date();
		console.log('<img src="' + jQuery.trim(content) + '?' + d.getTime() + '" alt="" />');
//		jQuery("#profile_small").children().remove();
//		jQuery("#profile_big").children().remove();
//		jQuery("#profile_small").html('<img src="' + jQuery.trim(content) + '?' + d.getTime() + '" alt="" />');
//		jQuery("#profile_big").html('<img src="' + jQuery.trim(content) + '?' + d.getTime() + '" alt="" />');
		jQuery("#profile_small img").attr('src', jQuery.trim(content) + '?' + d.getTime());
		jQuery("#profile_big img").attr('src', jQuery.trim(content) + '?' + d.getTime());
		// iFrame does not exist at this point, this piece of code causes IE errors
		//setTimeout('iframeId.parentNode.removeChild(iframeId)', 1000);
	};
	if (iframeId.addEventListener)
		iframeId.addEventListener("load", eventHandler, true);
	if (iframeId.attachEvent)
		iframeId.attachEvent("onload", eventHandler);
	// Set properties of form...
	form.setAttribute("target", "upload_iframe");
	form.setAttribute("action", action_url);
	form.setAttribute("method", "post");
	form.setAttribute("enctype", "multipart/form-data");
	form.setAttribute("encoding", "multipart/form-data");
	// Submit the form...
	form.submit();
	document.getElementById(div_id).innerHTML = "Uploading...";
	jQuery(".submit-upload").addClass('disabled');
}
jQuery('.file-picker-button').click(function () {
	jQuery("input[type='file']").trigger('click');
});
//	jQuery('#inputFile').on('change', function (e) {
//		e.preventDefault();
//		if ( this.files[0].size > 500 ) {
//			swal({
//				title: "Warning!",
//				text: "The image size is greater than 500Kb! Please select another image.",
//				html: true,
//				type: "warning",
//				showCancelButton: false,
//				confirmButtonColor: "#ff6603",
//				confirmButtonText: "Ok",
//				closeOnConfirm: true
//			});
//			return;
//		}
////		var picture = jQuery('#inputFile').val();
////		if ( picture !== '' )
////		{
//////		alert(' add disabled');
////			jQuery(".upload-background").removeClass('disabled');
////		} else {
//////		alert(' removing disabled');
////			jQuery(".upload-background").addClass('disabled');
////		}
//	});
jQuery('#inputFile').on('change', function (e) {
	e.preventDefault();
	if (this.files[0].size > 500000) {
		swal({
			title: "Warning!",
			text: "The image size is greater than 500Kb. Please select another image.",
			html: true,
			type: "warning",
			showCancelButton: false,
			confirmButtonColor: "#ff6603",
			confirmButtonText: "Ok",
			closeOnConfirm: true
		});
		jQuery(".submit-upload").addClass('disabled');
		return;
	}
	var picture = jQuery('#inputFile').val();
	if (picture !== '')
	{
//		alert(' add disabled');
		jQuery(".submit-upload").removeClass('disabled');
	} else {
//		alert(' removing disabled');
		jQuery(".submit-upload").addClass('disabled');
	}
});
jQuery('#go_to_company_info').on('click', function (e) {
//	jQuery('#save_profile').trigger('click');
	e.preventDefault();
	window.location.href = "company-info.php";
});
jQuery('#back_to_personal_info').on('click', function (e) {
//	jQuery('#save_profile').trigger('click');
	e.preventDefault();
	window.location.href = "personal-info.php";
});
function check_profile_status() {
	console.log('entering check_profie_status');
	jQuery.ajax({
		type: 'post',
		url: 'includes/profile/check_profile_status.php',
		success: function (result)
		{
			console.log('check profile status ' + result);
			if (result == 'true')
			{
				jQuery('.sidebar-info .business-card').removeClass('disabled');
				jQuery('.profile-form .create-business-card a').removeClass('disabled');
				setTimeout(function () {
					jQuery('#disabling-overlay').hide();
				}, 600);
				console.log('result true is ' + result);
				return true;

			} else if (result == 'false')
			{
				jQuery('.sidebar-info .business-card').addClass('disabled');
				jQuery('.profile-form .create-business-card a').addClass('disabled');
				setTimeout(function () {
					jQuery('#disabling-overlay').show();
				}, 600);
				console.log('result false is ' + result);
				return false;

			}else if (result == 'tologin'){
				return true;
			}
		}
	});
}
;
