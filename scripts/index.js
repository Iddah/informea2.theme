$(document).ready(function() {
    /* Click on the index page 'What is InforMEA' expand ribbon */
    $('div.whatisinformea a.ribbon-click').click(function() {
        $('div.whatisinformea div.content').slideToggle({easing: 'linear', duration:200});
    })

    /* Click on 'Advanced search' link on MEA Explorer in front page */
    $('div.index-explorer a.index-explorer-advanced-search-click').click(function () {
        $('div.index-explorer ul.advanced').slideToggle({easing: 'linear', duration:200});
    });

    featuredCountryPortletSetup();

    /* Set-up paginator for the upcoming meetings portlet */
    upcoming_meetings_paginator();

    $("#q_term_index").reusableComboBox({
        select: function (evt, ob) {
            var item = ob.item;
            var span_class = 'term-content span-term-' + item.value;
            if (!$('.span-term-' + item.value).length) {
                var label = item.text;
                if (label.length > 20) {
                    label = label.substring(0, 17) + '...';
                }
                if ($('#index-search-terms').text().length > 0) {
                    $('#index_and_or_radiobuttons').show();
                }
                $('<li title="' + item.text + '" onclick="javascript:explorerIndexUIDeselectTerm(' + item.value + ');">' + label + '</li>').attr({'class': span_class})
                    .append('<span class="ui-icon ui-icon-close"></span>')
                    .appendTo($('#index-search-terms'));
            }
            return false;
        }
    });

    $('#use_biodiversity_label').click(function () {
        var treaties = new Array(1 /* CBD */, 8 /* Cartagena */, 9 /* Nagoya */, 3 /* CITES */, 4 /* CMS */,
            10 /* AEWA */, 14/* ITPGRFA */, 18 /* Ramsar */, 16 /* WHC */);
        var check = $('#use_biodiversity').is(':checked');
        $.each(treaties, function (index, item) {
            $('#q_treaty_index_' + item).attr('checked', check);
        });
    });
    $('#use_chemicals_label').click(function () {
        var treaties = new Array(2 /* Basel */, 20 /* Rotterdam */, 5 /* Stockholm */);
        var check = $('#use_chemicals').is(':checked');
        $.each(treaties, function (index, item) {
            $('#q_treaty_index_' + item).attr('checked', check);
        });
    });
    $('#use_climate_label').click(function () {
        var treaties = new Array(15 /* UNFCCC */, 17 /* Kyoto Protocol */, 19 /* UNCCD */, 6 /* Vienna */, 7 /* Montreal */);
        var check = $('#use_climate').is(':checked');
        $.each(treaties, function (index, item) {
            $('#q_treaty_index_' + item).attr('checked', check);
        });
    });
});


function explorerIndexUIDeselectTerm(id) {
    $('.span-term-' + id).remove();
    $('#q_term_index option:selected').each(function (idx, el) {
        if ($(el).attr('value') == id) {
            $(el).removeAttr('selected');
        }
    });
}


function featuredCountryPortletSetup() {
    $('div.portlet.featured-country div#tabs').tabs();
    featuredCountryPortletInitMap();
}

function featuredCountryShowMap(latlng, zoom) {
    var myOptions = { zoom: zoom, center: latlng, mapTypeId: google.maps.MapTypeId.ROADMAP, streetViewControl: false };
    fc_map = new google.maps.Map(document.getElementById("fc-map-canvas"), myOptions);
}

function featuredCountryShowSites(id_country) {
    $.ajax({
        url: ajax_url + '?action=country_sites_markers',
        dataType: "json",
        data: { id: id_country },
        success: function (data) {
            $(data.whc).each(function(idx, cfg) {
                cfg.position = new google.maps.LatLng(cfg.latitude, cfg.longitude);
                cfg.map = fc_map;
                var marker = new google.maps.Marker(cfg);
                google.maps.event.addListener(marker, 'click', function () {
                    var txt = '<a href="' + cfg.url + '" target="_blank">' + cfg.title + '</a>';
                    infoWindow.setContent(txt);
                    infoWindow.open(fc_map, marker);
                });
            });
            $(data.ramsar).each(function(idx, cfg) {
                cfg.position = new google.maps.LatLng(cfg.latitude, cfg.longitude);
                cfg.map = fc_map;
                var marker = new google.maps.Marker(cfg);
                if(cfg.url) {
                    google.maps.event.addListener(marker, 'click', function () {
                        var txt = '<a href="javascript:void(0);" onclick="featuredCountryOpenRamsarSite(' + cfg.id + ');" target="_blank">' + cfg.title + '</a>';
                        txt += '<p class="text-grey"><strong>Tip:</strong> Clicking, you will leave InforMEA.<br />Press Back to return here</p>';
                        infoWindow.setContent(txt);
                        infoWindow.open(fc_map, marker);
                    });
                }
            });
        }
    });
}

function featuredCountryOpenRamsarSite(id) {
    var form = document.createElement('form');
    form.setAttribute('method', 'post');
    form.setAttribute('action', 'http://www.wetlands.org/reports/output.cfm');

    var site_id = document.createElement('input');
    site_id.setAttribute('type', 'hidden');
    site_id.setAttribute('name', 'site_id');
    site_id.setAttribute('value', id);
    form.appendChild(site_id);

    var button = document.createElement('input');
    button.setAttribute('type', 'hidden');
    button.setAttribute('name', 'RepAll');
    button.setAttribute('value', '1');
    form.appendChild(button);

    document.body.appendChild(form);
    form.submit();
}


function upcoming_meetings_paginator() {
    jQuery('div.upcoming-events ul').each(function(idx, portlet) {
        var items = jQuery('li', portlet);
        var total = items.length;
        if(total > 0) {
            var current = 0;
            var previous = total;
            var next = jQuery('div.upcoming-events a.next');
            var prev = jQuery('div.upcoming-events a.prev');
            jQuery(next, portlet).click(function() {
                previous = current;
                current += 1;
                if(current == total) { current = 0; }
                jQuery(items[previous]).hide();
                jQuery(items[current]).removeClass('hidden');
                jQuery(items[current]).show();
                jQuery('div.upcoming-events span.current').text(current + 1);
            });

            jQuery(prev, portlet).click(function() {
                previous = current;
                current -= 1;
                if(current < 0) { current = total - 1; }
                jQuery(items[previous]).hide();
                jQuery(items[current]).removeClass('hidden');
                jQuery(items[current]).show();
                jQuery('div.upcoming-events span.current').text(current + 1);
            });
        }
    });
}