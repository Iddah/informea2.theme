<?php
    wp_enqueue_script('index-js', get_bloginfo('template_directory') . '/scripts/index.js');
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