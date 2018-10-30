<?php
$research_url = get_post_meta($research_post->ID, '_cmb2_research_url', true);
$research_description = get_post_meta($research_post->ID, '_cmb2_description', true);
// External link?
$external_link = (!preg_match('/ivoh\.org/', $research_url) && preg_match('/^http/', $research_url)) ? 'rel="noopener" target="_blank" ' : '';
?>
<article class="research">
  <h2 class="article-title"><a <?= $external_link ?>href="<?= $research_url ?>"><?= $research_post->post_title ?></a></h2>
  <?php if (!empty($research_description)): ?>
  	<p class="article-action"><a class="read-description" href="#">Read More</a></p>
    <p class="description"><?= $research_description ?></p>
  <?php endif; ?>
</article>
