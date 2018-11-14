<?php get_template_part('templates/page', 'header'); ?>

<div class="fb-container-md padded">
  <div class="card-grid">
    <div class="-inner grid">
      <?= \Firebelly\PostTypes\Person\get_people(['category' => 'advisors', 'order-by' => 'name']); ?>
    </div>
  </div>
</div>