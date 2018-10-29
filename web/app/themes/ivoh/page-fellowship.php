<?php
/*
  Template name: Fellowship
*/

// Pull Fellows from this year
$fellows = \Firebelly\PostTypes\Person\get_people(['category' => date('Y').'-fellows']);
?>
<?php get_template_part('templates/page', 'header'); ?>

<div class="page-section fb-container-content">
  <?= apply_filters('the-content', $post->post_content); ?>
</div>

<?php if (!empty($fellows)): ?>
  <div class="page-section fb-container-md card-grid">
    <div class="-inner grid">
      <?= $fellows ?>
    </div>
  </div>
<?php endif ?>
