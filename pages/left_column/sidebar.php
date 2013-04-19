<?php
global $post;
$post_name = $post->post_name;
$id = get_request_variable('id');
$sidebar_name = $post_name . '-sidebar' . (empty($id) ? '' : '-item');
?>
<div class="clear">&nbsp;</div>
<?php dynamic_sidebar($sidebar_name); ?>