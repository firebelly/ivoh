<?php
/*
  Template name: Fellowship
*/

// Pull Fellows from this year
$fellows = \Firebelly\PostTypes\Person\get_people(['category' => date('Y').'-fellows', 'order-by' => 'name']);

// Intro Links?
$intro_links = get_post_meta($post->ID, '_cmb2_intro_links', true);
?>
<?php get_template_part('templates/page', 'header'); ?>

<div class="page-section fb-container-content user-content">
  <?= apply_filters('the_content', $post->post_content); ?>
</div>

<div class="page-section fb-container-content text-center">
  <?php \Firebelly\Utils\get_template_part_with_vars('templates/intro', 'links', [ 'intro_links' => $intro_links ]); ?>
</div>

<?php if (!empty($fellows)): ?>
  <div class="page-section fb-container-md">
    <h3 class="h3 text-center"><?= date('Y') ?> Fellows</h3>
    <div class="card-grid">
      <div class="-inner grid">
        <?= $fellows ?>
      </div>
    </div>
  </div>
<?php endif ?>
