jQuery(document).ready(function ($) {

	//set welcome text + user first name
	if (localStorage["first_name"]) {
		$("#test").text("Welcome, " + localStorage["first_name"]);
	}

	var folder_dd = $("#dd_folders");

	var dropdowns = jQuery(".dropdown-menu");
	//check if we can load users folders (IOW the user is logged in)
	if (dropdowns.length > 0) {
		$.ajax({
			type: "GET",
			url: "includes/manage-folders/get_folders.json.php",
			beforeSend: function () {
				//l.start();
			},
			success: function (data) {
				var response = JSON.parse(data);
				if (response.success) {
					var folders = response.result;
					if (folders.length > 0) {
						var i = 0, l = folders.length;
						for (i; i < l; i++) {
							var folderRef = globalURL + "card-contacts.php?category=folders&folder_id=" + folders[i].id;
							folder_dd.append($('<li>').append($('<a tabindex="-1" href=" ' + folderRef + '">').append(folders[i].description)));
						}
					} else {
						folder_dd.append($('<li>').append($('<a tabindex="-1" href="#">').append("None")));
					}
				}
			}
		});
	}
});