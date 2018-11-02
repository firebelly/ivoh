<?php
  if (get_post_type($post) == 'story') {
    $story_types = get_the_terms($post, 'story_type');
    $story_type = array_pop($story_types);
    $subtitle = $story_type->name;
  } elseif (get_post_type($post) == 'post') {
    $subtitle = 'News & Commentary';
  } elseif (get_post_type($post) == 'person') {
    $person_categories = get_the_terms($post, 'person_category');
    $person_type = \Firebelly\Utils\get_first_term($post, 'person_category');
    $subtitle = $person_type->name;
  } elseif ($post->post_parent) {
    $parent_post = get_post($post->post_parent);
    $subtitle = $parent_post->post_title;
  } elseif (!$post->post_parent) {
    $subtitle = '';
  }

?>

<article <?php post_class('search-result card item'); ?>>
  <div class="card-content">
    <header>
      <h3 class="card-subtitle"><?= $subtitle ?></h3>
      <h2 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      <?php if (get_post_type() === 'post') { get_template_part('templates/entry-meta'); } ?>
    </header>
    <div class="card-text">
      <?= \Firebelly\Utils\get_excerpt($post); ?>
    </div>
    <p class="post-url h5"><a href="<?php the_permalink(); ?>"><?php the_permalink(); ?></a></p>
  </div>
</article>
