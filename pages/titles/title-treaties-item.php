<?php
$page_data = new informea_treaties();
$id = get_request_variable('id');
$treaty = $page_data->get_treaty_by_odata_name($id);
?>
<div id="page-title">
    <img src="<?php echo $treaty->logo_medium; ?>" alt="<?php _e('Convention logo', 'informea'); ?>" />
    <h1><?php echo $treaty->short_title; ?></h1>
    <p><?php echo $treaty->long_title; ?></p>
</div>