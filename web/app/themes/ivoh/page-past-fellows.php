<?php
for ($year=date('Y'); $year >= 2015 ; $year--):
  // $person_category = get_term_by('slug', $year.'-fellows', 'person_category');
  $people = \Firebelly\PostTypes\Person\get_people(['category' => $year.'-fellows']);
  if (!empty($people)):
  ?>
  <div class="person-group">
    <h2><?= $year ?> Fellows</h2>
    <?= $people ?>
  </div>
  <?php endif; ?>
<?php endfor; ?>
