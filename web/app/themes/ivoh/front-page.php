<?php
/*
  Template name: Homepage
*/
use Roots\Sage\Titles;

// Get all post_meta
$post_meta = get_post_meta($post->ID);

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

// Intro Links?
if (!empty(get_post_meta($post->ID, '_cmb2_intro_links', true))) {
  $intro_link = get_post_meta($post->ID, '_cmb2_intro_links', true)[0];
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

<div class="site-wrap">

  <header class="page-header">
    <div class="page-header-top">
      <div class="header-image" <?= \Firebelly\Media\get_header_bg($post) ?>></div>
      <div class="fb-container-md">
        <h2 class="page-title fb-container-content"><?= $intro_title; ?></h2>
      </div>
    </div>
    <div class="page-header-text">
      <?php if (!empty($intro_body)): ?>
        <p class="page-intro-body"><?= $intro_body ?></p>
      <?php endif ?>
      <?php if (!empty($intro_link)): ?>
        <p class="intro-link"><a href="<?= $intro_link['url'] ?>" class="button"><?= $intro_link['link_text'] ?></a></p>
      <?php endif ?>
      <?php if (!empty($photo_caption) || !empty($photo_byline)): ?>
        <div class="banner-text">
          <?php if (!empty($photo_caption)): ?>
            <p class="photo-caption"><?= $photo_caption ?></p>
          <?php endif; ?>
          <?php if (!empty($photo_byline)): ?>
            <p class="photo-byline"><?= $photo_byline ?></p>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </header>

  <div class="page-section fb-container-md">
  	<h2 class="h1 text-center">Featured</h2>
    <div class="stories mobile-gutter patterned">
      <?php echo do_shortcode('[story_carousel type=all]'); ?>
    </div>
  </div>

  <div class="page-section fb-container-md">
  	<h2 class="h3 text-center">News & Commentary</h2>
    <div class="card-grid">
      <div class="masonry sm-halves md-thirds lg-fourths -inner">
        <div class="grid-sizer"></div>
      	<?php // todo: shove any news posts marked featured to top of list (sticky behavior) ?>
      	<?= \Firebelly\Utils\get_posts([
      		'numberposts' => 3,
      	]); ?>
      </div>
    </div>
  </div>

</div>