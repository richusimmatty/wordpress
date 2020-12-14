<?php
add_action( 'woocommerce_before_add_to_cart_button', 'wcf_show_custom_field',30 );
function wcf_show_custom_field(){
  if(get_option('wcf_menu_bg_color')){$wcf_menu_bg_color=get_option('wcf_menu_bg_color');}
  if(get_option('wcf_menu_hover_color')){$wcf_menu_hover_color=get_option('wcf_menu_hover_color');}
  if(get_option('wcf_menu_text_color')){$wcf_menu_text_color=get_option('wcf_menu_text_color');}
  if(get_option('wcf_menu_text_hover_color')){$wcf_menu_text_hover_color=get_option('wcf_menu_text_hover_color');}
  if(get_option('wcf_submenu_bg_color')){$wcf_submenu_bg_color=get_option('wcf_submenu_bg_color');}
  if(get_option('wcf_prod_name_color')){$wcf_prod_name_color=get_option('wcf_prod_name_color');}  
  
  ?>
   <style>
     .wcf_glossymenu a.menuitem{
      font: bold "Lucida Grande", "Trebuchet MS", Verdana, Helvetica, sans-serif;
      font-size: 18px;
      background:<?php echo '#'.$wcf_menu_bg_color.';';?>
    }
    .wcf_glossymenu div.submenu{ /*DIV that contains each sub menu*/
      <?php echo 'background:#'.$wcf_submenu_bg_color.';';?>
     }
    .wcf_glossymenu a.menuitem:hover{
      <?php echo 'background:#'.$wcf_menu_hover_color.';';?>
      <?php echo 'color:#'.$wcf_menu_text_hover_color.'!important;';?>
    }
    .wcf_glossymenu a.menuitem{
      <?php echo 'color:#'.$wcf_menu_text_color.'!important;';?>
    }  
    .wcf_glossymenu div.submenu ul li a{
      <?php echo 'color:#'.$wcf_menu_text_color.'!important;';?>
    }
    .wcf_item{
      <?php echo 'color:#'.$wcf_prod_name_color.'!important;';?>
    }
   </style>  
   <script>
       var img_url_plus = '<? echo plugins_url(); ?>/wc-custom-field/images/plus.png';
       var img_url_minus = '<? echo plugins_url(); ?>/wc-custom-field/images/minus.png';
         ddaccordion.init({
          headerclass: "submenuheader", //Shared CSS class name of headers group
          contentclass: "submenu", //Shared CSS class name of contents group
          revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
          mouseoverdelay: 500, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
          collapseprev: false, //Collapse previous content (so only one open at any time)? true/false 
          defaultexpanded: [], //index of content(s) open by default [index1, index2, etc] [] denotes no content
          onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
          animatedefault: true, //Should contents open by default be animated into view?
          persiststate: false, //persist state of opened contents within browser session?
          toggleclass: ["", ""], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
          togglehtml: ["suffix", "<img src='"+img_url_plus+"' class='statusicon' />", "<img src='"+img_url_minus+"' class='statusicon' />"], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
          animatespeed: "normal", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
          oninit:function(headers, expandedindices){ //custom code to run when headers have initalized
            //do nothing
          },
          onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
            //do nothing
          }
        })
        
   function wcf_checkbox_val(d){
     wcf_get_additional_cost();
     //alert(jQuery(d).attr('data-cf_value'));
   }
   function wcf_radio_val(d){
     //salert(jQuery(d+":checked").data('cf_value'));
   } 
   
   jQuery( document ).ready(function() {
       //For Dropdown
      jQuery(".wcf_ddl").change(function () {
        wcf_get_additional_cost();
        var price_html=jQuery(this).find(':selected').attr('data-price');
        var price=jQuery(this).find(':selected').attr('data-val');
        var id=jQuery(this).find(':selected').attr('data-id');
        sid='ddl_span'+ id;
        tid ='ddl_txt'+ id;
        jQuery("#"+sid).html(price_html);
        jQuery("#"+tid).val(price);
      });
      //for radio button
      jQuery(".wcf_rr").change(function () {
        wcf_get_additional_cost();
        //alert(jQuery(this).attr('data-cf_value'));
        
      });
      
      
    });
    
    function wcf_get_additional_cost(){
     //alert('AC');
     var frm = jQuery('.cart');     
     var ajax_url = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
     
     //var form = frm;
     //var serialized = jQuery.param(form.serializeArray().concat());
    // serialized = serialized.replace('%5B%5D','');
     //alert(serialized);
    //return false;
     
     //-----
      jQuery.ajax({
              type: "POST",
              url:ajax_url,
              data : {
                  'action': 'wcf_additional_cost',                  
                  'wcf_data': frm.serialize()
                  //'wcf_data': serialized
              },
              success: function(data){
                //alert(data);
                if(data!=0){
                  jQuery("#wcf_c").html(data);
                  jQuery(".wcf_add_cost").show();
                }else{
                  jQuery(".wcf_add_cost").hide();
                }
                //jQuery('#wfm_cart_price').html(data);
              }
            });    
   } 
     
   </script>
  <?php  
  global $post;
  $post_id= $post->ID;
  $chk  = get_post_meta($post_id, '_wcf_custom_degin_checkbox', true);
  if($chk=='on'){
    $custom_content= get_post_meta($post_id, '_wcf_frm_data', true);
    if($custom_content!=''){
      $frm_data=  json_decode($custom_content);   
      $k=1;
      echo '<div class="wcf_glossymenu">';
      foreach ($frm_data as $f_data){        
        $field='';
        $value='';
        $type='';
        foreach ($f_data as $key => $val){
          if($key==1) $field=$val;
          if($key==2) $value=$val;
          if($key==3) $type=$val;
        }
                     
        if($field!='' && $value!=''){
           echo '<a class="menuitem submenuheader">'.$field.'</a>';
           echo '<div class="submenu"><ul>';
           wcf_fields($field,$type,$value,$k);
           echo '</ul></div>';
        }
        $k++;
      }//foreach 
      echo '</div>';
    }//if content
  }//if custom field
}

