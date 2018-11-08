<?php get_template_part('templates/page', 'header'); ?>

<div class="fb-container-md card-grid">
  <div class="-inner grid">
    <?= \Firebelly\PostTypes\Person\get_people(['category' => 'trustees', 'order-by' => 'name']); ?>
  </div>
</div>
