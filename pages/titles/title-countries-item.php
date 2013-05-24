<?php
$id = informea_countries::get_id_from_request();
$country = informea_countries::get_country_for_id($id);
?>
<div id="page-title">
    <img src="<?php bloginfo('template_directory'); ?>/<?php echo $country->icon_medium; ?>" alt="" />
    <h1><?php echo $country->name; ?></h1>
    <p><?php echo $country->long_name; ?></p>
</div>