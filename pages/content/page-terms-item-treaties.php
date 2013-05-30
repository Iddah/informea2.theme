<?php
    $id = get_request_variable('id', 1);
    $page_data = new Thesaurus($id, array('id_ns' => 1, 'id_concept' => 1, 'feature' => 'str', 'mode' => 'str'));
    $term = $page_data->term;
    // Pre-cook the search - add parameters
    $request = array();
    $request['q_term'] = array($term->id);
    $page_treaties = new imea_treaties_page(); // Treaties
    $ts = $page_treaties->get_treaties();
    $request['q_treaty'] = array();
    foreach ($ts as $t) {
        $request['q_treaty'][] = $t->id;
    }
    $request['q_tab'] = 3; // Results like 3rd tab of adv search
    $request['q_use_treaties'] = 1;

    $search2 = new InformeaSearch2($request);
    $results = $search2->search();
?>
<div class="search-results">
<ol>
<?php
    foreach ($results->get_results() as $idx => $result) {
        $title = $result->get_title(2);
        $description = $result->get_description(2);
        $content = $result->get_content(2);
        $icon = $result->get_icon(1);
        $url = $result->get_item_url();
        ?>
        <li>
            <h2><?php echo $title; ?></h2>
            <p>
                <?php echo $description; ?>
            </p>
            <?php if (!empty($content)) { echo $content; } ?>
        </li>
    <?php } ?>
</ol>
</div>
