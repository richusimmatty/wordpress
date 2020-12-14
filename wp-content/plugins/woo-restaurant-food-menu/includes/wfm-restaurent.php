<?php
add_action('init', 'wfm_init_restaurant');

function wfm_init_restaurant() {
  wp_enqueue_style('jquery.autocomplete-css', WFM_BASE_URL . '/restaurant/css/jquery.autocomplete.css');
  wp_enqueue_style('wfm-table-style-css', WFM_BASE_URL . '/restaurant/css/wfm_table_style.css');
  wp_enqueue_script('jquery');
  wp_enqueue_script('jquery.autocomplete-jscolor', WFM_BASE_URL . '/restaurant/js/jquery.autocomplete.js');
}

add_shortcode('wfm_restaurant', 'wfm_restaurant_search');
add_shortcode('wfm_restaurant_search_btn', 'wfm_restaurant_search_btn');
add_shortcode('wfm_restaurant_data', 'wfm_restaurant_search_result');

function wfm_restaurant_search() {  
  global $woocommerce, $wpdb;

  if (!class_exists('Woocommerce')) {
    echo '<div id="message" class="error"><p>Please Activate Wp WooCommerce Plugin</p></div>';
    $var = ob_get_contents();
    ob_end_clean();
    return $var;
  }

  if (!function_exists('wvs_adding_extra_reg_fields')) {
    echo '<div id="message" class="error"><p>Please Activate Restaurant Plugin</p></div>';
    return '';
  }
  $wfm_tag = '';
  if (isset($_REQUEST['tag'])) {
    $wfm_tag = $_REQUEST['tag'];
  }
  if (isset($_POST['wfm_tag'])) {
    $wfm_tag = $_POST['wfm_tag'];
  }
  ?>
  <script>
    jQuery(document).ready(function () {
      /*
       var plugin_url='<? //echo plugins_url();  ?>/woo-restaurant-food-menu/includes/wfm-autocomplete.php';
       jQuery("#wfm_tag").autocomplete(plugin_url, {
       selectFirst: true
       });*/
    });
  </script>
  <div class="wfm_frm" id="wfm_frm">
    <form method="post" action="<?php echo get_permalink(); ?>" enctype="multipart/form-data">
        <div class="wfm_frm_search">
            <input type="search" name="wfm_tag" value="<?php echo $wfm_tag; ?>" placeholder="Search Restaurant here" id="wfm_tag" size="40"/>
            <input type="submit" value="Search" name="completedsearch" />
        </div>
    </form>
  </div>  

  <?php
  global $wpdb;
  if ($wfm_tag != '') {
    //----------      
    $pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
    $limit = 4;
    $offset = ( $pagenum - 1 ) * $limit;
    //------------
    $table_res = 'tag';
    $tag = $wfm_tag;

    $args = array('post_type' => 'vendor_product',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => '_vendor_name',
                'value' => $tag,
                'compare' => 'LIKE'
            ),
            array(
                'key' => '_vendor_zip',
                'value' => $tag,
                'compare' => 'LIKE'
            ),
            array(
                'key' => '_vendor_address',
                'value' => $tag,
                'compare' => 'LIKE'
            ),
            array(
                'key' => '_vendor_country',
                'value' => $tag,
                'compare' => 'LIKE'
            ),
            array(
                'key' => '_vendor_state',
                'value' => $tag,
                'compare' => 'LIKE'
            )
        ),
        'orderby' => 'title',
        'order' => 'ASC',
        'posts_per_page' => $limit,
        //'number'     =>  $limit,           
        'offset' => $offset
    );

    $loop = new WP_Query($args);

    $t = 0;

    if ($loop->have_posts()) {
      echo '<div class="wrs_rs_wrap">';
      foreach ($loop->posts as $val) {

        
        $r = get_post_meta($val->ID, '_vendor_name');
        $user_meta_id = get_post_meta($val->ID, '_vendor_user_id');
        /* echo '<pre>';
          print_r($user_meta_id);
          die('++++++++++'); */
        $user = $wpdb->get_row("SELECT * FROM $wpdb->usermeta WHERE umeta_id = '$user_meta_id[0]'");
        
//        echo '<pre>';
//        print_r($user_meta_id);
//        die('++++++++++++');
        $current_page_url = add_query_arg('rid', $user->user_id, get_permalink());
        $current_page_url = add_query_arg('pid', $val->ID, $current_page_url);
        //echo '<tr><td><a href="'.$current_page_url.'">'.$r[0]."</a></td></tr>"; 
        //--------------------------------------------
        $feat_image = wp_get_attachment_url(get_post_thumbnail_id($val->ID));
        if (!$feat_image) {
          $feat_image = WFM_BASE_URL . '/images/restaurant-default.jpg';
        }
        $hname = get_post_meta($val->ID, '_vendor_name');

        echo '<div class="wrs_rs_box">';        
          $current_page_url = add_query_arg('rid', $user->user_id, get_permalink());
          $current_page_url = add_query_arg('pid', $val->ID, $current_page_url);
        
        ?>
        <div class="wrs_rs_box_inner" onclick="document.location='<?php echo $current_page_url;?>'" style="background-image: url(<?php echo $feat_image; ?>); cursor:pointer;">
        <?php
        echo '</div>'; //   end div box, boox inner, box title
        echo wfm_vendor_info($val->ID, 0, $user->user_id);
        echo '<div style="clear:both;"></div>';
        echo '</div>';
        //--------------------------------------------
      }
      //die();
      echo '</div>';
      //--------pagi------------------------------  
      $total = wfm_count_search_result($tag);
      $num_of_pages = ceil($total / $limit);
      $page_links = paginate_links(array(
          'base' => add_query_arg('pagenum', '%#%'),
          'add_args' => array('tag' => $tag),
          'format' => '',
          'prev_text' => __('&laquo;'),
          'next_text' => __('&raquo;'),
          'total' => $num_of_pages,
          'hid' => $tag,
          'current' => $pagenum
      ));

      if ($page_links) {
        echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
      }


      //--------------------------------------
      //echo '</table>';
    } else {
      echo 'Sorry no result found';
    }
  }
  $user_item = array();
  if (isset($_GET['rid'])) {
    $r = get_post_meta($_GET['rid'], '_vendor_name');
    $user_meta_id = get_post_meta($_GET['pid'] ,'_vendor_user_id',true);    
    $args_publish = array(
		  'author'     =>  $user_meta_id,
		  'post_type'  => 'product',
		  'post_status'  => 'publish'
	  );	  
	  $user_post = get_posts( $args_publish );
    if (empty($user_post)) {
      echo '<div class="wfm_menu_not_found">Sorry No Menu Found</div>';
      echo '<style>.glossymenu{display: none;}</style>';
    } else {
      echo '<style>.glossymenu{display: block;}</style>';
      if (isset($_GET['pid'])) {
        $hname = get_post_meta($_GET['pid'], '_vendor_name', true);
        if (!empty($hname)) {
          echo '<h2>' . $hname . '</h2>';
        }
      }

      foreach ($user_post as $item) {
        array_push($user_item, $item->ID);
      }
      echo '<div style="width:100%;">';
      echo wfm_product('', $user_item);
      echo '</div>';
      echo wfm_vendor_info($_GET['pid'], 1, $_GET['rid']);
    }
  }
}

