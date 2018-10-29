<?php
/*
  Template name: Fellowship
*/
?>
<?php get_template_part('templates/page', 'header'); ?>

<div class="page-section fb-container-content">  
  <?= apply_filters('the-content', $post->post_content); ?>
</div>

<?php if (\Firebelly\PostTypes\Person\get_people(['category' => date('Y').'-fellows'])): ?>
  <div class="page-section fb-container-md card-grid">
    <div class="-inner grid">
      <?= \Firebelly\PostTypes\Person\get_people(['category' => date('Y').'-fellows']); ?>
    </div>
  </div>
<?php endif ?>