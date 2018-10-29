<?php
/**
 * Story post type
 */

namespace Firebelly\PostTypes\Story;
use PostTypes\PostType; // see https://github.com/jjgrainger/PostTypes
use PostTypes\Taxonomy;

$stories = new PostType(['name' => 'story', 'plural' => 'Stories', 'slug' => 'story'], [
  'taxonomies' => ['story_type', 'story_topic'],
  'supports'   => ['title', 'editor', 'thumbnail'],
  'rewrite'    => ['with_front' => false],
]);
$stories->filters(['story_type', 'story_topic']);
$stories->register();

// Custom taxonomies
$story_topic = new Taxonomy('story_topic');
$story_topic->register();
$story_type = new Taxonomy('story_type');
$story_type->register();

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
  // Set template type
  if (!empty($opts['template-type'])) {
    $template_type = $opts['template-type'];
  } else {
    $template_type = 'story';
  }
  return \Firebelly\Utils\get_posts(array_merge([
    'template-type'  => $template_type,
    'post-type'      => 'story',
    'topic-taxonomy' => 'story_topic',
  ], $opts));
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
    'story-types' => ($atts['type']=='all' ? ['rn','sbm'] : [$atts['type']]),
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
