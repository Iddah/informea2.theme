<?php
$page_data = new informea_treaties();
$odata_name = get_request_variable('id');
$treaty = $page_data->get_treaty_by_odata_name($odata_name);
$contact_data = informea_treaties::get_contacts($treaty->id);
$countries_contacts = $contact_data['countries'];
$all_contacts = $contact_data['contacts'];
?>
<div class="toolbar toolbar-nfp">
    <button id="expand-all"><i class="icon-plus-sign"></i> Expand all</button>
    <button id="collapse-all"><i class="icon-minus-sign"></i> Collapse all</button>
    <div class="pull-right">
        Search: <input id="nfp-filter" type="text" name="search" placeholder="Find country ..." />
    </div>
    <div class="clear"></div>
</div>

<div class="alert alert-info">
    <button class="close" data-dismiss="alert">×</button>
    Click on the country to see the national focal points
</div>

<ul class="nfp">
<?php
    foreach ($countries_contacts as $country) {
        $contacts = (isset($all_contacts[$country->id_country])) ? $all_contacts[$country->id_country] : array();
        $c = count($contacts);
?>
    <li id="treaty-<?php echo $country->id_country; ?>">
        <a name="contact-bookmark-<?php echo $country->id_country; ?>" href="javascript:void(0);" class="flag m28px"><i class="icon icon-chevron-right"></i></a>
        <a href="javascript:void(0);" class="flag country"
            style="background: url(<?php bloginfo('template_directory'); ?>/<?php echo $country->country_flag_medium; ?>) no-repeat;"><?php echo $country->country_name; ?> (<?php echo $c; ?>)</a>
        <div class="content hidden">
            <?php if(count($contacts)) : ?>
            <ul class="contacts">
            <?php foreach ($contacts as $contact) { ?>
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
            <?php } ?>
            </ul>
            <?php endif; ?>
        </div>
        <div class="clear"></div>
    </li>
    <?php } ?>
</ul>