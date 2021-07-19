Stripe.setPublishableKey('pk_test_cYr0CXtII4dszYZ6SYDJC8u9');

jQuery(document).ready(function ($) {
	$('#cc_number').payment('formatCardNumber');
	$('#cvc_number').payment('formatCardCVC');
});

jQuery(function ($) {
	$('#payment-form').submit(function (event) {
		var $form = $(this);

		$form.find('.payment-errors').text("");

//		jQuery.loader({
//			className: "blue-with-image-2",
//			content: ''
//		});

		// Disable the submit button to prevent repeated clicks
		$form.find('button').prop('disabled', true);

		Stripe.card.createToken($form, stripeResponseHandler);

		// Prevent the form from submitting with the default action
		return false;
	});
	function stripeResponseHandler(status, response) {
		var $form = $('#payment-form');
		if ( response.error ) {
			$.loader('close');
			// Show the errors on the form
			$form.find('.payment-errors').text(response.error.message);
			$form.find('button').prop('disabled', false);
		} else {
			// response contains id and card, which contains additional card details
			var token = response.id;
			// Insert the token into the form so it gets submitted to the server
			$form.append($('<input type="hidden" name="stripeToken" />').val(token));
			// and submit
			$form.get(0).submit();
		}
	}
	;
});

