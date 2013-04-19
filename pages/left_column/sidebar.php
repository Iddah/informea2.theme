<?php
global $post;
$id = get_request_variable('id');
$sidebar_name = $post->post_name . '-sidebar' . (empty($id) ? '' : '-item');
dynamic_sidebar($sidebar_name);
