<?php

function wrs_vendor_info_check($hook) {
  global $post;
  if (isset($post->post_type)) {
    if ($post->post_type == 'sc_custom_booking') {
      wp_enqueue_script('wvs-vendor_info_check', WQB_BOOKING_PLUGIN_URL . '/scpd_resource/js/room_info_check.js');
    }
  }
  wp_enqueue_script('wvs-vendor_info_check', WP_CUSTOM_PRODUCT_URL . '/vendor_resource/js/vendor_info_check.js');
}

add_action('admin_enqueue_scripts', 'wrs_vendor_info_check');

function wvs_vendor_product_post_type() {
  register_post_type('vendor_product', array(
      'labels' => array(
          'name' => __(WC_PM, 'woocommerce-vendor-setup'),
          'singular_name' => __(WC_PM, 'woocommerce-vendor-setup'),
          'add_new_item' => __('Add New ' . WC_PM, 'woocommerce-vendor-setup'),
          'edit_item' => __('Edit ' . WC_PM, 'woocommerce-vendor-setup')
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'vendor_products'),
      'supports' => array('title', 'editor', 'thumbnail')
          )
  );
}

function wvs_is_allowed_image($filename) {
  if ($filename == '') {
    return false;
  }
  $size = getimagesize($filename);

  switch ($size['mime']) {
    case "image/gif":
      return true;
      break;
    case "image/jpeg":
      return true;
      break;
    case "image/png":
      return true;
      break;
    default:
      return false;
  }
}

