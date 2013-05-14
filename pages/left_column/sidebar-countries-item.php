<?php
dynamic_sidebar('countries-sidebar-item');
$id = get_request_variable('id');
$country = informea_countries::get_country_for_id($id);

$rUN = new UNDataWebsiteParser($country->id, $country->name);
$un_country_img_url = $rUN->get_map_image();
if ($un_country_img_url != null) {
    echo '<br /><img src="' . $un_country_img_url . '" width="210" height="210" />';
}
$env_data = $rUN->get_environmental_data();
if (!empty($env_data)) {
    echo '<div id="country_un_env_data">' . $env_data . '</div>';
}
