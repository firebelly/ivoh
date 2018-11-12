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
        <form action="https://ivoh.us3.list-manage.com/subscribe/post-json?u=32b28d37ee5aa11c0826a50d4&amp;id=b584ca2f19&amp;c=?" method="post" name="mc-embedded-subscribe-form" class="newsletter" id="newsletter">
          <div class="form-row grid spaced">
            <div class="input-wrap one-half">
              <input type="text" name="FNAME" required>
              <label for="NAMEFIRST">First Name</label>
            </div>
            <div class="input-wrap one-half">
              <input type="text" name="LNAME" required>
              <label for="NAMELAST">Last Name</label>
            </div>
          </div>
          <div class="form-row">
            <div class="input-wrap">
              <input type="email" name="EMAIL" required>
              <label for="EMAIL">Email Address</label>
            </div>
          </div>
          <div class="form-row">
            <div class="status"></div>

            <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_32b28d37ee5aa11c0826a50d4_b584ca2f19" tabindex="-1" value=""></div>
            <button type="submit" class="button">Subscribe</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
