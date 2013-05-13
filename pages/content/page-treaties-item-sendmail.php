<?php
$page_data = new informea_treaties();
$contact = $page_data->get_contact_for_id($id_contact);

?>

<form name="contact-form" action="">
    <input type="hidden" id="contact_id" value="<?php echo $id_contact ?>"/>
    <div>
        <div class="contact-name">

        </div>
        <?php if ($contact->position !== NULL) { ?>
            <em><?php echo $contact->position; ?></em>
            <br/>
        <?php } ?>
        <?php if ($contact->department !== NULL) { ?>
            <em><?php echo $contact->department; ?></em>
            <br/>
        <?php } ?>
        <?php if ($contact->institution !== NULL) { ?>
            <?php echo $contact->institution; ?>
            <br/>
        <?php } ?>
        <?php if ($contact->address !== NULL) { ?>
            <div class="contact-address">
                <strong><?php _e('Address:', 'informea'); ?></strong> <?php echo replace_enter_br($contact->address); ?>
            </div>
        <?php } ?>

        <?php if ($contact->telephone !== NULL) { ?>
            <div>
                <strong><?php _e('Phone:', 'informea'); ?></strong> <?php echo $contact->telephone; ?>
            </div>
        <?php } ?>

        <?php if ($contact->fax !== NULL) { ?>
            <div>
                <strong><?php _e('Fax:', 'informea'); ?></strong> <?php echo $contact->fax; ?>
            </div>
        <?php } ?>
    </div>