function wcf_fields($field, $type, $value,$k){  
  $data=  explode('|', $value);
  if(!empty($data)){
    if($type=='chk_box'){            
      wcf_chk($data, $k, $field);
    }else if($type=='radio'){
      wcf_radio($data, $k, $field);
    }else{
      wcf_ddl($data, $k, $field);
    }
  }else{
    return '';
  }
  return '';
}

function wcf_chk($data, $k,$field_lbl){
  echo '<table class="rwd-table frwd-table input_fields_wrap">';
  $r=0;
  $class='cfield_'.$k;
  foreach ($data as $val){
    $v=explode(':', $val);
    //if(!empty($v) && $v[0]&& $v[1]){
    if(count($v)==2){  
      $price= woocommerce_price($v[1]);
      $val=$field_lbl.': '.$v[0].'('.$price.')||'.$v[1];
      //$val=base64_encode($val);
      $val=  htmlentities($val);
      echo '<tr>';
      echo "<td class='wcf_item' data-th='Item Name'>$v[0]</td>";
      /*echo "<td data-th='Item Price'><input id=\"$k\" class=\"$class\"  data-cf_type='chk_box' data-cf_id=\"$id\" data-cf_lbl=\"$field_lbl\" data-cf_field=\"$v[0]\" data-cf_value=\"$v[1]\" type='checkbox' name='cf_chk[]' value='$val' onclick='wcf_checkbox_val(this)' />&nbsp;$price</td>";*/
      echo "<td data-th='Item Price'><input id=\"$k\" class=\"$class\"  data-cf_type='chk_box' data-cf_lbl=\"$field_lbl\" data-cf_field=\"$v[0]\" data-cf_value=\"$v[1]\" type='checkbox' name='cf_chk[]' value='$val' onclick='wcf_checkbox_val(this)' />&nbsp;$price</td>";
      
      echo '</tr>';
      $r++;
    }else{
      return '';
    }
  }
  echo '</table>';
}
function wcf_radio($data, $k, $field_lbl){
  echo '<table class="rwd-table frwd-table input_fields_wrap">';
  $r=0;
  //$class='cfield_'.$k;
  $class='wcf_rr';
  foreach ($data as $val){
    $v=explode(':', $val);
    if(!empty($v) && $v[0]&& $v[1]){
      $price= woocommerce_price($v[1]); 
      $val=$field_lbl.': '.$v[0].'('.$price.')||'.$v[1];
      //$val=base64_encode($val);
      $val=  htmlentities($val);
      echo '<tr>';
      echo "<td class='wcf_item' data-th='Item Name'>$v[0]</td>";
      echo "<td data-th='Item Price'><input id=\"$k\" class=\"$class\" data-cf_type='radio' data-cf_id=\"$id\" data-cf_lbl=\"$field_lbl\" data-cf_field=\"$v[0]\" data-cf_value=\"$v[1]\" type='radio' name='cf_radio[]' value='".$val."'>&nbsp;".$price."</td>";
      echo '</tr>';
      $r++;
    }else{
      return '';
    }
  }
  
  if($r>0){
    echo '<tr>';
      echo "<td data-th='Item Name'>None</td>";
    echo "<td><input id=\"$k\" class=\"$class\" data-cf_type='radio' data-cf_value='' type='radio' name='cf_radio[]' value='' onclick='wcf_radio_val(this)' checked></td>";
    echo '</tr>';
  }
  echo '</table>';
}

