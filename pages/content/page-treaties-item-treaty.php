<?php
wp_enqueue_script('jquery.scrollTo-1.4.3.1-min', get_bloginfo('template_directory') . '/scripts/jquery.scrollTo-1.4.3.1-min.js');
$page_data = new informea_treaties();
$odata_name = get_request_variable('id');
$treaty = informea_treaties::get_treaty_by_odata_name($odata_name);
$articles = informea_treaties::get_articles($treaty->id);
$all_paragraphs = informea_treaties::get_all_paragraphs($treaty->id);
$print_url = sprintf('%s/treaties/%s/print', get_bloginfo('url'), $odata_name);
?>
<div class="toolbar toolbar-treaty">
    <button id="expand-all"><i class="icon-plus-sign"></i> Expand all</button>
    <button id="collapse-all"><i class="icon-minus-sign"></i> Collapse all</button>

    <?php if (!empty($articles)) : ?>
    <select id="go-to-article" class="toolbar-right">
        <option value="0"><?php _e('-- Go to specific article --', 'informea'); ?></option>
        <?php foreach ($articles as $article) : ?>
        <option value="<?php echo $article->id; ?>"><?php echo $article->official_order . ' ' . $article->title; ?></option>
        <?php endforeach; ?>
    </select>
    <?php endif; ?>
    <button id="print" data-target="<?php echo $print_url; ?>" class="toolbar-right"><i class="icon-print"></i> Print</button>
    <div class="clear"></div>
</div>

<div class="alert alert-info">
    <button class="close" data-dismiss="alert">×</button>
    Click on the article title to expand and see its contents
</div>

<ul id="articles" class="articles">
<?php
    foreach($articles as $article) :
?>
    <li id="article-<?php echo $article->id; ?>">
        <h3 data-id="<?php echo $article->id; ?>">
            <?php echo $article->official_order; ?> <?php echo $article->title; ?>
            <?php if (current_user_can('manage_options')) : ?>
            <div class="management-toolbar article-toolbar">
                <a class="btn white small" href="#">Edit</a>
                <a class="btn info small" href="#">Add new paragraph</a>
                <a class="btn error small" href="#">Delete</a>
            </div>
            <?php endif; ?>
        </h3>
        <?php
            if(!isset($all_paragraphs[$article->id])) :
                $article->content;
            else:
        ?>
            <ul class="paragraphs">
            <?php
                $paragraphs = $all_paragraphs[$article->id];
                $pfirst = $paragraphs[0]; $plast = $paragraphs[count($paragraphs) - 1];
                foreach ($paragraphs as $paragraph) :
                    $para_id = "article-{$article->id}-paragraph_{$paragraph->id}";
                    $content = trim(preg_replace(array('/^<p>/ix', '/<\/p>$/ix'), '', $paragraph->content));
            ?>
                <li id="<?php echo $para_id; ?>" class="ident-<?php echo $paragraph->indent;?>">
                    <?php if (current_user_can('manage_options')) : ?>
                    <?php endif; ?>
                    <div class="management-toolbar paragraph-toolbar">
                        <a class="btn white small" href="#">Edit</a>
                        <a class="btn info small" href="#">Insert paragraph below</a>
                        <a class="btn info small" href="#"><i class="icon-arrow-down"></i></a>
                        <a class="btn info small" href="#"><i class="icon-arrow-up"></i></a>
                        <a class="btn error small" href="#">Delete</a>
                    </div>
                    <?php echo $paragraph->official_order . ' ' . $content; ?>
                </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </li>
<?php endforeach; ?>
</ul>