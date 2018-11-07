<?php
/*
  Template name: Story Bank
*/

// Get filter vars
$topics = explode(',', get_query_var('topics', ''));
$order_by = get_query_var('order-by', 'date-desc');
$story_types = explode(',', get_query_var('story-types', 'rn,sbm'));

// Default story types = all story types
if (empty($story_types)) {
  $story_types = 'rn,sbm';
}

// Amount of posts to pull
$per_page = get_option('posts_per_page');

// Get all stories matching filters
$args = [
  'numberposts' => $per_page,
  'story-types' => array_filter($story_types),
  'topics'      => array_filter($topics),
  'order-by'    => $order_by,
];
$stories = \Firebelly\PostTypes\Story\get_stories($args);

// Total number of posts
$num_posts = \Firebelly\PostTypes\Story\get_stories(array_merge(['countposts' => 1], $args));

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
$story_type_terms = get_terms(['taxonomy' => 'story_type', 'hide_empty' => 0]);
?>

<?php get_template_part('templates/page', 'header'); ?>

<div class="mobile-gutter">
  <div class="story-type filters fb-container-md accordion expanded-md">
    <h3 class="filter-title accordion-toggle"><span class="-inner">Filter by Story Type<button class="expand-contract"><span class="icon plus-minus"></span></button></span></h3>
    <ul class="accordion-content">
      <?php foreach ($story_type_terms as $story_type_cat):
        $story_type_slug = $story_type_cat->slug;
        $story_type_title = $story_type_cat->name;
        if (in_array($story_type_slug, $story_types)) {
          $active = ' class="-active"';
          $filtered = array_filter($story_types, function ($el) use ($story_type_slug) { return ($el != $story_type_slug); });
          $link = add_query_arg(['story-types' => implode(',', $filtered) ]);
        } else {
          $active = '';
          $link = add_query_arg(['story-types' => implode(',', array_filter(array_merge($story_types, [$story_type_slug]))) ]);
        }
        ?>
        <li<?= $active ?>><a href="<?= $link ?>" class="button rounded white"><?= $story_type_title ?></a></option>
      <?php endforeach; ?>
    </ul>
  </div>

  <div class="topics filters fb-container-md accordion expanded-md">
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

  <div class="sort-by filters accordion expanded-md">
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
  <div class="load-more-container masonry sm-halves md-thirds lg-fourths -inner">
    <div class="grid-sizer"></div>
    <?php if (empty($stories)): ?>
      <p class="no-posts">No posts found.</p>
    <?php else: ?>
      <?= $stories ?>
    <?php endif; ?>
  </div>
  <?php if ($num_posts > $per_page): ?>
  <div class="load-more grid-actions inherit-background" data-post-type="story" data-page-at="1" data-per-page="<?= $per_page ?>" data-total-pages="<?= ceil($num_posts/$per_page) ?>" data-order-by="<?= $order_by ?>" data-story-types="<?= get_query_var('story-types', 'rn,sbm') ?>" data-topic-taxonomy="story_topic" data-topics="<?= get_query_var('topics', '') ?>">
    <a href="#" class="button">Load More Stories</a>
  </div>
  <?php endif ?>
</div>
