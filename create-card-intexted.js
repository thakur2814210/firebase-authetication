var card_front, card_back, bcn;

function saveLinks(default_card) {
    loaderText('Saving links...');
    loader.show();
    jQuery('.aboutCardposition').removeClass('active');
    var stringdata;
    if (default_card) {
        stringdata = jQuery("#default_links").html();
    } else {
        stringdata = jQuery("#links-f").html();
    }
    jQuery.ajax({
        type: "POST",
        url: "includes/create-business-card/links_front.php",
        data: {
            stringdata: stringdata
        },
        success: function (data) {
            var card_front = localStorage.getItem('card_front');
            var card_back = localStorage.getItem('card_back');
            var bcn = localStorage.getItem('bcn');
            //			if ( !default_card ) {
            setTimeout(function () {
                jQuery.ajax({
                    type: 'post',
                    url: 'includes/create-business-card/links_back.php',
                    data: {
                        stringdata: jQuery("#links-b").html()
                    },
                    success: function () {
                        var d = new Date();
                        jQuery('.aboutCardposition').removeClass('active');
                        // console.log('card_front ' + card_front);
                        // console.log('card_back ' + card_back);
                        // console.log('bcn: ' + bcn);
                        jQuery('#new-card-data img').attr('src', card_front + '?' + d.getTime());
                        jQuery('#back-side-content-id2 img').attr('src', card_back + '?' + d.getTime());
                        jQuery('#new-card-bcn').text(bcn);
                        loader.hide();
                        openPrviewSave();
                        //						window.location.href = globalURL + "my-own-cards.php";
                    },
                    error: function (result) {
                        console.log(result);
                    }
                });
            }, 1000);
            //			}
        },
        error: function (result) {
            console.log(result);
        }
    });
}

function saveWYSI(context) {
    // console.log('savingWYSI');
    //	console.log(jQuery(context).parents('.mg-toolbar').siblings().find(".click-container"));
    //	jQuery(context).parents('.mg-toolbar').siblings().find(".click-container").show();
    // console.log(jQuery('.mg-editable.edit-mode').find(".click-container"));
    jQuery('.mg-editable.edit-mode').find(".click-container").show();
    //	console.log("jQuery(':mg-intexted')");
    //	console.log(jQuery(':mg-intexted'));
    jQuery(':mg-intexted').intexted('destroy');
    jQuery('.mg-toolbar').remove();
    //	jQuery(context).parent().hide();
    jQuery(context).closest(".draggables").removeClass("on-top");
    jQuery(".draggables").resizable('destroy');
    jQuery(".draggables").resizable({
        containment: "#droppable",
        minHeight: 4,
        maxHeight: 10
    });
}

// SAVE WYSI
jQuery(document).on("click", ".save", function () {
    saveWYSI(this);
});

function ensureAllWidgetsClosed() {
    var check = jQuery(".input-group.wysi > button");
    jQuery('.glyphicon').hide();
    jQuery('.ui-rotatable-handle').hide();
    jQuery('.ui-resizable-handle').hide();
    saveWYSI(check);
}

function openPrviewSave() {
    jQuery('body').addClass('active');
    jQuery('#overLay').slideDown();
    jQuery('#preViewSave').slideDown();
    jQuery("html, body").animate({
        scrollTop: 0
    }, "slow");
    return false;
}

function fillBg(theColor) {
    var target;
    if (isVisible(jQuery('#droppable'))) {
        target = '#droppable';
    } else {
        target = '#back-side-content-id';
    }
    theWidth = jQuery(target).width() + 2;
    theHeight = jQuery(target).height() + 2;
    //newStyle = "width:" + theWidth + "px; height:" + theHeight + "px; background:" + theColor + " !important; position: absolute; top: 0px; left: 0px; z-index:55555;";
    newStyle = "width:" + theWidth + "px; height:" + theHeight + "px; background:" + theColor;
    jQuery(target).attr("style", newStyle);
}

