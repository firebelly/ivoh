<?php
/*
  Template name: Story Bank
*/

// Topic var
$topic = get_query_var('topic', '');

// Get all stories matching filters
$stories = \Firebelly\PostTypes\Story\get_stories([
  'topic' => $topic,
]);

// Get base topics for filtering
$topics = get_terms([
  'taxonomy' => 'story_topic',
  'parent' => 0,
]);
?>

<?php get_template_part('templates/page', 'header'); ?>

<ul class="topics">
<?php foreach ($topics as $term): ?>
  <li><a href="/story-bank/?topic=<?= $term->slug ?>"><?= $term->name ?></a></li>
<?php endforeach; ?>
</ul>

<?php
if (!empty($stories)):
  echo $stories;
endif;