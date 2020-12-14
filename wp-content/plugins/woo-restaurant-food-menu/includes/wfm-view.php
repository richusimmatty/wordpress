<?php
add_shortcode('wfm_food_menu', 'wfm_product');

function wfm_getvarprice(){
    global $woocommerce;
    ini_set('display_errors','Off');
    $var_id=$_POST['wfm_var_id'];
     $product = get_product($var_id);                
     $product_price=woocommerce_price($product->get_price());
     echo $product_price;
     exit();  
}

function wfm_addtocart88() {
  global $woocommerce;
  ini_set('display_errors','Off'); 
  //$vid=$_POST['wfm_prod_var_id'];
  $pid=$_POST['wfm_prod_id'];
  $vid=$_POST['wfm_prod_var_id'];
  $pqty=$_POST['wfm_prod_qty'];
 
  if($vid==0){
	$product = get_product($pid);
    $bool=$product->is_sold_individually();
    if($bool==1){
      $chk_cart=wfm_check_cart_item_by_id($pid);
      if($chk_cart==0){
        echo 'Already added to cart';
        exit;
      }
    }
  }else{
	$product = get_product($vid);
    $bool=$product->is_sold_individually();
    if($bool==1){      
      $chk_cart=wfm_check_cart_item_by_id($vid);
      if($chk_cart==0){
        echo 'Already added to cart';
        exit;
      }
    }
  }

  $stock=$product->get_stock_quantity();
  $availability = $product->get_availability();
  
  if($availability['class']=='out-of-stock'){
    echo 'Out of stock';
    exit;
  }
       
  if($stock!=''){
    	foreach($woocommerce->cart->cart_contents as $cart_item_key => $values ) {
        $c_item_id='';
        $c_stock='';
        if($values['variation_id']!=''){
          $c_item_id=$values['variation_id'];
        }else{
          $c_item_id=$values['product_id'];
        }
        $c_stock=$values['quantity']+$pqty;
        
        if($vid==0 && $pid==$c_item_id && $c_stock>$stock){
          $product = get_product($pid);		  
          echo 'You have cross the stock limit';
          exit;
        }else if($vid==$c_item_id && $c_stock>$stock){
          $product = get_product($vid);
          echo 'You have cross the stock limit';
          exit;
        }        
	   }    
  }

  if($vid==0){
    $z=$woocommerce->cart->add_to_cart($pid,$pqty,null, null, null );
  }else{    
    $z=$woocommerce->cart->add_to_cart($pid, $pqty, $vid, $product->get_variation_attributes(),null);
  }  
  echo '1';  
  exit;
}


function wfm_addtocart(){
  global $wpdb;
global $woocommerce;
  ini_set('display_errors','Off');
  
  $params = array();
  parse_str($_POST['wcf_data'],$params);

  $data2=array();
  if(!empty($params)){
    foreach ($params as $key=>$cv){
      if($key!='cf_txt'){
        foreach ($cv as $cval) {
          if($cval!=''){
            $cval_data=explode("||", $cval);
            $data2[html_entity_decode($cval_data[0])]=$cval_data[1];
          }
        }      
      }    
    }
  }

 if(!empty($data2)){
   WC()->session->set('wcf_custom_data2',$data2);
 }
  $pid=$_POST['wfm_prod_id'];
  $vid=$_POST['wfm_prod_var_id'];
  $pqty=$_POST['wfm_prod_qty'];
 
  if($vid==0){    
	$product = get_product($pid);
    $bool=$product->is_sold_individually();
    if($bool==1){
      $chk_cart=wfm_check_cart_item_by_id($pid);
      if($chk_cart==0){
        echo 'Already added to cart';
        exit;
      }
    }
  }else{
	$product = get_product($vid);
    $bool=$product->is_sold_individually();
    if($bool==1){      
      $chk_cart=wfm_check_cart_item_by_id($vid);
      if($chk_cart==0){
        echo 'Already added to cart';
        exit;
      }
    }
  }

  $stock=$product->get_stock_quantity();
  $availability = $product->get_availability();
  
  if($availability['class']=='out-of-stock'){
    echo 'Out of stock';
    exit;
  }
       
  if($stock!=''){
    	foreach($woocommerce->cart->cart_contents as $cart_item_key => $values ) {
        $c_item_id='';
        $c_stock='';
        if($values['variation_id']!=''){
          $c_item_id=$values['variation_id'];
        }else{
          $c_item_id=$values['product_id'];
        }
        $c_stock=$values['quantity']+$pqty;
        
        if($vid==0 && $pid==$c_item_id && $c_stock>$stock){
          $product = get_product($pid);		  
          echo 'You have cross the stock limit';
          exit;
        }else if($vid==$c_item_id && $c_stock>$stock){
          $product = get_product($vid);
          echo 'You have cross the stock limit';
          exit;
        }        
	   }    
  }

  if($vid==0){
    $z=$woocommerce->cart->add_to_cart($pid,$pqty,null, null, null );
  }else{    
    $z=$woocommerce->cart->add_to_cart($pid, $pqty, $vid, $product->get_variation_attributes(),null);
  }
  
  echo '1';  
  exit;
}


