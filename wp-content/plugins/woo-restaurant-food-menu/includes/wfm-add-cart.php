<?php
require_once('../../../../wp-blog-header.php');
header('HTTP/1.1 200 OK');
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
  
  function unserializeForm($str) {
    $returndata = array();
    $strArray = explode("||", $str);
    $i = 0;
    foreach ($strArray as $item) {
        $array = explode("=", $item);
        $returndata[$array[0]] = $array[1];
    }
    

    return $returndata;
}