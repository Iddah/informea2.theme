<?php
$id = informea_countries::get_id_from_request();
$rows = informea_countries::get_national_plans($id);
?>
<ul class="country-reports">
    <?php
    foreach ($rows as $treaty) {
        $plans = $treaty->national_plans;
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
