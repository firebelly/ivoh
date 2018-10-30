<?php
/*
  Template name: Person
*/

// Get all post_meta
$post_meta = get_post_meta($post->ID);

// Person Type/Title
$person_categories = get_the_terms($post, 'person_category');
$person_type = \Firebelly\Utils\get_first_term($post, 'person_category');
$person_category_slugs = [];
foreach ($person_categories as $cat):
  $person_category_slugs[] = $cat->slug;
endforeach;
if (preg_grep("/-fellows/", $person_category_slugs)) {
  $person_category = array_pop($person_categories);
  $person_title = str_replace('s','',$person_category->name);
} elseif (!empty($post_meta['_cmb2_person_title'])) {
  $person_title = $post_meta['_cmb2_person_title'][0];
} else {
  $person_title = '';
}

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

<header class="page-header">
  <div class="-inner">
    <div class="person-image" <?= \Firebelly\Media\get_header_bg($post) ?>></div>
    <div class="fb-container-content">
      <h2 class="page-title"><?= $post->post_title; ?></h2>
      <?php if (!empty($person_title)): ?>
        <p class="person-title"><?= $person_title ?></p>
      <?php endif ?>
    </div>
  </div>
</header>

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

<div class="post-navigation fb-container-content">
  <div class="back-navigation">
    <p class="back-text h5">Back To</p>
    <p><a href="#" class="h4">All <?= $person_type->name ?></a></p>
  </div>
</div>
