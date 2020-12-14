<?php	
add_shortcode('vendor_order_list_view', 'wvs_view_vendor_order_list_for_front_page');

function wvs_vendor_add_front_sub_menu_function(){
	$user_id = get_current_user_id();
	$become_a_vendor = get_user_meta( $user_id, 'become_a_vendor', true ); 
	$vendor_status = get_user_meta( $user_id, 'become_a_vendor_status', true ); 
	if($become_a_vendor=='vendor'){
		wvs_create_post_page(WC_PM,'[vendor_order_list_view]');
	}
}
add_shortcode('wpvendorlist', 'wvs_get_all_vendor_list');
function wvs_get_all_vendor_list(){
	global $woocommerce, $post, $current_user, $wpdb;
	
	if(isset($_GET['vid'])){
		echo $_GET['vid'];
		$vendor_name = get_post_meta( $_GET['vid'], '_vendor_name', true );
		$vendor_company = get_post_meta( $_GET['vid'], '_vendor_company', true );
		$vendor_email = get_post_meta( $_GET['vid'], '_vendor_email', true );
		
		$vendor_phone = get_post_meta( $_GET['vid'], '_vendor_phone', true );
		$vendor_fax = get_post_meta( $_GET['vid'], '_vendor_fax', true );
		$vendor_address = get_post_meta( $_GET['vid'], '_vendor_address', true );
		
		$vendor_zip = get_post_meta( $_GET['vid'], '_vendor_zip', true );
		$vendor_state = get_post_meta( $_GET['vid'], '_vendor_state', true );
		$vendor_country = get_post_meta( $_GET['vid'], '_vendor_country', true );
		
		
		$vendor_company = get_post_meta( $_GET['vid'], '_vendor_company', true );
		$ven_details = $wpdb->get_results("SELECT * FROM wp_postmeta where meta_value = '".$_GET['vid']."' and meta_key = '_vendor_select' ORDER BY post_id DESC");
		//echo '<pre>';
		//print_r($ven_details);
		?>
        <div class="wrap">
        <h2>Company Info</h2>
            <table class="form-table">
            <tbody>
            <tr>
                <th scope="row"><label>Name</label></th>
                <td><?php echo $vendor_name;?></td>
            </tr>
            <tr>
                <th scope="row"><label>Company</label></th>
                <td><?php echo $vendor_company;?></td>
            </tr>
            <tr>
                <th scope="row"><label>Email</label></th>
                <td><?php echo $vendor_email;?></td>
            </tr>
            <tr>
                <th scope="row"><label>Phone</label></th>
                <td><?php echo $vendor_phone;?></td>
            </tr>
            <tr>
                <th scope="row"><label>Fax</label></th>
                <td><?php echo $vendor_fax;?></td>
            </tr>
            <tr>
                <th scope="row"><label>Address</label></th>
                <td><?php echo $vendor_address.', '.$vendor_zip.', '.$vendor_state.', '.$vendor_country;?></td>
            </tr>
            <tr>
                <th scope="row" valign="top" style="vertical-align:top"><label>Company Logo</label></th>
                <td><?php echo get_the_post_thumbnail( $_GET['vid'], array(100,100) );?></td>
            </tr>
            </tbody>
            </table>
        </div>
		<div style="width:100%; margin-bottom:5%;">
    	<h2>All product's of <b style="color:#060;"><?php echo $vendor_company;?></b></h2>
            <div style="width:100%;">
                <?php
                    $i=1;
					$chk_empty = 0;
                    if(!empty($ven_details)){
                        foreach($ven_details as $van){
                            if ( get_post_status ( $van->post_id ) == 'publish') {
								$chk_empty = 1;
                            ?>
                            <div style="width:20%; float:left; margin-left:2%; margin-right:2%; margin-bottom:2%;">
                                <div style="width:100%; height:150px; text-align:center; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.3);">
                                    <a target="_blank" href="<?php echo get_permalink( $van->post_id);?>">
                                        <?php echo get_the_post_thumbnail( $van->post_id, array(100,100) );?>
                                    </a>
                                </div>
                                <div style="width:100%; height:20px; text-align:center; color:#bc360a; cursor:pointer;" onMouseOver="this.style.color='#ea9629'" onMouseOut="this.style.color='#bc360a'"><h4><?php echo get_the_title( $van->post_id ); ?></h4> </div>            	
                            </div>
                            <?php
                            
                            }
                            $i++;
                        }
                        
                    }
					if($chk_empty==0){
						echo '<h4><b style="color:#C9302C;">Sorry this vendor have no product yet.</b></h4>';
					}
                ?>
        </div>
        
        
        
        
        </div>
		<?php
	}
	else{
	
	$selected_vendor = get_post_meta(get_the_ID(), '_vendor_select',true);
	$selected_vendor_percentage = get_post_meta(get_the_ID(), '_vendor_percentage',true);
	$selected_vendor_note = get_post_meta(get_the_ID(), '_vendor_note',true);
	wp_reset_query();
	$args = array(
			'post_type'   => 'vendor_product',
			'post_status' => 'publish',
			'posts_per_page'=> -1
			);
	$select_ven='';
	$posts = new WP_Query( $args );
	$posts = $posts->posts;
	//echo '<pre>';
	//print_r($posts);
	if(!empty($posts)){
		?>
        <h2>All <?php echo WC_PM;?> List</h2>
		<div style="width:100%; margin-bottom:5%;">
              <div style="width:100%;">
		<?php
		$option_arr = array();
		
		foreach($posts as $pst){
			//echo '<pre>';
			//print_r($pst);
			$vendor_list = get_post_meta($pst->ID, '_vendor_company',true);
			$post_thumbnail_id = get_post_thumbnail_id( $pst->ID );
			if($vendor_list!=''){
				$select_ven=$vendor_list;
				//$select_ven.='<option value="'.$pst->ID.'">'.$vendor_list.'</option>';
				//$option_arr[$pst->ID]=__( $vendor_list, 'woocommerce' );
			} else{
				$select_ven=$pst->post_title;
				//$select_ven.='<option value="'.$pst->ID.'">'.$pst->post_title.'</option>';
				//$option_arr[$pst->ID]=__( $pst->post_title, 'woocommerce' );
			}
			
			?>
			
      <div style="width:20%; float:left; margin-left:2%; margin-right:2%; margin-bottom:2%;">
          <div style="width:100%; height:150px; text-align:center; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.3);">
              <?php
              $vendor_pro_path=$_SERVER['REQUEST_URI']."&vid=".$pst->ID."";
              if($post_thumbnail_id){
              ?>
              <a target="_blank" href="<?php echo $vendor_pro_path;?>">
                  <?php echo get_the_post_thumbnail( $pst->ID, array(100,100) );?>
              </a>
              <?php
              }
              else{
              ?>
              <a target="_blank" href="<?php echo get_permalink( $pst->ID);?>">
              <img src="<?php echo WP_CUSTOM_PRODUCT_URL;?>/vendor_resource/image/commingsoon.jpg" width="100" height="100"/>
              </a>
              <?php
              }
              ?>
          </div>
          <div style="width:100%; height:20px; text-align:center; color:#bc360a; cursor:pointer;" onMouseOver="this.style.color='#ea9629'" onMouseOut="this.style.color='#bc360a'"><h4><?php echo $select_ven; ?></h4> </div>            	
      </div>
      <div style="clear:both;"></div>
       
	<?php
		}
		//return $select_ven;
		?>
		</div>
    		</div>
		<?php
		}
	}
}



