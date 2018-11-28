<?php
/*
  Template name: Donate
*/
$by_mail_prompt = get_post_meta($post->ID, '_cmb2_donation_bymail', true);
$donation_thankyou = get_post_meta($post->ID, '_cmb2_donation_thankyou', true);
?>

<?php if (!empty($_REQUEST['success']) && !empty($donation_thankyou)): ?>
  <div class="page-section fb-container-md padded mobile-gutter patterned-sm user-content">
    <div class="card header-notice">
      <?= apply_filters('the_content', $donation_thankyou) ?>
    </div>
  </div>
<?php endif; ?>

<?php get_template_part('templates/page', 'header'); ?>

<?php if (!empty(trim($post->post_content))): ?>
<div class="post-content user-content fb-container-content">
  <?= apply_filters('the_content', $post->post_content) ?>
</div>
<?php endif; ?>

<div class="grid fb-container-md padded mobile-gutter patterned-sm">
  <div class="card md-one-half">
    <div class="card-content">
      <h4 class="card-subtitle">Give Online</h4>

      <form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="donate-form text-left" data-switch="single">
        <input type="hidden" name="cmd" value="_donations">
        <input type="hidden" name="business" value="<?= \Firebelly\SiteOptions\get_option('paypal_id'); ?>">
        <input type="hidden" name="item_name" value="Support ivoh">
        <input type="hidden" name="item_number" value="One Time Donation">
        <input type="hidden" name="currency_code" value="USD">
        <input type="hidden" name="no_shipping" value="1">
        <input type="hidden" name="return" value="<?= getenv('WP_HOME').'/donate/?success=1' ?>" />
        <input type="hidden" name="cancel_return" value="<?= getenv('WP_HOME').'/donate/' ?>" />

        <fieldset>
          <h3 class="p1">Choose an Amount:</h3>
            <?php $amounts = get_post_meta($post->ID, '_cmb2_donation_amounts', true ); ?>
            <div class="form-row">
            <?php foreach ((array)$amounts as $amount): ?>
              <label class="control radio">
                <input type="radio" name="amount" value="<?= $amount ?>" required>
                <span class="control-indicator"></span>
                <span class="control-text">
                  <span class="amount">$<?= $amount ?></span>
                </span>
              </label>
            <?php endforeach; ?>
            </div>

          <label class="control radio other-amount">
            <div class="input-wrap">
              <input type="text" value="" pattern="[\d\.]*">
              <label>Other (Enter Amount)</label>
            </div>
            <input type="radio" name="amount" value="" required>
          </label>
        </fieldset>

        <div class="form-row text-center">
          <button class="button submit">Complete Donation</button>
        </div>
      </form>

    </div>
  </div>
  <div class="donate-by-mail card md-one-half">
    <div class="card-content">
      <h4 class="card-subtitle">By Mail</h4>
      <div class="card-text user-content text-left">
        <?= apply_filters('the_content', $by_mail_prompt) ?>
      </div>
    </div>
  </div>
</div>
