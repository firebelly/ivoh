<?php
// Partial used for story carousels
$story_author_post = null;
if ($story_author = get_post_meta($story_post->ID, '_cmb2_author', true)) {
  $story_author_post = get_post($story_author);
}
$story_types = get_the_terms($story_post, 'story_type');
$story_type = array_pop($story_types);
$story_desc = \Firebelly\Utils\get_excerpt($story_post, $length=25);
?>
<div class="story-content card-content">
  <?php if (!empty($story_type)): ?>
    <p class="story-type card-subtitle"><?= $story_type->name ?></p>
  <?php endif; ?>
  <h1 class="card-title"><a href="<?= get_permalink($story_post); ?>"><?= $story_post->post_title ?></a></h1>
  <?php if (!empty($story_desc)): ?>
    <div class="card-text user-content"><?= $story_desc ?></div>
  <?php endif; ?>
  <p class="card-action"><a href="<?= get_permalink($story_post); ?>" class="button">Read</a></p>
</div>