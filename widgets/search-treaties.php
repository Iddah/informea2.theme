<?php

class SearchTreatyTextWidget extends WP_Widget {

    function SearchTreatyTextWidget() {
        $options = array(
            'classname' => 'SearchTreatyTextWidget',
            'description' => 'Full text search inside treaty texts',
        );
        $this->WP_Widget('SearchTreatyTextWidget', 'SEARCH: Treaty text', $options);
    }


    function form($instance) {
        $instance = wp_parse_args((array)$instance, array(
                'label' => '',
                'tab' => '2',
                'use_treaties' => '0',
                'use_decisions' => '0'
            )
        );
        $tab_options = array(
            '1' => 'Timeline',
            '2' => 'Grouped by treaty',
            '3' => 'Only global treaties',
            '5' => 'Only regional treaties',
            '4' => 'Only decisions',
        );
        $label = $instance['label'];
        $tab = $instance['tab'];
        $use_treaties = $instance['use_treaties'];
        $use_decisions = $instance['use_decisions'];
?>
        <p>
            <label for="<?php echo $this->get_field_id('label'); ?>">Label:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('label'); ?>"
                   name="<?php echo $this->get_field_name('label'); ?>" type="text"
                   value="<?php echo esc_attr($label); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tab'); ?>">Label:</label>
            <select id="<?php echo $this->get_field_id('tab'); ?>" name="<?php echo $this->get_field_name('tab'); ?>">
            <?php foreach($tab_options as $id => $label) :
                    $selected = ($id == $tab) ? ' selected="selected"' : '';
            ?>
                <option value="<?php echo $id; ?>"<?php echo $selected; ?>><?php echo $label; ?></option>
            <?php endforeach; ?>
            </select>
        </p>
        <p>
            <input type="checkbox"
                   id="<?php echo $this->get_field_id('use_treaties'); ?>"
                   name="<?php echo $this->get_field_name('use_treaties'); ?>" value="1"
                <?php echo $use_treaties == 1 ? 'checked="checked"' : ''; ?>
                />
            <label for="<?php echo $this->get_field_id('use_treaties'); ?>">Search in treaties</label>
        </p>
        <p>
            <input type="checkbox"
                   id="<?php echo $this->get_field_id('use_decisions'); ?>"
                   name="<?php echo $this->get_field_name('use_decisions'); ?>" value="1"
                <?php echo $use_decisions == 1 ? 'checked="checked"' : ''; ?>
                />
            <label for="<?php echo $this->get_field_id('use_decisions'); ?>">Search in decisions</label>
        </p>
    <?php
    }


    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['label'] = $new_instance['label'];
        $instance['tab'] = $new_instance['tab'];
        $instance['use_treaties'] = $new_instance['use_treaties'];
        $instance['use_decisions'] = $new_instance['use_decisions'];
        return $instance;
    }


    function widget($args, $instance) {
        $label = empty($instance['label']) ? '' : apply_filters('tlabel', $instance['label']);
        $tab = $instance['count_decisions'];
        $use_treaties = $instance['use_treaties'] == 1;
        $use_decisions = $instance['use_decisions'] == 1;

        $search2 = new InformeaSearch2($_GET);
        $ts = $search2->ui_get_treaties_ids();
?>
        <div class="portlet search" id="<?php echo $args['widget_id']; ?>">
            <form action="<?php bloginfo('url'); ?>/search" method="GET">
                <?php if($use_treaties) : ?><input type="hidden" name="q_use_treaties" value="1" /><?php endif; ?>
                <?php if($use_decisions) : ?><input type="hidden" name="q_use_decisions" value="1" /><?php endif; ?>
                <input type="hidden" name="q_tab" value="<?php echo $tab; ?>" />
                <?php foreach ($ts as $t_id) : ?>
                    <input type="hidden" name="q_treaty[]" value="<?php echo $t_id; ?>"/>
                <?php endforeach; ?>
                <label><?php echo $label; ?>
                    <input type="text" size="25" name="q_freetext" class="search-input" placeholder="Type a term or phrase" />
                </label>
                <a class="btn orange pull-right" href="javascript:void(0);" onclick="$('#searchtreatytextwidget-2>form').submit();">
                    <span>Search</span>
                </a>
                <div class="clear"></div>
            </form>
        </div>
<?php
    }
}

add_action('widgets_init', create_function('', 'return register_widget("SearchTreatyTextWidget");'));?>