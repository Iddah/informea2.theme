<div class="clear"></div>

<label class="date-label">
    Date interval between
</label>

<div class="date-selects">
    <div class="start-month">
        <select name="q_start_month">
            <option value="">Month</option>
            <?php
            foreach ($search2->ui_get_months() as $idx => $mon) {
                $search2->ui_write_option($idx, $mon, $idx == $search2->ui_get_start_month());
            }
            ?>
        </select>
        <select name="q_start_year">
            <option value="">Year</option>
            <?php
            foreach ($search2->ui_compute_years() as $y) {
                $search2->ui_write_option($y, $y, $y == $search2->ui_get_start_year());
            }
            ?>
        </select>
    </div>

    <div class="end-month">
        and
        <br/>
        <select name="q_end_month">
            <option value="">Month</option>
            <?php
            foreach ($search2->ui_get_months() as $idx => $mon) {
                $search2->ui_write_option($idx, $mon, $idx == $search2->ui_get_end_month());
            }
            ?>
        </select>
        <select name="q_end_year">
            <option value="">Year</option>
            <?php
            foreach (array_reverse($search2->ui_compute_years()) as $y) {
                $search2->ui_write_option($y, $y, $y == $search2->ui_get_end_year());
            }
            ?>
        </select>
    </div>
</div>
<div class="clear"></div>
