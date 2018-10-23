<?php while (have_posts()) : the_post(); ?>
  <?php 
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

    \Firebelly\Utils\get_template_part_with_vars('templates/page', 'header', ['authors' => $authors, 'post_date' => $post_date, 'republished_from' => $republished_from, 'post_terms' => $terms]);
  ?>

  <article <?php post_class('fb-container-content'); ?>>
    <div class="entry-content user-content">
      <?php the_content(); ?>
    </div>
    <footer>
      <?php wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']); ?>
    </footer>
    <?php comments_template('/templates/comments.php'); ?>
  </article>
<?php endwhile; ?>
