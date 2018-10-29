<?php
/*
  Template name: Person
*/

// Get all post_meta
$post_meta = get_post_meta($post->ID);

// Author Bio
$author_bio = apply_filters('the_content', $post->post_content);

// Get all stories by author
$stories = \Firebelly\PostTypes\Story\get_stories([
  'template-type' => 'simple',
  'author' => $post->ID,
]);

// Get all posts by author
$posts = get_posts([
  'numberposts'   => -1,
  'post_type'     => 'post',
  'template-type' => 'simple',
  'meta_query'    => [
    [
      'key'   => '_cmb2_author',
      'value' => [$post->ID],
    ]
  ],
]);
?>

<?php get_template_part('templates/page', 'header'); ?>

<div class="page-section fb-container-content user-content">
  <?= $author_bio ?>
</div>

<?php if ($stories || $posts): ?>
  <div class="article-list page-section fb-container-content">
    <div class="-inner">
      <h3 class="h5">Posts by author:</h3>

      <?= $stories ?>

      <?php foreach ($posts as $article_post): ?>
        <?php \Firebelly\Utils\get_template_part_with_vars('templates/article', 'simple', [ 'article_post' => $article_post, 'topic_taxonomy' => 'category' ]); ?>
      <?php endforeach ?>
    </div>
  </div>
<?php endif ?>