function wvs_vendor_upload_product() {
  global $woocommerce, $post, $current_user, $wpdb;
  //get_currentuserinfo();

  $msg_trac = true;
  $current_vendor_info = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key = '_vendor_user_id' and meta_value = '" . get_current_user_id() . "'");

  if (!empty($current_vendor_info)) {
    $vendor_pro_publish_status = get_post_meta($current_vendor_info->post_id, '_vendor_publish_pro', true);
  } else {
    $vendor_pro_publish_status = 'pending';
  }
  if ($_POST) {
    $post = array(
        'post_author' => get_current_user_id(),
        'post_content' => $_POST['van_pro_cont'],
        'post_status' => $vendor_pro_publish_status,
        'post_title' => $_POST['ven_pro_title'],
        'post_parent' => '',
        'post_type' => "product",
    );

    //Create post
    $post_id = wp_insert_post($post);
    if ($post_id && $_FILES['img_file']['name'] != '') {
      $image_path = wp_upload_dir();
      $file = $image_path['path'] . '/' . basename($_FILES['img_file']['name']);
      $file_url = $image_path['url'] . '/' . basename($_FILES['img_file']['name']);
      $raw_file_name = $_FILES['img_file']['tmp_name'];
      if (wvs_is_allowed_image($_FILES['img_file']['tmp_name'])) {

        if (move_uploaded_file($_FILES['img_file']['tmp_name'], $file)) {
          $thumb_url = $file_url;
          $tmp = download_url($thumb_url);
          preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|png|PNG)/', $thumb_url, $matches);
          $file_array['name'] = basename($matches[0]);
          $file_array['tmp_name'] = $tmp;

          $thumbid = media_handle_sideload($file_array, $post_id, 'gallery desc');
          set_post_thumbnail($post_id, $thumbid);
        }
      }
    }

    if (isset($post_id) && isset($_POST['wcf_status'])) {
      /*
        $fields = '';
        foreach ($_POST['wpr_mf_text'] as $val) {
        if($val[1]!='' && $val[2]!=''){
        $fields .=$val[0]. ', ';
        }
        }
        $fields = rtrim($fields, ','); */
      $data = $_POST['wpr_mf_text'];
      if (!empty($fields)) {
        update_post_meta($post_id, "_wcf_frm_created", 1);
        update_post_meta($post_id, "_wcf_frm_data", $data);
        update_post_meta($post_id, "_wcf_custom_degin_checkbox", true);
      }
    }

    if ($post_id) {
      ?>
      <div id="message" class="updated notice is-dismissible">
        <p>
          <strong>Product Uploaded Successfully.</strong>
        </p>
        <button type="button" class="notice-dismiss">
          <span class="screen-reader-text">Dismiss this notice.</span>
        </button>
      </div>
      <?php
    }

    if (isset($_POST['ven_pro_category_select'])) {
      wp_set_object_terms($post_id, $_POST['ven_pro_category_select'], 'product_cat');
    } else {
      wp_set_object_terms($post_id, '', 'product_cat'); // Computer
    }

    wp_set_object_terms($post_id, $_POST['van_pro_type'], 'product_type');

    update_post_meta($post_id, '_visibility', 'visible');
    update_post_meta($post_id, '_stock_status', $_POST['ven_pro_stock_status']);
    update_post_meta($post_id, 'total_sales', '0');
    if (isset($_POST['van_pro_downloadable'])) {
      update_post_meta($post_id, '_downloadable', 'yes');
    }
    if (isset($_POST['van_pro_virtual'])) {
      update_post_meta($post_id, '_virtual', 'yes');
    }
    update_post_meta($post_id, '_regular_price', $_POST['ven_pro_regular_price']);
    update_post_meta($post_id, '_sale_price', $_POST['ven_pro_sale_price']);
    update_post_meta($post_id, '_purchase_note', $_POST['ven_pro_purchase_note']);
    update_post_meta($post_id, '_featured', "no");
    update_post_meta($post_id, '_weight', $_POST['ven_pro_weight']);
    update_post_meta($post_id, '_length', $_POST['ven_pro_length']);
    update_post_meta($post_id, '_width', $_POST['ven_pro_width']);
    update_post_meta($post_id, '_height', $_POST['ven_pro_height']);
    update_post_meta($post_id, '_sku', $_POST['ven_pro_sku']);
    update_post_meta($post_id, '_product_attributes', array());
    update_post_meta($post_id, '_sale_price_dates_from', "");
    update_post_meta($post_id, '_sale_price_dates_to', "");
    update_post_meta($post_id, '_price', $_POST['ven_pro_sale_price']);
    update_post_meta($post_id, '_sold_individually', "");
    if (isset($_POST['ven_pro_manage_stock'])) {
      update_post_meta($post_id, '_manage_stock', $_POST['ven_pro_manage_stock']);
    }

    update_post_meta($post_id, '_backorders', $_POST['ven_pro_backorders']);
    update_post_meta($post_id, '_stock', $_POST['ven_pro_stock']);
    if (isset($_POST['ven_pro_vendor_select'])) {
      $vendor_select = $_POST['ven_pro_vendor_select'];
      if (!empty($vendor_select))
        update_post_meta($post_id, '_vendor_select', esc_attr($vendor_select));
    }
    // Vendor Percentage
    if (isset($_POST['ven_pro_vendor_percentage'])) {
      $vendor_percentage = $_POST['ven_pro_vendor_percentage'];
      if (!empty($vendor_percentage))
        update_post_meta($post_id, '_vendor_percentage', esc_attr($vendor_percentage));
    }
    // Product Category
    if (isset($_POST['ven_pro_category_select'])) {
      $vendor_pro_category = $_POST['ven_pro_category_select'];
      if (!empty($vendor_pro_category))
        update_post_meta($post_id, '_vendor_pro_cat', esc_attr($vendor_pro_category));
    }
    // Vendor Note
    if (isset($_POST['ven_pro_vendor_note'])) {
      $vendor_note = $_POST['ven_pro_vendor_note'];
      if (!empty($vendor_note))
        update_post_meta($post_id, '_vendor_note', esc_html($vendor_note));
    }

    //-------------------------------------------------------------------------------------
    $to = get_option('admin_email');
    $subject = 'A new product has been inserted, which is pending for your approval';
    $message = '<!DOCTYPE HTML>' .
            '<head>' .
            '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' .
            '<title>A new product has been inserted by ' . $current_user->user_login . ' </title>' .
            '</head>' .
            '<body>' .
            '<table style="width:100%;">' .
            '<tr>' .
            '<td align="center">' .
            '<table style="width:60%; border:solid 1px #69F;">' .
            '<tr style="width:100%;">' .
            '<td style="width:100%; background:#69F; color:#FFF;" height="50px;">&emsp;</td>' .
            '</tr>' .
            '<tr style="width:100%;">' .
            '<td style="width:100%; font-size:30px; color:#000; font-weight:bold;">A new product hasbeen uploaded by ' . $current_user->user_login . '</td>' .
            '</tr>' .
            '<tr>' .
            '<td>&nbsp;</td>' .
            '</tr>' .
            '<tr style="width:100%;">' .
            '<td style="width:100%;">Product name : ' . $_POST['ven_pro_title'] . '</td>
									</tr>' .
            '<tr>' .
            '<td>&nbsp;</td>' .
            '</tr>' .
            '<tr style="width:100%;">' .
            '<td style="width:100%;">ID : ' . $post_id . '</td>
									</tr>' .
            '<tr>' .
            '<td>&nbsp;</td>' .
            '</tr>' .
            '<tr style="width:100%;">' .
            '<td style="width:100%;">Please approve this product for publish</td>' .
            '</tr>' .
            '<tr>' .
            '<td>&nbsp;</td>' .
            '</tr>' .
            '<tr style="width:100%;">' .
            '<td style="width:100%;">Thank you</td>' .
            '</tr>' .
            '<tr>' .
            '<td>&nbsp;</td>' .
            '</tr>' .
            '<tr style="width:100%;">' .
            '<td style="width:100%; background:#69F; color:#FFF;" height="50px;">&emsp;</td>' .
            '</tr>' .
            '</table>' .
            '</td>' .
            '</tr>' .
            '</table>' .
            '</body>' .
            '</html>';
    $headers = 'From: ' . $current_user->user_login . ' ' . get_post_meta($current_user->ID, '_vendor_email', true);
    wp_mail($to, $subject, $message, $headers);
  }
  //else{
  ?>
  <form name="van_pro_upload_frm" id="van_pro_upload_frm" action="" method="post" enctype="multipart/form-data">
    <div class="wrap">
      <h2>Upload product</h2>
      <table class="form-table">
        <tbody>
          <tr>
            <th scope="row"><label>Title</label></th>
            <td><input type="text" name="ven_pro_title" id="ven_pro_title" placeholder="Product Title Here" /></td>
          </tr>
          <tr>
            <th scope="row"><label>Product Content</label></th>
            <td><textarea name="van_pro_cont" id="van_pro_cont" placeholder="Product Content Here"></textarea></td>
          </tr>
          <tr>
            <th scope="row"><label>Product Data</label></th>
            <td>
              <select name="van_pro_type" id="van_pro_type">
                <option selected="selected" value="simple">Simple product</option>
                <option value="grouped">Grouped product</option>
                <option value="external">External/Affiliate product</option>
                <option value="variable">Variable product</option>
              </select>
              &nbsp;<label>Virtual: <input type="checkbox" id="van_pro_virtual" name="van_pro_virtual" value="yes"></label>&nbsp;<label>Downloadable: <input type="checkbox" id="van_pro_downloadable" name="van_pro_downloadable" value="yes"></label>
            </td>
          </tr>
          <tr>
            <th scope="row"><label>SKU</label></th>
            <td><input type="text" name="ven_pro_sku" id="ven_pro_sku" /></td>
          </tr>
          <tr>
            <th scope="row"><label>Regular Price (£)</label></th>
            <td><input type="text" id="ven_pro_regular_price" name="ven_pro_regular_price"></td>
          </tr>
          <tr>
            <th scope="row"><label>Sale Price (£)</label></th>
            <td><input type="text" id="ven_pro_sale_price" name="ven_pro_sale_price"></td>
          </tr>
          <tr>
            <th scope="row"><label>Manage stock?</label></th>
            <td><input type="checkbox" id="ven_pro_manage_stock" name="ven_pro_manage_stock" class="checkbox" value="yes"></td>
          </tr>
          <tr>
            <th scope="row"><label>Stock Qty</label></th>
            <td><input type="number" step="any" value="0" id="ven_pro_stock" name="ven_pro_stock" ></td>
          </tr>
          <tr>
            <th scope="row"><label>Allow Backorders?</label></th>
            <td>
              <select class="select short" name="ven_pro_backorders" id="ven_pro_backorders">
                <option value="no">Do not allow</option>
                <option value="notify">Allow, but notify customer</option>
                <option value="yes">Allow</option>
              </select>
            </td>
          </tr>
          <tr>
            <th scope="row"><label>Stock status</label></th>
            <td>
              <select class="select short" name="ven_pro_stock_status" id="ven_pro_stock_status">
                <option value="instock">In stock</option>
                <option value="outofstock">Out of stock</option>
              </select>
            </td>
          </tr>
          <tr>
            <th scope="row"><label>Weight (kg)</label></th>
            <td><input type="text" id="ven_pro_weight" name="ven_pro_weight"></td>
          </tr>
          <tr>
            <th scope="row"><label>Dimensions (cm)</label></th>
            <td>
              <span class="wrap">
                <input type="text" name="ven_pro_length" id="ven_pro_length" size="6" placeholder="Length">
                <input type="text" name="ven_pro_width" id="ven_pro_width" size="6" placeholder="Width">
                <input type="text" name="ven_pro_height" id="ven_pro_height" size="6" placeholder="Height">
              </span>
            </td>
          </tr>
          <tr>
            <th scope="row"><label>Purchase Note</label></th>
            <td><textarea cols="20" rows="2" id="ven_pro_purchase_note" name="ven_pro_purchase_note"></textarea></td>
          </tr>        
          <?php
          global $woocommerce, $post, $current_user, $wpdb;

          $current_vendor_info = $wpdb->get_row("SELECT * FROM $wpdb->usermeta WHERE meta_key = 'become_a_vendor' and user_id = '" . $current_user->ID . "'");
          $umeta_id = $current_vendor_info->umeta_id;
          $current_vendor_info2 = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key = '_vendor_user_id' and meta_value = '" . $umeta_id . "'");
          //$vendor_id = $current_vendor_info2->post_id;
          $vendor_id = get_user_meta($current_user->ID, '_vendor_post_id', true);
          $get_global_percent_for_vendor = get_post_meta($vendor_id, '_vendor_percentage', true);

          wp_reset_query();
          $args = array(
              'post_type' => 'vendor_product',
              'post_status' => 'publish',
              'posts_per_page' => -1
          );
          $select_ven = '';
          $posts = new WP_Query($args);
          $posts = $posts->posts;
          if (!empty($posts)) {
            $option_arr = array();
            foreach ($posts as $pst) {
              if ($pst->ID == $vendor_id) {
                $slct_ven = 'selected="selected"';
              } else {
                $slct_ven = '';
              }
              $vendor_list = get_post_meta($pst->ID, '_vendor_company', true);
              if ($vendor_list != '') {
                $select_ven.='<option ' . $slct_ven . ' value="' . $pst->ID . '">' . $vendor_list . '</option>';
              } else {
                $select_ven.='<option ' . $slct_ven . ' value="' . $pst->ID . '">' . $pst->post_title . '</option>';
              }
            }
          }
          ?>
          <tr style="display:none;">
            <th scope="row"><label>Select <?php echo WC_PM; ?></label></th>
            <td>
              <select name="ven_pro_vendor_select" id="ven_pro_vendor_select">
  <?php echo $select_ven; ?>
              </select>
            </td>
          </tr> 
          <?php
          $select_cat = '';
          $taxonomy = 'product_cat';
          $orderby = 'name';
          $show_count = 0;      // 1 for yes, 0 for no
          $pad_counts = 0;      // 1 for yes, 0 for no
          $hierarchical = 1;      // 1 for yes, 0 for no  
          $title = '';
          $empty = 0;
          $args = array(
              'taxonomy' => $taxonomy,
              'orderby' => $orderby,
              'show_count' => $show_count,
              'pad_counts' => $pad_counts,
              'hierarchical' => $hierarchical,
              'title_li' => $title,
              'hide_empty' => $empty
          );

          $all_categories = get_categories($args);

          foreach ($all_categories as $cat) {
            if ($cat->category_parent == 0) {
              $category_id = $cat->term_id;
              $select_cat.='<option value="' . $cat->name . '">' . $cat->name . '</option>';
              $args2 = array(
                  'taxonomy' => $taxonomy,
                  'child_of' => 0,
                  'parent' => $category_id,
                  'orderby' => $orderby,
                  'show_count' => $show_count,
                  'pad_counts' => $pad_counts,
                  'hierarchical' => $hierarchical,
                  'title_li' => $title,
                  'hide_empty' => $empty
              );

              $sub_cats = get_categories($args2);
              if ($sub_cats) {
                foreach ($sub_cats as $sub_category) {
                  $select_cat.='<option value="' . $sub_category->name . '">' . $sub_category->name . '</option>';
                }
              }
            }
          }
          ?>
          <tr>
            <th scope="row"><label>Select Category</label></th>
            <td>
              <select name="ven_pro_category_select" id="ven_pro_category_select">
  <?php echo $select_cat; ?>
              </select>
            </td>
          </tr>  

        <script type="text/javascript">
          //admin image upload
          jQuery(document).ready(function () {
            var custom_uploader;
            jQuery('#ven_pro_upload').click(function ()
            {
              custom_uploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Image',
                button: {
                  text: 'Add Image'
                },
                multiple: false
              });
              custom_uploader.on('select', function () {
                attachment = custom_uploader.state().get('selection').first().toJSON();
                if (attachment.url)
                {
                  jQuery('#ven_pro_vendor_image').val(attachment.url);
                }
              });
              custom_uploader.open();
            });
          });
        </script>
        <tr>
          <th scope="row"><label><?php echo WC_PM; ?> Percentage</label></th>
          <td><input type="text" placeholder="Enter Percentage here" id="ven_pro_vendor_percentage" name="ven_pro_vendor_percentage" value="<?php echo $get_global_percent_for_vendor; ?>">%</td>
        </tr>
        <tr>
          <th scope="row"><label><?php echo WC_PM; ?> Note</label></th>
          <td><textarea cols="20" rows="2" id="ven_pro_vendor_note" name="ven_pro_vendor_note"></textarea></td>
        </tr>
        <tr>
          <th scope="row"><label>Feature Product</label></th>
          <td><input type="file" name="img_file" id="img_file" /></td>
        </tr>
        <tr>
          <th scope="row" valign="top">Product Custom Field</th>
          <td>
  <?php wcp_custom_degin_panel2(); ?>
          </td>
        </tr>
        <tr>
          <td colspan="2" style="text-align:left"><input type="submit" name="ven_pro_submit" class="button button-primary" id="van_pro_submit" value="Submit" /></td>
        </tr>
        </tbody>
      </table>
    </div>
  </form>
  <?php
  //}
}

