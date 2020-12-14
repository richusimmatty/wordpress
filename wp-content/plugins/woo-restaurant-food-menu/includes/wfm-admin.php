<?php
function wfm_setting_reset(){
  update_option('wfm_display_variation',1);
  update_option('wfm_image_size',40);
  update_option('wfm_display_mini_cart',1);
  update_option('wfm_display_image_preview',1);
  update_option('wfm_cart_template','red');
  update_option('wfm_exc_cat','');
  
  update_option('wfm_menu_bg_color','F52727');
  update_option('wfm_menu_hover_color','222222');
  update_option('wfm_menu_text_color','FFFFFF');
  update_option('wfm_submenu_bg_color','FFFFFF');
  update_option('wfm_prod_name_color','000000');
  update_option('wfm_prod_name_hover_color','FFFFFF');
  update_option('wfm_prod_des_color','000000');
  
  update_option('wfm_search_bg_color','ffffff');
  update_option('wfm_search_border_color','F52727');
  update_option('wfm_search_text_color','F52727');
      
  
  
}

function wfm_product_dropdown_categories( $args = array(), $deprecated_hierarchical = 1, $deprecated_show_uncategorized = 1, $deprecated_orderby = '' ) {
	global $wp_query;
  global $woocommerce;
	if ( ! is_array( $args ) ) {
    
		_deprecated_argument( 'wc_product_dropdown_categories()', '2.1', 'show_counts, hierarchical, show_uncategorized and orderby arguments are invalid - pass a single array of values instead.' );

		$args['show_counts']        = $args;
		$args['hierarchical']       = $deprecated_hierarchical;
		$args['show_uncategorized'] = $deprecated_show_uncategorized;
		$args['orderby']            = $deprecated_orderby;
	}

	$current_product_cat = isset( $wp_query->query['product_cat'] ) ? $wp_query->query['product_cat'] : '';
	$defaults            = array(
		'pad_counts'         => 1,
		'show_counts'        => 1,
		'hierarchical'       => 1,
		'hide_empty'         => 1,
		'show_uncategorized' => 0,
		'orderby'            => 'name',
		'selected'           => $current_product_cat,
		'menu_order'         => false
	);

	$args = wp_parse_args( $args, $defaults );

	if ( $args['orderby'] == 'order' ) {
		$args['menu_order'] = 'asc';
		$args['orderby']    = 'name';
	}

	$terms = get_terms( 'product_cat', apply_filters( 'wc_product_dropdown_categories_get_terms_args', $args ) );
  
  if (get_option('wfm_exc_cat')){
    
      $exc_cats_slug=  explode(',', get_option('wfm_exc_cat'));
      foreach ($terms as $key=>$val){
        if(in_array($val->slug, $exc_cats_slug)){
          unset($terms[$key]);
        }
      }    
  }
	if ( ! $terms ) {
		return;
	}

	$output  = "<select name='product_cat' class='dropdown_product_cat'>";
	$output .= '<option value="" ' .  selected( $current_product_cat, '', false ) . '>' . __( 'Select a category', 'woocommerce' ) . '</option>';
	$output .= wc_walk_category_dropdown_tree( $terms, 0, $args );
	if ( $args['show_uncategorized'] ) {
		$output .= '<option value="0" ' . selected( $current_product_cat, '0', false ) . '>' . __( 'Uncategorized', 'woocommerce' ) . '</option>';
	}
	$output .= "</select>";

	echo $output;
}

