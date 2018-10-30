<?php
/*
  Template name: Fellowship
*/

// Pull Fellows from this year
$fellows = \Firebelly\PostTypes\Person\get_people(['category' => date('Y').'-fellows']);

// Intro Links?
$intro_links = get_post_meta($post->ID, '_cmb2_intro_links', true);
?>
<?php get_template_part('templates/page', 'header'); ?>

<?php \Firebelly\Utils\get_template_part_with_vars('templates/intro', 'links', [ 'intro_links' => $intro_links ]); ?>

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
