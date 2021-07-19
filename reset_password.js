jQuery(document).ready(function ($) {
	jQuery.validator.addMethod("pwcheck", function (value) {
		return /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/.test(value); // consists of only these
	});
	InitializeLoginValidation();
	jQuery('#new_password').focusout(function () {
		jQuery('#new_password').valid();
	});
	jQuery('#confirm_password').focusout(function () {
		jQuery('#confirm_password').valid();
	});
});
function InitializeLoginValidation() {
	jQuery('#frmReset').validate({
		rules: {
			new_password: {
				required: true,
				pwcheck: '^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$'
			},
			confirm_password: {
				required: true,
				equalTo: "#new_password"
			},
		},
		messages: {
			new_password: {
				required: "You must set a password.",
				pwcheck: 'Password must be 8 characters long with at leat 1 uppercase letter and 1 numeric value.'
			},
			confirm_password: {
				required: "You must retype your password.",
				equalTo: "The two passwords don't match."
			},
		},
		highlight: function (element) {
			jQuery(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function (element) {
			jQuery(element).closest('.form-group').removeClass('has-error');
		},
//		errorElement: 'div',
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
jQuery(document).on("click", "#password_modal_save", function () {
	if (jQuery('#frmReset').valid()) {
		jQuery.ajax({
			type: "POST",
			url: "includes/auth/save_new_password.php" + window.location.search,
			data: {
				password: jQuery("#new_password").val(),
			},
			success: function (data) {
				var response = JSON.parse(data);
				if (response.success) {
					if (response.reset_successful && response.token_exists) {
						swal({
							title: "Success!",
							text: "Your password was reset successfully! You can now login again.",
							html: true,
							type: "success",
							showCancelButton: false,
							confirmButtonColor: "#ff6603"
						},
						function(){
							window.location.href = globalURL + "index.php";
						});
					} else {
						swal({
							title: "Error!",
							text: "Your password could not be reset, you either follow the wrong link to reach this page, or something unexpected happened!",
							html: true,
							type: "error",
							showCancelButton: false,
							confirmButtonColor: "#ff6603"
						});
					}
				} else {
					showErrorModal();
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				showErrorModal();
			}
		});
	}
});

//jQuery(document).on("click", ".btn.btn-success", function () {
//	jQuery("#modal_message").modal("hide");
//	window.location.href = globalURL + "index.php";
//});