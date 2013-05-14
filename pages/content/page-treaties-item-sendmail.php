<?php
$errors = array();
$success = FALSE;
$page_data = new informea_treaties();
$contact = $page_data->get_contact_for_id($id_contact);
$errors = array();
$options = get_option('informea_options');
$public_key = $options['recaptcha_public'];

$salutation = get_request_value('salutation');
$first_name = get_request_value('first_name');
$last_name = get_request_value('last_name');
$email = get_request_value('email');
$message = get_request_value('message');
$copy = get_request_boolean('copy');
$recaptcha_error = null;

$send = get_request_boolean('send');
if($send) {
    $errors = informea_treaties::send_message_to_nfp_validate();
    if(empty($errors)) {
        $errors = informea_treaties::send_message_to_nfp();
    }
    $success = empty($errors);
}

?>

<h1>
    Contact
    <?php echo $contact->prefix; ?> <?php echo $contact->first_name; ?> <?php echo $contact->last_name; ?>
</h1>

<div>
    <?php if ($contact->position !== NULL) { ?>
        <p>Position: <em><?php echo $contact->position; ?></em></p>
    <?php } ?>
    <?php if ($contact->institution !== NULL) { ?>
        <p>Institution: <?php echo $contact->institution; ?></p>
    <?php } ?>
    <?php if ($contact->department !== NULL) { ?>
        <p>Department: <em><?php echo $contact->department; ?></em></p>
    <?php } ?>
    <?php if ($contact->address !== NULL) { ?>
        <p>Address: <?php echo replace_enter_br($contact->address); ?></p>
    <?php } ?>
    <?php if ($contact->telephone !== NULL) { ?>
        <p>Phone: <?php echo $contact->telephone; ?></p>
    <?php } ?>
    <?php if ($contact->fax !== NULL) { ?>
        <p>Fax: <?php echo $contact->fax; ?></p>
    <?php } ?>
</div>

<?php if($send && !empty($errors)): ?>
<div class="alert alert-error">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <ul>
    <?php foreach($errors as $error): ?>
        <li><?php echo $error; ?></li>
    <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<?php if($send && $success): ?>
<div class="alert alert-success">
    The message has been successfully sent!
    <?php if($copy): ?>
    You should receive also a copy of the message
    <?php endif; ?>
</div>
<?php endif; ?>

<?php if(!($send && $success)): ?>
<form id="contact-form" action="" method="POST">
    <input type="hidden" name="id_contact" value="<?php echo $id_contact ?>" />

    <div class="control">
        <label for="salutation">
            <?php _e('Salutation', 'informea'); ?>
        </label>
        <input id="salutation" name="salutation" type="text" tabindex="2" value="<?php echo $salutation; ?>" />
    </div>

    <div class="control">
        <label for="first_name">
            <?php _e('Your first name', 'informea'); ?> <span class="gray">(required)</span>
        </label>
        <input id="first_name" name="first_name" type="text" tabindex="3" value="<?php echo $first_name; ?>" />
    </div>

    <div class="control">
        <label for="last_name">
            <?php _e('Your last name', 'informea'); ?> <span class="gray">(required)</span>
        </label>
        <input id="last_name" name="last_name" type="text" tabindex="4" value="<?php echo $last_name; ?>" />
    </div>

    <div class="control">
        <label for="email">
            <?php _e('Your e-mail address', 'informea'); ?> <span class="gray">(required)</span>
        </label>
        <input id="email" name="email" type="text" tabindex="5" value="<?php echo $email; ?>" />
    </div>

    <div class="control">
        <label for="message">
            <?php _e('Enter your message', 'informea'); ?> <span class="gray">(required)</span>
        </label>
        <textarea id="message" name="message" rows="10" cols="50" tabindex="6"><?php echo $message; ?></textarea>
    </div>

    <div class="control">
        <input id="copy" name="copy" type="checkbox" tabindex="7"<?php echo $copy ? ' checked="checked"' : ''; ?> />
        <label for="copy">
            Send me a copy of the email
        </label>
    </div>

    <?php echo recaptcha_get_html($public_key, $recaptcha_error); ?>

    <div class="control">
        <input type="submit" name="send" value="<?php _e('Send email message', 'informea'); ?>" tabindex="8" />
    </div>
</form>
<?php endif; ?>