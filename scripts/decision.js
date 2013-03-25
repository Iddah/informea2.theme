var decisionParagraphsCache = [];

jQuery(document).ready(function () {
	var paraDiv = jQuery('#' + window.location.hash.replace('#', ''));
	if (paraDiv.length > 0) {
		paraDiv.css('background-color', '#FFE974');
		var scp = paraDiv.position().top - 200;
		jQuery.scrollTo('' + scp + 'px', 100);
	}
	var selector = "#treaties-details-short_title";
	if (jQuery(selector).height() < 30) {
		jQuery(selector).css("marginTop", "12px");
	}
});
function abstract_collapse() {
	jQuery("#abstract_partial").hide();
	jQuery("#abstract_full").show();
}

function decisionOnMouseOverParagraph(id) {
	var el = jQuery('#paragraph-' + id);
	if (typeof(decisionParagraphsCache[id]) != 'undefined' && decisionParagraphsCache[id] != '') {
		showTermsBaloon(el);
		setTermsBalloonContent(decisionParagraphsCache[id]);
	} else if (decisionParagraphsCache[id] == '') {
		// Do nothing - cache hit, but no terms associated
	} else {
		jQuery(el).css('cursor', 'wait');
		jQuery.post(
			ajaxUrl, { action: 'get_decision_paragraph_tags', id_paragraph: id },
			function (response) {
				jQuery(el).css('cursor', 'auto');
				var s = '';
				var l = response.length;
				if (response.length > 0) {
					for (var i = 0; i < l; i++) {
						s += '<a class="link" href="' + blog_dir + '/terms/' + response[i].id + '">' + response[i].term + '</a>';
						if (i < l - 1) {
							s += ', ';
						}
					}
					decisionParagraphsCache[id] = s;
					showTermsBaloon(el);
					setTermsBalloonContent(s);
				} else {
					articlesCache[id] = '';
				}
			}
		);
	}
}

function showTermsBaloon(el) {
	var balloon = $('#terms_tooltip');
	var elOffset = el.offset();
	var left = elOffset.left + el.width();
	var top = elOffset.top - Math.abs(balloon.height() / 2) + Math.abs(el.height() / 2);
	$('#terms_tooltip').css({'left': left + 'px', 'top': top + 'px'});
	$('#terms_tooltip').show();
}

function setTermsBalloonContent(content) {
	$('#terms_tooltip_content').html(content);
	$('#terms_tooltip_content').show();
}

function hideTermsBalloon() {
	$('#terms_tooltip').hide();
}
