<?php
$dictionary = Thesaurus::index_alphabetical();
$letters = Thesaurus::get_alphabet_letters();
$definitions = get_request_int('definitions');

function informea_terms_toolbar_list() {
    $definitions = get_request_int('definitions') == 1 ;
    $checked = $definitions? ' checked="checked"' : '';
?>
    <form action="<?php echo get_permalink(); ?>/list/" id="show-definitions-toolbar-form">
        <label>
            <input type="checkbox" name="definitions" value="<?php echo $definitions ? '0' : '1'; ?>" <?php echo $checked; ?>
                   onclick="$('#show-definitions-toolbar-form').submit();" />
            Show terms definitions
        </label>
    </form>
<?php
}
add_action('informea-terms-toolbar-extra', 'informea_terms_toolbar_list');
do_action('informea-terms-toolbar');
?>
<ul class="terms">
<?php foreach ($letters as $letter): ?>
    <li>
        <h2><?php echo $letter->letter; ?></h2>
        <table class="content table-hover">
            <?php $terms = $dictionary[$letter->letter]; ?>
            <?php foreach ($terms as $term): ?>
            <tr>
                <td class="text-top" style="width: 200px;">
                    <a href="<?php echo get_permalink() . $term->id; ?>"><?php echo $term->term;?></a>
                </td>
                <?php if($definitions == 1) : ?>
                <td class="text-top">
                    <?php echo $term->description; ?>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </table>
    </li>
<?php endforeach; ?>
</ul>