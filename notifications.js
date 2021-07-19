var t_activity;
function checkNotifications(){
	jQuery.ajax({
		type: 'post',
		url: 'includes/get_notifications.php',
		success: function (result) 
		{
			var response = JSON.parse(result);
			if (response.data.length === 0){
				jQuery('.sidebar-nav li i').css({
					'background': 'transparent',
					'border': 'none'
				});
			}else{
				jQuery('.sidebar-nav li i').css({
					'background': '#fd6f02',
					'border': '2px solid white'
				});
			}
		},
		error: function (result)
		{
			console.log('error '+result);
		}
	});
}
function deleteNotification(id){
	jQuery.ajax({
		type: 'post',
		url: 'includes/delete_notification.php',
		data: {id: id},
		success: function (result) 
		{
			t_activity.ajax.reload(function(){
				if (!t_activity.data().count()){
					jQuery('.sidebar-nav li i').css({
						'background': 'transparent',
						'border': 'none'
					});
				}
			});
		},
		error: function (result)
		{
			
		}
	});
}

jQuery(document).ready(function () {
	t_activity = jQuery('#grid_activity').DataTable({ 
		"initComplete": function () {
			var api = this.api();
			if ( !( api ).data().count() ) {
				jQuery('#grid_activity thead tr th').addClass('not-arrow');
				console.log('t_on adding class not-arrow');
			} else {
				jQuery('#grid_activity thead tr th').removeClass('not-arrow');
				console.log('t_on removing class not-arrow');
			}
		},
		"ajax": {
			"url": "includes/get_notifications.php"
		},
		"columnDefs": [
			{
				className: "approve-reject",
				"targets": [3]
			},
			{
				className: "larger",
				"targets": [0]
			},
			{
				className: "largest",
				"targets": [1]
			},
			{
				className: "smaller",
				"targets": [2]
			},
			{
				className: "smallest",
				"targets": [3]
			}
		],
		aoColumns: [
			{
				"mData": 0,
				"mRender": function (data, type, full) {
					return '<div class="about-user"><a class="profile_xsmall avsl" href="#"><img class="img-circle" src="' + full[0] + '" alt=""></a><span class="user-info-detail" style="margin-top: 8px;"><a href="#" style="float: left; color: #fd6f02 !important; display: inline-block;font-size: 13px;">' + full[1] + ' ' + full[2] + '</a>&nbsp;<a href="#" style="float: left; color: #000 !important; display: inline-block;font-size: 13px;">' + full[3] + '</a></span></div>';
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
					if ( type === 'display' ) {
						var dt = full[5].split(' ');
						return dt[0] + ' at ' + dt[1];
					}
					return data;
				}
			},
			{
				"mData": 3,
				"mRender": function (data, type, full) {
					return '<a class="delete" onclick="deleteNotification(\''+full[6]+'\')">Delete</a>';
				}
			}
		]
//		,"order": [[ 2, "desc" ]]
		,"order": []
	});
//	t_activity.order( [ 2, 'desc' ] ).draw();
	jQuery('table#grid_activity th').css({
		"background": "#f8d97c"
	});
	jQuery('#grid_activity_length').hide();
	jQuery('#grid_activity_filter').hide();
	jQuery('#grid_activity thead').hide();
});

