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
$story_type = new Taxonomy('story_type', ['hierarchical' => false]);
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
  // $story_info->add_field([
  //   'name'      => 'Featured Image Caption',
  //   'id'        => $prefix . 'featured_image_caption',
  //   'type'      => 'text',
  // ]);
  // $story_info->add_field([
  //   'name'      => 'Featured Image Credit',
  //   'id'        => $prefix . 'featured_image_credit',
  //   'type'      => 'text',
  // ]);
}
add_filter( 'cmb2_admin_init', __NAMESPACE__ . '\metaboxes' );

/**
 * Get storys
 */
function get_stories($opts=[]) {
  // Default opts
  $opts = array_merge([
    'return' => 'html',
    'type'   => 'all',
  ], $opts);

  // Default args
  $args = [
    'numberposts' => (!empty($opts['numberposts']) ? $opts['numberposts'] : -1),
    'post_type'   => 'story',
  ];

  // Filter by topic?
  if (!empty($opts['topic'])) {
    $args['tax_query'] = [
      [
        'taxonomy' => 'story_topic',
        'field' => 'slug',
        'terms' => [$opts['topic']]
      ]
    ];
  }

  // Filter by type?
  if ($opts['type'] != 'all') {
    $args['tax_query'] = [
      [
        'taxonomy' => 'story_type',
        'field'    => 'slug',
        'terms'    => $opts['type'],
      ]
    ];
  }

  // Filter by featured?
  if (!empty($opts['featured'])) {
    $args = array_merge($args, [
      'meta_key'    => '_date_featured',
      'orderby'     => 'meta_value_num',
      'order'       => 'DESC',
      'meta_query'  => [
        [
          'key'       => '_cmb2_featured',
          'value'     => 'on',
        ]
      ],
    ]);
  }

  // Filter by author?
  if (!empty($opts['author'])) {
    $args = array_merge($args, [
      'meta_query'  => [
        [
          'key'   => '_cmb2_author',
          'value' => $opts['author'],
        ]
      ],
    ]);
  }

  // Display all matching posts using article-{$post_type}.php
  $story_posts = get_posts($args);
  if (!$story_posts) return false;
  // Just return array of posts?
  if ($opts['return'] == 'array') {
    return $story_posts;
  }
  // Otherwise spit out HTML
  $output = '';
  foreach ($story_posts as $story_post):
    ob_start();
    include(locate_template('templates/article-story.php'));
    $output .= ob_get_clean();
  endforeach;
  return $output;
}

/**
 * Story carousel shortcode [story_carousel]
 */
add_shortcode('story_carousel', __NAMESPACE__ . '\shortcode_story_carousel');
function shortcode_story_carousel($atts) {
  $output = '';
  $atts = shortcode_atts([
    'type' => 'restorative-narratives',
  ], $atts, 'story_carousel');

  $args = [
    'numberposts' => 3,
    'type'        => $atts['type'],
    'return'      => 'array',
    'featured'    => 1,
  ];
  $stories = get_stories($args);

  $output .= '
    <div class="story-carousel-container card landscape grid">
      <div class="story-image-carousel md-one-half">
  ';

  foreach ($stories as $story_post) {
    ob_start();
    include(locate_template('templates/carousel-story-image.php'));
    $output .= ob_get_clean();
  }

  $output .= '
    </div><!-- .story-image-carousel -->
    <div class="story-content-carousel md-one-half">
  ';

  foreach ($stories as $story_post) {
    ob_start();
    include(locate_template('templates/carousel-story-content.php'));
    $output .= ob_get_clean();
  }

  $output .= '
      </div><!-- .story-content-carousel -->
    </div><!-- .story-carousel-container -->
  ';

  return $output;
}

/**
 * Add query vars for story bank
 */
function add_query_vars_filter($vars){
  $vars[] = 'topic';
  $vars[] = 'order_by';
  $vars[] = 'order_dir';
  return $vars;
}
add_filter( 'query_vars', __NAMESPACE__ . '\\add_query_vars_filter' );
