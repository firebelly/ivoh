<?php use Roots\Sage\Titles; ?>

<header class="page-header">
  <div class="page-header-text">
    <?php
    $page_meta = get_post_meta($post->ID);

    // Subhead set in Page intro fields?
    if (!empty($page_meta['_cmb2_intro_subhead'])) {
      $intro_subhead = $page_meta['_cmb2_intro_subhead'][0];
    } elseif ($parent_id = wp_get_post_parent_id($post->ID)) {
      // Fallback to parent post title if subhead isn't set in Page Intro fields
      $parent_post = get_post($parent_id);
      $intro_subhead = $parent_post->post_title;
    } else {
      $intro_subhead = '';
    }

    // Headline set?
    if (!empty($page_meta['_cmb2_intro_headline'])) {
      $intro_title = $page_meta['_cmb2_intro_headline'][0];
    } else {
      // Fallback to page title
      $intro_title = Titles\title();
    }

    // Intro body set?
    if (!empty($page_meta['_cmb2_intro_body'])) {
      $intro_body = $page_meta['_cmb2_intro_body'][0];
    } else {
      $intro_body = '';
    }
    ?>
    <h4><?= $intro_subhead ?></h4>
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
  <?php endif; ?>
</header>
