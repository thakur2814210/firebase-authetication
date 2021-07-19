var t;
function deleteCard(card_link_id) {
	swal({
		title: "Confirm",
		text: "By clicking on yes, your card will be removed from the user folders, Are you sure you want to do this?",
		html: true,
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#ff6603",
		confirmButtonText: "Ok",
		closeOnConfirm: true
	}, function (isConfirm) {
		if ( isConfirm ) {
			jQuery.ajax({
				type: "POST",
				url: "includes/card-link/d_link_request_as_card_owner.php",
				data: {
					card_link_id: card_link_id
				},
				success: function (data) {
					var el = jQuery("#btn_" + card_link_id);
					var row = el.closest("tr");
					row.remove();
				}
			});
			t.ajax.reload(function () {
				if ( !t.data().count() ) {
					jQuery('table.dataTable thead').addClass('not-arrow');
				} else {
					jQuery('table.dataTable thead').removeClass('not-arrow');
				}
			});
		}
	});
}

jQuery(document).ready(function () {
	t = jQuery('#grid').DataTable({
		"initComplete": function () {
			var api = this.api();
			if ( !( api ).data().count() ) {
				jQuery('#grid thead tr th').addClass('not-arrow');
				console.log('t_on adding class not-arrow');
			} else {
				jQuery('#grid thead tr th').removeClass('not-arrow');
				console.log('t_on removing class not-arrow');
			}
		},
		"ajax": {
			"url": "includes/card-link/r_link_requests.php?report_type=holders_of_your_card"
		},
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
					return '<a class="delete" id=\"btn_' + full[7] + '\" onclick=\"deleteCard(\'' + full[7] + '\')\">Delete</a>';
				}
			}
		]
	});
	jQuery('#grid_filter label').html(jQuery('#grid_filter label').find('input'));
	jQuery('#grid_filter label input').attr('placeholder', 'Search').css({
		'color': '#969BAA',
		'font-size': '15px'
	});

//		needed to make filter work even with stanbdrd DataTable
	jQuery('#grid_filter label input').on('keyup change', function () {
		t.search(this.value).draw();
	});

//		this replicate standrd search function using Satish control	
//		jQuery('input#filter').on('keyup change', function () {
//			t.search(this.value).draw();
//		});

//this for add search to ech columns
//		t.columns().every(function () {
//			var that = this;
//			jQuery('input#filter').on('keyup change', function () {
//				if ( that.search() != this.value ) {
//					that
//									.search(this.value)
//									.draw();
//				}
//			});
//		});
});