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
$story_topic = new Taxonomy('story_topic', ['hierarchical' => false]);
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
    'name'      => 'Author',
    'id'        => $prefix . 'author',
    'type'             => 'select',
    'show_option_none' => true,
    'options_cb'       => '\Firebelly\CMB2\get_people'
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
function get_storys($opts=[]) {
  if (empty($opts['num_posts'])) $opts['num_posts'] = -1;
  $args = [
    'numberposts' => $opts['num_posts'],
    'post_type'   => 'story',
  ];

  // Display all matching posts using article-{$post_type}.php
  $stories_posts = get_posts($args);
  if (!$stories_posts) return false;
  $output = '';
  foreach ($stories_posts as $story_post):
    ob_start();
    include(locate_template('templates/article-story.php'));
    $output .= ob_get_clean();
  endforeach;
  return $output;
}
