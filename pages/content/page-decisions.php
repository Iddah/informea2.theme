<?php
$expand = get_request_variable('expand');
debug_wp_request();
get_template_part('pages/content/page', "decisions-$expand");
