<?php
/*
  Template name: News
*/

// Get filter vars
$topics = explode(',', get_query_var('topics', ''));
$order_by = get_query_var('order-by', 'date-desc');

// Get all posts matching filters
$posts = \Firebelly\Utils\get_posts([
  'topics'  => array_filter($topics),
  'order-by' => $order_by,
]);

// Get base topics for filtering
$news_topics = get_terms([
  'taxonomy' => 'category',
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
?>
<?php get_template_part('templates/page', 'header'); ?>

<div class="story-type filters">
<div class="topics filters">
  <h3>Filter by Issue</h3>
  <ul class="topics">
  <?php foreach ($news_topics as $term):
    if (in_array($term->slug, $topics)) {
      $active = ' class="active"';
      $filtered = array_filter($topics, function ($el) use ($term) { return ($el != $term->slug); });
      $link = add_query_arg(['topics' => implode(',', $filtered) ]);
    } else {
      $active = '';
      $link = add_query_arg(['topics' => implode(',', array_filter(array_merge($topics, [$term->slug]))) ]);
    }
    ?>
    <li<?= $active ?>><a href="<?= $link ?>"><?= $term->name ?></a></li>
  <?php endforeach; ?>
  </ul>
</div>

<div class="sort-by">
  <h3>Sort By</h3>
  <select name="sort-by" class="jumpSelect">
    <?php foreach ($sort_by_options as $sort_by_option => $sort_by_title): ?>
      <option<?= $sort_by_option == $order_by ? ' selected' : '' ?> value="<?= add_query_arg(['order-by' => $sort_by_option ]) ?>"><?= $sort_by_title ?></option>
    <?php endforeach; ?>
  </select>
</div>

<?php if (empty($posts)): ?>
  <p class="no-posts">No posts found.</p>
<?php else: ?>
  <?= $posts ?>
<?php endif; ?>
