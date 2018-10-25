<?php
$person_title = get_post_meta($person_post->ID, '_cmb2_person_title', true);
$person_image = \Firebelly\Media\get_header_bg($person_post, ['size' => 'medium']);
$person_type = \Firebelly\Utils\get_first_term($person_post, 'person_category');
if ($person_type->name == 'Staff' || $person_type->name == date('Y').'-fellows') {
  $read_more_text = 'More';
} else {
  $read_more_text = 'About '.$person_post->post_title;
}
?>
<article class="person card md-one-half lg-one-third <?= $person_type->slug ?>">
  <div class="card-content">
    <?php if ($person_image): ?>
      <div class="card-image-container background-blend">
        <a href="<?= get_permalink($person_post) ?>" class="card-image" <?= $person_image ?>></a>
      </div>
    <?php endif; ?>
    <div class="card-text">    
      <h2 class="card-title"><?= $person_post->post_title ?></h2>
      <?php if (!empty($person_title)): ?>
        <h4 class="card-subtitle"><?= $person_title ?></h4>
      <?php endif; ?>
      <p class="card-action"><a href="<?= get_permalink($person_post) ?>"<?= $person_type->name == 'Staff' || $person_type->name == date('Y').'-fellows' ? 'class="button"' : ''?>><?= $read_more_text ?></a></p>
    </div>
  </div>
</article>