//********************************************************************************************************



function wfm_check_cart_item_by_id($id) { 
	global $woocommerce;
	
	foreach($woocommerce->cart->get_cart() as $cart_item_key => $values ) {
		$_product = $values['data'];
		if($id == $_product->id) {
			return 0;
		}
	}	
	return 1;
}

function wfm_cart_amount(){
  global $woocommerce;
  echo $woocommerce->cart->get_cart_total();  
  exit;
}
function wfm_product($val,$user_item) {
  /*
  echo '<pre>';
  print_r($user_item);
  die('=============');*/
  
  
  if(is_admin()){
    return FALSE;
  }
  if (!class_exists('Woocommerce')) {
    echo '<div id="message" class="error"><p>Please Activate Wp WooCommerce Plugin</p></div>';
    $var = ob_get_contents();
    ob_end_clean();
    return $var;
  }
  ob_start();
  echo wfm_product2($val,$user_item);  
  $output_string = ob_get_contents();
  ob_end_clean();
  return $output_string;  
}

function wfm_product2($val, $user_item) {
  global $woocommerce;
  if(get_option('wfm_image_size')){
    $wfm_img_size=get_option('wfm_image_size');
  }else{
    $wfm_img_size=40;
  }
  
  $wfm_menu_bg_color='FF0000';
  $wfm_menu_hover_color='222222';
  $wfm_menu_text_color='FFFFFF';  
  $wfm_submenu_bg_color='FFFFFF';
  $wfm_prod_name_color='000000';
  $wfm_prod_name_hover_color='FFFFFF';
  $wfm_prod_des_color='000000';
  
  $wfm_search_bg_color='ffffff';
  $wfm_search_border_color='FF0000';
  $wfm_search_text_color='FF0000'; 
  
  if(get_option('wfm_search_bg_color')){$wfm_search_bg_color=get_option('wfm_search_bg_color');}
  if(get_option('wfm_search_border_color')){$wfm_search_border_color=get_option('wfm_search_border_color');}
  if(get_option('wfm_search_text_color')){$wfm_search_text_color=get_option('wfm_search_text_color');}
  
  if(get_option('wfm_menu_bg_color')){$wfm_menu_bg_color=get_option('wfm_menu_bg_color');}
  if(get_option('wfm_menu_hover_color')){$wfm_menu_hover_color=get_option('wfm_menu_hover_color');}
  if(get_option('wfm_menu_text_color')){$wfm_menu_text_color=get_option('wfm_menu_text_color');}  
   
  if(get_option('wfm_submenu_bg_color')){$wfm_submenu_bg_color=get_option('wfm_submenu_bg_color');}
  if(get_option('wfm_prod_name_color')){$wfm_prod_name_color=get_option('wfm_prod_name_color');}
  if(get_option('wfm_prod_name_hover_color')){$wfm_prod_name_hover_color=get_option('wfm_prod_name_hover_color');}
  if(get_option('wfm_prod_des_color')){$wfm_prod_des_color=get_option('wfm_prod_des_color');}
  ?>
  <style>
    .wfm_search{
      <?php 
      echo 'background:#'.$wfm_search_bg_color.'!important;';
      echo 'border:2px solid #'.$wfm_search_border_color.'!important;';
      echo 'color:#'.$wfm_search_text_color.'!important;';
      ?>
    }
    .glossymenu a.menuitem{
      font: bold "Lucida Grande", "Trebuchet MS", Verdana, Helvetica, sans-serif;
      font-size: 18px;
      <?php echo 'background:#'.$wfm_menu_bg_color.';';?>
    }
    .glossymenu div.submenu{ /*DIV that contains each sub menu*/
      <?php echo 'background:#'.$wfm_submenu_bg_color.';';?>
     }
    .glossymenu a.menuitem:hover{
      <?php echo 'background:#'.$wfm_menu_hover_color.';';?>
    }
    .glossymenu a.menuitem{
      <?php echo 'color:#'.$wfm_menu_text_color.'!important;';?>
    }  
    .glossymenu div.submenu ul li a{
      <?php echo 'color:#'.$wfm_menu_text_color.'!important;';?>
    }
    .wfm_name{
      color: black;
      font-size: 13px;
      font-weight: bold;
    }
    .wfm_name a{
      <?php echo 'color:#'.$wfm_prod_name_color.';';?>
     }
     .wfm_name a:hover{
      <?php echo 'color:#'.$wfm_prod_name_hover_color.';';?>
     }
    .wfm_des{
      <?php echo 'color:#'.$wfm_prod_des_color.';';?>
      font-size: 11px;
      line-height: 15px;
    }
    .wfm_des a{
      <?php echo 'color:#'.$wfm_prod_name_color.';';?>
    }
    .alert-info {
        <?php echo 'background-color:#'.$wfm_menu_text_color.';';
         echo 'border-color:#'.$wfm_menu_bg_color.';';
         echo 'color:#'.$wfm_menu_bg_color.';';?>
    }
  </style>
  <?php
  if(get_option('wfm_display_mini_cart')==1){  
  ?>

<link rel='stylesheet'  href='<?php echo WFM_BASE_URL.'/css/template_'.get_option('wfm_cart_template').'.css'; ?>' type='text/css' />
<?php
  }
?>
<form method="post" id="wfm_options">
  <?php    
    //echo wc_product_dropdown_categories( array(), 1, 0, '' );
  ?>
</form> <br /> 
  <?php
  //$cart_url = $woocommerce->cart->get_cart_url();
  $cart_url =wc_get_cart_url();  
  ?>
<div class="span4 alertAdd" style="opacity: 1; display: block;">
  <div class="alert alert-info"id="wfm_alert_info" style="display: none;"> Added to your cart </div>
</div>
<div id="wfm_cart_amount" class="wfm_cart_amount">
  <a href="<?php echo$cart_url;?>"><div id="wfm_cart_price" class="wfm_cart_price"><?php echo $woocommerce->cart->get_cart_total(); ?></div></a>  
</div>
<script>
  jQuery(document).ready(function() {
	jQuery('.simple-ajax-popup-align-top').magnificPopup({
      type: 'ajax',		
      overflowY: 'scroll' // as we know that popup content is tall we set scroll overflow by default to avoid jump
    });	
  });  
  //-------------------------------------
  var img_url_plus = '<?php echo WFM_BASE_URL; ?>/images/plus.png';
  var img_url_minus = '<?php echo WFM_BASE_URL; ?>/images/minus.png';
  
  ddaccordion.init({
    headerclass: "submenuheader", //Shared CSS class name of headers group
    contentclass: "submenu", //Shared CSS class name of contents group
    revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
    mouseoverdelay: 500, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
    collapseprev: false, //Collapse previous content (so only one open at any time)? true/false 
    defaultexpanded: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20], //index of content(s) open by default [index1, index2, etc] [] denotes no content
    onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
    animatedefault: true, //Should contents open by default be animated into view?
    persiststate: false, //persist state of opened contents within browser session?
    toggleclass: ["", ""], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
    togglehtml: ["suffix", "<img src='"+img_url_plus+"' class='statusicon' />", "<img src='"+img_url_minus+"' class='statusicon' />"], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
    animatespeed: "slow", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
    oninit:function(headers, expandedindices){ //custom code to run when headers have initalized
      //do nothing
    },
    onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
      //do nothing
    }
  })  
  //------------------------------------
  
  //jQuery('#dropdown_product_cat option[value=]').text('All products');
  function wfm_ddl(var_id, id){
    var_id=var_id.value;
    jQuery('#wfm_var_id_'+id).val(var_id);
    var ajax_url = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
    jQuery.ajax({
              type: "POST",
              url:ajax_url,
              data : {'action': 'wfm_getvarprice',
                'wfm_var_id':     var_id
              },
              success: function(data){
                jQuery('#wfm_price_'+id).html(data);
              }
            });
  }
  
  function wfm_add_prod(pid,vid){
    jQuery("#wfm_loader"+pid).show();
   
    var vid= jQuery('#wfm_var_id_'+pid).val();
    var qty= jQuery('#product_qty_'+pid).val(); 
    
    if(qty==0 || qty==''){
      jQuery('#wfm_alert_info').text('Quantity can not be less than 1');
      jQuery('#wfm_alert_info').show()
      setTimeout(function(){jQuery('#wfm_alert_info').hide()}, 1500);      
      return false;
    }
    if(qty>1000){
      jQuery('#wfm_alert_info').text('You have cross the quantity limit');
      jQuery('#wfm_alert_info').show()
      setTimeout(function(){jQuery('#wfm_alert_info').hide()}, 1500);      
      return false;
    }
    if(vid==0){
      qty= jQuery('#product_qty_'+pid).val();
    }
  
    
    var ajax_url2 = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
    var ajax_url = '<?php echo plugins_url(); ?>';
    ajax_url+='/woo-restaurant-food-menu/includes/wfm-add-cart.php';  
    var frm = jQuery('.cf_frm_'+ pid);
        jQuery.ajax({
          type: "POST",
          url:ajax_url2,
          data : {
                  'action':          'wfm_addtocart',
                  'wfm_prod_id':     pid,
                  'wfm_prod_var_id': vid,
                  'wcf_data': frm.serialize(),
                  'wfm_prod_qty':    qty
          },
          success: function(response){          
            if(response==1){
              jQuery('#wfm_alert_info').text('Added to your cart');
              updateCartFragment();
            }else{
              jQuery('#wfm_alert_info').text(response);
            }
            
            jQuery.ajax({
              type: "POST",
              url:ajax_url2,
              data : {'action': 'wfm_cart_amount'},
              success: function(data){                
                jQuery('#wfm_cart_price').html(data);
              }
            });
            
             jQuery('#wfm_alert_info').show()
             setTimeout(function(){jQuery('#wfm_alert_info').hide()}, 2000);
             jQuery("#wfm_loader"+pid).hide();
          }
        });
        
  }
  
  jQuery(document).ready(function(){
    jQuery(".ajax").colorbox();
  });
    function updateCartFragment() {
  $fragment_refresh = {
    url: woocommerce_params.ajax_url,
    type: 'POST',
    data: { action: 'woocommerce_get_refreshed_fragments' },
    success: function( data ) {
      if ( data && data.fragments ) {          
          jQuery.each( data.fragments, function( key, value ) {
              jQuery(key).replaceWith(value);
          });

          if ( $supports_html5_storage ) {
              sessionStorage.setItem( "wc_fragments", JSON.stringify( data.fragments ) );
              sessionStorage.setItem( "wc_cart_hash", data.cart_hash );
          }                
          jQuery('body').trigger( 'wc_fragments_refreshed' );
      }
    }
  };

  //Always perform fragment refresh
  jQuery.ajax( $fragment_refresh );  
  }
  var plugin_url='<? echo plugins_url(); ?>/woo-restaurant-food-menu/includes/wfm-popup-data.php';
