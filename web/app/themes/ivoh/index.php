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

<div class="mobile-gutter">
  <div class="topics filters fb-container-md accordion expanded-md">
    <h3 class="filter-title accordion-toggle"><span class="-inner">Filter by Topic<button class="expand-contract"><span class="icon plus-minus"></span></button></span></h3>
    <ul class="topics accordion-content">
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
      <li<?= $active ?>><a href="<?= $link ?>" class="button rounded white"><?= $term->name ?></a></li>
    <?php endforeach; ?>
    </ul>
  </div>

  <div class="sort-by filters fb-container-md accordion expanded-md">
    <h3 class="filter-title accordion-toggle"><span class="-inner">Sort By<button class="expand-contract"><span class="icon plus-minus"></span></button></span></h3>
    <div class="accordion-content">
      <div class="select-wrap">
        <select name="sort-by" class="jumpSelect">
          <?php foreach ($sort_by_options as $sort_by_option => $sort_by_title): ?>
            <option<?= $sort_by_option == $order_by ? ' selected' : '' ?> value="<?= add_query_arg(['order-by' => $sort_by_option ]) ?>"><?= $sort_by_title ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </div>
</div>

<div class="fb-container-md card-grid">
  <div class="masonry sm-halves md-thirds lg-fourths -inner">
    <div class="grid-sizer"></div>
    <?php if (empty($posts)): ?>
      <p class="no-posts">No posts found.</p>
    <?php else: ?>
      <?= $posts ?>
    <?php endif; ?>
  </div>
</div>