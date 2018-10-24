<?php
$person_title = get_post_meta($person_post->ID, '_cmb2_person_title', true);
$person_desc = apply_filters('the_content', $person_post->post_content);
$person_image = \Firebelly\Media\get_header_bg($person_post, ['size' => 'medium'])
?>
<article class="person <?= $person_post->column_width ?>"><div class="wrap">
  <?php if ($person_image): ?>
    <div class="image" <?= $person_image ?>></div>
  <?php endif; ?>
  <h2><?= $person_post->post_title ?></h2>
  <?php if (!empty($person_title)): ?>
    <h4><?= $person_title ?></h4>
  <?php endif; ?>
  <?php if (!empty($person_desc)): ?>
    <div class="user-content">
      <?= $person_desc ?>
    </div>
  <?php endif; ?>
</div></article>
