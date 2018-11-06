<?php while (have_posts()) : the_post(); ?>
  <?php
    $post_meta = get_post_meta($post->ID);
    $authors =  get_post_meta($post->ID, '_cmb2_author');
    $post_date = get_the_date('m/d/Y');
    if (!empty(get_post_meta($post->ID, '_cmb2_story_republished'))) {
      $republished_from = get_post_meta($post->ID, '_cmb2_story_republished')[0];
    } else {
      $republished_from = '';
    }
    if (get_the_terms($post, 'story_topic')) {
      $terms = get_the_terms($post, 'story_topic');
    } elseif (get_the_terms($post, 'category')) {
      $terms = get_the_terms($post, 'category');
    } else {
      $terms = '';
    }

    \Firebelly\Utils\get_template_part_with_vars('templates/page', 'header', [
      'authors'          => $authors,
      'post_date'        => $post_date,
      'republished_from' => $republished_from,
      'post_terms'       => $terms
    ]);

    if (get_post_type($post) == 'story' && !empty($authors)):
      $author = get_post($authors[0]);
      $author_photo = \Firebelly\Media\get_header_bg($author, ['size'=>'thumbnail']);
      $author_bio = get_post_meta($author->ID, '_cmb2_person_post_bio', true);
    endif;
  ?>

  <article <?php post_class('fb-container-content'); ?>>
    <?php if (!empty($author_bio)): ?>
      <div class="post-author-meta">
        <div class="author-photo" <?= $author_photo ?>></div>
        <p class="author-bio user-content"><?= $author_bio ?></p>
      </div>
    <?php endif ?>
    <div class="entry-content user-content">
      <?php the_content(); ?>
    </div>
    <footer>
      <?php wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']); ?>
    </footer>
  </article>
<?php endwhile; ?>
