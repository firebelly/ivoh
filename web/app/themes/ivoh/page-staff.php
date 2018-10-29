<?php get_template_part('templates/page', 'header'); ?>

<?php if (\Firebelly\PostTypes\Person\get_people(['category' => 'staff'])): ?>
  <div class="fb-container-md card-grid">
    <div class="-inner grid">
      <?= \Firebelly\PostTypes\Person\get_people(['category' => 'staff']); ?>
    </div>
  </div>
<?php endif ?>