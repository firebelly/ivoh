<?php

namespace Firebelly\Utils;

/**
 * Bump up # search results
 */
function search_queries( $query ) {
  if ( !is_admin() && is_search() ) {
    $query->set( 'posts_per_page', 40 );
  }
  return $query;
}
add_filter( 'pre_get_posts', __NAMESPACE__ . '\\search_queries' );

/**
 * Exclude pages from search results
 */
function exclude_pages_from_search($query) {
  if ( !is_admin() && is_search() ) {
    $top_level_pages = get_pages(array('parent'=>0));
    $top_level_ids = array();
    foreach($top_level_pages as $top_level_page ) {
      $top_level_ids[] = $top_level_page->ID;
    }
    $query->set('post__not_in', $top_level_ids);
  }
  return $query;
}
add_filter( 'pre_get_posts', __NAMESPACE__ . '\\exclude_pages_from_search');

/**
 * Custom li'l excerpt function
 */
function get_excerpt( $post, $length=15, $force_content=false ) {
  $excerpt = trim($post->post_excerpt);
  if (!$excerpt || $force_content) {
    $excerpt = $post->post_content;
    $excerpt = strip_shortcodes( $excerpt );
    $excerpt = apply_filters( 'the_content', $excerpt );
    $excerpt = str_replace( ']]>', ']]&gt;', $excerpt );
    $excerpt_length = apply_filters( 'excerpt_length', $length );
    $excerpt = wp_trim_words( $excerpt, $excerpt_length );
  }
  return $excerpt;
}

/**
 * Get top ancestor for post
 */
function get_top_ancestor($post){
  if (!$post) return;
  $ancestors = $post->ancestors;
  if ($ancestors) {
    return end($ancestors);
  } else {
    return $post->ID;
  }
}

/**
 * Get first term for post
 */
function get_first_term($post, $taxonomy='category') {
  $return = false;
  if ($terms = get_the_terms($post->ID, $taxonomy))
    $return = array_pop($terms);
  return $return;
}

/**
 * Get page content from slug
 */
function get_page_content($slug) {
  $return = false;
  if ($page = get_page_by_path($slug))
    $return = apply_filters('the_content', $page->post_content);
  return $return;
}

/**
 * Get category for post
 */
function get_category($post) {
  if ($category = get_the_category($post)) {
    return $category[0];
  } else return false;
}

/**
 * Get num_pages for category given slug + per_page
 */
function get_total_pages($category, $per_page) {
  $cat_info = get_category_by_slug($category);
  $num_pages = ceil($cat_info->count / $per_page);
  return $num_pages;
}

/**
 * Edit post link for various front end areas
 */
function admin_edit_link($post_or_term) {
  if (!empty($post_or_term->term_id)) {
    $link = get_edit_term_link($post_or_term->term_id);
  } else {
    $link = get_edit_post_link($post_or_term->ID);
  }
  return !empty($link) ? '<a class="edit-link" href="'.$link.'">Edit</a>' : '';
}

/**
 * Support for sending vars to get_template_part()
 * usage: \Firebelly\Utils\get_template_part_with_vars('templates/page', 'header', ['foo' => 'bar']);
 * (from https://github.com/JolekPress/Get-Template-Part-With-Variables)
 */
function get_template_part_with_vars($slug, $name = null, array $namedVariables = []) {
  // Taken from standard get_template_part function
  \do_action("get_template_part_{$slug}", $slug, $name);

  $templates = array();
  $name = (string)$name;
  if ('' !== $name)
      $templates[] = "{$slug}-{$name}.php";

  $templates[] = "{$slug}.php";

  $template = \locate_template($templates, false, false);

  if (empty($template)) {
    return;
  }

  // @see load_template (wp-includes/template.php) - these are needed for WordPress to work.
  global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;

  if (is_array($wp_query->query_vars)) {
    \extract($wp_query->query_vars, EXTR_SKIP);
  }

  if (isset($s)) {
      $s = \esc_attr($s);
  }
  // End standard WordPress behavior

  foreach ($namedVariables as $variableName => $value) {
    if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_\x7f-\xff]*/', $variableName)) {
      trigger_error('Variable names must be valid. Skipping "' . $variableName . '" because it is not a valid variable name.');
      continue;
    }

    // Allowing var overrides to set $post, let's see if it causes issues –nate
    // if (isset($$variableName)) {
    //   trigger_error("{$variableName} already existed, probably set by WordPress, so it wasn't set to {$value} like you wanted. Instead it is set to: " . print_r($$variableName, true));
    //   continue;
    // }

    $$variableName = $value;
  }

  require $template;
}

