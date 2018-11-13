<?php
namespace Firebelly\Ajax;

/**
 * Add wp_ajax_url variable to global js scope
 */
function wp_ajax_url() {
  wp_localize_script('sage/js', 'wp_ajax_url', admin_url('admin-ajax.php'));
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\wp_ajax_url', 100);

/**
 * Silly ajax helper, returns true if xmlhttprequest
 */
function is_ajax() {
  return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
}

/**
 * AJAX load more posts
 */
function load_more_posts() {
  // Get page offsets
  $page = !empty($_REQUEST['page']) ? $_REQUEST['page'] : 1;
  $per_page = !empty($_REQUEST['per_page']) ? $_REQUEST['per_page'] : get_option('posts_per_page');
  $order_by = !empty($_REQUEST['order_by']) ? $_REQUEST['order_by'] : 'date-desc';
  $order_by = explode('-', $order_by);
  $offset = ($page-1) * $per_page;

  echo \Firebelly\Utils\get_posts([
    'post-type'      => (!empty($_REQUEST['post_type']) ? $_REQUEST['post_type'] : 'news'),
    'offset'         => $offset,
    'numberposts'    => $per_page,
    'orderby'        => $order_by[0],
    'order'          => strtoupper($order_by[1]),
    'topic-taxonomy' => (!empty($_REQUEST['topic_taxonomy']) ? $_REQUEST['topic_taxonomy'] : 'category'),
    'topics'         => (!empty($_REQUEST['topics']) ? explode(',', $_REQUEST['topics']) : ''),
    'story-types'    => (!empty($_REQUEST['story_types']) && $_REQUEST['story_types'] != 'all' ? [$_REQUEST['story_types']] : []),
  ]);

  // we use this call outside AJAX calls; WP likes die() after an AJAX call
  if (is_ajax()) die();
}
add_action( 'wp_ajax_load_more_posts', __NAMESPACE__ . '\\load_more_posts' );
add_action( 'wp_ajax_nopriv_load_more_posts', __NAMESPACE__ . '\\load_more_posts' );
