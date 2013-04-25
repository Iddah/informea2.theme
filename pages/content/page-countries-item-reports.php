<?php
$id = get_request_variable('id');
$rows = informea_countries::get_national_reports($id);
?>
<ul class="country-reports">
<?php
    foreach ($rows as $treaty) {
        $plans = $treaty->national_reports;
?>
    <li>
        <h2><?php echo $treaty->short_title; ?></h2>
        <div class="content">
            <table class="table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th style="width: 100px;">Submitted</th>
                        <th style="width: 100px;">Document</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($plans as $plan) { ?>
                    <tr>
                        <td><?php echo $plan->title; ?></td>
                        <td><?php echo $plan->submission; ?></td>
                        <td>
                            <?php if(!empty($plan->document_url)) { ?>
                                <a target="_blank" href="<?php echo $plan->document_url; ?>">View</a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </li>
<?php } ?>
</ul>