function wcf_ddl($data, $k, $field_lbl){
  $id='cfield_'.$k;
  echo '<table class="rwd-table frwd-table input_fields_wrap">';
  echo '<tr><td>';
  $r=0;
  echo "<select style='min-width:180px;' class='wcf_ddl' name='wcf_ddl[]'>";
  echo '<option data-id="'.$k.'" data-val=""  data-price="" value="" selected="selected">Choose one</option>';
  foreach ($data as $val){
    $v=explode(':', $val);
    $price= woocommerce_price($v[1]);
    $val=$field_lbl.': '.$v[0].'('.$price.')||'.$v[1];
    //$val=base64_encode($val);
    $val=  htmlentities($val);
    
    if(!empty($v) && $v[0]&& $v[1]){
      $id='cfield_'.$k;
      echo "<option data-id='$k' data-val='$v[1]' data-price='$price' value='$val'>$v[0]</option>";
      $r++;
    }else{
      return '';
    }
  }
  echo '</select>';
   echo '</td><td>';
  $pid='ddl_span'.$k;
  $hf='ddl_txt'.$k;
  echo "&nbsp;<span id=\"$pid\"></span><input id=\"$hf\" type='hidden' name='cf_txt[]' value='' />";
  echo '</td></tr></table>';
}
/*
add_action( 'woocommerce_before_main_content', 'wcf_bmc',19 ,3);
function wcf_bmc(){
  echo 'helloz';
}*/
//--------------------------------add to cart----------------------

function wcf_product_custom_data(){
   $data=array();  
  if(isset($_POST['cf_chk']) && $_POST['cf_chk']!=''){
    foreach ($_POST['cf_chk'] as $val) {
      if(!empty($val)){
        //$v=  explode('||', base64_decode($val));
        $v=  explode('||', ($val));
        $data[$v[0]]=$v[1];
      }
    }
  }  
  if(isset($_POST['cf_radio']) && $_POST['cf_radio']!=''){
    foreach ($_POST['cf_radio'] as $val) {
      if(!empty($val)){
        //$v=  explode('||', base64_decode($val));
        $v=  explode('||', ($val));
        $data[$v[0]]=$v[1];
      }
    }
  }  
  if(isset($_POST['wcf_ddl']) && $_POST['wcf_ddl']!=''){
    foreach ($_POST['wcf_ddl'] as $val) {
      if(!empty($val)){
        //$v=  explode('||', base64_decode($val));
        $v=  explode('||', ($val));
        $data[$v[0]]=$v[1];
      }
    }
  }
  return $data;
}