function wvs_vendor_users_submenu() {
  $user_id = get_current_user_id();
  $become_a_vendor = get_user_meta($user_id, 'become_a_vendor', true);
  //die($become_a_vendor);
  if ($become_a_vendor == 'vendor_approved') {
    add_users_page('Page Title', 'Upload Product', 'read', 'vendor_pro_upload', 'wvs_vendor_upload_product');
  }
}

function wvs_vendor_product_category_init() {
  register_taxonomy(
        'vendor_category', 'vendor_product', array(
        'label' => __('Category', 'woocommerce-vendor-setup'),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'vendor_category'),
        'hierarchical' => true,
      )
  );
}

function wvs_vendor_meta_box() {
  add_meta_box('Meta Box', '' . __(WC_PM . ' Options', 'woocommerce-vendor-setup') . '', 'wvs_vendor_meta_box_content', 'vendor_product', 'normal', 'high');
}

function wvs_vendor_meta_box_content($post) {
  $vendor_name = get_post_meta($post->ID, '_vendor_name', true);
  $vendor_company = get_post_meta($post->ID, '_vendor_company', true);
  $vendor_email = get_post_meta($post->ID, '_vendor_email', true);
  $vendor_phone = get_post_meta($post->ID, '_vendor_phone', true);
  $vendor_fax = get_post_meta($post->ID, '_vendor_fax', true);

  $vendor_address = get_post_meta($post->ID, '_vendor_address', true);
  $vendor_zip = get_post_meta($post->ID, '_vendor_zip', true);
  $vendor_state = get_post_meta($post->ID, '_vendor_state', true);
  $vendor_country = get_post_meta($post->ID, '_vendor_country', true);
  $vendor_percentage = get_post_meta($post->ID, '_vendor_percentage', true);
  $vendor_paypal = get_post_meta($post->ID, '_vendor_paypal', true);
  $vendor_product_lbl = get_post_meta($post->ID, '_vendor_product_lbl', true);
  $vendor_publish_pro = get_post_meta($post->ID, '_vendor_publish_pro', true);
  $vendor_status = get_post_meta($post->ID, '_vendor_status', true);
  $vendor_details = get_post_meta($post->ID, '_vendor_details', true);
  $vendor_perational_details = get_post_meta($post->ID, '_vendor_perational_details', true);


  if ($vendor_status == '') {
    $vendor_status = 'enable';
  }
  ?>
  <script type="text/javascript">
    function check_email(obj) {
      if (email_validate(obj.value)) {

      } else {
        jQuery('#vendor_email_fld').html('<font style="color:#F00;">&emsp;** Please enter valid emeil address</font>');
      }
    }
    function email_validate(address) {
      var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
      if (reg.test(address) == false) {
        return false;
      } else {
        return true;
      }
    }
    function check_field(fld_name) {
      if (jQuery('#' + fld_name).val().length == 0) {
        jQuery('#' + fld_name + '_fld').html('<font style="color:#F00;">&emsp;** Please enter value</font>');
      }
    }
  </script>
  <table class="form-table">
    <tr>
      <th scope="row"><?php _e(WC_PM . " Name", "woocommerce-vendor-setup"); ?> <span class="wcf_required_field">*</span></th>
      <td><input type="text" name="_vendor_name" id="vendor_name" value="<?php if (isset($vendor_name)) printf(__("%s", "woocommerce-vendor-setup"), $vendor_name); ?>" onChange="check_field('vendor_name');" /><label id="vendor_name_fld"></label></td>
    </tr>
    <tr>
      <th scope="row"><?php _e("Company Name", "woocommerce-vendor-setup"); ?></th>
      <td><input type="text" name="_vendor_company" id="vendor_company" value="<?php if (isset($vendor_company)) printf(__("%s", "woocommerce-vendor-setup"), $vendor_company); ?>" onChange="check_field('vendor_company');" /><label id="vendor_company_fld"></label></td>
    </tr>
    <tr>
      <th scope="row"><?php _e("Email", "woocommerce-vendor-setup"); ?> <span class="wcf_required_field">*</span></th>
      <td><input type="text" name="_vendor_email" id="vendor_email" value="<?php if (isset($vendor_email)) printf(__("%s", "woocommerce-vendor-setup"), $vendor_email); ?>" onChange="check_email(this);" /><label id="vendor_email_fld"></label></td>
    </tr>
    <tr>
      <th scope="row"><?php _e("Phone", "woocommerce-vendor-setup"); ?></th>
      <td><input type="text" name="_vendor_phone" id="vendor_phone" value="<?php if (isset($vendor_phone)) printf(__("%s", "woocommerce-vendor-setup"), $vendor_phone); ?>" onChange="check_field('vendor_phone');" /><label id="vendor_phone_fld"></label></td>
    </tr>
    <tr>
      <th scope="row"><?php _e("Fax", "woocommerce-vendor-setup"); ?></th>
      <td><input type="text" name="_vendor_fax" id="vendor_fax" value="<?php if (isset($vendor_fax)) printf(__("%s", "woocommerce-vendor-setup"), $vendor_fax); ?>" /></td>
    </tr>
    <tr>
      <th scope="row"><?php _e("Address", "woocommerce-vendor-setup"); ?> <span class="wcf_required_field">*</span></th>
      <td><input type="text" name="_vendor_address" id="vendor_address" value="<?php if (isset($vendor_address)) printf(__("%s", "woocommerce-vendor-setup"), $vendor_address); ?>" onChange="check_field('vendor_address');"  /><label id="vendor_address_fld"></label></td>
    </tr>
    <tr>
      <th scope="row"><?php _e("Zip Code", "woocommerce-vendor-setup"); ?></th>
      <td><input type="text" name="_vendor_zip" id="vendor_zip" value="<?php if (isset($vendor_zip)) printf(__("%s", "woocommerce-vendor-setup"), $vendor_zip); ?>" onChange="check_field('vendor_zip');"  /><label id="vendor_zip_fld"></label></td>
    </tr>
    <tr>
      <th scope="row"><?php _e("State", "woocommerce-vendor-setup"); ?></th>
      <td><input type="text" name="_vendor_state" id="vendor_state" value="<?php if (isset($vendor_state)) printf(__("%s", "woocommerce-vendor-setup"), $vendor_state); ?>" onChange="check_field('vendor_state');"  /><label id="vendor_state_fld"></label></td>
    </tr>
    <tr>
      <th scope="row"><?php _e("Country", "woocommerce-vendor-setup"); ?> <span class="wcf_required_field">*</span></th>
      <td><input type="text" name="_vendor_country" id="vendor_country" value="<?php if (isset($vendor_country)) printf(__("%s", "woocommerce-vendor-setup"), $vendor_country); ?>" onChange="check_field('vendor_country');"  /><label id="vendor_country_fld"></label></td>
    </tr>
    <tr>
      <th scope="row"><?php _e("Admin Percentage", "woocommerce-vendor-setup"); ?> <span class="wcf_required_field">*</span></th>
      <td><input type="text" name="_vendor_percentage" id="vendor_percentage" value="<?php if (isset($vendor_percentage)) printf(__("%s", "woocommerce-vendor-setup"), $vendor_percentage); ?>" onChange="check_field('vendor_percentage');"  />%<label id="vendor_percentage_fld"></label></td>
    </tr>
    <tr>
      <th scope="row"><?php _e("Paypal Address", "woocommerce-vendor-setup"); ?></th>
      <td><input type="text" name="_vendor_paypal" id="vendor_paypal" value="<?php if (isset($vendor_paypal)) printf(__("%s", "woocommerce-vendor-setup"), $vendor_paypal); ?>" onChange="check_field('vendor_paypal');"  /><label id="vendor_paypal_fld"></label></td>
    </tr>
    <tr>
      <th scope="row"><?php _e("All Product Label", "woocommerce-vendor-setup"); ?></th>
      <td><input type="text" name="_vendor_product_lbl" id="vendor_product_lbl" value="<?php if (isset($vendor_product_lbl)) printf(__("%s", "woocommerce-vendor-setup"), $vendor_product_lbl); ?>" onChange="check_field('vendor_product_lbl');"  /><label id="vendor_product_lbl_fld"></label></td>
    </tr>
    <tr>
      <th scope="row">Can Publish Product</th>
      <td>
        <select name="_vendor_publish_pro" id="vendor_publish_pro">
          <option value="pending" <?php if ($vendor_publish_pro == 'pending') echo 'selected="selected"'; ?>>Pending</option>
          <option value="publish" <?php if ($vendor_publish_pro == 'publish') echo 'selected="selected"'; ?>>Publish</option>
        </select>
      </td>
    </tr>
    <tr>
      <th scope="row">Status</th>
      <td>

        <select name="_vendor_status" id="vendor_status">
          <option value="enable" <?php if ($vendor_status == 'enable') echo 'selected="selected"'; ?>>Enable</option>
          <option value="disable" <?php if ($vendor_status == 'disable') echo 'selected="selected"'; ?>>Disable</option>
        </select>
      </td>
    </tr>
    <tr>
      <th scope="row">Details</th>
      <td>
        <?php
        wp_editor(html_entity_decode($vendor_details), '_vendor_details', array('textarea_rows' => 2, 'textarea_name' => '_vendor_details'));
        ?>
      </td>
    </tr>
    <tr>
      <th scope="row">Operational Details</th>
      <td>
        <input type="text" style="width:230px; display:none;" value="" class="rounded hasDatepicker" name="dtpdate" id="dtpdate">
        <?php
        echo get_option('_vendor_perational_details');
        wp_editor(html_entity_decode($vendor_perational_details), '_vendor_perational_details', array('textarea_rows' => 2, 'textarea_name' => '_vendor_perational_details'));
        ?>
      </td>
    </tr>
  </table>

  <div style="clear:both;"></div>

  <?php
}

