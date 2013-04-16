
function toggleTreaty(id) {
	el = $('#treaty-' + id);
	if ($('.list-item-content-details', el).is(':visible')) {
		$('.list-item-action a img', el).attr({
			'src': images_dir + 'expand.gif',
			'title': 'Expand treaty content',
			'alt': 'Expand'
		});
		$('.list-item-content-details', el).slideUp('fast');
	} else {
		$('.list-item-action a img', el).attr({
			'src': images_dir + 'collapse.gif',
			'title': 'Compress treaty content',
			'alt': 'Compress'
		});
		$('.list-item-content-details', el).slideDown('normal', function () {
			//$.scrollTo( $('#treaty-' + id), 800 );
		});
	}
	return false;
}

function focusToggleInputValue(selector, defaultValue) {
	if ($(selector).val() === defaultValue) {
		$(selector).val('');
	}
}

function blurToggleInputValue(selector, defaultValue) {
	if ($(selector).val() === '') {
		$(selector).val(defaultValue);
	}
}

function oc(a) {
	var o = {};
	for (var i = 0; i < a.length; i++) {
		o[a[i]] = '';
	}
	return o;
}

function validate_feedback_form() {
	if ($.trim($('#feedback_name').val()) == '') {
		alert('Please enter your name');
		return false;
	}
	if ($.trim($('#feedback_email').val()) == '') {
		alert('Please enter your email address');
		return false;
	}
	if ($.trim($('#feedback_message').val()) == '') {
		alert('Please enter your message for us');
		return false;
	}
	if ($.trim($('#recaptcha_response_field').val()) == '') {
		alert('Please enter the spam verification keywords you see in the picture');
		return false;
	}
	return true;
}
