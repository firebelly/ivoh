<?php
/**
 * Various media functions
 */

namespace Firebelly\Media;

// Image sizes
add_action('after_setup_theme', __NAMESPACE__ . '\\set_image_sizes');
function set_image_sizes() {
  // Set default sizes
  update_option('medium_size_w', 600);
  update_option('medium_size_h', 0);
  update_option('medium_large_size_w', 1000);
  update_option('large_size_w', 1400);
  update_option('large_size_h', 0);

  // Custom banner size for headers
  add_image_size('banner', 1800, 850, true);
}

/**
 * Get the file path (not URL) to a thumbnail of a particular size.
 * (get_attached_file() only returns paths to full-sized thumbnails.)
 * @param  int            $thumb_id - attachment id of thumbnail
 * @param  string|array   $size - thumbnail size string (e.g. 'full') or array [w,h]
 * @return path           file path to properly sized thumbnail
 */
function get_thumbnail_size_path($thumb_id, $size) {
  // Find the path to the root image. We can get this from get_attached_file.
  $old_path = get_attached_file($thumb_id, true);

  // Find the url of the image with the proper size
  $attr = wp_get_attachment_image_src($thumb_id, $size);
  $url = $attr[0];

  // Grab the filename of the sized image from the url
  $exploded_url = explode('/', $url);
  $filename = $exploded_url[count($exploded_url)-1];

  // Replace the filename in our path with the filename of the properly sized image
  $exploded_path = explode('/', $old_path);
  $exploded_path[count($exploded_path)-1] = $filename;
  $new_path = implode ('/', $exploded_path);

  return $new_path;
}

/**
 * Get attachment ID from an image src
 * @param  string            $image_src (full URL of an image attachment)
 * @return int               ID of attachment
 */
function get_attachment_id_from_src($image_src) {
  global $wpdb;
  $id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE guid='%s'", $image_src));
  return $id;
}

/**
 * Get header bg for post (or category or file field), duotone-free
 * @param  string|object   $post_or_image (WP post object or background image)
 * @return HTML            background image code
 */
function get_header_bg($post_or_image, $opts=[]) {
  // Default options
  $opts = array_merge([
    'absolute_url' => false,
    'thumb_id' => '',
    'size' => 'banner',
    'output' => 'background',
  ], $opts);
  $header_bg = $background_image = false;
  $upload_dir = wp_upload_dir();

  // If WP post object, get the featured image
  if (is_object($post_or_image)) {
    if (has_post_thumbnail($post_or_image->ID)) {
      $opts['thumb_id'] = get_post_thumbnail_id($post_or_image->ID);
      $background_image = wp_get_attachment_image_src($opts['thumb_id'], $opts['size'])[0];
    }
  } else {
    // Absolute URLs, e.g. from a taxonomy page or other CMB2 file field
    if ($opts['thumb_id'] && is_numeric($opts['thumb_id'])) {
      // If thumb_id is sent, use that to get image
      $background_image = wp_get_attachment_image_src($opts['thumb_id'], $opts['size'])[0];
    } else if (preg_match('/^http/', $post_or_image)) {
      // If it's an absolute URL, make it relative
      $background_image = $upload_dir['baseurl'] . wp_make_link_relative($post_or_image);
    } else {
      // Not sure when this would ever happen...
      $background_image = $post_or_image;
    }
  }
  if ($background_image) {
    // Option to return commonly used style=background, or just filename
    if ($opts['output'] == 'background') {
      $header_bg = ' style="background-image:url(' . $background_image . ');"';
    } else {
      $header_bg = $background_image;
    }
  }
  return $header_bg;
}

/**
 * Get thumbnail image for post
 * @param  integer $post_id
 * @return string image URL
 */
function get_post_thumbnail($post_id, $size='medium') {
  $return = false;
  if (has_post_thumbnail($post_id)) {
    $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), $size);
    $return = $thumb[0];
  }
  return $return;
}

/**
 * Allow SVG files to be uplaoded via media uploader
 */
function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', __NAMESPACE__ . '\\cc_mime_types');

/**
 * Delete background images when attachment is deleted
 */
add_action('delete_attachment', __NAMESPACE__ . '\delete_background_images');
function delete_background_images($post_id) {
  // Get attachment image metadata
  $metadata = wp_get_attachment_metadata($post_id);
  if (!$metadata || empty($metadata['file']))
    return;

  $pathinfo = pathinfo($metadata['file']);
  $upload_dir = wp_upload_dir();
  $base_dir = trailingslashit($upload_dir['basedir']) . 'backgrounds/';
  $files = scandir($base_dir);

  foreach($files as $file) {
    // If filename matches background file, delete it
    if (strpos($file,$pathinfo['filename']) !== false) {
      @unlink($base_dir . '/' . $file);
    }
  }
}
