<?php
class EventsExplorerWidget extends WP_Widget {

    function EventsExplorerWidget() {
        $options = array(
            'classname' => 'EventsExplorerWidget',
            'description' => 'Widget to search & filter MEA events',
        );
        $this->WP_Widget('EventsExplorerWidget', 'MEA Events explorer', $options);
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
        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
        ?>
        <div class="portlet">
            <?php if (!empty($title)) : ?>
                <div class="pre-title">
                    <div class="title">
                        <?php echo $title; ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="content event-explorer">
                <form action="/events" method="get">
                    <select name="convention">
                        <option value="">All conventions</option>
                        <?php foreach ($events_ob->get_treaties() as $treaty) : ?>
                            <option value="<?php echo $treaty->id; ?>"><?php echo $treaty->short_title; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label>
                        Date
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
                            <option value="<?php echo $year; ?>"<?php echo $selected; ?>">
                                <?php echo $year; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <div class="margin-top-10">
                        <input type="submit" name="search" value="Search" />
                    </div>
                </form>
            </div>
        </div>
    <?php
    }
}

add_action('widgets_init', create_function('', 'return register_widget("EventsExplorerWidget");'));?>