</script>  
<?php
  if(!isset($_POST['wfm_hval'])){
    if($val){
      $id= $val['category_id'];
      $product_category =  get_term_by( 'id', $id, 'product_cat', 'ARRAY_A' );
      if(!empty($product_category )){
          $_POST['wfm_hval']=1;
          $_POST['product_cat']=$product_category['slug'];
          $_POST['wfm_front_order_by']='title';
          $_POST['wfm_front_order']='ASC';
          ?>
            <script>
              jQuery(".dropdown_product_cat option[value='" + '<?php echo $_POST['product_cat']?>' + "']").attr('selected', 'selected');
            </script>
          <?php
      }
    }
  }
  echo '<div class="glossymenu">';
    $term 			= get_queried_object();
    $parent_id 		= empty( $term->term_id ) ? 0 : $term->term_id;
    $args2 = array(
      'parent'       => $parent_id,
      'child_of'     => $parent_id,
      'menu_order'   => 'ASC',
      'hide_empty'   => 1,
      'hierarchical' => 1,
      'taxonomy'     => 'product_cat',      
      'field' 		=> 'slug'
    );
		
		$product_categories = get_categories( $args2  );    

        
    foreach ($product_categories as $cat_data){
          $args = array(
            'post_type'				=> 'product',
            'post_status' 			=> 'publish',			
            'orderby' 				=> 'title',
            'order' 				      => 'asc',
            'type' => 'numeric',
            'posts_per_page' 		=> 200,
            'tax_query' 			=> array(
                  array(
                  'taxonomy' 		=> 'product_cat',
                  'terms' 		=> array( esc_attr($cat_data->slug) ),
                  //'terms' 		=> array( esc_attr('tennis') ),    
                  'field' 		=> 'slug',
                  'operator' 		=> 'IN'
                )
              )
          );
        $loop = new WP_Query( $args );
        $count_cat_prod=wfm_count_cat_prod($loop, $user_item);
        /*
        echo '<pre>';
        print_r($loop);
        
        print_r($user_item);
        
        echo $count_cat_prod;
        die('************');*/
        
        if($count_cat_prod==1){
          echo '<a class="menuitem submenuheader">'.$cat_data->name.'</a>';//menu cat
          wfm_show_prod2($loop, $wfm_img_size, $user_item);
        }
    }
  
  echo '</div>';//glossymenu end
}
function wfm_count_cat_prod($loop, $user_item){
  /*echo '<pre>';
  print_r($user_item);
  print_r($loop->posts);
  die();*/
  
  if ($loop->have_posts()){
    foreach($loop->posts as $val){
      if(in_array($val->ID,$user_item)){
         return 1;
       }
    }
  }else{
    return 0;
  }        
}

