<?php
$id = get_request_variable('id');
$mea_membership = informea_countries::get_treaty_membership($id);
?>
<table class="table-hover">
    <thead>
    <tr>
        <th>MEA</th>
        <th>Entry into force</th>
        <th>Status</th>
        <th>Instrument</th>
        <th>Declarations</th>
        <th>Notes</th>
    </tr>
    </thead>
    <?php foreach($mea_membership as $party) : ?>
        <tr>
            <td><a href="<?php echo sprintf('%s/treaties/%s', get_bloginfo('url'), $party->odata_name); ?>"><?php echo $party->short_title; ?></a></td>
            <td class="text-center"><?php echo mysql2date('Y', $party->date); ?></td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
        </tr>
    <?php endforeach; ?>
</table>