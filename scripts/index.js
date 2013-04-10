jQuery(document).ready(function () {
    $('div.index-explorer a.btn-advanced-search').click(function () {
        $('div.index-explorer ul.advanced').toggle();
    });

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
                $('<div title="' + item.text + '">' + label + '</div>').attr({'class': span_class})
                    .append("<a href='javascript:explorerIndexUIDeselectTerm(" + item.value + ");'><img class='closebutton' src='" + images_dir + "/s.gif' alt='' title='' /></a>")
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
