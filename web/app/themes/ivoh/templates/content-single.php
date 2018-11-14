<?php while (have_posts()) : the_post();

  $post_meta = get_post_meta($post->ID);
  $authors =  get_post_meta($post->ID, '_cmb2_author');
  if (!empty($authors)) {
    $author = get_post($authors[0]);
    $author_photo = \Firebelly\Media\get_header_bg($author, ['size'=>'thumbnail']);
    $author_bio = get_post_meta($author->ID, '_cmb2_person_post_bio', true);
    if (empty($author_bio)) {
      $author_edit_link = get_edit_post_link($author->ID);
    }
  }

  $post_date = get_the_date('m/d/Y');
  $republished_from = get_post_meta($post->ID, '_cmb2_story_republished', true);

  if ($post->post_type == 'story') {
    $terms = get_the_terms($post, 'story_topic');
  } else {
    $terms = get_the_terms($post, 'category');
  }

  \Firebelly\Utils\get_template_part_with_vars('templates/page', 'header', [
    'authors'          => $authors,
    'post_date'        => $post_date,
    'republished_from' => $republished_from,
    'post_terms'       => (!empty($terms) ? $terms : '')
  ]);

  // Related links at bottom of page
  $related_posts = get_post_meta($post->ID, '_cmb2_related_posts');
  if ($related_posts) {
    // Get all stories & news by author
    $related_posts = \Firebelly\Utils\get_posts([
      'post_type'     => ['story','post'],
      'template-type' => 'simple',
      'post__in'      => (array)$related_posts,
      'numberposts'   => -1,
    ]);

  }

  // Post intro shown at top of page
  $post_intro = get_post_meta($post->ID, '_cmb2_post_intro', true);

?>

  <article <?php post_class('fb-container-content'); ?>>
    <?php if (!empty($author)): ?>
      <div class="post-author-meta">
        <?php if (!empty($author_photo)): ?>
          <a class="author-photo" href="<?= get_permalink($author) ?>" <?= $author_photo ?>></a>
        <?php endif; ?>
        <div class="author-bio user-content">
          <?php if (empty($author_bio) && !empty($author_edit_link)): ?>
            <p>Author bio empty. <a target="_blank" href="<?= $author_edit_link ?>">Edit Author</a></p>
          <?php else: ?>
            <?= apply_filters('the_content', $author_bio) ?>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>

    <?php if (!empty($post_intro)): ?>
      <div class="post-intro user-content">
        <?= apply_filters('the_content', trim($post_intro)) ?>
      </div>
    <?php endif; ?>

    <div class="entry-content user-content">
      <?php the_content(); ?>

      <div class="share hidden">
        <h3>Share:</h3>
        <div class="addthis_toolbox">
          <a class="addthis_button_facebook button circular"><svg class="icon icon-facebook" role="img"><use xlink:href="#icon-facebook"></use></svg></a>
          <a class="addthis_button_twitter button circular"><svg class="icon icon-share-twitter" role="img"><use xlink:href="#icon-twitter"></a>
          <a class="addthis_button_linkedin button circular"><svg class="icon icon-linkedin" role="img"><use xlink:href="#icon-linkedin"></use></svg></a>
          <a class="addthis_button_link button circular" target="_blank"><svg class="icon icon-link" role="img"><use xlink:href="#icon-link"></use></svg></a>
        </div>
      </div>
    </div>

  </article>

  <?php if (!empty($related_posts)): ?>
    <div class="article-list page-section fb-container-content">
      <div class="-inner inherit-background">
        <h3 class="list-title">Related:</h3>
        <?= $related_posts ?>
      </div>
    </div>
  <?php endif ?>

<?php endwhile; ?>
