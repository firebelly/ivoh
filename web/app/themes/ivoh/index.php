<?php
/*
  Template name: News
*/

// Get filter vars
$topics = explode(',', get_query_var('topics', ''));
$order_by = get_query_var('order-by', 'date-desc');

// Load More Vars
$per_page = get_option('posts_per_page');

$args = [
  'numberposts' => $per_page,
  'topics'  => array_filter($topics),
  'order-by' => $order_by,
];

// Get all posts matching filters
$posts = \Firebelly\Utils\get_posts($args);

$num_posts = \Firebelly\Utils\get_posts(array_merge(['countposts' => 1], $args));

// Get base topics for filtering
$news_topics = get_terms([
  'taxonomy' => 'category',
  'parent'   => 0,
]);

// Sort options
$sort_by_options = [
  'date-desc' => 'Date (Newest First)',
  'date-asc' => 'Date (Oldest First)',
  'title-asc' => 'Post Title (A-Z)',
  'title-desc' => 'Post Title (Z-A)',
  'author-asc' => 'Author Name (A-Z)',
  'author-desc' => 'Author Name (Z-A)',
];

// News page for header
$news_page = get_post(get_option('page_for_posts'));
?>

<?php \Firebelly\Utils\get_template_part_with_vars('templates/page', 'header', [ 'post' => $news_page ]); ?>

<div class="filters-container">
  <div class="topics filters fb-container-md padded">
    <div class="accordion expanded-md -inner">
      <h3 class="filter-title accordion-toggle"><span class="-inner">Filter by Topic<button class="expand-contract"><span class="icon plus-minus"></span></button></span></h3>
      <ul class="topics accordion-content">
      <?php foreach ($news_topics as $term):
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
  </div>

  <div class="sort-by filters fb-container-md padded">
    <div class="accordion expanded-md -inner">
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
</div>

<div class="fb-container-md padded">
  <div class="card-grid">
    <?php if (!empty($posts)): ?>
      <div class="load-more-container masonry sm-halves md-thirds lg-fourths -inner">
        <div class="grid-sizer"></div>
        <?= $posts ?>
      </div>
      <?php if ($num_posts > $per_page): ?>
        <div class="load-more grid-actions inherit-background" data-post-type="news" data-page-at="1" data-per-page="<?= $per_page ?>" data-total-pages="<?= ceil($num_posts/$per_page) ?>" data-order-by="<?= $order_by ?>" data-topics="<?= get_query_var('topics', '') ?>">
          <a href="#" class="button">Load More Posts</a>
        </div>
      <?php endif; ?>
    <?php else: ?>
      <div class="card"><p class="no-posts">No posts found.</p></div>
    <?php endif; ?>
  </div>
</div>