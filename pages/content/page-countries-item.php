<?php
$id = get_request_variable('id');
$expand = get_request_variable('expand', 'str', 'map');
$tabs = array(
    'map' => __('Map &amp; sites', 'informea'),
);

$c = imea_countries_page::count_treaty_membership($id);
if($c > 0) { $tabs['membership'] = __('MEA membership', 'informea'); }

$c = informea_countries::count_focal_points();
if($c > 0) { $tabs['nfp'] = sprintf('%s (%s)', __('Focal points', 'informea'), $c); }

$c = imea_countries_page::count_national_reports($id);
if($c > 0) { $tabs['reports'] = sprintf('%s (%s)', __('National reports', 'informea'), $c); }

$c = imea_countries_page::count_national_plans($id);
if($c > 0) { $tabs['plans'] = sprintf('%s (%s)', __('National plans', 'informea'), $c); }

$tabs['ecolex/legislation'] = __('Legislation', 'informea');
$tabs['ecolex/caselaw'] = __('Case law', 'informea');

if($expand == 'sendmail') { $tabs['sendmail'] = __('Send Mail', 'informea'); }
?>
<div class="tabs">
    <ul>
        <?php
        foreach($tabs as $url => $label) :
            $active = ($expand == $url) ? ' active' : '';
            $tab_url = sprintf('%s%s/%s', get_permalink(), $id, $url);
        ?>
        <li>
            <a class="<?php echo $active; ?>" href="<?php echo $tab_url; ?>"><?php echo $label; ?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php get_template_part('pages/content/page', "countries-item-$expand"); ?>