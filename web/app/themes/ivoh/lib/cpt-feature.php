<?php
/**
 * Feature post type
 */

namespace Firebelly\PostTypes\Feature;
use PostTypes\PostType; // see https://github.com/jjgrainger/PostTypes

$features = new PostType('feature', [
  'supports'   => ['title', 'thumbnail'],
  'rewrite'    => ['with_front' => false],
]);
$features->register();

/**
 * CMB2 custom fields
 */
function metaboxes() {
  $prefix = '_cmb2_';

  $feature_info = new_cmb2_box([
    'id'            => $prefix . 'feature_info',
    'title'         => __( 'Feature Info', 'cmb2' ),
    'object_types'  => ['feature'],
    'context'       => 'normal',
    'priority'      => 'high',
  ]);
  $feature_info->add_field([
    'name'      => 'Subhead',
    'id'        => $prefix . 'feature_subhead',
    'type'      => 'text_medium',
  ]);
  $feature_info->add_field([
    'name'      => 'Body',
    'id'        => $prefix . 'feature_body',
    'type'      => 'wysiwyg',
  ]);
  $feature_info->add_field([
    'name'      => 'Link',
    'id'        => $prefix . 'feature_link',
    'type'      => 'text_url',
  ]);
}
add_filter( 'cmb2_admin_init', __NAMESPACE__ . '\metaboxes' );

/**
 * Get features
 */
function get_features($options=[]) {
  if (empty($options['num_posts'])) $options['num_posts'] = -1;
  $args = [
    'numberposts' => $options['num_posts'],
    'post_type'   => 'feature',
  ];

  // Display all matching posts using article-{$post_type}.php
  $features_posts = get_posts($args);
  if (!$features_posts) return false;
  $output = '';
  foreach ($features_posts as $feature_post):
    $feature_post->column_width = $options['column-width'];
    ob_start();
    include(locate_template('templates/article-feature.php'));
    $output .= ob_get_clean();
  endforeach;
  return $output;
}
