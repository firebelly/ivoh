<?php get_template_part('templates/page', 'header'); ?>

<?php if (\Firebelly\PostTypes\Person\get_people(['category' => 'team'])): ?>
  <div class="fb-container-md card-grid">
    <div class="-inner grid">
      <?= \Firebelly\PostTypes\Person\get_people(['category' => 'team']); ?>
    </div>
  </div>
<?php endif ?>