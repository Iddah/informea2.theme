<?php
$count = 0;
$themes = $search->ui_get_treaties();
$ot = $search->ui_get_other_treaties();
?>
<input class="explorer_all_treaties" type="checkbox" checked="checked"/>
<label for="explorer_all_treaties">
    <?php _e('All conventions, protocols and agreements', 'informea'); ?>
</label>
<div class="explorer_all_treaties_items">
    <?php
    foreach ($themes as $theme => $treaties) {
        $checked = $search->ui_is_checked_treaty('theme-' . $count);
        ?>
        <ul class="main-list">
            <li>
                <input id="<?php echo slugify($theme); ?>" type="checkbox" checked="checked" class="explorer-treaty-click-children"/>
                <label for="<?php echo slugify($theme); ?>"><?php echo $theme; ?></label>
                <ul class="sublist">
                    <?php
                    foreach ($treaties as $id => $data) {
                        $children = $data['children'];
                        $title = $data['title'];
                        $theme = $data['theme'];
                        $checked = $search->ui_is_checked_treaty($id);
                        ?>
                        <li>
                            <input id="explorer_treaty_<?php echo $id; ?>" type="checkbox" name="q_treaty[]" value="<?php echo $id; ?>" checked="checked" class="explorer-treaty-click-children"/>
                            <label for="explorer_treaty_<?php echo $id; ?>">
                                <?php echo $title; ?>
                                <?php if ($theme) { ?>
                                    <span class="theme"><?php echo '(' . $theme . ')'; ?></span>
                                <?php } ?>
                            </label>
                            <?php if (count($children)) { ?>
                                <ul class="sublist">
                                    <?php
                                    foreach ($children as $id => $child) {
                                        $title = $child['title'];
                                        $theme = $child['theme'];
                                        $checked = $search->ui_is_checked_treaty($id);
                                        ?>
                                        <li>
                                            <input id="explorer_treaty_<?php echo $id; ?>" type="checkbox"
                                                   name="q_treaty[]" value="<?php echo $id; ?>" checked="checked"/>
                                            <label for="explorer_treaty_<?php echo $id; ?>"><?php echo $title; ?>
                                                <?php if ($theme) { ?><span
                                                    class="theme"><?php echo '(' . $theme . ')'; ?></span><?php } ?>
                                            </label>
                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                        </li>
                    <?php } ?>
                </ul>
            </li>
            <?php
            if ($count++ % 3 == 0) {
                echo '<div class="clear"></div>';
            }
            ?>
        </ul>
    <?php }  ?>
    <ul class="main-list">
        <li>
            <input id="exp01" type="checkbox" checked="checked" class="explorer-treaty-click-children"/>
            <label for="exp01">Other<span class="theme">(Regional, etc.)</span></label>
            <ul class="sublist">
                <li>
                    <?php
                    foreach ($ot as $id) :
                        ?>
                        <input type="checkbox" id="explorer_treaty_<?php echo $id; ?>" name="q_treaty[]"
                               value="<?php echo $id; ?>" checked="checked" class="hidden"/>
                    <?php endforeach; ?>
                </li>
            </ul>
        </li>
    </ul>
</div>
