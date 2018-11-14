<?php
/*
  Template name: Tools
*/
?>
<?php get_template_part('templates/page', 'header'); ?>

<?php if (!empty(trim($post->post_content))): ?>
<div class="post-content user-content fb-container-content">
  <?= apply_filters('the_content', $post->post_content) ?>
</div>
<?php endif; ?>

<div class="grid fb-container-md padded mobile-gutter patterned-sm">
  <?= \Firebelly\PostTypes\Tool\get_tools(); ?>
</div>
