<?php
$post_meta = get_post_meta($tool_post->ID);
// Pull subhead
if (!empty($post_meta['_cmb2_intro_subhead'])) {
  $subhead = $post_meta['_cmb2_intro_subhead'][0];
} elseif ($tool_post->post_parent) {
  // Fallback to parent post title if subhead isn't set in Page Intro fields
  $parent_post = get_post($post->post_parent);
  $subhead = $parent_post->post_title;
}
?>
<article class="tool card md-one-half">
  <div class="card-content">
    <h4 class="card-subtitle"><?= $subhead ?></h4>
    <h2 class="card-title -large"><a href="<?= get_permalink($tool_post) ?>"><?= $tool_post->post_title ?></a></h2>
    <?php if (!empty($post_meta['_cmb2_intro_body'])): ?>
      <p class="card-text user-content">
        <?= $post_meta['_cmb2_intro_body'][0] ?>
      </p>
    <?php endif; ?>
    <p class="card-action">
      <a class="button" href="<?= get_permalink($tool_post) ?>">More</a>
    </p>
  </div>
</article>
