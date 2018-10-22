<?php
// Partial used for the image section of story carousels
$story_image = \Firebelly\Media\get_header_bg($story_post, ['size' => 'medium']);
?>
<?php if ($story_image): ?>
  <div class="story-image-container background-blend">
    <div class="story-image" <?= $story_image ?>></div>
  </div>
<?php endif; ?>