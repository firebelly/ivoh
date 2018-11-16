<?php
/**
 * Extra fields for Posts (and shared fields w/ other post types)
 */

namespace Firebelly\Fields\Posts;

add_filter( 'cmb2_admin_init', __NAMESPACE__ . '\\metaboxes' );
function metaboxes() {
  $prefix = '_cmb2_';

  $post_is_featured = new_cmb2_box([
    'id'            => $prefix . 'post_is_featured',
    'title'         => esc_html__( 'Featured Post', 'cmb2' ),
    'object_types'  => ['post', 'story'],
    'context'       => 'side',
    'priority'      => 'default',
    'show_names'    => false,
  ]);
  $post_is_featured->add_field([
    'name'    => esc_html__( 'Featured', 'cmb2' ),
    'id'      => $prefix . 'featured',
    'desc'    => 'If a Story post, shows post on carousels; if News + Commentary post, makes post sticky (displayed at top of feed)',
    'type'    => 'checkbox',
  ]);

  $post_author = new_cmb2_box([
    'id'            => $prefix . 'post_author',
    'title'         => esc_html__( 'Author(s)', 'cmb2' ),
    'object_types'  => ['post', 'story'],
    'context'       => 'side',
    'priority'      => 'default',
    'show_names'    => false,
  ]);
  $post_author->add_field([
    'name'             => 'Author(s)',
    'id'               => $prefix . 'author',
    'type'             => 'pw_multiselect',
    'multiple'         => true,
    'show_option_none' => true,
    'options_cb'       => '\Firebelly\CMB2\get_people'
  ]);

  $seo_fields = new_cmb2_box([
    'id'            => $prefix . 'seo_fields',
    'title'         => esc_html__( 'SEO', 'cmb2' ),
    'object_types'  => ['post', 'story', 'page', 'tool'],
    'context'       => 'normal',
    'priority'      => 'default',
  ]);
  $seo_fields->add_field([
    'name'    => esc_html__( 'SEO Title', 'cmb2' ),
    'id'      => $prefix . 'seo_title',
    'desc'    => 'Custom title override to improve SEO — limit to 69 chars',
    'type'    => 'text',
  ]);
  $seo_fields->add_field([
    'name'    => esc_html__( 'SEO Description', 'cmb2' ),
    'id'      => $prefix . 'seo_description',
    'desc'    => 'Used for meta description to improve SEO, and for social sharing — limit to 155 chars',
    'type'    => 'textarea_small',
  ]);

  $photo_caption = new_cmb2_box([
    'id'            => $prefix . 'photo_caption',
    'title'         => esc_html__( 'Photo Caption', 'cmb2' ),
    'object_types'  => ['post', 'page', 'story'],
    'context'       => 'side',
    'priority'      => 'low',
    'show_names'    => false,
  ]);
  $photo_caption->add_field([
    'name'    => esc_html__( 'Caption', 'cmb2' ),
    'id'      => $prefix . 'photo_caption',
    'desc'    => 'Use this field to override the default image caption for just this post',
    'type'    => 'textarea_small',
  ]);

  $related_info = new_cmb2_box([
    'id'            => $prefix . 'related_info',
    'title'         => esc_html__( 'Related Info', 'cmb2' ),
    'object_types'  => ['post', 'story'],
    'context'       => 'normal',
    'priority'      => 'default',
  ]);
  $related_info->add_field([
    'name'    => esc_html__( 'Post Intro', 'cmb2' ),
    'id'      => $prefix . 'post_intro',
    'desc'    => 'Shows at at top of post, below author bio, i.e. Editors Note',
    'type'    => 'wysiwyg',
    'options' => [
       'textarea_rows' => 4,
     ],
  ]);
  // $related_info->add_field([
  //   'name'    => esc_html__( 'Related Links', 'cmb2' ),
  //   'id'      => $prefix . 'related_links',
  //   'desc'    => 'A list of related links which shows at bottom of posts',
  //   'type'    => 'wysiwyg',
  //   'options' => [
  //      'textarea_rows' => 4,
  //    ],
  // ]);
  $related_info->add_field([
    'name'             => esc_html__( 'Related Posts', 'cmb2' ),
    'id'               => $prefix . 'related_posts',
    'desc'             => 'A list of related posts which shows at bottom of posts',
    'type'             => 'pw_multiselect',
    'multiple'         => true,
    'show_option_none' => true,
    'options_cb'       => '\Firebelly\CMB2\get_stories_and_posts',
  ]);

  // $author = new_cmb2_box([
  //   'id'            => $prefix . 'author',
  //   'title'         => esc_html__( 'Author', 'cmb2' ),
  //   'object_types'  => ['post', 'story'],
  //   'context'       => 'normal',
  //   'priority'      => 'high',
  // ]);
  // $author->add_field([
  //   'name'      => 'Author',
  //   'id'        => $prefix . 'author',
  //   'type'             => 'select',
  //   'show_option_none' => true,
  //   'options_cb'       => '\Firebelly\CMB2\get_people'
  // ]);

  // $image_slideshow = new_cmb2_box([
  //   'id'            => 'image_slideshow',
  //   'title'         => esc_html__( 'Image Slideshow', 'cmb2' ),
  //   'object_types'  => ['program', 'workshop'],
  //   'context'       => 'side',
  //   'priority'      => 'low',
  //   // 'closed'        => true,
  // ]);
  // $image_slideshow->add_field([
  //   'name'       => __( 'Images', 'cmb2' ),
  //   'show_names' => false,
  //   'id'         => $prefix .'slideshow_images',
  //   'type'       => 'file_list',
  //   'desc'       => esc_html__('Slideshow for bottom of post', 'cmb2'),
  // ]);

}

/**
 * Record timestamp when post is marked featured for ordering
 */
add_action('add_post_meta', __NAMESPACE__ . '\\check_featured_added', 10, 3);
function check_featured_added($post_id, $meta_key, $meta_value) {
  // Avoid this check if the importers update-date-featured task is being run to avoid nesting error
  if (empty($_REQUEST['update-date-featured'])) {
      if ($meta_key === '_cmb2_featured' && $meta_value === 'on') {
        update_post_meta($post_id, '_date_featured', time());
      } else {
        // Always setting _date_featured to make the sticky ordering work for News
        update_post_meta($post_id, '_date_featured', 0);
      }
  }
}