function wfm_show_prod2($loop,$wfm_img_size, $user_item){
      if ($loop->have_posts()){        
        echo '<div class="submenu"><ul><table class="wfm_table" style="width:100%;">';
        foreach($loop->posts as $val){          
         if(in_array($val->ID,$user_item)){ 
          
          $product = wc_get_product($val->ID );                           
          $att_value='';
          if($product->is_type( 'variable')){
            $default_att=$product->get_variation_default_attributes();
            if(!empty($default_att)){
              foreach ($default_att as $att_val){
                $att_value= $att_val;
              }
            }
          }
          $is_cat=0;
          
          if($is_cat==0){
            $variation_display=false;
            $variation=false;
            if (get_option('wfm_display_variation')=='1'){
              $variation_display= true;
            }            
            
            if ($variation_display == true){
                $variation_query = new WP_Query();
                $args_variation = array(
                  'post_status' => 'publish',
                  'post_type' => 'product_variation',
                  'posts_per_page'   => -1,  
                  'post_parent' => $val->ID
                );                
                $variation_query->query($args_variation);

                if ($variation_query->have_posts()){
                  $variation=true;
                }
            }
             ini_set('display_errors','Off');
             
            if($variation==true && $product->is_type( 'variable' )){
              //----------------------------------------------------              
              $product_name_org='<div class="wfm_name"><a href="'. plugins_url().'/woo-restaurant-food-menu/includes/wfm-popup-data.php?pid='.$val->ID.'" class="simple-ajax-popup-align-top">'.$val->post_title.'</a></div>';
              $prod_des='';
              if($val->post_content){
                $prod_des=$val->post_content;
                if(strlen($prod_des)>=120){
                  $prod_des = substr($prod_des,0,140).'... <a href="'. plugins_url().'/woo-restaurant-food-menu/includes/wfm-popup-data.php?pid='.$val->ID.'" class="simple-ajax-popup-align-top">Read More..</a>';
                }   
              }
                            
              $ddl_att_val='';              
              $vq=$variation_query->posts;
              $prod_price='';
              $wfm_var_id='';

              foreach($vq as $var_data){
                $product = get_product($var_data->ID);                
                $attributes= woocommerce_get_formatted_variation($product->get_variation_attributes(),true);
                $attributes=  explode(':', $attributes); 
                
                $att_value=strtolower($att_value);
                $att_curr=strtolower($attributes[1]);
                $att_curr = str_replace(' ', '', $att_curr);
                $select='';
                
                //-------price
                  $product_price=woocommerce_price($product->get_price());
                  if($att_value==''){
                    if(!$prod_price){                    
                      $prod_price=$product_price;
                      $wfm_var_id=$var_data->ID;
                    }
                  
                  }else if($att_value==$att_curr){
                    //die('++++++');
                    $prod_price=$product_price;
                    $wfm_var_id=$var_data->ID;
                    $select='selected="selected"';
                  }
                  
                //------dropdown product variation
                  $ddl_att_val.='<option '.$select.' value='.$var_data->ID.'>'.$attributes[1].'</option>';
                
                //-------image
                  //$img_url = WFM_BASE_URL. '/images/placeholder.png';
                  $img_url=wc_placeholder_img_src();
                  if (has_post_thumbnail($var_data->ID)){
                    $img_url2 = wp_get_attachment_url( get_post_thumbnail_id($var_data->ID) );                    
                    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($var_data->ID), 'thumbnail' );
                    $img_url = $thumb['0'];
                    
                  } else if (has_post_thumbnail($val->ID)){
                    $img_url2 = wp_get_attachment_url( get_post_thumbnail_id($val->ID) );                    
                    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($val->ID), 'thumbnail' );
                    $img_url = $thumb['0'];                   
                  }
                  //--------stock
                  $max_stock=1000;
              }//end foreach
              //prod_image
              $img_url = WFM_BASE_URL. '/images/placeholder.png';
              if (has_post_thumbnail($val->ID)){
                    $img_url2 = wp_get_attachment_url( get_post_thumbnail_id($val->ID) );                    
                    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($val->ID), 'thumbnail' );
                    $img_url = $thumb['0'];                   
              }
              $prod_option= '<select onchange="wfm_ddl(this,'.$val->ID.');" style="max-width:100px;">'.$ddl_att_val.'</select>';
              echo '<tr class="wfm_m_tr">';
              if (get_option('wfm_display_image_preview')=='1'){
                  echo '<td valign="top"><a href="'.$img_url.'" class="preview"><img src="'.$img_url.'" height="'.$wfm_img_size.'" width="'.$wfm_img_size.'" /></a></td>';
                  }else{
                    echo '<td><img src="'.$img_url.'" height="'.$wfm_img_size.'" width="'.$wfm_img_size.'" /></td>';
              }
              
              //----------------------------------------------For Custom Field--------------------------------------------------------
              $cf_data='';
              $cf='';              
              $chk  = get_post_meta($val->ID, '_wcf_custom_degin_checkbox', true);
              if($chk=='on'){
                  $curr=get_woocommerce_currency_symbol();
                  $cf_data= '<div style="display:none; width:100%;" class="wcf_add_cost_'.$val->ID.'">Additional cost: '.$curr.'<span id="wcf_c_'.$val->ID.'"></span></div>';
                  $cf_data.= '<input type="hidden" name="wcf_cost_'.$val->ID.'" id="wcf_cost_'.$val->ID.'" value="" />';
                  $cf='<form class="cf_frm_'.$val->ID.'">';
                  $cf.=wrm_item_custom_field($val->ID);
                  $cf.='</form>';
                  
              }
              
              if (get_option('wfm_display_image_preview')=='1'){
                echo '<td class="wfm_td">'.$product_name_org.'<div class="wfm_des">'.$prod_des.'</div></td>';
              }else{
                echo '<td class="wfm_td">'.$product_name_org.'<div class="wfm_des">'.$prod_des.'</div></td>';
              }
              
              //echo '<td>'.$cf_data.' '.$cf.'</td>';
              //'.wrm_item_custom_field($val->ID).'
                  
              ?>
                    <td valign="top">
                      <?php echo $cf_data;?>
                      <?php echo $cf;?>
                      <input type="hidden" name="wfm_var_id" id="wfm_var_id_<?php echo $val->ID?>" value="<?php echo $wfm_var_id;?>" />
                      
                        <?php
                        if($max_stock!=0){                            
                          ?><input type="number" style="width:70px;" value="1" min="1"  max="<?php echo $max_stock;?>" name="product_qty_<?php echo $val->ID?>" id="product_qty_<?php echo $val->ID?>" /><?php                            
                        }else{                            
                           ?><input type="number" style="width:70px;" value="0" min="0" max="0" name="product_qty_<?php echo $val->ID?>" id="product_qty_<?php echo $val->ID?>" /><?php
                        }
                        ?>  
                     
                    </td>  
                  <?php                  
                  if($product->regular_price && $max_stock!=0){  
                  echo '<td class="wfm_td_price">
                      <div id="wfm_price_'.$val->ID.'">
                          '.$prod_price.'
                      </div>  
                      <div class="wfm_add_btn"><a onclick="wfm_add_prod('.$val->ID.',1);"><div class="wfm_add_cart"></div></a></div>
                      <div class="wfm_loading" id="wfm_loader'.$val->ID.'" style="display: none;"></div>
                      </td>';
                  }else {
                    echo '<td></td>';
                  }
                  //echo '<td width="30"><div class="wfm_loading" id="wfm_loader'.$val->ID.'" style="display: none;"></div></td></tr>';
                  echo '</tr>';
            }else{
                wfm_show_prod($val->ID,$wfm_img_size, $val->post_title);
            }
          }//is cat check end 
         }//is item in array 
        }//end foreach
          echo '</table></ul></div>';
      }//if
}

