<?php
  if (empty($search_title)):
    $search_title = 'Search';
  endif;
?>
<form role="search" method="get" class="search-form fb-container-content" action="<?= esc_url(home_url('/')); ?>">
  <h3 class="search-title"><?= $search_title ?></h3>
  <div class="-inner">
    <div class="input-wrap">
      <input id="s" type="search" value="" autocomplete="off" name="s" class="search-field form-control" placeholder="" required>
      <label for="s">Enter Keyword(s)</label>
    </div>
    <button type="submit" class="search-submit button">Go</button>
    <div class="search-close button circular"><span class="sr-only">Close Search</span><span class="icon close"></span></div>
  </div>
</form>