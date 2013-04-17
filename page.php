<?php
wp_deregister_script('jquery');
wp_enqueue_script('jquery-custom', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6.3/jquery.min.js', array(), FALSE, TRUE);
wp_enqueue_script('jquery-ui-custom', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js', array(), FALSE, TRUE);
wp_enqueue_script('informea-common', get_bloginfo('template_directory') . '/scripts/common.js', array(), FALSE, TRUE);
wp_enqueue_script('index-js', get_bloginfo('template_directory') . '/scripts/index.js', array(), FALSE, TRUE);
get_header();
?>
    <div class="col3-left col3">
        <div>
            <?php dynamic_sidebar('index-page-left'); ?>
        </div>
        <div class="clear"></div>
    </div>
    <div class="col3-center col3">
        <?php dynamic_sidebar('index-page-center'); ?>
    </div>
    <div class="col3-right col3">
        <?php dynamic_sidebar('index-page-right'); ?>
    </div>
<?php
get_footer();
?>