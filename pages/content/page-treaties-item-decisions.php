<?php
$odata_name = get_request_variable('id');
$treaty = informea_treaties::get_treaty_by_odata_name($odata_name);
$showall = get_request_variable('showall', 'str');
if ($treaty->odata_name == 'cites') {
    get_template_part('pages/content/page', 'treaties-item-decisions-cites');
    return;
}
$page_data = new informea_treaties();
?>
<div class="toolbar toolbar-decisions">
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
    <div class="clear"></div>
</div>

<div class="alert alert-info">
    <button class="close" data-dismiss="alert">×</button>
    Click on the meeting abbreviation to see the corresponding decisions
</div>

<?php  if ($treaty->id == '17' || $treaty->id == '19')  : // Kyoto, UNCCD ?>
<div class="alert alert-warning">
    <button class="close" data-dismiss="alert">×</button>
    <strong>Disclaimer:</strong> Please note that the decisions from UNCCD and UNFCCC and the Kyoto
    Protocol, other than those listed here, will be accessible in the course of 2013
</div>
<?php endif; ?>

<ul class="decisions">
<?php
    $d = $page_data->group_decisions_by_meeting($treaty->id);
    $meetings = $d['decisions'];
    $meetings_obs = $d['meetings'];
    $showall_title = ($showall !== NULL) ? 'opened' : 'closed';
    $showall_content = ($showall !== NULL) ? '' : ' hidden';
    foreach ($meetings as $meeting_id => &$decisions) {
        $meeting = $meetings_obs[$meeting_id];
        $meeting_title = $page_data->tab_decisions_meeting_title($meeting);
        $meeting_summary = $page_data->decisions_meeting_summary($meeting);
?>
    <li>
        <h2><?php echo $meeting_title; ?></h2>
        <div class="content hidden">
            <?php echo $meeting_summary; ?>
            <table class="table-hover">
            <thead>
                <tr>
                    <th class="no" style="width: 5%"><?php _e('No.', 'informea'); ?></th>
                    <th class="title"><?php _e('Title', 'informea'); ?></th>
                    <th><?php _e('Type/Status', 'informea'); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php
                $page_data->sort_decisions($decisions);
                foreach ($decisions as $decision) :
                    $tags = $page_data->get_decision_tags($decision->id);
            ?>
                <tr>
                    <td class="text-top">
                        <?php echo $decision->number; ?>
                    </td>
                    <td class="text-top">
                        <h3>
                            <?php echo $page_data->page_decisions_overview_decision_link($decision, $treaty); ?>
                        </h3>
                        <?php if (count($tags)) : ?>
                        Related terms
                        <ul class="terms round">
                            <?php
                                $last = end($tags);
                                foreach ($tags as $tag) :
                            ?>
                                <li><a href="<?php bloginfo('url'); ?>/terms/<?php echo $tag->id; ?>"><?php echo $tag->term; ?></a><?php if ($last !== $tag) { echo ','; } ?></li>
                            <?php
                                endforeach;
                            ?>
                        </ul>
                        <?php  endif; ?>
                        <div class="management-toolbar pull-left">
                            <?php if (current_user_can('manage_options')) { ?>
                                <a target="_blank" class="btn white small"
                                   href="<?php echo admin_url(); ?>admin.php?page=informea_decisions&act=decision_edit&id_decision=<?php echo $decision->id; ?>&id_treaty=<?php echo $decision->id_treaty; ?>">Edit</a>
                                <a target="_blank" class="btn white small"
                                   href="<?php echo admin_url(); ?>admin.php?page=informea_decisions&act=decision_edit_decision&id_treaty=<?php echo $decision->id_treaty; ?>&id_decision=<?php echo $decision->id; ?>">Break in paragraphs</a>
                            <?php } ?>
                        </div>
                    </td>
                    <td>
                        <?php echo $decision->type; ?>
                        (<?php $status = decode_decision_status($decision->status); echo $status; ?>)
                    </td>
                </tr>
        <?php
            endforeach;
        ?>
            </tbody>
        </table>
    </li>
    <?php
    }
    ?>
</ul>