$(document).ready(function () {
	oTable = $('#oswtools_gitmanager').DataTable({
		"iDisplayLength": 50,
		"aoColumnDefs": [
			{"aTargets": [1], "bSortable": false},
		]
	});
});

function manager(i, link, manager_action, manager_package) {
	if (oTable.$('#package_' + i).find('.manager_options .' + manager_action).hasClass('disabled') !== true) {
		oTable.$('#package_' + i).find('.manager_options .' + manager_action).html('<i class="fas fa-spinner fa-spin fa-fw"></i>');
		$.ajax({
			method: "POST",
			url: link,
			dataType: 'json',
			data: {
				"doaction": manager_action,
				"manager_package": manager_package
			}
		})
			.done(function (ar_data) {
				$.each(ar_data, function (i, data) {
					oTable.$('#package_' + i).find('.manager_release').html(data.installed);

					if (manager_action == 'install') {
						oTable.$('#package_' + i).find('.manager_options .' + manager_action).html('<i class="fas fa-plus fa-fw"></i>');
					}
					if (manager_action == 'update') {
						oTable.$('#package_' + i).find('.manager_options .' + manager_action).html('<i class="fa fa-sync fa-fw"></i>');
					}
					if (manager_action == 'remove') {
						oTable.$('#package_' + i).find('.manager_options .' + manager_action).html('<i class="fa fa-times fa-fw"></i>');
					}

					if (data.install == true) {
						oTable.$('#package_' + i).find('.manager_options .install').removeClass('disabled');
					} else {
						oTable.$('#package_' + i).find('.manager_options .install').addClass('disabled');
					}
					if (data.update == true) {
						oTable.$('#package_' + i).find('.manager_options .update').removeClass('disabled');
					} else {
						oTable.$('#package_' + i).find('.manager_options .update').addClass('disabled');
					}
					if (data.remove == true) {
						oTable.$('#package_' + i).find('.manager_options .remove').removeClass('disabled');
					} else {
						oTable.$('#package_' + i).find('.manager_options .remove').addClass('disabled');
					}
				});
			});
	}
}

function updateAll() {
	oTable.$('.manager_options').find('.update').not('.disabled').each(function () {
		eval(decodeURI(this));
	});
}