<?php
function tengine_header($show_explorer = true, $primary_menu = '', $useful_links_menu = '', $breadcrumbtrail = '',
                        $languages = '', $secondary_menu = '') {
global $post;
?>
<?php if (informea_is_staging()) : ?>
    <div id="informea-staging">
        DEMO VERSION
    </div>
<?php endif; ?>
<div id="informea" class="hfeed">
    <div class="skip-link screen-reader-text">
        <a href="#content" title="<?php _e('Skip to content', 'informea'); ?>"><?php _e('Skip to content', 'informea'); ?></a>
    </div>
    <div id="header">
        <div id="masthead">
            <div id="branding" role="banner">
                <div id="logo">
                    <a href="/"><img src="<?php header_image(); ?>" alt="" title="<?php echo bloginfo('name'); ?>" id="logo-image"/></a>
                </div>
            </div>
            <?php echo (!empty($primary_menu)) ? $primary_menu : '<span id="informea_template_primary_menu"></span>'; ?>
            <?php
            if (TRUE) {
                ?>
                <div id="explorer">
                    <div class="mea-button right">
                        <img src="<?php bloginfo('template_directory'); ?>/images/s.gif" alt="" title="<?php _e('Toggle MEA Explorer box', 'informea'); ?>" />
                    </div>
                    <div class="clear"></div>
                    <?php include(dirname(__FILE__) . '/../imea_pages/explorer/inc.explorer.php'); ?>
                </div>
            <?php
            }
            ?>
            <div class="clear"></div>
        </div><!-- /#masthead -->
    </div><!-- /#header -->
    <div id="main">
        <?php echo (!empty($breadcrumbtrail)) ? $breadcrumbtrail : '<span id="informea_template_breadcrumbtrail"></span>'; ?>
        <?php
        }
        ?>
