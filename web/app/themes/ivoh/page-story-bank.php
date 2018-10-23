<?php
/*
  Template name: Story Bank
*/

// Topic var
$topics = explode(',', get_query_var('topics', ''));
$order_by = get_query_var('order-by', 'date-desc');
$story_types = explode(',', get_query_var('story-types', 'rn,sbm'));
if (empty($story_types)) {
  $story_types = 'rn,sbm';
}

// Get all stories matching filters
$stories = \Firebelly\PostTypes\Story\get_stories([
  'types'   => array_filter($story_types),
  'topics'  => array_filter($topics),
  'orderby' => $order_by,
]);

// Get base topics for filtering
$story_topics = get_terms([
  'taxonomy' => 'story_topic',
  'parent' => 0,
]);

// Sort options
$sort_by_options = [
  'date-desc' => 'Date (Newest First)',
  'date-asc' => 'Date (Oldest First)',
  'title-asc' => 'Story Name (A-Z)',
  'title-desc' => 'Story Name (Z-A)',
  'author-asc' => 'Author Name (A-Z)',
  'author-desc' => 'Author Name (Z-A)',
];

// Story type options
$story_type_options = [
  'rn' => 'Restorative Narratives',
  'sbm' => 'Strength-Based Media',
];
?>

<?php get_template_part('templates/page', 'header'); ?>

<div class="story-type filters fb-container-md">
  <h3 class="filter-title">Filter by Story Type</h3>
  <ul>
    <?php foreach ($story_type_options as $story_type_option => $story_type_title):
      if (in_array($story_type_option, $story_types)) {
        $active = ' class="-active"';
        $filtered = array_filter($story_types, function ($el) use ($story_type_option) { return ($el != $story_type_option); });
        $link = add_query_arg(['story-types' => implode(',', $filtered) ]);
      } else {
        $active = '';
        $link = add_query_arg(['story-types' => implode(',', array_filter(array_merge($story_types, [$story_type_option]))) ]);
      }
      ?>
      <li<?= $active ?>><a href="<?= $link ?>" class="button rounded white"><?= $story_type_title ?></a></option>
    <?php endforeach; ?>
  </ul>
</div>

<div class="topics filters fb-container-md">
  <h3 class="filter-title">Filter by Issue</h3>
  <ul class="topics">
  <?php foreach ($story_topics as $term):
    if (in_array($term->slug, $topics)) {
      $active = ' class="-active"';
      $filtered = array_filter($topics, function ($el) use ($term) { return ($el != $term->slug); });
      $link = add_query_arg(['topics' => implode(',', $filtered) ]);
    } else {
      $active = '';
      $link = add_query_arg(['topics' => implode(',', array_filter(array_merge($topics, [$term->slug]))) ]);
    }
    ?>
    <li<?= $active ?>><a href="<?= $link ?>" class="button rounded white"><?= $term->name ?></a></li>
  <?php endforeach; ?>
  </ul>
</div>

<div class="sort-by fb-container-content">
  <h3>Sort By</h3>
  <select name="sort-by" class="jumpSelect">
    <?php foreach ($sort_by_options as $sort_by_option => $sort_by_title): ?>
      <option<?= $sort_by_option == $order_by ? ' selected' : '' ?> value="<?= add_query_arg(['order-by' => $sort_by_option ]) ?>"><?= $sort_by_title ?></option>
    <?php endforeach; ?>
  </select>
</div>

<div class="fb-container-md grid patterned">
  <?php if (empty($stories)): ?>
    <p class="no-posts">No posts found.</p>
  <?php else: ?>
    <?= $stories ?>
  <?php endif; ?>
</div>