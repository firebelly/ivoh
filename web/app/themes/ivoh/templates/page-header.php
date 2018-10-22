<?php
use Roots\Sage\Titles;

// Default vars
$intro_body = $intro_subhead = $intro_body = $photo_caption = $photo_byline = '';

// Get all post_meta
$post_meta = get_post_meta($post->ID);

// Subhead set in Page intro fields?
if (is_404()) {
  $intro_subhead = 'Error 404';
} elseif (!empty($post_meta['_cmb2_intro_subhead'])) {
  $intro_subhead = $post_meta['_cmb2_intro_subhead'][0];
} elseif ($parent_id = wp_get_post_parent_id($post->ID)) {
  // Fallback to parent post title if subhead isn't set in Page Intro fields
  $parent_post = get_post($parent_id);
  $intro_subhead = $parent_post->post_title;
}

// Headline set?
if (!empty($post_meta['_cmb2_intro_headline'])) {
  $intro_title = $post_meta['_cmb2_intro_headline'][0];
} else {
  // Fallback to page title
  $intro_title = Titles\title();
}

// Intro body set?
if (!empty($post_meta['_cmb2_intro_body'])) {
  $intro_body = $post_meta['_cmb2_intro_body'][0];
}

// Try to get photo caption and byline
if (has_post_thumbnail($post)) {
  // Override caption?
  if (!empty($post_meta['_cmb2_photo_caption'])) {
    $photo_caption = $post_meta['_cmb2_photo_caption'][0];
  } else {
    // Fallback to default WP caption
    $photo_caption = get_the_post_thumbnail_caption($post);
  }
  // Byline? (taken from WP image description, which is just the content of the image attachment post)
  $photo_post = get_post(get_post_thumbnail_id($post));
  if (!empty($photo_post->post_content)) {
    $photo_byline = $photo_post->post_content;
  }
}
?>

<header class="page-header">
  <div class="page-header-text">
    <h4 class="breadcrumbs"><?= $intro_subhead ?></h4>
    <h1 class="page-title"><?= $intro_title; ?></h1>
    <?php if (!empty($intro_body)): ?>
      <p class="page-intro-body"><?= $intro_body ?></p>
    <?php endif ?>
  </div>

  <?php if (has_post_thumbnail($post)): ?>
    <div class="page-header-banner bordered patterned">
      <div class="banner-image-container background-blend fb-container-lg">
        <div class="banner-image" <?= \Firebelly\Media\get_header_bg($post) ?>></div>
      </div>
    </div>
    <?php if (!empty($photo_caption)): ?>
      <p class="photo-caption"><?= $photo_caption ?></p>
    <?php endif; ?>
    <?php if (!empty($photo_byline)): ?>
      <p class="photo-byline"><?= $photo_byline ?></p>
    <?php endif; ?>
  <?php endif; ?>
</header>
