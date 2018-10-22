<?php get_template_part('templates/page', 'header'); ?>
<?= \Firebelly\PostTypes\Person\get_people(['category' => 'staff']); ?>
