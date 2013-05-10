<?php
class EventsExplorerWidget extends WP_Widget {

    function EventsExplorerWidget() {
        $options = array(
            'classname' => 'EventsExplorerWidget',
            'description' => 'Widget to search & filter MEA events',
        );
        $this->WP_Widget('EventsExplorerWidget', 'InforMEA events explorer', $options);
    }


    function form($instance) {
        $instance = wp_parse_args((array)$instance, array('title' => '', 'count' => 3));
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
        $events_ob = new informea_events();
?>
        <li class="widget event-explorer">
            <div class="content">
                <form action="/events/" method="get">
                    <div class="field">
                        <label for="fe_treaty">Convention</label>
                        <select id="fe_treaty" name="fe_treaty">
                            <option value="">-- All conventions --</option>
                            <?php foreach ($events_ob->get_treaties() as $treaty) : ?>
                                <option value="<?php echo $treaty->id; ?>"><?php echo $treaty->short_title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="field">
                        <label for="fe_type">Event type</label>
                        <select id="fe_type" name="fe_type">
                            <?php foreach(informea_events::get_event_types() as $value => $label) :
                                ?>
                                <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="field">
                        <label for="fe_year">Date</label>
                        <select name="fe_year">
                            <option value="-1">-- All years --</option>
                            <?php
                            foreach($events_ob->get_years_interval() as $year) :
                                $selected = strftime('%Y') == $year ? ' selected="selected"' : '';
                            ?>
                            <option value="<?php echo $year; ?>"<?php echo $selected; ?>>
                                <?php echo $year; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <p>
                        <input type="submit" name="search" value="Search" class="btn orange pull-right" />
                    </p>
                </form>
            </div>
        </li>
    <?php
    }
}

add_action('widgets_init', create_function('', 'return register_widget("EventsExplorerWidget");'));?>