$(document).ready(function () {
	$('#q_freetext').focus();
	$.ajax({
		url: ajax_url + '?action=show_survey',
		dataType: "json",
		data: { op: 'check' },
		success: function (data) {
			if (data.show) {
				var cookie = getCookie('informea_survey');
				if (cookie != '0') {
					show_survey();
				}
			}
		}
	});

	$('.tooltip, .location-link').tipsy({gravity: 'w'});
	$('.tooltip.alphabet-order-link').tipsy({gravity: 's'});

	/* Global code for collapsible list of items. Usage pattern:
	 * <li class="list-item">
	 *    ...
	 *    <div class="list-item-title">
	 *       <a class="list-item-title-click">clickable title</a>
	 *    </div>
	 *    ...
	 *    <div class="list-item-content">...</div>
	 * </li>
	 */
	$.each($('ul.list-dropdown li'), function () {
		var listItem = $(this);
		var clickLink = $(this).find('.list-item-title-click');
		clickLink.click(function (e) {
			e.preventDefault();
			// Expand or collapse the accompanying content and update the arrow
			var contentDiv = listItem.find('.list-item-content');
			if (contentDiv.is(':visible')) {
				contentDiv.slideUp('normal');
				clickLink.removeClass('opened');
				clickLink.addClass('closed');
			} else {
				contentDiv.slideDown('normal');
				clickLink.removeClass('closed');
				clickLink.addClass('opened');
			}
		});
	});

	$.each($('.collapsible li'), function () {
		var listItem = $(this);
		var clickLink = $(this).find('.title a');
		clickLink.click(function (e) {
			e.preventDefault();
			// Expand or collapse the accompanying content and update the arrow
			var contentDiv = listItem.find('.content');
			if (contentDiv.is(':visible')) {
				contentDiv.slideUp('normal');
				clickLink.removeClass('opened');
				clickLink.addClass('closed');
			} else {
				contentDiv.slideDown('normal');
				clickLink.removeClass('closed');
				clickLink.addClass('opened');
			}
		});
	});


	/**
	 *  Alphabetically view
	 */
	$('.by-letter').click(function (e) {
		e.preventDefault();
		letter = $(this).attr('class').split(' ')[0].split('-')[1];
		$('.alphabetically-view').fadeOut('fast');
		$('.alphabetically-' + letter).fadeIn('fast');
		return false;
	});

	$('.all-letters').click(function (e) {
		e.preventDefault();
		$('.alphabetically-view').fadeIn('fast');
		return false;
	});

	/**
	 * Add classes for buttons hiperlinks
	 */
	$('a.button').bind({
		mousedown: function (e) {
			e.stopPropagation();
			jQuery(this).addClass('mousedown');
		},
		blur: function (e) {
			e.stopPropagation();
			jQuery(this).removeClass('mousedown');
		},
		mouseup: function (e) {
			e.stopPropagation();
			jQuery(this).removeClass('mousedown');
		}
	});

	$('.up-button, .down-button').click(function (e) {
		e.preventDefault();
		id = $(this).attr('class').split('move-')[1];
		direction = $(this).attr('class').split('-button')[0].split(' ')[1];
		$('#direction-' + id).val('' + direction + '');
		$('#move-paragraph-' + id).submit();
	});

	/**
	 * Treaties functions
	 */

	$('.toggle-treaty').click(function (e) {
		e.preventDefault();
		toggleTreaty($(this).attr('id').split('-')[2]);
	});

	$('.list-item-top-details').click(function (e) {
		toggleTreaty($('.toggle-treaty', $(this)).attr('id').split('-')[2]);
	});

	$('.list-item-top-details a').click(function (e) {
		e.stopPropagation();
	});

	$('ul.treaty-articles li').each(function () {
		var details = $('div.article-paragraph', this);
		// If URL contains anchor (hash), to highlight certain paragraph/article - do not hide the paragraphs
		if (window.location.hash == '') {
			details.hide();
		}
		var headline = $('h3', this).click(function (evt) {
			//evt.preventDefault();
			details.slideToggle('fast');
		}).css('cursor', 'pointer');
	});

	/**
	 * List view Expand/Collapse all buttons
	 */
	$('a.expand-button').click(function (e) {
		e.preventDefault();
		if (( $('ul.list-dropdown').is(':visible') ) && ( !$(this).hasClass('disabled') )) {
			$('.list-item-content').slideDown('fast');
			$('.list-item-title-click').removeClass('closed');
			$('.list-item-title-click').addClass('opened');

		}
		$('a.compress-button').removeClass('disabled');
		$(this).addClass('disabled');
	});

	$('a.compress-button').click(function (e) {
		e.preventDefault();
		if (( $('ul.list-dropdown').is(':visible') ) && ( !$(this).hasClass('disabled') )) {
			$('.list-item-content').slideUp('fast');
			$('.list-item-title-click').removeClass('opened');
			$('.list-item-title-click').addClass('closed');
			$('a.expand-button').removeClass('disabled');
			$(this).addClass('disabled');
		}
	});

	$('a.articles-expand-button').click(function (e) {
		e.preventDefault();
		if (!$(this).hasClass('disabled')) {
			$('.article-paragraph').slideDown('fast');
			$('a.articles-compress-button').removeClass('disabled');
			$(this).addClass('disabled');
		}
	});

	$('a.articles-compress-button').click(function (e) {
		e.preventDefault();
		if (!$(this).hasClass('disabled')) {
			$('.article-paragraph').slideUp('fast');
			$('a.articles-expand-button').removeClass('disabled');
			$(this).addClass('disabled');
		}
	});

	$('.box-search-form-input').focus(function (e) {
		e.preventDefault();
		focusToggleInputValue($('.box-search-form-input'), 'Search');
		return false;
	});

	$('.box-search-form-input').blur(function (e) {
		e.preventDefault();
		blurToggleInputValue($('.box-search-form-input'), 'Search');
		return false;
	});
});