/**
 * Custom get_posts function to share between News and Stories listing pages w/ several filter options
 */
function get_posts($opts=[]) {
  // Default opts
  $opts = array_merge([
    'return'         => 'html',
    'post-type'      => 'news',
    'order-by'       => 'date-desc',
    'topic-taxonomy' => 'category',
  ], $opts);
  $orderby = explode('-', $opts['order-by']);

  // Default args for get_posts call
  $args = [
    'numberposts' => (!empty($opts['numberposts']) ? $opts['numberposts'] : -1),
    'post_type'   => ($opts['post-type'] == 'news' ? 'post' : $opts['post-type']),
    'orderby'     => $orderby[0],
    'order'       => strtoupper($orderby[1]),
  ];

  // Order by author uses generated postmeta _author_sort which is saved in a hook
  if ($orderby[0]=='author') {
    $args = array_merge($args, [
      'orderby'  => 'meta_value',
      'meta_key' => '_author_sort',
    ]);
  }

  $args['tax_query'] = [];

  // Filter by type?
  if (!empty($opts['story-types'])) {
    $args['tax_query'][] = [
      'relation' => 'AND',
      [
        'taxonomy' => 'story_type',
        'field'    => 'slug',
        'terms'    => $opts['story-types'],
        'compare'  => 'IN',
      ]
    ];
  }

  // Filter by topic?
  if (!empty($opts['topics'])) {
    $args['tax_query'][] = [
      'taxonomy' => $opts['topic-taxonomy'],
      'field'    => 'slug',
      'terms'    => $opts['topics'],
      'compare'  => 'IN',
    ];
  }

  // Filter by featured?
  if (!empty($opts['featured'])) {
    $args = array_merge($args, [
      'meta_key'    => '_date_featured',
      'orderby'     => 'meta_value_num',
      'order'       => 'DESC',
      'meta_query'  => [
        [
          'key'       => '_cmb2_featured',
          'value'     => 'on',
        ]
      ],
    ]);
  }

  // Filter by author?
  if (!empty($opts['author'])) {
    $args = array_merge($args, [
      'meta_query'  => [
        [
          'key'   => '_cmb2_author',
          'value' => $opts['author'],
        ]
      ],
    ]);
  }

  $posts = \get_posts($args);

  // No posts? false!
  if (!$posts) return false;

  // Just return array of posts?
  if ($opts['return'] == 'array') {
    return $posts;
  }

  // Set template type
  if (!empty($opts['template-type'])) {
    $template_type = $opts['template-type'];
  } else {
    $template_type = $opts['post-type'];
  }

  // Display all matching posts using templates/article-{$post_type}.php
  $output = '';
  foreach ($posts as $post):
    ob_start();
    get_template_part_with_vars('templates/article', $template_type, [ 'article_post' => $post, 'topic_taxonomy' => $opts['topic-taxonomy'] ]);
    $output .= ob_get_clean();
  endforeach;
  return $output;
}

/**
 * Custom get_term_link to support filter pages (/story-bank/?topics=foo & /news/?topics=baz)
 */
function get_term_link($term) {
  $link = '';
  if ($term->taxonomy == 'story_topic') {
    $link = '/story-bank/?topics='.$term->slug;
  } else if ($term->taxonomy == 'category') {
    $link = '/news/?topics='.$term->slug;
  } else {
    // We shouldn't ever get here as we don't use built-in category views
    $link = \get_term_link($term);
  }
  return $link;
}