//send custom Email
//wfm_new_customer_registered_send_email_admin('2', '64649646', 'r@r.com');
function wfm_new_customer_registered_send_email_admin($user_id, $random_password, $to) {
  require_once(ABSPATH . 'wp-includes/pluggable.php');
  require_once(ABSPATH . 'wp-load.php');
  //wp_mail( 'e@e.com', 'hello', 'test', '');
  //die('+++*++++');

  /* $myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
    if ( $myaccount_page_id ) {
    $myaccount_page_url = get_permalink( $myaccount_page_id );
    } */
  $user_info = get_userdata($user_id);
  $username = $user_info->user_login;
  ob_start();
  do_action('woocommerce_email_header', 'New customer registered');
  $email_header = ob_get_clean();
  if (ob_get_contents())
    ob_end_clean();
  ob_start();
  do_action('woocommerce_email_footer', 'New customer registered');
  $email_footer = ob_get_clean();
  if (ob_get_contents())
    ob_end_clean();
  //$email_content=$email_header;
  $email_content = '<p>We have added you as a restaurant owner.</p>';
  $email_content.='<p>Your username is: ' . $username . '</p>';
  $email_content.='<p>Your password has been automatically generated: ' . $random_password . '</p>';
  //$email_content.='<p>You can access your account area and change your password here: '.$myaccount.'</p>';
  $email_content.=$email_footer;
  $subject = get_bloginfo('name') . ' - New customer registered';
  wp_mail($to, $subject, $email_content, $email_header);
}

