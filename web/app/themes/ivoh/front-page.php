<?php
/*
  Template name: Homepage
*/
?>

<div class="site-wrap">
	<h2>Featured</h2>
  <div class="stories fb-container-md">
    <?php echo do_shortcode('[story_carousel type=all]'); ?>
  </div>

	<h2>News & Commentary</h2>
	<?php // todo: shove any news posts marked featured to top of list (sticky behavior) ?>
	<?= \Firebelly\Utils\get_posts([
		'numberposts' => 3,
	]); ?>
</div>