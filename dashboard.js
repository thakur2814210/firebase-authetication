jQuery(document).ready(function ()
{
	setTimeout(function () {
		var cardDetailsBcn = jQuery.cookie('viewCardDetails');
		if (cardDetailsBcn != null) {
//			loadDataByBcn(cardDetailsBcn);
			showCardDetails(cardDetailsBcn);
		}
		jQuery.removeCookie('viewCardDetails');
		var addCard = jQuery.cookie('addCard');
		if(addCard != null){
			addCardToContact(addCard, '');
			showCardDetails(addCard);
			jQuery.removeCookie('addCard');
		}
		setTimeout(function(){
			getFolders('dashboard', fillPage);
		}, 500);
	}, 500);


});