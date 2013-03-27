
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


function meetings_this_week_paginator() {
	jQuery('div.weeks-meetings').each(function(idx, portlet) {
		var items = jQuery('li', portlet);
		var total = items.length;
		if(total > 0) {
			var current = 0;
			var previous = total;
			var next = jQuery('a.next', portlet);
			var prev = jQuery('a.prev', portlet);
			jQuery(next, portlet).click(function() {
				previous = current;
				current += 1;
				if(current == total) { current = 0; }
				jQuery(items[previous]).hide();
				jQuery(items[current]).removeClass('hidden');
				jQuery(items[current]).show();
				jQuery('span.current', portlet).text(current + 1);
			});

			jQuery(prev, portlet).click(function() {
				previous = current;
				current -= 1;
				if(current < 0) { current = total - 1; }
				jQuery(items[previous]).hide();
				jQuery(items[current]).removeClass('hidden');
				jQuery(items[current]).show();
				jQuery('span.current', portlet).text(current + 1);
			});
		}
	});
}