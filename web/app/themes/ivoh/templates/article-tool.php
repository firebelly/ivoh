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
<article class="tool"><div class="wrap">
  <h4><?= $subhead ?></h4>
  <h2><?= $tool_post->post_title ?></h2>
  <?php if (!empty($post_meta['_cmb2_intro_body'])): ?>
    <div class="user-content">
      <p><?= $post_meta['_cmb2_intro_body'][0] ?></p>
    </div>
  <?php endif; ?>
</div></article>
