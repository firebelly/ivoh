<?php
/*
  Template name: Past Fellows
*/
?>
<?php get_template_part('templates/page', 'header'); ?>

<?php
for ($year=date('Y'); $year >= 2015 ; $year--):
  // $person_category = get_term_by('slug', $year.'-fellows', 'person_category');
  $people = \Firebelly\PostTypes\Person\get_people(['category' => $year.'-fellows', 'extra-class' => 'past-fellows']);
  if (!empty($people)): ?>
  <div class="page-section fb-container-md">
    <h2 class="h5"><?= $year ?> Fellows</h2>
    <div class="card-grid">
      <div class="-inner grid">
        <?= $people ?>
      </div>
    </div>
  </div>
  <?php endif; ?>
<?php endfor; ?>