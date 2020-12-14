<?php
//add_shortcode('wrs_restaurant', 'wrs_restaurant_menu2');
add_filter('the_content', 'wrs_restaurant_menu2');

function wrs_restaurant_menu2($content) {
  ob_start();
  global $wpdb;
  if (isset($GLOBALS['post']->ID)) {
    $post_type = get_post_type($GLOBALS['post']->ID);
    if ($post_type == 'vendor_product') {
      $rp_id = get_post_meta($GLOBALS['post']->ID, 'wrs_res_id', true);
      $user_meta_id = get_post_meta($rp_id, '_vendor_user_id');
      $user = $wpdb->get_row("SELECT * FROM $wpdb->usermeta WHERE umeta_id = '$user_meta_id[0]'");
      //$r_user_id = $user->user_id;
      $r_user_id = get_post_meta($GLOBALS['post']->ID, '_vendor_user_id', true);
      //-----------------------       
      $where = get_posts_by_author_sql('product', '', $r_user_id, '');
      $query = "SELECT ID FROM $wpdb->posts where $where";
      $user_post = $wpdb->get_results($query);
      echo $content;
      if (empty($user_post)) {
        echo '<div class="wfm_menu_not_found">Sorry No Menu Found</div>';
        echo '<style>.glossymenu{display: none;}</style>';
      } else {
        echo '<style>.glossymenu{display: block;}</style>';
        $user_item = array();
        foreach ($user_post as $item) {
          array_push($user_item, $item->ID);
        }
        echo '<div>';
        echo wfm_product('', $user_item);
        echo '</div>';
      }
      //--------------------------      
    } else {
      echo $content;
    }
  }
  //
  $output_string = ob_get_contents();
  ob_end_clean();
  return $output_string;
}

function wrs_restaurant_menu() {
  ob_start();
  global $post, $wpdb;
  if (isset($post->ID)) {
    $post_type = get_post_type($post->ID);
    if ($post_type == 'job_listing') {
      $rp_id = get_post_meta($post->ID, 'wrs_res_id', true);
      $user_meta_id = get_post_meta($rp_id, '_vendor_user_id');
      $user = $wpdb->get_row("SELECT * FROM $wpdb->usermeta WHERE umeta_id = '$user_meta_id[0]'");
      $r_user_id = $user->user_id;
      //-----------------------       
      $where = get_posts_by_author_sql('product', '', $r_user_id, '');
      $query = "SELECT ID FROM $wpdb->posts where $where";
      $user_post = $wpdb->get_results($query);

      if (empty($user_post)) {
        echo '<div class="wfm_menu_not_found">Sorry No Menu Found</div>';
        echo '<style>.glossymenu{display: none;}</style>';
      } else {
        echo '<style>.glossymenu{display: block;}</style>';
        $user_item = array();
        foreach ($user_post as $item) {
          array_push($user_item, $item->ID);
        }
        echo '<div>';
        echo wfm_product('', $user_item);
        echo '</div>';
      }
      //--------------------------      
    }
  }
  //
  $output_string = ob_get_contents();
  ob_end_clean();
  return $output_string;
}

function wvs_restaurant_list_css_save(){
  update_option('wvs_r_search_btn_bg', $_POST['wvs_r_search_btn_bg']);
  update_option('wvs_r_search_btn_text', $_POST['wvs_r_search_btn_text']);
  update_option('wvs_r_search_input_bg', $_POST['wvs_r_search_input_bg']);
  update_option('wvs_r_search_input_text', $_POST['wvs_r_search_input_text']);
  update_option('wvs_r_search_border', $_POST['wvs_r_search_border']);
  
  update_option('wvs_r_search_result_title', $_POST['wvs_r_search_result_title']);
  update_option('wvs_r_search_result_address', $_POST['wvs_r_search_result_address']);
  update_option('wvs_r_search_result_country', $_POST['wvs_r_search_result_country']);
  update_option('wvs_r_search_result_phone', $_POST['wvs_r_search_result_phone']);
  update_option('wvs_r_search_result_background', $_POST['wvs_r_search_result_background']);
}

function wvs_restaurant_list_css_reset(){
  update_option('wvs_r_search_btn_bg', '1bba9a');
  update_option('wvs_r_search_btn_text', 'FFFFFF');
  update_option('wvs_r_search_input_bg', 'FFFFFF');
  update_option('wvs_r_search_input_text', '000000');
  update_option('wvs_r_search_border', '42454E');  
  update_option('wvs_r_search_result_title', 'FC7B43');
  update_option('wvs_r_search_result_address', 'FFFFFF');
  update_option('wvs_r_search_result_country', 'FFFFFF');
  update_option('wvs_r_search_result_phone', 'E8F963');
  update_option('wvs_r_search_result_background', '189E83');
}

