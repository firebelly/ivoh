<?php
// Partial used for story carousels
$story_author_post = null;
if ($story_author = get_post_meta($story_post->ID, '_cmb2_author', true)) {
  $story_author_post = get_post($story_author);
}
$topics = \Firebelly\Media\get_header_bg($story_post, ['size' => 'medium']);
$story_desc = \Firebelly\Utils\get_excerpt($story_post, $length=25);
?>
<div class="story-content card-content">
  <h1 class="card-title"><?= $story_post->post_title ?></h1>
  <?php if (!empty($story_author_post)): ?>
    <p class="author card-subtitle"><?= $story_author_post->post_title ?></p>
  <?php endif; ?>
  <?php if (!empty($story_desc)): ?>
    <div class="user-content"><?= $story_desc ?></div>
  <?php endif; ?>
  <p class="card-action"><a href="<?= get_permalink($story_post); ?>" class="button">Read</a></p>
</div>