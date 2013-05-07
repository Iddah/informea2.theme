<?php
wp_enqueue_script('events', get_bloginfo('template_directory') . '/scripts/search.js');
$search = InformeaSearch3::get_searcher();
$tab = get_request_int('q_tab', 2);

function informea_search_toolbar() {
    get_template_part('pages/content/page', "search-toolbar");
}
add_action('informea-search-toolbar', 'informea_search_toolbar');

$search = InformeaSearch3::get_searcher();
$tab = get_request_int('q_tab', 2);
$dir = $search->get_sort_direction();

function informea_search_toolbar_tab() {
    $search = InformeaSearch3::get_searcher();
    $dir = $search->get_sort_direction();
    ?>
    <form action="" class="pull-left">
        <label for="sort-order"><?php _e('Order', 'informea'); ?></label>
        <select id="sort-order" name="sort-order">
            <?php $selected = ($dir == 'DESC') ? 'selected="selected "' : ''; ?>
            <option <?php echo $selected; ?>value="desc"><?php _e('Newest first', 'informea'); ?></option>
            <?php $selected = ($dir == 'ASC') ? 'selected="selected "' : ''; ?>
            <option <?php echo $selected; ?>value="asc"><?php _e('Oldest first', 'informea'); ?></option>
        </select>
    </form>
<?php
}
add_action('informea-search-toolbar-extra', 'informea_search_toolbar_tab');
do_action('informea-search-toolbar');

echo $search->render();
?>