function wvs_save_vendor_info_content($post_id) {
  
  //echo '<pre>';
  //print_r($_POST);
  //die('++++++++++++++++++');
  //_020
  if (isset($_POST["_vendor_email"])) {
    $user_email = explode("@", $_POST["_vendor_email"]);
    $user_name = $user_email[0];
    $user_id = username_exists($user_name);
    //echo $user_id;
    //die('okzzzzzz');
    if (!$user_id && email_exists($_POST["_vendor_email"]) == false) {
      $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
      $user_id = wp_create_user($user_name, $random_password, $_POST["_vendor_email"]);
      wfm_new_customer_registered_send_email_admin($user_id, $random_password, $_POST["_vendor_email"]);
      update_post_meta($post_id, '_vendor_user_id', $user_id);
      update_user_meta($user_id, '_vendor_post_id', $post_id);
      update_user_meta($user_id, 'become_a_vendor_status', 'Approved');
      update_user_meta($user_id, 'become_a_vendor', 'vendor_approved');

      if ($user_id != '') {
        update_post_meta($post_id, '_vendor_name', $_POST['_vendor_name']);
        update_post_meta($post_id, '_vendor_company', $_POST["_vendor_company"]);
        update_post_meta($post_id, '_vendor_email', $_POST["_vendor_email"]);
        update_post_meta($post_id, '_vendor_phone', $_POST["_vendor_phone"]);
        update_post_meta($post_id, '_vendor_fax', $_POST["_vendor_fax"]);

        update_post_meta($post_id, '_vendor_address', $_POST['_vendor_address']);
        update_post_meta($post_id, '_vendor_zip', $_POST["_vendor_zip"]);
        update_post_meta($post_id, '_vendor_state', $_POST["_vendor_state"]);
        update_post_meta($post_id, '_vendor_country', $_POST["_vendor_country"]);
        update_post_meta($post_id, '_vendor_percentage', $_POST["_vendor_percentage"]);
        update_post_meta($post_id, '_vendor_paypal', $_POST["_vendor_paypal"]);
        update_post_meta($post_id, '_vendor_product_lbl', $_POST["_vendor_product_lbl"]);
        update_post_meta($post_id, '_vendor_publish_pro', $_POST["_vendor_publish_pro"]);
        update_post_meta($post_id, '_vendor_status', $_POST["_vendor_status"]);
        update_post_meta($post_id, '_vendor_details', $_POST["_vendor_details"]);
        update_post_meta($post_id, '_vendor_perational_details', $_POST["_vendor_perational_details"]);
        update_option($user_name, $post_id);
      }
    } else {
      update_post_meta($post_id, '_vendor_user_id', $user_id);
      update_user_meta($user_id, '_vendor_post_id', $post_id);
      update_user_meta($user_id, 'become_a_vendor_status', 'Approved');
      update_user_meta($user_id, 'become_a_vendor', 'vendor_approved');
            
      $user_email = explode("@", $_POST["_vendor_email"]);
      $user_name = $user_email[0];
      update_post_meta($post_id, '_vendor_name', $_POST['_vendor_name']);
      update_post_meta($post_id, '_vendor_company', $_POST["_vendor_company"]);
      update_post_meta($post_id, '_vendor_email', $_POST["_vendor_email"]);
      update_post_meta($post_id, '_vendor_phone', $_POST["_vendor_phone"]);
      update_post_meta($post_id, '_vendor_fax', $_POST["_vendor_fax"]);

      update_post_meta($post_id, '_vendor_address', $_POST['_vendor_address']);
      update_post_meta($post_id, '_vendor_zip', $_POST["_vendor_zip"]);
      update_post_meta($post_id, '_vendor_state', $_POST["_vendor_state"]);
      update_post_meta($post_id, '_vendor_country', $_POST["_vendor_country"]);
      update_post_meta($post_id, '_vendor_percentage', $_POST["_vendor_percentage"]);
      update_post_meta($post_id, '_vendor_paypal', $_POST["_vendor_paypal"]);
      update_post_meta($post_id, '_vendor_product_lbl', $_POST["_vendor_product_lbl"]);
      update_post_meta($post_id, '_vendor_publish_pro', $_POST["_vendor_publish_pro"]);
      update_post_meta($post_id, '_vendor_status', $_POST["_vendor_status"]);
      update_post_meta($post_id, '_vendor_details', $_POST["_vendor_details"]);
      update_post_meta($post_id, '_vendor_perational_details', $_POST["_vendor_perational_details"]);
      update_option($user_name, $post_id);
    }
  }
}

