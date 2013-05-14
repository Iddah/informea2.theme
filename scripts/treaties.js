var cacheArticleTerms = [];
var cacheParagraphTerms = [];

$(document).ready(function() {
    /* Expand articles (treaty tab) */
    $('ul#articles > li > h3').click(function(e) {
        var p = $(e.target).next('ul.paragraphs');
        $(p).slideToggle({duration: 200});
    });
    // Toolbar (treaty tab)
    $('div.toolbar-treaty button#expand-all').click(function() { $('ul.paragraphs').slideDown({duration: 600}); });
    $('div.toolbar-treaty button#collapse-all').click(function() { $('ul.paragraphs').slideUp({duration: 600}); });
    $('div.toolbar-treaty button#print').click(function() { window.open($(this).data('target')) });
    $('div.toolbar-treaty select#go-to-article').change(function (e) {
        e.preventDefault();
        var target = $('#article_' + $(this).val());
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
        $.scrollTo(target, 300, { offset: target.height() - 30 });
    });

    /* NFP */
    $('ul.nfp>li>h2').click(function() {
        var content = $(this).parent().find('div.content');
        content.slideToggle({duration: 200});
    });
    $('div.toolbar-nfp button#expand-all').click(nfpExpandAll);
    $('div.toolbar-nfp button#collapse-all').click(nfpCollapseAll);
    var ctrl1 = $('input#nfp-filter');
    ctrl1.keyup(function(e) {
        if (e.which == 13) {
            e.preventDefault();
        } else {
            filterNFPList(ctrl1.val());
        }
    });

    var ctrl2 = $('input#party-filter');
    ctrl2.keyup(function(e) {
        if (e.which == 13) {
            e.preventDefault();
        } else {
            filterPartiesList(ctrl2.val());
        }
    });

    // Mouse over the treaty article/paragraph - show terms balloon
    $('ul.articles h3').each(function() {
        $(this).balloon({
            url: ajax_url + '?action=get_article_tags_html&id_article=' + $(this).data('id'),
            position: 'left', css: { opacity: "0.95" }, hideDuration: 20
        });
    });
    $('ul.paragraphs li').each(function() {
        $(this).balloon({
            url: ajax_url + '?action=get_paragraph_tags_html&id_paragraph=' + $(this).data('id'),
            position: 'left', css: { opacity: "0.95" }, hideDuration: 20
        });
    });

    // If we have # in URL, then scroll to the approriate anchor using scollTo
    var hash = document.location.hash;
    if(hash != '') {
        var target = $(hash);
        if(target.length > 0) {
            $(target).addClass('focus');
            $.scrollTo(target, 500, {
                offset: -100,
                onAfter: function () { $('ul.paragraphs', target).slideDown('fast'); }
            });
        }
    }

    // Mouse over decision paragraph (decision index page)
    $('div.decision>ul.paragraphs li').each(function() {
        $(this).balloon({
            url: ajax_url + '?action=get_decision_paragraph_tags_html&id_paragraph=' + $(this).data('id'),
            position: 'left', css: { opacity: "0.95" }, hideDuration: 20
        });
    });

    // Focus contact form first field
    $('#salutation').focus();
});

function nfpCollapseAll() {
    $('ul.nfp>li>div.content').hide();
}

function nfpExpandAll() {
    $('ul.nfp>li>div.content').show();
}


function filterNFPList(filter) {
    $('ul.nfp>li').each(function(idx, item) { $(item).show(); });
    if(filter.length > 0) {
        var _ctrl = $('ul.nfp');
        var regExp = new RegExp(filter, 'i');
        _ctrl.find('a.flag.country').each(function(idx, item) {
            var found = regExp.test($(this).text());
            if(!found) {
                $(item).parent().hide();
            }
        });
    }
}

function filterPartiesList(filter) {
    var rows = $('table#parties tr');
    rows.each(function(idx, item) { $(item).show(); });
    if(filter.length > 0) {
        var regExp = new RegExp(filter, 'i');
        rows.each(function(idx, item) {
            var found = regExp.test($(this).text());
            if(!found) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    }
}

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