function wvs_restaurant_list_css_setting() {
  if(!get_option('wvs_r_search_btn_bg')){
    wvs_restaurant_list_css_reset();
  }
  if (isset($_POST['r_search_setting_submit']) && $_POST['r_search_setting_submit'] == 1) {
    wvs_restaurant_list_css_save();
  }else if (isset($_POST['r_search_setting_submit']) && $_POST['r_search_setting_submit'] == 2) {
    wvs_restaurant_list_css_reset();
  }
  ?>
  <h2>CSS Settings:</h2>
  <form method="post" id="r_form" name="r_form">	
      <input type="hidden" name="r_search_setting_submit" id="r_search_setting_submit" value="2"  />
      <div class="wrap">
          <table class="widefat">
              <tr>
                  <td style="width: 30%;">Search button background:</td>
                  <td style="width: 60%;">
                      <input type="text" name="wvs_r_search_btn_bg" size="10" id="wvs_r_search_btn_bg" class="color" value="<?php echo get_option('wvs_r_search_btn_bg'); ?>" /> 
                  </td>
              </tr>
              <tr>
                  <td style="width: 30%;">Search button text Color:</td>
                  <td style="width: 60%;">
                      <input type="text" name="wvs_r_search_btn_text" size="10" id="wvs_r_search_btn_text" class="color" value="<?php echo get_option('wvs_r_search_btn_text'); ?>" /> 
                  </td>
              </tr>
              <tr>
                  <td style="width: 30%;">Search box BG color:</td>
                  <td style="width: 60%;">
                      <input type="text" name="wvs_r_search_input_bg" size="10" id="wvs_r_search_input_bg" class="color" value="<?php echo get_option('wvs_r_search_input_bg'); ?>" /> 
                  </td>
              </tr>
              <tr>
                  <td style="width: 30%;">Search box text color:</td>
                  <td style="width: 60%;">
                      <input type="text" name="wvs_r_search_input_text" size="10" id="wvs_r_search_input_text" class="color" value="<?php echo get_option('wvs_r_search_input_text'); ?>" /> 
                  </td>
              </tr>
              <tr>
                  <td style="width: 30%;">Search border Color:</td>
                  <td style="width: 60%;">
                      <input type="text" name="wvs_r_search_border" size="10" id="wvs_r_search_border" class="color" value="<?php echo get_option('wvs_r_search_border'); ?>" /> 
                  </td>
              </tr>
              <tr>
                  <td style="width: 30%;">Restaurant title text color:</td>
                  <td style="width: 60%;">
                      <input type="text" name="wvs_r_search_result_title" size="10" id="wvs_r_search_result_title" class="color" value="<?php echo get_option('wvs_r_search_result_title'); ?>" /> 
                  </td>
              </tr>
              <tr>
                  <td style="width: 30%;">Restaurant address text color:</td>
                  <td style="width: 60%;">
                      <input type="text" name="wvs_r_search_result_address" size="10" id="wvs_r_search_result_address" class="color" value="<?php echo get_option('wvs_r_search_result_address'); ?>" /> 
                  </td>
              </tr>
              <tr>
                  <td style="width: 30%;">Restaurant country text color:</td>
                  <td style="width: 60%;">
                      <input type="text" name="wvs_r_search_result_country" size="10" id="wvs_r_search_result_country" class="color" value="<?php echo get_option('wvs_r_search_result_country'); ?>" /> 
                  </td>
              </tr>
              <tr>
                  <td style="width: 30%;">Restaurant phone text color:</td>
                  <td style="width: 60%;">
                      <input type="text" name="wvs_r_search_result_phone" size="10" id="wvs_r_search_result_phone" class="color" value="<?php echo get_option('wvs_r_search_result_phone'); ?>" /> 
                  </td>
              </tr>
              <tr>
                  <td style="width: 30%;">Background Color:</td>
                  <td style="width: 60%;">
                      <input type="text" name="wvs_r_search_result_background" size="10" id="wvs_r_search_result_background" class="color" value="<?php echo get_option('wvs_r_search_result_background'); ?>" /> 
                  </td>
              </tr>
              <tr valign="top">
                  <td colspan="2" scope="row">			
                      <input type="button" name="save" onclick="document.getElementById('r_search_setting_submit').value = '1'; document.getElementById('r_form').submit();" value="Save" class="button-primary" />
                      <input type="button" name="reset" onclick="document.getElementById('r_search_setting_submit').value = '2'; document.getElementById('r_form').submit();" value="Reset" class="button-primary" />
                  </td> 
              </tr>
          </table> 
      </div> 
  </form>
  <?php
}