add_filter('manage_edit-vendor_product_columns', 'wvs_edit_vendor_product_columns');

function wvs_edit_vendor_product_columns($columns) {
  $columns = array(
      //'cb'            => '<input type="checkbox" />',
      'title' => __('Name', 'woocommerce-vendor-setup'),
      'company' => __('Company', 'woocommerce-vendor-setup'),
      'email' => __('Email', 'woocommerce-vendor-setup'),
      'paypal' => __('Paypal', 'woocommerce-vendor-setup'),
      'status' => __('Status', 'woocommerce-vendor-setup'),
      'percentage' => __('Percentage', 'woocommerce-vendor-setup'),
      'date' => __('Date', 'woocommerce-vendor-setup')
  );
  return $columns;
}

add_action('manage_vendor_product_posts_custom_column', 'wvs_manage_vendor_product_columns', 10, 2);

function wvs_manage_vendor_product_columns($column, $post_id) {
  global $post;

  switch ($column) {
    case 'company' :
      $vendor_company = get_post_meta($post_id, '_vendor_company', true);
      echo "<a href='admin.php?page=vendor_details&vendor_id=$post_id'>$vendor_company</a>";
      break;

    case 'email' :
      $vendor_email = get_post_meta($post_id, '_vendor_email', true);
      echo $vendor_email;
      break;

    case 'paypal' :
      $vendor_paypal = get_post_meta($post_id, '_vendor_paypal', true);
      echo $vendor_paypal;
      break;

    case 'status' :
      $vendor_status = get_post_meta($post_id, '_vendor_status', true);
      echo $vendor_status;
      break;

    case 'percentage' :
      $vendor_percentage = get_post_meta($post_id, '_vendor_percentage', true);
      if (!empty($vendor_percentage)) {
        echo $vendor_percentage . '%';
      }
      break;

    default :
      break;
  }
}

