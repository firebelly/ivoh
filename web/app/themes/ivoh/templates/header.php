<header class="site-header site-wrap" role="banner">
  <div class="-inner fb-container-lg">
    <h1 class="site-logo"><a href="<?= esc_url(home_url('/')); ?>"><svg class="ivoh-logo"><use xlink:href="#logo"/></svg><span class="sr-only"><?php bloginfo('name'); ?></span></a></h1>
    <nav class="site-nav" role="navigation">
      <?php
      if (has_nav_menu('primary_navigation')) :
        wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'primary-nav']);
      endif;
      ?>

      <div class="secondary-nav-container">
        <?php
        if (has_nav_menu('secondary_navigation')) :
          wp_nav_menu(['theme_location' => 'secondary_navigation', 'menu_class' => 'secondary-nav']);
        endif;
        ?>

        <button class="search-toggle button circular" data-active-toggle="#search"><span class="sr-only">Search</span><svg class="icon icon-search" aria-hidden="true" role="presentation"><use xlink:href="#icon-search"/></svg></button>

      </div>

      <?php get_search_form(); ?>
    </nav>
  </div>

</header>