function exportCard(ending, default_card) {
    jQuery('.aboutCardposition').removeClass('active');
    ensureAllWidgetsClosed();
    jQuery('.draggables').draggable('destroy');
    jQuery('.draggables').resizable('destroy');
    //	jQuery('.new-image').draggable('destroy');
    //	jQuery('#background-pic-front').resizable('destroy');
    jQuery('#resizing-container-front').resizable('destroy');
    jQuery('#resizing-container-back').resizable('destroy');
    jQuery('.new-image').resizable('destroy');
    // console.log('START SAVING FRONT');
    // console.log('jQuery("#droppable").html()');
    // console.log(jQuery("#droppable").html());
    jQuery('#back-side-content-id').children().css({
        'visibility': 'hidden'
    });
    setTimeout(function () {
        html2canvas([document.getElementById('droppable')], {
            onrendered: function (canvas) {
                var data = canvas.toDataURL("image/png");
                var image = new Image();
                image.src = data;
                // AJAX call to send `data` to a PHP file that
                // creates a PNG image from the dataURI string
                // and saves it to a directory on the server
                //				console.log(jQuery("#droppable").html());
                var landscape = "1";
                if (jQuery('#portraitButton').hasClass('active')) {
                    landscape = "0";
                }
                // console.log('landscape in saveCard is ' + landscape);
                jQuery('.mg-editable').each(function () {
                    var p = jQuery(this).position();
                    var top = p.top;
                    var left = p.left;
                    var percTop = Math.round((top / 338) * 100);
                    var percLeft = Math.round((left / 539) * 100);
                    jQuery(this).css('top', percTop + '%');
                    jQuery(this).css('left', percLeft + '%');
                });
                jQuery.ajax({
                    type: "POST",
                    url: "includes/create-business-card/export_front.php",
                    data: {
                        landscape: landscape,
                        base64data: data,
                        stringdata: jQuery("#droppable").html()
                    },
                    success: function (data) {
                        jQuery('#back-side-content-id').children().css({
                            'visibility': 'visible'
                        });
                        jQuery('#droppable').children().css({
                            'visibility': 'hidden'
                        });
                        jQuery('.aboutCardposition').addClass('active');
                        //						jQuery('#back-side-content-id').children().css({'display': 'block'});
                        //						jQuery('#droppable').children().css({'display': 'none'});
                        console.log(data);

                        var response = JSON.parse(data);
                        card_front = response.image;
                        bcn = response.bcn;
                        console.log('START SAVING BACK with BCN: ' + bcn);
                        setTimeout(function () {
                            html2canvas([document.getElementById("back-side-content-id")], {
                                onrendered: function (canvas) {
                                    var data = canvas.toDataURL("image/png");
                                    var image = new Image();
                                    image.src = data;
                                    //								console.log(jQuery("#back-side-content-id").html());
                                    // AJAX call to send `data` to a PHP file that
                                    // creates an PNG image from the dataURI string
                                    // and saves it to a directory on the server
                                    jQuery.ajax({
                                        type: "POST",
                                        url: "includes/create-business-card/export_back.php",
                                        /*dataType: 'text',*/
                                        data: {
                                            base64data: data,
                                            stringdata: jQuery("#back-side-content-id").html()
                                        },
                                        success: function (data) {
                                            var d = new Date();
                                            jQuery('.aboutCardposition').removeClass('active');
                                            card_back = data;
                                            localStorage.setItem('card_front', card_front);
                                            localStorage.setItem('card_back', card_back);
                                            localStorage.setItem('bcn', bcn);
                                            jQuery('#new-card-data img').attr('src', card_front + '?' + d.getTime());
                                            jQuery('#back-side-content-id2 img').attr('src', card_back + '?' + d.getTime());
                                            jQuery('#new-card-bcn').text(bcn);
                                            loader.hide();
                                            if (ending) {
                                                jQuery('#hidden-card').hide();
                                                openPrviewSave();
                                            } else {
                                                window.location.href = globalURL + 'create-card-4.php';
                                            }
                                        }
                                    });
                                }
                            });
                        }, 1000);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });
            }
        });
    }, 1000);
}
// SAVE CANVAS AND WIDGET POSITIONS
function saveCard(ending, default_card) {
    loaderText('Saving your card...');
    loader.show();
    var response;
    if (default_card) {
        jQuery.ajax({
            type: 'post',
            url: 'includes/set_default.php',
            success: function (data) {
                response = data;
            }
        });
        setTimeout(function () {
            if (response === "exists") {
                swal({
                    title: "Warning!",
                    text: "You have already created a default card.",
                    html: true,
                    type: "info",
                    showCancelButton: false,
                    confirmButtonColor: "#ff6603",
                    confirmButtonText: "Ok",
                    closeOnConfirm: true
                });
                loader.hide();
                return;
            } else if (response === "creating") {
                loader.hide();
                swal({
                    title: "Warning!",
                    text: "A default card is already created but you still have to save it.",
                    html: true,
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#ff6603",
                    confirmButtonText: "Ok",
                    closeOnConfirm: true
                }, function (isConfirm) {
                    if (isConfirm) {
                        openPrviewSave();
                    }
                });
                return;
            } else {
                jQuery("img#background-pic-front").remove();
                jQuery('#droppable').prepend('<img id="background-pic-front" class="img-responsive" src="includes/create-business-card/uploads/background/default/default-card-front.png" alt="" width="539" height="338">');
                jQuery('#hidden-card').slideDown();
                exportCard(ending, default_card);
            }
        }, 500);
    } else {
        exportCard(ending, default_card);
    }
    console.log('STOP SAVING');
}

/*
 * create a background image from color
 * called even when open page to replace
 */
function createColorImage(theColor) {
    console.log('create color image');
    var target;
    var side;
    if (isVisible(jQuery('#droppable'))) {
        target = '#droppable';
        side = 'front';
    } else {
        target = '#back-side-content-id';
        side = 'back';
    }
    theWidth = jQuery(target).width() + 2;
    theHeight = jQuery(target).height() + 2;
    console.log('appending color image to ' + target);
    //newStyle = "width:" + theWidth + "px; height:" + theHeight + "px; background:" + theColor + " !important; position: absolute; top: 0px; left: 0px; z-index:55555;";
    //		newStyle = "width:" + theWidth + "px; height:" + theHeight + "px; background:" + theColor;
    //		jQuery(target).attr("style", newStyle);
    var landscape = "1";
    //	var bgHeight = '338';
    //	var bgWidth = '539';
    var bgHeight = '350';
    var bgWidth = '558';
    if (jQuery('#portraitButton').hasClass('active')) {
        landscape = "0";
        //		bgHeight = '539';
        //		bgWidth = '338';
        bgHeight = '558';
        bgWidth = '350';
    }
    jQuery.ajax({
        type: 'get',
        url: 'includes/create-business-card/create-color-image.php',
        data: {
            theColor: theColor,
            landscape: landscape,
            bgHeight: bgHeight,
            bgWidth: bgWidth
        },
        success: function (result) {
            var d = new Date();
            jQuery("img#background-pic-" + side).remove();
            jQuery("#droppable").css('background', 'transparent');
            jQuery(target).prepend('<img id="background-pic-' + side + '" class="img-responsive" src="includes/create-business-card/uploads/background/' + result + '?' + d.getTime() + '" alt="" width="' + bgWidth + '" height="' + bgHeight + '">');
        },
        error: function (result) {
            jQuery('div').html(result).fadeIn('slow');
        }
    });
}

jQuery(document).ready(function () {
    //	var edit_mode = localStorage.getItem('edit_mode');
    //	if (typeof edit_mode == 'undefined' || edit_mode == null || edit_mode == ''){
    //		fillBg('#ffffff');
    //	}
    if (jQuery("img#background-pic-front").attr('src') === '' || jQuery("img#background-pic-front") == null ||
        typeof jQuery("img#background-pic-front").attr('src') == 'undefined') {
        //		createColorImage('#ffffff');
        console.log('call createColorImage');
    } else {
        console.log('background-pic-front exists');
    }
    localStorage.removeItem('edit_mode');

    jQuery('#add-text-field').click(function () {
        var offSetPosition = jQuery(document).find('.draggables').length;
        console.log('offsetPosition is ' + offSetPosition);
        var offSetTop = offSetPosition + offSetPosition;
        console.log('offSetTop is ' + offSetTop);
        var el = jQuery('.input-group.draggables');
        var number;
        if (!el) {
            number = 0;
        } else {
            number = el.length + 1;
        }
        var fieldResult = '<div id="title-' + number + '" class="mg-editable input-group draggables ui-draggable ui-draggable-handle ui-resizable" style="position:absolute; top:' + offSetTop + '0px; left:' + offSetPosition + '0px;">' +
            '<div class="form-control wysi-field">your text here</div>' +
            '<span class="input-group-addon click-container">' +
            '<span class="glyphicon glyphicon-edit edit" style="cursor: pointer"></span>&nbsp;' +
            '<span class="glyphicon glyphicon-remove exit" style="cursor: pointer"></span>' +
            '</span>' +
            '<div class="clearfix"></div>' +
            '<div class="input-group wysi" style="display:none;">'
            //+'<button type="button" class="btn btn-danger btn-sm exit">Remove</button>'
            +
            '<button type="button" class="btn btn-success btn-sm save"><span class="glyphicon glyphicon-ok" ></span></button>' +
            '</div>' +
            '</div>';
        var target;
        if (jQuery('.aboutCardposition').hasClass('active')) {
            target = '#back-side-content-id';
        } else {
            target = '#droppable';
        }
        jQuery(target).append(fieldResult);
        jQuery(".draggables").draggable({
            containment: target
        });
        jQuery(target).multidraggable();
        jQuery(".draggables").resizable({
            minHeight: 4,
            maxHeight: 10
        });
        //		jQuery(".draggables").selectable();
    });

    jQuery(document).on("click", ".del", function () {
        jQuery(this).parent().remove();
    });

    jQuery(document).on('mouseenter', '.mg-editable', function () {
        //		jQuery(':mg-intexted').intexted('destroy');
        //		jQuery(this).intexted();
        //		jQuery('.click-container').toggleClass('el-opacity');
    });

    jQuery(document).on("click", ".edit", function () {
        //		jQuery(this).parent().hide();
        jQuery(this).parent().css('opacity', '0');
        jQuery(this).closest(".draggables").addClass("on-top");
        jQuery(this).parents('.mg-editable').intexted();
        jQuery(this).parents('.mg-editable').intexted('show');
        //		jQuery(':mg-intexted').intexted('show');
        //		jQuery('.intexted').intexted('show');
        //		jQuery('.mg-editable').intexted('show');
    });

    // RESET WYSI
    jQuery(document).on("click", ".exit", function () {
        jQuery(this).closest(".draggables").remove();
        jQuery(".draggables").draggable("option", "disabled", false);
        jQuery(this).find('glyphicon-ok').hide();
        jQuery(this).parent().siblings(".wysi-field").destroy();
        jQuery(this).parent().hide();
        jQuery(this).parent().siblings(".click-container").show();
    });

    jQuery(document).on('click', '.mg-close', function (e) {
        e.preventDefault();
        //		jQuery(':mg-intexted').intexted('destroy');
        //		jQuery('.intexted').intexted('close');
        jQuery('.mg-editable').intexted('close');
    });

    //	INIT DRAGGABLES

    jQuery(".draggables").draggable({
        containment: "#droppable"
    });
    jQuery(".draggables").draggable({
        containment: "#back-side-content-id"
    });
    jQuery('#droppable').multidraggable();
    jQuery('#back-side-content-id').multidraggable();


    //	jQuery(".new-image").draggable({containment: "#droppable"});

    //	jQuery(".new-image").draggable({containment: "#back-side-content-id"});
    //	jQuery('.upload-panel').draggable({containment: "window"});

    jQuery('.upload-panel').draggable();
    // INIT RESIZABLE 

    jQuery(".draggables").resizable({
        containment: "#droppable",
        minHeight: 4,
        maxHeight: 10
    });

    jQuery("#resizing-container-front").resizable({
        containment: "#droppable",
        minHeight: 4,
        maxHeight: 350
    });

    jQuery("#resizing-container-back").resizable({
        containment: "#droppable",
        minHeight: 4,
        maxHeight: 350
    });
    //	jQuery("#background-pic-back").resizable({containment: "#droppable", minHeight: 10, maxHeight: 300});

    jQuery(".draggables").resizable({
        containment: "#back-side-content-id",
        minHeight: 4,
        maxHeight: 10
    });
    /*
     * this initialize resizable and rotatable logo
     */
    jQuery(".new-image").resizable({
        containment: "#droppable",
        minHeight: 4,
        maxHeight: 330,
        minWidth: 10,
        maxWidth: 530,
        aspectRatio: true,
        handles: "n, e, s, w, se, nw"
    });

    jQuery(".new-image").rotatable();

    jQuery(document).on('click', '.draggables', function () {
        $this = jQuery(this);
        $this.css({
            //			'background': 'url("assets/images/lines.png") repeat scroll 0 0'
            'border': '1px dotted #b5b5b5'
        });
        $this.children('.click-container').css({
            'opacity': '0.75'
        });
    });

    jQuery(document).on('mouseenter', '.draggables', function () {
        $this = jQuery(this);
        console.log($this.find('.glyphicon.glyphicon-edit.edit'));
        $this.find('.glyphicon, .glyphicon-remove').show();
        $this.css({
            //			'background': 'url("assets/images/lines.png") repeat scroll 0 0'
            'border': '1px dotted #b5b5b5'
        });
        $this.children('.click-container').css({
            'opacity': '0.75'
        });
    });

    jQuery(document).on('mouseleave', '.draggables', function () {
        $this = jQuery(this);
        $this.css({
            //			'background': 'transparent'
            'border': 'none'
        });
        $this.children('.click-container').css({
            'opacity': '0'
        });
        //		$this.find('.glyphicon-edit, .glyphicon-remove').hide();
    });

    // INIT DROPPABLE
    jQuery("#droppable").droppable({
        accept: ".draggables, .new-image"
    });

    jQuery("#back-side-content-id").droppable({
        accept: ".draggables, .new-image"
    });

    // INIT COLOR PICKER
    jQuery(".colpicker").click(function (e) {
        e.preventDefault();
    });

    jQuery(".colpicker").colpick({
        layout: "rgbhex",
        submit: 0,
        color: "ffffff",
        colorScheme: "dark",
        onChange: function (hsb, hex, rgb, el, bySetColor) {
            jQuery(el).css("border-color", "#" + hex);
            if (!bySetColor) {
                jQuery(el).val("#" + hex);
            }
        }
    }).keyup(function () {
        jQuery(this).colpickSetColor(this.value);
    });

    jQuery('.card_data').on('keyup', function () {
        var $this = jQuery(this);
        $this.prev().find('.warn_required').fadeOut();
        $this.css({
            'border-bottom': '1px solid #fd6f02'
        });
    });

    jQuery('.card_data').on('focusout blur', function () {
        var $this = jQuery(this);
        if ($this.val() === '') {
            $this.css({
                'border-bottom': '1px solid #ff0000'
            });
            $this.prev().find('.warn_required').fadeIn();
        } else {
            $this.css({
                'border-bottom': '1px solid #c2c2c2'
            });
        }
    });

    jQuery('#set-card-name').click(function () {
        var error = false;
        jQuery('.card_data').each(function () {
            var $this = jQuery(this);
            if ($this.val() === '') {
                $this.css({
                    'border-bottom': '1px solid #ff0000'
                });
                $this.prev().find('.warn_required').fadeIn();
                //				jQuery(this).attr('placeholder', 'This field is required');
                error = true;
            }
        });
        if (error) {
            return;
        } else {
            var card_name = jQuery('#card_name').val();
            var distributed_brand = jQuery('#distributed_brand').val();
            var category = jQuery('#category').val();
            var sub_category = jQuery('#sub_category').val();
            window.location.href = globalURL + 'create-card-3.php?card_name=' + card_name + '&distributed_brand=' + distributed_brand + '&category=' + category + '&sub_category=' + sub_category;
        }
    });

    jQuery('#select-background').click(function (e) {
        e.preventDefault();
        jQuery('#inputFileB').trigger('click');
    });

    jQuery('#select-logo').click(function (e) {
        e.preventDefault();
        jQuery('#inputFileL').trigger('click');
    });

    // APPEND IMAGE BACKGROUND TO CANVAS
    jQuery(document).on("click", "#set-color", function (e) {
        e.preventDefault();
        var theColor = jQuery(".colpicker").val();
        createColorImage(theColor);
    });

    jQuery('#inputFileB').on('change', function (e) {
        e.preventDefault();
        if (this.files[0].size > 10000000) {
            swal({
                title: "Warning!",
                text: "The image size is greater than 10 Mb. Uploading this image could take a long time. Are you sure you want to proceed?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ff6603",
                confirmButtonText: "Ok",
                closeOnConfirm: true
            }, function (isConfirm) {
                if (!isConfirm) {
                    jQuery(".upload-background").addClass('disabled');
                    return;
                }
            });
        }
        var picture = jQuery('#inputFileB').val();
        if (picture !== '') {
            //		alert(' add disabled');
            jQuery(".upload-background").removeClass('disabled');
        } else {
            //		alert(' removing disabled');
            jQuery(".upload-background").addClass('disabled');
        }
    });

    jQuery('#inputFileL').on('change', function (e) {
        e.preventDefault();
        var picture = jQuery('#inputFileL').val();
        if (picture !== '') {
            //		alert(' add disabled');
            jQuery(".upload-logo").removeClass('disabled');
        } else {
            //		alert(' removing disabled');
            jQuery(".upload-logo").addClass('disabled');
        }
    });

    jQuery(document).on('click', '.close-panel', function () {
        jQuery(this).closest('.upload-panel').slideUp();
    });

    //	jQuery('.fake-img').imgAreaSelect({
    //		handles: true,
    //		fadeSpeed: 400,
    //		onSelectChange: preview
    //	});


    jQuery('#preview-and-save').click(function () {
        saveCard(true, false);
    });

    jQuery('#save-links').click(function () {
        saveLinks(false);
    });

    jQuery('#add-links').click(function () {
        saveCard(false, false);
    });

    jQuery('#add-link').click(function () {});

    jQuery("#premium_id").keyup(function (e) {
        e.preventDefault();
        var premium_id = jQuery(this).val();
        if (premium_id === '') {
            jQuery("#btnBuyPrem").addClass('disabled');
        } else {
            jQuery.post('includes/premium-id-check.php', {
                'premium_id': premium_id
            }, function (data) {
                jQuery("#premium-id-result").html(data); //dump the data received from PHP page
                if (jQuery("#prem-avail").attr('title') === 'Available') {
                    jQuery("#btnBuyPrem").removeClass('disabled');
                    jQuery('#na_bcn_msg').text('');
                    console.log(jQuery("#prem-avail").attr('title'));
                } else {
                    jQuery("#btnBuyPrem").addClass('disabled');
                    jQuery('#na_bcn_msg').text('This bcn has already been used. Could you please choose another bcn?');
                    console.log(jQuery("#prem-avail").attr('title'));
                }
            });
        }
    });

    jQuery(document).on('click', "#btnBuyPrem", function () {
        if (jQuery(this).hasClass('disabled')) {
            console.log('is disabled');
            return;
        }
        //		window.location.href = "create-card-5-bcn.php";
        loaderText('Saving card to buy premium BCN...');
        loader.show();
        var url = jQuery(this).attr('data-url');
        console.log('clicked btnBuyPrem url is  ' + url);
        var res = url.split('?');
        console.log('res is ' + res);
        var res2 = res[1].split('&');
        console.log('res2 is ' + res2);
        var process_completed = false,
            buying = false;
        console.log('res2 length is ' + res2.length);
        if (res2.length == 1) {
            buying = res2[0].split('=')[1];
        }
        if (res2.length == 2) {
            process_completed = res2[0].split('=')[1];
            buying = res2[1].split('=')[1];
        }
        console.log('process_completed ' + process_completed);
        console.log('buying ' + buying);
        var date_string = getDateTime();
        var card_id = jQuery(this).attr('data-card-id');
        jQuery.ajax({
            type: 'post',
            url: 'includes/create-business-card/save-card.php',
            data: {
                process_completed: process_completed,
                buying: buying,
                date_string: date_string
            },
            success: function (result) {
                window.location.href = "create-card-5-bcn.php?card_id=" + card_id;
                //				window.location.href = globalURL + url;
            },
            error: function (result) {
                return;
            }
        });
    });

    jQuery('#create-corporate').click(function (e) {
        e.preventDefault();
        swal({
            title: "Not ready yet",
            text: "This feature is currently under developing. Thanks for your patience.",
            html: true,
            type: "info",
            showCancelButton: false,
            confirmButtonColor: "#ff6603",
            confirmButtonText: "Ok",
            closeOnConfirm: true
        });
        return;
    });

    /*end no more used*/
    jQuery('#add-background').click(function (e) {
        e.preventDefault();
        if (jQuery(this).hasClass('disabled')) {
            return;
        }
        //		if ( jQuery('#background-panel').is(':visible') ) {
        //			jQuery('#background-panel').slideToggle('slow');
        //		}
        //		if ( jQuery('#logo-panel').is(':visible') ) {
        //			jQuery('#logo-panel').slideToggle('slow');
        //		}
        jQuery('.upload-panel').slideUp('slow');
        setTimeout(function () {
            jQuery('.colpicker').show();
            jQuery('#set-color').show();
            jQuery('#bg-panel-title').text('Background options');
            jQuery('#background-panel').slideToggle('slow');
        }, 500);
    });

    jQuery('#import-card-image').click(function (e) {
        e.preventDefault();
        if (jQuery(this).hasClass('disabled')) {
            return;
        }
        jQuery('.upload-panel').slideUp('slow');
        setTimeout(function () {
            jQuery('.colpicker').hide();
            jQuery('#set-color').hide();
            jQuery('#bg-panel-title').text('Your card image');
            jQuery('#background-panel').slideToggle('slow');
        }, 500);
    });

    jQuery('#add-logo').click(function (e) {
        e.preventDefault();
        if (jQuery(this).hasClass('disabled')) {
            return;
        }
        jQuery('.upload-panel').slideUp('slow');
        setTimeout(function () {
            jQuery('#logo-panel').slideToggle('slow');
        }, 500);
    });

    jQuery('#import-data').click(function () {
        jQuery('.upload-panel').slideUp('slow');
        setTimeout(function () {
            jQuery('#import-details').slideToggle('slow');
        }, 500);
    });

    jQuery('#add-link').click(function (e) {
        e.preventDefault();
        if (jQuery(this).hasClass('disabled')) {
            return;
        }
        jQuery('#link-panel').slideToggle('slow');
    });

    jQuery(document).on('click', 'a.disabled', function (e) {
        e.preventDefault();
        return;
    });

    jQuery(document).on('focus blur', '#link-panel input', function () {
        jQuery(this).prev('.input-group-addon').toggleClass('orange_underline');
    });


    jQuery(document).on("click", ".plus", function () {
        if (jQuery(this).hasClass('limited')) {
            return false;
        }
        // find targeted field
        var fieldFromParent = jQuery(this).parent().siblings('.wysi-adder').find('input').attr('id').replace("-field", "");
        // count existing fields and adjust id
        var fieldIdExist = jQuery(document).find('.' + fieldFromParent).length;
        var fieldId = fieldFromParent + "-" + fieldIdExist;
        // get field value
        var fieldValue = jQuery(this).parent().siblings('.wysi-adder').find('input').val();
        // calculate top end left offset based on other elements
        var offSetPosition = jQuery(document).find('.draggables').length;
        var offSetTop = offSetPosition + offSetPosition;
        if (jQuery(document).find('.' + fieldFromParent).length > 9) {
            jQuery(this).addClass('limited');
            jQuery(this).attr('title', 'Limit exceeded');
        }
        var fieldResult = '<div id="' + fieldId + '" class="mg-editable input-group draggables ' + fieldFromParent + '" style="position:absolute; top:' + offSetTop + '0px; left:' + offSetPosition + '0px;">' +
            '<div class="form-control wysi-field">' + fieldValue + '</div>' +
            '<span class="input-group-addon click-container">' +
            '<span class="glyphicon glyphicon-edit edit" style="cursor: pointer"></span>&nbsp;' +
            '<span class="glyphicon glyphicon-remove exit" style="cursor: pointer;"></span>' +
            '</span>' +
            '<div class="clearfix"></div>' +
            '<div class="input-group wysi" style="display:none;">'
            //+'<button type="button" class="btn btn-danger btn-sm exit">Remove</button>'
            +
            '<button type="button" class="btn btn-success btn-sm save"><span class="glyphicon glyphicon-ok" ></span></button>' +
            '</div>' +
            '</div>';
        var target;
        if (isVisible(jQuery('#droppable'))) {
            target = '#droppable';
        } else {
            target = '#back-side-content-id';
        }
        jQuery(target).append(fieldResult);
        //		jQuery(".draggables").draggable({containment: "#droppable"});
        jQuery(".draggables").resizable({
            minHeight: 4,
            maxHeight: 10
        });
        //		jQuery(".draggables").selectable();
    });

    jQuery('.editing').click(function () {
        jQuery('#landscapeButton').css('opacity', '0');
        jQuery('#portraitButton').css('opacity', '0');
    });

    jQuery('#save-bc').on('click', function () {
        loaderText('Saving your card...');
        loader.show();
        var url = jQuery(this).attr('data-url');
        var res = url.split('?');
        console.log(res);
        if (res.length > 1) {
            var process_completed = res[1].split('=')[1];
        }
        var date_string = getDateTime();
        console.log('process_completed ' + process_completed);
        jQuery.ajax({
            type: 'post',
            url: 'includes/create-business-card/save-card.php',
            data: {
                process_completed: process_completed,
                date_string: date_string
            },
            success: function (result) {
                window.location.href = 'my-own-cards.php';
            },
            error: function (result) {
                return;
            }
        });
    });

    jQuery('#discard').on('click', function () {
        var url = jQuery(this).attr('data-url');
        var res = url.split('?');
        console.log(res);
        //		if (res.length > 1){
        //			var process_completed = res[1].split('=')[1]; 
        //		}
        swal({
            title: "Please, confirm!",
            text: "Are you sure you want to exit? ll your changes will be permanently lost.",
            html: true,
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#ff6603",
            confirmButtonText: "Ok",
            closeOnConfirm: true
        }, function isConfirm() {
            if (isConfirm) {
                window.location.href = globalURL + url;
            } else {
                return;
            }
        });
    });

}); //end jQuery