<?php
$page_data = new informea_treaties();
$treaties = $page_data->get_treaties_by_region_by_theme($page_data->region);
?>
<table class="datatable treaty-table">
    <thead>
    <tr>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th><?php _e('Year', 'informea'); ?></th>
        <th><?php _e('Depository', 'informea'); ?></th>
        <th><?php _e('Links', 'informea'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($treaties as $theme => $treaties) {
        if (empty($theme)) {
            $theme = __('Generic (no theme)', 'informea');
        }
        ?>
        <tr>
            <td colspan="5">
                <h2 style="margin-top: 15px;"><?php echo $theme; ?></h2>
            </td>
        </tr>
        <?php
        foreach ($treaties as $_treaty) {
            if ($_treaty->id == 9) {
                $tooltip = __('The Nagoya protocol was adopted at CBD COP 10 and will enter into force 90 days after the ratification by 50 parties. Open Nagoya Protocol', 'informea');
            } else {
                $tooltip = __('Open', 'informea') . ' ' . $_treaty->short_title;
            }
            ?>
            <tr>
                <td class="logo">
                    <a href="<?php imea_url('treaties/' . $_treaty->odata_name); ?>"><img
                            src="<?php echo $_treaty->logo_medium; ?>"
                            alt="<?php _e('Convention logo', 'informea'); ?>" title="Click to see details"/></a>
                </td>
                <td class="middle">
                    <a href="<?php imea_url('treaties/' . $_treaty->odata_name); ?>"
                       title="Click to see details"><?php echo $_treaty->short_title; ?></a>
                </td>
                <td class="middlecenter">
                    <?php echo (@date('Y', strtotime($_treaty->start)) > 0) ? @date('Y', strtotime($_treaty->start)) : '-'; ?>
                </td>
                <td class="middle">
                    <?php echo $_treaty->depository; ?>
                </td>
                <td class="middlecenter">
                    <a href="<?php echo parse_url($_treaty->url, PHP_URL_SCHEME) . "://" . parse_url($_treaty->url, PHP_URL_HOST); ?>"
                       title="<?php _e('Visit convention website. This will open in a new window', 'informea'); ?>"
                       target="_blank"><img
                            src="<?php bloginfo('template_directory'); ?>/images/globe.png"/></a>
                    <a href="<?php echo $_treaty->url; ?>"
                       title="<?php _e('Read treaty text on the Convention website. This will open in a new window', 'informea'); ?>"
                       target="_blank"><img
                            src="<?php bloginfo('template_directory'); ?>/images/small-treaty.png"/></a>
                </td>
            </tr>
        <?php
        }
    }
    ?>
    </tbody>
</table>