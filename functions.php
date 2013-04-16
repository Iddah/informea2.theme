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

function informea2_primary_nav_menu_args($args) {
    $args['show_home'] = FALSE;
    return $args;
}

add_filter('wp_nav_menu_args', 'informea2_primary_nav_menu_args');


function informea2_setup() {
    register_nav_menus(array(
        'primary' => __('Primary Navigation', 'informea'),
    ));
}

add_action('after_setup_theme', 'informea2_setup');


/* Theme customization */
require_once( ABSPATH . WPINC . '/class-wp-customize-setting.php' );
require_once( ABSPATH . WPINC . '/class-wp-customize-section.php' );
require_once( ABSPATH . WPINC . '/class-wp-customize-control.php' );

class Checkbox_Customize_Control extends WP_Customize_Control {
    public $type = 'checkbox';

    public function render_content() {
        $checked = $this->value() == 1 ? ' checked="checked"' : ' ';
?>
        <label>
            <input type="checkbox" <?php $this->link(); ?><?php echo $checked; ?>/>
            <?php echo esc_html( $this->label ); ?>
        </label>
<?php
    }
}

function informea_customize_register( $wp_customize ) {
    //All our sections, settings, and controls will be added here
    $wp_customize->add_setting('show_changelog_in_index',
        array('default' => TRUE, 'type' => 'option', 'transport'   => 'refresh')
    );
    $wp_customize->add_section('informea_index_section' , array('title'      => __('Index page','informea'), 'priority'   => 30));
    $wp_customize->add_control(new Checkbox_Customize_Control($wp_customize, 'show_changelog_in_index', array(
        'label' => __('Show changelog above footer', 'informea'),
        'section' => 'informea_index_section',
        'settings' => 'show_changelog_in_index',
    )));
}
add_action('customize_register', 'informea_customize_register');


function informea2_widgets_init() {
    register_sidebar(array(
        'name' => __('Index page first column', 'informea'),
        'id' => 'index-page-left',
        'description' => __('Index page first column widgets', 'informea'),
        'before_widget' => '<div class="portlet">',
        'after_widget' => '</div>',
        'before_title' => '<div class="pre-title"><div class="title"><span>',
        'after_title' => '</span></div></div>',
    ));
    register_sidebar(array(
        'name' => __('Index page center column', 'informea'),
        'id' => 'index-page-center',
        'description' => __('Index page center column widgets', 'informea'),
    ));
    register_sidebar(array(
        'name' => __('Index page third column', 'informea'),
        'id' => 'index-page-right',
        'description' => __('Index page third column widgets', 'informea'),
        'before_widget' => '<div class="portlet">',
        'after_widget' => '</div>',
        'before_title' => '<div class="pre-title"><div class="title"><span>',
        'after_title' => '</span></div></div>',
    ));
}

add_action('widgets_init', 'informea2_widgets_init');


require_once(dirname(__FILE__) . '/widgets/collapsible-text.php');
require_once(dirname(__FILE__) . '/widgets/latest-news.php');
require_once(dirname(__FILE__) . '/widgets/featured-country.php');
require_once(dirname(__FILE__) . '/widgets/featured-treaty.php');
require_once(dirname(__FILE__) . '/widgets/current-week-meetings.php');
require_once(dirname(__FILE__) . '/widgets/index-mea-explorer.php');
require_once(dirname(__FILE__) . '/widgets/event-explorer.php');
require_once(dirname(__FILE__) . '/widgets/borderless-widget.php');
require_once(dirname(__FILE__) . '/widgets/terms-cloud.php');


