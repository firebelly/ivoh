<?php
$person_title = get_post_meta($person_post->ID, '_cmb2_person_title', true);
$person_image = \Firebelly\Media\get_header_bg($person_post, ['size' => 'medium'])
?>
<article class="person card md-one-half lg-one-third">
  <div class="card-content">
    <?php if ($person_image): ?>
      <div class="card-image-container background-blend">
        <a href="<?= get_permalink($person_post) ?>" class="card-image" <?= $person_image ?>></a>
      </div>
    <?php endif; ?>
    <h2 class="card-title"><?= $person_post->post_title ?></h2>
    <?php if (!empty($person_title)): ?>
      <h4 class="card-subtitle"><?= $person_title ?></h4>
    <?php endif; ?>
    <p class="card-action"><a href="<?= get_permalink($person_post) ?>" class="button">More</a></p>
  </div>
</article>
