<?php
$research_url = get_post_meta($research_post->ID, '_cmb2_research_url', true);
$research_description = get_post_meta($research_post->ID, '_cmb2_description', true);
?>
<article class="research">
  <h2 class="article-title"><a rel="noopener" target="_blank" href="<?= $research_url ?>"><?= $research_post->post_title ?></a></h2>
  <?php if (!empty($research_description)): ?>
  	<p class="article-action"><a class="read-description" href="#">Read More</a></p>
    <p class="description"><?= $research_description ?></p>
  <?php endif; ?>
</article>
