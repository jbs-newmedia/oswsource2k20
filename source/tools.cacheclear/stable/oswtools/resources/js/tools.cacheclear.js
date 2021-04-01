$(document).ready(function () {
	$('#oswtools_cacheclear').DataTable({
		"iDisplayLength": 100,
		"order": [[1, "asc"]],
		"aoColumnDefs": [{"aTargets": [0], "bSortable": false}]
	});
});