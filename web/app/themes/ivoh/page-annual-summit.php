<?php
/*
  Template name: Annual Summit
*/

$body = apply_filters('the_content', $post->post_content);
$post_meta = get_post_meta($post->ID, '');
?>
<?php get_template_part('templates/page', 'header'); ?>

<?php if (!empty($post_meta['_cmb2_date_start'])): ?>
  <?php
  $timestamp_start = strtotime($post_meta['_cmb2_date_start'][0]);
  $timestamp_end = strtotime($post_meta['_cmb2_date_end'][0]);
  ?>

  <div class="article-list page-section fb-container-content">
    <div class="-inner">
      <h4 class="h5">When</h4>
      <time class="date-start" datetime="<?= date('Y-m-d', $timestamp_start) ?>">
        <span class="day"><?= date('l', $timestamp_start) ?></span>
        <span class="date"><?= date('m / d / Y', $timestamp_start) ?></span>
      </time>
      –
      <time class="date-end" datetime="<?= date('Y-m-d', $timestamp_end) ?>">
        <span class="day"><?= date('l', $timestamp_end) ?></span>
        <span class="date"><?= date('m / d / Y', $timestamp_end) ?></span>
      </time>
    </div>
  </div>
<?php endif; ?>

<?php if (!empty($post_meta['_cmb2_address'])): ?>
  <div class="article-list page-section fb-container-content">
    <div class="-inner">
      <h4 class="h5">Where</h4>
      <p translate="no" typeof="schema:PostalAddress">
        <?php if (!empty($post_meta['_cmb2_venue'])): ?>
          <span property="schema:name"><?= $post_meta['_cmb2_venue'][0] ?></span><br>
        <?php endif; ?>

        <?php $address = unserialize($post_meta['_cmb2_address'][0]); ?>
        <span property="schema:streetAddress"><?= $address['address-1'] ?>
          <?php if (!empty($address['address-2'])): ?>
            <br><?= $address['address-2'] ?>
          <?php endif; ?>
        </span><br>
        <span property="schema:addressLocality"><?= $address['city'] ?></span>, <abbr property="schema:addressRegion"><?= $address['state'] ?></abbr> <span property="schema:postalCode"><?= $address['zip'] ?></span>
      </p>
    </div>
  </div>
<?php endif; ?>

<?php if (!empty($post_meta['_cmb2_address']) || !empty($post_meta['_cmb2_microsite_url'])): ?>
  <div class="article-list page-section fb-container-content">
    <div class="-inner">
      <?php if (!empty($post_meta['_cmb2_register_url'])): ?>
        <a rel="noopener" target="_blank" class="button" href="<?= $post_meta['_cmb2_register_url'][0] ?>">Register</a>
      <?php endif; ?>
      <?php if (!empty($post_meta['_cmb2_microsite_url'])): ?>
        <a rel="noopener" target="_blank" class="button" href="<?= $post_meta['_cmb2_microsite_url'][0] ?>">Microsite</a>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>

<div class="post-content user-content fb-container-content">
  <?= $body ?>
</div>
