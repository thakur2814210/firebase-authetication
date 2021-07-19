//assuming that the <div class="modal-dialog"> element is defined in the document 
//(it is in the footer.php at this stage) 
//the following methods will make it a little bit easier to call a modal with specific content
function showErrorModal(){
	jQuery('#modal_message_title').text('Error');
	jQuery('#modal_message_body').text('An error has occurred, please try again.');
	jQuery('#modal_message').modal('show');
}
//might be abit too verbose
function showModalWithTitleAndMessage(arg){
	jQuery('#modal_message_title').text(arg.title);
  jQuery('#modal_message_body').text(arg.message);
  jQuery('#modal_message').modal('show');
}
function showConfirmationModal(arg){
    jQuery('#modal_message_confirm_generic_title').text(arg.title);
    jQuery('#modal_message_confirm_generic_body').text(arg.message);
    jQuery('#modal_message_confirm_generic').modal('show');
}
function showConfirmationModal2(arg){
    jQuery('#modal_message_confirm_generic_title2').text(arg.title);
    jQuery('#modal_message_confirm_generic_body2').text(arg.message);
    jQuery('#modal_message_confirm_generic2').modal('show');
}
function showProfileModal(arg){
    jQuery('#modal_profile_confirm_generic_title').text(arg.title);
    jQuery('#modal_profile_confirm_generic_body').html(arg.message);
    jQuery('#modal_profile_confirm_generic').modal('show');
}
function showErrorModalWithMsg(msg) {
    jQuery('#modal_message_title').text('Error');
    jQuery('#modal_message_body').text(msg);
    jQuery('#modal_message').modal('show');
}
