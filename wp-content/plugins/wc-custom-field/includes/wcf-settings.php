<?php
function wcf_setting_reset(){  
  update_option('wcf_menu_bg_color','F52727');
  update_option('wcf_menu_hover_color','222222');
  update_option('wcf_menu_text_color','FFFFFF');
  update_option('wcf_submenu_bg_color','FFFFFF');
  update_option('wcf_prod_name_color','000000');
  update_option('wcf_menu_text_hover_color','FFFFFF');
  
}

function wcf_tab_design(){
    if (!class_exists('Woocommerce')) {
      echo '<div id="message" class="error"><p>Please Activate Wp WooCommerce Plugin</p></div>';
      return false;
    }
    if( !get_option('wcf_menu_bg_color') ) {
      wcf_setting_reset();
    }
    
    if(isset($_POST['wcf_status_submit']) && $_POST['wcf_status_submit']==1){      
      update_option('wcf_menu_bg_color',$_POST['wcf_menu_bg_color']);
      update_option('wcf_menu_hover_color',$_POST['wcf_menu_hover_color']);
      update_option('wcf_menu_text_color',$_POST['wcf_menu_text_color']);
      update_option('wcf_submenu_bg_color',$_POST['wcf_submenu_bg_color']);
      update_option('wcf_prod_name_color',$_POST['wcf_prod_name_color']);
      update_option('wcf_menu_text_hover_color',$_POST['wcf_menu_text_hover_color']);
      
    }

    if(isset($_POST['wcf_status_submit']) && $_POST['wcf_status_submit']==2){
      wcf_setting_reset();   
    }    
    ?>
    
    <div class="wrap postbox" style="float:left; width:60%;">
      
    <div class="inside">
    <h3 class="hndle ui-sortable-handle"><span><?php _e("Front-end Tab Settings","wcf-lang"); ?>:</span></h3>
    <form method="post" id="wcf_options">	
      <input type="hidden" name="wcf_status_submit" id="wcf_status_submit" value="2"  />
      <table width="100%" cellspacing="2" cellpadding="5" class="editform">
        <tr>
          <td><?php _e("Menu Background Color","wcf-lang"); ?>:</td>
          <td>
            <input type="text" name="wcf_menu_bg_color" size="10" id="wcf_menu_bg_color" class="color" value="<?php echo get_option('wcf_menu_bg_color')?>" /> 
          </td>
        </tr>
        <tr>
          <td><?php _e("Menu Hover Color","wcf-lang"); ?>:</td>
          <td>
            <input type="text" name="wcf_menu_hover_color" size="10" id="wcf_menu_hover_color" class="color" value="<?php echo get_option('wcf_menu_hover_color')?>" /> 
          </td>
        </tr>
        <tr>
          <td><?php _e("Menu Text Color","wcf-lang"); ?>:</td>
          <td>
            <input type="text" name="wcf_menu_text_color" size="10" id="wcf_menu_text_color" class="color" value="<?php echo get_option('wcf_menu_text_color')?>" /> 
          </td>
        </tr>
        <tr>
          <td><?php _e("Menu Text Hover Color","wcf-lang"); ?>:</td>
          <td>
            <input type="text" name="wcf_menu_text_hover_color" size="10" id="wcf_menu_text_hover_color" class="color" value="<?php echo get_option('wcf_menu_text_hover_color')?>" /> 
          </td>
        </tr>
        <tr>
          <td><?php _e("Sub Menu Background Color","wcf-lang"); ?>:</td>
          <td>
            <input type="text" name="wcf_submenu_bg_color" size="10" id="wcf_submenu_bg_color" class="color" value="<?php echo get_option('wcf_submenu_bg_color')?>" /> 
          </td>
        </tr>
        <tr>
          <td><?php _e("Item Name Color","wcf-lang"); ?>:</td>
          <td>
            <input type="text" name="wcf_prod_name_color" size="10" id="wcf_prod_name_color" class="color" value="<?php echo get_option('wcf_prod_name_color')?>" /> 
          </td>
        </tr>
       
        </tr>
            <tr valign="top">
            <td colspan="2" scope="row">			
              <input type="button" name="save" onclick="document.getElementById('wcf_status_submit').value='1'; document.getElementById('wcf_options').submit();" value="<?php _e("Save setting","wcf-lang"); ?>" class="button-primary" />
              <input type="button" name="reset" onclick="document.getElementById('wcf_status_submit').value='2'; document.getElementById('wcf_options').submit();" value="<?php _e("Reset to default setting","wcf-lang"); ?>" class="button-primary" />
            </td> 
          </tr>
				</td>
			</tr>
    </table>
  </form>

    </div>
</div>
<?php
}
?>