<?php
// Post body
$body = apply_filters('the_content', $post->post_content);
// Sample PDFs
$sample_pdfs = get_post_meta($post->ID, '_cmb2_sample_pdfs', true);
$application_prompt = get_post_meta($post->ID, '_cmb2_application_prompt', true);
?>

<?php get_template_part('templates/page', 'header'); ?>

<div class="fb-container-md">
  <hr>
</div>

<div class="page-section fb-container-content user-content">
  <?= $body ?>
</div>

<div class="fb-container-md padded mobile-gutter page-section patterned-sm grid">
  <div class="article-list md-one-half">
    <div class="-inner inherit-background">
      <?php if (!empty($sample_pdfs)): ?>
        <h3 class="list-title">Sample PDFs:</h3>
        <ul>
          <?php foreach ((array)$sample_pdfs as $attachment_id => $attachment_url): ?>
            <?php $attachment_post = get_post($attachment_id); ?>
            <li class="article with-icon">
              <a download="<?= basename($attachment_url) ?>" href="<?= $attachment_url ?>">
                <h4 class="article-title"><?= $attachment_post->post_title ?></h4>
                <p class="article-action"><span>Download</span></p>
                <svg class="icon icon-downloadable" aria-hidden="true" role="presentation"><use xlink:href="#icon-downloadable"/></svg>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>

  <div class="form-section card md-one-half">
    <div class="card-content">
      <p class="card-title"><?= $application_prompt ?></p>
      <?= \Firebelly\Utils\get_template_part_with_vars('templates/form', 'tool-application', [ 'tool_post' => $post ]); ?>
    </div>
  </div>
</div>