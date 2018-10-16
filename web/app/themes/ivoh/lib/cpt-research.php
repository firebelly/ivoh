<?php
/**
 * Research post type
 */

namespace Firebelly\PostTypes\Research;
use PostTypes\PostType; // see https://github.com/jjgrainger/PostTypes
use PostTypes\Taxonomy;

$research = new PostType(['name' => 'research', 'plural' => 'Research', 'slug' => 'research'], [
  'taxonomies' => ['research_topic'],
  'supports'   => ['title'],
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
    'column'    => array( // adds this field to admin columns
      'position' => 3,
      'name'     => 'URL',
    ),
  ]);
}

/**
 * Get research posts
 */
function get_research($opts=[]) {
  if (empty($opts['num_posts'])) $opts['num_posts'] = -1;
  $args = [
    'numberposts' => $opts['num_posts'],
    'post_type'   => 'research',
  ];

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
