<?php
/**
 * Tool post type
 */

namespace Firebelly\PostTypes\Tool;
use PostTypes\PostType; // see https://github.com/jjgrainger/PostTypes
use PostTypes\Taxonomy;

$tool = new PostType('tool', [
  'supports'   => ['title', 'editor'],
  'rewrite'    => ['with_front' => false],
]);
$tool->register();

/**
 * CMB2 custom fields
 */
add_filter('cmb2_admin_init', __NAMESPACE__ . '\metaboxes');
function metaboxes() {
  $prefix = '_cmb2_';

  $tool_info = new_cmb2_box([
    'id'            => $prefix . 'tool_info',
    'title'         => __( 'Tool Info', 'cmb2' ),
    'object_types'  => ['tool'],
    'context'       => 'normal',
    'priority'      => 'high',
  ]);
  $tool_info->add_field([
    'name'      => 'Application Prompt',
    'id'        => $prefix . 'application_prompt',
    'type'      => 'textarea_small',
    'desc'      => 'Shown above the application form',
  ]);
  $group_field_id = $tool_info->add_field([
    'id'              => 'asset_categories',
    'type'            => 'group',
    'options'         => [
      'group_title'   => __( 'Category {#}', 'cmb2' ),
      'add_button'    => __( 'Add Another Category', 'cmb2' ),
      'remove_button' => __( 'Remove Category', 'cmb2' ),
      'sortable'      => true,
    ],
  ]);
  $tool_info->add_group_field( $group_field_id, [
    'name' => 'Category Title',
    'id'   => 'category_title',
    'type' => 'text',
  ]);
  $tool_info->add_group_field( $group_field_id, [
      'id'            => 'assets',
      'name'          => __('Assets', 'cmb2'),
      'type'          => 'file_list',
      // 'text' => array(
      //   'add_upload_files_text' => 'Replacement', // default: "Add or Upload Files"
      //   'remove_image_text' => 'Replacement', // default: "Remove Image"
      //   'file_text' => 'Replacement', // default: "File:"
      //   'file_download_text' => 'Replacement', // default: "Download"
      //   'remove_text' => 'Replacement', // default: "Remove"
      // ),
  ]);
}

/**
 * Get tool posts
 */
function get_tool($opts=[]) {
  if (empty($opts['num_posts'])) $opts['num_posts'] = -1;
  $args = [
    'numberposts' => $opts['num_posts'],
    'post_type'   => 'tool',
  ];

  // Display all matching posts using article-{$post_type}.php
  $tool_posts = get_posts($args);
  if (!$tool_posts) return false;
  $output = '';
  foreach ($tool_posts as $tool_post):
    ob_start();
    include(locate_template('templates/article-tool.php'));
    $output .= ob_get_clean();
  endforeach;
  return $output;
}