function wfm_restaurant_search_btn() {
  global $wpdb;
  if (!class_exists('Woocommerce')) {
    echo '<div id="message" class="error"><p>Please Activate Wp WooCommerce Plugin</p></div>';
    $var = ob_get_contents();
    ob_end_clean();
    return $var;
  }

  if (!function_exists('wvs_adding_extra_reg_fields')) {
    echo '<div id="message" class="error"><p>Please Activate Restaurant Plugin</p></div>';
    return '';
  }
  ?>
  <script>
    jQuery(document).ready(function () {
      var plugin_url = '<? echo plugins_url(); ?>/woo-restaurant-food-menu/includes/wfm-autocomplete.php';
      jQuery("#wfm_tag").autocomplete(plugin_url, {
        selectFirst: true
      });
    });
  </script>
  <div class="wfm_frm" id="wfm_frm">
    <form method="post" action="<?php echo get_permalink(); ?>" enctype="multipart/form-data">
        <div class="wfm_frm_search">            
            <input type="search" name="wfm_tag" placeholder="Search Restaurant here" id="wfm_tag" size="40"/>
            <input type="submit" value="Search" name="completedsearch" />
        </div>
    </form>
  </div>
  <?php
}

function wfm_restaurant_search_result() {  
  global $wpdb;
  if (isset($_POST['wfm_tag']) && $_POST['wfm_tag'] != '') {
    $table_res = 'tag';
    //$tag=mysql_real_escape_string($_POST['wfm_tag']);
    $tag = $_POST['wfm_tag'];
    $args = array('post_type' => 'vendor_product',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => '_vendor_name',
                'value' => $tag,
                'compare' => 'LIKE'
            ),
            array(
                'key' => '_vendor_zip',
                'value' => $tag,
                'compare' => 'LIKE'
            ),
            array(
                'key' => '_vendor_address',
                'value' => $tag,
                'compare' => 'LIKE'
            ),
            array(
                'key' => '_vendor_country',
                'value' => $tag,
                'compare' => 'LIKE'
            ),
            array(
                'key' => '_vendor_state',
                'value' => $tag,
                'compare' => 'LIKE'
            )
        ),
        'orderby' => 'title',
        'order' => 'ASC',
        'posts_per_page' => 300);

    $loop = new WP_Query($args);
    $t = 0;

    if ($loop->have_posts()) {
      echo '<table class="wfm_search_tb">';
      foreach ($loop->posts as $val) {
        $r = get_post_meta($val->ID, '_vendor_name');
        $user_meta_id = get_post_meta($val->ID, '_vendor_user_id');
        $sql = "SELECT * FROM $wpdb->usermeta WHERE umeta_id = '$user_meta_id[0]'";
        $user = $wpdb->get_row($sql);
        $current_page_url = add_query_arg('rid', $user->user_id, get_permalink());
        $current_page_url = add_query_arg('pid', $val->ID, $current_page_url);
        echo '<tr><td><a href="' . $current_page_url . '">' . $r[0] . "</a></td></tr>";
      }
      echo '</table>';
    } else {
      echo 'Sorry no result found';
    }
  }
  $user_item = array();
  if (isset($_GET['rid'])) {
    $r = get_post_meta($_GET['rid'], '_vendor_name');


    $where = get_posts_by_author_sql('product', '', $_GET['rid'], '');
    $query = "SELECT ID FROM $wpdb->posts where $where";
    $user_post = $wpdb->get_results($query);


    if (empty($user_post)) {
      echo '<div class="wfm_menu_not_found">Sorry No Menu Found</div>';
      echo '<style>.glossymenu{display: none;}</style>';
    } else {
      echo '<style>.glossymenu{display: block;}</style>';
      if (isset($_GET['pid'])) {
        $hname = get_post_meta($_GET['pid'], '_vendor_name');
        if (!empty($hname)) {
          echo '<h2>' . $hname[0] . '</h2>';
        }
      }
      foreach ($user_post as $item) {
        array_push($user_item, $item->ID);
      }
      echo '<div>';
      echo wfm_product('', $user_item);
      echo '</div>';
    }
  }
}

