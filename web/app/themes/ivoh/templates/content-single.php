<?php while (have_posts()) : the_post(); ?>
  <?php \Firebelly\Utils\get_template_part_with_vars('templates/page', 'header', ['foo' => 'bar']); ?>

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
