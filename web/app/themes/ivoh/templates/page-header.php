<?php
use Roots\Sage\Titles;

// Default vars
$intro_body = $intro_subhead = $intro_body = $photo_caption = $photo_byline = '';

// Get all post_meta (if we can)
if (!empty($post)) {
  $post_meta = get_post_meta($post->ID);
} else {
  $post_meta = [];
}

// Subhead set in Page intro fields?
if (!empty($post_meta['_cmb2_intro_subhead'])) {
  $intro_subhead = $post_meta['_cmb2_intro_subhead'][0];
} elseif (get_post_type($post) == 'story') {
  $story_types = get_the_terms($post, 'story_type');
  $story_type = array_pop($story_types);
  $intro_subhead = $story_type->name;
} elseif ($post->post_parent) {
  // Fallback to parent post title if subhead isn't set in Page Intro fields
  $parent_post = get_post($post->post_parent);
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
  $intro_body = apply_filters('the_content', $post_meta['_cmb2_intro_body'][0]);
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
    <?php if (!empty($authors)): ?>
      <p class="post-byline">By
        <?php
        $author_links = [];
        foreach ($authors as $author_id) {
          $author_post = get_post($author_id);
          $author_links[] = '<a href="' . get_permalink($author_id) . '">' . $author_post->post_title . '</a>';
        }
        echo implode(', ', $author_links);
        ?>
      </p>
    <?php endif ?>
    <?php if (!empty($post_date) || !empty($republished_from) || !empty($post_terms)): ?>
      <div class="post-meta">
        <?php if (!empty($post_date)): ?>
          <span class="post-date"><?= $post_date ?></span>
        <?php endif ?>
        <?php if (!empty($republished_from)): ?>
          <span class="republished-from">Republished from <?= $republished_from ?></span>
        <?php endif ?>

        <?php if (!empty($post_terms)): ?>
          <ul class="post-terms">
            <?php
              foreach ($post_terms as $term):
                echo '<li><a href="'.\Firebelly\Utils\get_term_link($term).'">'.$term->name.'</a></li>';
              endforeach;
            ?>
          </ul>
        <?php endif ?>
      </div>
    <?php endif ?>
    <?php if (!empty($intro_body)): ?>
      <div class="page-intro-body"><?= $intro_body ?></div>
    <?php endif ?>
  </div>

  <?php if (!is_home() && has_post_thumbnail($post)): ?>
    <div class="page-header-banner bordered patterned">
      <div class="banner-image-container background-blend">
        <div class="banner-image" <?= \Firebelly\Media\get_header_bg($post) ?>></div>
      </div>
    </div>
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
  <?php endif; ?>
</header>
