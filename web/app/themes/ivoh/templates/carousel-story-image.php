<?php
// Partial used for the image section of story carousels
$story_image = \Firebelly\Media\get_header_bg($story_post, ['size' => 'medium']);
?>
<?php if ($story_image): ?>
  <div class="story-image-container card-image-container background-blend">
    <a href="<?= get_the_permalink($story_post) ?>" class="story-image card-image" <?= $story_image ?>></a>
  </div>
<?php endif; ?>