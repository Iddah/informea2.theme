<?php
    get_header();
?>
    <div id="container">
        <div id="content" role="main">
            <div class="col3-left col3"><!-- col1 -->
                <div>
                    <?php dynamic_sidebar('index-page-col1'); ?>
                </div>
                <div class="portlet">
                    <div class="title">
                        TITLE
                    </div>
                    <div class="content">
                        CONTENT
                    </div>
                </div>
                <div class="clear"></div>
            </div><!--/ col1 -->
        </div>
        <!-- #content -->
    </div><!-- #container -->
<?php
    get_footer();
?>