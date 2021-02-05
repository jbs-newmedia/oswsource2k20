$(document).ready(function() {
    $('#fprojectverify_main').DataTable({
    	"iDisplayLength": 100,
    	"order": [[ 1, "asc" ]],
		"aoColumnDefs": [{"aTargets": [0], "bSortable": false}]
    });
	$('.selectpicker').selectpicker();
});

function engine(element, session) {
	$.ajax({
		method: "POST",
		url: "index.php",
	    dataType: 'json',
		data: {
			"session": session,
			"action": 'settings',
			"doaction": 'doignore',
			"element": element
		}
	})
	.done(function(data) {
		if (data.status==true) {
			window.location = 'index.php?session='+session;
		}
	});
}

