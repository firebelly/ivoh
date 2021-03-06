<?php

namespace Firebelly\Init;
use Roots\Sage\Assets;

/**
 * Don't run wpautop before shortcodes are run! wtf Wordpress. from http://stackoverflow.com/a/14685465/1001675
 */
remove_filter('the_content', 'wpautop');
add_filter('the_content', 'wpautop' , 99);
add_filter('the_content', 'shortcode_unautop',100);

/**
 * Various theme defaults
 */
function setup() {
  // Default Image options
  update_option('image_default_align', 'none');
  update_option('image_default_link_type', 'none');
  update_option('image_default_size', 'large');
}
add_action('after_setup_theme', __NAMESPACE__ . '\setup');

/*
 * Tiny MCE options
 */
function mce_buttons_2($buttons) {
  array_unshift($buttons, 'styleselect');
  return $buttons;
}
add_filter('mce_buttons_2', __NAMESPACE__ . '\mce_buttons_2');

function simplify_tinymce($settings) {
  // What goes into the 'formatselect' list
  $settings['block_formats'] = 'H2=h2;H3=h3;Paragraph=p;Blockquote=blockquote';

  $settings['inline_styles'] = 'false';
  if (!empty($settings['formats']))
    $settings['formats'] = substr($settings['formats'],0,-1).",underline: { inline: 'u', exact: true} }";
  else
    $settings['formats'] = "{ underline: { inline: 'u', exact: true} }";

  // What goes into the toolbars. Add 'wp_adv' to get the Toolbar toggle button back
  $settings['toolbar1'] = 'formatselect,styleselect,bold,italic,underline,strikethrough,bullist,numlist,blockquote,link,unlink,hr,fullscreen';
  $settings['toolbar2'] = '';
  $settings['toolbar3'] = '';
  $settings['toolbar4'] = '';

  // $settings['autoresize_min_height'] = 250;
  $settings['autoresize_max_height'] = 1000;

  // Clear most formatting when pasting text directly in the editor
  $settings['paste_as_text'] = 'true';

  $style_formats = array(
    // array(
    //   'title' => 'Two Column',
    //   'block' => 'div',
    //   'classes' => 'two-column',
    //   'wrapper' => true,
    // ),
    array(
      'title' => 'Button',
      'block' => 'span',
      'classes' => 'button',
    ),
    array(
      'title' => 'Quote Attribution',
      'block' => 'cite',
      'classes' => 'quote-attribution',
    ),
 );
  $settings['style_formats'] = json_encode($style_formats);

  return $settings;
}
add_filter('tiny_mce_before_init', __NAMESPACE__ . '\simplify_tinymce');

/**
 * Clean up content before saving post
 */
function clean_up_content($content) {
  // Convert <span class="button"><a></span> to <a class="button"> (can't just add class to element w/ tinymce style formats, has to have wrapper)
  $content = preg_replace('/<span class=\\\"button\\\"><a(.*)<\/a><\/span>/', '<a class=\"button\"$1</a>', $content);
  return $content;
}
add_filter('content_save_pre', __NAMESPACE__ . '\\clean_up_content', 10, 1);
//
// ... and support for cmb2 wysiwyg fields:
//
function cmb2_sanitize_wysiwyg_callback($override_value, $content) {
  $content = preg_replace('/<span class=\\\"button\\\"><a(.*)<\/a><\/span>/', '<a class=\"button\"$1</a>', $content);
  return $content;
}
add_filter('cmb2_sanitize_wysiwyg', __NAMESPACE__ . '\\cmb2_sanitize_wysiwyg_callback', 10, 2);

/**
 * Wrap post images in figure tag
 * @param  [type] $html
 * @param  [type] $id
 * @param  [type] $caption
 * @param  [type] $title
 * @param  [type] $align
 * @param  [type] $url
 * @return [type]
 */
function html5_insert_image($html, $id, $caption, $title, $align, $url, $size, $alt) {
  $url = wp_get_attachment_url($id);
  $src = wp_get_attachment_image_src( $id, $size, false );
  $html5 = "<figure>";
  $html5 .= "<img src='$src[0]' alt='$alt' />";
  if ($caption) {
    $html5 .= "<figcaption>$caption</figcaption>";
  }
  $html5 .= "</figure>";
  return $html5;
}
add_filter( 'image_send_to_editor', __NAMESPACE__ . '\html5_insert_image', 10, 9 );

/**
 * Remove unused Customize link from admin bar
 */
add_action( 'wp_before_admin_bar_render', function() {
  global $wp_admin_bar;
  $wp_admin_bar->remove_menu('customize');
});

/**
 * Custom Admin styles + JS
 */
add_action('admin_enqueue_scripts', function($hook){
  wp_enqueue_style('fb_wp_admin_css', Assets\asset_path('styles/admin.css'));
  wp_enqueue_script('fb_wp_admin_js', Assets\asset_path('scripts/admin.js'), ['jquery'], null, true);
}, 100);

