function approveCard(card_id, email) {
	var date_string = getDateTime();
	loaderText('Approving card request...');
	loader.show();
	jQuery.ajax({
		type: "POST",
		url: "includes/card-link/u_link_request.php",
		data: {
			card_id: card_id,
			link_status: 'ACCEPTED',
			email: email,
			date_string: date_string
		},
		success: function (data) {
//			if ( data === 'expired' ) {
//				window.location.href = globalURL;
//				return;
//			}
			loader.hide();
			updateTableIn();
		}
	});
}
function rejectCard(card_id, email) {
	loaderText('Rejecting card request...');
	loader.show();
	jQuery.ajax({
		type: "POST",
		url: "includes/card-link/u_link_request.php",
		data: {
			card_id: card_id,
			link_status: 'REJECTED',
			email: email
		},
		success: function (data) {
			loader.hide();
			updateTableIn();
		}
	});
}
function cancelRequest(card_id) {
	jQuery.ajax({
		type: "POST",
		url: "includes/card-link/u_link_request_cancel_as_sender.php",
		data: {card_id: card_id},
		success: function (data) {
			if (data === 'expired') {
				window.location.href = globalURL;
				return;
			}
			updateTableOn();
		}
	});
}
function updateTableIn() {
	t_in.ajax.reload(function () {
		if (!t_in.data().count()) {
			jQuery('table.dataTable thead th').addClass('not-arrow');
		} else {
			jQuery('table.dataTable thead th').removeClass('not-arrow');
		}
	});
}
function updateTableOn() {
	t_on.ajax.reload(function () {
		if (!t_on.data().count()) {
			jQuery('table.dataTable thead th').addClass('not-arrow');
		} else {
			jQuery('table.dataTable thead th').removeClass('not-arrow');
		}
	});
}

jQuery(document).ready(function () {

	var rejectId = jQuery.cookie('rejectRequest');
	var approveId = jQuery.cookie('approveRequest');

	t_in = jQuery('#grid_in').DataTable({
		"initComplete": function () {
			var api = this.api();
			if ((api).data().count() == 0) {
				jQuery('#grid_in thead tr th').addClass('not-arrow');
				console.log('t_in adding class not-arrow');
			} else {
				jQuery('#grid_in thead tr th').removeClass('not-arrow');
				console.log('t_in removing class not-arrow');
			}
			if (approveId != null) {
				jQuery('#btn_app_' + approveId).onclick();
				jQuery.removeCookie('approveRequest');
			}
			if (rejectId != null) {
				jQuery('#btn_rej_' + rejectId).trigger('click');
				jQuery.removeCookie('rejectRequest');
			}
		},
		"ajax": {
			"url": "includes/card-link/r_link_requests.php?report_type=new_link_requests"
		},
		"fixedHeader": "true",
		"columnDefs": [
			{
				className: "approve-reject",
				"targets": [4]
			}
		],
		aoColumns: [
			{
				"mData": 0,
				"mRender": function (data, type, full) {
					return '<div class="about-user"><a class="profile_xsmall avsl" href="#"><img class="img-circle" src="' + full[0] + '" alt=""></a><span class="user-info-detail"><a href="#">' + full[2] + ' ' + full[1] + '</a><a href="#"><i>' + full[3] + '</i></a></span></div>'
				}
			},
			{
				"mData": 1,
				"mRender": function (data, type, full) {
					return full[4];
				}
			},
			{
				"mData": 2,
				"mRender": function (data, type, full) {
					return full[5];
				}
			},
			{
				"mData": 3,
				"mRender": function (data, type, full) {
					var dt = full[6].split(' ');
					return dt[0] + ' at ' + dt[1];
				}
			},
			{
				"mData": 4,
				"mRender": function (data, type, full) {
					return '<a class="approve" id=\"btn_app_' + full[7] + '\" onclick=\"approveCard(\'' + full[7] + '\')\">Approve</a>\n\
							<a class="reject" id=\"btn_rej_' + full[7] + '\" onclick=\"rejectCard(\'' + full[7] + '\')\">Reject</a>';
//					return '<a class="approve" id=\"btn_' + full[7] + '\" onclick=\"approveCard(\'' + full[7] + '\', \'' + full[8] + '\')\">Approve</a>\n\
//							<a class="reject" id=\"btn_' + full[7] + '\" onclick=\"rejectCard(\'' + full[7] + '\', \'' + full[8] + '\')\">Reject</a>';
				}
			}
		]
	});

	t_on = jQuery('#grid_on').DataTable({
		"initComplete": function () {
			var api = this.api();
			if ((api).data().count() == 0) {
				jQuery('#grid_on thead tr th').addClass('not-arrow');
				console.log('t_on adding class not-arrow');
			} else {
				jQuery('#grid_on thead tr th').removeClass('not-arrow');
				console.log('t_on removing class not-arrow');
			}
		},
		"ajax": {
			"url": "includes/card-link/r_link_requests.php?report_type=my_link_requests"
		},
		fixedHeader: true,
		"columnDefs": [
			{
				className: "approve-reject",
				"targets": [4]
			}
		],
		aoColumns: [
			{
				"mData": 0,
				"mRender": function (data, type, full) {
					return '<div class="about-user"><a class="profile_xsmall avsl" href="#"><img class="img-circle" src="' + full[0] + '" alt=""></a><span class="user-info-detail"><a href="#">' + full[2] + ' ' + full[1] + '</a><a href="#"><i>' + full[3] + '</i></a></span></div>'
				}
			},
			{
				"mData": 1,
				"mRender": function (data, type, full) {
					return full[4];
				}
			},
			{
				"mData": 2,
				"mRender": function (data, type, full) {
					return full[5];
				}
			},
			{
				"mData": 3,
				"mRender": function (data, type, full) {
					var dt = full[6].split(' ');
					return dt[0] + ' at ' + dt[1];
				}
			},
			{
				"mData": 4,
				"mRender": function (data, type, full) {
					return '<a class="Cancel" onclick="cancelRequest(\'' + full[7] + '\')">Cancel</a>';
				}
			}
		]
	});
//	if ( jQuery('#incoming').hasClass('active') ) {//this doesn't work
//		jQuery('input#filter').on('keyup change', function () {
//			t_in.search(this.value).draw();
//		});
	jQuery(document).on('keyup change', '#grid_in_filter label input', function () {
		t_in.search(this.value).draw();
		console.log('typing in');
	});
//	} else if ( jQuery('#outgoing').hasClass('active') ) {
//		jQuery('input#filter').on('keyup change', function () {
//			t_on.search(this.value).draw();
//		});
	jQuery(document).on('keyup change', '#grid_on_filter label input', function () {
		t_on.search(this.value).draw();
		console.log('typing out');
	});
//	}
//		jQuery('#grid_filter label').html(jQuery('#grid_filter label').find('input'));
	jQuery('#grid_in_filter label').html(jQuery('#grid_in_filter label').find('input'));
	jQuery('#grid_on_filter label').html(jQuery('#grid_on_filter label').find('input'));
	jQuery('#grid_in_filter label input').attr('placeholder', 'Search').css({
		'color': '#969BAA',
		'font-size': '15px'
	});
	jQuery('#grid_on_filter label input').attr('placeholder', 'Search').css({
		'color': '#969BAA',
		'font-size': '15px'
	});
});
