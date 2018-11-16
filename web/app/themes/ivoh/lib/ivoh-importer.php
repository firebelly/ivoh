<?php
/**
 * Custom ivoh importer for news/stories from csv
 */

namespace Firebelly\Import;

// show link to import form
add_action('admin_menu', __NAMESPACE__.'\fb_schedule_admin_menu');
function fb_schedule_admin_menu() {
  // add_submenu_page('edit.php?post_type=story', 'Import CSV', 'Import CSV', 'publish_posts', 'schedule-importer', __NAMESPACE__.'\fb_csv_import_form');
  add_management_page( 'ivoh Importer', 'ivoh Importer', 'publish_posts', 'schedule-importer', __NAMESPACE__.'\fb_csv_import_form');
}
function fb_csv_import_form() {
  if ('POST' == $_SERVER['REQUEST_METHOD']) {
    $importer = new \Firebelly\Import\CSVImporter;
    if (!empty($_REQUEST['update-author-sort'])) {
      $importer->update_author_sort();
    } elseif (!empty($_REQUEST['update-date-featured'])) {
      $importer->update_date_featured();
    } else if (!empty($_REQUEST['convert-related-links'])) {
      $importer->convert_related();
    } else {
      $importer->handle_post();
    }
  }

?>
  <div class="wrap">
      <h2>ivoh Importer</h2>
      <form method="post" id="csv-upload-form" enctype="multipart/form-data">
          <fieldset>
            <label for="csv_import">Upload file(s):</label>
            <input name="csv_import[]" id="csv-import" type="file" multiple>
          </fieldset>
          <p class="submit">
            <input type="submit" class="button" name="submit" value="Import">

            <p class="submit">
              Other Tasks:
            <input type="submit" class="button" name="convert-related-links" value="Convert Related Links">
            &nbsp;
            <input type="submit" class="button" name="update-author-sort" value="Update Author Sort">
            &nbsp;
            <input type="submit" class="button" name="update-date-featured" value="Update Date Featured">
          </p>
      </form>
      <h3>Format:</h3>
      <textarea style="max-width: 100%; font-family: monospace; font-size: 12px;" cols="120" rows="8">
title,url,posttype,categories,author
'The Gun Show' triggers a conversation of gun violence in America,http://ivoh.org/gun-show-triggers-conversation-gun-violence-america/,Strengths-based media,"Art + Design, Violence, Health + Health Care, Politics, Culture + Community",Celeste Hamilton Dennis
The media's 'failure' presents an opportunity in the aftermath of Trump's presidential win,http://ivoh.org/media-bridge-divides-post-election-coverage/,News + Commentary,"Media, Politics",
etc...
      </textarea>
  </div>
<?php
}

class CSVImporter {
  var $defaults = array(
    'title'      => null,
    'url'        => null,
    'posttype'   => null,
    'categories' => null,
    'author'     => null,
  );

  var $log = array();
  var $ajax = false;
  var $categories;
  var $storyTopics;
  var $storyTypes;
  var $oldDb = null;

  function printMessages() {
      if (!$this->ajax && !empty($this->log)) {

      // messages HTML {{{
?>

<div class="wrap">
  <?php if (!empty($this->log['error'])): ?>

  <div class="error">

      <?php foreach ($this->log['error'] as $error): ?>
          <p><?php echo $error; ?></p>
      <?php endforeach; ?>

  </div>

  <?php endif; ?>

  <?php if (!empty($this->log['notice'])): ?>

  <div class="updated">

      <?php foreach ($this->log['notice'] as $notice): ?>
          <p><?php echo $notice; ?></p>
      <?php endforeach; ?>

  </div>

  <?php endif; ?>
</div><!-- end wrap -->

<?php
      // end messages HTML }}}

          $this->log = array();
      }
  }

  /**
   * Update author_sort
   *
   * @return void
   */
  function update_author_sort($ajax=false) {
    $posts = get_posts(['post_type' => ['story','post'], 'numberposts' => -1]);
    echo '<h2>Updating _author_sort</h2>';
    foreach ($posts as $post) {
      $author_sort = \Firebelly\PostTypes\Story\update_author_sort_meta($post->ID);
      echo '<li>Post #'.$post->ID.' '.$post->post_title.' updated _author_sort to <strong>'.$author_sort.'</strong></li>';
    }
  }

