<?php
class IndexLatestNewsWidget extends WP_Widget {

    function IndexLatestNewsWidget() {
        $options = array(
            'classname' => 'IndexLatestNewsWidget',
            'description' => 'Display latest MEA news retrieved via RSS',
        );
        $this->WP_Widget('IndexLatestNewsWidget', 'InforMEA latest news', $options);
    }


    function form($instance) {
        $instance = wp_parse_args((array)$instance, array('title' => '', 'count' => 3));
        $title = $instance['title'];
        $count = $instance['count'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('count'); ?>">Items to show:</label>
            <input
                   id="<?php echo $this->get_field_id('count'); ?>"
                   name="<?php echo $this->get_field_name('count'); ?>" type="text"
                   value="<?php echo esc_attr($count); ?>"
                />
        </p>
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
        $instance['count'] = $new_instance['count'];
        return $instance;
    }


    function widget($args, $instance) {
        $count = empty($instance['count']) ? 3 : $instance['count'];
        $highlights_ob = new imea_highlights_page();
        $highlights = $highlights_ob->get_index_news(1);
        $highlights = array_slice($highlights, 0, $count);
        if (empty($highlights)) {
            return;
        }
        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
        ?>
        <div class="portlet">
            <?php if(!empty($title)) : ?>
            <div class="title">
                <?php echo $title; ?>
                <a class="rss" target="_blank" href="<?php bloginfo('url'); ?>/highlights/rss"></a>
            </div>
            <?php endif; ?>
            <div class="content">
                <ul class="latest-news">
                    <?php
                    foreach ($highlights as $row) :
                        $permalink = $row->permalink;
                        $target = ' target="_blank"';
                        $is_local = strpos($permalink, get_bloginfo('url'));
                        if (is_int($is_local) && $is_local >= 0) {
                            $target = '';
                        }
                        ?>
                        <li>
                            <a title="Click to read the full story" rel="bookmark" href="<?php echo $row->permalink; ?>"<?php echo $target; ?>>
                                <?php echo subwords($row->title, 10); ?>
                            </a>
                            <br />
                            <?php echo $row->date_formatted; ?>, <?php echo $row->source; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="clear"></div>
        <div class="margin-bottom-10"></div>
        <?php
    }
}
add_action('widgets_init', create_function('', 'return register_widget("IndexLatestNewsWidget");'));?>