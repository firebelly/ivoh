<?php
/**
 * Story post type
 */

namespace Firebelly\PostTypes\Story;
use PostTypes\PostType; // see https://github.com/jjgrainger/PostTypes
use PostTypes\Taxonomy;

$stories = new PostType(['name' => 'story', 'plural' => 'Stories', 'slug' => 'story'], [
  'taxonomies' => ['story_type', 'story_topic'],
  'supports'   => ['title', 'editor', 'thumbnail', 'revisions'],
  'rewrite'    => ['with_front' => false],
]);
$stories->filters(['story_type', 'story_topic']);

// Custom taxonomies
$story_topic = new Taxonomy('story_topic');
$story_topic->register();
$story_type = new Taxonomy('story_type');
$story_type->register();

$stories->columns()->add([
  'authors'     => esc_html__( 'Author', 'cmb2' ),
  'featured'    => esc_html__( 'Featured', 'cmb2'),
]);
$stories->columns()->order( [
    'authors' => 2,
] );
$stories->columns()->sortable([
  'featured'    => ['_date_featured', true],
]);
$stories->columns()->populate('authors', function($column, $post_id) {
  $authors = get_post_meta($post_id, '_cmb2_author');
  if (!empty($authors)) {
    $author = get_post($authors[0]);
    echo $author->post_title;
  } else {
    echo '';
  }
});
$stories->register();

/**
 * Populate Featured admin list field for both Stories and News
 */
function featured_check($post_id) {
  $featured = get_post_meta($post_id, '_cmb2_featured');
  if ($featured) {
    $date_featured =  ' '.date('m/d/Y H:i:s', get_post_meta($post_id, '_date_featured')[0]);
  } else {
    $date_featured = '';
  }
  return $featured ? '✔️ '.$date_featured : '';
}

/**
 * Add Featured column to default Posts, and make sortable
 */
add_filter('manage_posts_columns', __NAMESPACE__.'\posts_custom_columns');
add_action('manage_posts_custom_column', __NAMESPACE__.'\posts_custom_columns_content', 10, 2);
add_filter('manage_edit-post_sortable_columns', __NAMESPACE__.'\posts_custom_columns_sortable');
function posts_custom_columns($cols) {
  $cols['featured'] = 'Featured';
  return $cols;
}
function posts_custom_columns_content($column_name, $post_id) {
    if ($column_name == 'featured') {
      echo featured_check($post_id);
    }
}
function posts_custom_columns_sortable($cols) {
  $cols['featured'] = '_date_featured';
  return $cols;
}

/**
 * Register custom sort for _date_featured
 */
add_action('pre_get_posts', __NAMESPACE__.'\date_featured_orderby');
function date_featured_orderby($query) {
  if (!is_admin()) return;

  if ($query->get('orderby') == '_date_featured') {
    $query->set('meta_key', '_date_featured');
    $query->set('orderby', 'meta_value_num');
  }
}

/**
 * CMB2 custom fields
 */
function metaboxes() {
  $prefix = '_cmb2_';

  $story_info = new_cmb2_box([
    'id'            => $prefix . 'story_info',
    'title'         => __( 'Story Info', 'cmb2' ),
    'object_types'  => ['story'],
    'context'       => 'normal',
    'priority'      => 'high',
  ]);
  $story_info->add_field([
    'name'      => 'Date Published',
    'id'        => $prefix . 'date_published',
    'type'      => 'text_date',
  ]);
  $story_info->add_field([
    'name'      => 'Republished from',
    'id'        => $prefix . 'story_republished',
    'type'      => 'text_medium',
  ]);
}
add_filter( 'cmb2_admin_init', __NAMESPACE__ . '\metaboxes' );

function get_stories($opts) {
  // Defaults
  $opts = array_merge([
    'numberposts'    => -1,
    'post-type'      => 'story',
    'topic-taxonomy' => 'story_topic',
    'template-type'  => 'story',
    'story-types'    => 'all',
  ], $opts);
  // If story types is all, just set to blank array
  $opts['story-types'] = ($opts['story-types'] == 'all') ? [] : [$opts['story-types']];

  return \Firebelly\Utils\get_posts($opts);
}

/**
 * Story carousel shortcode [story_carousel]
 */
add_shortcode('story_carousel', __NAMESPACE__ . '\shortcode_story_carousel');
function shortcode_story_carousel($atts) {
  $output = '';
  $atts = shortcode_atts([
    'type' => 'rn',
  ], $atts, 'story_carousel');

  $stories = get_stories([
    'numberposts' => 3,
    'return'      => 'array',
    'story-types' => $atts['type'],
    'featured'    => 1,
  ]);
  if (empty($stories)) return '';

  $output .= '<div class="story-carousel-container card landscape grid"><div class="story-image-carousel lg-one-half">';

  foreach ($stories as $story_post) {
    ob_start();
    include(locate_template('templates/carousel-story-image.php'));
    $output .= ob_get_clean();
  }

  $output .= '</div><div class="story-content-carousel lg-one-half">';

  foreach ($stories as $story_post) {
    ob_start();
    include(locate_template('templates/carousel-story-content.php'));
    $output .= ob_get_clean();
  }

  $output .= '</div></div>';

  return $output;
}

/**
 * Add query vars for story bank filtering
 */
function add_query_vars_filter($vars){
  $vars[] = 'topics';
  $vars[] = 'order-by';
  $vars[] = 'story-types';
  return $vars;
}
add_filter( 'query_vars', __NAMESPACE__ . '\\add_query_vars_filter' );

/**
 * Update post meta for sorting articles by author(s)
 */
function update_sort_meta($post_id, $post, $update) {
  if (wp_is_post_revision($post_id) || !in_array($post->post_type, ['post', 'story']))
    return;

  $author_last_names = [];
  $story_authors = get_post_meta($post_id, '_cmb2_author');
  if (!empty($story_authors)) {
    foreach ($story_authors as $author_id) {
      $last_name = get_post_meta($author_id, '_last_name', true);
      $author_last_names[] = $last_name;
    }
  }
  update_post_meta($post_id, '_author_sort', implode(' ', $author_last_names));
}
add_action('save_post', __NAMESPACE__.'\update_sort_meta', 10, 3);
