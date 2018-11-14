<?php
// Get all post_meta (if we can)
$post_meta = !empty($post) ? get_post_meta($post->ID) : [];

if (!empty($post_meta['_cmb2_previous_page']) || !empty($post_meta['_cmb2_next_page'])):
?>
  <div class="fb-container-content">
    <div class="page-links patterned">
      <?php
      if (!empty($post_meta['_cmb2_previous_page'])):
        $previous_page = get_post($post_meta['_cmb2_previous_page'][0]);
        $subhead = get_post_meta($previous_page->ID, '_cmb2_intro_subhead', true);
        if (empty($subhead) && $previous_page->post_parent) {
          $parent_post = get_post($previous_page->post_parent);
          $subhead = $parent_post->post_title;
        }
        ?>
        <div class="previous-page -item inherit-background">
          <a href="<?= get_permalink($previous_page) ?>">
            <span class="icon-container"><svg class="icon icon-arrow" aria-hidden="true" role="presentation"><use xlink:href="#icon-arrow"/></svg></span>
            <span class="link-text">
              <?= !empty($subhead) ? '<h4 class="breadcrumbs">'.$subhead.'</h4>' : '' ?>
              <h2><?= $previous_page->post_title ?></h2>
            </span>
          </a>
        </div>
      <?php endif; ?>
      <?php
      if (!empty($post_meta['_cmb2_next_page'])):
        $next_page = get_post($post_meta['_cmb2_next_page'][0]);
        $subhead = get_post_meta($next_page->ID, '_cmb2_intro_subhead', true);
        if (empty($subhead) && $next_page->post_parent) {
          $parent_post = get_post($next_page->post_parent);
          $subhead = $parent_post->post_title;
        }
        ?>
        <div class="next-page -item inherit-background">
          <a href="<?= get_permalink($next_page) ?>">
            <span class="icon-container"><svg class="icon icon-arrow" aria-hidden="true" role="presentation"><use xlink:href="#icon-arrow"/></svg></span>
            <span class="link-text">
              <?= !empty($subhead) ? '<h4 class="breadcrumbs">'.$subhead.'</h4>' : '' ?>
              <h2><?= $next_page->post_title ?></h2>
            </span>
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>