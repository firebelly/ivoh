<?php
// Post body
$body = apply_filters('the_content', $post->post_content);
// Sample PDFs
$sample_pdfs = get_post_meta($post->ID, '_cmb2_sample_pdfs', true);
$application_prompt = get_post_meta($post->ID, '_cmb2_application_prompt', true);
?>

<?php get_template_part('templates/page', 'header'); ?>

<hr>

<div class="page-section fb-container-content user-content">
  <?= $body ?>
</div>

<div class="article-list page-section fb-container-content">
  <div class="-inner">
    <?php if (!empty($sample_pdfs)): ?>
      <h3 class="h5">Sample PDFs:</h3>
      <ul>
        <?php foreach ((array)$sample_pdfs as $attachment_id => $attachment_url): ?>
          <?php $attachment_post = get_post($attachment_id); ?>
          <li><?= $attachment_post->post_title ?> <a download="<?= basename($attachment_url) ?>" href="<?= $attachment_url ?>">Download</a></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>
</div>

<div class="article-list page-section fb-container-content">
  <div class="-inner">
    <p><?= $application_prompt ?></p>
    <?= \Firebelly\Utils\get_template_part_with_vars('templates/form', 'tool-application', [ 'tool_post' => $post ]); ?>
  </div>
</div>
