<?php
use Roots\Sage\Titles;
/*
  Template name: Search
*/
?>

<header class="page-header">
  <div class="page-header-text">
    <div class="-inner">
      <h4 class="breadcrumbs">Error 404</h4>
      <h1 class="page-title"><?= Titles\title() ?></h1>
      <p class="page-intro-body">Sorry, but the page you were trying to view does not exist.</p>
    </div>
  </div>
</header>

<div class="fb-container-md padded mobile-gutter">
  <hr>
</div>

<div class="page-search-form">
  <?php get_search_form(); ?>
</div>
