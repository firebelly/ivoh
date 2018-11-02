<?php
/**
 * Research post type
 */

namespace Firebelly\PostTypes\Research;
use PostTypes\PostType; // see https://github.com/jjgrainger/PostTypes
use PostTypes\Taxonomy;

$research = new PostType(['name' => 'research', 'plural' => 'Research', 'slug' => 'research'], [
  'taxonomies' => ['research_topic'],
  'supports'   => ['title', 'revisions'],
  'rewrite'    => ['with_front' => false],
]);
$research->filters(['research_topic']);
$research->register();

// Custom taxonomies
$research_topic = new Taxonomy('research_topic');
$research_topic->register();

/**
 * CMB2 custom fields
 */
add_filter('cmb2_admin_init', __NAMESPACE__ . '\metaboxes');
function metaboxes() {
  $prefix = '_cmb2_';

  $research_info = new_cmb2_box([
    'id'            => $prefix . 'research_info',
    'title'         => __( 'Research Info', 'cmb2' ),
    'object_types'  => ['research'],
    'context'       => 'normal',
    'priority'      => 'high',
  ]);
  $research_info->add_field([
    'name'      => 'URL',
    'id'        => $prefix . 'research_url',
    'type'      => 'text_url',
    'column'    => array(
      'position' => 3,
      'name'     => 'URL',
    ),
  ]);
  $research_info->add_field([
    'name'      => 'Description',
    'id'        => $prefix . 'description',
    'type'      => 'textarea_small',
  ]);
}

/**
 * Get research posts
 */
function get_research($opts=[]) {
  if (empty($opts['numberposts'])) $opts['numberposts'] = -1;
  $args = [
    'numberposts' => $opts['numberposts'],
    'post_type'   => 'research',
  ];
  if (!empty($opts['category'])) {
    $args['tax_query'] = [
      [
        'taxonomy' => 'research_topic',
        'field' => 'slug',
        'terms' => $opts['category']
      ]
    ];
  }

  // Display all matching posts using article-{$post_type}.php
  $research_posts = get_posts($args);
  if (!$research_posts) return false;
  $output = '';
  foreach ($research_posts as $research_post):
    ob_start();
    include(locate_template('templates/article-research.php'));
    $output .= ob_get_clean();
  endforeach;
  return $output;
}
