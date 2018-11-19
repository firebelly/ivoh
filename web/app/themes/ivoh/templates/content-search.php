<?php
// Defaults
$subtitle = '';
$external_link = '';
$excerpt = \Firebelly\Utils\get_excerpt($post);

switch ($post->post_type) {
  case 'story':
    // Try to get story_type for subtitle
    if ($story_types = get_the_terms($post, 'story_type')) {
      $story_type = array_pop($story_types);
      $subtitle = $story_type->name;
    }
    break;

  case 'post':
    $subtitle = 'News & Commentary';
    break;

  case 'research':
    $subtitle = 'Research';
    $permalink = get_post_meta($post->ID, '_cmb2_research_url', true);
    $excerpt = get_post_meta($post->ID, '_cmb2_description', true);
    // External link?
    if (strpos(getenv('WP_HOME'), $permalink)===false && preg_match('/^http/', $permalink)) {
      $external_link = 'rel="noopener" target="_blank" ';
      $permalink_display = $permalink;
    } else {
      $permalink_display = getenv('WP_HOME').$permalink;
    }
    break;

  case 'person':
    // Try to get person_type for subtitle
    if ($person_type = \Firebelly\Utils\get_first_term($post, 'person_category')) {
      $subtitle = $person_type->name;
    }
    // Use bio for excerpt if available
    if ($person_bio = get_post_meta($post->ID, '_cmb2_person_post_bio', true)) {
      $excerpt = wp_trim_words(strip_tags($person_bio), 20);
    }
    break;

  default:
    if ($post->post_parent) {
      $parent_post = get_post($post->post_parent);
      $subtitle = $parent_post->post_title;
    }
}

// If no custom $permalink set (e.g. Research), get post permalink
if (empty($permalink)) {
  $permalink = get_permalink($post->ID);
}
?>

<article <?php post_class('search-result card item'); ?>>
  <div class="card-content">
    <header>
      <h3 class="card-subtitle"><?= $subtitle ?></h3>
      <h2 class="card-title"><a <?= $external_link ?>href="<?= $permalink ?>"><?php the_title(); ?></a></h2>
    </header>
    <div class="card-text">
      <?= $excerpt ?>
    </div>
    <p class="post-url h6"><a <?= $external_link ?>href="<?= $permalink ?>"><?= $permalink ?></a></p>
  </div>
</article>
