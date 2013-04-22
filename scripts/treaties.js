$(document).ready(function() {
    /* Expand articles (treaty tab) */
    $('ul#articles > li > h3').click(function() {
        e.preventDefault();
        var p = $(e.target).next('ul.paragraphs');
        $(p).slideToggle({duration: 200});
    });
    // Toolbar (treaty tab)
    $('div.toolbar-treaty button#expand-all').click(function() { $('ul.paragraphs').slideDown({duration: 600}); });
    $('div.toolbar-treaty button#collapse-all').click(function() { $('ul.paragraphs').slideUp({duration: 600}); });
    $('div.toolbar-treaty button#print').click(function() { window.open($(this).data('target')) });
    $('div.toolbar-treaty select#go-to-article').change(function (e) {
        e.preventDefault();
        var target = $('#article-' + $(this).val());
        $.scrollTo(target, 500, {
            offset: target.height() * -1.5,
            onAfter: function () { $('ul.paragraphs', target).slideDown('fast'); }
        });
    });

    /* Expand decisions (decision tab) */
    $('ul.decisions>li>h2').click(function(e) {
        var p = $(e.target).next('div.content');
        $(p).slideToggle({duration: 200})
    });
    /* Toolbar (decisions tab) */
    $('div.toolbar-decisions button#expand-all').click(function() { $('ul.decisions div.content').slideDown({duration: 600}); });
    $('div.toolbar-decisions button#collapse-all').click(function() { $('ul.decisions div.content').slideUp({duration: 600}); });
    $('div.toolbar-decisions button#scroll-resolutions').click(function(e) {
        e.preventDefault();
        var target = $('a[name="' + $(this).data('target') + '"]');
        console.log(target);
        $.scrollTo(target, 300, { offset: target.height() - 30 });
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