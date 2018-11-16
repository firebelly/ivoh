<?php
/**
 * Person post type
 */

namespace Firebelly\PostTypes\Person;
use PostTypes\PostType; // see https://github.com/jjgrainger/PostTypes
use PostTypes\Taxonomy;

$persons = new PostType(['name' => 'person', 'plural' => 'People', 'slug' => 'person'], [
  'taxonomies' => ['person_category'],
  'supports'   => ['title', 'editor', 'thumbnail', 'revisions'],
  'rewrite'    => ['with_front' => false],
]);
$persons->filters(['person_category']);
$persons->register();

// Custom taxonomy
$person_category = new Taxonomy([
  'name'     => 'person_category',
  'slug'     => 'person_category',
  'plural'   => 'Person Categories',
]);
$person_category->register();

/**
 * CMB2 custom fields
 */
function metaboxes() {
  $prefix = '_cmb2_';

  $person_info = new_cmb2_box([
    'id'            => $prefix . 'person_info',
    'title'         => __( 'Person Info', 'cmb2' ),
    'object_types'  => ['person'],
    'context'       => 'normal',
    'priority'      => 'high',
  ]);
  $person_info->add_field([
    'name'      => 'Title',
    'id'        => $prefix . 'person_title',
    'type'      => 'text_medium',
    'column'    => array(
      'position' => 2,
      'name'     => 'Title',
    ),
  ]);
  $person_info->add_field([
    'name'      => 'Post Bio',
    'id'        => $prefix . 'person_post_bio',
    'type'      => 'wysiwyg',
    'desc'      => 'A shortened bio that appears on posts that are authored by the person.',
    'options' => [
       'textarea_rows' => 5,
     ],
  ]);
}
add_filter( 'cmb2_admin_init', __NAMESPACE__ . '\metaboxes' );

/**
 * Update post meta for sorting people by last name
 */
function person_sort_meta($post_id) {
  if (wp_is_post_revision($post_id))
    return;

  $post_title = get_the_title($post_id);
  // Remove ", Ph.D" from name
  $post_title = preg_replace('#, ?(.*)$#', '', $post_title);

  if (strpos($post_title, ' ') !== FALSE) {
    list($first, $last) = preg_split('/ ([^ ]+)$/', $post_title, 0, PREG_SPLIT_DELIM_CAPTURE);
    // Remove middle name if present
    $first = preg_replace('# (.*)$#', '', $first);
  } else {
    // ivoh uses first_name in people listings, so setting title to both
    $first = $post_title;
    $last = $post_title;
  }
  update_post_meta($post_id, '_first_name', $first);
  update_post_meta($post_id, '_last_name', $last);

  // Update all stories/posts `_author_sort` for this person in case name changed
  $stories = \Firebelly\PostTypes\Story\get_stories([
    'template-type' => 'simple',
    'author' => $post_id,
    'return' => 'array',
  ]);
  if (!empty($stories)) {
    foreach ($stories as $story) {
      update_post_meta($story->ID, '_author_sort', $last);
    }
  }
}
add_action('save_post_person', __NAMESPACE__.'\\person_sort_meta');

/**
 * Get People
 */
function get_people($options=[]) {
  if (empty($options['numberposts'])) $options['numberposts'] = -1;
  $args = [
    'numberposts' => $options['numberposts'],
    'post_type'   => 'person',
  ];
  if (!empty($options['category'])) {
    $args['tax_query'] = [
      [
        'taxonomy' => 'person_category',
        'field' => 'slug',
        'terms' => $options['category']
      ]
    ];
  }

  // By default use Intuitive CPO hand-ordering, otherwise sort by last name
  if (!empty($options['order-by']) && $options['order-by'] === 'name') {
    $args['order'] = 'ASC';
    $args['orderby'] = 'meta_value';
    $args['meta_key'] = '_last_name';
  }

  // Display all matching posts using article-{$post_type}.php
  $people_posts = get_posts($args);
  if (!$people_posts) return false;
  $output = '';
  $extra_class = !empty($options['extra-class']) ? $options['extra-class'] : '';
  foreach ($people_posts as $person_post):
    ob_start();
    \Firebelly\Utils\get_template_part_with_vars('templates/article', 'person', [ 'person_post' => $person_post, 'extra_class' => $extra_class ]);
    $output .= ob_get_clean();
  endforeach;
  return $output;
}
