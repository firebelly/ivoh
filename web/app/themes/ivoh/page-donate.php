<?php
/*
  Template name: Donate
*/

$body = apply_filters('the_content', $post->post_content);
?>
<?php get_template_part('templates/page', 'header'); ?>

<div class="grid fb-container-md">
  <div class="card md-one-half">
    <div class="card-content">
      <h4 class="card-subtitle">Give Online</h4>

      <form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="donate-form" data-switch="single">
        <input type="hidden" name="cmd" value="_donations">
        <input type="hidden" name="business" value="<?= \Firebelly\SiteOptions\get_option('paypal_id'); ?>">
        <input type="hidden" name="item_name" value="Support ivoh">
        <input type="hidden" name="item_number" value="One Time Donation">
        <input type="hidden" name="currency_code" value="USD">
        <input type="hidden" name="no_shipping" value="1">

        <fieldset>
          <h3>Choose an Amount:</h3>
            <?php $amounts = get_post_meta($post->ID, '_cmb2_donation_amounts', true ); ?>
            <?php foreach ((array)$amounts as $amount): ?>
              <div class="form-row">
                <label class="control radio">
                <input type="radio" name="amount" value="<?= $amount ?>" required>
                <span class="control-indicator"></span>
                <span class="control-text">
                  <span class="amount">$<?= $amount ?></span>
                </span>
                </label>
              </div>
            <?php endforeach; ?>

          <label class="control radio other-amount">
            <input type="radio" name="amount" value="" required>
            <span class="control-indicator"></span>
            <span class="control-text">
              <div class="input-wrap">
                <input type="text" value="" pattern="[\d\.]*">
                <label>Other (Enter Amount)</label>
              </div>
            </span>
          </label>
        </fieldset>

        <button class="button">Complete Donation</button>
      </form>

    </div>
  </div>
  <div class="card md-one-half">
    <div class="card-content">
      <h4 class="card-subtitle">By Mail</h4>
      <h3 class="card-title"></h3>
      <div class="card-text user-content">
        <?= $body ?>
      </div>
    </div>
  </div>
</div>
