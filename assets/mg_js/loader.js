//jQuery(document).ready(function () {
	jQuery('body').append(
					"<div id='spinner'><img src='assets/images/spinner.gif' alt='Loading...'/></div>"
					);
	var $loading = jQuery('#spinner').hide();
	jQuery('#spinner').css(
					{
						'display': 'none',
						'width': '100px',
						'height': '100px',
						'position': 'fixed',
						'top': '50%',
						'left': '50%',
						'text-align': 'center',
						'margin-left': '-50px',
						'margin-top': '-100px',
						'z-index': '20',
						'overflow': 'auto'
					});

//	jQuery(document).ajaxStart(function ()
//	{
//		$loading.show();
//	}).ajaxStop(function ()
//	{
//		$loading.hide();
//	});
//});