add_action('woocommerce_thankyou', 'wvs_woo_vendor_order_data');

function wvs_woo_vendor_order_data($order_id) {
  global $wpdb;
  //require_once WP_CUSTOM_PRODUCT_PATH.'PHPMailer-master/class.phpmailer.php';
  $item_arr = array();
  $i = 0;
  $vendor_table_prefix = 'woocommerce_';
  $order = new WC_Order($order_id);
  $items = $order->get_items();
  
  foreach ($items as $item) {
    $item_arr[$i]['name'] = $item['name'];
    $item_arr[$i]['product_id'] = $item['product_id'];

    $item_arr[$i]['vendor_id'] = get_post_meta($item['product_id'], '_vendor_select', true);
    $item_arr[$i]['vendor_percentage'] = get_post_meta($item['product_id'], '_vendor_percentage', true);
    $item_arr[$i]['product_qty'] = $item['qty'];
    $item_arr[$i]['product_subtotal'] = $item['line_subtotal'];
    $wpdb->insert($wpdb->prefix . $vendor_table_prefix . 'vendor', array(
        'vendor_id' => '',
        'vendor_vendor_id' => $item_arr[$i]['vendor_id'],
        'vendor_product_id' => $item_arr[$i]['product_id'],
        'vendor_product_name' => $item_arr[$i]['name'],
        'vendor_product_qty' => $item_arr[$i]['product_qty'],
        'vendor_product_unit_price' => ($item_arr[$i]['product_subtotal'] / $item_arr[$i]['product_qty']),
        'vendor_product_amount' => $item_arr[$i]['product_subtotal'],
        'vendor_percent' => $item_arr[$i]['vendor_percentage'],
        'vendor_amount' => (($item_arr[$i]['vendor_percentage'] * $item_arr[$i]['product_subtotal']) / 100),
        'vendor_order_id' => $order->get_id(),
        'vendor_order_date' => date("Y-m-d"),
        'vendor_send_money_status' => 'Pending',
        'vendor_send_money_date' => '',
        'vendor_product_delivared' => 'Pending'
            )
    );
    
    $to = get_post_meta($item_arr[$i]['vendor_id'], '_vendor_email', true);

    $subject = '' . __('A order has been reseived, Order ID is ', 'woocommerce-vendor-setup') . '' . $order_id;

    $message = '<!DOCTYPE HTML>' .
            '<head>' .
            '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' .
            '<title>' . __('Email notification ', 'woocommerce-vendor-setup') . '</title>' .
            '</head>' .
            '<body>' .
            '<table style="width:100%;">' .
            '<tr>' .
            '<td align="center">' .
            '<table style="width:60%; border:solid 1px #69F;">' .
            '<tr style="width:100%;">' .
            '<td style="width:100%; background:#69F; font-size:46px; color:#FFF; font-weight:bold;" height="100px;">&nbsp;' . __('New customer order', 'woocommerce-vendor-setup') . '</td>' .
            '</tr>' .
            '<tr style="width:100%;">' .
            '<td style="width:100%;">' . __('You have received an order. The order is as follows:', 'woocommerce-vendor-setup') . '</td>' .
            '</tr>' .
            '<tr>' .
            '<td>&nbsp;</td>' .
            '</tr>' .
            '<tr style="width:100%;">' .
            '<td style="width:100%; font-size:30px; color:#000; font-weight:bold;">' . __('Order', 'woocommerce-vendor-setup') . ': #' . $order_id . ' (' . date("F j, Y, g:i a") . ')</td>
									</tr>' .
            '<tr>' .
            '<td>&nbsp;</td>' .
            '</tr>' .
            '<tr style="width:100%;">' .
            '<td style="width:100%;">' .
            '<table style="width:100%;">' .
            '<tr>' .
            '<td style="font-weight:bold; color:#000;">' . __('Product Name', 'woocommerce-vendor-setup') . '</td>' .
            '<td>:</td>' .
            '<td>' . $item_arr[$i]['name'] . '</td>' .
            '</tr>' .
            '<tr>' .
            '<td style="font-weight:bold; color:#000;">' . __('Product Quantity', 'woocommerce-vendor-setup') . '</td>' .
            '<td>:</td>' .
            '<td>' . $item_arr[$i]['product_qty'] . '</td>' .
            '</tr>' .
            '<tr>' .
            '<td style="font-weight:bold; color:#000;">' . __('Product Price', 'woocommerce-vendor-setup') . '</td>' .
            '<td>:</td>' .
            '<td>' . $item_arr[$i]['product_subtotal'] . '</td>' .
            '</tr>' .
            '<tr>' .
            '<td style="font-weight:bold; color:#000;">' . __('Your Percentage', 'woocommerce-vendor-setup') . '</td>' .
            '<td>:</td>' .
            '<td>' . $item_arr[$i]['vendor_percentage'] . '%</td>' .
            '</tr>' .
            '<tr>' .
            '<td style="font-weight:bold; color:#000;">' . __('Your Ammount', 'woocommerce-vendor-setup') . '</td>' .
            '<td>:</td>' .
            '<td>' . (($item_arr[$i]['vendor_percentage'] * $item_arr[$i]['product_subtotal']) / 100) . '</td>' .
            '</tr>' .
            '</table>' .
            '</td>' .
            '</tr>' .
            '<tr>' .
            '<td>&nbsp;</td>' .
            '</tr>' .
            '<tr style="width:100%;">' .
            '<td style="width:100%;">' . __('Please contact with stote owner for your payment.', 'woocommerce-vendor-setup') . '<br /><br />' . __('Thank you', 'woocommerce-vendor-setup') . '</td>' .
            '</tr>' .
            '<tr>' .
            '<td>&nbsp;</td>' .
            '</tr>' .
            '<tr style="width:100%;">' .
            '<td style="width:100%; background:#69F; color:#FFF;" height="50px;">&emsp;</td>' .
            '</tr>' .
            '</table>' .
            '</td>' .
            '</tr>' .
            '</table>' .
            '</body>' .
            '</html>';
    //$headers = 'From: Your Namea9 <your@email.com>' . "\r\n";
    $headers = 'From:'.get_option('blogname').'<'.get_option('admin_email').'>' . "\r\n";
    
          
    wp_mail($to, $subject, $message, $headers);
    $i++;
  }
}

