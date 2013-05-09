<?php
$page_data = new informea_treaties();
$treaty = informea_treaties::get_treaty_from_request();
$parties = informea_treaties::get_parties($treaty->id);
if (count($parties)) :
?>
    <div class="toolbar toolbar-parties">
        <div class="pull-right">
            Search: <input id="party-filter" type="text" name="search" placeholder="in table below ..." />
        </div>
        <div class="clear"></div>
    </div>
    <p>
        <strong>Total number of parties</strong>: <?php echo count($parties); ?>
    </p>
    <table id="parties" class="table-hover">
    <thead>
    <tr>
        <th class="text-left">Member country</th>
        <th>Since</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($parties as $country) { ?>
        <tr>
            <td>
                <a href="<?php echo $country->id; ?>"><?php echo $country->name; ?></a>
            </td>
            <td class="center">
                <?php echo $country->year; ?>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<?php endif;