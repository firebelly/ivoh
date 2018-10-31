<?php
/*
  Template name: Research
*/

$research_topics = get_terms('research_topic');
?>
<?php get_template_part('templates/page', 'header'); ?>

<div class="fb-container-md">
  <div class="mobile-gutter patterned">
    <div class="article-list grid masonry outlined">
      <div class="grid-sizer md-one-half"></div>
      <?php
      foreach ($research_topics as $research_topic):
        $research = \Firebelly\PostTypes\Research\get_research(['category' => $research_topic->slug ]);
        if (!empty($research)):
        ?>
        <div class="article-group inherit-background md-one-half item">
          <h2 class="list-title"><?= $research_topic->name ?></h2>
          <?= $research ?>
        </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
</div>