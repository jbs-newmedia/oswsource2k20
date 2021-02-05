function confirm(message, url) {
    bootbox.confirm({
        message: message,
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> No'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> Yes'
            }
        },
        callback: function (result) {
        	if (result===true) {
        		window.location=url;
        	}
        }
    });
};

$(document).ready(function(){
    $(window).scroll(function () {
           if ($(this).scrollTop() > 50) {
               $('#back-to-top').fadeIn();
           } else {
               $('#back-to-top').fadeOut();
           }
       });
       // scroll body to 0px on click
       $('#back-to-top').click(function () {
           $('#back-to-top').tooltip('hide');
           $('body,html').animate({
               scrollTop: 0
           }, 800);
           return false;
       });
       
       $('#back-to-top').tooltip('show');

});

$(document).ready(function() {
	$("#select_all").click( function() {
		$("input[type='checkbox']").prop('checked', true);
		return false;
	});
	$("#select_none").click( function() {
		$("input[type='checkbox']").prop('checked', false);
		return false;
	});
	$("#select_invert").click( function() {
		$("input[type='checkbox']").each( function() {
			$(this).prop('checked', !$(this).prop('checked'));
		});
		return false;
	});
});