<?php
// todo: News/Story articles are nearly identical, refactor to share template
$topics = wp_get_post_terms($article_post->ID, $topic_taxonomy);
?>
<article class="story article-simple">
  <div class="article-content">
    <h1 class="article-title"><a href="<?= get_permalink($article_post) ?>"><?= $article_post->post_title ?></a></h1>
    <?php if (!empty($topics)): ?>
      <p class="topics article-tags">
        <?php
        $topic_links = [];
        foreach ($topics as $term) {
          $topic_links[] = '<a href="/story-bank/?topics=' . $term->slug . '">' . $term->name . '</a>';
        }
        echo implode(', ', $topic_links);
        ?>
      </p>
    <?php endif ?>
    <p class="article-action"><a href="<?= get_permalink($article_post) ?>">Read</a></p>
  </div>
</article>
