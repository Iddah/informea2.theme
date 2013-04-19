$(document).ready(function() {
    $('ul#articles > li > h3').click(function(evt) {
        evt.preventDefault();
        var p = $(evt.target).next('ul.paragraphs');
        $(p).slideToggle({duration: 200});
    });
    $('ul#articles > li > h3').mouseover(function() { $('div.article-toolbar', this).show(); });
    $('ul#articles > li > h3').mouseout(function() { $('div.article-toolbar', this).hide(); });

    $('ul.paragraphs > li').mouseover(function() { $('div.paragraph-toolbar', this).show(); });
    $('ul.paragraphs > li').mouseout(function() { $('div.paragraph-toolbar', this).hide(); });

    // Expand all
    $('button#expand-all').click(function() { $('ul.paragraphs').slideDown({duration: 1000}); });
    $('button#collapse-all').click(function() { $('ul.paragraphs').slideUp({duration: 1000}); });
    $('button#print').click(function() { window.open($(this).data('target')) });
    $('select#go-to-article').change(function (evt) {
        evt.preventDefault();
        var target = $('#article-' + $(this).val());
        $.scrollTo(target, 500, {
            offset: target.height() * -1.5,
            onAfter: function () { $('ul.paragraphs', target).slideDown('fast'); }
        });
    });
});

function onChangeTreaty() {
    var opt = $('#change-treaty-id>option:selected');
    if(opt.index() !== 0) {
        window.location = blog_dir + '/treaties/' + opt.attr('value');
    }
}

function onClickAbstractMore() {
    $('#abstract_partial').hide();
    $('#abstract_full').show();
}