function send_mail($from_mail, $to_mail, $message, $subject) {
  $mail = new PHPMailer();
  $body = eregi_replace("[\]", '', $message);
  $mail->AddReplyTo($from_mail);
  $mail->SetFrom($from_mail);
  $mail->AddReplyTo($from_mail);
  $mail->AddAddress($to_mail);
  $mail->Subject = $subject;
  $mail->AltBody = "To view the message, please use an HTML compatible email viewer!";
  $mail->MsgHTML($body);

  if (!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
  } else {
    echo "Message sent!";
  }
}

add_action('init', 'wvs_vendor_product_post_type');
add_action('init', 'wvs_vendor_product_category_init');
add_action('admin_menu', 'wvs_vendor_users_submenu');
add_action('add_meta_boxes', 'wvs_vendor_meta_box');
add_action('save_post', 'wvs_save_vendor_info_content', 10, 2);

//----------------------------------

//add_filter( 'wp_insert_post_data' , 'filter_post_data' , '99', 2 );

function filter_post_data( $data , $postarr ) {
  
  if(isset($_POST['post_type'])){
    if($_POST['post_type']=='vendor_product'){
      
       if (isset($_POST["_vendor_email"])) {
         if($_POST["_vendor_email"]!=''){
           
              $user_email = explode("@", $_POST["_vendor_email"]);
              $user_name = $user_email[0];
              $user_id = username_exists($user_name);
           
           
           
                 $args = array(
          'meta_query' => array(
              array(
                        'key' => '_vendor_user_id',
                        'value' => $user_id
                    )
                )
             );
             $query = new WP_Query($args);
             
             echo '<pre>';
             print_r($query);
             die('+++++++++++++');
             
           
           
           
         }
         
       }
      
      


         
      
      echo '<pre>';
      print_r($postarr);
      
      //return 0;
      
      
      die('----------------------------');
      
      
    }
        
  }
  
  

    return $data;
}

/*=========================================*/
