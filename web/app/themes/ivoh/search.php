<?php
use Roots\Sage\Titles;
/*
  Template name: Search
*/
?>

<header class="page-header">
  <div class="page-header-text">
    <div class="-inner">
      <h4 class="breadcrumbs">Search Results</h4>
      <h1 class="page-title"><?= Titles\title() ?></h1>
    </div>
  </div>
</header>

<div class="page-search-form fb-container-md padded mobile-gutter ">
  <div class="-inner">
    <?php \Firebelly\Utils\get_template_part_with_vars('templates/searchform', null, ['search_title' => 'Search Again']); ?>
  </div>
</div>

<?php if (!have_posts()) : ?>
  <div class="alert fb-container-lg text-center">
    <?php _e('Sorry, no results were found.', 'sage'); ?>
  </div>
<?php else: ?>
  <h2 class="h4 mobile-gutter text-center">Results</h2>
  <div class="fb-container-md padded">
      <div class="card-grid">
      <div class="masonry md-halves lg-thirds -inner">
        <div class="grid-sizer"></div>
        <?php while (have_posts()) : the_post(); ?>
          <?php get_template_part('templates/content', 'search'); ?>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
<?php endif; ?>
