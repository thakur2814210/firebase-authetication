var SearchPageNamespace = {};
var filteredCards = [];
SearchPageNamespace.screenWidth = jQuery(window).width();
var CardCollection = [],
  globalContacts,
  GlobalContactsInFolder,
  globalSearch = true;
var templates = [
  { name: 'product', path: 'card-contacts/product.hbs' },
  { name: 'professional', path: 'card-contacts/professional.hbs' },
  { name: 'corporate', path: 'card-contacts/corporate.hbs' },
  { name: 'personal', path: 'card-contacts/personal.hbs' },
  { name: 'product_private', path: 'card-contacts/product_private.hbs' },
  { name: 'professional_private', path: 'card-contacts/professional_private.hbs' },
  { name: 'corporate_private', path: 'card-contacts/corporate_private.hbs' },
  { name: 'personal_private', path: 'card-contacts/personal_private.hbs' },
  { name: 'card_panel_search', path: 'card-contacts/card_panel_search.hbs' },
  { name: 'card_status_bar', path: 'card-contacts/card_status_bar.hbs' },
  { name: 'mutual_contacts', path: 'card-contacts/mutual_contacts.hbs' },
  { name: 'mutual_contacts_pro', path: 'card-contacts/mutual_contacts_prod.hbs' },
  { name: 'navtabs_search', path: 'card-contacts/navtabs_search.hbs' },
  { name: 'card_row', path: 'card-contacts/card_row_search.hbs' }, //modified card_row_search
  { name: 'card_mutual_contacts', path: 'card-contacts/card_mutual_contacts.hbs' }, //added mg 20/01/2016
  { name: 'card_grid_view', path: 'card-contacts/card_grid_view.hbs' },
  { name: 'card_flip', path: 'card-contacts/card_flip.hbs' },
  { name: 'card_details', path: 'card-contacts/card_details.hbs' },
  { name: 'card_details_mutual_contacts', path: 'card-contacts/card_details_mutual_contacts.hbs' },
  { name: 'link_requests', path: 'card-contacts/link_requests.hbs' },
  { name: 'card_details_comments', path: 'card-contacts/card_details_comments.hbs' },
  { name: 'link_requests', path: 'card-contacts/link_requests.hbs' },
  { name: 'folders_dash', path: 'card-contacts/folders_dash.hbs' },
  { name: 'folders_all', path: 'card-contacts/folders_all.hbs' },
  { name: 'card_row_folder_content', path: 'card-contacts/card_row_folder_content.hbs' }, //cards in folder
  { name: 'card_row_folder_content_table', path: 'card-contacts/card_row_folder_content_table.hbs' }, //cards in folder
  { name: 'sort_by_dropdown', path: 'card-contacts/sort_drop_down.hbs' },
  { name: 'my_own_cards', path: 'card-contacts/my_own_cards.hbs' }
];
//History.js initialization
(function (window, undefined) {
  //	History.Adapter.bind(window, 'statechange', function () {
  //		var State = History.getState();
  //	});
})(window);
var enhancedContacts = [];
function isVisible(selector) {
  return !(jQuery(selector).css('visibility') == 'hidden');
}
//first load and cache templates, then do the rest
jQuery(document).on("templatesLoaded", continueLoading);
function continueLoading() {
  //	var searchTerm = location.search;
  //	if (searchTerm) {
  //		//set search value on input element to query parameter in url, if one is present
  //		var el = jQuery("#searchbox");
  //		var findTerm = searchTerm.split("=")[1];
  //		var decodeTerm = decodeURI(findTerm);
  //		el.val(decodeTerm);
  //		searchPressed();
  //	}
  //initSidr();
}
function performSearch(searchValue) {
  var decoded = decodeURI(searchValue);
  //set query parameter in url
  //	History.pushState(null, null, "?term=" + searchValue);
  loadData(searchValue);
}
function searchPressed() {
  var searchValue = jQuery("#searchbox").val();
  performSearch(searchValue, ".card-box-sec");
}
jQuery(document).on("click", "#btnList", function () {
  var searchValue = jQuery("#searchbox").val();
  performSearch(searchValue, ".card-box-sec");
});
jQuery(document).on("click", "#btnGrid", function () {
  //	console.log('in btnGrid click event');
  performSearch("#searchbox", "card_grid_view");
});
function initGridView(card_view, details_view, elClass) {
  //	console.log('in initGridView');
  var card_flip_html = parseAndReturnHandlebarsRev2("card_grid_view", { elClass: elClass });
  //	console.log(card_flip_html);
  var card_flip_j = jQuery(card_flip_html);
  jQuery(".front", card_flip_j).append(card_view);
  jQuery(".back", card_flip_j).append(details_view);
  cardsContainer.append(card_flip_j);
}
function initQuickFLip(card_view, details_view, elClass) {
  //	console.log('in initQuickFLip');
  var card_flip_html = parseAndReturnHandlebarsRev2("card_flip", { elClass: elClass });
  var card_flip_j = jQuery(card_flip_html);
  jQuery(".front", card_flip_j).append(card_view);
  jQuery(".back", card_flip_j).append(details_view);
  cardsContainer.append(card_flip_j);
}
function flip(obj) {
  jQuery(obj).next().quickFlipper();
}
function isOdd(n) {
  return Math.abs(n % 2) == 1;
}
function isEven(n) {
  return n % 2 == 0;
}
function loadComments(card_id) {
  //	console.log('enter loadComments');
  var html;
  jQuery.ajax({
    type: "post",
    url: "includes/mg_get_comments_to_card.php",
    data: { card_id: card_id },
    success: function (data) {
      if (data !== 'false') {
        //				console.log(data);
        var response = JSON.parse(data);
        html = '<strong>All Comments <a href="#">(' + response.length + ')</a></strong>';
        jQuery(response).each(function (i, c) {
          var card_comments = {
            user_id: c.user_id,
            full_name: c.full_name,
            comment_id: c.comment_id,
            comment: c.comment,
            timestamp: c.timestamp
          };
          var comment = parseAndReturnHandlebarsRev2('card_details_comments', card_comments);
          html = html + comment;
        });
        //				jQuery('.comment-scroller').html(html);
        jQuery('.comment-scroller').html(html);
        if (jQuery('.scrolling-area')) {
          jQuery('.scrolling-area').mCustomScrollbar("destroy");
        }
        jQuery('.scrolling-area').mCustomScrollbar();
        //				console.log('exit loadComments');
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });
}
/*
 * code coming from satish script.js
 * modified by mg 20/01/2016
 * to get mutual contacts on hovering
 */
function getCardSettings(card_id) {
  jQuery.ajax({
    type: "POST",
    data: { card_id: card_id },
    url: "includes/view-business-card/get_card_settings.php",
    success: function (data) {
      //			console.log(data);
      //			var response = JSON.parse(data);
      if (data === 'expired') {
        window.location.href = globalURL;
        return;
      }
      if (data['private'] == 1) {
        jQuery('#checkboxG5').attr("checked", true);
      }
      jQuery('#rating-input').rating('update', data['rating']);
    },
    dataType: 'json',
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });
}
function showContactProfile(user_id) {
  loaderText('Loading profile...');
  loader.show();
  var card = JSON.parse(localStorage.getItem('contact-prof-' + user_id));
  jQuery('#u-prof-name-profile').html(card.contact_first_name + card.contact_last_name + "'s " + " Profile");
  jQuery('#u-prof-profile-image').attr('src', card.contact_profile_image);
  jQuery('#u-prof-full-name').html('<a href="#" class="avatar-title"><span class="full-name">' + card.contact_first_name + " " + card.contact_last_name + '</span></a>');
  jQuery('#u-prof-role').html(card.contact_company_name);
  jQuery('#u-prof-email-address').html('<a href="mailto:' + card.contact_email_address + '" class="address-link">' + card.contact_email_address + '</a>');
  var phones;
  if (card.contact_phone.length > 0 && card.contact_mobile.length > 0) {
    phones = 't- <a href="tel:+' + card.contact_phone + '">' + card.contact_phone + '</a>   |   m- <a href="tel:+' + card.contact_mobile + '">' + card.contact_mobile + '</a>';
  } else if (card.contact_phone.length > 0 && card.contact_mobile.length <= 0) {
    phones = 't- <a href="tel:+' + card.contact_phone + '">' + card.contact_phone + '</a>';
  } else if (card.contact_phone.length <= 0 && card.contact_mobile.length > 0) {
    phones = 'm- <a href="tel:+' + card.contact_mobile + '">' + card.contact_mobile + '</a>';
  }
  jQuery('#u-prof-phones').html(phones);
  jQuery('#u-prof-website-link').html('<a href="' + card.contact_website_link + '" class="address-link">' + card.contact_website_link + '</a>');
  jQuery('#u-prof-card-status').hide();
  //	if(loc==='cardDetail'){
  loader.hide();
  if (jQuery('#searchSecArea').css('display') === 'block') {
    jQuery('body').addClass('active');
    jQuery('#superOverLay').slideDown();
    jQuery('#avatarSlideSec').slideDown();
    jQuery("html, body").animate({ scrollTop: 0 }, "slow");
  } else {
    if (jQuery('#userCardDetail').css('display') == 'block') {
      jQuery('body').addClass('active');
      jQuery('#superOverLay').slideDown();
      jQuery('#avatarSlideSec').slideDown();
      jQuery("html, body").animate({ scrollTop: 0 }, "slow");
    } else {
      jQuery('body').addClass('active');
      //		jQuery('#superOverLay').slideDown();
      jQuery('#overLay').slideDown();
      jQuery('#avatarSlideSec').slideDown();
      jQuery("html, body").animate({ scrollTop: 0 }, "slow");
    }
  }
}
function getMutualContacts(target, card_id, template) {
  //	loader.show();
  var html = '';
  var cardViewContext = JSON.parse(localStorage.getItem(card_id));
  //	console.log('cardViewContext in getMutualContacts');
  //	console.log(cardViewContext);
  var card_type_professional = cardViewContext.card_type_professional;
  var card_added = false;
  //	console.log('card_type_professional is ' + card_type_professional + ' in getMutualContacts()')
  jQuery.ajax({
    type: "GET",
    data: { card_id: card_id },
    url: "includes/card-contacts/get_mutual_contacts.php",
    success: function (data) {
      // console.log(data);
      if (data === 'expired') {
        window.location.href = globalURL;
        return;
      }
      var response = JSON.parse(data);
      if (response.data.length > 0) {
        if (response.data.length === 1) {
          html += '<h4 id="title_count">There is ' + response.data.length + ' mutual holder of this card</h4>';
        } else {
          html += '<h4 id="title_count">There are ' + response.data.length + ' mutual holders of this card</h4>';
        }
        var prof_image;
        jQuery.each(response.data, function (i, item) {
          if (item.contact_profile_image == null) {
            prof_image = 'assets/img/def_avatar.gif';
          } else {
            prof_image = item.contact_profile_image;
          }
          //					console.log('your_contact = ' + item.your_contact);
          if (item.your_contact) {
            card_added = true;
          }
          //					console.log('card_added = ' + card_added);
          var context = {
            contact_profile_image: prof_image,
            contact_user_id: item.contact_user_id,
            contact_first_name: item.contact_first_name,
            contact_last_name: item.contact_last_name,
            contact_phone: item.contact_phone,
            contact_mobile: item.contact_mobile,
            contact_email_address: item.contact_email_address,
            contact_website_link: item.contact_website_link,
            contact_company_name: item.contact_company_name,
            bcn: item.bcn,
            contact_rating: item.contact_rating,
            card_type_professional: card_type_professional,
            card_added: card_added
          };
          localStorage.setItem('contact-prof-' + item.contact_user_id, JSON.stringify(context));
          //					console.log(JSON.parse(localStorage.getItem('contact-prof-'+item.contact_user_id)));
          //					console.log('rating for '+card_id+' is '+item.rating);
          if (isEven(i)) {
            html += '<div class="user-card-sec u_c_s' + card_id + '">';
            // which becomes new target
            html += parseAndReturnHandlebarsRev2(template, context);
          } else {
            html += parseAndReturnHandlebarsRev2(template, context);
            html += '</div>';//close user-card-sec
          }
        });
        jQuery(target).html(html);
        /*re-initialization of rating is needed*/
        jQuery('.average-rating').rating({
          min: 0,
          max: 5,
          step: 0.5,
          rtl: false,
          showCaption: false,
          showClear: false,
          size: 'xxxs',
          readonly: true
        });
      } else {
        jQuery(target).html("<div class=\"well well-sm details-half-page-ex personal\"><p class=\"lead\">No Mutual Contacts</p></div>");
      }
      //			loader.hide();
    },
    error: function () {
      console.log('error in setContacts');
    }
  });
}
//string to put in card_row_search2 if Fabien wants links around names:	<a href="#" class="show-card-details">{{full_name}}</a><br>
function loadData(searchTerm) {
  loaderText('Loading cards...');
  loader.show();
  var url;
  localStorage.removeItem("cardViewContext");
  url = "includes/card-contacts/search_cards.php";
  var data = { term: searchTerm };
  //	console.log(url);
  jQuery.ajax({
    type: "POST",
    data: data,
    url: url,
    success: function (data) {
      console.log(data);
      var response = JSON.parse(data);
      if (data === 'expired') {
        window.location.href = globalURL;
        return;
      }
      if (!response.success) {
        loader.hide();
        swal({
          title: "Error!",
          text: "Something went wrong while trying to retrieve search data!",
          html: true,
          type: "error",
          showCancelButton: false,
          confirmButtonColor: "#ff6603"
        });
        return;
      } else {
        //				jQuery("#search_header").show();
        jQuery('#search_text').html(decodeURI(searchTerm));
        var contactsLength = response.result.length;
        if (contactsLength > 0) {
          jQuery("#contact_count").html(contactsLength);
          //					CardCollection = response.result;
          globalContacts = response.result;
          showCards(response.result, true);
          //					jQuery(".quickflip-wrapper").quickFlip();
        } else {
          jQuery('#result-count').text('No cards found matching your search criteria');
          jQuery(".card-box-sec").empty();
          jQuery(".card-box-sec").append(jQuery('<p class="lead bump-right">').append("No Results"));
          loader.hide();
        }
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });
}
function showCards(cards, visible) {
  //	alert('showCards');
  //clear cards
  jQuery(".card-box-sec").empty();
  var d = new Date();
  var t = d.getTime();
  //add cards
  var l = cards.length, i;
  if (l === 1) {
    jQuery('#result-count').text(l + ' Card matching your search criteria');
  } else {
    jQuery('#result-count').text(l + ' Cards matching your search criteria');
  }
  var allFoundCards = [];
  for (i = 0; i < l; i++) {
    card = cards[i];
    var private = false;
    if (card.need_approval && !card.your_contact) {
      private = true;
    }
    var profile_image;
    if (typeof card.profile_image === 'object') {
      profile_image = 'assets/img/def_avatar.gif';
    } else {
      profile_image = card.profile_image;
    }
    var card_type_personal;
    var card_type_professional;
    if (card.card_type.toLowerCase() === 'personal' || card.card_type.toLowerCase() === 'corporate') {
      card_type_personal = true;
      card_type_professional = false;
    } else {
      card_type_personal = false;
      card_type_professional = true;
    }
    var rating_na = false;
    if (card.average_rating == null) {
      rating_na = true;
    }
    var cardViewContext = {
      profile_image: profile_image,
      card_image_url: card.canvas_front,
      card_back_image_url: card.canvas_back,
      links_front: card.links_front,
      links_back: card.links_back,
      //added 27/01/2016: avoids to use nother query
      //to get ratable cards: just added code from models.php class CardStatusPermissions
      //to model.php class SearchedCard to get the value of allow rating in only one query
      card_allow_rating: card.allow_rating,
      //			card_allow_rating: allow_rating,
      //end
      src_id: "src" + card.card_id,
      card_id: card.card_id,
      card_name: card.card_name,
      your_contact: card.your_contact,
      need_approval: card.need_approval,
      can_share: card.can_share,
      link_status: card.link_status,
      first_name: card.first_name,
      //added 22/01/2016
      last_name: card.last_name,
      full_name: card.full_name,
      //end
      //added 01/03/2016 to make visible company name in profile details
      company_name: card.company_name,
      //end
      email_address: card.email_address,
      website_link: card.website_link,
      phone: card.phone,
      mobile: card.mobile,
      pending_request: card.pending_request,
      requires_reciprocity: card.requires_reciprocity,
      with_zoom: true,
      landscape: card.landscape,
      //added 21/01/2016 mg
      card_type: card.card_type,
      card_type_personal: card_type_personal,
      card_type_professional: card_type_professional,
      card_private: private,
      //end
      seen_in_user_folder: card.seen_in_user_folder,
      your_rating: card.your_rating,
      average_rating: card.average_rating,
      rating_na: rating_na,
      timestamp: t,
      bcn: card.bcn
    };
    var cardProfile = {
      profile_image: profile_image,
      bcn: card.bcn,
      first_name: card.first_name,
      last_name: card.last_name,
      full_name: card.full_name,
      email_address: card.email_address,
      website_link: card.website_link,
      phone: card.phone,
      mobile: card.mobile,
      your_contact: card.your_contact,
      need_approval: card.need_approval,
      pending_request: card.pending_request,
      requires_reciprocity: card.requires_reciprocity
    };
    //		console.log(card.card_id+': '+card.average_rating);
    console.log('cardViewContext in showCards is ');
    console.log(cardViewContext);
    //		console.log('card_id ' + cardViewContext.card_id + ' has allow_rating set to ' + cardViewContext.card_allow_rating);
    allFoundCards.push(cardViewContext);
    localStorage.setItem(card.card_id, JSON.stringify(cardViewContext));
    localStorage.setItem('prof' + card.card_id, JSON.stringify(cardProfile));
    enhancedContacts.push(cardViewContext);
    var card_row_html, card_row;
    if (visible) {
      card_row_html = parseAndReturnHandlebarsRev2("card_row", cardViewContext);
      card_row = jQuery(card_row_html);
      jQuery(".card-box-sec").append(card_row);
    }
  }
  //	console.log('CardCollection');
  //	console.log(CardCollection);
  localStorage.setItem('allFoundCards', JSON.stringify(allFoundCards));
  setTimeout(function () {
    jQuery('.average-rating').rating({
      min: 0,
      max: 5,
      step: 0.5,
      rtl: false,
      showCaption: false,
      showClear: false,
      size: 'xxxs',
      readonly: true
    });
  }, 100);
  loader.hide();
}
function loadDataFilterable(searchTerm) {
  loaderText('Loading cards...');
  loader.show();
  var url;
  //	console.log('loadDataFilterable ' + searchTerm);
  url = "includes/card-contacts/search_cards_in_folder.php";
  var data = { folder: searchTerm };
  if (searchTerm === 'pc') {
    jQuery('#page-title').text('Personal / Corporate Cards Folder');
    jQuery('#page-title').attr('data-folder-id', 'pc');
  } else if (searchTerm === 'sp') {
    jQuery('#page-title').text('Product / Service Cards Folder');
    jQuery('#page-title').attr('data-folder-id', 'sp');
  } else {
    var cFolders = JSON.parse(localStorage.getItem('customFolders'));
    for (var i = 0; i < cFolders.length; i++) {
      if (cFolders[i].folder_id === searchTerm) {
        var folderName = cFolders[i].folder_description;
      }
    }
    jQuery('#page-title').text(folderName + ' Cards Folder');
    jQuery('#page-title').attr('data-folder-id', searchTerm);
  }
  //	console.log('loadDataFilterable ');
  //	console.log(data);
  //	console.log('url');
  //	console.log(url);
  jQuery.ajax({
    type: "post",
    data: data,
    url: url,
    success: function (data) {
      console.log('data');
      console.log(data);
      var response = JSON.parse(data);
      if (response === 'expired') {
        window.location.href = globalURL;
        return;
      }
      //			console.log(response);
      if (!response.success) {
        loader.hide();
        swal({
          title: "Error!",
          text: "Something went wrong while trying to retrieve search data!",
          html: true,
          type: "error",
          showCancelButton: false,
          confirmButtonColor: "#ff6603",
          closeOnConfirm: true
        });
        return;
      } else {
        loader.hide();
        var contactsLength = response.result.length;
        globalContacts = response.result;
        //				console.log('response.result');
        //				console.log(response.result);
        if (contactsLength > 0) {
          // if (contactsLength === 1) {
          //     jQuery('#result-count-in-folder').text(contactsLength + ' Card in this folder');
          // } else {
          //     jQuery('#result-count-in-folder').text(contactsLength + ' Cards in this folder');
          // }
          GlobalContactsInFolder = response.result;
          enhancedContacts = _.map(GlobalContactsInFolder, function (contact) {
            contact.numeric_id = parseInt(contact.card_id);
            var parsedFloat = parseFloat(contact.rating);
            if (parsedFloat)
              contact.rating = parsedFloat;
            else
              contact.rating = 0;
            return contact;
          });
          setTimeout(function () {
            console.log('response.result');
            console.log(response.result);
            showCardsFilterable(response.result);
            updateCheckbox();
          }, 1000);
        } else {
          jQuery('#result-count-in-folder').text('This folder is empty');
          jQuery(".my-cards").children().remove();
        }
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(jqXHR.status);
      console.log(jqXHR.statusText);
      console.log(textStatus, errorThrown);
    }
  });
}
function showCardsFilterable(cards) {
  //	alert('showCardsFilterable');
  //	console.log('cards in showCardsFilterable');
  //	console.log(cards);
  //clear cards
  jQuery('.my-cards').empty();
  jQuery('#card-details-table').empty();
  var d = new Date();
  var t = d.getTime();
  //add cards
  var l = cards.length, i;

  var allFoundCards = [];
  var notInContacts = '(';
  for (i = 0; i < l; i++) {
    card = cards[i];
    var private = false;
    if (card.need_approval && !card.your_contact) {
      private = true;
    }
    var profile_image;
    if (typeof card.profile_image === 'object') {
      profile_image = 'assets/img/def_avatar.gif';
    } else {
      profile_image = card.profile_image;
    }
    var card_type_personal;
    var card_type_professional;
    if (card.card_type.toLowerCase() === 'personal' || card.card_type.toLowerCase() === 'corporate') {
      card_type_personal = true;
      card_type_professional = false;
    } else {
      card_type_personal = false;
      card_type_professional = true;
    }
    var rating_na = false;
    if (card.average_rating == null) {
      rating_na = true;
    }
    var cardViewContext = {
      profile_image: profile_image,
      card_image_url: card.canvas_front,
      card_back_image_url: card.canvas_back,
      links_front: card.links_front,
      links_back: card.links_back,
      //added 27/01/2016: avoids to use another query
      //to get ratable cards: just added code from models.php class CardStatusPermissions
      //to model.php class SearchedCard to get the value of allow rating in only one query
      card_allow_rating: card.allow_rating,
      //			card_allow_rating: allow_rating,
      //end
      src_id: "src" + card.card_id,
      card_id: card.card_id,
      card_name: card.card_name,
      your_contact: card.your_contact,
      need_approval: card.need_approval,
      can_share: card.can_share,
      link_status: card.link_status,
      first_name: card.first_name,
      //added 22/01/2016
      last_name: card.last_name,
      full_name: card.full_name,
      //end
      email_address: card.email_address,
      website_link: card.website_link,
      phone: card.phone,
      mobile: card.mobile,
      company_name: card.company_name,
      distributed_brand: card.distributed_brand,
      category: card.category,
      sub_category: card.sub_category,
      pending_request: card.pending_request,
      requires_reciprocity: card.requires_reciprocity,
      with_zoom: true,
      landscape: card.landscape,
      orientation: card.orientation,
      //added 21/01/2016 mg
      card_type: card.card_type,
      card_type_personal: card_type_personal,
      card_type_professional: card_type_professional,
      card_private: private,
      //end
      seen_in_user_folder: card.seen_in_user_folder,
      your_rating: card.your_rating,
      average_rating: card.average_rating,
      rating_na: rating_na,
      timestamp: t,
      bcn: card.bcn
    };
    var cardProfile = {
      profile_image: profile_image,
      bcn: card.bcn,
      first_name: card.first_name,
      last_name: card.last_name,
      full_name: card.full_name,
      email_address: card.email_address,
      website_link: card.website_link,
      phone: card.phone,
      mobile: card.mobile,
      your_contact: card.your_contact,
      need_approval: card.need_approval,
      pending_request: card.pending_request,
      requires_reciprocity: card.requires_reciprocity,
    };
    allFoundCards.push(cardViewContext);
    localStorage.setItem(card.card_id, JSON.stringify(cardViewContext));
    localStorage.setItem('prof' + card.card_id, JSON.stringify(cardProfile));
    var card_row_html, card_row;

    if (card.your_contact) {
      CardCollection.push({ key: 'contact', value: card.card_id });
      filteredCards.push(cardViewContext);
      //			console.log('passing card_row_folder_content');
      card_row_html = parseAndReturnHandlebarsRev2("card_row_folder_content", cardViewContext);
      card_row = jQuery(card_row_html);
      jQuery('.my-cards').append(card_row);
      card_row_html = parseAndReturnHandlebarsRev2("card_row_folder_content_table", cardViewContext);
      card_row = jQuery(card_row_html);
      jQuery('#card-details-table').append(card_row);
    } else {
      console.log('not your_contact: '+card.card_id);
      // not your_contact: 5c1670f992e82
      // not your_contact: 5cb218a15b9af
      // not your_contact: 5d0779474a58e
    }
  }
  if (filteredCards.length === 1) {
    jQuery('#result-count-in-folder').text(filteredCards.length + ' Card in this folder');
  } else {
    jQuery('#result-count-in-folder').text(filteredCards.length + ' Cards in this folder');
  }
  //	console.log('filteredCards');
  //	console.log(filteredCards);
  localStorage.setItem('allFoundCards', JSON.stringify(allFoundCards));
  loadSortingDropdown();
  loadFilteringDropDown();
  loadDropdownFolder();
  setTimeout(function () {
    jQuery('.average-rating').rating({
      min: 0,
      max: 5,
      step: 0.5,
      rtl: false,
      showCaption: false,
      showClear: false,
      size: 'xxxs',
      readonly: true
    });
  }, 100);
  loader.hide();
}

function loadDataByBcn(searchTerm) {
  loaderText('Loading card...');
  loader.show();
  var url;
  localStorage.removeItem("cardViewContext");
  url = "includes/card-contacts/search_cards_by_bcn.php";
  var data = { term: searchTerm };
  //	console.log(url);
  jQuery.ajax({
    type: "POST",
    data: data,
    url: url,
    success: function (data) {
      //			console.log(data);
      var response = JSON.parse(data);
      if (data === 'expired') {
        window.location.href = globalURL;
        return;
      }
      if (!response.success) {
        loader.hide();
        swal({
          title: "Error!",
          text: "Something went wrong while trying to retrieve search data!",
          html: true,
          type: "error",
          showCancelButton: false,
          confirmButtonColor: "#ff6603"
        });
        return;
      } else {
        //				jQuery("#search_header").show();
        jQuery('#search_text').html(decodeURI(searchTerm));
        var contactsLength = response.result.length;
        if (contactsLength > 0) {
          console.log('calling showCards ');
          //					localStorage.setItem(card.card_id, JSON.stringify(cardViewContext));
          jQuery("#contact_count").html(contactsLength);
          //					CardCollection = response.result;
          globalContacts = response.result;
          showCards(response.result, false);
          //					jQuery(".quickflip-wrapper").quickFlip();
        } else {
          jQuery('#result-count').text('No cards found matching your search criteria');
          jQuery(".card-box-sec").empty();
          jQuery(".card-box-sec").append(jQuery('<p class="lead bump-right">').append("No Results"));
          loader.hide();
        }
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });
}

function getFolders(requestingPage, callback) {
  jQuery('.spinner-overlay.spinner-sidebar').fadeIn();
  var foldersContext = {
    cFolders: []
  };
  var folderList = [];
  var defferds = [];
  getCustomFolder().success(function (data) {
    console.log('data');
    console.log(data);

    var response = JSON.parse(data);
    if (response === 'expired') {
      window.location.href = globalURL;
      return;
    }
    if (response.success === 1) {
      var folders = response.result;
      console.log(folders);
      if (folders.length > 0) {
        var i = 0, l = folders.length;
        for (i; i < l; i++) {
          var customFolder = {};
          customFolder.folder_id = folders[i].folder_id;
          customFolder.folder_description = folders[i].description;
          customFolder.cards_amount = folders[i].cards_amount;
          defferds.push(customFolder.cards_amount);
          folderList.push(customFolder);
        }
      }
      localStorage.setItem('customFolders', JSON.stringify(folderList));
    }
  }).done(function () {
    setTimeout(function () {
      jQuery.when.apply(jQuery, defferds).done(function () {
        //				console.log('foldersContext in getFolders');
        //				console.log(foldersContext);
        foldersContext.cFolders = folderList;
        callback(foldersContext, requestingPage);
      });
    }, 1000);
    jQuery('.spinner-overlay.spinner-sidebar').fadeOut();
  });
}
function getCustomFolder() {
  return jQuery.ajax({
    //		async: false,
    type: "GET",
    url: 'includes/manage-folders/mg_get_folders.php',
    data: {}
  });
}
function fillPage(foldersContext, requestingPage) {
  var html;
  //	console.log('foldersContext in fillPage');
  //	console.log(foldersContext);
  if (requestingPage === 'all-folders') {
    for (var key in foldersContext) {
      //		if (foldersContext.hasOwnProperty(key)) {
      //			if (jQuery.type(key) === 'object') {
      html = parseAndReturnHandlebarsRev2("folders_all", foldersContext);
    }
  } else if (requestingPage === 'dashboard') {
    html = parseAndReturnHandlebarsRev2("folders_dash", foldersContext);
  }
  //	console.log('html is');
  //	console.log(html);
  jQuery('.all-folders').html(html);
  jQuery('.horizontal-scroll').mCustomScrollbar();
  jQuery('.all-folders li:nth-child(1) div#pc').removeClass('new-folder');
  jQuery('.all-folders li:nth-child(1) div#pc div.checkboxes').remove();
  jQuery('.all-folders li:nth-child(2) div#sp').removeClass('new-folder').addClass('service-product');
  jQuery('.all-folders li:nth-child(2) div#sp div.checkboxes').remove();
}
function fillSidebarMenu(foldersContext, requestingPage) {
  jQuery('#folders ul.inner-sidebar-links').children().remove();
  var fixedFolders = '<li><a href="all-folders.php">all folders</a></li>';
  jQuery('#folders ul.inner-sidebar-links').append(fixedFolders);
  for (var i = 0; i < foldersContext.cFolders.length; i++) {
    var data = { 'folder': foldersContext.cFolders[i].folder_id };
    var fd_count = foldersContext.cFolders[i].cards_amount;
    if (typeof fd_count === 'undefined') {
      fd_count = '0';
    }
    jQuery('#folders ul.inner-sidebar-links').append('<li><a href="folder-content.php?folder=' + data.folder + '">' + foldersContext.cFolders[i].folder_description + '</a></li>');
    //		jQuery('#folders ul.inner-sidebar-links').append('<li><a href="folder-content.php?folder=' + data.folder + '">' + foldersContext.cFolders[i].folder_description + ' (' + fd_count + ')</a></li>');
  }
}
function updateCheckbox() {
  var total_cards = jQuery('.card-box').filter(':visible');
  //	console.log('total_cards length updating checkbox ' + total_cards.length);
  if (total_cards.length == 0) {
    jQuery('.commanCheckBox').prop('checked', false);
    jQuery('a.card-selector').removeClass('select-all');
    jQuery('.checkboxStyle').removeClass('active');
  } else {
    var checked_cards = total_cards.filter('.active');
    //		console.log('checked_cards length in upadte chackboxes ' + checked_cards.length);
    if (checked_cards.length == 0) {
      jQuery('.commanCheckBox').prop('checked', false);
      jQuery('a.card-selector').removeClass('select-all');
      jQuery('.checkboxStyle').removeClass('active');
    } else if (checked_cards.length == total_cards.length) {
      jQuery('.commanCheckBox').prop('checked', true);
      jQuery('a.card-selector').addClass('select-all');
      jQuery('.checkboxStyle').removeClass('active');
    } else {
      jQuery('.commanCheckBox').prop('checked', false);
      jQuery('a.card-selector').removeClass('select-all');
      jQuery('.checkboxStyle').addClass('active');
    }
  }
}

function loadCards() {
  loaderText('Loading cards...');
  loader.show();
  jQuery.ajax({
    type: "POST",
    data: '',
    url: "includes/manage-your-cards/get_your_cards.json.php",
    success: function (data) {
      var response = JSON.parse(data);
      if (response === 'expired') {
        window.location.href = globalURL;
        return;
      }
      if (response.success) {
        setTimeout(function () {
          jQuery('.my-cards').children().remove();
          var i = 0, l = response.result.length;
          for (i; i < l; i++) {
            var card = response.result[i];
            // console.log(card);
            var html = parseAndReturnHandlebarsRev2("my_own_cards", card);
            jQuery('.my-cards').append(html);
            //						if ( i === ( l - 1 ) ) {
            //							var html = '<div class="card-box add-new-card"><div class="folder-info"><a href="create-card-0.php"><img src="assets/sat_images/add-new-card.png" alt=""><img src="assets/sat_images/add-coment.png" alt="" class="add-link-img"></a></div></div>';
            //							jQuery('.my-cards').append(html);
            //						}
          }
          var html = '<div class="card-box add-new-card"><div id="disabling-overlay"></div><div class="folder-info"><a href="create-card-0.php"><img src="assets/sat_images/add-new-card.png" alt=""><img src="assets/sat_images/add-coment.png" alt="" class="add-link-img"></a></div></div>';
          jQuery('.my-cards').append(html);

        }, 500);
        loader.hide();
      } else {
        //panic
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });
}
/******************************************************************************
 *
 *										EDITING CARD SETTNGS
 *
 ******************************************************************************/
function determineCardPropsBasedOnType(card_type) {
  var urlBase = globalURL + 'includes/manage-your-cards/',
    keyword = "";
  card_type = card_type.toLowerCase();
  if (card_type == "personal" || card_type == "corporate") {
    keyword = "personal";
  } else if (card_type == "professional" || card_type == "service" || card_type == "product") {
    keyword = "professional";
  } else {
    showErrorModalWithMsg('Invalid Card Type!');
  }
  return {
    url: urlBase + "r_card_settings_" + keyword + ".json.php",
    selector: "#card-" + keyword + "-settings",
    frmSelector: "#settings-checkboxes-" + keyword
  };
}
//U
function editCardSettings(card_id, card_type) {
  //	console.log('editing card settings');
  card_type = card_type.toLowerCase();
  var d = determineCardPropsBasedOnType(card_type);
  var selector = d.selector;
  //	console.log('selector');
  //	console.log(selector);
  var frmSelector = d.frmSelector;
  var url = d.url + "?card_id=" + card_id;
  //	console.log(url);
  //	console.log(frmSelector);
  jQuery.ajax({
    type: "GET",
    url: url,
    success: function (data) {
      var response = JSON.parse(data);
      //			if ( response.success ) {
      var settings = response.data;
      //			console.log('settings');
      //			console.log(settings);
      //				jQuery(function () {
      var settings_object = {};
      var settings_array = [];
      jQuery(frmSelector + ' input:checkbox').each(function (input) {
        var el = jQuery(this);
        var s = settings;
        if (card_type == "personal" || card_type == "corporate") {
          s = settings[0];
        }
        var isChecked = (s[el.attr("id")] === "0") ? false : true;
        //				console.log('setting id ' + el.prop('id'));
        el.prop("checked", isChecked);
        el.data("set", "yes");
        if (isChecked) {
          settings_array.push(el.prop('id'));
          el.next('label').children('span').text(settings["plan_name"]);
        } else {
          el.next('label').children('span').text('');
          var index = settings_array.indexOf(el.prop('id'));
          if (index > -1) {
            settings_array.splice(index);
          }
        }
      });
      //			localStorage.setItem('settings_' + card_id, JSON.stringify(settings_array));
      //			var check = JSON.parse(localStorage.getItem('settings_' + card_id));
      //			console.log('editing ' + card_id);
      //			console.log('check: ' + check);
      //			function to get the variable type in javascript. See https://javascriptweblog.wordpress.com/2011/08/08/fixing-the-javascript-typeof-operator/
      //			var toType = function (obj) {
      //				return ( {} ).toString.call(obj).match(/\s([a-zA-Z]+)/)[1].toLowerCase();
      //			};
      //			console.log('check type: ' + toType(check));
      jQuery(frmSelector).attr("data-card-id", card_id);
      jQuery('body').addClass('active');
      jQuery('#overLay').slideDown();
      jQuery(selector).slideDown();
      jQuery("html, body").animate({ scrollTop: 0 }, "slow");
      //checkSettingsConditions();
      //				});
      //			}
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });
}
//19/11/2015 mg added edit_mode to notify user is editing his card so when the update will be completed the email will be sent
function editCard(card_id, card_type) {
  localStorage.setItem("edit_mode", card_id);
  window.location.href = globalURL + 'create-card-3.php?card_id=' + card_id + '&card_type=' + card_type + '&edit_mode=' + true;
}
function removeCard(card_id) {
  //	onclick="removeCard('{{card_id}}', '{{card_type}}')"
  //hmm don't like this
  //TODO: expand modal method to accomodate callbacks
  var cardToBeDeleted = card_id;
  //Confirm
  swal({
    title: "Confirm Delete",
    text: "Your business card will be completely removed. Are you sure you want to continue, uh?",
    html: true,
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#ff6603",
    confirmButtonText: "Ok",
    closeOnConfirm: false
  }, function (isConfirm) {
    if (isConfirm) {
      loaderText('Removing card...');
      loader.show();
      var url = 'includes/manage-your-cards/d_card.php?card_id=' + cardToBeDeleted;
      jQuery.ajax({
        type: "GET",
        url: url,
        success: function (data) {
          console.log('my own cards data');
          console.log(data);
          var response = JSON.parse(data);
          if (response === 'expired') {
            window.location.href = globalURL;
            return;
          }
          if (response != 'success') {
            //						showErrorModalWithMsg(response.meta);
          } else if (response == 'success') {
            jQuery('#' + cardToBeDeleted).remove();
            loader.hide();
            swal({
              title: "Success!",
              text: "Card Removed Successfully!",
              html: true,
              type: "success",
              showCancelButton: false,
              confirmButtonColor: "#ff6603",
              confirmButtonText: "Ok",
              closeOnConfirm: true
            });
          } else {
            loader.hide();
            swal({
              title: "Error!",
              text: "Unknown Response From Server",
              html: true,
              type: "error",
              showCancelButton: false,
              confirmButtonColor: "#ff6603",
              confirmButtonText: "Ok",
              closeOnConfirm: true
            });
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.log('error deleting card');
        }
      });
    }
  });
}
var t_in, t_on;
jQuery(document).on('click', '#delete-card', function (e) {
  e.preventDefault();
  var card_id = jQuery(this).attr('data-card-id');
  //	alert(card_id);
  removeCard(card_id);
});
jQuery(document).on("click", "#save-personal-card-settings", function (e) {
  e.preventDefault();
  loaderText('Saving settings...');
  loader.show();
  var card_id = jQuery("#settings-checkboxes-personal").attr("data-card-id");
  //	console.log('card_id in save settings is ' + card_id);
  //	var url = "includes/manage-your-cards/u_card_settings.php?card_id=" + settingsFrm.data("card-id") + "&card_type=Personal";
  var url = "includes/manage-your-cards/mg_u_card_settings.php?card_id=" + card_id + "&card_type=Personal";
  //	var str = settingsFrm.serialize();
  var str = '';
  jQuery(document).find('#settings-checkboxes-personal input:checkbox').filter(':checked').each(function (input) {
    str += 'settings[]=' + jQuery(this).attr('id') + '&';
  });
  str = str.substr(0, str.length - 1);
  //	console.log('str');
  //	console.log(str);
  jQuery.post(url, str, function (data) {
    //		var response = JSON.parse(data);
    //		console.log('response.code is ', response.code);
    //		if ( response.code == 0 ) {
    if (data === 'expired') {
      window.location.href = globalURL;
      return;
    }
    if (data === 'unchanged') {
      loader.hide();
      //			console.log('settings unchanged');
      closePopup();
    } else if (data === 'success') {
      loader.hide();
      swal({
        title: "Success!",
        text: "Successfully Updated Settings!",
        html: true,
        type: "success",
        showCancelButton: false,
        confirmButtonColor: "#ff6603",
        confirmButtonText: "Ok",
        closeOnConfirm: true
      }, function (isConfirm) {
        if (isConfirm) {
          loader.hide();
          closePopup();
          loadCards();
        }
      });
    } else {
      swal({
        title: "Error!",
        text: "An error occurredt attempting to change your settings. Please, contact Cardition support team.",
        html: true,
        type: "error",
        showCancelButton: false,
        confirmButtonColor: "#ff6603",
        confirmButtonText: "Ok",
        closeOnConfirm: true
      });
      loader.hide();
      closePopup();
    }
  });
});
jQuery(document).on("click", "#save-professional-card-settings", function (e) {
  loaderText('Saving settings...');
  loader.show();
  e.preventDefault();
  //var can_share = jQuery('#share_among_users').is(':checked');
  var card_id = jQuery("#settings-checkboxes-professional").attr("data-card-id");
  var global_search = jQuery('#visible_pp_search').is(':checked');
  var allow_rating = jQuery('#allow_rating').is(':checked');
  if (!global_search && !allow_rating) {
    loader.hide();
    closePopup();
    return;
  }
  var options_changed = false;
  var settingsFrm = jQuery("#settings-checkboxes-professional");
  var url = "includes/manage-your-cards/mg_u_card_settings.php?card_id=" + card_id + "&card_type=Professional";
  //	var url = "includes/manage-your-cards/r_card_settings_professional.json.php?card_id=" + settingsFrm.data("card-id");
  var str = '';
  var set_settings = [];
  jQuery(document).find('#settings-checkboxes-professional input:checkbox').filter(':checked').each(function (input) {
    str += 'settings[]=' + jQuery(this).attr('id') + '&';
    set_settings.push(jQuery(this).attr('id'));
  });
  str = str.substr(0, str.length - 1);
  //	console.log(str);
  jQuery.post(url, str, function (data) {
    //	jQuery.ajax({
    //		type: "GET",
    //		url: url,
    //		success: function (data) {
    var response = JSON.parse(data);
    if (response.status === 'expired') {
      window.location.href = globalURL;
      return;
    }
    if (response.status === 'unchanged') {
      loader.hide();
      closePopup();
    } else {
      //			var new_settings = data.split(',');
      //			console.log('response.wanted_settings');
      //			console.log(response.wanted_settings);
      //			if ( response.wanted_settings.length > 0 ) {
      var url = "includes/manage-your-cards/u_card_settings.php?card_id=" + settingsFrm.data("card-id") + "&card_type=Professional"
      var shortcut = "";
      if (response.wanted_settings.indexOf('visible_pp_search') > -1) {
        shortcut = shortcut + "2";
      }
      if (response.wanted_settings.indexOf('allow_rating') > -1) {
        shortcut = shortcut + "3";
      }
      var dest;
      if (shortcut == "") {
        dest = "manage-settings-cancel-subscription.php?card_id=" + settingsFrm.data("card-id");
        //				console.log('dest ' + dest);
        window.location.href = "manage-settings-cancel-subscription.php?card_id=" + settingsFrm.data("card-id");
      } else {
        dest = "manage-settings-subscription-period.php?card_id=" + settingsFrm.data("card-id") + "&shortcut=" + shortcut;
        //				console.log('dest ' + dest);
        window.location.href = "manage-settings-subscription-period.php?card_id=" + settingsFrm.data("card-id") + "&shortcut=" + shortcut;
      }
    }
  });
});
/******************************************************************************
 *
 *										END EDITING CARD SETTNGS
 *
 ******************************************************************************/
/*
 * following functions no more used
 */
//function is_card_ratable(card_id) {
//	/*
//	 * mg 25/01/2016
//	 * this function uses file get_business_card_details_rev_2.php
//	 * to know if card allows rating
//	 */
//	var allow_rating;
//	jQuery.ajax({
//		async: false,
//		type: 'post',
//		url: 'includes/mg_get_card_details.php',
//		data: {card_id: card_id},
//		success: function (result)
//		{
//			var response = JSON.parse(result);
//			allow_rating = response.allow_rating;
//		}
//	});
////	console.log('result is ' + allow_rating);
//	return allow_rating;
//}

function view_card(card_id) {
  localStorage["page_before_view_card"] = window.location.href;
  window.location.href = globalURL + 'view-business-card.php?card_id=' + card_id;
}
function goToCard(card_id) {
  view_card(card_id);
}
// end no more used

function showCardDetails(card_id) {
  var bcn;
  jQuery.ajax({
    type: 'post',
    url: 'includes/get_bcn_by_card_id.php',
    data: { card_id: card_id },
    success: function (result) {
      bcn = result;
      var cardViewContext;
      cardViewContext = JSON.parse(localStorage.getItem(card_id));
      console.log('cvc1:');
      console.log(cardViewContext);

      if (cardViewContext == null) {
        loadDataByBcn(bcn);
        console.log('loaded data by bcn');
        setTimeout(function () {
          console.log('getting cardViewCOntext');
          cardViewContext = JSON.parse(localStorage.getItem(card_id));
        }, 500);
      }
      console.log('cvc2:');
      console.log(cardViewContext);
      if (!cardViewContext.card_private) {
        setTimeout(function () {
          //				console.log('cardViewContext in showCardDetails ');
          //				console.log(cardViewContext);
          var card_row_html = parseAndReturnHandlebarsRev2("card_details", cardViewContext);
          var card_row = jQuery(card_row_html);
          jQuery('#userCardDetail').children().remove();
          jQuery('#userCardDetail').append(card_row);
          jQuery('#rating-input').rating({
            min: 0,
            max: 5,
            step: 0.5,
            rtl: false,
            showCaption: false,
            showClear: false,
            size: 'xxxs'
          });
          prepareLinks();
          getCardSettings(card_id);
          getMutualContacts('.m_c_container', card_id, 'card_details_mutual_contacts');
          loadComments(card_id);
          loadDropdownFolder();
          jQuery('body').addClass('active');
          jQuery('#overLay').slideDown();
          jQuery('#userCardDetail').slideDown();
          jQuery("html, body").animate({ scrollTop: 0 }, "slow");
          jQuery('.search-sec').css({
            'overflow-y': 'hidden'
          });
          return false;
        }, 500);
      } else {
        swal({
          title: "Warning",
          text: "This card is private.",
          type: "warning",
          showCancelButton: false,
          confirmButtonColor: "#FD722C",
          confirmButtonText: "Ok"
        }).then(function () {

        });
      }
    }
  });
}
function getNewCoord(coordinates) {
  var c_values = coordinates.split(',');
  var x1, x2, y1, y2;
  c_values[0] = Math.round((c_values[0] * 415) / 539);
  c_values[2] = Math.round((c_values[2] * 415) / 539);
  c_values[1] = Math.round((c_values[1] * 260) / 338);
  c_values[3] = Math.round((c_values[3] * 260) / 338);
  return c_values.join();
}
// RETRIEVE DATA VALUES FROM LINKS
function prepareLinks() {
  //	console.log('entering prepareLinks');
  jQuery("#preview-details").find(".linky").each(function () {
    var linkType = jQuery(this).attr("data-link-type");
    var linkValue = jQuery(this).attr("data-link-value");
    var coordinates = jQuery(this).attr("data-link-coordinates");
    //		console.log(coordinates);
    var new_coordinates = getNewCoord(coordinates);
    //		console.log(new_coordinates);
    jQuery(this).attr("data-link-coordinates", new_coordinates);
    var appendEl, ga;
    switch (linkType) {
      case "email":
        appendEl = "mailto:";
        ga = "";
        break;
      case "phone":
        appendEl = "tel:";
        ga = "";
        break;
      default:
        appendEl = "http://";
        ga = "target='_blank' onclick='trackOutboundLink(" + linkValue + "); return false;";
    }
    //		console.log('ga is ' + ga);
    jQuery(this).wrap("<a href='" + appendEl + linkValue + "'" + ga + " '></a>");
  });
  var linkk = jQuery(document).find('#preview-details .linky');
  for (var i = linkk.length - 1; i >= 0; i--) {
    convert_to_percentage(linkk[i], false);
  }
  //	console.log('exiting prepareLinks');
}
function prepareLinks_zoom() {
  //	console.log('entering prepareLinks');
  var ex_links = jQuery('.ex-link');
  if (ex_links != null && ex_links.length > 0) {
    return;
  }
  jQuery("#preview-details2").find(".linky").each(function () {
    var linkType = jQuery(this).attr("data-link-type");
    var linkValue = jQuery(this).attr("data-link-value");
    var coordinates = jQuery(this).attr("data-link-coordinates");
    //		console.log(coordinates);
    var new_coordinates = getNewCoord(coordinates);
    //		console.log(new_coordinates);
    jQuery(this).attr("data-link-coordinates", new_coordinates);
    var appendEl, ga;
    switch (linkType) {
      case "email":
        appendEl = "mailto:";
        ga = "";
        break;
      case "phone":
        appendEl = "tel:";
        ga = "";
        break;
      default:
        appendEl = "http://";
        ga = "target='_blank' onclick='trackOutboundLink(" + linkValue + "); return false;";
    }
    //		console.log('ga is ' + ga);
    jQuery(this).wrap("<a class='ex-link' href='" + appendEl + linkValue + "'" + ga + " '></a>");
  });
  var linkkk = jQuery(document).find('#preview-details2 .linky');
  for (var i = linkkk.length - 1; i >= 0; i--) {
    convert_to_percentage(linkkk[i], true);
  }
}
function convert_to_percentage(el, zoomed) {
  var el = jQuery(el);
  //	console.log('Converting Percentage for');
  //	console.log(el);
  var result = determineParentChildResolution(el, el.parent(), zoomed);
  var parentElementWidth = result.parentWidth,
    parentElementHeight = result.parentHeight;
  el.css({
    left: parseInt(el.css('left')) / parentElementWidth * 96 + "%",
    top: parseInt(el.css('top')) / parentElementHeight * 98 + "%",
    width: el.width() / parentElementWidth * 100 + "%",
    height: el.height() / parentElementHeight * 100 + "%"
  });
}
// According to resolution, set dynamic or static properties
function determineParentChildResolution(el, parent, zoomed) {
  var orientation;
  var container;
  if (zoomed) {
    container = jQuery('#preview-details');
  } else {
    container = jQuery('#preview-details2');
  }

  if (container.hasClass('portrait')) {
    orientation = 'portrait';
  } else {
    orientation = 'landscape';
  }
  var dParentHeight, dParentWidth;
  if (orientation == "portrait") {
    dParentHeight = 539;
    dParentWidth = 338;
  }
  if (orientation == "landscape") {
    dParentHeight = 338;
    dParentWidth = 539;
  }
  return {
    parentWidth: dParentWidth,
    parentHeight: dParentHeight,
  }
  //}
}

function getLinkRequests(reportType) {
  loaderText('Loading card requests...');
  loader.show();
  var html = '';
  jQuery.ajax({
    type: 'post',
    url: 'includes/card-link/r_link_requests.php',
    data: { 'report_type': reportType },
    success: function (result) {
      var response = JSON.parse(result);
      if (response === 'expired') {
        window.location.href = globalURL;
        return;
      }
      jQuery(response).each(function (i, item) {
        var requestData = {
          card_name: item.card_name,
          card_type: item.card_type,
          last_name: item.last_name,
          first_name: item.first_name,
          profile_image: item.profile_image,
          website_link: item.website_link,
          company_name: item.company_name,
          last_prop: item.last_prop,
          card_id: item.card_id,
          email_address: item.email_address
        };
        //				console.log(requestData);
        html += parseAndReturnHandlebarsRev2('link_requests', requestData);
      });
      jQuery('.incoming-table').html(html);
      loader.hide();
    },
    error: function (result) {
      jQuery('div').html(result).fadeIn('slow');
    }
  });
}

/*******************************************************************************
 *
 *									REFACTORED FROM	STATUS-BAR.JS
 *
 *******************************************************************************/
function updateButtonInSearch() {
  //	console.log('searchPressed()');
  searchPressed();
  var folder_id = jQuery('#page-title').data('folder-id');
  //	console.log('folder_id in updateButtonInSearch ' + folder_id);
  if (typeof folder_id !== 'undefined') {
    loadDataFilterable(folder_id);
  }
}
function updateAddButtonInDetails(card_id) {
  //	console.log('within function updateAddButtonInDetails()');
  jQuery('.card-status').html('<strong class="rate-card rating-inline"><span>Rate this card: </span><input data-card-id="' + card_id + '" id="rating-input" type="number" /></strong>' +
    '<p class="card-added"><img src="assets/sat_images/card-added.png" alt="" />Card Already Added</p>');
  getCardSettings(card_id);
  jQuery('#rating-input').rating({
    min: 0,
    max: 5,
    step: 0.5,
    rtl: false,
    showCaption: false,
    showClear: false,
    size: 'xxxs'
  });
  searchPressed();
  var folder_id = jQuery('#page-title').data('folder-id');
  //	console.log('folder_id in updateAddButtonInDetails ' + folder_id);
  if (typeof folder_id !== 'undefined') {
    loadDataFilterable(folder_id);
  }
}
function updateSendButtonInDetails(card_id) {
  //	console.log('within function updateSendButtonInDetails()');
  jQuery('.card-status').html('<strong class="rate-card rating-inline"><span>Rate this card: </span><input data-card-id="' + card_id + '" id="rating-input" type="number" /></strong>' +
    '<p class="card-added"><img src="assets/sat_images/remove-card.png" alt="" />Link Request Approval Pending</p>');
  getCardSettings(card_id);
  jQuery('#rating-input').rating({
    min: 0,
    max: 5,
    step: 0.5,
    rtl: false,
    showCaption: false,
    showClear: false,
    size: 'xxxs'
  });
  searchPressed();
}
function callback(card_id, functionName) {
  //	console.log(functionName);
  functionName(card_id);
}
function addCardToContact(card_id, functionName) {
  //	console.log(functionName);
  loaderText('Adding card to your contacts...');
  loader.show();
  var date_string = getDateTime();
  //	console.log('date time is ' + date_string);
  jQuery.ajax({
    type: 'post',
    url: 'includes/card-link/c_add_contact.php',
    data: { card_id: card_id, date_string: date_string },
    success: function (response) {
      var r = JSON.parse(response);
      if (r === 'expired') {
        window.location.href = globalURL;
        return;
      }
      if (r.success) {
        loader.hide();
        swal({
          title: "Contact Added",
          text: "Contact added successfully!",
          html: true,
          type: "success",
          showCancelButton: false,
          confirmButtonColor: "#ff6603"
        }, function (isConfirm) {
          if (isConfirm) {
            //						loaderText('Sending notification...');
            //						loader.show();
            jQuery.ajax({
              type: 'post',
              url: 'includes/card-link/c_add_contact_notification.php',
              data: { card_id: card_id, date_string: date_string },
              //							success: function (result)
              //							{
              //								callback(card_id, functionName);
              //								var folder_id = jQuery('#page-title').data('folder-id');
              //								console.log('folder_id in addCardToContact ' + folder_id);
              //								if (typeof folder_id !== 'undefined') {
              //									loadDataFilterable(folder_id);
              //								}
              //								loader.hide();
              //							},
              //							error: function (result)
              //							{
              //								loader.hide();
              //								console.log('error updating notification');
              //							}
            });
            if (functionName != '') {
              callback(card_id, functionName);
            }
            var folder_id = jQuery('#page-title').data('folder-id');
            //						console.log('folder_id in addCardToContact ' + folder_id);
            if (typeof folder_id !== 'undefined') {
              loadDataFilterable(folder_id);
            }

          } else {
            //						console.log('go to home');
          }
        });
      } else {
        loader.hide();
        swal({
          title: "Contact Not Added",
          text: "Contact could not be added!",
          html: true,
          type: "error",
          showCancelButton: false,
          confirmButtonColor: "#ff6603"
        });
      }
    },
    error: function () {
      console.log('Error occurred on the server');
    }
  });
}
function delete_card(card_ids, folder_id) {
  loaderText('Removing card...');
  loader.show();
  var cards = card_ids.join(',');
  //	console.log('removing card');
  //	cards = cards.substring(0, cards.length - 1);
  jQuery.ajax({
    type: "POST",
    data: { cards: cards, folder_id: folder_id },
    url: "includes/card-link/d_link_request_as_card_holder.php",
    success: function (data) {
      var response = JSON.parse(data);
      if (response === 'expired') {
        window.location.href = globalURL;
        return;
      }
      if (response.success) {
        loader.hide();
        swal({
          title: "Removed Successfully",
          text: "Contact removed successfully!",
          html: true,
          type: "success",
          showCancelButton: false,
          confirmButtonColor: "#ff6603",
          confirmButtonText: "Ok",
          closeOnConfirm: true
        }, function (isConfirm) {
          if (isConfirm) {
            //						console.log('reloading');
            var folder_id = jQuery('#page-title').data('folder-id');
            //						console.log('folder_id in delete_card is ' + folder_id);
            if (typeof folder_id !== 'undefined') {
              loadDataFilterable(folder_id);
              getFolders('', fillSidebarMenu);
              //							console.log('call update checkbox');
            }
            loadDataFilterable(getParameterByName("folder"));
            setTimeout(function () {
              updateCheckbox();
            }, 200);
          }
        });
        //update ui
        //				jQuery("#row_card_id" + card_id).remove();
        //				var count = jQuery("#contact_count").html();
        //				jQuery("#contact_count").html(count - 1);
      } else {
        loader.hide();
        swal({
          title: "Error removing card",
          text: "Something went wrong while trying to remove the contact!",
          html: true,
          type: "error",
          showCancelButton: false,
          confirmButtonColor: "#ff6603",
          confirmButtonText: "Ok",
          closeOnConfirm: true
        });
        //				console.log('error deleting cards');
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });
}
function process_delete_request() {
  //	console.log('delete_selected_cards');
  var folder_id = jQuery('#page-title').data('folder-id');
  var card_ids = [];
  jQuery('.my-cards .card-box input.css-checkbox:checked').each(function () {
    card_ids.push(jQuery(this).attr('id').substr(4));
  });
  //		console.log(card_ids);
  if (card_ids.length === 0) {
    swal({
      title: "Warning!",
      text: "Please select at least one card, please.",
      html: true,
      type: "warning",
      showCancelButton: false,
      confirmButtonColor: "#ff6603",
      confirmButtonText: "Ok",
      closeOnConfirm: true
    });
    return;
  }
  swal({
    title: "Confirm delete",
    text: "Are you sure to want to delete selected card(s)?",
    html: true,
    type: "info",
    showCancelButton: true,
    confirmButtonColor: "#ff6603",
    confirmButtonText: "Ok",
    closeOnConfirm: false
  },
    function (isConfirm) {
      if (isConfirm) {
        delete_card(card_ids, folder_id);
      }
      //			setTimeout(function(){
      //				updateCheckbox();
      //			}, 200);
    });
}
function sendCardLinkRequest(card_id, functionName) {
  loaderText('Sending card requests...');
  var date_string = getDateTime();
  loader.show();
  jQuery.ajax({
    type: 'post',
    url: 'includes/card-link/c_link_request.php',
    data: { card_id: card_id, date_string: date_string },
    success: function (data) {
      //			console.log('data sending link request');
      //			console.log(data);
      var response = JSON.parse(data);
      if (response === 'expired') {
        loader.hide();
        window.location.href = globalURL;
        return;
      }
      if (response.success) {
        loader.hide();
        swal(
          {
            title: "Request Successful",
            text: "Request sent successfully!",
            html: true,
            type: "success",
            showCancelButton: false,
            confirmButtonColor: "#ff6603"
          },
          function (isConfirm) {
            if (isConfirm) {
              callback(card_id, functionName);
            } else {
              //								console.log('go to home');
            }
          });
      }
    },
    error: function (result) {
      console.log('error: ' + result);
    }
  });
}
/*******************************************************************************
 *
 *										END OF STATUS-BAR.JS
 *
 *******************************************************************************/

/*******************************************************************************
 *
 *										REFACTORED CARD-CONTACTS.JS
 *										MY-OWN-CARDS.PHP
 *
 *******************************************************************************/
/*SORTING CARDS*/
function loadSortingDropdown() {
  var category = location.search.split("=")[1];
  var context = { items: [] };
  //both
  //	context.items.push({tab_index: 0, sort_label: 'Folder Name', sort_key: 'folder_name'});
  //if p&c
  if (category == "pc") {
    context.items.push({ tab_index: 1, sort_label: 'Last Name', sort_key: 'last_name' });
    context.items.push({ tab_index: 2, sort_label: 'First Name', sort_key: 'first_name' });
    context.items.push({ tab_index: 3, sort_label: 'Company Name', sort_key: 'company_name' });
    context.items.push({ tab_index: 4, sort_label: 'Card Rating', sort_key: 'rating' });
  } else
    //else if p&p
    if (category == "sp") {
      context.items.push({ tab_index: 1, sort_label: 'Category Name', sort_key: 'category' });
      context.items.push({ tab_index: 2, sort_label: 'Sub Category Name', sort_key: 'sub_category' });
      context.items.push({ tab_index: 3, sort_label: 'Brand Name', sort_key: 'distributed_brand' });
      context.items.push({ tab_index: 4, sort_label: 'Card Rating', sort_key: 'rating' });
    } else {
      //folders
      context.items.push({ tab_index: 1, sort_label: 'Last Name', sort_key: 'last_name' });
      context.items.push({ tab_index: 2, sort_label: 'First Name', sort_key: 'first_name' });
      context.items.push({ tab_index: 3, sort_label: 'Company Name', sort_key: 'company_name' });
      context.items.push({ tab_index: 4, sort_label: 'Card Rating', sort_key: 'rating' });
    }
  var html = parseAndReturnHandlebarsRev2("sort_by_dropdown", context);
  jQuery("#sort_by_container").append(html);
}
var sort_key;
function buildWithNewOrdering(sortedCards) {
  var i = 0, l = sortedCards.length;
  //	console.log(sortedCards.length);
  jQuery('.my-cards').empty();
  jQuery('#card-details-table tbody').children().remove();
  for (i; i < l; i++) {
    //		console.log(sortedCards[i].div);
    //		jQuery('.my-cards').append(sortedCards[i].div);
    var card_row_html = parseAndReturnHandlebarsRev2("card_row_folder_content", sortedCards[i]);
    var card_row = jQuery(card_row_html);
    //		console.log(card_row);
    jQuery('.my-cards').append(card_row);
    card_row_html2 = parseAndReturnHandlebarsRev2("card_row_folder_content_table", sortedCards[i]);
    card_row2 = jQuery(card_row_html2);
    jQuery('#card-details-table tbody').append(card_row2);
  }
  setTimeout(function () {
    jQuery('.average-rating').rating({
      min: 0,
      max: 5,
      step: 0.5,
      rtl: false,
      showCaption: false,
      showClear: false,
      size: 'xxxs',
      readonly: true
    });
  }, 100);
}
function sortByKey(a, b) {
  var aKey;
  var bKey;
  //	console.log('sort_key is ' + sort_key);
  if (sort_key === 'rating') {
    my_sort_key = 'average_rating';
    aKey = a[my_sort_key];
    bKey = b[my_sort_key];
    if (aKey == null) {
      aKey = 0.0;
    }
    if (bKey == null) {
      bKey = 0.0;
    }
    //		console.log('is aKey for ' + a.card_name + ' (' + aKey + ') greater of bKey for ' + b.card_name + ' (' + bKey + ')');
    return parseFloat(bKey) - parseFloat(aKey);
  } else {
    aKey = a[sort_key].toLowerCase();
    bKey = b[sort_key].toLowerCase();
    //		console.log('is aKey (' + aKey + ') greater of bKey (' + bKey + ')');
    if (aKey < bKey) {
      //			console.log(aKey + ' is lower than ' + bKey);
      return -1;
    } else if (aKey > bKey) {
      //			console.log(aKey + ' is greater than ' + bKey);
      return 1;
    }
    return 0;
    //		return ( ( aKey < bKey ) ? -1 : ( ( aKey > bKey ) ? 1 : 0 ) );
  }
}
function sortBy(anchorTag) {
  var sorting_direction = (sessionStorage.getItem(anchorTag));
  sort_key = jQuery(anchorTag).attr("id");
  //	console.log('go to sort');
  //	console.log(filteredCards);
  if (typeof sorting_direction == 'undefined' || sorting_direction === 'desc') {
    filteredCards.sort(sortByKey);
    buildWithNewOrdering(filteredCards);
    sessionStorage.setItem(anchorTag, 'asc');
  } else {
    filteredCards.sort(sortByKey).reverse();
    buildWithNewOrdering(filteredCards);
    sessionStorage.setItem(anchorTag, 'desc');
  }
}
/*END SORTING CARDS*/
/*SEARCHING CARDS IN FOLDER*/
function contactSearched(token) {
  //according to discussion all results will already be in the view
  var idsToRemove = [], idsToAdd = [];
  //	console.log(filteredCards);
  for (var i = 0; i < filteredCards.length; i++) {
    for (var property in filteredCards[i]) {
      if (filteredCards[i].hasOwnProperty(property)) {
        //				console.log(property+': '+filteredCards[i][property]);
        if (filteredCards[i][property] != null) {
          if (filteredCards[i][property].toLocaleString().toLowerCase().indexOf(token.toLowerCase()) > -1) {
            idsToAdd.push(filteredCards[i].card_id);
          } else {
            idsToRemove.push(filteredCards[i].card_id);
          }
        }
      }
    }
  }
  updateCardsViewAfterSearch(idsToRemove, idsToAdd);
}
function updateCardsViewAfterSearch(cardIdsToRemove, cardsToAdd) {
  var i = 0, l = cardIdsToRemove.length;
  for (i; i < l; i++) {
    //		console.log('card to remove is ' + cardIdsToRemove[i]);
    jQuery('#' + cardIdsToRemove[i]).hide();
    jQuery('#card-details-table tbody tr#' + cardIdsToRemove[i]).hide();
  }
  var i = 0, l = cardsToAdd.length;
  for (i; i < l; i++) {
    //		console.log('card to add is ' + cardsToAdd[i]);
    var st = jQuery("#" + cardsToAdd[i]).attr('style');
    if (st != null) {
      var sts = st.split(':');
    } else {
      sts = ['', ''];
    }
    if (sts[1] != '0') {
      jQuery('#' + cardsToAdd[i]).show();
      jQuery('#card-details-table tbody tr#' + cardsToAdd[i]).show();
    }
  }
  jQuery('#result-count-in-folder').text();
}
/*END SEARCHING CARDS IN FOLDER*/
/*COPYNG CARD TO FOLDERS*/
function loadDropdownFolder() {
  /*
   * load folders to fill in dropdown menu
   * in crd details
   * called in on click event of .cradDetailLink line 2636
   */
  //	console.log('enter loadDropDownFolder');
  jQuery.ajax({
    type: "post",
    data: '',
    url: "includes/view-business-card/view_business_card.php",
    success: function (data) {
      if (data === 'expired') {
        window.location.href = globalURL;
        return;
      }
      jQuery('ul.drop-down.select-folder').children().remove();
      jQuery('ul.drop-down.select-folder').append('<li><strong>Copy selected to:</strong></li>');
      jQuery('ul.drop-down.select-folder').append(data);
      jQuery('ul.drop-down.select-folder').append('<li class="btn-new-folder"><a href="#">Create New Folder</a></li>');

      //			jQuery('ul.drop-down.select-folder li.btn-new-folder').before(data);
      jQuery('ul.drop-down.select-folder li.btn-new-folder a').css({
        "background": "#f6f6f6",
        "margin-top": "10px",
        "color": "#fd6f02"
      });
      jQuery('ul.drop-down.select-folder li.apply').css({
        "border-top": "1px solid #bebebe",
        "border-bottom": "1px solid #bebebe",
      });
      jQuery('.select-folder').mCustomScrollbar();
      //			console.log('exit loadDropdownfolder');
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });
}
function applyClick() {
  var selected = [];
  jQuery('.drop-down.select-folder input:checked').each(function () {
    selected.push(jQuery(this).attr('value'));
  });
  var folder_ids = selected.join(",");
  if (folder_ids.length === 0) {
    swal({
      title: "Warning!",
      text: "Please select at least one destination folder, please.",
      html: true,
      type: "warning",
      showCancelButton: false,
      confirmButtonColor: "#ff6603",
      confirmButtonText: "Ok",
      closeOnConfirm: false
    });
    return;
  }
  //	console.log('folder_ids ' + folder_ids);
  if (jQuery('#userCardDetail').css('display') == 'block') {
    var card_id = jQuery('.user-card-detail').attr('data-card-id');
    //		console.log(card_id);
    var activeList = jQuery('li.folder-list');
    var callback = function () {
      activeList.toggleClass('active');
    };
    var data = { card_id: card_id, folder_ids: folder_ids };
  } else {
    var card_ids = [];
    jQuery('.my-cards .card-box input.css-checkbox:checked').each(function () {
      card_ids.push(jQuery(this).attr('id').substr(4));
    });
    if (card_ids.length === 0) {
      swal({
        title: "Warning!",
        text: "Please select at least one card, please.",
        html: true,
        type: "warning",
        showCancelButton: false,
        confirmButtonColor: "#ff6603",
        confirmButtonText: "Ok",
        closeOnConfirm: false
      });
      return;
    } else {
      var card_ids_s = card_ids.join(',');
      data = { card_id: card_ids_s, folder_ids: folder_ids };
      //			console.log(data);
    }
  }
  //	console.log(data.card_id + "\n" + folder_ids);
  jQuery.ajax({
    type: "POST",
    url: "includes/view-business-card/update_selected_folders.php",
    data: data,
    success: function (data) {
      if (data === 'expired') {
        window.location.href = globalURL;
        return;
      }
      swal({
        title: "Success!",
        text: "Selected cards have been successfully copied in selected folder(s)",
        html: true,
        type: "success",
        showCancelButton: false,
        confirmButtonColor: "#ff6603",
        confirmButtonText: "Ok",
        closeOnConfirm: false
      });
      jQuery('.drop-down.select-folder input:checked').each(function () {
        jQuery(this).prop('checked', false);
      });
      jQuery('.css-checkbox:not(.mg-checkbox)').prop("checked", false);
      jQuery('.card-box').removeClass('active');
      return;
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });
}
/*END COPYNG CARD TO FOLDERS*/
/*FILTERING CARDS*/
function loadFilteringDropDown() {
  var category = location.search.split("=")[1];
  var filterDropdown = jQuery('.select-filter');
  filterDropdown.children().remove();
  filterDropdown.append("<li><a class='checkboxStyle'><input checked='checked' id='cbx-all-none' type='checkbox' class='companies css-checkbox mg-checkbox' value='all' /><label for='cbx-all-none' onclick='checkAll();' class='checkbox css-label mg-checkbox'>Select all/none</label></a></li>");
  var uniqueItems = [];
  if (category == 'pc') {
    filterDropdown.append("<li class='dropdown' style='margin-left: 5px;'><a href='#'>Companies</a><ul id='companies-submenu' class='submenu'></ul></li>");
    for (var i = 0; i < filteredCards.length; i++) {
      if (filteredCards[i].company_name != '' && filteredCards[i].company_name != null && typeof filteredCards[i].company_name != 'undefined') {
        var item = "<li class='folsel'><input checked='checked' id='cbx-" + filteredCards[i].company_name + "' type='checkbox' class='companies css-checkbox mg-checkbox' data-family='companies' value='" + filteredCards[i].company_name + "' /><label for='cbx-" + filteredCards[i].company_name + "' class='checkbox css-label mg-checkbox'>" + filteredCards[i].company_name + "</label></li>";
        if (jQuery.inArray(item, uniqueItems) == -1) {
          uniqueItems.push(item);
        }
      }
    }
    uniqueItems.sort();
    jQuery('#companies-submenu').append(uniqueItems);
    uniqueItems = [];
    filterDropdown.append("<li class='dropdown' style='margin-left: 5px;'><a href='#'>Names</a><ul id='names-submenu' class='submenu'></ul></li>");
    for (var i = 0; i < filteredCards.length; i++) {
      var full_name = filteredCards[i].first_name + ' ' + filteredCards[i].last_name;
      if (full_name != '' && full_name != null && typeof full_name != 'undefined') {
        var item = "<li class='folsel'><input checked='checked' id='cbx-" + full_name + "' type='checkbox' class='names css-checkbox mg-checkbox' data-family='names' value='" + full_name + "' /><label for='cbx-" + full_name + "' class='checkbox css-label mg-checkbox'>" + full_name + "</label></li>";
        if (jQuery.inArray(item, uniqueItems) == -1) {
          uniqueItems.push(item);
        }
      }
    }
    uniqueItems.sort();
    jQuery('#names-submenu').append(uniqueItems);
    uniqueItems = [];
  } else if (category == 'sp') {
    filterDropdown.append("<li class='dropdown' style='margin-left: 5px;'><a href='#'>Categories</a><ul id='categories-submenu' class='submenu'></ul></li>");
    for (var i = 0; i < filteredCards.length; i++) {
      if (filteredCards[i].category != '' && filteredCards[i].category != null && typeof filteredCards[i].category != 'undefined') {
        var item = "<li class='folsel'><input checked='checked' id='cbx-" + filteredCards[i].category + "' type='checkbox' class='categories css-checkbox mg-checkbox' data-family='categories' value='" + filteredCards[i].category + "' /><label for='cbx-" + filteredCards[i].category + "' class='checkbox css-label mg-checkbox'>" + filteredCards[i].category + "</label></li>";
        if (jQuery.inArray(item, uniqueItems) == -1) {
          uniqueItems.push(item);
        }
      }
    }
    uniqueItems.sort();
    jQuery('#categories-submenu').append(uniqueItems);
    uniqueItems = [];
    filterDropdown.append("<li class='dropdown' style='margin-left: 5px;'><a href='#'>Names</a><ul id='names-submenu' class='submenu'></ul></li>");
    for (var i = 0; i < filteredCards.length; i++) {
      var full_name = filteredCards[i].first_name + ' ' + filteredCards[i].last_name;
      if (full_name != '' && full_name != null && typeof full_name != 'undefined') {
        var item = "<li class='folsel'><input checked='checked' id='cbx-" + full_name + "' type='checkbox' class='names css-checkbox mg-checkbox' data-family='names' value='" + full_name + "' /><label for='cbx-" + full_name + "' class='checkbox css-label mg-checkbox'>" + full_name + "</label></li>";
        if (jQuery.inArray(item, uniqueItems) == -1) {
          uniqueItems.push(item);
        }
      }
    }
    uniqueItems.sort();
    jQuery('#names-submenu').append(uniqueItems);
    uniqueItems = [];
  } else {
    filterDropdown.append("<li class='dropdown' style='margin-left: 5px;'><a href='#'>Companies</a><ul id='companies-submenu' class='submenu'></ul></li>");
    for (var i = 0; i < filteredCards.length; i++) {
      if (filteredCards[i].company_name != '' && filteredCards[i].company_name != null && typeof filteredCards[i].company_name != 'undefined') {
        var item = "<li class='folsel'><input checked='checked' id='cbx-" + filteredCards[i].company_name + "' type='checkbox' class='companies css-checkbox mg-checkbox' data-family='companies' value='" + filteredCards[i].company_name + "' /><label for='cbx-" + filteredCards[i].company_name + "' class='checkbox css-label mg-checkbox'>" + filteredCards[i].company_name + "</label></li>";
        if (jQuery.inArray(item, uniqueItems) == -1) {
          uniqueItems.push(item);
        }
      }
    }
    uniqueItems.sort();
    jQuery('#companies-submenu').append(uniqueItems);
    uniqueItems = [];
    filterDropdown.append("<li class='dropdown' style='margin-left: 5px;'><a href='#'>Categories</a><ul id='categories-submenu' class='submenu'></ul></li>");
    for (var i = 0; i < filteredCards.length; i++) {
      if (filteredCards[i].category != '' && filteredCards[i].category != null && typeof filteredCards[i].category != 'undefined') {
        var item = "<li class='folsel'><input checked='checked' id='cbx-" + filteredCards[i].category + "' type='checkbox' class='categories css-checkbox mg-checkbox' data-family='categories' value='" + filteredCards[i].category + "' /><label for='cbx-" + filteredCards[i].category + "' class='checkbox css-label mg-checkbox'>" + filteredCards[i].category + "</label></li>";
        if (jQuery.inArray(item, uniqueItems) == -1) {
          uniqueItems.push(item);
        }
      }
    }
    uniqueItems.sort();
    jQuery('#categories-submenu').append(uniqueItems);
    uniqueItems = [];
    filterDropdown.append("<li class='dropdown' style='margin-left: 5px;'><a href='#'>Names</a><ul id='names-submenu' class='submenu'></ul></li>");
    for (var i = 0; i < filteredCards.length; i++) {
      var full_name = filteredCards[i].first_name + ' ' + filteredCards[i].last_name;
      if (full_name != '' && full_name != null && typeof full_name != 'undefined') {
        var item = "<li class='folsel'><input checked='checked' id='cbx-" + full_name + "' type='checkbox' class='names css-checkbox mg-checkbox' data-family='names' value='" + full_name + "' /><label for='cbx-" + full_name + "' class='checkbox css-label mg-checkbox'>" + full_name + "</label></li>";
        if (jQuery.inArray(item, uniqueItems) == -1) {
          uniqueItems.push(item);
        }
      }
    }
    uniqueItems.sort();
    jQuery('#names-submenu').append(uniqueItems);
    uniqueItems = [];
  }
  jQuery('.select-filter').mCustomScrollbar();
}
function checkAll() {
  jQuery('#mg-accordion a.checkboxStyle').removeClass('active');
  if (jQuery('#cbx-all-none').attr('value') === 'all') {
    jQuery('.submenu li.folsel input').each(function () {
      jQuery(this).prop('checked', false);
    });
    jQuery('#cbx-all-none').attr('value', 'none');
    //		console.log('was all and set to ' + jQuery('#cbx-all-none').attr('value'));
  } else if (jQuery('#cbx-all-none').attr('value') === 'none') {
    //		console.log('was none and set to ' + jQuery('#cbx-all-none').attr('value'));
    jQuery('.submenu li.folsel input').each(function () {
      jQuery(this).prop('checked', true);
    });
    jQuery('#cbx-all-none').attr('value', 'all');
  }
  filterCardsInclusive('');
}

/*unused function*/function filterCardsExclusive(family) {
  var idsToRemove = [], idsToAdd = [];
  switch (family) {
    case 'companies':
      jQuery('.drop-down.select-filter input.companies:checked').each(function () {
        var $this = jQuery(this);
        var token = $this.attr('value');
        //				console.log('the token is ' + token);
        for (var i = 0; i < filteredCards.length; i++) {
          if (filteredCards[i]['company_name'] != null && typeof filteredCards[i]['company_name'] != 'undefined') {
            if (filteredCards[i]['company_name'].toLocaleString().toLowerCase().indexOf(token.toLowerCase()) > -1) {
              idsToAdd.push(filteredCards[i].card_id);
              var index = filteredCards[i]['company_name'].toLocaleString().toLowerCase().indexOf(token.toLowerCase());
              //							console.log('added company_name = ' + filteredCards[i]['company_name'] + ' = token ' + token + ' card_id ' + filteredCards[i].card_id + 'index is ' + index);
            } else {
              idsToRemove.push(filteredCards[i].card_id);
              //							console.log('removed company_name = ' + filteredCards[i]['company_name'] + ' = token ' + token + ' card_id ' + filteredCards[i].card_id);
            }
          }
        }
      });
      break;
    case 'names':
      jQuery('.drop-down.select-filter input.names:checked').each(function () {
        for (var i = 0; i < filteredCards.length; i++) {
          if (filteredCards[i]['full_name'] != null && typeof filteredCards[i]['full_name'] != 'undefined') {
            if (filteredCards[i]['full_name'].toLocaleString().toLowerCase().indexOf(token.toLowerCase()) > -1) {
              idsToAdd.push(filteredCards[i].card_id);
            } else {
              idsToRemove.push(filteredCards[i].card_id);
            }
          }
        }
      });
      break;
    case 'categories':
      jQuery('.drop-down.select-filter input.categories:checked').each(function () {
        for (var i = 0; i < filteredCards.length; i++) {
          if (filteredCards[i]['category'] != null && typeof filteredCards[i]['category'] != 'undefined') {
            if (filteredCards[i]['category'].toLocaleString().toLowerCase().indexOf(token.toLowerCase()) > -1) {
              idsToAdd.push(filteredCards[i].card_id);
            } else {
              idsToRemove.push(filteredCards[i].card_id);
            }
          }
        }
      });
      break;
  }
  updateCardsViewAfterSearch(idsToRemove, idsToAdd);
}
function filterCardsInclusive(family) {
  var idsToRemove = [], idsToAdd = [];
  jQuery('.drop-down.select-filter input:checked').each(function () {
    var token = jQuery(this).attr('value');
    //		console.log('the token is ' + token);
    for (var i = 0; i < filteredCards.length; i++) {
      if (filteredCards[i]['company_name'] != null && typeof filteredCards[i]['company_name'] != 'undefined') {
        if (filteredCards[i]['company_name'].toLocaleString().toLowerCase().indexOf(token.toLowerCase()) > -1) {
          if (jQuery.inArray(filteredCards[i].card_id, idsToAdd) == -1) {
            idsToAdd.push(filteredCards[i].card_id);
          }
        } else {
          if (jQuery.inArray(filteredCards[i].card_id, idsToRemove) == -1) {
            idsToRemove.push(filteredCards[i].card_id);
          }
        }
      }
      if (filteredCards[i]['full_name'] != null && typeof filteredCards[i]['full_name'] != 'undefined') {
        if (filteredCards[i]['full_name'].toLocaleString().toLowerCase().indexOf(token.toLowerCase()) > -1) {
          if (jQuery.inArray(filteredCards[i].card_id, idsToAdd) == -1) {
            idsToAdd.push(filteredCards[i].card_id);
          }
        } else {
          if (jQuery.inArray(filteredCards[i].card_id, idsToRemove) == -1) {
            idsToRemove.push(filteredCards[i].card_id);
          }
        }
      }
      if (filteredCards[i]['category'] != null && typeof filteredCards[i]['category'] != 'undefined') {
        if (filteredCards[i]['category'].toLocaleString().toLowerCase().indexOf(token.toLowerCase()) > -1) {
          if (jQuery.inArray(filteredCards[i].card_id, idsToAdd) == -1) {
            idsToAdd.push(filteredCards[i].card_id);
          }
        } else {
          if (jQuery.inArray(filteredCards[i].card_id, idsToRemove) == -1) {
            idsToRemove.push(filteredCards[i].card_id);
          }
        }
      }
    }
  });
  updateCardsViewAfterSearch(idsToRemove, idsToAdd);
}

/*END FILTERING CARDS*/
function getParameterByName(name, url) {
  if (!url)
    url = window.location.href;
  name = name.replace(/[\[\]]/g, "\\$&");
  var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
    results = regex.exec(url);
  if (!results)
    return null;
  if (!results[2])
    return '';
  return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function getDateTime() {
  var d = new Date;
  //	return ( '0' + d.getDate() ).slice(-2) + '/' + ( '0' + d.getMonth() ).slice(-2) + '/' + d.getFullYear() + ' ' + ( '0' + d.getHours() ).slice(-2) + ':' + ( '0' + d.getMinutes() ).slice(-2) + ':' + ( '0' + d.getSeconds() ).slice(-2);
  return d.getFullYear() + '/' + ('0' + (d.getMonth() + 1)).slice(-2) + '/' + ('0' + d.getDate()).slice(-2) + ' ' + ('0' + d.getHours()).slice(-2) + ':' + ('0' + d.getMinutes()).slice(-2) + ':' + ('0' + d.getSeconds()).slice(-2);
}
function checkNotifications() {
  jQuery.ajax({
    type: 'post',
    url: 'includes/get_notifications.php',
    success: function (result) {
      // console.log('notification result');
      // console.log(result);
      var response = JSON.parse(result);
      if (response != 'no_session') {
        if (response.data.length === 0) {
          jQuery('.sidebar-nav li i').css({
            'background': 'transparent',
            'border': 'none'
          });
        } else {
          jQuery('.sidebar-nav li i').css({
            'background': '#fd6f02',
            'border': '2px solid white'
          });
        }
      }
    },
    error: function (result) {
      console.log('error ' + result);
    }
  });
}


/*******************************************************************************
 *
 *										END OF CARD-CONTACTS.JS
 *
 *******************************************************************************/
jQuery(document).ready(function ($) {


  var active_session = jQuery.cookie('PHPSESSID');
  if (active_session) {
    //		console.log('session active');
  } else {
    //		console.log('session expired');
  }
  checkNotifications();
  var mymenu = jQuery('ul#mg-accordion');
  //	var dropdowns = jQuery(mymenu.find('li.dropdown > a'));
  jQuery(document).on('click', 'li.dropdown > a', function (e) {
    e.preventDefault();
    var items = jQuery(this).parents().siblings().find('ul.submenu');
    items.each(function () {
      if (jQuery(this).is(':visible')) {
        jQuery(this).slideUp('slow');
      }
    });
    jQuery(this).siblings('ul.submenu').slideToggle();
  });
  //	var mglabel = jQuery('#cbx-all-none').next('label.mg-checkbox');
  //	jQuery(document).on('mouseup', mglabel, function () {
  //		return;
  //	});
  jQuery(document).on('click', '#filter-sel ul li.folsel input', function () {
    var family = jQuery(this).attr('data-family');
    var checkboxes = jQuery('#filter-sel ul li.folsel input');
    var checkeds = jQuery('#filter-sel ul li.folsel input:checked');
    //		console.log('checkeds.length ' + checkeds.length);
    //		console.log('checkboxes.length ' + checkboxes.length);
    if (checkeds.length < checkboxes.length && checkeds.length > 0) {
      jQuery('#mg-accordion').find('a.checkboxStyle').addClass('active');
    } else if (checkeds.length == 0 || checkeds.length == checkboxes.length) {
      jQuery('#mg-accordion').find('a.checkboxStyle').removeClass('active');
      jQuery('#mg-accordion').find('a.checkboxStyle input').prop('checked', false);
      jQuery('#mg-accordion').find('a.checkboxStyle input').attr('value', 'none');
    }
    setTimeout(function () {
      filterCardsInclusive(family);
    }, 500);
  });
  jQuery('#cc_number').payment('formatCardNumber');
  jQuery('#cvc_number').payment('formatCardCVC');
  jQuery.fn.selectText = function () {
    var doc = document
      , element = this[0]
      , range, selection
      ;
    if (doc.body.createTextRange) {
      range = document.body.createTextRange();
      range.moveToElementText(element);
      range.select();
    } else if (window.getSelection) {
      selection = window.getSelection();
      range = document.createRange();
      range.selectNodeContents(element);
      selection.removeAllRanges();
      selection.addRange(range);
    }
  };
  getPageTemplates(templates);
  jQuery(document).on('click', 'drop-down select-folder li a', function (e) {
    e.preventDefault();
    var folder_id = $(this).attr('id');
  });
  jQuery(document).on('mouseenter', '.search-avatar', function () {
    var card_id = jQuery(this).parents('.card-box').data('card-id');
    //	if the search has not yet been done
    if (!jQuery(this).next('.mutual-contacts-slide').find('#title_count').length) {
      getMutualContacts('#m_c_' + card_id, card_id, 'card_mutual_contacts');
    }
    jQuery(this).next('.mutual-contacts-slide').addClass('active');
  });
  jQuery(document).on('mouseleave', '.search-avatar', function () {
    jQuery(this).next('.mutual-contacts-slide').removeClass('active');
  });
  jQuery(document).on('click', '.avsl', function (e) {
    e.preventDefault();
    var card_id = jQuery(this).closest('.card-box').data('card-id');
    var card = JSON.parse(localStorage.getItem('prof' + card_id));
    //	I put this because we can't view user profile if it is not added
    var private = false;
    if (card.need_approval && !card.your_contact) {
      private = true;
    }
    /*can th4 user see user profile for a card with pending request?*/
    if (private) {
      return;
    }
    var content = '';
    if (card.your_contact) {
      content = '<div class="card-status"><p class="card-added"><img style="width:auto;height:auto;" src="assets/sat_images/card-added.png" alt="" />Card Already Added</p></div>';
    } else {
      if (card.need_approval) {
        if (card.pending_request) {
          content = '<div class="card-status"><p class="card-added"><img style="width:auto;height:auto; src="assets/sat_images/remove-card.png" alt="" />Link Request Approval Pending</p></div>';
        } else {
          content = '<div class="card-status"><a href="#" class="send-request new-link-img"><img style="width:auto;height:auto; src="assets/sat_images/send-card.png" alt="" />Send Card Link Request</a></div>';
        }
      } else {
        content = '<div class="card-status"><a href="#" class="add-card new-link-img"><img style="width:auto;height:auto; src="assets/sat_images/add-card.png" alt="" />Add Card/Contact</a></div>';
      }
    }
    //		console.log(content);
    jQuery('#u-prof-name-profile').html(card.first_name + ' ' + card.last_name + "'s " + " Profile");
    jQuery('#u-prof-profile-image').attr('src', card.profile_image);
    jQuery('#u-prof-full-name').html('<a href="#" class="avatar-title"><span class="full-name">' + card.first_name + " " + card.last_name + '</span></a>');
    if (card.company_name != null) {
      jQuery('#u-prof-role').html(card.company_name);
    }
    jQuery('#u-prof-email-address').html('<a href="mailto:' + card.email_address + '" class="address-link">' + card.email_address + '</a>');
    var phones;
    var landline_exists = false;
    if (card.phone == null) {
      phones = '';
    } else {
      if (card.phone.length > 0) {
        phones = 't- <a href="tel:+' + card.phone + '">' + card.phone + '</a>';
        landline_exists = true;
      }
    }
    if (card.mobile == null) {
      phones += '';
    } else {
      if (card.mobile.length > 0) {
        if (landline_exists) {
          phones += '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
        }
        phones += 'm- <a href="tel:+' + card.mobile + '">' + card.mobile + '</a>';
      }
    }
    jQuery('#u-prof-phones').html(phones);
    if (card.website_link != null) {
      jQuery('#u-prof-website-link').html('<a href="' + card.website_link + '" class="address-link">' + card.website_link + '</a>');
    }
    jQuery('#u-prof-card-status').html(content);
    //		console.log('adding class');
    jQuery('body').addClass('active');
    jQuery('#overLay').slideDown();
    jQuery('#avatarSlideSec').slideDown();
    jQuery("html, body").animate({ scrollTop: 0 }, "slow");
  });//end click avatar-slide
  jQuery(document).on('mouseenter', '.mutual-contacts-slide', function () {
    jQuery(this).addClass('active');
  });
  jQuery(document).on('mouseleave', '.mutual-contacts-slide', function () {
    jQuery(this).removeClass('active');
  });
  jQuery(document).on('mouseenter', '.cradDetailLink', function () {
    //	var card_private = jQuery(this).parents('.card-box').data('card-private');
    //	if (card_private === true) {
    //		jQuery(this).find('li').html("Sorry, but you can't view details of a private card.");
    //	}
  });
  jQuery('.custom-dropdown ul').mCustomScrollbar();
  jQuery('#view-card-details').on('click', function (e) {
    e.preventDefault();
    jQuery('#search-bcn').val(jQuery(this).attr('data-bcn'));
    jQuery('#add-bcn').trigger('click');
    jQuery('#search-bcn').val('');
  });
  jQuery(document).on('click', '#add-bcn', function (e) {
    e.preventDefault();
    var bcn = jQuery('#search-bcn').val();
    jQuery.ajax({
      type: 'post',
      url: 'includes/check-card-id.php',
      data: { bcn: bcn },
      success: function (result) {
        if (result === 'owned') {
          swal({
            title: "Your own card",
            text: "You can't add one your own card to your folders. Please, check the BCN and retry.",
            html: true,
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#ff6603",
            confirmButtonText: "OK",
            closeOnConfirm: false
          });
        } else if (result === 'failed') {
          swal({
            title: "Invalid Card ID",
            text: "We were unable to find the card you are searching for.",
            html: true,
            type: "error",
            showCancelButton: false,
            confirmButtonColor: "#ff6603",
            confirmButtonText: "OK",
            closeOnConfirm: false
          });
        } else {
          setTimeout(function () {
            showCardDetails(result);
          }, 100);
        }
      },
      error: function (result) {
        jQuery('div').html(result).fadeIn('slow');
      }
    });
  });
  jQuery('input#searchbox').on('input', function () {
    if (this.value !== null && this.value !== "") {
      jQuery('#btnSearch').removeClass('disabled');
    } else {
      jQuery('#btnSearch').addClass('disabled');
    }
  });
  jQuery('.card-box-sec').children().remove();
  jQuery(document).on('click', '#det-btn-save', function (e) {
    e.preventDefault();
    var card_id = jQuery(this).closest('.user-card-detail:visible').data('card-id');
    jQuery.ajax({
      type: "POST",
      url: "includes/view-business-card/update_card_contact.php",
      data: {
        card_id: card_id,
        private: jQuery('#checkboxG5').is(':checked') ? 1 : 0,
        rating: parseFloat(jQuery('#rating-input').val())
      },
      success: function (data) {
        if (data === 'expired') {
          window.location.href = globalURL;
          return;
        }
        closePopup();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
      }
    });
  });
  jQuery(document).on('click', '#det-btn-cancel', function (e) {
    e.preventDefault();
    closePopup();
  });
  jQuery(document).on('click', '#buy-bcn', function () {
    var card_id = jQuery(this).attr('data-card-id');
    jQuery('#buy-premium-id').attr('data-card-id', card_id);
    jQuery('body').addClass('active');
    jQuery('#overLay').slideDown();
    jQuery('#buy-premium-id').slideDown();
    jQuery('#preViewSave').slideUp();
    jQuery("html, body").animate({ scrollTop: 0 }, "slow");
    return false;
  });
  jQuery(document).on('click', '.btn-new-folder', function () {
    jQuery('body').addClass('active');
    jQuery('#overLay').slideDown();
    jQuery('#createFolder').slideDown();
    jQuery('#userCardDetail').slideUp();
    jQuery("html, body").animate({ scrollTop: 0 }, "slow");
    return false;
  });
  jQuery(document).on('click', '#create_new_folder', function () {
    var page = jQuery(this).data('page');
    var folder_description = jQuery('#new_folder_name').val();
    if (folder_description.length === 0) {
      swal({
        title: "Warning!",
        text: "Missing new folder's name!",
        html: true,
        type: "warning",
        showCancelButton: false,
        confirmButtonColor: "#ff6603"
      });
      return;
    } else
      if (folder_description.length > 30) {
        swal({
          title: "Warning!",
          text: "Folders' names cannot have more than 30 characters. Please, try again.",
          html: true,
          type: "warning",
          showCancelButton: false,
          confirmButtonColor: "#ff6603"
        });
        return;
      } else {
        loaderText('Creating new folder ' + folder_description + '...');
        loader.show();
        jQuery.ajax({
          type: "POST",
          url: "includes/manage-folders/insert_new_folder.php",
          data: {
            folder_description: folder_description,
          },
          success: function (data) {
            if (data === 'expired') {
              window.location.href = globalURL;
              return;
            }
            if (data === 'exists') {
              swal({
                title: "Warning!",
                text: "This folder name already exists! Please, choose another name for your new folder.",
                html: false,
                type: "warning",
                showCancelButton: false,
                confirmButtonColor: "#ff6603",
                confirmButtonText: "Ok",
                closeOnConfirm: true
              });
              loader.hide();
              return;
            }
            if (data !== "false") {
              if (page == 'profile') {
                jQuery('ul.drop-down.select-folder li.apply').before(data);
                jQuery('#createFolder').slideUp();
                jQuery('#userCardDetail').slideDown();
                loader.hide();
              } else if (page == 'allfolders') {
                closePopup();
                loader.hide();
                getFolders('all-folders', fillPage);
              } else {
                closePopup();
                loader.hide();
              }
              jQuery('#new_folder_name').val('');
            }
          },
          error: function () {
            swal({
              title: "Error!",
              text: "An error occurred while creating new folder. Please, try again.",
              html: true,
              type: "error",
              showCancelButton: false,
              confirmButtonColor: "#ff6603"
            });
            return;
          }
        });
      }
  });
  jQuery(document).on('click', '#cancel_new_folder_creation', function () {
    if (jQuery(this).data('page') == 'profile') {
      jQuery('#new_folder_name').val('');
      jQuery('#createFolder').slideUp();
      jQuery('#userCardDetail').slideDown();
    } else {
      jQuery('#new_folder_name').val('');
      jQuery('#change_folder_name').val('');
      closePopup();
    }
  });
  jQuery(document).on('click', '#cancel_rename_folder', function () {
    jQuery('#new_folder_name').val('');
    jQuery('#renameFolder').slideUp();
  });
  jQuery(document).on('click', '.show-card-details, .cradDetailLink', function () {
    var card_id = jQuery(this).parents('.card-box').data('card-id');
    var private = jQuery(this).parents('.card-box').data('card-private');
    if (private !== true) {
      showCardDetails(card_id);
    }
  });
  jQuery(document).on('click', '.delete-comment', function (e) {
    e.preventDefault();
    var card_id = jQuery(this).closest('.user-card-detail:visible').data('card-id');
    var comment_id = jQuery(this).data('comment-id');
    if (comment_id.indexOf('_no_del') != -1) {
      return;
    }
    jQuery.ajax({
      type: 'post',
      url: 'includes/view-business-card/delete_comment.php',
      data: { comment_id: comment_id },
      success: function (response) {
        if (response === 'expired') {
          window.location.href = globalURL;
          return;
        }
        loadComments(card_id);
        //				jQuery('.comment-scroller').mCustomScrollbar('update');
      },
      error: function () {
        console.log('error deleting comment')
      }
    });
  });
  jQuery(document).on('click', '#add-comment', function (e) {
    e.preventDefault();
    var comment = jQuery('#comment-text').val();
    if (comment === '') {
      return;
    }
    var card_id = jQuery(this).closest('.user-card-detail:visible').data('card-id');
    jQuery.ajax({
      type: 'post',
      url: 'includes/view-business-card/add_comment.php',
      data: { comment: comment },
      success: function (result) {
        if (result === 'expired') {
          window.location.href = globalURL;
          return;
        }
        loadComments(card_id);
        //				jQuery('.comment-scroller').mCustomScrollbar('update');
        jQuery('#comment-text').val('');
      },
      error: function (result) {
        console.log('error adding comment');
      }
    });
  });
  //	jQuery('.innerSideBarLink').unbind("click").click(function () {
  jQuery('.innerSideBarLink').click(function () {
    getFolders('', fillSidebarMenu);
    var curItem = jQuery(this).attr('href');
    if (jQuery(this).parents('li').hasClass('active')) {
      jQuery('.profile-links > li').removeClass('active');
      jQuery('#user-main-wrap').removeClass('active');
      jQuery('.inner-sidebar').removeClass('active');
    } else {
      jQuery('.profile-links > li').removeClass('active');
      jQuery(this).parents('li').addClass('active');
      jQuery('#user-main-wrap').addClass('active');
      jQuery('.inner-sidebar').removeClass('active');
      jQuery(curItem).addClass('active');
    }
    return false;
  })
  /*******************************************************************************
   *
   *									REFACTORED FROM	STATUS-BAR.JS
   *
   *******************************************************************************/
  jQuery(document).on('click', '.add-card', function (e) {
    e.preventDefault();
    var functionName = updateAddButtonInDetails;
    card_id = jQuery(this).closest('.user-card-detail:visible').data('card-id');
    requires_reciprocity = jQuery(this).closest('.user-card-detail:visible').data('requires-reciprocity');
    //		console.log('details card id is ' + card_id);
    //		console.log('details reciprocity is ' + requires_reciprocity);
    if (typeof (card_id) === 'undefined') {
      var card_id = jQuery(this).closest('.card-box').data('card-id');
      var requires_reciprocity = jQuery(this).closest('.card-box').data('requires-reciprocity');
      //			console.log('search card id is ' + card_id);
      //			console.log('search reciprocity is ' + requires_reciprocity);
      functionName = updateButtonInSearch;
    }
    //		console.log(functionName);
    if (requires_reciprocity) {
      swal({
        title: "Please, confirm.",
        text: "This contact requires a mutual exchange, in short, your card will also be added to their contacts, do you want to continue?",
        html: true,
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#ff6603"
      }, function (isConfirm) {
        if (isConfirm) {
          jQuery.ajax({
            type: 'post',
            url: 'includes/card-link/c_check_reciprocity_user_has_card.php',
            data: {},
            success: function (response) {
              var r = JSON.parse(response);
              if (r === 'expired') {
                window.location.href = globalURL;
                return;
              }
              if (parseInt(r.result) > 0) {
                addCardToContact(card_id, functionName);
              } else {
                swal({
                  title: "Contact Not Added",
                  text: "This card requires a mutual exchange, but you do not currently have any cards. Please create a card first.",
                  html: true,
                  type: "error",
                  showCancelButton: false,
                  confirmButtonColor: "#ff6603"
                });
              }
            },
            error: function (result) {
              console.log('error: ' + result);
            }
          });
        }
      });
    } else {
      addCardToContact(card_id, functionName);
    }
  });
  jQuery(document).on('click', '.add-card2', function (e) {
    e.preventDefault();
    var functionName = updateAddButtonInDetails;
    card_id = jQuery(this).closest('.user-card-detail:visible').data('card-id');
    requires_reciprocity = jQuery(this).closest('.user-card-detail:visible').data('requires-reciprocity');
    //		console.log('details card id is ' + card_id);
    //		console.log('details reciprocity is ' + requires_reciprocity);
    if (typeof (card_id) === 'undefined') {
      var card_id = jQuery(this).closest('.card-box').data('card-id');
      var requires_reciprocity = jQuery(this).closest('.card-box').data('requires-reciprocity');
      //			console.log('search card id is ' + card_id);
      //			console.log('search reciprocity is ' + requires_reciprocity);
      functionName = updateButtonInSearch;
    }
    //		console.log(functionName);
    if (requires_reciprocity) {
      swal({
        title: "Please, confirm.",
        text: "This contact requires a mutual exchange, in short, your card will also be added to their contacts, do you want to continue?",
        html: true,
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#ff6603"
      }, function (isConfirm) {
        if (isConfirm) {
          jQuery.ajax({
            type: 'post',
            url: 'includes/card-link/c_check_reciprocity_user_has_card.php',
            data: {},
            success: function (response) {
              var r = JSON.parse(response);
              if (r === 'expired') {
                window.location.href = globalURL;
                return;
              }
              if (parseInt(r.result) > 0) {
                addCardToContact(card_id, functionName);
              } else {
                swal({
                  title: "Contact Not Added",
                  text: "This card requires a mutual exchange, but you do not currently have any cards. Please create a card first.",
                  html: true,
                  type: "error",
                  showCancelButton: false,
                  confirmButtonColor: "#ff6603"
                });
              }
            },
            error: function (result) {
              console.log('error: ' + result);
            }
          });
        }
      });
    } else {
      addCardToContact(card_id, functionName);
    }
  });
  jQuery(document).on('click', '.send-request', function (e) {
    e.preventDefault();

    var functionName = updateSendButtonInDetails;
    card_id = jQuery(this).closest('.user-card-detail:visible').data('card-id');
    requires_reciprocity = jQuery(this).closest('.user-card-detail:visible').data('requires-reciprocity');
    //		console.log('details card id is ' + card_id);
    //		console.log('details reciprocity is ' + requires_reciprocity);
    if (typeof (card_id) === 'undefined') {
      var card_id = jQuery(this).closest('.card-box').data('card-id');
      var requires_reciprocity = jQuery(this).closest('.card-box').data('requires-reciprocity');
      //			console.log('search card id is ' + card_id);
      //			console.log('search reciprocity is ' + requires_reciprocity);
      functionName = updateButtonInSearch;
    }
    if (requires_reciprocity) {
      swal(
        {
          title: "Please, confirm.",
          text: "This contact requires a mutual exchange, in short, your card will also be added to their contacts, do you want to continue?",
          html: true,
          type: "info",
          showCancelButton: true,
          confirmButtonColor: "#ff6603"
        }, function (isConfirm) {
          if (isConfirm) {
            sendCardLinkRequest(card_id, functionName);
            if (jQuery(t_on)) {
              t_on.ajax.reload();
            }
          }
        });
    } else {
      sendCardLinkRequest(card_id, functionName);
      setTimeout(function () {
        if (jQuery(t_on)) {
          t_on.ajax.reload();
        }
      }, 500);
    }
  });
  /*******************************************************************************
   *
   *										END OF STATUS-BAR.JS
   *
   *******************************************************************************/

  /*******************************************************************************
   *
   *										ALL-FOLDERS.PHP
   *
   *******************************************************************************/
  jQuery(document).on('click', '.css-checkbox-mg', function () {
    //		forces checkboxes to behave almost like radiobuttons: the difference is that
    //		here is allowed all checkboxes are unchecked. See: http://stackoverflow.com/questions/881166/jquery-checkboxes-like-radiobuttons
    jQuery('input.css-checkbox-mg').filter(':checked').not(this).prop('checked', false);
    var mg_checkboxes = jQuery('.css-checkbox-mg:checked');
    if (mg_checkboxes.length > 0) {
      if (!jQuery('#commands').is(':visible')) {
        jQuery('#commands').slideDown('slow');
      }
    } else {
      if (jQuery('#commands').is(':visible')) {
        jQuery('#commands').slideUp('slow');
      }
    }
  });
  jQuery(document).on('click', '#delete-folder', function (e) {
    e.preventDefault();
    var mg_checkboxes = jQuery('.css-checkbox-mg:checked');
    var folder_id = jQuery(mg_checkboxes).data('folder-id');
    var folder_description = jQuery(mg_checkboxes).data('folder-description');
    var cards_number = jQuery(mg_checkboxes).parents('div').parents('div').children('span.card-number').text();
    //		if (cards_number == 0) {
    swal({
      title: "Please, confirm.",
      text: "Do you really want to delete this folder?",
      html: true,
      type: "warning",
      showCancelButton: true,
      cancelButtonText: "No",
      confirmButtonColor: "#ff6603",
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: true
    }, function (isConfirm) {
      if (isConfirm) {
        jQuery.ajax({
          type: 'post',
          url: 'includes/manage-folders/delete_folder.php',
          data: { folder_id: folder_id },
          success: function () {
            jQuery('#commands').slideUp('slow');
            getFolders('all-folders', fillPage);
          },
          error: function () {
            //						console.log('error deleting folder');
          }
        });
      }
    });
  });
  //button click event to open rename folder dialog
  jQuery(document).on('click', '#rename-folder', function (e) {
    e.preventDefault();
    var mg_checkboxes = jQuery('.css-checkbox-mg:checked');
    var folder_id = jQuery(mg_checkboxes).data('folder-id');
    var folder_description = jQuery(mg_checkboxes).data('folder-description');
    //		jQuery('#change_folder_name').attr('placeholder', folder_description); if user click Rename without change an empty value is passed
    jQuery('#change_folder_name').val(folder_description);
    jQuery('#rename_folder').attr('data-folder-id', folder_id);
    jQuery('body').addClass('active');
    jQuery('#overLay').slideDown();
    jQuery('#renameFolder').slideDown();
    jQuery("html, body").animate({ scrollTop: 0 }, "slow");
  });
  //	button click event to rename folder
  jQuery(document).on('click', '#rename_folder', function () {
    var folder_id = jQuery(this).data('folder-id');
    var folder_description = jQuery('#change_folder_name').val();
    jQuery.ajax({
      type: 'post',
      url: 'includes/manage-folders/update_folder.php',
      data: { folder_id: folder_id, folder_description: folder_description },
      success: function (result) {
        if (result === 'expired') {
          window.location.href = globalURL;
          return;
        }
        if (result === 'exists') {
          swal({
            title: "Warning!",
            text: "This folder name already exists! Please, choose another name for your new folder.",
            html: false,
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#ff6603",
            confirmButtonText: "Ok",
            closeOnConfirm: true
          });
          if (jQuery('#commands').is(':visible')) {
            jQuery('#commands').slideUp('slow');
          }
          loader.hide();
          return;
        }
        if (jQuery('#commands').is(':visible')) {
          jQuery('#commands').slideUp('slow');
        }
        getFolders('all-folders', fillPage);
        closePopup();
      },
      error: function (result) {
        //				console.log('error renaming folder');
      }
    });
  });
  //	jQuery(document).on('click', '#delete_selected_cards', function (e) {
  //		e.preventDefault();
  //	jQuery('.controls-list > li:not(.folsel) > a').click(function () {
  jQuery('.controls-list > li > a').click(function () {
    if (jQuery(this).attr('id') === 'delete_selected_cards') {
      process_delete_request();
      return;
    }
    if (jQuery(this).parent().hasClass('active')) {
      jQuery('.controls-list > li').removeClass('active');
    } else {
      jQuery('.controls-list > li').removeClass('active');
      jQuery(this).parent('li').addClass('active');
    }
    jQuery('#user-main-wrap').removeClass('active');
    jQuery('.profile-links > li').removeClass('active');
    jQuery('.inner-sidebar').removeClass('active');
    return false;
  });
  /*******************************************************************************
   *
   *										END ALL-FOLDERS.PHP
   *
   *******************************************************************************/

  /*******************************************************************************
   *
   *										MY-OWN-CARDS.PHP.PHP
   *
   *******************************************************************************/
  jQuery(document).on("click", ".add-fav", function (e) {
    jQuery('.add-fav').removeClass('fav');
    jQuery(this).parents('.card-box').addClass('fav');
    var card_id = jQuery(this).closest('.card-box').attr('id');
    //		console.log('fav id is ' + card_id);
    jQuery.ajax({
      type: "POST",
      url: "includes/update-fav.php",
      data: {
        stringdata: card_id
      },
      success: function (data) {
        loadCards();
      }
    });
    return false;
  });
  jQuery(document).on('click', '#search-cards', function () {
    contactSearched(jQuery('#search-for').val());
  });

  jQuery(document).on('click', '#search-for', function () {
    //		globalSearch = false;
  });

  jQuery(document).on('click', '#searchbox', function () {
    //		globalSearch = true;
  });

  jQuery('#searchbox').keypress(function (e) {
    var key = e.which;
    // e.preventDefault();
    if (key === 13)  // the enter key code
    {
      // alert('searchbox');
      var searchValue = jQuery("#searchbox").val();
      performSearch(searchValue, ".card-box-sec");
      return false;
    }
  });
  jQuery('#search-for').keypress(function (e) {
    var key = e.which;
    // e.preventDefault();
    if (key === 13)  // the enter key code
    {
      // alert('searchbox');
      contactSearched(jQuery('#search-for').val());
      return false;
    }
  });
  jQuery('#search-bcn').keypress(function (e) {
    var key = e.which;
    // e.preventDefault();
    if (key === 13)  // the enter key code
    {
      // alert('search-bcn');
      jQuery('#add-bcn').trigger('click');
      return false;
    }
  });

  jQuery('#premium_id').keypress(function (e) {
    var key = e.which;
    // e.preventDefault();
    if (key === 13)  // the enter key code
    {
      // alert('premium_id');
      jQuery('#btnBuyPrem').trigger('click');
      return false;
    }
  });

  /*******************************************************************************
   *
   *										END MY-OWN-CARDS.PHP.PHP
   *
   *******************************************************************************/
  /*******************************************************************************
   *
   *										SETTING.PHP
   *
   *******************************************************************************/
  //	jQuery.validator.addMethod("pwcheck", function (value) {
  //		return /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/.test(value); // consists of only these
  //	});
  //	function UpdatePasswordAddValidation() {
  //		validationAbstraction('#frmUpdatePassword', {
  //			new_confirm_password: {
  //				required: true,
  //				pwcheck: '^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$',
  //				equalTo: "#new_password"
  //			}
  //		});
  //	}
  function check_password(pwd) {
    return /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/.test(pwd);
  }
  jQuery(document).on("click", "#update_password", function (e) {
    e.preventDefault();
    var new_password = jQuery('#new_password').val();
    var confirm_password = jQuery('#confirm_password').val();
    if (!check_password(new_password)) {
      swal({
        title: "Warning!",
        text: "Password must be 8 characters long with at leat 1 uppercase letter and 1 numeric value.",
        html: true,
        type: "warning",
        showCancelButton: false,
        confirmButtonColor: "#ff6603",
        confirmButtonText: "Ok",
        closeOnConfirm: true
      });
      return;
    }
    if (new_password !== confirm_password) {
      swal({
        title: "Warning!",
        text: "Passwords don't match.",
        html: true,
        type: "warning",
        showCancelButton: false,
        confirmButtonColor: "#ff6603",
        confirmButtonText: "Ok",
        closeOnConfirm: true
      });
      return;
    }
    jQuery.ajax({
      type: "POST",
      url: "includes/profile/update_password.php",
      data: {
        email_address: jQuery("#email_address").val(),
        password: jQuery("#new_password").val()
      },
      success: function (data) {
        if (data === 'expired') {
          window.location.href = globalURL;
          return;
        }
        jQuery("#new_password").val('');
        jQuery("#confirm_password").val('');
        if (data == "success") {
          jQuery('body').addClass('active');
          jQuery('#overLay').slideDown();
          jQuery('#settingUpDate').slideDown();
          jQuery("html, body").animate({ scrollTop: 0 }, "slow");
          return false;
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
  });
  jQuery(document).on("click", "#update_preferences", function (e) {
    e.preventDefault();
    var checked = jQuery('#checkboxG26').prop('checked');
    var wants_news = 0;
    if (checked) {
      wants_news = '1';
    }
    jQuery.ajax({
      type: 'post',
      url: 'includes/profile/mg_update_newsletter.php',
      data: { wants_news: wants_news },
      success: function () {
        jQuery('body').addClass('active');
        jQuery('#overLay').slideDown();
        jQuery('#settingUpDate').slideDown();
        jQuery("html, body").animate({ scrollTop: 0 }, "slow");
        return false;
      },
      error: function () {
        console.log('error updating preferences');
      }
    });
  });
  /*******************************************************************************
   *
   *										SAVE-CARD.PHP
   *
   *******************************************************************************/
  jQuery('.fancybox').fancybox({
    beforeShow: function () {
      //			if ( jQuery(window).width( ) > 767 ) {
      //			set new fancybox width
      var newWidth = Math.round(jQuery('#preview-details img').width() * 2);
      var newHeight = Math.round((jQuery('#preview-details img').height() * newWidth) / jQuery('#preview-details img').width());
      jQuery('#preview-details2').css({
        "width": newWidth,
        "height": newHeight,
        "max-width": newWidth,
        "max-height": newHeight
      });
      this.width = newWidth;
      this.height = newHeight;
      prepareLinks_zoom();
      //			}
    },
    //			if ( jQuery(window).width() > 767 ) {
    fitToView: true,
    autoSize: true,
    scrolling: "no"
    //			}
  });
  //	jQuery('.ciccio').magnificPopup({type:'inline', midClick: true });
  //	jQuery('.fancybox').featherlight("#preview-details2");

  jQuery('#email_selected_cards').click(function () {
    var folder_id = jQuery('#page-title').data('folder-id');
    var card_ids = [];
    jQuery('.my-cards .card-box input.css-checkbox:checked').each(function () {
      card_ids.push(jQuery(this).attr('id').substr(4));
    });
    //		console.log(card_ids);
    if (card_ids.length === 0) {
      swal({
        title: "Warning!",
        text: "Please select at least one card, please.",
        html: true,
        type: "warning",
        showCancelButton: false,
        confirmButtonColor: "#ff6603",
        confirmButtonText: "Ok",
        closeOnConfirm: true
      });
      return;
    }
    var cards = card_ids.join(',');
    jQuery.ajax({
      type: 'post',
      url: 'includes/mg_get_email_recipients.php',
      data: { cards: cards },
      success: function (result) {
        var mailto_link = 'mailto:' + result;
        window.location.href = mailto_link;
      },
      error: function (result) {
        jQuery('div').html(result).fadeIn('slow');
      }
    });
  });
  jQuery('#delete-account').click(function (e) {
    e.preventDefault();
    swal({
      title: "Warning!",
      text: "If you proceed all your data will be completely deleted and your account will be removed. Are you sure you want to delete your Cardition account?",
      html: true,
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#ff6603",
      confirmButtonText: "Ok",
      closeOnConfirm: true
    }, function isConfirm() {
      if (isConfirm) {
        loader.show();
        var date = new Date();
        date.setTime(date.getTime() + (30 * 60 * 1000));
        jQuery.ajax({
          type: 'post',
          url: 'includes/profile/delete_account.php',
          success: function (result) {
            //						console.log(result);
            jQuery.cookie('accountDeleted', result, { expires: date });
            window.location.href = 'includes/auth/logout.php';
          },
          error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
          }
        });
      }
    });
  });
  setInterval(function () {
    var this_page = document.location.href;
    //		console.log(this_page);
    if (this_page.indexOf('index.php') === -1) {
      $.get("includes/auth/check_session.php", function (data) {
        if (data == 0) {
          //					console.log('data is ' + data);
          //					window.location.href = globalURL + "includes/auth/logout.php";
        }
      });
    }
  }, 15 * 60 * 1000);


  jQuery('.share-gmail').hover(function () {
    $(this).addClass('share-gmail-hover');
  }, function () {
    $(this).removeClass('share-gmail-hover');
  });
  jQuery('.share-outlook').hover(function () {
    $(this).addClass('share-outlook-hover');
  }, function () {
    $(this).removeClass('share-outlook-hover');
  });
  jQuery('.share-mine').hover(function () {
    $(this).addClass('share-mine-hover');
  }, function () {
    $(this).removeClass('share-mine-hover');
  });


  jQuery('.btn-new-card').on('click', function (e) {
    //		e.preventDefault();
    if (jQuery(this).hasClass('disabled')) {
      //			alert('is disabled');
      return;
    }
    var creationStep = jQuery('body').data('creation-step');
    switch (creationStep) {
      case 0:
        break;
      case 1:
        break;
      case 2:
        break;
      case 3:
        break;
      default:
        jQuery(this).attr('href', 'create-card-0.php');
        break;
    }

  });




  //end jQuery ready
});
//end jQuery ready


//	jQuery(document).on('change', '#rating-input', function (e) {
//		e.preventDefault();
//		var card_id = jQuery(this).data("card-id");
//		console.log('new rating is ' + parseFloat(jQuery('#rating-input').val()));
//		jQuery.ajax({
//			type: "POST",
//			url: "includes/view-business-card/update_card_contact.php",
//			data: {
//				card_id: card_id,
//				private: jQuery('#chkPrivate').is(':checked') ? 1 : 0,
//				rating: parseFloat(jQuery('#rating-input').val())
//			},
//			success: function (data) {
//				console.log("closing");
//			},
//			error: function (jqXHR, textStatus, errorThrown) {
//				console.log(textStatus, errorThrown);
//			}
//		});
//	});
//checkboxG27
