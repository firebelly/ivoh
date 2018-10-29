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
    'column'     => [
      'position' => 2,
      'name'     => 'Application Prompt',
    ],
  ]);
  $tool_info->add_field([
    'name'       => 'Mailchimp List ID',
    'id'         => $prefix . 'mailchimp_list_id',
    'type'       => 'text_small',
    'desc'       => 'Mailchimp list new applicants are added to',
    'column'     => [
      'position' => 3,
      'name'     => 'Mailchimp List ID',
    ],
  ]);
  $tool_info->add_field([
    'name'      => 'Sample PDF(s)',
    'id'        => $prefix . 'sample_pdfs',
    'type'      => 'file_list',
    'desc'      => 'PDF(s) shown on public page',
  ]);
  $tool_info->add_field([
    'name'      => 'Final PDF(s)',
    'id'        => $prefix . 'final_pdfs',
    'type'      => 'file_list',
    'desc'      => 'PDF(s) attached to email sent to applicants',
  ]);
  $tool_info->add_field([
    'name'      => 'Applicant email',
    'id'        => $prefix . 'applicant_email',
    'type'      => 'wysiwyg',
    'desc'      => 'Template for email sent to applicants',
    'options' => [
      'textarea_rows' => 8,
    ],
  ]);
}

/**
 * Get tool posts
 */
function get_tools($opts=[]) {
  if (empty($opts['numberposts'])) $opts['numberposts'] = -1;
  $args = [
    'numberposts' => $opts['numberposts'],
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
