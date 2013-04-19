<?php
wp_enqueue_script('jquery-custom', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6.3/jquery.min.js', array(), FALSE, TRUE);
wp_enqueue_script('jquery-ui-custom', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js', array(), FALSE, TRUE);
wp_enqueue_script('informea-common', get_bloginfo('template_directory') . '/scripts/common.js', array(), FALSE, TRUE);
get_header();
if (have_posts()) : while (have_posts()) : the_post();
    global $post;
    $post_name = $post->post_name;
    $id = get_request_variable('id');
    $specific_template = $post_name . (empty($id) ? '' : '-item');
?>
    <div id="page-title">
        <?php get_template_part("pages/titles/title", $post_name); ?>
    </div>
    <div class="col2-left col2">
        <div>
            <?php get_template_part("pages/left_column/sidebar", $specific_template); ?>
        </div>
        <div class="clear"></div>
    </div>
    <div class="col2-center col2">
    <?php
        get_template_part("pages/content/page", $specific_template);
    ?>
    </div>
<?php
endwhile; endif;
get_footer();
