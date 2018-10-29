<?php
// todo: News/Story articles are nearly identical, refactor to share template
$news_author_post = null;
$news_authors = get_post_meta($article_post->ID, '_cmb2_author');
$news_image = \Firebelly\Media\get_header_bg($article_post, ['size' => 'medium']);
$topics = wp_get_post_terms($article_post->ID, 'category');
?>
<article class="news card">
  <?php if ($news_image): ?>
    <div class="card-image-container background-blend">
      <a href="<?= get_permalink($article_post) ?>" class="card-image" <?= $news_image ?>></a>
    </div>
  <?php endif; ?>
  <div class="card-content">
    <h1 class="card-title"><a href="<?= get_permalink($article_post) ?>"><?= $article_post->post_title ?></a></h1>
    <?php if (!empty($news_authors)): ?>
      <p class="author card-subtitle">By 
        <?php
        $news_author_links = [];
        foreach ($news_authors as $author_id) {
          $news_author_post = get_post($author_id);
          $news_author_links[] = '<a href="' . get_permalink($author_id) . '">' . $news_author_post->post_title . '</a>';
        }
        echo implode(', ', $news_author_links);
        ?>
      </p>
      <?php if (!empty($topics)): ?>
        <p class="topics card-tags">
          <?php
          $topic_links = [];
          foreach ($topics as $term) {
            $topic_links[] = '<a href="/news/?topic=' . $term->slug . '">' . $term->name . '</a>';
          }
          echo implode(', ', $topic_links);
          ?>
        </p>
      <?php endif ?>
    <?php endif; ?>
    <p class="card-action"><a href="<?= get_permalink($article_post) ?>" class="button">Read</a></p>
  </div>
</article>
