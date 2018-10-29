<form action="<?= admin_url('admin-ajax.php') ?>" class="applicant-form">

  <div class="grid">
    <div class="md-one-half">
      <div class="input-wrap">
        <input type="text" name="first_name" required>
        <label>Name</label>
      </div>
    </div>
    <div class="md-one-half">
      <div class="input-wrap">
        <input type="text" name="last_name" required>
        <label>Last Name</label>
      </div>
    </div>
  </div>

  <div class="grid">
    <div class="md-one-third">
      <div class="input-wrap">
        <input type="text" name="organization"  organization">
        <label>Organization</label>
      </div>
    </div>
    <div class="md-one-third">
      <div class="input-wrap">
        <input type="text" name="title"  title">
        <label>Title</label>
      </div>
    </div>
    <div class="md-one-third">
      <div class="input-wrap">
        <input type="email" name="email"  email" required>
        <label>Email</label>
      </div>
    </div>
  </div>

  <div class="input-submit">
    <button type="submit" class="button">Submit</button>
  </div>

  <input name="action" type="hidden" value="tool_applicant">
  <input name="tool_id" type="hidden" value="<?= $tool_post->ID ?>">
  <?php wp_nonce_field( 'tool_applicant', 'tool_applicant_nonce' ); ?>

</form>
