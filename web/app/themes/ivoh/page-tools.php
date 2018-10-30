<?php
/*
  Template name: Tools
*/
?>
<?php get_template_part('templates/page', 'header'); ?>

<div class="grid fb-container-md">
  <?= \Firebelly\PostTypes\Tool\get_tools(); ?>
</div>