//-----------------------------------------Custom data------------------------------------
add_filter( 'woocommerce_add_cart_item_data', function ( $cartItemData, $productId, $variationId ) {
    $session_data = WC()->session->get('wcf_custom_data2');
    $data=$session_data;
    if(empty($data)){
      $data=wcf_product_custom_data();
    }
    //$data=wcf_product_custom_data();
    if(!empty($data)){
      $cartItemData['wcf_custom_data'] = $data;
    }
    return $cartItemData;
}, 10, 3 );

add_filter( 'woocommerce_get_cart_item_from_session', function ( $cartItemData, $cartItemSessionData, $cartItemKey ) {
    if ( isset( $cartItemSessionData['wcf_custom_data'] ) ) {
        $cartItemData['wcf_custom_data'] = $cartItemSessionData['wcf_custom_data'];
    }
    return $cartItemData;
}, 10, 3 );


//show the data at the cart/checkout page
add_filter( 'woocommerce_get_item_data', function ( $data, $cartItem ) {
    if ( isset( $cartItem['wcf_custom_data'] ) ) {
      
      foreach ($cartItem['wcf_custom_data'] as $key => $value) {
        $val=  explode(':', $key);
        $data[] = array(
            'name' => $val[0],
            'value' => $val[1]
        );
      }
    }
    return $data;
}, 10, 2 );

//------------- update Price -------------
add_action( 'woocommerce_before_calculate_totals', 'wqb_add_custom_price',10, 2 );

function wqb_add_custom_price( $cart_object ) 
{
  foreach ( $cart_object->cart_contents as $key => $value )
  {
    if(isset($value['wcf_custom_data'])){
      $cprice=$value['data']->price;
      foreach ($value['wcf_custom_data'] as $value2) {
        $cprice=$cprice+$value2;
      }
      $value['data']->price = $cprice;
    }
  }
}

//save the data when the order is made
add_action( 'woocommerce_add_order_item_meta', function ( $itemId, $values, $key ) {
    if ( isset( $values['wcf_custom_data'] ) ) {
        foreach ($values['wcf_custom_data'] as $key => $value) {
          $val=  explode(':', $key);
          wc_add_order_item_meta( $itemId, $val[0], $val[1]);
        }
    }
}, 10, 3 );

//--------------------------------------
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_startsinfo', 11 );
function woocommerce_template_single_startsinfo() { 
  $curr=get_woocommerce_currency_symbol();
  echo '<div style="display:none; width:100%;" class="wcf_add_cost">Additional cost: '.$curr.'<span id="wcf_c"></span></div>';
  echo '<input type="hidden" name="wcf_cost" id="wcf_cost" value="" />';
}

//get additional Cost
function wcf_additional_cost(){
  $data='';
  $price=0;
  if(isset($_POST['wcf_data'])){    
    $data=  explode('&', $_POST['wcf_data']);
    foreach ($data as $value) {
      $val=  explode('%5B%5D=', $value);
      if(isset($val[1]) && $val[1]!='' && $val[0]!='cf_txt'){        
        $k=  explode('%7C%7C', $val[1]);
        if(isset($k[1])){
          $price=$price+$k[1];
        }         
      }
    }
  }
  echo $price;
  
  exit(); 
  
  
}

add_action( 'wp_ajax_nopriv_wcf_additional_cost','wcf_additional_cost' );
add_action( 'wp_ajax_wcf_additional_cost', 'wcf_additional_cost' );

?>