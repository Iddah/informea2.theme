$(document).ready(function () {
    $('.find-nfp-input').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: ajax_url + '?action=nfp_autocomplete',
                dataType: "json",
                data: {
                    maxRows: 10,
                    key: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            label: item.label,
                            value: item.id_country,
                            id_contact: item.id_contact,
                            id_country: item.id_country,
                            id_treaty: item.id_treaty
                        }
                    }));
                }
            });
        },
        delay: 100,
        minLength: 1,
        focus: function (event, ui) {
            return false;
        },
        select: function (ui, data) {
            $('.find-nfp-input').val(data.item.label);
            window.location = blog_dir + '/countries/' + data.item.id_country + '/nfp?showall=true&id_contact=' + data.item.id_contact + '#contact-bookmark-' + data.item.id_treaty;
            return false;
        }
    });

    $('.find-nfp-input').keydown(function (e) {
        return e.keyCode != 13;
    });
});
