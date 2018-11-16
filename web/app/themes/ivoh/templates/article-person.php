<?php
$person_title = get_post_meta($person_post->ID, '_cmb2_person_title', true);
$first_name = get_post_meta($person_post->ID, '_first_name', true);
$person_image = \Firebelly\Media\get_header_bg($person_post, ['size' => 'medium']);
$person_types = get_the_terms($person_post, 'person_category');
foreach ($person_types as &$person_type) {
  $person_type = $person_type->slug;
}
$person_types = implode(' ', $person_types);
?>
<article class="person card sm-one-half lg-one-third <?= $person_types ?><?= !empty($extra_class) ? " {$extra_class}" : '' ?>">
  <div class="card-content">
    <?php if ($person_image): ?>
      <div class="card-image-container background-blend">
        <a href="<?= get_permalink($person_post) ?>" class="card-image" <?= $person_image ?>></a>
      </div>
    <?php endif; ?>
    <div class="card-text">
      <h2 class="card-title"><a href="<?= get_permalink($person_post) ?>"><?= $person_post->post_title ?></a></h2>
      <?php if (!empty($person_title)): ?>
        <h4 class="card-subtitle"><?= $person_title ?></h4>
      <?php endif; ?>
      <p class="card-action"><a href="<?= get_permalink($person_post) ?>">About <?= $first_name ?></a></p>
    </div>
  </div>
</article>
