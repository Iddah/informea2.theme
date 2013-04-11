<?php

class BorderlessWidget extends WP_Widget {

    function BorderlessWidget() {
        $options = array(
            'classname' => 'BorderlessWidget',
            'description' => 'Plain widget with no border, no title',
        );
        $this->WP_Widget('BorderlessWidget', 'Plain, borderless widget', $options);
    }


    function form($instance) {
        $instance = wp_parse_args((array)$instance, array('content' => ''));
        $content = $instance['content'];
?>
        <p>
            <label for="<?php echo $this->get_field_id('content'); ?>">Content:</label>
            <textarea class="widefat"
                      id="<?php echo $this->get_field_id('content'); ?>" name="<?php echo $this->get_field_name('content'); ?>"
                      rows="5" cols="30"><?php echo $content; ?></textarea>
        </p>
    <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['content'] = $new_instance['content'];
        return $instance;
    }


    function widget($args, $instance) {
        $content = empty($instance['content']) ? ' ' : apply_filters('widget_text', $instance['content']);
        ?>
        <div class="borderless-widget" id="<?php echo @$args['widget_id']; ?>">
            <?php echo $content; ?>
        </div>
    <?php
    }
}

add_action('widgets_init', create_function('', 'return register_widget("BorderlessWidget");'));?>