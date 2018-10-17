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
    'object_types'  => ['post'],
    'context'       => 'side',
    'priority'      => 'default',
    'show_names'    => false,
  ]);
  $post_is_featured->add_field([
    'name'    => esc_html__( 'Featured', 'cmb2' ),
    'id'      => $prefix . 'featured',
    'desc'    => 'Feature on homepage',
    'type'    => 'checkbox',
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
	if ($meta_key === '_cmb2_featured' && $meta_value === 'on') {
		update_post_meta($post_id, '_date_featured', time());
	}
}
// ... and delete timestamp when unmarked as featured
add_action('delete_post_meta', __NAMESPACE__ . '\\check_featured_deleted', 10, 4);
function check_featured_deleted($meta_id, $post_id, $meta_key, $meta_value) {
	if ($meta_key === '_cmb2_featured') {
		delete_post_meta($post_id, '_date_featured');
	}
}
