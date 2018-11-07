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
  // What type of post?
  $post_type = !empty($_REQUEST['post_type']) ? $_REQUEST['post_type'] : 'post';
  // get page offsets
  $page = !empty($_REQUEST['page']) ? $_REQUEST['page'] : 1;
  $per_page = !empty($_REQUEST['per_page']) ? $_REQUEST['per_page'] : get_option('posts_per_page');
  $order_by = !empty($_REQUEST['order_by']) ? $_REQUEST['order_by'] : 'date-desc';
  $order_by = explode('-', $order_by);
  // Filter Optons
  if (!empty($_REQUEST['story_types'])) {
    $story_types = explode(',', $_REQUEST['story_types']);
  }
  if (!empty($_REQUEST['topics'])) {
    $topics = explode(',', $_REQUEST['topics']);
    $topic_taxonomy = $_REQUEST['topic_taxonomy'];
  }

  $offset = ($page-1) * $per_page;

  $args = [
    'offset'         => $offset,
    'posts_per_page' => $per_page,
    'post_type'      => $post_type,
    'orderby'        => $order_by[0],
    'order'          => strtoupper($order_by[1]),
  ];

  // Filter by Story Type?
  if (!empty($_REQUEST['story_types'])) {
    $args['tax_query'][] = [
      'relation' => 'AND',
      [
        'taxonomy' => 'story_type',
        'field'    => 'slug',
        'terms'    => array_filter($story_types),
        'compare'  => 'IN',
      ]
    ];
  }
  // Filter by Topic?
  if (!empty($_REQUEST['topics'])) {
    $args['tax_query'][] = [
      'taxonomy' => $topic_taxonomy,
      'field'    => 'slug',
      'terms'    => array_filter($topics),
      'compare'  => 'IN',
    ];
  }

  $posts = get_posts($args);

  if ($posts):
    foreach ($posts as $post) {
      // set local var for post type — avoiding using $post in global namespace
      $article_post = $post;
      // Reset $post_type var to 'news' to pull up article-news template
      if ($post_type === 'post') {
        $post_type = 'news';
      }
      include(locate_template('templates/article-'.$post_type.'.php'));
    }
  endif;

  // we use this call outside AJAX calls; WP likes die() after an AJAX call
  if (is_ajax()) die();
}
add_action( 'wp_ajax_load_more_posts', __NAMESPACE__ . '\\load_more_posts' );
add_action( 'wp_ajax_nopriv_load_more_posts', __NAMESPACE__ . '\\load_more_posts' );
