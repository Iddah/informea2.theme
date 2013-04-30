$(document).ready(function() {
    $('ul.sidebar h3').click(function() {
        var H3 = this;
        $(this).siblings('div.content').slideToggle(100, function() {
            var icon = $(H3).find('i.icon');
            console.log($(this).is(':visible'));
            if($(this).is(':visible')) {
                icon.removeClass('icon-plus-sign').addClass('icon-minus-sign');
            } else {
                icon.removeClass('icon-minus-sign').addClass('icon-plus-sign');
            }
        })
    });

    $("#q_term_filters").reusableComboBox({
        select: function (evt, ob) {
            var item = ob.item;
            var span_class = 'term-content span-term-' + item.value;
            if (!$('.span-term-' + item.value).length) {
                var label = item.text;
                if (label.length > 20) {
                    label = label.substring(0, 17) + '...';
                }
                if ($('#selected-terms-holder').text().length > 0) {
                    $('#filter_and_or_radiobuttons').show();
                }
                $('<li title="' + item.text + '" onclick="javascript:explorerIndexUIDeselectTerm(' + item.value + ');">' + label + '</li>').attr({'class': span_class})
                    .append('<span class="ui-icon ui-icon-close"></span>')
                    .appendTo($('#selected-terms-holder'));
            }
            return false;
        }
    });

    $('.explorer-treaty-click-children').click(function (e) {
        if ($(e.target).is(':checked')) {
            $(e.target).siblings('ul.sublist').find('[type=checkbox]').attr('checked', 'checked');
        } else {
            $(e.target).siblings('ul.sublist').find('[type=checkbox]').removeAttr('checked');
        }
    });
});

function explorerIndexUIDeselectTerm(id) {
    $('.span-term-' + id).remove();
    $('#q_term_filters option:selected').each(function (idx, el) {
        if ($(el).attr('value') == id) {
            $(el).removeAttr('selected');
        }
    });
}
