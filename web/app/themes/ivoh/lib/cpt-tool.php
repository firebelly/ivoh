<?php
/**
 * Tool post type
 */

namespace Firebelly\PostTypes\Tool;
use PostTypes\PostType; // see https://github.com/jjgrainger/PostTypes
use PostTypes\Taxonomy;
use \DrewM\MailChimp\MailChimp; // see https://github.com/drewm/mailchimp-api

$tool = new PostType(['name' => 'tool', 'plural' => 'Tool Posts', 'singular' => 'Tool Post', 'slug' => 'tool'], [
  'supports'   => ['title', 'editor', 'revisions'],
  'rewrite'    => ['with_front' => false],
]);
$tool->register();

/**
 * CMB2 custom fields
 */
add_filter('cmb2_admin_init', __NAMESPACE__ . '\metaboxes');
function metaboxes() {
  $prefix = '_cmb2_';

  $tool_info = new_cmb2_box([
    'id'            => $prefix . 'tool_info',
    'title'         => __( 'Tool Info', 'cmb2' ),
    'object_types'  => ['tool'],
    'context'       => 'normal',
    'priority'      => 'high',
  ]);
  $tool_info->add_field([
    'name'      => 'Application Prompt',
    'id'        => $prefix . 'application_prompt',
    'type'      => 'textarea_small',
    'desc'      => 'Shown above the application form',
    'column'     => [
      'position' => 2,
      'name'     => 'Application Prompt',
    ],
  ]);
  $tool_info->add_field([
    'name'       => 'Mailchimp List ID',
    'id'         => $prefix . 'mailchimp_list_id',
    'type'       => 'text_small',
    'desc'       => 'Mailchimp list new applicants are added to',
    'column'     => [
      'position' => 3,
      'name'     => 'Mailchimp List ID',
    ],
  ]);
  $tool_info->add_field([
    'name'      => 'Sample PDF(s)',
    'id'        => $prefix . 'sample_pdfs',
    'type'      => 'file_list',
    'desc'      => 'PDF(s) shown on public page',
  ]);
  $tool_info->add_field([
    'name'      => 'Final PDF(s)',
    'id'        => $prefix . 'final_pdfs',
    'type'      => 'file_list',
    'desc'      => 'PDF(s) attached to email sent to applicants',
  ]);
  $tool_info->add_field([
    'name'      => 'Applicant email',
    'id'        => $prefix . 'applicant_email',
    'type'      => 'wysiwyg',
    'desc'      => 'Template for email sent to applicants (available shortcodes: [first_name], [last_name], [email], [organization], [final_pdf])',
    'options' => [
      'textarea_rows' => 8,
    ],
  ]);
}

/**
 * Get tool posts
 */
function get_tools($opts=[]) {
  if (empty($opts['numberposts'])) $opts['numberposts'] = -1;
  $args = [
    'numberposts' => $opts['numberposts'],
    'post_type'   => 'tool',
  ];

  // Display all matching posts using article-{$post_type}.php
  $tool_posts = get_posts($args);
  if (!$tool_posts) return false;
  $output = '';
  foreach ($tool_posts as $tool_post):
    ob_start();
    include(locate_template('templates/article-tool.php'));
    $output .= ob_get_clean();
  endforeach;
  return $output;
}

/**
 * New Tool Application
 */
