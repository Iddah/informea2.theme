<?php
class CollapsibleTextWidget extends WP_Widget {

    function CollapsibleTextWidget() {
        $options = array(
            'classname' => 'CollapsibleTextWidget',
            'description' => 'Display text default collapsed with expand ribbon',
        );
        $this->WP_Widget('CollapsibleTextWidget', 'Collapsible text', $options);
    }


    function form($instance) {
        $instance = wp_parse_args((array)$instance, array('title' => '', 'content' => ''));
        $title = $instance['title'];
        $content = $instance['content'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <input class="widefat"
                   id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo attribute_escape($title); ?>"
                />
        </p>
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
        $instance['title'] = $new_instance['title'];
        $instance['content'] = $new_instance['content'];
        return $instance;
    }


    function widget($args, $instance) {
        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
        $content = empty($instance['content']) ? ' ' : apply_filters('widget_text', $instance['content']);
        ?>
        <div class="portlet round whatisinformea" id="<?php echo @$args['widget_id']; ?>">
            <div class="title"><?php echo $title; ?></div>
            <div class="content"><?php echo $content; ?></div>
        </div>
        <?php
        echo $after_widget;
    }
}

add_action('widgets_init', create_function('', 'return register_widget("CollapsibleTextWidget");'));?>