<form action="<?= admin_url('admin-ajax.php') ?>" class="tool-form">

  <div class="form-row grid spaced">
    <div class="input-wrap one-half">
      <input type="text" name="first_name" required>
      <label>First Name</label>
    </div>
    <div class="input-wrap one-half">
      <input type="text" name="last_name" required>
      <label>Last Name</label>
    </div>
  </div>

  <div class="form-row grid spaced">
    <div class="input-wrap one-third">
      <input type="text" name="organization">
      <label>Organization</label>
    </div>

    <div class="input-wrap one-third">
      <input type="text" name="title">
      <label>Title</label>
    </div>

    <div class="input-wrap one-third">
      <input type="email" name="email" required>
      <label>Email</label>
    </div>
  </div>

  <div class="input-submit">
    <div class="status"></div>

    <button type="submit" class="button">Submit</button>
  </div>

  <input name="action" type="hidden" value="tool_submission">
  <input name="tool_id" type="hidden" value="<?= $tool_post->ID ?>">
  <?php wp_nonce_field( 'tool_submission', 'tool_submission_nonce' ); ?>

</form>
