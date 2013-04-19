$(document).ready(function() {
    $('ul#articles > li >  h3').click(function(evt) {
        evt.preventDefault();
        var p = $(evt.target).next('ul.paragraphs');
        $(p).slideToggle({duration: 200});
    });
    // Expand all
    $('button#expand-all').click(function() { $('ul.paragraphs').slideDown({duration: 1000}); });
    $('button#collapse-all').click(function() { $('ul.paragraphs').slideUp({duration: 1000}); });
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