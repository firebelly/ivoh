<?php
/**
 * Extra fields for Pages
 */

namespace Firebelly\Fields\Pages;

add_filter( 'cmb2_admin_init', __NAMESPACE__ . '\metaboxes' );
function metaboxes() {
  $prefix = '_cmb2_';

  /**
    * Page intro fields
    */
  $page_intro = new_cmb2_box([
    'id'            => $prefix . 'page_intro',
    'title'         => esc_html__( 'Page Intro', 'cmb2' ),
    'object_types'  => ['page', 'tool'],
    'context'       => 'normal',
    'priority'      => 'high',
  ]);
  $page_intro->add_field([
    'name' => esc_html__( 'Intro Subhead', 'cmb2' ),
    'id'   => $prefix .'intro_subhead',
    'type' => 'text_medium',
    'desc' => 'Will use parent page title if left blank'
  ]);
  $page_intro->add_field([
    'name' => esc_html__( 'Intro Headline', 'cmb2' ),
    'id'   => $prefix .'intro_headline',
    'type' => 'text_medium',
    'desc' => 'Will use page title if left blank'
  ]);
  $page_intro->add_field([
    'name' => esc_html__( 'Intro Body', 'cmb2' ),
    'id'   => $prefix .'intro_body',
    'type' => 'textarea_small',
    // 'options' => [
    //   'textarea_rows' => 8,
    // ],
  ]);

  $page_intro_links = new_cmb2_box([
    'id'            => $prefix . 'page_intro_links',
    'title'         => esc_html__( 'Page Intro Links', 'cmb2' ),
    'show_on'       => ['key' => 'page-template', 'value' => ['front-page.php', 'page-fellowship.php']],
    'object_types'  => ['page', 'tool'],
    'context'       => 'normal',
    'priority'      => 'high',
  ]);
  $group_field_id = $page_intro_links->add_field([
    'id'              => 'intro_links',
    'type'            => 'group',
    'options'         => [
      'group_title'   => __( 'Intro Link {#}', 'cmb2' ),
      'add_button'    => __( 'Add Another Link', 'cmb2' ),
      'remove_button' => __( 'Remove Link', 'cmb2' ),
      'sortable'      => true,
    ],
  ]);
  $page_intro_links->add_group_field( $group_field_id, [
    'name' => 'Link Text',
    'id'   => 'link_text',
    'type' => 'text',
    'row_classes' => '-half',
  ]);
  $page_intro_links->add_group_field( $group_field_id, [
    'name' => 'Link URL',
    'id'   => 'url',
    'type' => 'text',
    'row_classes' => '-half',
  ]);

  /**
    * Annual Summit fields
    */
  $annual_summit = new_cmb2_box([
    'id'            => 'secondary_content',
    'title'         => __( 'Annual Summit Info', 'cmb2' ),
    'object_types'  => ['page'],
    'context'       => 'normal',
    'show_on'       => ['key' => 'page-template', 'value' => 'page-annual-summit.php'],
    'priority'      => 'high',
    'show_names'    => true,
  ]);
  $annual_summit->add_field([
    'name'    => esc_html__( 'Date Start', 'cmb2' ),
    'id'      => $prefix . 'date_start',
    'type'    => 'text_date',
  ]);
  $annual_summit->add_field([
    'name'    => esc_html__( 'Date End', 'cmb2' ),
    'id'      => $prefix . 'date_end',
    'type'    => 'text_date',
  ]);
  $annual_summit->add_field([
    'name'    => esc_html__( 'Venue', 'cmb2' ),
    'id'      => $prefix . 'venue',
    'type'    => 'text',
  ]);
  $annual_summit->add_field([
    'name' => esc_html__( 'Address', 'cmb2' ),
    'id'   => $prefix . 'address',
    'type' => 'address',
  ]);
  $annual_summit->add_field([
    'name' => esc_html__( 'Register URL', 'cmb2' ),
    'id'   => $prefix . 'register_url',
    'type' => 'text_url',
  ]);
  $annual_summit->add_field([
    'name' => esc_html__( 'Microsite URL', 'cmb2' ),
    'id'   => $prefix . 'microsite_url',
    'type' => 'text_url',
  ]);

  // /**
  //   * Homepage fields
  //   */
  // $homepage_fields = new_cmb2_box([
  //   'id'            => 'secondary_content',
  //   'title'         => __( 'Custom Featured Block', 'cmb2' ),
  //   'object_types'  => ['page'],
  //   'context'       => 'normal',
  //   'show_on'       => ['key' => 'page-template', 'value' => 'front-page.php'],
  //   'priority'      => 'high',
  //   'show_names'    => true,
  // ]);
  // $homepage_fields->add_field([
  //   'name'    => esc_html__( 'Custom Featured Image', 'cmb2' ),
  //   'id'      => $prefix . 'custom_featured_image',
  //   'type'    => 'file',
  //   'options' => [
  //     'url'   => false, // Hide the text input for the url
  //   ],
  // ]);
  // $homepage_fields->add_field([
  //   'name' => esc_html__( 'Custom Featured Title', 'cmb2' ),
  //   'id'   => $prefix . 'custom_featured_title',
  //   'type' => 'text',
  // ]);
  // $homepage_fields->add_field([
  //   'name' => esc_html__( 'Custom Featured Body', 'cmb2' ),
  //   'id'   => $prefix . 'custom_featured_body',
  //   'type' => 'wysiwyg',
  //   'options' => [
  //     'textarea_rows' => 8,
  //   ],
  // ]);
  // $homepage_fields->add_field([
  //   'name' => esc_html__( 'Custom Featured Link', 'cmb2' ),
  //   'id'   => $prefix . 'custom_featured_link',
  //   'type' => 'text_url',
  //   'desc' => 'e.g. http://foo.com/',
  // ]);

  /**
    * Donate page fields
    */
  $donate_single_fields = new_cmb2_box([
    'id'            => 'donation_options',
    'title'         => __( 'Donation Options', 'cmb2' ),
    'object_types'  => ['page'],
    'context'       => 'normal',
    'show_on'       => ['key' => 'page-template', 'value' => 'page-donate.php'],
    'priority'      => 'core',
    'show_names'    => true,
  ]);
  $donate_single_fields->add_field([
    'name'        => 'Amount',
    'id'          => 'amount',
    'type'        => 'text',
    'sortable'    => true,
    'repeatable'  => true,
  ]);
}

function sanitize_text_callback( $value, $field_args, $field ) {
  $value = strip_tags( $value, '<b><strong><i><em>' );
  return $value;
}
