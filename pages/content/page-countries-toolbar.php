<?php
$expand = get_request_variable('expand', 'str', 'map');
?>
<div class="toolbar toolbar-countries">
    <?php do_action('informea-countries-toolbar-extra'); ?>
    <form action="" class="pull-right">
        <label for="view-mode"><?php _e('Change view', 'informea'); ?></label>
        <select id="view-mode" name="view-mode" onchange="onChangeViewMode(this);">
            <?php
            foreach(array('World map' => 'map', 'Country MEA membership' => 'parties', 'MEA parties' => 'treaty', 'Grid' => 'grid') as $label => $mode) :
                $selected = ($expand == $mode) ? 'selected="selected "' : '';
                $data_url = sprintf('%s/countries/%s', get_bloginfo('url'), $mode);
                ?>
                <option data-url="<?php echo $data_url; ?>" value="<?php echo $mode; ?>"<?php echo $selected; ?>><?php echo $label; ?></option>
            <?php endforeach; ?>
        </select>
    </form>
    <div class="clear"></div>
</div>