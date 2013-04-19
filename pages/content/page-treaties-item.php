<?php
$page_data = new informea_treaties();
$id = get_request_variable('id');
$treaty = $page_data->get_treaty_by_odata_name($id);
