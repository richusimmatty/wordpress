<?php
// Display Fields
add_action( 'woocommerce_product_data_panels', 'wvs_woo_add_custom_general_fields' );
// Save Fields
add_action( 'save_post', 'wvs_woo_add_custom_general_fields_save' );

add_action( 'woocommerce_product_write_panel_tabs', 'wvs_woo_add_custom_admin_product_tab' );
 
function wvs_woo_add_custom_admin_product_tab() {
?>
<li class="custom_tab"><a href="#custom_tab_data"><?php _e(WC_PM.' Setup', 'woocommerce'); ?></a></li>
<?php
}

function wvs_woo_add_custom_general_fields(){  
  if( get_post_type()!='product'){
	  return false;
	}
	global $woocommerce, $post;
	$selected_vendor = get_post_meta(get_the_ID(), '_vendor_select',true);
	$selected_vendor_percentage = get_post_meta(get_the_ID(), '_vendor_percentage',true);
	$selected_vendor_note = get_post_meta(get_the_ID(), '_vendor_note',true);
	wp_reset_query();
	$args = array(
			'post_type'   => 'vendor_product',
			'post_status' => 'publish',
			'posts_per_page'=> -1
			);
	$posts = new WP_Query( $args );
	$posts = $posts->posts;
	/*echo '<pre>';
	print_r($posts);
	die('************');*/
  $option_arr = array();
	if(!empty($posts)){		
		foreach($posts as $pst){
			$vendor_list = get_post_meta($pst->ID, '_vendor_company',true);
			if($vendor_list!=''){        
				$option_arr[$pst->ID]=__( $vendor_list, 'woocommerce' );
			}
			else{
				$option_arr[$pst->ID]=__( $pst->post_title, 'woocommerce' );
			}
		}
	}
	if(empty($option_arr)){
    echo '<div class="error notice">You do not have any restaurant.Please create a restaurant before add a product.</div>';
  }else{
	echo '<div id="custom_tab_data" class="panel woocommerce_options_panel">';
	woocommerce_wp_select(
	array(
		'id' => '_vendor_select',
		'label' => __( 'Select '.WC_PM,'woocommerce-vendor-setup' ),
		'options' => $option_arr,
		'desc_tip' => 'true',
		'description' => __( 'Please Select '.WC_PM,'woocommerce-vendor-setup' )
		)
	);
	
	woocommerce_wp_text_input(
	array(
		'id' => '_vendor_percentage',
		'label' => __( WC_PM.' Percentage','woocommerce-vendor-setup' ),
		'placeholder' => __( 'Enter Percentage here','woocommerce-vendor-setup' ),
		'value' => $selected_vendor_percentage,
		'desc_tip' => 'true',
		'description' => __( 'Enter Percentage Amount Here.','woocommerce-vendor-setup' )
		)
	);
	
	woocommerce_wp_textarea_input(
	array(
		'id' => '_vendor_note',
		'label' => __( 'Note','woocommerce-vendor-setup' ),
		'placeholder' => '',
		'value' => $selected_vendor_note,
		'desc_tip' => 'true',
		'description' => __( 'Enter Note Here If You Have.','woocommerce-vendor-setup' )
		)
	);		
	echo '</div>';
  
  }
}

function wvs_woo_add_custom_general_fields_save( $post_id ){

  if( get_post_type()!='product'){
	  return false;
	}
 	// Select Vendor
	global $wpdb;
	
	// Vendor Percentage
	if(isset($_POST['_vendor_percentage'])){
	  $vendor_percentage = $_POST['_vendor_percentage'];
	  if( !empty( $vendor_percentage ) )
	  update_post_meta( $post_id, '_vendor_percentage', esc_attr( $vendor_percentage ) );
	}
	// Vendor Note
	if(isset($_POST['_vendor_note'])){
	  $vendor_note = $_POST['_vendor_note'];
	  if( !empty( $vendor_note ) )
	  update_post_meta( $post_id, '_vendor_note', esc_html( $vendor_note ) );
	}
	if(isset($_POST['_vendor_select'])){
	  $vendor_select = $_POST['_vendor_select'];
    $uid= get_post_meta($vendor_select, '_vendor_user_id', true);    
        
	  if( !empty( $vendor_select ) ){
      wp_update_post( $post_data );
	  	update_post_meta($post_id, '_vendor_select', esc_attr($vendor_select));		  
		  $user_meta_id=get_post_meta($vendor_select,'_vendor_user_id');
		  $sql="SELECT * FROM $wpdb->usermeta WHERE umeta_id = '$user_meta_id[0]'";      
		  $user=$wpdb->get_row($sql);
		  $arg = array('ID' => $post_id, 'post_author' => $user->user_id);
		  $table=$wpdb->prefix.'posts';		 
		  $wpdb->update( $table, array( 'post_author' => $uid), array('ID'=>$post_id));
		  //wp_update_post($arg);
	  }	  
	}		
}
//------------020
add_action( 'admin_init', 'wrs_restautant_list_metabox' );
function wrs_restautant_list_metabox(){
  add_meta_box( 'wrs_restautant_list_metabox_field','Restaurant List','wrs_restautant_list_panel','job_listing', 'normal', 'high');
}
function wrs_restautant_save($id){
  
  //get_option('wpmc_popup_active')
  if(isset($_POST['wrs_res_id'])){
    update_post_meta($id, 'wrs_res_id',$_POST['wrs_res_id']);
  }
  
}
function wrs_restautant_list_panel($pst){  
  //$post->ID;
  if( get_post_type()!='job_listing'){
	  return false;
	}
  $rid=  get_post_meta($pst->ID, 'wrs_res_id',true);  
  wp_reset_query();
	$args = array('post_type'  => 'vendor_product', 'post_status' => 'publish', 'posts_per_page'=> -1);
	$posts = new WP_Query( $args );
	$posts = $posts->posts;
  if(!empty($posts)){
    $option_arr = array();
    echo 'Select Restaurant: ';
    echo ' <select style="" name="wrs_res_id" id="wrs_res_id">';
    foreach($posts as $pst){
      $s='';
			$vendor_list = get_post_meta($pst->ID, '_vendor_company',true);
      $rp_id=$pst->ID;
      $rp_name=$pst->post_title;
			if($vendor_list!=''){				
        $rp_name=$vendor_list;
			}
      if ($rp_id==$rid){
        $s='selected="selected"';
      }
      //selected="selected"
      echo '<option value="'.$rp_id.'" '.$s.'>'.$rp_name.'</option>';
		}
    echo '</select>';
  }else {
    echo 'Sorry No Restaurant Found';  
  }
}