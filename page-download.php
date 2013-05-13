<?php
global $wpdb;
$entity = get_request_value('entity');
if ($entity == 'decision_document') {
    $id = get_request_int('id');
    $row = $wpdb->get_row("SELECT url, path FROM ai_document WHERE id = $id");
    $remote_url = $row->url;
    $handle = curl_init($remote_url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($handle, CURLOPT_TIMEOUT, 5);
    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    if ($httpCode != 200) {
        $local_url = get_bloginfo('url') . '/' . $row->path;
        header("Location: $local_url");
    } else {
        header("Location: $remote_url");
    }
    exit();
} else if ('terms_csv' == $entity) {
    $filename = 'informea_vocabulary_' . date('d_M_Y_H_i') . '.csv';
    header('Content-type: text/csv');
    header("Content-disposition: attachment;filename=$filename");
    Thesaurus::generate_download_csv();
    exit();
} else if ('countries_csv' == $entity) {
    $filename = 'informea_countries_' . date('d_M_Y_H_i') . '.csv';
    header('Content-type: text/csv');
    header("Content-disposition: attachment;filename=$filename");
    informea_countries::generate_parties_download_csv();
    exit();
} else if ('vcard' == $entity) {
    $id = get_request_int('id');
    informea_treaties::generate_vcard($id);
} else {
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    get_template_part(404);
    exit();
}