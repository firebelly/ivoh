<?php
/*
  Template name: Homepage
*/
?>

<div class="site-wrap">
  <div class="fb-container-md">  
  	<h2 class="h1 text-center">Featured</h2>
    <div class="stories mobile-gutter patterned">
      <?php echo do_shortcode('[story_carousel type=all]'); ?>
    </div>
  </div>

  <div class="fb-container-md">  
  	<h2 class="h3 text-center">News & Commentary</h2>
    <div class="card-grid">
      <div class="mobile-gutter">      
      	<?php // todo: shove any news posts marked featured to top of list (sticky behavior) ?>
      	<?= \Firebelly\Utils\get_posts([
      		'numberposts' => 3,
      	]); ?>
      </div>    
    </div>
  </div>
</div>