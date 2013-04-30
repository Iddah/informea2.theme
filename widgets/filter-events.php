<?php

$featured_country = NULL;

class FilterEventsWidget extends WP_Widget {

    function FilterEventsWidget() {
        $options = array(
            'classname' => 'FilterEventsWidget',
            'description' => 'Filter MEA events using Ajax and show the results',
        );
        $this->WP_Widget('FilterEventsWidget', 'Filter events', $options);
    }


    function form($instance) {
        $instance = wp_parse_args((array)$instance, array('title' => ''));
        $title = $instance['title'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <input class="widefat"
                   id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"
                />
        </p>
    <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        return $instance;
    }

    function widget($args, $instance) {
        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
        $orgs = informea_events::get_treaties();
        $id_treaty = get_request_int('fe_treaty');
        $years = informea_events::get_years_interval();
        $fe_year = get_request_int('fe_year');
        if($fe_year == 0) {
            $fe_year = strftime('%Y');
        } // Show only current year
        $fe_type = get_request_value('fe_type');
        $show_past = get_request_int('fe_show_past');
?>
        <li class="widget filter-event">
            <?php if (!empty($title)) : ?>
                <h2>
                    <?php echo $title; ?>
                </h2>
                <div class="clear"></div>
            <?php endif; ?>
            <div class="content">
                <form action="">
                    <div class="field">
                        <label for="fe_treaty">MEA</label>
                        <select id="fe_treaty" name="fe_treaty" class="select-box">
                            <option value=""><?php _e('-- All conventions --', 'informea'); ?></option>
                        <?php foreach ($orgs as $org) :
                            $selected = $id_treaty == $org->id ? ' selected="selected"' : '';
                        ?>
                            <option value="<?php echo $org->id; ?>"<?php echo $selected; ?>><?php echo $org->short_title; ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="field">
                        <label for="fe_type">Event type</label>
                        <select id="fe_type" name="fe_type">
                        <?php foreach(informea_events::get_event_types() as $value => $label) :
                                $selected = ($value == $fe_type) ? ' selected="selected"' : '';
                        ?>
                            <option value="<?php echo $value; ?>"<?php echo $selected;?>><?php echo $label; ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="field">
                        <label for="fe_year">Year</label>
                        <select id="fe_year" name="fe_year">
                            <option value="-1">-- <?php _e('All years', 'informea'); ?> --</option>
                        <?php
                            foreach ($years as $year) :
                                $sel = ($year == $fe_year) ? ' selected' : '';
                                echo "<option value='$year'$sel>$year</option>";
                            endforeach;
                        ?>
                        </select>
                    </div>

                    <div class="field-inline">
                        <?php $checked = $show_past == 1 ? ' checked="checked"' : ' '; ?>
                        <input id="fe_show_past" type="checkbox" name="fe_show_past" value="1"<?php echo $checked; ?>/>
                        <label for="fe_show_past">Show past events</label>
                    </div>
                    <p>
                        <a href="<?php echo get_permalink(); ?>" class="pull-left">Reset</a>
                        <input type="submit" value="Filter" class="pull-right" />
                    </p>
                </form>
            </div>
        </li>
<?php
    }
}
add_action('widgets_init', create_function('', 'return register_widget("FilterEventsWidget");'));
