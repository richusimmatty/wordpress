<?php
function wvs_PPHttpPost($methodName_, $nvpStr_) {
	global $environment;
	
	$vendor_paypal_username = get_option( 'masspay_user_name' );
	$vendor_paypal_password = get_option( 'masspay_api_pass' );
	$vendor_paypal_signature = get_option( 'masspay_api_signature' );
	$vendor_paypal_api_mode = get_option( 'masspay_api_mode' );
	$API_Endpoint = '';
	// Set up your API credentials, PayPal end point, and API version.
	
	
	/*$API_UserName = urlencode('halen_aktar_api1.yahoo.com');
	$API_Password = urlencode('1366977236');
	$API_Signature = urlencode('AlSaw1PgqsXY.BqrM75oUiOFYjzaAaFOQ8cG4-iWEt5TVpn5Q14K0f2q');
	$API_Endpoint = "https://api-3t.paypal.com/nvp";	
	$API_Endpoint = "https://api-3t.sandbox.paypal.com/nvp";
	$version = urlencode('51.0');*/
	
	$API_UserName = urlencode($vendor_paypal_username);
	$API_Password = urlencode($vendor_paypal_password);
	$API_Signature = urlencode($vendor_paypal_signature);
	if($vendor_paypal_api_mode == 'sandbox'){
		$API_Endpoint = "https://api-3t.sandbox.paypal.com/nvp";	
	}
	else{
		$API_Endpoint = "https://api-3t.paypal.com/nvp";
	}
		
	
	$version = urlencode('51.0');
	
	
	/*if("sandbox" === $environment || "beta-sandbox" === $environment) {
		$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
	}*/

	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	// Set the API operation, version, and API signature in the request.
	$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	// Get response from the server.
	$httpResponse = curl_exec($ch);

	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}

	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}

	return $httpParsedResponseAr;
}

