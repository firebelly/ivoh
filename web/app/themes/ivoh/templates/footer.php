<?php
$slug = !empty($post) ? $post->post_name : '';
if (!is_search() && $slug !== 'donate') {
  get_template_part('templates/pre-footer');
}

// Pull fields that we need to check for being populated
$contact_address_2 = \Firebelly\SiteOptions\get_option('contact_address_2');
$contact_phone = \Firebelly\SiteOptions\get_option('contact_phone');
$contact_email = \Firebelly\SiteOptions\get_option('contact_email');
?>

<footer class="site-footer patterned" role="contentinfo">
  <div class="site-wrap">
    <div class="-inner fb-container-md grid">

      <div class="-column-one">
        <h1 class="site-logo"><a href="<?= esc_url(home_url('/')); ?>"><svg class="ivoh-logo"><use xlink:href="#logo"/></svg><span class="sr-only"><?php bloginfo('name'); ?></span></a></h1>
      </div>

      <div class="-column-two">
        <div class="contact">
          <div translate="no" typeof="schema:PostalAddress">
            <p class="address">
              <span property="schema:streetAddress"><?= \Firebelly\SiteOptions\get_option('contact_address'); ?>
              <?php if (!empty($contact_address_2)): ?>
                <br><?= $contact_address_2 ?>
              <?php endif ?>
              <br><span property="schema:addressLocality"><?= \Firebelly\SiteOptions\get_option('contact_locality'); ?></span>, <abbr property="schema:addressRegion"><?= \Firebelly\SiteOptions\get_option('contact_region'); ?></abbr> <span property="schema:postalCode"><?= \Firebelly\SiteOptions\get_option('contact_zip'); ?></span>
            </p>
            <?php if (!empty($contact_phone)): ?>
              <a href="tel:<?= $contact_phone ?>"> <span property="schema:telephone" class="phone"><?= $contact_phone ?></a>
            <?php endif; ?>
            <?php if (!empty($contact_email)): ?>
              <a href="mailto: <?= $contact_email ?>" class="email" property="schema:email"><?= $contact_email ?></a>
            <?php endif; ?>
          </div>
        </div>
        <ul class="social-media-links">
          <li><a rel="noopener" target="_blank" href="<?= \Firebelly\SiteOptions\get_option('facebook_url'); ?>" class="button circular"><svg class="icon icon-facebook" aria-hidden="true" role="presentation"><use xlink:href="#icon-facebook"/></svg></a></li>
          <li><a rel="noopener" target="_blank" href="<?= \Firebelly\SiteOptions\get_option('twitter_url'); ?>" class="button circular"><svg class="icon icon-twitter" aria-hidden="true" role="presentation"><use xlink:href="#icon-twitter"/></svg></a></li>
        </ul>
      </div>

      <div class="footer-text -column-three">
        <p><?= \Firebelly\SiteOptions\get_option('footer_statement'); ?></p>
      </div>

    </div>
  </div>
</footer>
