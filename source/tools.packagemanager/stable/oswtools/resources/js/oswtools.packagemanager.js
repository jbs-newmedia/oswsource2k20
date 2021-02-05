$(document).ready(function(){
	oTable=$('#oswtools_manager').DataTable({
		"iDisplayLength": 50,
		"aoColumnDefs": [
			{"aTargets": [unsorted], "bSortable": false},
		] 
	});
});

function manager(i, manager_session, manager_action, manager_serverlist, manager_package, manager_release) {
	if (oTable.$('#package_'+i).find('.manager_options .'+manager_action).hasClass('disabled')!==true) {
		oTable.$('#package_'+i).find('.manager_options .'+manager_action).html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
		$.ajax({
			method: "POST",
			url: "index.php",
			dataType: 'json',
			data: {
				"session": manager_session,
				"action": 'start',
				"sl": manager_serverlist,
				"doaction": manager_action,
				"manager_package": manager_package,
				"manager_release": manager_release
			}
		})
		.done(function(ar_data) {
			$.each( ar_data, function(i, data) {
				if (data.version_installed=='0.0') {
					data.version_installed='-----';
				}
				
				oTable.$('#package_'+i).find('.manager_release').html(data.version_installed);
				
				if (manager_action=='install') {
					oTable.$('#package_'+i).find('.manager_options .'+manager_action).html('<i class="fa fa-plus fa-fw"></i>');
				}
				if (manager_action=='update') {
					oTable.$('#package_'+i).find('.manager_options .'+manager_action).html('<i class="fa fa-refresh fa-fw"></i>');
				}
				if (manager_action=='remove') {
					oTable.$('#package_'+i).find('.manager_options .'+manager_action).html('<i class="fa fa-remove fa-fw"></i>');
				}
				
				if(data.options.install==true) {
					oTable.$('#package_'+i).find('.manager_options .install').removeClass('disabled');
				} else {
					oTable.$('#package_'+i).find('.manager_options .install').addClass('disabled');
				}
				if(data.options.update==true) {
					oTable.$('#package_'+i).find('.manager_options .update').removeClass('disabled');
				} else {
					oTable.$('#package_'+i).find('.manager_options .update').addClass('disabled');
				}
				if(data.options.remove==true) {
					oTable.$('#package_'+i).find('.manager_options .remove').removeClass('disabled');
				} else {
					oTable.$('#package_'+i).find('.manager_options .remove').addClass('disabled');
				}
			});
		});
	}
}

function removeCustom(i, manager_session, manager_package) {
	manager_action='remove';
	if (oTable.$('#package_'+i).find('.manager_options .'+manager_action).hasClass('disabled')!==true) {
		oTable.$('#package_'+i).find('.manager_options .'+manager_action).html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
		$.ajax({
			method: "POST",
			url: "index.php",
			dataType: 'json',
			data: {
				"session": manager_session,
				"action": 'start',
				"sl": 'custom',
				"doaction": 'removecustom',
				"manager_package": manager_package
			}
		})
		.done(function(data) {
			oTable.$('#package_'+i).find('.manager_link').html(manager_package);
			oTable.$('#package_'+i).find('.manager_options .'+manager_action).html('<i class="fa fa-remove fa-fw"></i>');
			oTable.$('#package_'+i).find('.manager_options .remove').addClass('disabled');
		});
	}
}

function updateAll() {
	oTable.$('.manager_options').find('.update').not('.disabled').each(function() {
		eval(decodeURI(this));
	});
}