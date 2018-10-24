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

  /**
    * Page next/prev nav
    */
  $page_links = new_cmb2_box([
    'id'            => $prefix . 'page_links',
    'title'         => esc_html__( 'Footer Page Links', 'cmb2' ),
    'object_types'  => ['page', 'story', 'post'],
    'context'       => 'normal',
    'priority'      => 'default',
    'show_on_cb'    => __NAMESPACE__.'\\cmb_show_for_fbdev',
  ]);
  $page_links->add_field([
    'name'             => esc_html__( 'Previous Page', 'cmb2' ),
    'id'               => $prefix .'previous_page',
    'type'             => 'select',
    'show_option_none' => true,
    'options_cb'       => '\Firebelly\CMB2\get_pages'
  ]);
  $page_links->add_field([
    'name'             => esc_html__( 'Next Page', 'cmb2' ),
    'id'               => $prefix .'next_page',
    'type'             => 'select',
    'show_option_none' => true,
    'options_cb'       => '\Firebelly\CMB2\get_pages'
  ]);

  /**
    * Page Color Field
    */
  $page_color = new_cmb2_box([
    'id'            => $prefix . 'page_color_theme',
    'title'         => esc_html__( 'Page Color', 'cmb2' ),
    'object_types'  => ['page'],
    'context'       => 'side',
    'priority'      => 'default',
    'show_names'    => false,
  ]);
  $page_color->add_field([
    'name'      => esc_html__( 'Page Color', 'cmb2' ),
    'id'        => $prefix .'page_color',
    'type'      => 'select',
    'default'   => 'pink',
    'options'   => [
      'pink'    => __( 'Pink', 'cmb2' ),
      'mint'    => __( 'Mint', 'cmb2' ),
      'blue'    => __( 'Blue', 'cmb2' ),
      'sand'    => __( 'Sand', 'cmb2' ),
    ],
  ]);

  /**
   * Page intro links (only used on homepage and fellowship)
   */
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

/**
 * This can be used to allow tags in text fields
 * usage: 'sanitization_cb' => __NAMESPACE__.'\\sanitize_text_callback',
 */
function sanitize_text_callback( $value, $field_args, $field ) {
  $value = strip_tags( $value, '<b><strong><i><em>' );
  return $value;
}

/**
 * Only show fields to fbdev user
 */
function cmb_show_for_fbdev($cmb) {
  $user = wp_get_current_user();
  $show = ($user->user_login === 'fbdev') ? true : false;
  return $show;
}
