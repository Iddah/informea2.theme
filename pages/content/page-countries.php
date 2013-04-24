<?php
wp_enqueue_script('countries', get_bloginfo('template_directory') . '/scripts/countries.js');
$expand = get_request_variable('expand', 'str', 'map');
?>
<div class="toolbar text-right">
    <form action="">
        <label for="view-mode"><?php _e('Change view', 'informea'); ?></label>
        <select id="view-mode" name="view-mode" onchange="onChangeViewMode(this);">
        <?php
        foreach(array('Map' => 'map', 'Alphabetical' => 'alphabetical', 'Grid' => 'grid') as $label => $mode) :
            $selected = ($expand == $mode) ? 'selected="selected "' : '';
            $data_url = sprintf('%s/countries/%s', get_bloginfo('url'), $mode);
        ?>
            <option data-url="<?php echo $data_url; ?>" value="<?php echo $mode; ?>"<?php echo $selected; ?>><?php echo $label; ?></option>
        <?php endforeach; ?>
        </select>
    </form>
</div>
<?php get_template_part('pages/content/page', "countries-$expand");