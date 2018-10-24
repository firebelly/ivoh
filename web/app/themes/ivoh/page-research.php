<?php
/*
  Template name: Research
*/

$research_topics = get_terms('research_topic');
?>
<?php get_template_part('templates/page', 'header'); ?>

<?php
foreach ($research_topics as $research_topic):
  $research = \Firebelly\PostTypes\Research\get_research(['category' => $research_topic->slug ]);
  if (!empty($research)):
  ?>
  <div class="research-group">
    <h2><?= $research_topic->name ?></h2>
    <?= $research ?>
  </div>
  <?php endif; ?>
<?php endforeach; ?>
