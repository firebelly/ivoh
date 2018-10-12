<div id="search" class="search-form-container">
  <form role="search" method="get" class="search-form" action="<?= esc_url(home_url('/')); ?>">
    <h3>Search</h3>
    <div class="-inner">
      <div class="input-wrap">    
        <input id="s" type="search" value="" autocomplete="off" name="s" class="search-field form-control" placeholder="" required>
        <label for="s">Enter Keyword(s)</label>
      </div>
      <button type="submit" class="search-submit button">Go</button>
      <div class="search-close"><span class="sr-only">Close Search</span> <svg class="icon icon-plus" aria-hidden="true" role="presentation"><use xlink:href="#icon-plus"/></svg></div>
    </div>
  </form>
</div>