$(document).ready(function () {
	$('#oswtools_logclear').DataTable({
		"iDisplayLength": 100,
		"order": [[1, "asc"]],
		"aoColumnDefs": [{"aTargets": [0], "bSortable": false}]
	});
});