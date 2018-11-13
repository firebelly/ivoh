<?php
// Defaults
$subtitle = '';
$excerpt = \Firebelly\Utils\get_excerpt($post);

if (get_post_type($post) == 'story') {
  // Try to get story_type for subtitle
  if ($story_types = get_the_terms($post, 'story_type')) {
    $story_type = array_pop($story_types);
    $subtitle = $story_type->name;
  }
} elseif (get_post_type($post) == 'post') {
  $subtitle = 'News & Commentary';
} elseif (get_post_type($post) == 'person') {
  // Try to get person_type for subtitle
  if ($person_type = \Firebelly\Utils\get_first_term($post, 'person_category')) {
    $subtitle = $person_type->name;
  }
  // Use bio for excerpt if available
  if ($person_bio = get_post_meta($post->ID, '_cmb2_person_post_bio', true)) {
    $excerpt = wp_trim_words(strip_tags($person_bio), 20);
  }
} elseif ($post->post_parent) {
  $parent_post = get_post($post->post_parent);
  $subtitle = $parent_post->post_title;
}
?>

<article <?php post_class('search-result card item'); ?>>
  <div class="card-content">
    <header>
      <h3 class="card-subtitle"><?= $subtitle ?></h3>
      <h2 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    </header>
    <div class="card-text">
      <?= $excerpt ?>
    </div>
    <p class="post-url h6"><a href="<?php the_permalink(); ?>"><?= getenv('WP_HOME') ?><?php the_permalink(); ?></a></p>
  </div>
</article>