function wvs_view_vendor_details(){
	global $wpdb;
	if(isset($_GET['vendor_id'])){
	$vendor_id = $_GET['vendor_id'];
	  //-------------------  Vendor Info ----------------------
	$vendor_name=get_post_meta($_GET['vendor_id'],'_vendor_name',true);
	$vendor_company=get_post_meta($_GET['vendor_id'],'_vendor_company',true);
  	$vendor_email=get_post_meta($_GET['vendor_id'],'_vendor_email',true);
  	$vendor_phone=get_post_meta($_GET['vendor_id'],'_vendor_phone',true);
	$vendor_fax=get_post_meta($_GET['vendor_id'],'_vendor_fax',true);
	
	$vendor_address=get_post_meta($_GET['vendor_id'],'_vendor_address',true);
	$vendor_zip=get_post_meta($_GET['vendor_id'],'_vendor_zip',true);
  	$vendor_state=get_post_meta($_GET['vendor_id'],'_vendor_state',true);
  	$vendor_country=get_post_meta($_GET['vendor_id'],'_vendor_country',true);
	$vendor_paypal=get_post_meta($_GET['vendor_id'],'_vendor_paypal',true);
	  //-------------------------------------------------------
	  
	  $custom_table_prefix = 'woocommerce_';
	  $pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
	  $limit =5;
	  $offset = ( $pagenum - 1 ) * $limit;
	  $wpdb_all_prefix = $wpdb->prefix.$custom_table_prefix;
	  //$vendor_records = $wpdb->get_results("SELECT * FROM {$wpdb_all_prefix}vendor where vendor_vendor_id = ".$_GET['vendor_id']." and vendor_send_money_status = 'Pending' ORDER BY vendor_id DESC LIMIT $offset, $limit" );
	  $vendor_records = $wpdb->get_results("SELECT * FROM {$wpdb_all_prefix}vendor where vendor_vendor_id = ".$_GET['vendor_id']." and vendor_send_money_status = 'Pending' ORDER BY vendor_id DESC" );
	  //echo '<pre>';
	  //print_r($vendor_records);
	  //die();
	  ?>
	  <div class="wrap">
      	<script type="text/javascript">
			jQuery(document).ready(function(){ 
					jQuery("#myTable").tablesorter({widthFixed: false, widgets: ['zebra']}); 
					jQuery("#myTable").tablesorterPager({container: jQuery("#pager")});			
				} 
			);
		</script>
		<h2><?php _e("Order Record of","woocommerce-vendor-setup"); ?>&nbsp;<font style="color:#090; font-weight:bold;"><?php echo $vendor_name;?></font></h2>
        <?php
         if( $vendor_records ) { 
		  
		?>
		<table style="width:100%;" id="myTable" class="tablesorter">
		  <thead style="background-color:#DBDBDB; cursor:pointer;">
			<tr>
			  <th scope="col" class="manage-column column-name" style=""><?php _e("Product","woocommerce-vendor-setup"); ?></th>
              <th scope="col" class="manage-column column-name" style=""><?php _e("Qty","woocommerce-vendor-setup"); ?></th>
			  <th scope="col" class="manage-column column-name" style=""><?php _e("Amount","woocommerce-vendor-setup"); ?></th>			  
			  <th scope="col" class="manage-column column-name" style=""><?php _e("Purchase Date","woocommerce-vendor-setup"); ?></th>
              <th scope="col" class="manage-column column-name" style=""><?php _e("Status","woocommerce-vendor-setup"); ?></th>
              <th scope="col" class="manage-column column-name" style=""><?php _e("Pay Date","woocommerce-vendor-setup"); ?></th>
			</tr>
		  </thead>
		  <tbody>
		  <?php
		  $count = 1;
		  $class = '';
		  foreach( $vendor_records as $ven_record ) {
			$class = ( $count % 2 == 0 ) ? ' style="background-color:#4CC5E2"' : '';
		  ?>
			<tr<?php //echo $class; ?>>
			  <td><?php echo $ven_record->vendor_product_name;?></td>
			  <td><?php echo $ven_record->vendor_product_qty;?></td>
			  <td><?php echo $ven_record->vendor_product_amount;?></td>
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
        <div id="pager" class="pager">
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
		}
		$total = $wpdb->get_var( "SELECT count(*) FROM {$wpdb_all_prefix}vendor where vendor_vendor_id = ".$_GET['vendor_id']." and vendor_send_money_status = 'Pending' ORDER BY vendor_id DESC");
		$num_of_pages = ceil( $total / $limit );
		$page_links = paginate_links( array(
			'base' => add_query_arg( 'pagenum', '%#%' ),
			'format' => '',
			'prev_text' => __( '&laquo;', 'aag' ),
			'next_text' => __( '&raquo;', 'aag' ),
			'total' => $num_of_pages,
			'current' => $pagenum
		  ));
		/*if ( $page_links ) {
		  echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
		}*/
		echo '<div style="text-align:left;"><a class="button button-primary button-large" href="admin.php?page=vendor_details&v_id='.$vendor_id.'">'.__("Pay now","woocommerce-vendor-setup").'</a></div>';
	  ?>
      
      </div>
	  <?php
	}
	elseif(isset($_GET['v_id'])){
			//die($_GET['v_id']);
			$vendor_id = $_GET['v_id'];
			$custom_table_prefix = 'woocommerce_';
			$wpdb_all_prefix = $wpdb->prefix.$custom_table_prefix;
			//echo 'This is working... '.$_GET['v_id'];
			$vendor_name=get_post_meta($vendor_id,'_vendor_name',true);
			$vendor_company=get_post_meta($vendor_id,'_vendor_company',true);
			$vendor_email=get_post_meta($vendor_id,'_vendor_email',true);
			$vendor_phone=get_post_meta($vendor_id,'_vendor_phone',true);
			$vendor_fax=get_post_meta($vendor_id,'_vendor_fax',true);
			
			$vendor_address=get_post_meta($vendor_id,'_vendor_address',true);
			$vendor_zip=get_post_meta($vendor_id,'_vendor_zip',true);
			$vendor_state=get_post_meta($vendor_id,'_vendor_state',true);
			$vendor_country=get_post_meta($vendor_id,'_vendor_country',true);
			$vendor_paypal=get_post_meta($vendor_id,'_vendor_paypal',true);
			$vendor_records = $wpdb->get_results("SELECT * FROM {$wpdb_all_prefix}vendor where vendor_vendor_id = ".$vendor_id." and vendor_send_money_status = 'Pending' ORDER BY vendor_id " );
			
			//echo '<pre>';
			//print_r($vendor_records);
						
			/*$environment = 'sandbox';
			$emailSubject =urlencode('example_email_subject');
			$receiverType = urlencode('john.austin271@gmail.com');
			$currency = urlencode('USD');*/							// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
			$vendor_paypal_api_mode = get_option( 'masspay_api_mode' );
			$environment = $vendor_paypal_api_mode;
			$emailSubject =urlencode('Your Payment Details');
			$receiverType = urlencode($vendor_paypal);
			$currency = urlencode('USD');
			
			// Add request-specific fields to the request string.
			$nvpStr="&EMAILSUBJECT=$emailSubject&RECEIVERTYPE=$receiverType&CURRENCYCODE=$currency";
			
			$receiversArray = array();
			$receiversmail = array();
			$i=0;
			if( $vendor_records ) {
				foreach( $vendor_records as $ven_record ) {
					$receiversmail[$i]['email'] = $vendor_email;
					$receiversmail[$i]['amount'] = $ven_record->vendor_amount;
					$receiversmail[$i]['uniqueID'] = $ven_record->vendor_id;
					$receiversmail[$i]['notes'] = "Hi, ".$vendor_name." <br>You get ".$ven_record->vendor_amount." for product ".$ven_record->vendor_product_name." order date ".$ven_record->vendor_order_date.".";
					$i++;
				}
			}
			for($i = 0; $i < count($receiversmail); $i++) {
				$receiverData = array(	'receiverEmail' => $receiversmail[$i]['email'],
										'amount' => $receiversmail[$i]['amount'],
										'uniqueID' => $receiversmail[$i]['uniqueID'],
										'note' => $receiversmail[$i]['notes']);
				$receiversArray[$i] = $receiverData;
			}
			//-----------------------------------------------------------------------------------------
			$temp_sales_pay_list = array();
			
			$i = 0;                 
			foreach($receiversArray as $pay_list){
				$temp_sales_pay_list[$i] = $pay_list;
				$i++;
			}
			
			$i = 0;
			$interval = 10000;
			for($j=0;$j<count($temp_sales_pay_list);$j++){
				if($temp_sales_pay_list[$j]['amount']>$interval){
					$cnt = ceil($temp_sales_pay_list[$j]['amount']/$interval);
					$total_pay = $temp_sales_pay_list[$j]['amount'];
					for($m=0;$m<$cnt;$m++){
					
						if($m==($cnt-1)){
							$total_pay_amount = $total_pay-($interval*($cnt-1));
						}
						else{
							$total_pay_amount = $interval;
						}
						
						$receiversArray[$i]['receiverEmail'] 	= $temp_sales_pay_list[$j]['receiverEmail'];
						$receiversArray[$i]['amount'] 			= $total_pay_amount;
						$receiversArray[$i]['uniqueID'] 		= $temp_sales_pay_list[$j]['uniqueID'] ;
						$receiversArray[$i]['note'] 			= $temp_sales_pay_list[$j]['note'] ;
						$i++;
					}
				}
				else{
					$receiversArray[$i]['receiverEmail'] 	= $temp_sales_pay_list[$j]['receiverEmail'];
					$receiversArray[$i]['amount'] 			= $temp_sales_pay_list[$j]['amount'];
					$receiversArray[$i]['uniqueID'] 		= $temp_sales_pay_list[$j]['uniqueID'];
					$receiversArray[$i]['note'] 			= $temp_sales_pay_list[$j]['note'];
					$i++;
				}
			}
			/*echo '<pre>';
			print_r($receiversArray);
			die();*/
			//-----------------------------------------------------------------------------------------
			
			foreach($receiversArray as $i => $receiverData) {
				$receiverEmail = urlencode($receiverData['receiverEmail']);
				$amount = urlencode($receiverData['amount']);
				$uniqueID = urlencode($receiverData['uniqueID']);
				$note = urlencode($receiverData['note']);
				$nvpStr .= "&L_EMAIL$i=$receiverEmail&L_Amt$i=$amount&L_UNIQUEID$i=$uniqueID&L_NOTE$i=$note";
			}
			
			// Execute the API operation; see the PPHttpPost function above.
			//die($nvpStr);
			$httpParsedResponseAr = wvs_PPHttpPost('MassPay', $nvpStr);
			
			if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
				//$wpdb->update('scpd_order',array('order_status'=>$received_order_status_id),array('order_id'=>$_GET['v_id']));
				foreach( $vendor_records as $ven_record ) {
					$wpdb->update($wpdb_all_prefix.'vendor',array('vendor_send_money_status'=>'Success','vendor_send_money_date'=>date("Y-m-d")),array('vendor_product_id'=>$ven_record->vendor_product_id));
				}
				wp_redirect( admin_url( '/admin.php?page=vendor_details&vendor_id='.$_GET['v_id'].'') );
				exit('<div style="width:100%; font-size:14px; color:#060; text-align:center">Payment Completed Successfully. Please check your account.</div>');
				?>
                
				<!--<p><a class="button" href="<?php //echo get_permalink(woocommerce_get_page_id('shop')); ?>"><?php //_e( 'â†? Return To Shop', 'woocommerce' ) ?></a></p>-->
				<?php
			} else  {
				wp_redirect( admin_url( '/admin.php?page=vendor_details&vendor_id='.$_GET['v_id'].'') );
				exit('<div style="width:100%; font-size:14px; color:#060; text-align:center">Your Payment Failed. Please Try Again.</div>');
				
				//exit('MassPay failed: ' . print_r($httpParsedResponseAr, true));
			}
			
		}
		else{
			wp_redirect( site_url( '/?page=vendor_details&vendor_id='.$_GET['v_id'].'') );
    		//exit;
			exit('<div style="width:100%; font-size:14px; color:#060; text-align:center">No Record Found For Payment</div>');
		}		
	
}



function wvs_create_vendor_page() {
	 //add_submenu_page('edit.php?post_type=vendor_product', 'Vendor Details', '<font style="display:none;">Pay Vendor</font>', 'edit_theme_options', 'vendor_details', 'view_vendor_details');
	 add_submenu_page('null', WC_PM.' Details', '<font style="display:none;">Pay '.WC_PM.'</font>', 'edit_theme_options', 'vendor_details', 'wvs_view_vendor_details');
}

add_action('admin_menu', 'wvs_create_vendor_page');
/*
function edit_admin_menus() {
    global $menu;
	echo '<pre>';
	print_r($menu);
}*/
//add_action( 'admin_menu', 'edit_admin_menus' );