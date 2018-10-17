<?php use Roots\Sage\Titles; ?>

<header class="page-header">
  <div class="page-header-text">
    <!-- if child page
    <ul class="breadcrumbs">
      <li><a href="#">Breadcrumb One</a></li>
      <li><a href="#">Breadcrumb Two</a></li>
      <li><a href="#">Breadcrumb Two</a></li>
      <li><a href="#">Breadcrumb Two</a></li>
      <li><a href="#">Breadcrumb Two</a></li>
      <li><a href="#">Breadcrumb Two</a></li>
    </ul>
  -->
    <h1 class="page-title"><?= Titles\title(); ?></h1>
    <!-- if page-description
    <p class="page-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nemo a aliquid veritatis quod esse itaque alias iusto ab enim, quam vitae recusandae aperiam explicabo placeat earum totam, doloribus dignissimos suscipit.</p>
    -->
  </div>
  
  <?php if (has_post_thumbnail($post)) { ?>
  <div class="page-header-banner bordered patterned">
    <div class="banner-image-container background-blend fb-container-lg">
      <div class="banner-image" style="background-image:url('<?= \Firebelly\Media\get_post_thumbnail($post->ID, 'banner'); ?>');"></div>
    </div>
  </div>
  <?php } ?>
</header>
