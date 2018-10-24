<?php
$feature_title = get_post_meta($feature_post->ID, '_cmb2_feature_title', true);
$feature_subhead = get_post_meta($feature_post->ID, '_cmb2_feature_subhead', true);
$feature_body = apply_filters('the_content', get_post_meta($feature_post->ID, '_cmb2_feature_body', true););
$feature_image = \Firebelly\Media\get_header_bg($feature_post, ['size' => 'medium'])
?>
<article class="feature">
  <div class="wrap">
  <?php if ($feature_image): ?>
    <div class="image" <?= $feature_image ?>></div>
  <?php endif; ?>
  <h1 class="h3"><?= $feature_post->post_title ?></h1>
  <?php if (!empty($feature_title)): ?>
    <h2><?= $feature_title ?></h2>
  <?php endif; ?>
  <?php if (!empty($feature_subhead)): ?>
    <h3><?= $feature_subhead ?></h3>
  <?php endif; ?>
  <?php if (!empty($feature_body)): ?>
    <div class="user-content"><?= $feature_body ?></div>
  <?php endif; ?>
  </div>
</article>
