function engine(link, direct, element, type) {
	$.ajax({
		method: "POST",
		url: link,
		dataType: 'json',
		data: {
			"action": 'settings',
			"doaction": 'doignore',
			"element": element,
			"type": type
		}
	})
		.done(function (data) {
			if (data.status == true) {
				window.location = direct;
			}
		});
}