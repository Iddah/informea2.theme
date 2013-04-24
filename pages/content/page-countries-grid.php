<?php
do_action('informea-countries-toolbar');
$data = informea_countries::index_grid();
$columns = $data['column'];
$signatures = $data['signatures'];
$countries = $data['countries'];
?>
<div class="table-scroll-wrapper">
<div class="table-scroll">
<table id="table-grid">
    <thead>
    <tr>
        <th>&nbsp;</th>
        <th><?php _e('Country', 'informea') ?></th>
        <?php foreach ($columns as $column) { ?>
            <th><?php echo $column->short_title; ?></th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($countries as $count => $country) : ?>
        <tr>
            <td>
                <a href="<?php echo get_permalink() . '/' . $country->id ?>">
                    <img src="<?php bloginfo('template_directory'); ?>/<?php echo $country->icon_medium; ?>" alt="" />
                </a>
            </td>
            <td>
                <a href="<?php echo get_permalink() . '/' . $country->id ?>">
                    <?php echo $country->name; ?>
                </a>
            </td>
            <?php
            foreach ($columns as $column) :
                $id_treaty = $column->id;
                $id_country = $country->id;
                $coldata = '-';
                if (isset($signatures[$id_treaty])) {
                    $tmparr = $signatures[$id_treaty];
                    if (isset($tmparr[$id_country])) {
                        $coldata = $tmparr[$id_country];
                    }
                }
                ?>
                <td><?php echo $coldata; ?></td>
            <?php endforeach; ?>
            </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
</div>