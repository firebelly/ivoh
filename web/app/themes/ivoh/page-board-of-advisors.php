<?php get_template_part('templates/page', 'header'); ?>

<div class="fb-container-md card-grid">
  <div class="-inner">
    <?= \Firebelly\PostTypes\Person\get_people(['category' => 'advisors']); ?>
  </div>
</div>