function wvs_create_post_page($title,$content){
	global $user_ID;
	$new_page_title = $title;
	$new_page_content = $content;
	$new_page_template = '';
	$page_check = get_page_by_title($new_page_title);
	$new_page = array(
			'post_type' => 'page',
			'post_title' => $new_page_title,
			'post_content' => $new_page_content,
			'post_status' => 'publish',
			'comment_status' => 'closed',          
			'post_author' => $user_ID
	);
	if(!isset($page_check->ID)){
	  $new_page_id = wp_insert_post($new_page);
	  update_option( $title, $new_page_id );
	}
}

function wvs_view_vendor_order_list_for_front_page(){
  //echo wc_get_page_permalink( 'myaccount' );
  //echo wc_get_endpoint_url( 'customer-logout', '', wc_get_page_permalink( 'myaccount' ) );
  //die('+++++++');
  global $user_ID;
  global $current_user;
	$user_id = get_current_user_id();
	$become_a_vendor = get_user_meta( $user_id, 'become_a_vendor', true );  
  
  if (current_user_can( 'manage_options' )) {
          echo 'You are logged in as a admin user.To view in this page please logged in as a user';
          return false;
  }
		
  if (!is_user_logged_in()) {
	wp_redirect( get_permalink( get_option('woocommerce_myaccount_page_id') ) );
    echo 'Please logged in to view this page';
    return false;
  }
  
  if (is_user_logged_in()) {
    $user_ID = get_option( $current_user->user_nicename );		
    if($user_ID ==''){	
        ?><div style="width:100%; text-align:center; color:#F00; font-size:14px;">
        <?php _e("You do not have permission to access this page","woocommerce-vendor-setup"); ?>
      </div><?php
      return false;		
    }    

    //--
    $user_data=get_userdata( $user_id );
	$uid=$user_data->ID;
 	$v_status = get_user_meta($uid, 'become_a_vendor_status');
    if(isset($v_status[0])){
		if($v_status[0]=='Pending'){
		   echo 'You vendor account is waiting for admin approval';
          return false;
		}
	}   

    $user_name=$user_data->data->user_login;
    $vendor_post_id=get_option($user_name);
    $vendor_status=get_post_meta($vendor_post_id,'_vendor_status',true);
    
    //disable
    if($vendor_status=='disable'){
      echo 'Your accout is disable.Please contact with site admin';
      return false;
    }    
  }	
			
	if(isset($_GET['action_type'])&&($_GET['action_type']=='order_details')){
		$order_id = $_GET['order_id'];
		global $woocommerce;
		global $wpdb;
		global $user_ID;
		global $current_user;
		echo woocommerce_order_details_table($order_id);
	} else if(isset($_GET['delivery_type'])&&($_GET['delivery_type']=='delivered')){
		global $wpdb;
		global $user_ID;
		global $current_user;
		global $woocommerce;
		
		$custom_table_prefix = 'woocommerce_';
	  	$wpdb_all_prefix = $wpdb->prefix.$custom_table_prefix;
		//$vendor_page = get_page_by_title('Vendor');
		//$vendor_page_id =  $vendor_page->ID;
		$vendor_page_id = get_the_ID();
		
		
		$current_user = wp_get_current_user();
		//echo 'fdgdfgdfg';
		
		$user_ID = get_option( $current_user->user_nicename );
		//echo $user_ID.'---->';
		$order_id = $_GET['order_id'];
		//echo "SELECT * FROM {$wpdb_all_prefix}vendor where vendor_vendor_id = ".$user_ID." and vendor_order_id = ".$order_id." ORDER BY vendor_id DESC";
		$vendor_records_popup = $wpdb->get_results("SELECT * FROM {$wpdb_all_prefix}vendor where vendor_vendor_id = ".$user_ID." and vendor_order_id = ".$order_id." ORDER BY vendor_id DESC " );
		$vendor_id = $wpdb->get_row("SELECT vendor_vendor_id FROM wp_woocommerce_vendor where vendor_order_id = ".$order_id." limit 1" );
		//print_r($vendor_records_popup);
		if( $vendor_records_popup ) { 
			foreach( $vendor_records_popup as $ven_record ) {
				$vendor_product = $wpdb->get_row("SELECT * FROM wp_postmeta where post_id = ".$ven_record->vendor_product_id." and meta_value = ".$vendor_id->vendor_vendor_id."" );
				//echo '<pre>';
				//print_r($vendor_product);
				
				if(!empty($vendor_product)){
					/*echo "
					  UPDATE {$wpdb_all_prefix}vendor 
					  SET vendor_product_delivared = '".$_GET['action_type']."'
					  WHERE vendor_order_id = ".$ven_record->vendor_order_id." 
						  AND vendor_product_id = ".$ven_record->vendor_product_id."
					  ";*/
				  $wpdb->query(
					  "
					  UPDATE {$wpdb_all_prefix}vendor 
					  SET vendor_product_delivared = '".$_GET['action_type']."'
					  WHERE vendor_order_id = ".$ven_record->vendor_order_id." 
						  AND vendor_product_id = ".$ven_record->vendor_product_id."
					  "
				  );
				}
			}
		}
		wp_redirect( site_url( '/?page_id='.$vendor_page_id.'') );
    	exit;
		
		
	}
	else{
	?>
  <script type="text/javascript">
  /*jQuery(document).ready(function(){
	jQuery( '.vendor_view_tab').tabs();
  });*/
  	function get_hostname(url) {
		var m = url.match(/^http:\/\/[^/]+/);
		return m ? m[0] : null;
	}
	function ShowMyDiv(Obj){
  var elements = document.getElementsByTagName('div');
	for (var i = 0; i < elements.length; i++) 
		if(elements[i].className=='tabcontent')
			elements[i].style.display= 'none';

	document.getElementById(Obj.rel).style.display= 'block';
	//------------------------------------

  var ul_el = document.getElementById('tab_ul');
  var li_el = ul_el.getElementsByTagName('li');
	for (var i = 0; i < li_el.length; i++) 
		li_el[i].className="";

	Obj.parentNode.className="selected";}
	function set_delivery_status(vendor_page, vendor_order){
		//alert(vendor_page+'----'+vendor_order);
		var action_type = jQuery("#ven_order_"+vendor_order).val();
		//alert(vendor_page+'----'+vendor_order+'----'+action_type);
		
		if((vendor_page!='')&&(vendor_order!='')){
			//alert("<?php //echo get_option('siteurl');?>/?page_id="+vendor_page+"&order_id="+vendor_order+"&action_type="+action_type+"&delivery_type=delivered");
			window.location.href="<?php echo get_option('siteurl');?>/?page_id="+vendor_page+"&order_id="+vendor_order+"&action_type="+action_type+"&delivery_type=delivered";
			/*setTimeout(function() {
				  window.location.href = "<?php //echo get_option('siteurl');?>/?page_id="+vendor_page+"&order_id="+vendor_order+"&action_type="+action_type+"&delivery_type=delivered";
			}, 5000);*/
		}
	}
  
  </script>

  <?php
	global $wpdb;
	global $user_ID;
	global $current_user;
	global $woocommerce;
  $current_user = wp_get_current_user();
	//$vendor_page = get_page_by_title('Vendor');
	//$vendor_page_id =  $vendor_page->ID;
	$vendor_page_id = get_the_ID();
	$user_ID = get_option( $current_user->user_nicename );
	
	if($user_ID!=''){	
	  //-------------------  Vendor Info ----------------------
    $vendor_name=get_post_meta($user_ID,'_vendor_name',true);
    $vendor_company=get_post_meta($user_ID,'_vendor_company',true);
  	$vendor_email=get_post_meta($user_ID,'_vendor_email',true);
  	$vendor_phone=get_post_meta($user_ID,'_vendor_phone',true);
    $vendor_fax=get_post_meta($user_ID,'_vendor_fax',true);
	
    $vendor_address=get_post_meta($user_ID,'_vendor_address',true);
    $vendor_zip=get_post_meta($user_ID,'_vendor_zip',true);
  	$vendor_state=get_post_meta($user_ID,'_vendor_state',true);
  	$vendor_country=get_post_meta($user_ID,'_vendor_country',true);
    $vendor_paypal=get_post_meta($user_ID,'_vendor_paypal',true);
	  //-------------------------------------------------------	  
	  $custom_table_prefix = 'woocommerce_';
	  $pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
	  $limit =5;
	  $offset = ( $pagenum - 1 ) * $limit;
	  $wpdb_all_prefix = $wpdb->prefix.$custom_table_prefix;
	  
	  $args_publish = array(
		  'author'     =>  get_current_user_id(),
		  'post_type'  => 'product',
		  'post_status'  => 'publish'
	  );
	  
	  $author_publish_posts = get_posts( $args_publish );
	  
	  $args_pending = array(
		  'author'     =>  get_current_user_id(),
		  'post_type'  => 'product',
		  'post_status'  => 'pending'
	  );
	  
	  $author_pending_posts = get_posts( $args_pending );
	  
	  $vendor_records_details = $wpdb->get_results("SELECT * FROM {$wpdb_all_prefix}vendor where vendor_vendor_id = ".$user_ID."  ORDER BY vendor_id DESC " );
	  $vendor_records = $wpdb->get_results("SELECT vendor_order_id, count(*) as total_product, count(vendor_product_qty) as pro_qty,sum(vendor_product_amount) as total_price, vendor_order_date FROM wp_woocommerce_vendor where vendor_vendor_id = ".$user_ID." GROUP BY `vendor_order_id`" );
	  
	  
	  $vendor_records_stat = $wpdb->get_results("SELECT count(*) as cnt_record, `vendor_order_date` FROM {$wpdb_all_prefix}vendor WHERE `vendor_vendor_id` = ".$user_ID." GROUP BY `vendor_order_date` ORDER BY vendor_id" );
	  $vendor_all_record = "['Date', 'Sales'],";
	foreach($vendor_records_stat as $ven_stat){
      $vendor_all_record .="['".$ven_stat->vendor_order_date."', ".$ven_stat->cnt_record."],";
	}
  
  $vendor_all_record .="['".date('Y-m-d')."', 0],";
  $tomorrow=mktime(0, 0, 0, date("m"), date("d")+1, date("Y"));
  $next_tomorrow=mktime(0, 0, 0, date("m"), date("d")+2, date("Y"));
  $next_next_tomorrow=mktime(0, 0, 0, date("m"), date("d")+3, date("Y"));
  $next_next_next_tomorrow=mktime(0, 0, 0, date("m"), date("d")+4, date("Y"));
  $next_next_next_next_tomorrow=mktime(0, 0, 0, date("m"), date("d")+5, date("Y"));
  
  $vendor_all_record .="['".date('Y-m-d',$tomorrow)."', 0],";
  $vendor_all_record .="['".date('Y-m-d',$next_tomorrow)."', 0],";
  
	   if( $vendor_records ) { 
            $count = 1;
            $class = '';
            foreach( $vendor_records as $ven_record ) {
            ?>
          <div id="vendor_ordered_item_<?php echo $ven_record->vendor_order_id;?>" style="display:none">
            <h2>My items Order No&nbsp;:&nbsp;<?php echo $ven_record->vendor_order_id;?></h2>
            <div class="woocommerce" style="float:left;padding:10px; width:95%;">
          <table class="shop_table my_account_orders">
                <thead>
                  <tr>
                    <th><?php _e("Product","woocommerce-vendor-setup"); ?></th>
                    <th>&nbsp;</th>
                    <th><?php _e("Qty","woocommerce-vendor-setup"); ?></th>
                	<th><?php _e("Amount","woocommerce-vendor-setup"); ?></th>
                	<th><?php _e("Percent","woocommerce-vendor-setup"); ?></th>
                    <th><?php _e("Percent Amount","woocommerce-vendor-setup"); ?></th>
                    <th><?php _e("Status","woocommerce-vendor-setup"); ?></th>
                  </tr>
                </thead>
                <tbody>
                <?php 
                $table_woocommerce_vendor=$wpdb->prefix.'woocommerce_vendor';
				$vendor_records_popup = $wpdb->get_results("SELECT * FROM {$wpdb_all_prefix}vendor where vendor_vendor_id = ".$user_ID." and vendor_order_id = ".$ven_record->vendor_order_id." ORDER BY vendor_id DESC " );
				//echo "SELECT vendor_vendor_id FROM wp_woocommerce_vendor where vendor_order_id = ".$ven_record->vendor_order_id." limit 1";
				$vendor_id = $wpdb->get_row("SELECT vendor_vendor_id FROM ".$table_woocommerce_vendor." where vendor_order_id = ".$ven_record->vendor_order_id." limit 1" );

				if( $vendor_records_popup ) { 
                $count = 1;
				$delivary_status = '';				
                $class = '';
                foreach( $vendor_records_popup as $ven_record ) {
					$vendor_product = $wpdb->get_row("SELECT * FROM wp_postmeta where post_id = ".$ven_record->vendor_product_id." and meta_value = ".$vendor_id->vendor_vendor_id."" );
					if(!empty($vendor_product)){
						if($ven_record->vendor_product_delivared=='Pending'){
							$delivary_status = 'Pending';
						}
						else{
							$delivary_status = 'Delivered';
						}
                  $class = ( $count % 2 == 0 ) ? ' style="background-color:#4CC5E2"' : '';
                ?>
                  <tr>
                    <td><?php echo $ven_record->vendor_product_name;?></td>
                    <td>
                    	<?php
							$img_url = wp_get_attachment_image_src(get_post_thumbnail_id($ven_record->vendor_product_id),'thumbnail');
                      ?>
                        <img src="<?php echo $img_url[0];?>" width="45" height="45" />
                    </td>
                    <td><?php echo $ven_record->vendor_product_qty;?></td>
                    <td><?php echo $ven_record->vendor_product_amount;?></td>                
                    <td><?php echo $ven_record->vendor_percent;?>&nbsp;%</td>
                    <td><?php echo $ven_record->vendor_amount;?></td>
                    <td><?php echo $ven_record->vendor_product_delivared;?></td>
                  </tr>
                  <?php
                    $count++;
					}
        }
                  ?>
               <?php } else { ?>
                  <tr>
                    <td colspan="4" style="text-align:center; color:#F00; font-weight:bold;"><?php _e("You have no order for pending.","woocommerce-vendor-setup"); ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
              <div style="width:100%;">
              	<?php
                	
					$billing_first_name =  get_post_meta($ven_record->vendor_order_id,'_billing_first_name',true);
					$billing_last_name  =  get_post_meta($ven_record->vendor_order_id,'_billing_last_name',true);
					$billing_company = get_post_meta($ven_record->vendor_order_id,'_billing_company',true);
					$billing_address = get_post_meta($ven_record->vendor_order_id,'_billing_address_1',true);
					$billing_address2 = get_post_meta($ven_record->vendor_order_id,'_billing_address_2',true);
					$billing_city = get_post_meta($ven_record->vendor_order_id,'_billing_city',true);
					$billing_postcode = get_post_meta($ven_record->vendor_order_id,'_billing_postcode',true);
					$billing_country = get_post_meta($ven_record->vendor_order_id,'_billing_country',true);
					$billing_state = get_post_meta($ven_record->vendor_order_id,'_billing_state',true);
					$billing_email = get_post_meta($ven_record->vendor_order_id,'_billing_email',true);
					$billing_phone = get_post_meta($ven_record->vendor_order_id,'_billing_phone',true);
					$billing_paymethod = get_post_meta($ven_record->vendor_order_id,'_payment_method',true);
					
					$shipping_first_name =  get_post_meta($ven_record->vendor_order_id,'_shipping_first_name',true);
					$shipping_last_name = get_post_meta($ven_record->vendor_order_id,'_shipping_last_name',true);
					$shipping_company = get_post_meta($ven_record->vendor_order_id,'_shipping_company',true);
					$shipping_address = get_post_meta($ven_record->vendor_order_id,'_shipping_address_1',true);
					$shipping_address2 = get_post_meta($ven_record->vendor_order_id,'_shipping_address_2',true);
					$shipping_city = get_post_meta($ven_record->vendor_order_id,'_shipping_city',true);
					$shipping_postcode = get_post_meta($ven_record->vendor_order_id,'_shipping_postcode',true);
					$shipping_country = get_post_meta($ven_record->vendor_order_id,'_shipping_country',true);
					$shipping_state = get_post_meta($ven_record->vendor_order_id,'_shipping_state',true);
					$shipping_email = get_post_meta($ven_record->vendor_order_id,'_shipping_email',true);
					$shipping_phone = get_post_meta($ven_record->vendor_order_id,'_shipping_phone',true);
					//$billing_paymethod = get_post_meta($ven_record->vendor_order_id,'_payment_method',true);
				?>
                <div style="width:49%; min-height:220px; float:left; border:solid 1px #CCCCCC;">
                	<div style="width:100%; min-height:20px; background:#CCC;">
                    	<h3>&emsp;Billing Address</h3>
                    </div>
                	<div style="width:80%; margin:auto;">                                    	
                	<?php
                    	echo $billing_first_name.' '.$billing_last_name.'<br>';
                      echo $billing_company.'<br>'; 
                      echo $billing_address.'<br>'; 
                      echo $billing_city.'<br>'; 
                      echo $billing_postcode.'<br>';
                      echo $billing_state.', '.$billing_country.'<br>'; 
                      echo $billing_email.'<br>'; 
                      echo $billing_phone.'<br>'; 
                      //echo $billing_paymethod.'<br>';
                    ?>
                  </div>
                </div>
                <div style="width:49%; min-height:220px; float:right;  border:solid 1px #CCCCCC;">
                    <div style="width:100%; min-height:20px; background:#CCC;">
                    	<h3>&emsp;<?php _e("Shipping Address","woocommerce-vendor-setup"); ?></h3>
                    </div>
                    <div style="width:80%; margin:auto;">                    
                    <?php
                    	echo $shipping_first_name.' '.$shipping_last_name.'<br>';
                      echo $shipping_company.'<br>'; 
                      echo $shipping_address.'<br>'; 
                      echo $shipping_city.'<br>'; 
                      echo $shipping_postcode.'<br>';
                      echo $shipping_state.', '.$shipping_country.'<br>'; 
                      echo $shipping_email.'<br>'; 
                      echo $shipping_phone.'<br>';
                    ?>
                    </div>
                </div>
              </div>
              <div style="clear:both;"></div>
              <div style="width:98%; text-align:right; margin-top:5px;">
              	<?php
                	//if($delivary_status!='Delivered'){
                ?>
                <select id="ven_order_<?php echo $ven_record->vendor_order_id;?>">
                	<option value="Pending" <?php if($delivary_status=='Pending')echo 'selected="selected"';?>><?php _e("Pending","woocommerce-vendor-setup"); ?></option>
                    <option value="Delivered" <?php if($delivary_status=='Delivered')echo 'selected="selected"';?>><?php _e("Delivered","woocommerce-vendor-setup"); ?></option>
                </select>
                <a class="button view" onclick="set_delivery_status('<?php echo $vendor_page_id;?>','<?php echo $ven_record->vendor_order_id;?>');"><?php _e("Submit","woocommerce-vendor-setup"); ?></a>
                <?php
					//}
				?>
              </div>
            </div>           
            <!--<strong>Just click outside the pop-up to close it.</strong>-->
          </div>
          <?php
			}
		  }
		  ?>  
          
        <div id="parentHorizontalTab">
        <div style="margin-bottom:25px;">
          
          <style>
            .wvs_box {
                background: #5ab1d0;
                transition-property: background;
                transition-duration: 1s;
                transition-timing-function: linear;
                width: 140px;
                height:35px;
                text-align: center;
                color: #ffffff;
                font-size: 14px;
                padding-top: 5px;
                float: left;
                margin-right: 10px;
                cursor: pointer;
                border-radius: 2px;
              }
              .wvs_box:hover {
                background: #F94848;
              }
              .wvs_box a{
                color: #FFFFFF;
                text-decoration: none !important;
                text-decoration-color: red;
              }
              .wvs_box a:hover{
                color: #FFFFFF;
                text-decoration: none !important;
                text-decoration-color: red;
              }
          </style> 

       <div class="wvs_box"><a target="_blank" href="<?php echo admin_url().'profile.php';?>"><?php echo WC_PM;?> Settings</a></div>
       <div class="wvs_box"><a target="_blank" href="<?php echo admin_url().'profile.php?page=vendor_pro_upload';?>">Upload products</a></div>
       <div class="wvs_box"><a href="<?php echo wc_get_endpoint_url( 'customer-logout', '', wc_get_page_permalink( 'myaccount' ) );?>">Logout</a></div>
       <br />


<!--        For upload product <a target="_blank" href="<?php //echo admin_url().'profile.php?page=vendor_pro_upload';?>" class="myButton">Click Here</a>-->
        </div>
        
          <ul class="resp-tabs-list hor_1">
            <li class="selected"><?php _e("Statistics","woocommerce-vendor-setup"); ?></li>
            <?php
            if(!current_user_can('edit_theme_options')){
            ?>
                <li>                  
                    <?php _e("Order Lists","woocommerce-vendor-setup"); ?>                  
                </li>
                <li>                  
                    <?php _e("Payment Status","woocommerce-vendor-setup"); ?>                  
                </li>
                <li>
                  Published Product
                </li>
                <li>
                  Pending Product
                </li>
            <?php
            }
            ?>
          </ul>
        <div class="resp-tabs-container hor_1">
            <div>
                <script type="text/javascript">
                  google.load("visualization", "1", {packages:["corechart"]});
                  google.setOnLoadCallback(drawChart);
                  function drawChart(){
                  var data = google.visualization.arrayToDataTable([<?php echo $vendor_all_record;?>]);

                  var options = {
                    title: 'Sales Performance',
                    hAxis: {title: 'Date',  titleTextStyle: {color: '#333'}},
                    vAxis: {minValue: 0},
                          chartArea:{left:50,top:50,width:'80%',height:'75%'},
                          legend: {position: 'top',alignment:'end'},
                          colors: ['#1C9B24'],
                    pointShape: 'circle',    //'circle', 'triangle', 'square', 'diamond', 'star', or 'polygon'
                    pointSize: 5
                  };

                  var chart = new google.visualization.AreaChart(document.getElementById('liner_divssss'));
                  chart.draw(data, options);
                  }
                </script>
                <div id="liner_divssss" style="width:95%; height: 350px;" ></div>
              </div>
            
            <div>
                <script type="text/javascript">
                  jQuery(document).ready(function(){ 
                      jQuery("#orderHistory").tablesorter({widthFixed: false, widgets: ['zebra']}); 
                      jQuery("#orderHistory").tablesorterPager({container: jQuery("#order_pager")});			
                    } 
                  );
                </script>
                <h2>Order Record of <font style="color:#090; font-weight:bold;"><?php echo $vendor_name;?></font></h2>
                <div class="woocommerce">
                <?php  if( $vendor_records ) { ?>
                  <table class="shop_table my_account_orders tablesorter" id="orderHistory" style="width:100%;">
                  <thead style="background-color:#DBDBDB; cursor:pointer;"> 
                    <tr>
                      <th><?php _e("Order ID","woocommerce-vendor-setup"); ?></th>
                      <th><?php _e("Total Item","woocommerce-vendor-setup"); ?></th>
      <!--                <th>Qty</th>-->
                      <th><?php _e("Amount","woocommerce-vendor-setup"); ?></th>			  
                      <th><?php _e("Purchase Date","woocommerce-vendor-setup"); ?></th>
                      <th>&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                  $count = 1;
                  $class = '';
                  foreach( $vendor_records as $ven_record ) {
                    $class = ( $count % 2 == 0 ) ? ' style="background-color:#4CC5E2"' : '';
                  ?>
                    <tr>
                      <td style="color:#1898FC;"><a style="cursor:pointer;" href="?page_id=<?php echo $vendor_page_id;?>&order_id=<?php echo $ven_record->vendor_order_id;?>&action_type=order_details" >#<?php echo $ven_record->vendor_order_id;?></a></td>
                      <td><?php echo $ven_record->total_product;?>&nbsp;<?php _e("Item","woocommerce-vendor-setup"); ?></td>
      <!--                <td><?php //echo $ven_record->pro_qty;?></td>-->
                      <td><?php echo $ven_record->total_price;?></td>                
                      <td><?php echo $ven_record->vendor_order_date;?></td>
                      <td class="order-actions">
                <a class="button view thickbox" href="#TB_inline?width=600&height=550&inlineId=vendor_ordered_item_<?php echo $ven_record->vendor_order_id;?>"><?php _e("Order Details","woocommerce-vendor-setup"); ?></a>

                      </td>
                    </tr>
                    <?php
                      $count++;
                    }
                    ?>

                  </tbody>
                </table>
                  <div id="order_pager" class="pager">
                    <form>
                        <img src="<?php echo WP_CUSTOM_PRODUCT_URL;?>/vendor_resource/image/pager/first.png" class="first"/>
                        <img src="<?php echo WP_CUSTOM_PRODUCT_URL;?>/vendor_resource/image/pager/prev.png" class="prev"/>
                        <input type="text" class="pagedisplay"/>
                        <img src="<?php echo WP_CUSTOM_PRODUCT_URL;?>/vendor_resource/image/pager/next.png" class="next"/>
                        <img src="<?php echo WP_CUSTOM_PRODUCT_URL;?>/vendor_resource/image/pager/last.png" class="last"/>
                        <select class="pagesize">
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option selected="selected"  value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </form>
                </div>
                <?php }?>
                </div>
              <?php
              $total = $wpdb->get_var( "SELECT count(*) FROM {$wpdb_all_prefix}vendor where vendor_vendor_id = ".$user_ID."  ORDER BY vendor_id DESC");
              $num_of_pages = ceil( $total / $limit );
              $page_links = paginate_links( array(
                  'base' => add_query_arg( 'pagenum', '%#%' ),
                  'format' => '',
                  'prev_text' => __( '&laquo;', 'aag' ),
                  'next_text' => __( '&raquo;', 'aag' ),
                  'total' => $num_of_pages,
                  'current' => $pagenum
                ));
            ?>
            </div>
            
            <!-- ********************************** Payment Status ************************************************** -->
            <div>
              <?php 
              if(!current_user_can('edit_theme_options')){ 
              ?>
                <script type="text/javascript">
                  jQuery(document).ready(function(){ 
                      jQuery("#orderHistory_Details").tablesorter({widthFixed: false, widgets: ['zebra']}); 
                      jQuery("#orderHistory_Details").tablesorterPager({container: jQuery("#order_details_pager")});			
                    } 
                  );

                </script>
                <h2><?php _e("Payment Status of","woocommerce-vendor-setup"); ?> 
                    <font style="color:#090; font-weight:bold;"><?php echo $vendor_name;?></font>
                </h2>
                <div class="woocommerce">
                <?php 
                if( $vendor_records_details ) { 
                ?>

                <table class="shop_table my_account_orders tablesorter" id="orderHistory_Details" style="width:100%;">
                  <thead style="background-color:#DBDBDB; cursor:pointer;"> 
                    <tr>
                      <th><?php _e("Product","woocommerce-vendor-setup"); ?></th>
                      <th><?php _e("Qty","woocommerce-vendor-setup"); ?></th>
                    <th><?php _e("Amount","woocommerce-vendor-setup"); ?></th>
                    <th><?php _e("Percent","woocommerce-vendor-setup"); ?></th>
                    <th><?php _e("Percent Amount","woocommerce-vendor-setup"); ?></th>			  
                    <th><?php _e("Purchase Date","woocommerce-vendor-setup"); ?></th>
                      <th><?php _e("Status","woocommerce-vendor-setup"); ?></th>
                      <th><?php _e("Pay Date","woocommerce-vendor-setup"); ?></th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php 
                  $count = 1;
                  $class = '';
                  foreach( $vendor_records_details as $ven_record ) {
                    $class = ( $count % 2 == 0 ) ? ' style="background-color:#4CC5E2"' : '';
                  ?>
                    <tr>
                      <td><?php echo $ven_record->vendor_product_name;?></td>
                      <td><?php echo $ven_record->vendor_product_qty;?></td>
                      <td><?php echo $ven_record->vendor_product_amount;?></td>                
                    <td><?php echo $ven_record->vendor_percent;?>&nbsp;%</td>
                    <td><?php echo $ven_record->vendor_amount;?></td>
                    <td><?php echo $ven_record->vendor_order_date;?></td>
                      <td><?php echo $ven_record->vendor_send_money_status;?></td>
                      <td><?php echo $ven_record->vendor_send_money_date;?></td>
                    </tr>
                    <?php
                      $count++;
                    }
                    ?>

                  </tbody>
                </table>
                <div id="order_details_pager" class="pager">
                  <form>
                      <img src="<?php echo WP_CUSTOM_PRODUCT_URL;?>/vendor_resource/image/pager/first.png" class="first"/>
                      <img src="<?php echo WP_CUSTOM_PRODUCT_URL;?>/vendor_resource/image/pager/prev.png" class="prev"/>
                      <input type="text" class="pagedisplay"/>
                      <img src="<?php echo WP_CUSTOM_PRODUCT_URL;?>/vendor_resource/image/pager/next.png" class="next"/>
                      <img src="<?php echo WP_CUSTOM_PRODUCT_URL;?>/vendor_resource/image/pager/last.png" class="last"/>
                      <select class="pagesize">
                          <option value="1">1</option>
                          <option value="5">5</option>
                          <option value="10">10</option>
                          <option selected="selected"  value="25">25</option>
                          <option value="50">50</option>
                          <option value="100">100</option>
                      </select>
                  </form>
              </div>
                <?php 

                } else { 
                ?>
                  <div style="text-align:center; color:#F00; font-weight:bold; width:100%;">
                    <?php _e("You have no order for pending.","woocommerce-vendor-setup"); ?>
                  </div>
                <?php           
                } 
                ?>
                </div>
                    <?php
                    $total = $wpdb->get_var( "SELECT count(*) FROM {$wpdb_all_prefix}vendor where vendor_vendor_id = ".$user_ID."  ORDER BY vendor_id DESC");
                    $num_of_pages = ceil( $total / $limit );
                    $page_links = paginate_links( array(
                        'base' => add_query_arg( 'pagenum', '%#%' ),
                        'format' => '',
                        'prev_text' => __( '&laquo;', 'aag' ),
                        'next_text' => __( '&raquo;', 'aag' ),
                        'total' => $num_of_pages,
                        'current' => $pagenum
                      ));

                   ?>
            <?php
            }
            ?> 
            </div>
            
            <!--  *********************************  Product Published ****************************** -->            
            
                <div>
                  
                  <?php wvs_product_publish(); ?>
                </div>            
            
            <!--  *********************************  Product Pending  ****************************** -->

            
                <div>
                   <?php wvs_product_pending();?>
                </div>
          
            <!--****************************  Product Tab ***************************-->
          
         </div>
       </div>
       
       <script type="text/javascript">
        jQuery(document).ready(function() {
              //Horizontal Tab
              jQuery('#parentHorizontalTab').easyResponsiveTabs({
                  type: 'default', //Types: default, vertical, accordion
                  width: 'auto', //auto or any width like 600px
                  fit: true, // 100% fit in a container
                  tabidentify: 'hor_1', // The tab groups identifier
                  activate: function(event) { // Callback function if tab is switched
                      var $tab = jQuery(this);
                      var $info = jQuery('#nested-tabInfo');
                      var $name = jQuery('span', $info);
                      $name.text($tab.text());
                      $info.show();
                  }
              });    
          });
      </script>
	  <?php
	} else{
		?>
			<div style="width:100%; text-align:center; color:#F00; font-size:14px;">
        <?php _e("Please wait until admin approval","woocommerce-vendor-setup"); ?>
      </div>
		<?php	
	}
		}
}

function wvs_product_publish(){
  global $wpdb;
  $limit =5;
  echo get_current_user_id();
  $pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
  	  $args_publish = array(
		  'author'     =>  get_current_user_id(),
		  'post_type'  => 'product',
		  'post_status'  => 'publish'
	  );
	  
	$author_publish_posts = get_posts( $args_publish );
  
      if(!current_user_can('edit_theme_options')){
      ?>
      <script type="text/javascript">
              jQuery(document).ready(function(){ 
                  jQuery("#uploadProducts_Publish").tablesorter({widthFixed: false, widgets: ['zebra']}); 
                  jQuery("#uploadProducts_Publish").tablesorterPager({container: jQuery("#author_publish_posts")});			
                } 
              );
        </script>

<!--        <div class="woocommerce">-->
      <h2>Published Product</h2>
      <?php if( $author_publish_posts ) {
        
        ?>
        

        <table class="shop_table my_account_orders tablesorter" id="uploadProducts_Publish" style="width:100%;">
          <thead style="background-color:#DBDBDB; cursor:pointer;"> 
            <tr>
              <th>SN</th>
              <th>ID</th>
              <th>Title</th>
              <th>Date</th>
              <th>&nbsp;</th>	
            </tr>
          </thead>
          <tbody>
          <?php 
          $count = 1;
          $class = '';
          foreach( $author_publish_posts as $auth_publish ) {
            $class = ( $count % 2 == 0 ) ? ' style="background-color:#4CC5E2"' : '';
          ?>
            <tr>
              <td><?php echo $count;?></td>
              <td><?php echo $auth_publish->ID;?></td>
              <td><?php echo $auth_publish->post_title;?></td>                
              <td><?php echo $auth_publish->post_date;?></td>
              <td><?php echo get_the_post_thumbnail( $auth_publish->ID, array(50,50) );?></td>
            </tr>
            <?php
              $count++;
            }
            ?>
          </tbody>
        </table>
        <div id="author_publish_posts" class="pager">
            <form>
                <img src="<?php echo WP_CUSTOM_PRODUCT_URL;?>/vendor_resource/image/pager/first.png" class="first"/>
                <img src="<?php echo WP_CUSTOM_PRODUCT_URL;?>/vendor_resource/image/pager/prev.png" class="prev"/>
                <input type="text" class="pagedisplay"/>
                <img src="<?php echo WP_CUSTOM_PRODUCT_URL;?>/vendor_resource/image/pager/next.png" class="next"/>
                <img src="<?php echo WP_CUSTOM_PRODUCT_URL;?>/vendor_resource/image/pager/last.png" class="last"/>
                <select class="pagesize">
                    <option value="1">1</option>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option selected="selected"  value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </form>
        </div>
      <?php
      } else { 
      ?>
      <div style="text-align:center; color:#F00; font-weight:bold; width:100%;">
        You have no product for published yet
      </div>
      <?php
      } 

        $total = $wpdb->get_var( "SELECT count(*) FROM wp_posts where post_author = ".get_current_user_id()." and post_status='publish'  ORDER BY ID DESC");
        $num_of_pages = ceil( $total / $limit );
        $page_links = paginate_links( array(
            'base' => add_query_arg( 'pagenum', '%#%' ),
            'format' => '',
            'prev_text' => __( '&laquo;', 'aag' ),
            'next_text' => __( '&raquo;', 'aag' ),
            'total' => $num_of_pages,
            'current' => $pagenum
          ));
    }
            
}

//--------
function wvs_product_pending(){
  global $wpdb;
  $limit =5;
  $pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
  $args_publish = array(
		  'author'     =>  get_current_user_id(),
		  'post_type'  => 'product',
		  'post_status'  => 'publish'
	  );	  
	   $author_publish_posts = get_posts( $args_publish );
  
  	  $args_pending = array(
        'author'     =>  get_current_user_id(),
        'post_type'  => 'product',
        'post_status'  => 'pending'
      );	  
	  $author_pending_posts = get_posts( $args_pending );
    
      if(!current_user_can('edit_theme_options')){ 
    ?>
    <script type="text/javascript">
      jQuery(document).ready(function(){ 
          jQuery("#uploadProducts_Pending").tablesorter({widthFixed: false, widgets: ['zebra']}); 
          jQuery("#uploadProducts_Pending").tablesorterPager({container: jQuery("#author_pending_posts")});			
        } 
      );
    </script>
    <h2>Pending Product</h2>
    <?php
     if( $author_pending_posts ) { 
     ?>             
      <table class="shop_table my_account_orders tablesorter" id="uploadProducts_Pending" style="width:100%;">
        <thead style="background-color:#DBDBDB; cursor:pointer;"> 
            <tr>
              <th>SN</th>
              <th>ID</th>
              <th>Title</th>
              <th>Date</th>
              <th>&nbsp;</th>	
            </tr>
          </thead>
        <tbody>
        <?php 
        $count = 1;
        $class = '';
        foreach( $author_pending_posts as $auth_pending ) {
          $class = ( $count % 2 == 0 ) ? ' style="background-color:#4CC5E2"' : '';
        ?>
          <tr>
            <td><?php echo $count;?></td>
            <td><?php echo $auth_pending->ID;?></td>
            <td><?php echo $auth_pending->post_title;?></td>                
            <td><?php echo $auth_pending->post_date;?></td>
            <td><?php echo get_the_post_thumbnail( $auth_pending->ID, array(50,50) );?></td>
          </tr>
        <?php
          $count++;
        }
        ?>

        </tbody>
      </table>
    
      <div id="author_pending_posts" class="pager">
        <form>
            <img src="<?php echo WP_CUSTOM_PRODUCT_URL;?>/vendor_resource/image/pager/first.png" class="first"/>
            <img src="<?php echo WP_CUSTOM_PRODUCT_URL;?>/vendor_resource/image/pager/prev.png" class="prev"/>
            <input type="text" class="pagedisplay"/>
            <img src="<?php echo WP_CUSTOM_PRODUCT_URL;?>/vendor_resource/image/pager/next.png" class="next"/>
            <img src="<?php echo WP_CUSTOM_PRODUCT_URL;?>/vendor_resource/image/pager/last.png" class="last"/>
            <select class="pagesize">
                <option value="1">1</option>
                <option value="5">5</option>
                <option value="10">10</option>
                <option selected="selected"  value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </form>
      </div>
      <?php
  }else{
    echo '<div style="text-align:center; color:#F00; font-weight:bold; width:100%;">You have no product for pending yet</div>';
  }


    $total = $wpdb->get_var( "SELECT count(*) FROM wp_posts where post_author = ".get_current_user_id()." and post_status='pending' ORDER BY ID DESC");
    $num_of_pages = ceil( $total / $limit );
    $page_links = paginate_links( array(
        'base' => add_query_arg( 'pagenum', '%#%' ),
        'format' => '',
        'prev_text' => __( '&laquo;', 'aag' ),
        'next_text' => __( '&raquo;', 'aag' ),
        'total' => $num_of_pages,
        'current' => $pagenum
      ));
   }//end current user check
         
 
            
}//end function