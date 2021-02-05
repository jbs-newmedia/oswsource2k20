$(document).ready(function() {
    $('#logclear_main').DataTable({
    	"iDisplayLength": 100,
    	"order": [[ 1, "asc" ]],
		"aoColumnDefs": [{"aTargets": [0], "bSortable": false}]
    });
    
	$("#select_all").click( function() {
		$("input[type='checkbox']").prop('checked', true);
		return false;
	});
	// Select none
	$("#select_none").click( function() {
		$("input[type='checkbox']").prop('checked', false);
		return false;
	});
	// Invert selection
	$("#select_invert").click( function() {
		$("input[type='checkbox']").each( function() {
			$(this).prop('checked', !$(this).prop('checked'));
		});
		return false;
	});
});