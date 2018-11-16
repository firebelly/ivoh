<?php
/*
  Template name: Person
*/

// Get all post_meta
$post_meta = get_post_meta($post->ID);

// Person Type/Title
$person_categories = get_the_terms($post, 'person_category');
$is_fellow = false;
$person_title = '';
if (!empty($person_categories)) {
  $person_category_slugs = [];
  $person_type = \Firebelly\Utils\get_first_term($post, 'person_category');
  foreach ($person_categories as $key => &$cat) {
    if (strpos($cat->slug, '-fellows')) {
      $is_fellow = true;
      $cat = str_replace('Fellows','Fellow',$cat->name);
    } else {
      unset($person_categories[$key]);
    }
  }

  if (!empty($post_meta['_cmb2_person_title'])) {
      $person_title = $post_meta['_cmb2_person_title'][0];
  }

  if ($is_fellow) {
    if (!empty($person_title)) {
      $person_title.= ', ';
    }
    $person_title.= implode(', ', $person_categories);
  }
}

// Author Bio
if (empty(trim($post->post_content)) && $post_bio = get_post_meta($post->ID, '_cmb2_person_post_bio', true)) {
  // Fallback to short post bio if body is empty
  $author_bio = apply_filters('the_content', $post_bio);
} else {
  $author_bio = apply_filters('the_content', $post->post_content);
}

// Get all stories & news by author
$author_posts = \Firebelly\Utils\get_posts([
  'post_type'     => ['story','post'],
  'template-type' => 'simple',
  'author'        => $post->ID,
  'numberposts'   => -1,
]);

?>
<header class="page-header">
  <div class="-inner">
    <div class="person-image" <?= \Firebelly\Media\get_header_bg($post, ['size'=>'medium']) ?>></div>
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

<?php if ($author_posts): ?>
  <div class="article-list page-section fb-container-content">
    <div class="-inner inherit-background">
      <h3 class="list-title">Posts by author:</h3>
      <?= $author_posts ?>
    </div>
  </div>
<?php endif ?>

<?php if (!empty($person_type)): ?>
  <?php
  switch (true) {
    case $person_type->name === 'Trustees':
      $back_to_link = '/who-we-are/board-of-trustees/';
      break;
    case $person_type->name === 'Advisors':
      $back_to_link = '/who-we-are/board-of-advisors/';
      break;
    case $person_type->name === date('Y').' Fellows':
      $back_to_link = '/what-we-do/fellowship/';
      break;
    case strpos($person_type->name, 'Fellows') !== false:
      $back_to_link = '/what-we-do/past-fellows/';
      break;
    default:
      $back_to_link = '/who-we-are/team/';
      break;
  }
  ?>
  <div class="post-navigation fb-container-content">
    <p class="back-navigation">
      <a href="<?= $back_to_link ?>">
        <span class="h5">Back To</span>
        <span class="h4">All <?= $person_type->name ?></span>
      </a>
    </p>
  </div>
<?php endif ?>
