<?php

use Roots\Sage\Setup;
use Roots\Sage\Wrapper;

?>

<!doctype html>
<!--[if IE 8]> <html class="no-js ie8 lt-ie9 lt-ie10" lang="en"> <![endif]-->
<!--[if IE 9 ]> <html class="no-js ie9 lt-ie10" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
  <?php get_template_part('templates/head'); ?>
  <body <?php body_class(); ?>>
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
    <div class="site-wrap" role="document">

      <main class="site-main" role="main">
        <?php include Wrapper\template_path(); ?>
      </main><!-- /.main -->

    </div><!-- /.site-wrap -->

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