/**
 * Toggle div with +/-
 */
function toggle_section(base_class) {
	var item_content = $('.' + base_class + ' .content');
	var icon = $('.' + base_class + ' .title .toggle-icon');
	if (!item_content.is(':visible')) {
		item_content.slideDown();
		icon.attr('src', images_dir + 'search-box/minus.png');
	} else {
		item_content.slideUp();
		icon.attr('src', images_dir + 'search-box/plus.png');
	}
}


/**
 * Global variables ajax_url - from template
 * @param textinput_id - html control to attach to
 * @param attached_form_id - html form where control is positioned
 * @param submit_on_enter - if true, form is submitted when enter is pressed inside focused control
 */
function register_autocomplete_terms(textinput_id, attached_form_id, submit_on_enter) {
	var ctrl = $('#' + textinput_id);
	ctrl.autocomplete({
		source: function (request, response) {
			// Break-down the terms to enable autocomplete for comma separated terms
			var terms = request.term.split(',');
			if (terms.length > 0) {
				terms = terms[terms.length - 1]; // Last term use for autocomplete
			}
			$.ajax({
				url: ajax_url + '?action=suggest_terms',
				dataType: "json",
				data: { maxRows: 10, key: terms },
				success: function (data) {
					response($.map(data, function (item) {
						return { label: item.term, value: item.id }
					}));
				}
			});
		},
		minLength: 10, delay: 100, minLength: 1,
		focus: function (event, ui) {
			return false;
		},
		select: function (ui, data) {
			var terms = ctrl.val().split(',');
			var content = '';
			for (var i = 0; i < terms.length - 1; i++) {
				content += terms[i] + ',';
			}
			content += '"' + data.item.label + '",';
			ctrl.val(content);
			return false;
		}
	});
	if (submit_on_enter == true) {
		ctrl.keydown(function (e) {
			if (e.keyCode == 13) {
				$('#' + attached_form_id).submit();
			}
		});
	}
}

function delete_paragraph(id) {
	if (confirm('Delete cannot be undone. Are you sure?')) {
		$('#delete-paragraph-' + id).submit();
	}
}

function delete_article(id) {
	if (confirm('Delete cannot be undone. Are you sure?')) {
		$('#delete-article-' + id).submit();
	}
}

function show_survey() {
	var iframe = $('<iframe style="width: 705px; height: 420px; border: 0; margin: 0; padding: 0" />');
	iframe.attr('src', 'http://www.surveymonkey.com/s.aspx?sm=86KKzRmxBIZl78pDdpTwzA%3d%3d');
	$("#dialog_survey").append(iframe).dialog({
		width: 705, minWidth: 705, maxWidth: 705,
		height: 500, minHeight: 500, maxHeight: 500,
		modal: true, draggable: false, closeOnEscape: true,
		top: 'top', resizable: false,
		dialogClass: 'titlessDialog',
		buttons: [
			{
				text: 'Later', click: function () {
				setCookie('informea_survey', '0', 5);
				$(this).dialog('close');
			}
			},
			{
				text: 'Never show', click: function () {
				setCookie('informea_survey', '0', 365);
				$(this).dialog('close');
			}
			}
		]
	});
}

function getCookie(c_name) {
	var i, x, y, ARRcookies = document.cookie.split(";");
	for (i = 0; i < ARRcookies.length; i++) {
		x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
		y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
		x = x.replace(/^\s+|\s+$/g, "");
		if (x == c_name) {
			return unescape(y);
		}
	}
	return '';
}

function setCookie(c_name, value, exdays) {
	var exdate = new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
	document.cookie = c_name + "=" + c_value;
}