/**
 * Remove labels from archive/category titles
 */
add_filter( 'get_the_archive_title', function($title) {
  if ( is_category() ) {
      $title = single_cat_title( '', false );
  } elseif ( is_tag() ) {
      $title = single_tag_title( '', false );
  } elseif ( is_author() ) {
      $title = '<span class="vcard">' . get_the_author() . '</span>';
  } elseif ( is_post_type_archive() ) {
      $title = post_type_archive_title( '', false );
  } elseif ( is_tax() ) {
      $title = single_term_title( '', false );
  }
  return $title;
} );

/**
 * Also search postmeta
 */
function search_join($join) {
  global $wpdb;
  if (is_search()) {
    $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
  }
  return $join;
}
add_filter('posts_join', __NAMESPACE__ . '\search_join');
function search_where($where) {
  global $wpdb;
  if (is_search()) {
    $where = preg_replace(
      "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
      "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where);
  }
  return $where;
}
add_filter('posts_where', __NAMESPACE__ . '\search_where');
function search_distinct($where) {
  global $wpdb;
  if (is_search()) {
    return "DISTINCT";
  }
  return $where;
}
add_filter('posts_distinct', __NAMESPACE__ . '\search_distinct');

/**
 * Bump up # search results
 */
function search_queries( $query ) {
  if ( !is_admin() && is_search() ) {
    $query->set( 'posts_per_page', -1 );
  }
  return $query;
}
add_filter( 'pre_get_posts', __NAMESPACE__ . '\search_queries' );

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
add_filter( 'pre_get_posts', __NAMESPACE__ . '\exclude_pages_from_search');

/**
 * Hide editor on specific pages
 */
add_action('admin_head', __NAMESPACE__.'\\hide_editor');
function hide_editor() {
  global $pagenow;
  if($pagenow !== 'post.php') return;
  $post_id = !empty($_GET['post']) ? $_GET['post'] : (!empty($_POST['post_ID']) ? $_POST['post_ID'] : '');
  if(!isset($post_id)) return;
  $template_file = basename(get_page_template());
  if($template_file == 'front-page.php') {
    remove_post_type_support('page', 'editor');
  }
}

/**
 * Add support for SEO title field
 */
add_filter('pre_get_document_title', __NAMESPACE__.'\\change_the_title');
function change_the_title() {
  global $post;
  if (is_singular() && !empty($post)) {
    $seo_title = get_post_meta($post->ID, '_cmb2_seo_title', true);
  }
  if (!empty($seo_title)) {
    return $seo_title;
  }
}

/**
 * Redirect 404s if we can find a suitable new home
 * (redirect old categories if possible, and URLs that have slug in the base, e.g. /ivoh-recalibrating/ -> /news/ivoh-recalibrating/)
 */
function redirect_old_posts(){
  if( is_404() ){
    global $wp,$wpdb;
    $request_url = $wp->request;

    // Category page? See if we can find a new home for that category
    if (preg_match('#^category/#', $request_url)) {
      $slug = preg_replace('#^/?category/([^/]+)/?#', '$1', $request_url);
      $new_url = '';

      // Custom redirects (e.g. "2016-restorative-narrative-fellowship" — "ivoh-restorative-narrative-fellowship" — "ivoh-2017-summit" — "international-journalism/")
      if (preg_match('/restorative-narrative-fellowship/', $slug)) {
        $new_url = '/story-bank/?topics=restorative-narrative-fellowship';
      } else if (preg_match('/summit/', $slug)) {
        $new_url = '/news/?topics=ivoh-summit';
      } else if (preg_match('/journalism/', $slug)) {
        $new_url = '/news/?topics=journalism';
      } else if (preg_match('/film/', $slug)) {
        $new_url = '/story-bank/?topics=film';
      }

      // Custom redirect match already?
      if (!empty($new_url)) {
        wp_redirect($new_url, 301);
        exit;
      }

      // Try to find story topics that match slug
      if ($topic_slug = $wpdb->get_var($wpdb->prepare("SELECT t.slug FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy='story_topic' AND t.slug LIKE %s", '%'.$slug.'%'))) {
        $new_url = '/story-bank/?topics='.$topic_slug;
      } else if ($topic_slug = $wpdb->get_var($wpdb->prepare("SELECT t.slug FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy='category' AND t.slug LIKE %s", '%'.$slug.'%'))) {
        // Try to find news categories that match slug
        $new_url = '/news/?topics='.$topic_slug;
      } else {
        // Fall back to /story-bank if nothing found
        $new_url = '/story-bank/';
      }
      wp_redirect($new_url, 301);
      exit;

    } else {

      // See if we can find a matching post by slug
      $id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = %s", $request_url));
      if($id) {
        $wp_query->is_404 = false;
        $new_url = get_permalink($id);
        wp_redirect($new_url, 301);
        exit;
      }

    }
  }
}
add_action('template_redirect', __NAMESPACE__.'\\redirect_old_posts');