function wfm_count_search_result($tag) {
  $args = array('post_type' => 'vendor_product',
      'meta_query' => array(
          'relation' => 'OR',
          array(
              'key' => '_vendor_name',
              'value' => $tag,
              'compare' => 'LIKE'
          ),
          array(
              'key' => '_vendor_zip',
              'value' => $tag,
              'compare' => 'LIKE'
          ),
          array(
              'key' => '_vendor_address',
              'value' => $tag,
              'compare' => 'LIKE'
          ),
          array(
              'key' => '_vendor_country',
              'value' => $tag,
              'compare' => 'LIKE'
          ),
          array(
              'key' => '_vendor_state',
              'value' => $tag,
              'compare' => 'LIKE'
          )
      ),
      'orderby' => 'title',
      'order' => 'ASC'
  );
  $loop = new WP_Query($args);
  $cnt = count($loop->posts);
  return $cnt;
}

function wfm_vendor_info($pid, $p = 0, $rid) {
  
  ob_start();
  if(!get_option('wvs_r_search_btn_bg')){
    wvs_restaurant_list_css_reset();
  }
  ?>
  <style>
    #wfm_frm input[type="submit"] {
        background: #<?php echo get_option('wvs_r_search_btn_bg'); ?>;
        color: #<?php echo get_option('wvs_r_search_btn_text'); ?>;
    }

    #wfm_frm input[type="search"] {
        background: #<?php echo get_option('wvs_r_search_input_bg'); ?>;
        color: #<?php echo get_option('wvs_r_search_input_text'); ?>;
    }

    .wfm_frm_search {
        background: #<?php echo get_option('wvs_r_search_border'); ?>;
    }

    .wrs_rs_name a{
      color: #<?php echo get_option('wvs_r_search_result_title'); ?>!important;
    }

    .wrs_rs_address{      
      color: #<?php echo get_option('wvs_r_search_result_address'); ?>!important;
    }

    .wrs_rs_phone a{
      color: #<?php echo get_option('wvs_r_search_result_phone'); ?>!important;
    }
    .wrs_rs_country{
      color: #<?php echo get_option('wvs_r_search_result_country'); ?>!important;
    }
    .wrs_rs_box_title{
         background: #<?php echo get_option('wvs_r_search_result_background'); ?>!important;
    }    
  </style>
  <?php
  
  $current_page_url = add_query_arg('rid', $rid, get_permalink());
  $current_page_url = add_query_arg('pid', $pid, $current_page_url);

  $address_box = 'wrs_rs_box_title';
  $hname_c = 'wrs_rs_name';
  $address = 'wrs_rs_address';
  $phone = 'wrs_rs_phone';
  $country = 'wrs_rs_country';

  if ($p == 1) {
    $address_box = 'wrs_s_res_address_box';
    $hname_c = 'wrs_s_res_name';
    $address = 'wrs_s_res_address';
    $phone = 'wrs_s_res_phone';
  }
  //--------------------------------  
  $hname = get_post_meta($pid, '_vendor_name');
  if (empty($hname)) {
    return '';
  }
  $post_data = get_post_meta($pid);
  $vendor_name = '';
  $vendor_phone = '';
  $vendor_address = '';
  $vendor_state = '';
  $vendor_zip = '';
  $vendor_country = '';

  if (isset($post_data['_vendor_name'][0])) {
    $vendor_name = $post_data['_vendor_name'][0];
  }
  if (isset($post_data['_vendor_phone'][0])) {
    $vendor_phone = $post_data['_vendor_phone'][0];
  }
  if (isset($post_data['_vendor_address'][0])) {
    $vendor_address = $post_data['_vendor_address'][0];
  }
  if (isset($post_data['_vendor_state'][0])) {
    $vendor_state = $post_data['_vendor_state'][0];
  }
  if (isset($post_data['_vendor_zip'][0])) {
    $vendor_zip = $post_data['_vendor_zip'][0];
  }
  if (isset($post_data['_vendor_country'][0])) {
    $vendor_country = $post_data['_vendor_country'][0];
  }

  //for map---------------------------------------
  /*echo $vendor_address;
  echo '<br />';
  echo $vendor_country;
  echo '<br />';
    echo $p;
  echo '<br />';
  die('Hellozzzzzzzzzz');*/
  
  
  
  if ($vendor_address != '' && $vendor_country != '' && $p == 1) {
    
    
    $map_address = $vendor_address . ', ' . $vendor_zip;
    $data_arr = geocode($map_address, $vendor_country);
    $map_url = plugin_dir_url( __FILE__ ).'google_map.js';
      
      
    /*echo '<pre>';
    print_r($data_arr);
    die('++++++++++++++');*/
    if ($data_arr) {
      $latitude = $data_arr[0];
      $longitude = $data_arr[1];
      $formatted_address = $data_arr[2];
      ?>
      <!-- google map will be shown here -->
      <div id="gmap_canvas">Loading map...</div>
      <!-- JavaScript to show google map -->
<!--      <script type="text/javascript" src="<?php //echo $map_url;?>"></script>-->
<!--      <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3"></script>-->
      
      <script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyBtZdXp07xFmqb-NU1sRmWdumUYe8BwEtk"></script>
      
      <script type="text/javascript">
        function init_map() {
          var myOptions = {
            zoom: 14,
            center: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>),
            mapTypeId: google.maps.MapTypeId.ROADMAP
          };
          map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);
          marker = new google.maps.Marker({
            map: map,
            position: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>)
          });
          infowindow = new google.maps.InfoWindow({
            content: "<?php echo $formatted_address; ?>"
          });
          google.maps.event.addListener(marker, "click", function () {
            infowindow.open(map, marker);
          });
          infowindow.open(map, marker);
        }
        google.maps.event.addDomListener(window, 'load', init_map);
      </script>

      <?php     
    } else {
      echo "No map found.";
    }
  }


  //------------------For Address----------------------------



  echo '<div class="' . $address_box . '">';
  echo '<div class="' . $hname_c . '"><a href="' . $current_page_url . '">' . $hname[0] . '</a></div>';

  if ($vendor_address != '' && $vendor_country != '') {
    echo '<div class="' . $address . '">';
    if ($vendor_address != '') {
      echo '<span>' . $vendor_address . '</span>';
    }
    if ($vendor_state != '' && $vendor_zip != '') {
      echo ', <span itemprop="addressLocality">' . $vendor_state . '</span>, <span itemprop="postalCode">' . $vendor_zip . '</span><br />';
    }    
    echo '</div>';
  }
  
  if ($vendor_country != '') {
    echo '<div class="' . $country . '">' . $vendor_country . '</div>';
  }

  if ($vendor_phone != '') {
    echo '<div class="' . $phone . '"><span itemprop="telephone"><a href="tel:' . $vendor_phone . '"> ' . $vendor_phone . '</a></span></div>';
  }
  echo '</div>'; //   end div box, boox inner, box title
  //--------------------------------
  $output_string = ob_get_contents();
  ob_end_clean();
  return $output_string;
}

