<?php
// Breadcrumbtrail generation
function informea_breadcrumbtrail() {
    global $post;
    $ret = '';
    if ($post !== NULL && $post->post_name != 'index') {
        return '<div id="breadcrumb" class="clear">
            <span class="breadcrumb-name">' . __('You are here:', 'informea') . '</span>
            <a href="' . get_bloginfo('url') . '" title="">' . __('Home', 'informea') . '</a>' . apply_filters('breadcrumbtrail', '') .
            '</div>';
    }
    return $ret;
}

function informea_is_staging() {
    return get_bloginfo('url') == 'http://test.informea.org';
}

function informea2_primary_nav_menu_args( $args ) {
    $args['show_home'] = FALSE;
    return $args;
}
add_filter('wp_nav_menu_args', 'informea2_primary_nav_menu_args');


function informea2_setup() {
    register_nav_menus(array(
        'primary' => __('Primary Navigation', 'twentyten'),
    ));

}

add_action('after_setup_theme', 'informea2_setup');