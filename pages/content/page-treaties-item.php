<?php
$page_data = new informea_treaties();
$expand = get_request_variable('expand', 'str', 'treaty');
$odata_name = get_request_variable('id');
$treaty = $page_data->get_treaty_by_odata_name($odata_name);

$decisions_count = $page_data->get_decisions_count($treaty->id);

$contact_data = informea_treaties::get_contacts($treaty->id);
$countries_contacts = $contact_data['countries'];
$all_contacts = $contact_data['contacts'];
?>
<div class="tabs">
    <ul>
        <li>
            <a class="<?php echo ($expand == 'treaty') ? ' active' : ''; ?>"
               href="<?php echo get_permalink() . '/' . $treaty->odata_name . '/treaty'; ?>"><?php _e('Treaty text', 'informea'); ?></a>
        </li>
        <?php if ($decisions_count > 0) : ?>
            <li>
                <a class="<?php echo ($expand == 'decisions' || $expand == 'decision') ? 'active' : ''; ?>"
                   href="<?php echo get_permalink() . '/' . $treaty->odata_name . '/decisions'; ?>"><?php _e('Decisions', 'informea'); ?>
                    (<?php echo $decisions_count; ?>)</a>
            </li>
        <?php endif; ?>
        <?php if (count($all_contacts)) : ?>
        <li>
            <a class="<?php echo ($expand == 'nfp') ? 'active' : ''; ?>"
               href="<?php echo get_permalink() . '/' . $treaty->odata_name . '/nfp'; ?>"><?php _e('Focal Points', 'informea'); ?>
                (<?php echo count($all_contacts); ?>)</a>
        </li>
        <?php endif; ?>
        <?php if (informea_treaties::has_coverage($id)) { ?>
            <li>
                <a class="<?php echo ($expand == 'coverage') ? 'active' : ''; ?>"
                   href="<?php echo get_permalink() . '/' . $treaty->odata_name . '/coverage'; ?>"><?php _e('Map and Membership', 'informea'); ?></a>
            </li>
        <?php } ?>
        <?php if ($expand == 'sendmail') { ?>
            <li>
                <a class="<?php echo ($expand == 'sendmail') ? 'active' : ''; ?>"
                   href=""><?php _e('Send Mail', 'informea'); ?> </a>
            </li>
        <?php } ?>
    </ul>
</div>
<?php get_template_part('pages/content/page', "treaties-item-$expand"); ?>