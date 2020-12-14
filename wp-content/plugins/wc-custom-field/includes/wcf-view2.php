<?php
function wrm_item_custom_field($post_id){
ob_start();
?>
<script>
  function wcf_checkbox_val(d){
     var pid=jQuery(d).attr('data-pid');     
     wcf_get_additional_cost(pid);     
   }

   jQuery( document ).ready(function() {
       //For Dropdown
      jQuery(".wcf_ddl").change(function () {
        var pid=jQuery(this).find(':selected').attr('data-pid');        
        wcf_get_additional_cost(pid);
        var price_html=jQuery(this).find(':selected').attr('data-price');
        var price=jQuery(this).find(':selected').attr('data-val');
        var id=jQuery(this).find(':selected').attr('data-id');
        
        
        sid='ddl_span'+ id;
        tid ='ddl_txt'+ id;
        //jQuery("#"+sid).html(price_html);
        jQuery("#"+tid).val(price);
      });
      //for radio button
      jQuery(".wcf_rr").change(function () {
        var pid=jQuery(this).attr('data-pid');        
        wcf_get_additional_cost(pid);
      });
    });
    
    function wcf_get_additional_cost(pid){
      //cf_frm_
      var frm = jQuery('.cf_frm_'+ pid);
     var ajax_url = '<?php echo admin_url( 'admin-ajax.php' ); ?>';     
     var form = frm;
      jQuery.ajax({
              type: "POST",
              url:ajax_url,
              data : {
                  'action': 'wcf_additional_cost',                  
                  'wcf_data': frm.serialize(),
                  'wcf_pid':pid
                  //'wcf_data': serialized
              },
              success: function(data){
                if(data!=0){
                  jQuery("#wcf_c_"+pid).html(data);
                  jQuery(".wcf_add_cost_"+pid).show();
                }else{
                  jQuery(".wcf_add_cost_"+pid).hide();
                }
                //jQuery('#wfm_cart_price').html(data);
              }
            });    
   } 
    
</script>  

<?php
  
 $chk  = get_post_meta($post_id, '_wcf_custom_degin_checkbox', true); 
   if($chk==true){
    $custom_content= get_post_meta($post_id, '_wcf_frm_data', true);
    if($custom_content!=''){
      //$frm_data=  json_decode($custom_content);
      $frm_data=  $custom_content;  
      $k=1;
      echo '<div>';
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
           echo '<div class="wrs_cf_lbl">'.$field.'</div>';
           echo '<div>';
           wrs_cf_fields($field,$type,$value,$k,$post_id);
           echo '</div>';
        }
        $k++;
      }//foreach 
      echo '</div>';
    }//if content
  }//if custom field
  
  $var = ob_get_contents();
  ob_end_clean();
  return $var;
}

//---------------
function wrs_cf_fields($field, $type, $value,$k, $post_id){
  $data=  explode('|', $value);
  if(!empty($data)){
    if($type=='chk_box'){      
      wrs_cf_chk($data, $k, $field, $post_id);
    }else if($type=='radio'){
      wrs_cf_radio($data, $k, $field, $post_id);
    }else{
      wrs_cf_ddl($data, $k, $field, $post_id);
    }
  }else{
    return '';
  }
  return '';
}

function wrs_cf_chk($data, $k, $field_lbl, $post_id){
  echo '<table class="wrs_cf_table">';
  $r=0;
  $class='cfield_'.$k;
  foreach ($data as $val){
    $v=explode(':', $val);
    if(count($v)==2){  
      $price= woocommerce_price($v[1]);
      $val=$field_lbl.': '.$v[0].'('.$price.')||'.$v[1];      
      $val=  $val;
      echo '<tr>';
      echo "<td class='wcf_item' data-th='Item Name'>$v[0]</td>";
      echo "<td data-th='Item Price'><input id=\"$k\" class=\"$class\"  data-cf_type='chk_box' data-pid=\"$post_id\" data-cf_id=\"$id\" data-cf_lbl=\"$field_lbl\" data-cf_field=\"$v[0]\" data-cf_value=\"$v[1]\" type='checkbox' name='cf_chk[]' value='$val' onclick='wcf_checkbox_val(this)' />&nbsp;$price</td>";            
      echo '</tr>';
      $r++;
    }else{
      return '';
    }
  }
  echo '</table>';
}
function wrs_cf_radio($data, $k, $field_lbl, $post_id){
  echo '<table class="wrs_cf_table">';
  $r=0;  
  $class='wcf_rr';
  foreach ($data as $val){
    $v=explode(':', $val);
    if(count($v)==2){   
      $price= woocommerce_price($v[1]); 
      $val=$field_lbl.': '.$v[0].'('.$price.')||'.$v[1];      
      $val=  $val;
      echo '<tr>';
      echo "<td class='wcf_item' data-th='Item Name'>$v[0]</td>";
      echo "<td data-th='Item Price'><input id=\"$k\" class=\"$class\" data-cf_type='radio' data-pid=\"$post_id\" data-cf_id=\"$id\" data-cf_lbl=\"$field_lbl\" data-cf_field=\"$v[0]\" data-cf_value=\"$v[1]\" type='radio' name='cf_radio[]' value='".$val."''>&nbsp;".$price."</td>";
      echo '</tr>';
      $r++;
    }else{
      return '';
    }
  }
  
  if($r>0){
    echo '<tr>';
      echo "<td data-th='Item Name'>None</td>";
    echo "<td><input id=\"$k\" class=\"$class\" data-cf_type='radio' data-cf_value='' data-pid=\"$post_id\" type='radio' name='cf_radio[]' value='' onclick='wcf_radio_val(this)' checked></td>";
    echo '</tr>';
  }
  echo '</table>';
}

function wrs_cf_ddl($data, $k, $field_lbl, $post_id){
  $id='cfield_'.$k;
  echo '<table class="wrs_cf_table">';
  echo '<tr><td>';
  $r=0;
  echo "<select style='min-width:180px;' class='wcf_ddl' name='wcf_ddl[]'>";
  echo '<option data-id="'.$k.'" data-val="" data-pid="'.$post_id.'"  data-price="" value="" selected="selected">Choose one</option>';
  foreach ($data as $val){
    $v=explode(':', $val);
    $price= woocommerce_price($v[1]);
    $val=$field_lbl.': '.$v[0].'('.$price.')||'.$v[1];
    //$val=base64_encode($val);
    $val=  ($val);
    
    //if(!empty($v) && $v[0]&& $v[1]){
    if(count($v)==2){   
      $id='cfield_'.$k;
      echo "<option data-id='$k' data-val='$v[1]' data-pid='$post_id' data-price='$price' value='$val'>$v[0] ($price)</option>";
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









?>