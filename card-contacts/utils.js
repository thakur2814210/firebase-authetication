//TODO:move out to logging file
//used as global to control logging
var LOG = {};

//log when templates started and stopped loading
LOG.logTemplateLoading = false;

//global variable used to register templates for caching
var Templates = {};
var CompletedCounter = 0;

//loads a handlebars (.hbs) file via ajax
//this replaces default embedding of handlebars
function getTemplateAjax(name, path, length) {
	jQuery.ajax({
		url: path,
		success: function (data) {
			Templates[name] = data;
			CompletedCounter++;
			if (CompletedCounter == length) {
				templatesCompletedLoading();
				if (LOG.logTemplateLoading)
					console.log('Finished Loading Template With Ajax', data, Templates);
			}
		}
	});
}

//just iterates through templates used by a page 
//and retrieves them from server via ajax
function getPageTemplates(templates) {
	if (LOG.logTemplateLoading)
		console.log("The following templates are being loaded", templates);
	var i = 0, l = templates.length;
	for (i; i < l; i++) {
		if (LOG.logTemplateLoading)
			console.log('Started Loading Template With Ajax', templates[i]);
		getTemplateAjax(templates[i].name, templates[i].path, l);
	}
}

//used to parse a handlebars template and just return the appropriate html
function parseAndReturnHandlebarsRev2(templateName, context) {
	var source = Templates[templateName];
//	console.log('Handlebars templateName is '+templateName);
//	console.log('Handlebars context is '+context);
//	console.log('Handlebars source is '+source.name + ' = '+source.path);
	var template = Handlebars.compile(source);
	return template(context);
}

//same as above, but also sets html of destination element
function loadContextIntoElementRev2(templateName, context, destinationSelector) {
	var html = parseAndReturnHandlebarsRev2(templateName, context);
	//console.log(context);
	jQuery(destinationSelector).html(html);
}

function templatesCompletedLoading() {
	jQuery.event.trigger({
		type: "templatesLoaded",
		message: "success"
	});
}

function showErrorModal() {
	jQuery('#modal_message_title').text('Error');
	jQuery('#modal_message_body').text('An error has occurred, please try again.');
	jQuery('#modal_message').modal('show');
}
//might be abit too verbose
function showModalWithTitleAndMessage(arg) {
	jQuery('#modal_message_title').text(arg.title);
	jQuery('#modal_message_body').text(arg.message);
	jQuery('#modal_message').modal('show');
}
//expand this method to really by generic
function showConfirmationModal(arg) {
	jQuery('#modal_message_confirm_generic_title').text(arg.title);
	jQuery('#modal_message_confirm_generic_body').text(arg.message);
	jQuery('#modal_message_confirm_generic').modal('show');
}
function showErrorModalWithMsg(msg) {
	jQuery('#modal_message_title').text('Error');
	jQuery('#modal_message_body').text(msg);
	jQuery('#modal_message').modal('show');
}

function showConfirmationModalWithSelector(arg, partSelector) {
	jQuery('#' + partSelector + '_title').text(arg.title);
	jQuery('#' + partSelector + '_body').text(arg.message);
	jQuery('#' + partSelector).modal('show');
}
function closeModal(selector) {
	jQuery('#' + partSelector).modal('hide');
}
function showConfirmationModalWithSelector2(arg, partSelector) {
	jQuery('#' + partSelector + '_title').text(arg.title);
	jQuery('#' + partSelector + '_body').text(arg.message);
	jQuery('#' + partSelector).modal('show');
}
