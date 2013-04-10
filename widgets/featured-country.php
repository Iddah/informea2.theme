<?php

$featured_country = NULL;

class FeaturedCountryWidget extends WP_Widget {

    function FeaturedCountryWidget() {
        $options = array(
            'classname' => 'FeaturedCountryWidget',
            'description' => 'Rotate one of the countries, daily',
        );
        $this->WP_Widget('FeaturedCountryWidget', 'Featured Country', $options);
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
        global $featured_country;
        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
        $featured_country = informea_countries::get_featured_country();
        if ($featured_country) {
?>
            <div class="portlet">
                <?php if (!empty($title)) : ?>
                    <div class="title">
                        <?php echo $title; ?>:
                        <a href="<?php echo sprintf('%s/countries/%s', get_bloginfo('url'), $featured_country->id); ?>">
                            <?php echo $featured_country->name; ?>
                        </a>
                    </div>
                <?php endif; ?>
                <div class="content featured-country">
                    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
                    <div id="index_map_canvas" style="width: 482px; height: 350px;"></div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
            <div class="margin-bottom-10"></div>
        <?php
        }
    }
}

add_action('widgets_init', create_function('', 'return register_widget("FeaturedCountryWidget");'));

function portlet_featured_country_inject_js() {
?>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            var mapOptions = {
                center: new google.maps.LatLng(-34.397, 150.644),
                zoom: 8,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            var map = new google.maps.Map(document.getElementById("index_map_canvas"), mapOptions);
        });
    </script>
<?php
}
add_action('js_inject', 'portlet_featured_country_inject_js');