function new_tool_applicant() {
  $errors = [];
  $tool = get_post($_POST['tool_id']);
  if (!$tool) {
    wp_send_json_error(['message' => 'Tool post not found']);
  }
  $mailchimp_list_id = get_post_meta($tool->ID, '_cmb2_mailchimp_list_id', true);

  // Add applicant to mailchimp list if list_id is set
  if ($mailchimp_list_id && getenv('MAILCHIMP_API_KEY')) {
    $MailChimp = new MailChimp(getenv('MAILCHIMP_API_KEY'));

    $subscriber_hash = $MailChimp->subscriberHash($_POST['email']);

    // Is user already a member of list?
    $result = $MailChimp->get('lists/'.$mailchimp_list_id.'/members/'.$subscriber_hash);
    if($result['status'] == '404') {
      // Add user to list
      $result = $MailChimp->post('lists/'.$mailchimp_list_id.'/members', [
        'email_address' => $_POST['email'],
        'status'        => 'subscribed',
        'merge_fields'  => [
          'FNAME'   => $_POST['first_name'],
          'LNAME'   => $_POST['last_name'],
          'ORGNAME' => (!empty($_POST['organization']) ? $_POST['organization'] : ''),
          'TITLE'   => (!empty($_POST['title']) ? $_POST['title'] : ''),
        ],
      ]);
    } else {
      // Update info for existing member
      $result = $MailChimp->patch('lists/'.$mailchimp_list_id.'/members/'.$subscriber_hash, [
        'merge_fields'  => [
          'FNAME'   => $_POST['first_name'],
          'LNAME'   => $_POST['last_name'],
          'ORGNAME' => (!empty($_POST['organization']) ? $_POST['organization'] : ''),
          'TITLE'   => (!empty($_POST['title']) ? $_POST['title'] : ''),
        ]
      ]);
    }
  }

  // If thanks email template is set, send email to applicant
  if ($thanks_email = get_post_meta($tool->ID, '_cmb2_applicant_email', true)) {
    // Get final PDF links
    $final_pdfs = get_post_meta($tool->ID, '_cmb2_final_pdfs', true);
    $final_pdf_links = '';
    foreach ((array)$final_pdfs as $attachment_id => $attachment_url) {
      $attachment_post = get_post($attachment_id);
      $final_pdf_links = '• ' . $attachment_post->post_title . ': ' . $attachment_url . "\n";
    }

    // Replace vars in thank you email template
    $thanks_email = str_replace('[first_name]', $_POST['first_name'], $thanks_email);
    $thanks_email = str_replace('[last_name]', $_POST['last_name'], $thanks_email);
    $thanks_email = str_replace('[email]', $_POST['email'], $thanks_email);
    $thanks_email = str_replace('[organization]', $_POST['organization'], $thanks_email);
    $thanks_email = str_replace('[final_pdf]', $final_pdf_links, $thanks_email);

    // Send email to applicant
    $site_url = preg_replace('#https?\://#','',getenv('WP_HOME'));
    wp_mail($_POST['email'], 'Thank you for your interest in '.$tool->post_title, $thanks_email);
  }

  if (empty($errors)) {
    return true;
  } else {
    return $errors;
  }
}


/**
 * AJAX Tool Submissions
 */
function tool_submission() {
  if($_SERVER['REQUEST_METHOD']==='POST' && !empty($_POST['tool_submission_nonce'])) {
    if (wp_verify_nonce($_POST['tool_submission_nonce'], 'tool_submission')) {

      // Server side validation of required fields
      $required_fields = ['tool_id',
                          'first_name',
                          'last_name',
                          'email'];
      foreach($required_fields as $required) {
        if (empty($_POST[$required])) {
          $required_txt = ucwords(str_replace('_', ' ', $required));
          wp_send_json_error(['message' => 'Please enter a value for '.$required_txt]);
        }
      }

      // Check for valid Email
      if (!is_email($_POST['email'])) {
        wp_send_json_error(['message' => 'Invalid email']);
      } else {

        // Try to save applicant to mailchimp list and send email
        $return = new_tool_applicant();
        if (is_array($return)) {
          wp_send_json_error(['message' => 'There was an error: '.implode("\n", $return)]);
        } else {
          wp_send_json_success(['message' => 'Tool application sent OK!']);
        }

      }
    } else {
      // Bad nonce, man!
      wp_send_json_error(['message' => 'Invalid form submission (bad nonce)']);
    }
  }
  wp_send_json_error(['message' => 'Invalid post']);
}
add_action('wp_ajax_tool_submission', __NAMESPACE__ . '\\tool_submission');
add_action('wp_ajax_nopriv_tool_submission', __NAMESPACE__ . '\\tool_submission');
