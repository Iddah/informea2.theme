<?php
// Template engine functions
function tengine_head() {
    $imea_options = get_option('informea_options');
    ?>
    <?php
    if (@$imea_options['css_optimizer']) {
        ?>
        <link rel="stylesheet" type="text/css" media="screen"
              href="<?php bloginfo('template_directory'); ?>/style_min.css"/>
    <?php
    } else {
        ?>
        <link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('template_directory'); ?>/style.css"/>
        <link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('template_directory'); ?>/styles/tipsy.css"/>
        <link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('template_directory'); ?>/styles/bubble.css"/>
        <link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('template_directory'); ?>/styles/ui.css"/>
        <link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('template_directory'); ?>/styles/bubbletip.css"/>
        <link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('template_directory'); ?>/styles/boxy.css"/>
        <link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('template_directory'); ?>/styles/feedback.css"/>
        <link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('template_directory'); ?>/styles/slider.css"/>
<?php
    }
?>
    <!--[if IE]>
    <link href="<?php bloginfo('template_directory'); ?>/bubbletip-IE.css" rel="stylesheet" type="text/css" media="screen" />
    <![endif]-->
    <!--[if IE 7]>
    <link href="<?php bloginfo('template_directory'); ?>/fix-IE7.css" rel="stylesheet" type="text/css" media="screen" />
    <![endif]-->
    <!--[if gte IE 8]>
    <link href="<?php bloginfo('template_directory'); ?>/fix-IE8.css" rel="stylesheet" type="text/css" media="screen" />
    <![endif]-->
    <?php
    // Execute css_inject to give pages the chance to add CSS code in html header of the page
    do_action('css_inject');
    ?>
    <link rel="stylesheet" type="text/css" media="print" href="<?php bloginfo('template_directory'); ?>/styles/print.css" />
<?php
}
