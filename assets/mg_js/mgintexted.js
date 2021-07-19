/*!
 * jQuery UI Widget-factory plugin boilerplate (for 1.8/9+)
 * Author: @addyosmani
 * Further changes: @peolanha
 * Licensed under the MIT license
 */

;
(function ($, window, document, undefined) {


	$.widget("mg.intexted", {
		options: {
			boldPressed: false,
			italicPressed: false,
			underlinePressed: false,
			alLeftPressed: true,
			alCenterPressed: false,
			alRightPressed: false
		},
		_create: function () {
			var mgToolbar = "<div class='mg-toolbar intexted-hidden'>" +
					"<div class='row'>" +
					"<div class='col-lg-12'>" +
					"<a class='close mg-close' href='#'><i class='fa fa-close'></i></a>" +
					"</div>" +
					"</div>" +
					"<div class='row'>" +
					"<div class='col-lg-12'>" +
					"<div class='commands'>" +
					"<a class='control bold' href='#'>B</a>" +
					"<a class='control italic' href='#'>I</a>" +
					"<a class='control underline' href='#'>U</a>" +
					"</div>" +
					"<div class='commands'>" +
					"<a class='control al-left' href='#'><i class='fa fa-align-left'></i></a>" +
					"<a class='control al-center' href='#'><i class='fa fa-align-center'></i></a>" +
					"<a class='control al-right' href='#'><i class='fa fa-align-right'></i></a>" +
					"</div>" +
					"<div class='commands'>" +
					"<a class='control fg-color' href='#'><i class='material-icons'>&#xE23C;</i></a>" +
					"<a class='control bg-color' href='#'><i class='material-icons'>&#xE23A;</i></a>" +
					"</div>" +
					"<select class='fontsize'>" +
					"<option value='8'>8</option>" +
					"<option value='9'>9</option>" +
					"<option value='10'>10</option>" +
					"<option value='11'>11</option>" +
					"<option value='12'>12</option>" +
					"<option value='13'>13</option>" +
					"<option value='14'>14</option>" +
					"<option value='16'>16</option>" +
					"<option value='18'>18</option>" +
					"<option value='20'>20</option>" +
					"<option value='22'>22</option>" +
					"<option value='24'>24</option>" +
					"<option value='26'>26</option>" +
					"<option value='28'>28</option>" +
					"<option value='30'>30</option>" +
					"<option value='32'>32</option>" +
					"</select>" +
					"</div>" +
					"</div>" +
					"<br>" +
					"<div class='row'>" +
					"<div class='col-lg-12'>" +
					"<div class='wrap'>" +
					"<input type='text' class='mg-text' />" +
					"<input type='button' class='btn-clear' value='X' />" +
					"</div>" +
					"<select class='control fontfamily'>" +
					"<option value=\"Arial\" style=\"font-family: 'Arial'\">Arial</option>" +
					"<option value=\"Comic Sans MS\" style=\"font-family: 'Comic Sans MS'\">Comic Sans MS</option>" +
					"<option value=\"Courier New\" style=\"font-family: 'Courier New'\">Courier New</option>" +
					"<option value=\"Times New Roman\" style=\"font-family: 'Times New Roman'\">Times New Roman</option>" +
					"<option value=\"Verdana\" style=\"font-family: 'Verdana'\">Verdana</option>" +
					"<option value=\"Raleway\" style=\"font-family: 'Raleway'\">Raleway</option>" +
					"</select>" +
					"</div>" +
					"</div>" +
					"</div>";
			this.intexted = $(mgToolbar);
			console.log('MGINTEXTED CREATED	');
			console.log(this);
			console.log(this.intexted);
			$(this.element.parent()).append(this.intexted);
//			this.intexted.addClass('intexted');
			this.element.addClass('intexted');
			console.log(this.element);
//			this._on(this.element, {
//				click: '_show'
//			});
//			this._trigger('click');
			var $closeButton = this.intexted.find('.mg-close');
			this._on($closeButton, {
				click: '_close'
			});
			this._on(this.element, {
				mousemove: '_reposition'
			});
			var $boldButton = this.intexted.find('.bold');
			this._on($boldButton, {
				click: '_applyBold'
			});
			var $italicButton = this.intexted.find('.italic');
			this._on($italicButton, {
				click: '_applyItalic'
			});
			var $underlineButton = this.intexted.find('.underline');
			this._on($underlineButton, {
				click: '_applyUnderline'
			});
			var $alLeftButton = this.intexted.find('.al-left');
			this._on($alLeftButton, {
				click: '_applyAlLeft'
			});
			var $alCenterButton = this.intexted.find('.al-center');
			this._on($alCenterButton, {
				click: '_applyAlCenter'
			});
			var $alRightButton = this.intexted.find('.al-right');
			this._on($alRightButton, {
				click: '_applyAlRight'
			});
			var $inputText = this.intexted.find('input.mg-text');
			var $clearButton = this.intexted.find('.btn-clear');
			this._on($clearButton, {
				click: '_clearText'
			});
			this._on($inputText, {
				change: '_writeText'
			});
			$inputText.bind("keyup", $.proxy(this, '_writeText'));
			var $fontSize = this.intexted.find('.fontsize');
			this._on($fontSize, {
				change: '_applyFontSize'
			});
			var $fontFamily = this.intexted.find('.fontfamily');
			this._on($fontFamily, {
				change: '_applyFontFamily'
			});
		},
		_init: function () {
//			console.log('this.element.html()');
//			console.log(this.element.html());
//			console.log('this.element.text()');
//			console.log(this.element.text());

			var $this = this;
			if ($this.element.hasClass('bold')) {
				this.intexted.find('.bold').addClass('pressed');
			}
			if ($this.element.hasClass('italic')) {
				this.intexted.find('.italic').addClass('pressed');
			}
			if ($this.element.hasClass('underline')) {
				this.intexted.find('.underline').addClass('pressed');
			}
			if ($this.element.hasClass('al-left')) {
				this.intexted.find('.al-left').addClass('pressed');
				this._trigger('_adjustButtons');
			}
			if ($this.element.hasClass('al-center')) {
				this.intexted.find('.al-center').addClass('pressed');
				this._trigger('_adjustButtons');
			}
			if ($this.element.hasClass('al-right')) {
				this.intexted.find('.al-right').addClass('pressed');
				this._trigger('_adjustButtons');
			}
			var edText = $this.element.text();
			this.intexted.find('input.mg-text').val(edText);
			var textFontSize = this.element.children('.wysi-field').css('font-size') ? this.element.children('.wysi-field').css('font-size') : '14';
			console.log(textFontSize);
			this.intexted.find('.fontsize option').each(function () {
				if ($(this).val() + 'px' === textFontSize) {
					$(this).prop('selected', true);
				}
			});
			var textFontFamily = this.element.children('.wysi-field').css('font-family') ? this.element.children('.wysi-field').css('font-family') : 'Arial';
			this.intexted.find('.fontfamily option').each(function () {
				if ($(this).val() == textFontFamily) {
					$(this).prop('selected', true);
				}
			});
			var mgWidget = this.intexted;
			this.intexted.find('.fg-color').spectrum({
				change: function (color) {
					if ($this.element.hasClass('edit-mode')) {
						$this.element.children('.wysi-field').css({
							'color': color.toHexString()
						});
					}
//					mgWidget.find('.fg-color').children('.wysi-field').css({
					mgWidget.find('.fg-color').css({
//						'background-color': color.toHexString()
						'color': color.toHexString()
					});
				}
			});
			this.intexted.find('.bg-color').spectrum({
				change: function (color) {
					if ($this.element.hasClass('edit-mode')) {
						$this.element.css({
							'background-color': color.toHexString()
						});
					}
					mgWidget.find('.bg-color').css({
						'color': color.toHexString()
//						'background-color': color.toHexString()
					});
				}
			});
		},
		_adjustButtons: function () {
			alert('adjusting buttons');
			if (this.intexted.find('.bold').hasClass('pressed')) {
				this.intexted.find('.italic').removeClass('pressed');
				this.intexted.find('.underline').removeClass('pressed');
			}
			if (this.intexted.find('.italic').hasClass('pressed')) {
				this.intexted.find('.bold').removeClass('pressed');
				this.intexted.find('.underline').removeClass('pressed');
			}
			if (this.intexted.find('.underline').hasClass('pressed')) {
				this.intexted.find('.bold').removeClass('pressed');
				this.intexted.find('.italic').removeClass('pressed');
			}
		},
		_show: function () {
			$(".mg-toolbar").not(this.intexted).each(function () {
				var $this = $(this);
				if ($this.hasClass('intexted-visible')) {
					$this.addClass('intexted-hidden').removeClass('intexted-visible');
				}
			});
			var fgColor = this.element.children('.wysi-field').css('color');
			var bgColor = this.element.css('background-color');
			var ignoreColor = 'rgb(51, 51, 51)';
			if (fgColor === ignoreColor) {
				fgColor = 'rgb(0, 0, 0)';
			}
			this.intexted.find('.fg-color').css('color', fgColor);
			this.intexted.find('.bg-color').css('background-color', bgColor);

			$('.mg-editable').removeClass('edit-mode');
			this.element.addClass('edit-mode');
			var position = this.element.position();
			this.intexted.addClass('intexted-visible').removeClass('intexted-hidden');
			this.intexted.css({
				top: position.top + this.element.height() + 40 + 'px',
				left: position.left
			});
		},
		_applyBold: function (e) {
			e.preventDefault();
			$.each(this.element, function () {
				if ($(this).hasClass('edit-mode')) {
					$(this).find('.wysi-field').toggleClass('bold');
				}
			});
			this.intexted.find('a.bold').toggleClass('pressed');
		},
		_applyItalic: function (e) {
			e.preventDefault();
			$.each(this.element, function () {
				if ($(this).hasClass('edit-mode')) {
					$(this).find('.wysi-field').toggleClass('italic');
				}
			});
			this.intexted.find('a.italic').toggleClass('pressed');
		},
		_applyUnderline: function (e) {
			e.preventDefault();
			$.each(this.element, function () {
				if ($(this).hasClass('edit-mode')) {
					$(this).find('.wysi-field').toggleClass('underline');
				}
			});
			this.intexted.find('a.underline').toggleClass('pressed');
		},
		_applyAlLeft: function (e) {
			e.preventDefault();
			if (this.element.hasClass('edit-mode')) {
				this.element.find('.wysi-field').toggleClass('al-left');
				if (this.element.hasClass('al-center')) {
					this.element.find('.wysi-field').toggleClass('al-center');
				}
				if (this.element.hasClass('al-right')) {
					this.element.find('.wysi-field').toggleClass('al-right');
				}
			}
			this.intexted.find('a.al-left').toggleClass('pressed');
			this.intexted.find('a.al-center').removeClass('pressed');
			this.intexted.find('a.al-right').removeClass('pressed');
		},
		_applyAlCenter: function (e) {
			e.preventDefault();
			if (this.element.hasClass('edit-mode')) {
				this.element.find('.wysi-field').toggleClass('al-center');
				if (this.element.hasClass('al-left')) {
					this.element.find('.wysi-field').toggleClass('al-left');
				}
				if (this.element.hasClass('al-right')) {
					this.element.find('.wysi-field').toggleClass('al-right');
				}
			}
			this.intexted.find('a.al-center').toggleClass('pressed');
			this.intexted.find('a.al-left').removeClass('pressed');
			this.intexted.find('a.al-right').removeClass('pressed');
		},
		_applyAlRight: function (e) {
			e.preventDefault();
			if (this.element.hasClass('edit-mode')) {
				this.element.find('.wysi-field').toggleClass('al-right');
				if (this.element.hasClass('al-left')) {
					this.element.find('.wysi-field').toggleClass('al-left');
				}
				if (this.element.hasClass('al-center')) {
					this.element.find('.wysi-field').toggleClass('al-center');
				}
			}
			this.intexted.find('a.al-right').toggleClass('pressed');
			this.intexted.find('a.al-center').removeClass('pressed');
			this.intexted.find('a.al-left').removeClass('pressed');
		},
		_clearText: function (e) {
			e.preventDefault();
			var mgText = this.element.text().trim();
			var mgHtml = this.element.html();
			var regex = new RegExp(mgText, "i");
			var newHtml = mgHtml.replace(regex, '');
			this.element.html(newHtml);
			this.intexted.find('input.mg-text').val('');
		},
		_writeText: function () {
//			var mgTextOld = this.element.text().trim();
//			var mgTextNew = this.intexted.find('input.mg-text').val().trim();
//			var mgHtml = this.element.html();
//			var regex = new RegExp(mgTextOld, "i");
//			var newHtml = mgHtml.replace(regex, mgTextNew);
//			this.element.html(newHtml);
//			this.element.text(this.intexted.find('input.mg-text').val());
			this.element.children('.wysi-field').text(this.intexted.find('input.mg-text').val());
		},
		_applyFontSize: function () {
			this.element.children('.wysi-field').css('font-size', this.intexted.find('.fontsize').val() + 'px');
		},
		_applyFontFamily: function () {
			this.element.children('.wysi-field').css('font-family', this.intexted.find('.fontfamily').val());
		},
		_destroy: function () {
			// this.element.removeStuff();
			// For UI 1.8, destroy must be invoked from the 
			// base widget
//			this._trigger('_close');
//			alert('destroying');
			this.element.removeClass('intexted');
			this.destroy();
//			$.Widget.prototype.destroy.call(this);
			// For UI 1.9, define _destroy instead and don't 
			// worry about 
			// calling the base widget
		},
		_reposition: function () {
			var position = this.element.position();
			this.intexted.css({
				top: position.top + this.element.height() + 40 + 'px',
				left: position.left
			});
		},
		_close: function () {
			this.element.removeClass('edit-mode');
			this.intexted.addClass('intexted-hidden').removeClass('intexted-visible');
		},
		// Respond to any changes the user makes to the 
		// option method
		_setOption: function (key, value) {
			switch (key) {
				case "someValue":
					//this.options.someValue = doSomethingWith( value );
					break;
				default:
					//this.options[ key ] = value;
					break;
			}

			// For UI 1.8, _setOption must be manually invoked 
			// from the base widget
			$.Widget.prototype._setOption.apply(this, arguments);
			// For UI 1.9 the _super method can be used instead
			// this._super( "_setOption", key, value );
		},
		show: function () {
			$(".mg-toolbar").not(this.intexted).each(function () {
				var $this = $(this);
				if ($this.hasClass('intexted-visible')) {
					$this.addClass('intexted-hidden').removeClass('intexted-visible');
				}
			});
			var fgColor = this.element.children('.wysi-field').css('color');
			var bgColor = this.element.css('background-color');
			var ignoreColor = 'rgb(51, 51, 51)';
			if (fgColor === ignoreColor) {
				fgColor = 'rgb(0, 0, 0)';
			}
			this.intexted.find('.fg-color').css('color', fgColor);
			this.intexted.find('.bg-color').css('background-color', bgColor);

			$('.mg-editable').removeClass('edit-mode');
			this.element.addClass('edit-mode');
			var position = this.element.position();
			this.intexted.addClass('intexted-visible').removeClass('intexted-hidden');
			this.intexted.css({
				top: position.top + this.element.height() + 40 + 'px',
				left: position.left
			});
		},
		destroy: function () {
			// this.element.removeStuff();
			// For UI 1.8, destroy must be invoked from the 
			// base widget
//			this._trigger('_close');
//			this.element.removeClass('intexted');
//			this.destroy();
//			$.Widget.prototype.destroy.call(this);
			// For UI 1.9, define _destroy instead and don't 
			// worry about 
			// calling the base widget
		},
		close: function () {
			this.intexted.remove();
			this.element.removeClass('edit-mode');
			this.intexted.addClass('intexted-hidden').removeClass('intexted-visible');
			this.element.removeClass('intexted');
			this.destroy();
//			this._trigger('_destroy');
		}
	});

})(jQuery, window, document);
