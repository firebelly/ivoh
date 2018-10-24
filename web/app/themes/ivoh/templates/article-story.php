<?php
// todo: News/Story articles are nearly identical, refactor to share template
$story_author_post = null;
$story_authors = get_post_meta($story_post->ID, '_cmb2_author');
$story_image = \Firebelly\Media\get_header_bg($story_post, ['size' => 'medium']);
$topics = wp_get_post_terms($story_post->ID, 'story_topic');
$story_desc = \Firebelly\Utils\get_excerpt($story_post, $length=25);
?>
<article class="story card sm-one-half md-one-third lg-one-fourth <?= $story_post->column_width ?>">
  <?= \Firebelly\Utils\admin_edit_link($story_post) ?>
  <?php if ($story_image): ?>
    <div class="card-image-container background-blend">
      <a href="<?= get_permalink($story_post) ?>" class="card-image" <?= $story_image ?>></a>
    </div>
  <?php endif; ?>
  <div class="card-content">
    <h1 class="card-title"><a href="<?= get_permalink($story_post) ?>"><?= $story_post->post_title ?></a></h1>
    <?php if (!empty($story_authors)): ?>
      <p class="author card-subtitle">
        <?php
        $story_author_links = [];
        foreach ($story_authors as $author_id) {
          $story_author_post = get_post($author_id);
          $story_author_links[] = '<a href="' . get_permalink($author_id) . '">' . $story_author_post->post_title . '</a>';
        }
        echo implode(', ', $story_author_links);
        ?>
      </p>
      <?php if (!empty($topics)): ?>
        <p class="topics card-tags">
          <?php
          $topic_links = [];
          foreach ($topics as $term) {
            $topic_links[] = '<a href="/story-bank/?topic=' . $term->slug . '">' . $term->name . '</a>';
          }
          echo implode(', ', $topic_links);
          ?>
        </p>
      <?php endif ?>
    <?php endif; ?>
    <p class="card-action"><a href="<?= get_permalink($story_post) ?>" class="button">Read</a></p>
  </div>
</article>
