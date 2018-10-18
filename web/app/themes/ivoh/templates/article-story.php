<?php
$story_author_post = null;
if ($story_author = get_post_meta($story_post->ID, '_cmb2_author', true)) {
  $story_author_post = get_post($story_author);
}
$story_image = \Firebelly\Media\get_header_bg($story_post, ['size' => 'medium']);
$topics = \Firebelly\Media\get_header_bg($story_post, ['size' => 'medium']);
$story_desc = \Firebelly\Utils\get_excerpt($story_post, $length=25);
?>
<article class="story <?= $story_post->column_width ?>"><div class="wrap">
  <?= \Firebelly\Utils\admin_edit_link($story_post) ?>
  <?php if ($story_image): ?>
    <div class="image" <?= $story_image ?>></div>
  <?php endif; ?>
  <h1 class="h3"><?= $story_post->post_title ?></h1>
  <?php if (!empty($story_author_post)): ?>
    <p class="author"><?= $story_author_post->post_title ?></p>
  <?php endif; ?>
  <?php if (!empty($story_desc)): ?>
    <div class="user-content"><?= $story_desc ?></div>
  <?php endif; ?>
</div></article>
