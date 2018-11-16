<header class="site-header" role="banner">
  <div class="-inner fb-container-md padded">
    <h1 class="site-logo"><a href="<?= esc_url(home_url('/')); ?>"><svg class="ivoh-logo"><use xlink:href="#logo"/></svg><span class="sr-only"><?php bloginfo('name'); ?></span></a></h1>
    <nav id="site-nav" class="site-nav" role="navigation">
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

        <button class="search-toggle button circular"><span class="sr-only">Search</span><svg class="icon icon-search" aria-hidden="true" role="presentation"><use xlink:href="#icon-search"/></svg></button>

      </div>

      <div id="header-search" class="search-form-container">
        <?php \Firebelly\Utils\get_template_part_with_vars('templates/searchform', null, ['search_title' => 'Search']); ?>
      </div>
    </nav>
  </div>

</header>