function wfm_setting(){
  //------------------
    //wfm_cat_data();
  //------------------
  
    if (!class_exists('Woocommerce')) {
      echo '<div id="message" class="error"><p>Please Activate Wp WooCommerce Plugin</p></div>';
      return false;
    }
    
    if(isset($_POST['wfm_status_submit']) && $_POST['wfm_status_submit']==1){
      update_option('wfm_display_variation',$_POST['wfm_display_variation']);
      update_option('wfm_image_size',$_POST['wfm_image_size']);
      update_option('wfm_display_mini_cart',$_POST['wfm_display_mini_cart']);
      update_option('wfm_display_image_preview',$_POST['wfm_display_image_preview']);
      update_option('wfm_cart_template',$_POST['wfm_cart_template']);
      update_option('wfm_exc_cat',$_POST['wfm_exc_cat']); 
      
      update_option('wfm_menu_bg_color',$_POST['wfm_menu_bg_color']);
      update_option('wfm_menu_hover_color',$_POST['wfm_menu_hover_color']);
      update_option('wfm_menu_text_color',$_POST['wfm_menu_text_color']);
      update_option('wfm_submenu_bg_color',$_POST['wfm_submenu_bg_color']);
      update_option('wfm_prod_name_color',$_POST['wfm_prod_name_color']);
      update_option('wfm_prod_name_hover_color',$_POST['wfm_prod_name_hover_color']);
      update_option('wfm_prod_des_color',$_POST['wfm_prod_des_color']);
      
      update_option('wfm_search_bg_color',$_POST['wfm_search_bg_color']);
      update_option('wfm_search_border_color',$_POST['wfm_search_border_color']);
      update_option('wfm_search_text_color',$_POST['wfm_search_text_color']);
    }

    if(isset($_POST['wfm_status_submit']) && $_POST['wfm_status_submit']==2){
      wfm_setting_reset();   
    }    
    ?>
    <h2>Settings</h2>
    <form method="post" id="wfm_options">	
        <input type="hidden" name="wfm_status_submit" id="wfm_status_submit" value="2"  />
      <table width="100%" cellspacing="2" cellpadding="5" class="editform">
        <tr style="display: none;" valign="top"> 
          <td width="150" scope="row">Display Variations:</td>
          <td>
              <select name="wfm_display_variation">
                  <option value="1"<?php if (get_option('wfm_display_variation')=='1'):?> selected="selected"<?php endif;?>>Yes</option>
<!--                  <option value="0"<?php //if (get_option('wfm_display_variation')=='0'):?> selected="selected"<?php //endif;?>>No</option>                -->
              </select>
          </td>
        </tr>
        
        <tr valign="top"> 
          <td width="150" scope="row">Product image size:</td>
          <td>
              <select name="wfm_image_size">
                  <option value="16"<?php if (get_option('wfm_image_size')==16):?> selected="selected"<?php endif;?>>16x16</option>
                  <option value="32"<?php if (get_option('wfm_image_size')==32):?> selected="selected"<?php endif;?>>32x32</option>
                  <option value="40"<?php if (get_option('wfm_image_size')==40):?> selected="selected"<?php endif;?>>40x40</option>
                  <option value="48"<?php if (get_option('wfm_image_size')==48):?> selected="selected"<?php endif;?>>48x48</option>
                  <option value="64"<?php if (get_option('wfm_image_size')==64):?> selected="selected"<?php endif;?>>64x64</option>
              </select>
          </td>
        </tr>
        <tr valign="top"> 
          <td width="150" scope="row">Display Mini Cart:</td>
          <td>
              <select name="wfm_display_mini_cart">
                  <option value="1"<?php if (get_option('wfm_display_mini_cart')=='1'):?> selected="selected"<?php endif;?>>Yes</option>
                  <option value="0"<?php if (get_option('wfm_display_mini_cart')=='0'):?> selected="selected"<?php endif;?>>No</option>                
              </select>
          </td>
        </tr>
        <tr valign="top"> 
          <td width="150" scope="row">Display Image Preview:</td>
          <td>
              <select name="wfm_display_image_preview">
                  <option value="1"<?php if (get_option('wfm_display_image_preview')=='1'):?> selected="selected"<?php endif;?>>Yes</option>
                  <option value="0"<?php if (get_option('wfm_display_image_preview')=='0'):?> selected="selected"<?php endif;?>>No</option>                
              </select>
          </td>
        </tr>
        <tr valign="top"> 
          <td width="150" scope="row">Mini Cart Template:</td>
          <td>
              <select name="wfm_cart_template">
                  <option value="red"<?php if (get_option('wfm_cart_template')=='red'):?> selected="selected"<?php endif;?>>Red</option>
                  <option value="blue"<?php if (get_option('wfm_cart_template')=='blue'):?> selected="selected"<?php endif;?>>blue</option>
                  <option value="green"<?php if (get_option('wfm_cart_template')=='green'):?> selected="selected"<?php endif;?>>Green</option>
                  <option value="sky"<?php if (get_option('wfm_cart_template')=='sky'):?> selected="selected"<?php endif;?>>Sky</option>
                  <option value="pink"<?php if (get_option('wfm_cart_template')=='pink'):?> selected="selected"<?php endif;?>>Pink</option>
                  <option value="black"<?php if (get_option('wfm_cart_template')=='black'):?> selected="selected"<?php endif;?>>Black</option>
                  <option value="grey"<?php if (get_option('wfm_cart_template')=='grey'):?> selected="selected"<?php endif;?>>Grey</option>
                  <option value="yellow"<?php if (get_option('wfm_cart_template')=='yellow'):?> selected="selected"<?php endif;?>>Yellow</option>
              </select>
          </td>
          </tr>
          
          <tr valign="top" style="display: none;"> 
            <td width="150" scope="row">Exclude Category</td>
            <td>
                <input type="text" name="wfm_exc_cat" id="wfm_exc_cat" value="<?php if (get_option('wfm_exc_cat')){echo get_option('wfm_exc_cat');}?>" />
                [Comma seperate Category Slug i.e. giftcerts, side ]
            </td>
        </tr>   
        <tr style="display: none;">
          <td>Search Button Background Color:</td>
          <td>
            <input type="text" name="wfm_search_bg_color" size="10" id="wfm_search_bg_color" class="color" value="<?php echo get_option('wfm_search_bg_color')?>" /> 
          </td>
        </tr>
        <tr style="display: none;">
          <td>Search button Border Color:</td>
          <td>
            <input type="text" name="wfm_search_border_color" size="10" id="wfm_search_border_color" class="color" value="<?php echo get_option('wfm_search_border_color')?>" /> 
          </td>
        </tr>
        <tr style="display: none;">
          <td>Search button Text Color:</td>
          <td>
            <input type="text" name="wfm_search_text_color" size="10" id="wfm_search_text_color" class="color" value="<?php echo get_option('wfm_search_text_color')?>" /> 
          </td>
        </tr>        
        <tr>
          <td>Menu Background Color:</td>
          <td>
            <input type="text" name="wfm_menu_bg_color" size="10" id="wfm_menu_bg_color" class="color" value="<?php echo get_option('wfm_menu_bg_color')?>" /> 
          </td>
        </tr>
        <tr>
          <td>Menu Hover Color:</td>
          <td>
            <input type="text" name="wfm_menu_hover_color" size="10" id="wfm_menu_hover_color" class="color" value="<?php echo get_option('wfm_menu_hover_color')?>" /> 
          </td>
        </tr>
        <tr>
          <td>Menu Text Color:</td>
          <td>
            <input type="text" name="wfm_menu_text_color" size="10" id="wfm_menu_text_color" class="color" value="<?php echo get_option('wfm_menu_text_color')?>" /> 
          </td>
        </tr>
        <tr>
          <td>Sub Menu Background Color:</td>
          <td>
            <input type="text" name="wfm_submenu_bg_color" size="10" id="wfm_submenu_bg_color" class="color" value="<?php echo get_option('wfm_submenu_bg_color')?>" /> 
          </td>
        </tr>
        <tr>
          <td>Item Name Color:</td>
          <td>
            <input type="text" name="wfm_prod_name_color" size="10" id="wfm_prod_name_color" class="color" value="<?php echo get_option('wfm_prod_name_color')?>" /> 
          </td>
        </tr>
        <tr>
          <td>Item Name Hover Color:</td>
          <td>
            <input type="text" name="wfm_prod_name_hover_color" size="10" id="wfm_prod_name_hover_color" class="color" value="<?php echo get_option('wfm_prod_name_hover_color')?>" /> 
          </td>
        </tr>
        <tr>
          <td>Item Description Color:</td>
          <td>
            <input type="text" name="wfm_prod_des_color" size="10" id="wfm_prod_des_color" class="color" value="<?php echo get_option('wfm_prod_des_color')?>" /> 
          </td>
        </tr>        
          <tr valign="top">
            <td colspan="2" scope="row">			
              <input type="button" name="save" onclick="document.getElementById('wfm_status_submit').value='1'; document.getElementById('wfm_options').submit();" value="Save setting" class="button-primary" />
              <input type="button" name="reset" onclick="document.getElementById('wfm_status_submit').value='2'; document.getElementById('wfm_options').submit();" value="Reset to default setting" class="button-primary" />
            </td> 
          </tr>
    </table>
  </form>   
<?php
}