<?php
//https://wisdmlabs.com/blog/add-custom-data-woocommerce-order/
//http://stackoverflow.com/questions/25188365/how-to-retrieve-cart-item-data-with-woocommerce

function wcp_custom_degin_panel($product){
  if (!class_exists('Woocommerce')) {
    echo '<div class="error"><p>WooCommerce Plugin is required for this plugin.</p>
      <p>Please activate WP WooCommerce Plugin</p></div>';
    return false;
  }
  $custom_content='';
  $meta_box_check=false;
  $get_custom = get_post_custom($product->ID);
  if(!empty($get_custom)){
    $meta_box_check = $get_custom["_wcf_custom_degin_checkbox"][0];
    $custom_content= get_post_meta( $product->ID,'_wcf_frm_data', true );
  } 
  $cnt=1;
  $cnt_data=1;
  if(!empty($custom_content)){    
    $frm_data=  $custom_content;   
    foreach ($frm_data as $f_data){   
      $cnt_data++;
    }   
  }
  ?>
  <table>
  <tr>
    <td><strong>Enable Custom Field: &emsp;</strong></td>
    <td>
      <?php
      /*echo '<pre>';
      print_r($meta_box_check);
      die('++++++++++++');*/
      
      ?>
      <input type="checkbox" name="wcf_status" id="wcf_status"  <?php if( $meta_box_check == 'on' ) { ?>checked="checked"<?php } ?> />
    </td>
  </tr>
  </table>  
  <table class="rwd-table frwd-table input_fields_wrap rwd-table2">
    <tr>
      <th>Field Name</th>
      <th>Field Value & Price</th>
      <th>Field Type</th>
    </tr>
    <?php if($cnt_data==1){?>
    <tr>
      <td data-th="Name">
        <input type="text" class="wpr_mf_text" placeholder="Field Label" name="wpr_mf_text[1][1]"/>
      </td>
      <td data-th="value" >
        <input type="text" class="wpr_mf_text" placeholder="value:price|value:price" name="wpr_mf_text[1][2]" value="" />
      </td>
      <td data-th="Type">
          <select  class="wpr_mf_ddl" name="wpr_mf_text[1][3]">
            <option value="chk_box">Checkbox</option>
            <option value="radio">Radio</option>
            <option value="ddl">DropDownList</option>
          </select>          
        </td> 
    </tr>
    <?php }else{  
      foreach ($frm_data as $f_data){
        $field='';
        $value='';
        $type='';
        foreach ($f_data as $key => $val){
          if($key==1) $field=$val;
          if($key==2) $value=$val;
          if($key==3) $type=$val;
        }
        $r_field='';
        if($cnt!=1){
          $r_field='&nbsp;<span class="wpr_f_remove" >Remove</span>';
        }
        echo '<tr>';
        echo '<td data-th="Name" ><input type="text" class="wpr_mf_text" placeholder="Field Label" name="wpr_mf_text['.$cnt.'][1]" value="'.$field.'" /></td>';
        echo '<td data-th="value" ><input type="text" class="wpr_mf_text" placeholder="value:price|value:price" name="wpr_mf_text['.$cnt.'][2]" value="'.$value.'" /></td>';
        ?>
        <td data-th="Type">
          <select  class="wpr_mf_ddl" name="wpr_mf_text[<?php echo $cnt;?>][3]">
            <option value="chk_box" <?php if($type=='chk_box'){ echo 'selected="selected"';} ?>>Checkbox</option>
            <option value="radio" <?php if($type=='radio'){ echo 'selected="selected"';} ?>>Radio</option>
            <option value="ddl" <?php if($type=='ddl'){ echo 'selected="selected"';}?>>DropDownList</option>
          </select>
          <?php echo $r_field;?>
        </td>   
        <?php
        echo '</tr>';
        $cnt++;
      }      
    }?>
  </table>
  
  <table>
    <tr>
      <td data-th="Manufacturer" align="right"><span class="wpr_f_add_field ">Add More Fields</span><br /></td>
    </tr>    
  </table>  
    <input type="hidden" name="wpr_frm_submit" value="1" />
    <input type="hidden" name="wpr_frm_cnt" id="wpr_frm_cnt" value="<?php echo $cnt;?>" />
<?php
}

add_action( 'admin_init', 'wcf_add_custom_design_box' );
function wcf_add_custom_design_box() {  
  add_meta_box( 'wcf_custom_degin_metabox','Product Custom Fields & Price','wcp_custom_degin_panel','product', 'normal', 'high');
}

add_action( 'save_post','wcf_content_save', 10, 2 );	
function wcf_content_save( $post_id,$product) {
  if($product->post_type == 'product'){
    if (isset($_POST['wcf_status'])) {
      update_post_meta($post_id, "_wcf_custom_degin_checkbox", $_POST["wcf_status"]);
      update_post_meta($post_id, "_wcf_frm_created", 1);
    }else{      
      update_post_meta($post_id, "_wcf_frm_created", '');
      update_post_meta($post_id, "_wcf_custom_degin_checkbox", false);
    }    
    if (isset($_POST['wpr_mf_text'])) {
        /*$fields='';
        foreach ($_POST['wpr_mf_text'] as $val){
          $fields .=$val[0].', ';
        }
        $fields=rtrim($fields,',');*/      
        $data=$_POST['wpr_mf_text'];
        	
        update_post_meta($post_id, "_wcf_frm_data", $data);	
    }    
  }
}

?>