function geocode($address, $region) {
  
  
  //************************
//$address = "1600 Pennsylvania Ave NW Washington DC 20500";
$address = str_replace(" ", "+", $address);
//$region = "USA";
$key='AIzaSyBtZdXp07xFmqb-NU1sRmWdumUYe8BwEtk';

$json = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=$address&key=$key&region=$region");

//$json = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=$address&key=$key&sensor=true&region=$region");
$resp = json_decode($json);
/*
$lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
$long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
echo $lat."
".$long;*/

//echo '<pre>';
//print_r($resp);
  
  
  //****************************
  
  
  
  // url encode the address
  //$address = urlencode($address);
  // google map geocode api url
  //$url = "http://maps.google.com/maps/api/geocode/json?address={$address}";
  // get the json response
  //$resp_json = file_get_contents($url);
  // decode the json
  //$resp = json_decode($resp_json, true);
  // response status will be 'OK', if able to geocode given address 
  /*echo '<pre>';
  print_r($resp);
  die();*/
  if ($resp->{'status'} == 'OK') {    
    $lati = $resp->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
    $longi = $resp->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
    $formatted_address = $resp->{'results'}[0]->{'formatted_address'};    
    if ($lati && $longi && $formatted_address) {      
      $data_arr = array();
      array_push($data_arr, $lati, $longi, $formatted_address);
      return $data_arr;
    } else {
      return false;
    }
  } else {
    return false;
  }
}