  /**
   * Update date_featured
   *
   * @return void
   */
  function update_date_featured($ajax=false) {
    $posts = get_posts(['post_type' => ['story','post'], 'numberposts' => -1]);
    echo '<h2>Updating _date_featured</h2>';
    foreach ($posts as $post) {
      $date_featured = get_post_meta($post->ID, '_date_featured', true);
      if (empty($date_featured)) $date_featured = 0;
      update_post_meta($post->ID, '_date_featured', $date_featured);
      echo '<li>Post #'.$post->ID.' '.$post->post_title.' updated _date_featured to <strong>'.$date_featured.'</strong></li>';
    }
  }

  /**
   * Convert all old Related Links -> stuctured Related Posts (if post is found)
   *
   * @return void
   */
  function convert_related($ajax=false) {
    global $wpdb;
    $posts = get_posts(['post_type' => 'any', 'numberposts' => -1]);
    $matched = $nomatched = 0;
    foreach ($posts as $post) {
      $related_links = get_post_meta($post->ID, '_cmb2_related_links', true);
      if($related_links) {
        // Clear out related posts in case this is run multiple times
        delete_post_meta($post->ID, '_cmb2_related_posts');

        echo '<hr><a href="'.get_permalink($post).'">'.$post->post_title.'</a><hr>';

        // Find all links in field
        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $related_links, $match);
        foreach($match[0] as $url) {
          $slug = str_replace(['http://ivoh.org/','/','%20'],['','',''],$url);
          $id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = %s", $slug));
          // Found a post? Let's associate it
          if ($id) {
            echo '<li>'.$slug.' matched to '.$id.'</li>';
            add_post_meta($post->ID, '_cmb2_related_posts', $id);
            $matched++;
          } else {
            echo '<li><strong>'.$slug.' not matched!</strong></li>';
            $nomatched++;
          }
        }
      }
    }
    echo "<h2>matched: $matched, not matched: $nomatched</h2>";
  }

  /**
   * Handle POST submission
   *
   * @return void
   */
  function handle_post($ajax=false) {
    global $wpdb;
    $this->ajax = $ajax;
    // rejigger HTML5 multiple file upload array format
    $files = [];
    $fdata = $_FILES['csv_import'];
    if (!empty($fdata)) {
      if ( is_array($fdata['name']) ) {
        for ($i = 0; $i<count($fdata['name']); ++$i) {
          if (!empty($fdata['name'][$i])) {
            $files[] = array(
              'name'     => $fdata['name'][$i],
              'type'     => $fdata['type'][$i],
              'tmp_name' => $fdata['tmp_name'][$i],
              'error'    => $fdata['error'][$i],
              'size'     => $fdata['size'][$i]
              );
          }
        }
      } else $files[] = $fdata;
    }

    if (empty($files)) {
      $this->log['error'][] = 'No file specified for import.';
      $this->printMessages();
      return $this->log;
    }

    if (!current_user_can('publish_pages') || !current_user_can('publish_posts')) {
      $this->log['error'][] = 'You don\'t have the permissions to publish posts and pages.';
      $this->printMessages();
      return $this->log;
    }

    // Connect to old db
    try {
        $this->oldDb = new \PDO('mysql:host='.getenv('OLD_DB_HOST').';dbname='.getenv('OLD_DB_NAME').';charset=utf8', getenv('OLD_DB_USER'), getenv('OLD_DB_PASSWORD'));
        $this->oldDb->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch(\PDOException $e) {
        die('PDO Error: ' . $e->getMessage());
    }

    $time_start = microtime(true);
    $i = $skipped = $imported = 0;

    // Cache terms
    $this->categories = get_terms('category', array('hide_empty' => 0));
    $this->storyTopics = get_terms('story_topic', array('hide_empty' => 0));
    $this->storyTypes = get_terms('story_type', array('hide_empty' => 0));

    foreach($files as $fileUpload) {

      $file = $fileUpload['tmp_name'];

      // Map csv headers to associative array
      $csv = array_map('str_getcsv', file($file));
      array_walk($csv, function(&$a) use ($csv) {
        $a = array_combine($csv[0], $a);
      });
      array_shift($csv); // remove column header

      if (empty($csv)) {
        $this->log['error'][] = 'Failed to load file, aborting.';
        $this->printMessages();
        return $this->log;
      }

      foreach ($csv as $csvRow) {
        if ($postId = $this->createPost($csvRow)) {
            $imported++;
        } else {
            $skipped++;
        }
      }

      // remove temp upload file
      if (file_exists($file)) {
        @unlink($file);
      }
      $i++;
    }

    $exec_time = microtime(true) - $time_start;
    if ($skipped) {
      $this->log['notice'][] = "<b>Skipped {$skipped} entries.</b>";
    }
    $this->log['notice'][] = sprintf("<b>Imported %s entries in %.2f seconds.</b>", $imported, $exec_time);
    $this->log['stats']['entries'] = $imported;
    $this->log['stats']['exec_time'] = sprintf("%.2f", $exec_time);
    $this->printMessages();
    return $this->log;
  }

  function createPost($data) {
    global $wpdb;

    $data = array_merge($this->defaults, $data);

    $postType = ($data['posttype'] == 'News + Commentary') ? 'post' : 'story';

    // Check if post exists
    $existing_post = get_page_by_title($data['title'], OBJECT, $postType);

    if (!empty($existing_post)) {
      $this->log['error'][] = "<a target=\"_blank\" href=\"".get_permalink($existing_post)."\">{$data['title']}</a> already exists. <a target=\"_blank\" href=\"".get_edit_post_link($existing_post)."\">Edit</a>";

      return false;
    }

    // Find old post by title
    $query = $this->oldDb->prepare('SELECT * FROM wp_posts WHERE post_title LIKE ? AND post_status = ?');
    $query->execute([ '%'.$data['title'].'%', 'publish' ]);
    $oldPost = $query->fetch(\PDO::FETCH_OBJ);

    // Old post found?
    if (!empty($oldPost)) {

      // Terms to assign to post
      $termIds = [];

      // Split all categories from CSV field
      $postCategories = array_map('trim', explode(',', $data['categories']));

      // Find/insert all categories and store ids for assigning to post after insert
      if ($postType == 'post') {
        // News post?
        $taxonomy = 'category';
        foreach ($postCategories as $categoryName) {
          $term_id = '';
          foreach($this->categories as $term) {
            if (strtolower($term->name) == strtolower($categoryName)) {
              $term_id = $term->term_id;
            }
          }
          // New term?
          if (empty($term_id)) {
            $newTerm = wp_insert_term($categoryName, $taxonomy);
            if(is_wp_error($newTerm)) {
              $this->log['error'][] = $categoryName . ' had an error inserting: ' . $newTerm->get_error_message();
            } else {
              $term_id = $newTerm['term_id'];
              $this->categories = get_terms('category', array('hide_empty' => 0));
            }
          }
          // Add to cat array for post
          if (!empty($term_id)) {
            $termIds[] = $term_id;
          }
        }
      } else {
        // Story post
        $taxonomy = 'story_topic';
        foreach ($postCategories as $categoryName) {
          $term_id = '';
          foreach($this->storyTopics as $term) {
            if (strtolower($term->name) == strtolower($categoryName)) {
              $term_id = $term->term_id;
            }
          }
          // New term?
          if (empty($term_id)) {
            $newTerm = wp_insert_term($categoryName, $taxonomy);
            if(is_wp_error($newTerm)) {
              $this->log['error'][] = $categoryName . ' had an error inserting: ' . $newTerm->get_error_message();
            } else {
              $term_id = $newTerm['term_id'];
              $this->storyTopics = get_terms('story_topic', array('hide_empty' => 0));
            }
          }
          // Add to cat array for post
          if (!empty($term_id)) {
            $termIds[] = $term_id;
          }
        }
      }

      // Massage the content a bit...
      $post_content = $oldPost->post_content;

      // Update image references to be relative
      $post_content = str_replace('http://ivoh.org/wp-content/uploads/', '/app/uploads/', $post_content);

      // Remove spans
      $post_content = preg_replace('#</?span([^>]+)?>#i', '', $post_content);

      // Remove extra spaces
      $post_content = str_replace('&nbsp;', '', $post_content);

      // Strip image size and just use source image, in case old image size is used that doesn't exist any more
      $post_content = preg_replace('#\-([\d{2-4}]+)x([\d{2-4}]+)\.(jpg|jpeg|gif|png)#', '.$3', $post_content);

      // Strip out related links at bottom of posts
      $related_links = '';
      if (preg_match('#((<(b|strong)>)?Related:(</(b|strong)>)?(.*))#', $post_content, $m)) {
        $related_links = trim($m[6]);
        $post_content = trim(str_replace($m[0], '', $post_content));
      }

      // Insert post
      $post_args = [
        'post_title'   => $data['title'],
        'post_name'    => $oldPost->post_name,
        'post_status'  => $oldPost->post_status,
        'post_content' => $post_content,
        'post_date'    => $oldPost->post_date,
        'post_type'    => $postType,
        'post_author'  => get_current_user_id(),
      ];
      $id = wp_insert_post($post_args);

      // Find old featured photo and assign it if possible
      $query = $this->oldDb->prepare('SELECT * FROM wp_postmeta WHERE post_id = ? AND meta_key = ?');
      $query->execute([ $oldPost->ID, '_thumbnail_id' ]);
      $oldFeaturedImageMeta = $query->fetch(\PDO::FETCH_OBJ);
      if (!empty($oldFeaturedImageMeta)) {
        $query = $this->oldDb->prepare('SELECT * FROM wp_posts WHERE ID = ?');
        $query->execute([ $oldFeaturedImageMeta->meta_value ]);
        $oldFeaturedImage = $query->fetch(\PDO::FETCH_OBJ);

        // Get filename from guid omitting everything up to /uploads/etc
        $filename = preg_replace('#(.*)(/uploads.*)$#', '$2', $oldFeaturedImage->guid);

        // Remove image size from filename (e.g. -200x200), also remove extension for better fuzzy matching
        $filename = preg_replace('#(\-([\d{2-4}]+)x([\d{2-4}]+))?\.(jpg|jpeg|gif|png)#', '', $filename);

        // See if we can find it in media library
        $newFeaturedImagePost = $wpdb->get_var($wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE guid LIKE %s", '%' . $filename . '%'));
        if (!empty($newFeaturedImagePost)) {
          set_post_thumbnail($id, $newFeaturedImagePost);
        }
      }

      // Assign categories to new post
      if (!empty($termIds)) {
        wp_set_object_terms($id, $termIds, $taxonomy);
      }

      // Find or create author
      if (!empty($data['author'])) {
        $author_post = get_page_by_title($data['author'], OBJECT, 'person');
        if (empty($author_post)) {
          $author_post_id = wp_insert_post([
            'post_title'  => $data['author'],
            'post_status' => 'publish',
            'post_type'   => 'person',
          ]);
        } else {
          $author_post_id = $author_post->ID;
        }
      } else {
        $author_post_id = '';
      }

      // Assign author to new post
      if (!empty($author_post_id)) {
        add_post_meta($id, '_cmb2_author', $author_post_id);
      }

      // Assign author to new post
      if (!empty($related_links)) {
        add_post_meta($id, '_cmb2_related_links', trim($related_links));
      }

      // If story, assign story_type
      if ($postType == 'story') {
        foreach($this->storyTypes as $term) {
          if (strpos(strtolower($term->name), strtolower($data['posttype'])) !== false) {
            wp_set_object_terms($id, [$term->term_id], 'story_type');
          }
        }
      }

      $this->log['notice'][] = "<a target=\"_blank\" href=\"".get_permalink($id)."\">{$data['title']}</a> {$postType} added OK! <a target=\"_blank\" href=\"".get_edit_post_link($id)."\">Edit</a>";
      return $id;

    } else {

      $this->log['error'][] = $data['title'] . ' was not found in the old db.';
      return false;

    }
  }
}
