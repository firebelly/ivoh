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
      if (empty($author_bio)) {
        $author_edit_link = get_edit_post_link($author->ID);
      }
    endif;
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
    <div class="entry-content user-content">
      <?php the_content(); ?>
    </div>
    <footer>
      <?php wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']); ?>
    </footer>
  </article>
<?php endwhile; ?>