function wfm_show_prod($id, $wfm_img_size, $post_title){
  wrm_item_custom_field($id);
    $max_stock=500;
    ini_set('display_errors','Off');
    $product=wc_get_product( $id );
        
    if($product->get_stock_quantity()!=''){
      $max_stock=$product->get_stock_quantity();
    }
    $availability=$product->get_availability();

    if($availability['class']=='out-of-stock'){
      $max_stock=0;
    }

    $product_name='<div class="wfm_name"><a href="'. plugins_url().'/woo-restaurant-food-menu/includes/wfm-popup-data.php?pid='.$id.'" class="simple-ajax-popup-align-top">'.$post_title.'</a>
      </div>';
    $product = get_product($id);
    $prod_des='';
    if($product->post->post_content){
      $prod_des=$product->post->post_content;   
      if(strlen($prod_des)>=120){
         $prod_des = substr($prod_des,0,120).'... <a href="'. plugins_url().'/woo-restaurant-food-menu/includes/wfm-popup-data.php?pid='.$id.'" class="simple-ajax-popup-align-top">Read More..</a>';
      }
    }

    $product_price =$product->get_price_html();
    
    if (has_post_thumbnail($id)){
        $img_url2 = wp_get_attachment_url( get_post_thumbnail_id($id,'thumbnail'));
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($id), 'thumbnail' );
        $img_url = $thumb['0'];

    } else {
        $img_url=WFM_BASE_URL. '/images/placeholder.png';
        $img_url2=$img_url;
    }
    if (has_post_thumbnail($id)){
        $img_url2 = wp_get_attachment_url( get_post_thumbnail_id($id,'thumbnail'));
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($id), 'thumbnail' );
        $img_url = $thumb['0'];

    } else {
        $img_url=WFM_BASE_URL. '/images/placeholder.png';
        $img_url2=$img_url;
    }
    if (get_option('wfm_display_image_preview')=='1'){
      echo '<tr class="wfm_m_tr"><td><a href="'.$img_url2.'" class="preview"><img src="'.$img_url.'" height="'.$wfm_img_size.'" width="'.$wfm_img_size.'" /></a></td>
        <td class="wfm_td">'.$product_name.'<div class="wfm_des">'.$prod_des.'</div></td>';
    }else{                
      echo '<tr class="wfm_m_tr"><td><img src="'.$img_url.'" height="'.$wfm_img_size.'" width="'.$wfm_img_size.'" /></td>
        <td class="wfm_td">'.$product_name.'<div class="wfm_des">'.$prod_des.'</div></td>';
    }
    
    //-----------------------------Custom Field------------------------------------------
    $cf_data='';
    $cf='';              
    $chk  = get_post_meta($id, '_wcf_custom_degin_checkbox', true);
    if($chk=='on'){
        $curr=get_woocommerce_currency_symbol();
        $cf_data= '<div style="display:none; width:100%;" class="wcf_add_cost_'.$id.'">Additional cost: '.$curr.'<span id="wcf_c_'.$id.'"></span></div>';
        $cf_data.= '<input type="hidden" name="wcf_cost_'.$id.'" id="wcf_cost_'.$id.'" value="" />';
        $cf='<form class="cf_frm_'.$id.'">';
        $cf.=wrm_item_custom_field($id);
        $cf.='</form>';

    }
    //echo '<td>'.$cf_data.''.$cf.'</td>';
    
    ?>
      <td>
        <?php echo $cf_data;?>
        <?php echo $cf;?>
          <?php
          if($max_stock!=0){
          //if($product->regular_price && $max_stock!=0){
            ?><input type="number" style="width:70px;" value="1" min="0" max="0<?php echo $max_stock;?>" name="product_qty_<?php echo $id;?>" id="product_qty_<?php echo $id;?>" /><?php
          }else{
            ?><input type="number" style="width:70px;" value="0" min="0" max="0" name="product_qty_<?php echo $id;?>" id="product_qty_<?php echo $id;?>" /><?php
          }
          ?>        
      </td>  
    <?php
    
    if($max_stock!=0){
    //if($product->regular_price && $max_stock!=0){
      echo '<td class="wfm_td_price">
             <div>'.$product_price.'</div>
             <div class="wfm_add_btn"><a onclick="wfm_add_prod('.$id.', 0);"><div class="wfm_add_cart"></div></a></div>
             <div class="wfm_loading" id="wfm_loader'.$id.'" style="display: none;"></div>  
          </td>';
    }else{
      echo '<td><div>'.$product_price.'</div></td>';
    }
    //echo '<td><div class="wfm_loading" id="wfm_loader'.$id.'" style="display: none;"></div></td></tr>';
    echo '</tr>';
    
}
add_action( 'wp_ajax_nopriv_wfm_addtocart','wfm_addtocart' );
add_action( 'wp_ajax_wfm_addtocart', 'wfm_addtocart' );

add_action( 'wp_ajax_nopriv_wfm_cart_amount','wfm_cart_amount' );
add_action( 'wp_ajax_wfm_cart_amount', 'wfm_cart_amount' );

add_action( 'wp_ajax_nopriv_wfm_getvarprice','wfm_getvarprice' );
add_action( 'wp_ajax_wfm_getvarprice', 'wfm_getvarprice' );