<?php
/*
  Template name: Annual Summit
*/

$body = apply_filters('the_content', $post->post_content);
$post_meta = get_post_meta($post->ID, '');
?>
<?php get_template_part('templates/page', 'header'); ?>

<div class="fb-container-content patterned-sm">

  <div class="summit-card inherit-background">
    <?php if (!empty($post_meta['_cmb2_date_start'])): ?>
      <?php
      $timestamp_start = strtotime($post_meta['_cmb2_date_start'][0]);
      $timestamp_end = strtotime($post_meta['_cmb2_date_end'][0]);
      ?>

      <div class="when card-section">
        <h4 class="h5">When</h4>
        <div class="-inner">
          <time class="date-start inherit-background" datetime="<?= date('Y-m-d', $timestamp_start) ?>">
            <span class="day"><?= date('l', $timestamp_start) ?></span>
            <span class="date"><?= date('m / d / Y', $timestamp_start) ?></span>
          </time>
          <time class="date-end inherit-background" datetime="<?= date('Y-m-d', $timestamp_end) ?>">
            <span class="day"><?= date('l', $timestamp_end) ?></span>
            <span class="date"><?= date('m / d / Y', $timestamp_end) ?></span>
          </time>
        </div>
      </div>
    <?php endif; ?>

    <?php if (!empty($post_meta['_cmb2_address'])): ?>
      <div class="where card-section">
        <h4 class="h5">Where</h4>
        <p translate="no" typeof="schema:PostalAddress">
          <?php if (!empty($post_meta['_cmb2_venue'])): ?>
            <span property="schema:name"><?= $post_meta['_cmb2_venue'][0] ?></span><br>
          <?php endif; ?>

          <?php $address = unserialize($post_meta['_cmb2_address'][0]); ?>
          <?php if (!empty($address['address-1'])): ?>
            <span property="schema:streetAddress">
              <?php if (!empty($address['address-2'])): ?>
                <?= $address['address-1'] ?>
              <?php endif; ?>
              <?php if (!empty($address['address-2'])): ?>
                <br><?= $address['address-2'] ?>
              <?php endif; ?>
            </span><br>
          <?php endif; ?>
          <span property="schema:addressLocality"><?= $address['city'] ?></span>, <abbr property="schema:addressRegion"><?= $address['state'] ?></abbr>
          <?php if (!empty($address['zip'])): ?>
            <span property="schema:postalCode"><?= $address['zip'] ?></span>
          <?php endif; ?>
        </p>
      </div>
    <?php endif; ?>

    <?php if (!empty($post_meta['_cmb2_address']) || !empty($post_meta['_cmb2_microsite_url'])): ?>
      <div class="links card-section">
        <?php if (!empty($post_meta['_cmb2_register_url'])): ?>
          <a rel="noopener" target="_blank" class="button" href="<?= $post_meta['_cmb2_register_url'][0] ?>">Register</a>
        <?php endif; ?>
        <?php if (!empty($post_meta['_cmb2_microsite_url'])): ?>
          <a rel="noopener" target="_blank" class="button" href="<?= $post_meta['_cmb2_microsite_url'][0] ?>"><?= !empty($post_meta['_cmb2_microsite_text']) ? $post_meta['_cmb2_microsite_text'][0] : 'Microsite' ?></a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>

</div>

<div class="post-content user-content fb-container-content">
  <?= $body ?>
</div>
