<?php
/*
  Template name: Person
*/

// Get all post_meta
$post_meta = get_post_meta($post->ID);

// Author Bio
$author_bio = apply_filters('the-content', $post->post_content);

// Get all stories by author
$stories = \Firebelly\PostTypes\Story\get_stories([
  'author' => $post->ID,
]);

// Get all posts by author
$posts = get_posts([
  'numberposts' => -1,
  'post_type'   => 'post',
  'meta_query'  => [
    [
      'key'   => '_cmb2_author',
      'value' => [$post->ID],
    ]
  ],
]);
?>

<?php get_template_part('templates/page', 'header'); ?>

<?= $author_bio ?>

<h3>Posts by author:</h3>

<?= $stories ?>

<?php foreach ($posts as $news_post): ?>
  <?php include(locate_template('templates/article-news.php')); ?>
<?php endforeach ?>
