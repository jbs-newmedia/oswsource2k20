$(function () {
	// Scroll to top button appear
	$(document).on('scroll', function () {
		var scrollDistance = $(this).scrollTop();
		if (scrollDistance > 100) {
			$('.scroll-to-top').fadeIn();
		} else {
			$('.scroll-to-top').fadeOut();
		}
	});

	// Smooth scrolling using jQuery easing
	$(document).on('click', 'a.scroll-to-top', function (e) {
		var $anchor = $(this);
		$('html, body').stop().animate({
			scrollTop: ($($anchor.attr('href')).offset().top)
		}, 1000, 'easeInOutExpo');
		e.preventDefault();
	});

	$(document).ready(function () {
		$('.datatables').DataTable({
			"iDisplayLength": 50
		});
	});
});

function osWTools_confirmUpdate(message, url_yes, url_no) {
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
			if (result === true) {
				window.location = url_yes;
			} else {
				window.location = url_no;
			}
		}
	});
};

function osWTools_selectAll(seletor) {
	$(seletor + " input[type='checkbox']").prop('checked', true);
	return true;
}

function osWTools_selectNone(seletor) {
	$(seletor + " input[type='checkbox']").prop('checked', false);
	return true;
}

function osWTools_selectInvert(seletor) {
	$(seletor + " input[type='checkbox']").each(function () {
		$(this).prop('checked', !$(this).prop('checked'));
	});
	return true;
}