<div class="site-pre-footer">
  <div class="fb-container-md grid">

    <div id="support-us" class="-item md-one-half accordion expanded-md">
      <h3 class="accordion-toggle"><span class="-inner">Support Us<button class="expand-contract"><span class="icon plus-minus"></span></button></span></h3>
      <div class="accordion-content user-content">
        <?= apply_filters('the_content', \Firebelly\SiteOptions\get_option('support_us_statement')); ?>
        <p class="action"><a href="/donate/" class="button">Donate</a></p>
      </div>
    </div>

    <div id="subscribe" class="md-one-half -item accordion expanded-md">
      <h3 class="accordion-toggle"><span class="-inner">Subscribe to our Newsletter<button class="expand-contract"><span class="icon plus-minus"></span></button></span></h3>
      <div class="accordion-content">
        <form action="" id="newsletter" class="newsletter-form">
          <div class="form-row grid spaced">
            <div class="input-wrap one-half">
              <input type="text" name="NAMEFIRST">
              <label for="NAMEFIRST">First Name</label>
            </div>
            <div class="input-wrap one-half">
              <input type="text" name="NAMELAST">
              <label for="NAMELAST">Last Name</label>
            </div>
          </div>
          <div class="form-row">
            <div class="input-wrap">
              <input type="email" name="EMAILADDRESS">
              <label for="EMAILADDRESS">Email Address</label>
            </div>
          </div>
          <div class="form-row">
            <button type="submit" class="button">Subscribe</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>