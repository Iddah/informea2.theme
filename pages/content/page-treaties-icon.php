<?php
$page_data = new informea_treaties();
$treaties = $page_data->get_treaties_by_region_by_theme($page_data->region);
?>
<div class="tab-content">
    <?php
    foreach ($treaties as $theme => $treaties) {
        if (empty($theme)) {
            $theme = __('Generic (no theme)', 'informea');
        }
        ?>
        <div style="margin-top: 15px;">
            <h2><?php echo $theme; ?></h2>
            <?php
            foreach ($treaties as $idx => $_treaty) {
                if ($_treaty->id == 9) {
                    $tooltip = __('The Nagoya protocol was adopted at CBD COP 10 and will enter into force 90 days after the ratification by 50 parties. Open Nagoya Protocol', 'informea');
                } else {
                    $tooltip = __('View this treaty', 'informea');
                }
                if ($page_data->region == 'Europe' && $idx % 7 == 0) {
                    echo '<div class="clear"></div>';
                }
                ?>
                <div class="treaty-entry">
                    <div class="treaty-icon">
                        <a class="link" href="<?php imea_url('treaties/' . $_treaty->odata_name); ?>"
                           title="<?php echo $tooltip; ?>"><img src="<?php echo $_treaty->logo_medium; ?>"
                                                                alt="<?php _e('Convention logo', 'informea'); ?>"/></a>
                        <br/>
                        <a class="link" href="<?php imea_url('treaties/' . $_treaty->odata_name); ?>"
                           title="<?php echo $tooltip; ?>">
                            <?php echo $_treaty->short_title_alternative; ?>
                        </a>
                        <?php
                        if (!empty($_treaty->theme_secondary)) {
                            ?>
                            <br/>
                            <span class="text-grey">(<?php echo $_treaty->theme_secondary; ?>)</span>
                        <?php } ?>
                    </div>
                </div>
            <?php
            }
            ?>
            <div class="clear"></div>
        </div>
    <?php
    }
    ?>
</div>
