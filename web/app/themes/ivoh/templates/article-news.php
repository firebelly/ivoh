<?php
// todo: News/Story articles are nearly identical, refactor to share template
$news_author_post = null;
$news_authors = get_post_meta($news_post->ID, '_cmb2_author');
$news_image = \Firebelly\Media\get_header_bg($news_post, ['size' => 'medium']);
$topics = wp_get_post_terms($news_post->ID, 'category');
$news_desc = \Firebelly\Utils\get_excerpt($news_post, $length=25);
?>
<article class="story <?= $news_post->column_width ?>"><div class="wrap">
  <?= \Firebelly\Utils\admin_edit_link($news_post) ?>
  <?php if ($news_image): ?>
    <div class="image" <?= $news_image ?>></div>
  <?php endif; ?>
  <h1 class="h3"><a href="<?= get_permalink($news_post) ?>"><?= $news_post->post_title ?></a></h1>
  <?php if (!empty($news_authors)): ?>
    <p class="author">
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
      <p class="topics">
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
  <?php if (!empty($news_desc)): ?>
    <div class="user-content"><?= $news_desc ?></div>
  <?php endif; ?>
</div></article>
