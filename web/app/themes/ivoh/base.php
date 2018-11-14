<?php
use Roots\Sage\Setup;
use Roots\Sage\Wrapper;

// Default page color
$page_color = 'pink';

// Determine if we can get a page color override
if (is_search()) {
  $top_ancestor = \Firebelly\Utils\get_page_id_by_slug('search');
} elseif (is_home() || (!empty($post) && $post->post_type == 'post')) {
  // News posts
  $top_ancestor = get_option('page_for_posts');
} elseif (!empty($post) && $post->post_type == 'story') {
  // Story posts
  $top_ancestor = \Firebelly\Utils\get_page_id_by_slug('story-bank');
} elseif (!empty($post) && $post->post_type == 'person') {
  // Person posts
  $person_type = \Firebelly\Utils\get_first_term($post, 'person_category');
  if (empty($person_type) || strpos($person_type->name, 'Fellows') !== false) {
    $top_ancestor = \Firebelly\Utils\get_page_id_by_slug('what-we-do');
  } else {
    $top_ancestor = \Firebelly\Utils\get_page_id_by_slug('who-we-are');
  }
} elseif (!empty($post) && $post->post_type == 'tool') {
  // Tool posts
  $top_ancestor = \Firebelly\Utils\get_page_id_by_slug('resources');
} else {
  // Normal page
  $top_ancestor = \Firebelly\Utils\get_top_ancestor($post);
}
// Now that we have top ancestor, see if a page color has been set
if (!empty($top_ancestor) && $page_color_override = get_post_meta($top_ancestor, '_cmb2_page_color', true)) {
  $page_color = $page_color_override;
}
?>

<!doctype html>
<!--[if IE 8]> <html class="no-js ie8 lt-ie9 lt-ie10" lang="en"> <![endif]-->
<!--[if IE 9 ]> <html class="no-js ie9 lt-ie10" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
  <?php get_template_part('templates/head'); ?>
  <body <?php body_class(); ?> data-theme="<?= $page_color ?>">
    <div id="breakpoint-indicator"></div>
    <!--[if lt IE 9]>
      <div class="alert alert-warning">
        <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'sage'); ?>
      </div>
    <![endif]-->
    <?php
      do_action('get_header');
      get_template_part('templates/header');
    ?>

    <main class="site-main" role="main">
      <?php include Wrapper\template_path(); ?>
    </main><!-- /.main -->

    <?php
      do_action('get_footer');
      get_template_part('templates/footer');
      wp_footer();
    ?>

    <?php if (WP_ENV === 'development'): ?>
    <script id="__bs_script__">//<![CDATA[
        document.write("<script async src='http://HOST:3000/browser-sync/browser-sync-client.js?v=2.26.3'><\/script>".replace("HOST", location.hostname));
    //]]></script>
    <?php endif; ?>
  </body>
</html>
