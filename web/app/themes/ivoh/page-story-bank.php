<?php
/*
  Template name: Story Bank
*/
$stories = \Firebelly\PostTypes\Story\get_stories();
?>
<?php get_template_part('templates/page', 'header'); ?>

<?php
if (!empty($stories)):
  echo $stories;
endif;