<?php
/*
  Template name: Story Bank
*/

// Get filter vars
$topics = explode(',', get_query_var('topics', ''));
$order_by = get_query_var('order-by', 'date-desc');
$story_types = get_query_var('story-types', 'all');

// Amount of posts to pull
$per_page = get_option('posts_per_page');

// If only showing Restorative Narratives, only show topics used by RN posts
if ($story_types != 'all') {

  // Get all story post IDs
  $story_post_ids = \Firebelly\PostTypes\Story\get_stories([
    'numberposts' => -1,
    'fields'      => 'ids',
    'return'      => 'array',
    'story-types' => $story_types
  ]);

  // In case they've chosen some filters in ALL and then switched to RN, show those topics also, even if not used by RN posts
  // (to avoid "no posts found" but no topic filters being shown)
  $extra_sql = !empty($topics) ? ' OR t.slug IN ("'.implode('","', $topics).'")' : '';

  // Find story topics that use those post IDs
  $story_topic_ids = $wpdb->get_col("
  SELECT t.term_id FROM $wpdb->terms AS t
        INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
        INNER JOIN $wpdb->term_relationships AS r ON r.term_taxonomy_id = tt.term_taxonomy_id
        WHERE tt.taxonomy IN('story_topic')
        AND (r.object_id IN (".implode(',', $story_post_ids)."){$extra_sql})
        GROUP BY t.term_id
  ");

  // Pull those topics for filtering
  $story_topics = get_terms([
    'taxonomy' => 'story_topic',
    'parent'   => 0,
    'include'  => $story_topic_ids
  ]);

} else {

  // Get all (non-empty) story topics for filtering
  $story_topics = get_terms([
    'taxonomy' => 'story_topic',
    'parent'   => 0,
  ]);

}

// Get all stories matching filters
$args = [
  'numberposts' => $per_page,
  'story-types' => $story_types,
  'topics'      => array_filter($topics),
  'order-by'    => $order_by,
];
$stories = \Firebelly\PostTypes\Story\get_stories($args);

// Total number of posts
$num_posts = \Firebelly\PostTypes\Story\get_stories(array_merge(['countposts' => 1], $args));

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

<div class="filters-container">
  <div class="topics filters fb-container-md padded">
    <div class="accordion expanded-md -inner">
      <h3 class="filter-title accordion-toggle"><span class="-inner">Filter by Issue<button class="expand-contract"><span class="icon plus-minus"></span></button></span></h3>
      <ul class="topics accordion-content">
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
  </div>

  <div class="story-type filters fb-container-md padded">
    <div class="accordion expanded-md -inner">
      <h3 class="filter-title accordion-toggle"><span class="-inner">Filter by Story Type<button class="expand-contract"><span class="icon plus-minus"></span></button></span></h3>
      <ul class="accordion-content">
        <li<?= $story_types == 'rn' ? ' class="-active"' : ''?>><a class="button rounded white" href="<?= add_query_arg(['story-types' => 'rn']); ?>">Restorative Narratives</a></li>
        <li<?= $story_types == 'all' ? ' class="-active"' : ''?>><a class="button rounded white" href="<?= add_query_arg(['story-types' => 'all']); ?>">All</a></li>
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
    <?php if (!empty($stories)): ?>
      <div class="load-more-container masonry sm-halves md-thirds lg-fourths -inner">
        <div class="grid-sizer"></div>
          <?= $stories ?>
      </div>
      <?php if ($num_posts > $per_page): ?>
      <div class="load-more grid-actions inherit-background" data-post-type="story" data-page-at="1" data-per-page="<?= $per_page ?>" data-total-pages="<?= ceil($num_posts/$per_page) ?>" data-order-by="<?= $order_by ?>" data-story-types="<?= $story_types ?>" data-topic-taxonomy="story_topic" data-topics="<?= get_query_var('topics', '') ?>">
        <a href="#" class="button">Load More Stories</a>
      </div>
      <?php endif; ?>
    <?php else: ?>
      <div class="card"><p class="no-posts">No posts found.</p></div>
    <?php endif; ?>
  </div>
</div>