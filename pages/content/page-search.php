<?php
wp_enqueue_script('events', get_bloginfo('template_directory') . '/scripts/search.js');
$search = InformeaSearch3::get_searcher();
$tab = get_request_int('q_tab', 2);

function informea_search_toolbar() {
    get_template_part('pages/content/page', "search-toolbar");
}
add_action('informea-search-toolbar', 'informea_search_toolbar');

get_template_part('pages/content/page', "search-tab-$tab"); ?>