<?php
wp_enqueue_script('countries', get_bloginfo('template_directory') . '/scripts/countries.js');
$id = get_request_variable('id');
$showall = get_request_boolean('showall', false);
$id_contact = get_request_int('id_contact', null);
$treaties = informea_countries::get_focal_points_by_treaty($id);

?>
<div class="toolbar toolbar-nfp">
    <button id="expand-all"><i class="icon-plus-sign"></i> Expand all</button>
    <button id="collapse-all"><i class="icon-minus-sign"></i> Collapse all</button>
</div>
<div class="alert alert-warning">
    <button class="close" data-dismiss="alert">×</button>
    <strong>Disclaimer: </strong>Please note that the focal points of the remaining conventions will be accessible in due course.
</div>

<ul class="nfp">
<?php
    foreach($treaties as $treaty) :
        $showall_div = $showall ? 'visible' : 'hidden';
        $contacts = $treaty->focal_points;
?>
        <li>
            <h2><?php echo $treaty->short_title; ?> (<?php echo count($contacts); ?>)</h2>
            <div class="content hidden">
                <?php if(count($contacts)) : ?>
                <ul class="contacts">
                    <?php foreach ($contacts as $contact) : ?>
                    <li class="round">
                        <div class="user-icon"></div>
                        <h3>
                            <?php echo $contact->prefix; ?> <?php echo $contact->first_name; ?> <?php echo $contact->last_name; ?>
                            <i>
                                <?php if ($contact->position !== NULL) :
                                    echo '<em>' . $contact->position . '</em><br />';
                                endif; ?>
                            </i>
                        </h3>
                        <div class="clear"></div>
                        <?php if (!empty($contact->email)) : ?>
                            <a class="btn blue small"
                               href="<?php bloginfo('url'); ?>/treaties/<?php echo $treaty->odata_name; ?>/sendmail/<?php echo $contact->id ?>/<?php echo $country->id_country ?>">
                                <i class="icon icon-envelope"></i> <?php _e('e-mail', 'informea'); ?>
                            </a>
                        <?php endif; ?>
                        <a class="btn white small"
                           href="<?php echo bloginfo('url'); ?>/vcard?id_contact=<?php echo $contact->id ?>"
                           title="<?php _e('Download vcard', 'informea'); ?>">
                            <i class="icon icon-user"></i> <?php _e('vCard', 'informea'); ?>
                        </a>
                        <div class="details">
                            <?php if ($contact->department !== NULL) :
                                echo '<em>' . $contact->department . '</em><br />';
                            endif;
                            if ($contact->institution !== NULL) :
                                echo '<em>' . $contact->institution . '</em><br />';
                            endif;
                            ?>
                            <?php if ($contact->address !== NULL) : ?>
                                <div class="nfp-address">
                                    <strong><?php _e('Address:', 'informea'); ?></strong> <?php echo replace_enter_br($contact->address); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($contact->telephone !== NULL) { ?>
                                <div class="nfp-phone">
                                    <strong><?php _e('Phone:', 'informea'); ?></strong> <?php echo $contact->telephone; ?>
                                </div>
                            <?php } ?>

                            <?php if ($contact->fax !== NULL) { ?>
                                <div class="nfp-fax">
                                    <strong><?php _e('Fax:', 'informea'); ?></strong> <?php echo $contact->fax; ?>
                                </div>
                            <?php } ?>
                        </div>
                    </li>
                <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
        </li>
<?php endforeach; ?>
</ul>