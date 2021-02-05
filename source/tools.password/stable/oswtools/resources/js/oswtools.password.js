function generatepassword() {
	var symbols = $("input[name='symbol']:checked").val();
	var length = $("select[name='length'] option:selected").val();
	var chars='';
	
	switch (symbols) {
		case '1': {
			chars='0123456789';
			break;
		}
		case '2': {
			chars='0123456789ABCDEF';
			break;
		}
		case '3': {
			chars='abcdefghijklmnopqrstuvwxyz';
			break;
		}
		case '4': {
			chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			break;
		}
		case '5': {
			chars='abcdefghijklmnopqrstuvwxyz0123456789';
			break;
		}
		case '6': {
			chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
			break;
		}
		case '7': {
			chars='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			break;
		}
		case '8': {
			chars='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
			break;
		}
		case '9': {
			chars='ABCDEFGHIJKLMNOPQRSTUWXYZabcdefghijklmnopqrstuvwxyz0123456789\!\"\#\$\%\&\'\(\)\*\+\,\-\.\/\:\;\<\>\=\?\@\[\\\]\^\_\`\{\|\}\~';
			break;
		}
	}
	Math.random();
	Math.random();
	Math.random();
	$('#generatedpassword').html('');
	for (var i=1; i<=length; i++) {
		char=chars.charAt(Math.floor(chars.length*(Math.random()%1)));
		$('#generatedpassword').append(char);
		for (var j=0; j<=Math.floor(100*(Math.random()%1)); j++) {
			Math.random();
		}
	}	
}