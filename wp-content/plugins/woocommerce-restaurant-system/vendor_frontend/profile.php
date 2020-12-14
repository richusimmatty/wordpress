<?php
global $current_user;
require_once(ABSPATH . 'wp-includes/pluggable.php');
require_once(ABSPATH . 'wp-load.php');
if (!current_user_can('manage_options')) {
  add_action('show_user_profile', 'wvs_add_vendor_info_to_profile');
  add_action('edit_user_profile', 'wvs_add_vendor_info_to_profile');
}

function wvs_add_vendor_info_to_profile($user) {
  global $wpdb;
  ?>
  <h3><?php echo WC_PM; ?> Information</h3>
  <?php
  $vendor_id = get_user_meta($user->ID, '_vendor_post_id', true);
  ?>
  <table class="form-table">
    <tr>
      <th><label for="_vendor_name">Name</label></th>
      <td><input type="text" name="_vendor_name" value="<?php echo esc_attr(get_post_meta($vendor_id, '_vendor_name', true)); ?>" class="regular-text" /></td>
    </tr>
    <tr>
      <th><label for="_vendor_company">Company</label></th>
      <td><input type="text" name="_vendor_company" value="<?php echo esc_attr(get_post_meta($vendor_id, '_vendor_company', true)); ?>" class="regular-text" /></td>
    </tr>

    <tr>
      <th><label for="_vendor_address">Address</label></th>
      <td><input type="text" name="_vendor_address" value="<?php echo esc_attr(get_post_meta($vendor_id, '_vendor_address', true)); ?>" class="regular-text" /></td>
    </tr>
    <tr>
      <th><label for="_vendor_country">Country</label></th>
      <td><input type="text" name="_vendor_country" value="<?php echo esc_attr(get_post_meta($vendor_id, '_vendor_country', true)); ?>" class="regular-text" /></td>
    </tr>
    <tr>
      <th><label for="_vendor_state">State</label></th>
      <td><input type="text" name="_vendor_state" value="<?php echo esc_attr(get_post_meta($vendor_id, '_vendor_state', true)); ?>" class="regular-text" /></td>
    </tr>
    <tr>
      <th><label for="_vendor_zip">Zip</label></th>
      <td><input type="text" name="_vendor_zip" value="<?php echo esc_attr(get_post_meta($vendor_id, '_vendor_zip', true)); ?>" class="regular-text" /></td>
    </tr>
    <tr>
      <th><label for="_vendor_email">Email</label></th>
      <td><input type="text" name="_vendor_email" value="<?php echo esc_attr(get_post_meta($vendor_id, '_vendor_email', true)); ?>" class="regular-text" /></td>
    </tr>
    <tr>
      <th><label for="_vendor_phone">Phone</label></th>
      <td><input type="text" name="_vendor_phone" value="<?php echo esc_attr(get_post_meta($vendor_id, '_vendor_phone', true)); ?>" class="regular-text" /></td>
    </tr>
    <tr>
      <th><label for="_vendor_fax">Fax</label></th>
      <td><input type="text" name="_vendor_fax" value="<?php echo esc_attr(get_post_meta($vendor_id, '_vendor_fax', true)); ?>" class="regular-text" /></td>
    </tr>
    <tr>
      <th><label for="_vendor_paypal">Paypal</label></th>
      <td><input type="text" name="_vendor_paypal" value="<?php echo esc_attr(get_post_meta($vendor_id, '_vendor_paypal', true)); ?>" class="regular-text" /></td>
    </tr>
    <tr>
      <th><label for="_vendor_product_lbl">All Product Label</label></th>
      <td><input type="text" name="_vendor_product_lbl" value="<?php echo esc_attr(get_post_meta($vendor_id, '_vendor_product_lbl', true)); ?>" class="regular-text" /></td>
    </tr>
  </table>
  <?php
}

add_action('personal_options_update', 'wvs_save_vendor_info_to_profile');
add_action('edit_user_profile_update', 'wvs_save_vendor_info_to_profile');

function wvs_save_vendor_info_to_profile($user_id) {
  global $wpdb;
  $vendor_id = get_user_meta($user_id, '_vendor_post_id', true); 

  if ($_POST['_vendor_name'] != '') {
    update_post_meta($vendor_id, '_vendor_name', $_POST['_vendor_name']);
  }
  update_post_meta($vendor_id, '_vendor_company', $_POST["_vendor_company"]);
  update_post_meta($vendor_id, '_vendor_address', $_POST['_vendor_address']);

  update_post_meta($vendor_id, '_vendor_country', $_POST["_vendor_country"]);
  update_post_meta($vendor_id, '_vendor_state', $_POST["_vendor_state"]);
  update_post_meta($vendor_id, '_vendor_zip', $_POST["_vendor_zip"]);

  update_post_meta($vendor_id, '_vendor_email', $_POST["_vendor_email"]);
  update_post_meta($vendor_id, '_vendor_phone', $_POST["_vendor_phone"]);
  update_post_meta($vendor_id, '_vendor_fax', $_POST["_vendor_fax"]);

  update_post_meta($vendor_id, '_vendor_paypal', $_POST["_vendor_paypal"]);
  update_post_meta($vendor_id, '_vendor_product_lbl', $_POST["_vendor_product_lbl"]);
}