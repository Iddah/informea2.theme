<?php
$search = InformeaSearch3::get_searcher();
$tab = get_request_int('q_tab', 2);
$dir = $search->get_sort_direction();

function informea_search_toolbar_tab1() {
    $search = InformeaSearch3::get_searcher();
    $dir = $search->get_sort_direction();
?>
    <form action="" class="pull-left">
        <label for="view-mode"><?php _e('Order', 'informea'); ?></label>
        <select id="view-mode" name="view-mode"
                onchange="var dir = $(this).val(); if(dir == 'desc') { sort_descending(); } else { sort_ascending(); };">
            <?php $selected = ($dir == 'DESC') ? 'selected="selected "' : ''; ?>
            <option <?php echo $selected; ?>value="desc"><?php _e('Newest first', 'informea'); ?></option>
            <?php $selected = ($dir == 'ASC') ? 'selected="selected "' : ''; ?>
            <option <?php echo $selected; ?>value="asc"><?php _e('Oldest first', 'informea'); ?></option>
        </select>
    </form>
<?php
}
add_action('informea-search-toolbar-extra', 'informea_search_toolbar_tab1');
do_action('informea-search-toolbar');

echo $search->render();
?>