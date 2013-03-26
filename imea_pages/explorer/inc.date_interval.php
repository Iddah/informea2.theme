<div class="clear"></div>

<label>Date interval between</label>
<select name="q_start_year">
    <option value="">Start year</option>
    <?php
    foreach ($search2->ui_compute_years() as $y) {
        $search2->ui_write_option($y, $y, $y == $search2->ui_get_start_year());
    }
    ?>
</select>
and
<select name="q_end_year">
    <option value="">End year</option>
    <?php
    foreach (array_reverse($search2->ui_compute_years()) as $y) {
        $search2->ui_write_option($y, $y, $y == $search2->ui_get_end_year());
    }
    ?>
</select>
<div class="clear"></div>
