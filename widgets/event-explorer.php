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
                <form action="/events" method="get">
                    <label>
                        Convention
                        <select name="convention">
                            <option value="">All conventions</option>
                            <?php foreach ($events_ob->get_treaties() as $treaty) : ?>
                                <option value="<?php echo $treaty->id; ?>"><?php echo $treaty->short_title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label>
                        Date
                        <br />
                        <select name="month">
                            <option value="">All months</option>
                            <?php foreach (informea_events::get_months_fullname() as $idx => $month) : ?>
                                <option value="<?php echo $idx; ?>"><?php echo $month; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="year">
                            <?php
                            foreach($events_ob->get_years_interval() as $year) :
                                $selected = strftime('%Y') == $year ? ' selected="selected"' : '';
                            ?>
                            <option value="<?php echo $year; ?>"<?php echo $selected; ?>>
                                <?php echo $year; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
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