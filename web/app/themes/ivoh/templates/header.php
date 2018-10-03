<header class="site-header site-wrap" role="banner">
  <div class="container-md">
    <h1 class="site-logo"><a href="<?= esc_url(home_url('/')); ?>"><svg class="ivoh-logo"><use xlink:href="#logo"/></svg><span class="sr-only"><?php bloginfo('name'); ?></span></a></h1>
    <nav class="site-nav" role="navigation">
      <?php
      if (has_nav_menu('primary_navigation')) :
        wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']);
      endif;
      ?>
    </nav>
  </div>
</header>
