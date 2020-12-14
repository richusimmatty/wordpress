<?php


function vendor_set_email_content_type(){
	return "text/html";
}

function vendor_ob_start_function(){
  ob_start();
}

function vendor_session(){
    if(!session_id()) {
        session_start();
    }
}

function vendor_session_end(){
    session_destroy();
}

//--------i18n--------
function vendor_plugin_textdomain() {
  load_plugin_textdomain( 'woocommerce-vendor-setup', FALSE, basename( dirname( __FILE__ ) ) . '/i18n/languages/' );
}

function wvs_add_admin_additional_script(){
	wp_enqueue_script( 'jsapi', 'https://www.google.com/jsapi' );
	
	wp_enqueue_style('wqo-css',WP_CUSTOM_PRODUCT_URL.'/vendor_resource/css/blue/style.css');
	wp_enqueue_style('colorbox-css',WP_CUSTOM_PRODUCT_URL.'/vendor_resource/js/jquery.tablesorter.pager.css');  
  
  
  
  	wp_enqueue_script('jquery');
  	wp_enqueue_script('wcp-jscolor', WP_CUSTOM_PRODUCT_URL.'/vendor_resource/js/jquery.tablesorter.js');
  	wp_enqueue_script('wqo-tooltip', WP_CUSTOM_PRODUCT_URL.'/vendor_resource/js/jquery.tablesorter.pager.js');
    
	//wp_enqueue_script( 'jsapi.js', plugins_url( '/vendor_resource/js/jsapi.js', __FILE__ ) );
	wp_enqueue_media();
}

function add_frontend_additional_script(){
	//wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
  	//wp_enqueue_script( 'jquery' );
  	//wp_enqueue_script( 'jquery-ui-core' );
  	//wp_enqueue_script( 'jquery-ui-dialog' );
  	//wp_enqueue_script( 'jquery-ui-tabs' );
	//wp_enqueue_style( 'demo_style', WP_CUSTOM_PRODUCT_URL.'/vendor_resource/css/demo_style.css');
	//wp_enqueue_script( 'demo_script', WP_CUSTOM_PRODUCT_URL.'/vendor_resource/js/demo_script.js');
	wp_enqueue_style('horizontal_tabs', WP_CUSTOM_PRODUCT_URL.'/vendor_resource/css/horizontal_tabs.css');
	wp_enqueue_script( 'jsapi', 'https://www.google.com/jsapi' );
  wp_enqueue_style('wvs-easy-responsive-tabs',WP_CUSTOM_PRODUCT_URL.'/vendor_resource/css/easy-responsive-tabs.css');
	add_thickbox();
	
	wp_enqueue_style('wqo-css',WP_CUSTOM_PRODUCT_URL.'/vendor_resource/css/blue/style.css');
	wp_enqueue_style('colorbox-css',WP_CUSTOM_PRODUCT_URL.'/vendor_resource/js/jquery.tablesorter.pager.css');
  	wp_enqueue_script('wcp-jscolor', WP_CUSTOM_PRODUCT_URL.'/vendor_resource/js/jquery.tablesorter.js');
  	wp_enqueue_script('wqo-tooltip', WP_CUSTOM_PRODUCT_URL.'/vendor_resource/js/jquery.tablesorter.pager.js');
    wp_enqueue_script('wvs-easyResponsiveTabs', WP_CUSTOM_PRODUCT_URL.'/vendor_resource/js/easyResponsiveTabs.js');
	//wp_enqueue_script( 'jsapi.js', plugins_url( '/vendor_resource/js/jsapi.js', __FILE__ ) );
}

/*add_action( 'woo_main_before', 'woo_sidebar' );
function woo_sidebar() {
if (is_woocommerce()) { 
    echo '<div class="primary"> ABCD </div>';
}
}*/

/*function theme_slug_filter_the_content( $content ) {
    $custom_content = 'YOUR CONTENT GOES HERE';
    $custom_content .= $content;                        //**********************  partialy working *****************
    return $custom_content;
}
add_filter( 'the_content', 'theme_slug_filter_the_content' );*/
function wvs_third_party_tab_content(){
	//echo 'This is tab content';
	global $wpdb;
	global $post;
	//echo $post->ID;
	$vendor_id = get_post_meta( $post->ID, '_vendor_select', true );
	//echo "SELECT * FROM wp_postmeta where meta_value = '".$vendor_id."' and meta_key = '_vendor_select' ORDER BY post_id DESC";
	$ven_details = $wpdb->get_results("SELECT * FROM wp_postmeta where meta_value = '".$vendor_id."' and meta_key = '_vendor_select' ORDER BY post_id DESC");
	//die("SELECT * FROM wp_postmeta where meta_value = '".$vendor_id."' and meta_key = '_vendor_select' ORDER BY post_id DESC");
	//echo "SELECT * FROM wp_postmeta where meta_value = '".$vendor_id."' and meta_key = '_vendor_select' ORDER BY post_id DESC";
	//die();
	//echo '<pre>';
	//print_r($ven_details);
	/*if(!empty($ven_details)){
		foreach($ven_details as $van){
			//echo $van->post_id.'<br>';
			if ( (get_post_status ( $van->post_id ) == 'publish')&&($post->ID!=$van->post_id) ) {
			?>
			<a target="_blank" href="<?php echo get_permalink( $van->post_id);?>"><?php echo get_the_post_thumbnail( $van->post_id, array(100,100) );?></a>
			<?php
			
			}
		}
	}*/
	
	//*************************************************
	$vendor_name = get_post_meta( $vendor_id, '_vendor_name', true );
	$vendor_company = get_post_meta( $vendor_id, '_vendor_company', true );
	$vendor_email = get_post_meta( $vendor_id, '_vendor_email', true );
	
	$vendor_phone = get_post_meta( $vendor_id, '_vendor_phone', true );
	$vendor_fax = get_post_meta( $vendor_id, '_vendor_fax', true );
	$vendor_address = get_post_meta( $vendor_id, '_vendor_address', true );
	
	$vendor_zip = get_post_meta( $vendor_id, '_vendor_zip', true );
	$vendor_state = get_post_meta( $vendor_id, '_vendor_state', true );
	$vendor_country = get_post_meta( $vendor_id, '_vendor_country', true );
	$vendor_product_lbl = get_post_meta( $vendor_id, '_vendor_product_lbl', true );
	if($vendor_product_lbl==''){
		$vendor_product_lbl = "Other product's of this vendor";
	}
	
	?>
	<div class="wrap">
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
        <?php
        $v_address='';
		if(!empty($vendor_address)){
			$v_address=$vendor_address;
			if(!empty($vendor_state)){
				$v_address.=', '.$vendor_state;
			}
			if(!empty($vendor_country)){
				$v_address.=', '.$vendor_country;
			}
		
		}
		
		
		?>
        <tr>
            <th scope="row"><label>Address</label></th>
            <td><?php echo $v_address;?></td>
        </tr>
        <tr>
            <th scope="row" valign="top" style="vertical-align:top"><label>Company Logo-</label></th>
            <td><?php echo get_the_post_thumbnail( $vendor_id, array(100,100) );?></td>
        </tr>
        </tbody>
        </table>
    </div>
    <div style="width:100%; margin-bottom:5%;">
    	<h2><?php echo $vendor_product_lbl;?></h2>
    	<div style="width:100%;">
        	<?php
				$i=1;
            	if(!empty($ven_details)){
					foreach($ven_details as $van){
						//echo $van->post_id.'<br>';
						if ( (get_post_status ( $van->post_id ) == 'publish')&&($post->ID!=$van->post_id) ) {
						?>
                        
                        <!--<div style="width:20%; float:left; <? //if($i%4==0)echo 'margin-left:5%;'; ?>">-->
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
			?>
        	
            
			<!--<ul class="products">
			<li class="post-43 product type-product status-publish has-post-thumbnail first sale downloadable virtual shipping-taxable purchasable product-type-simple instock">

	
			<a href="http://localhost/vendor/?product=cartoon">
			<img width="150" height="150" alt="456" class="attachment-shop_catalog wp-post-image" src="http://localhost/vendor/wp-content/uploads/2015/03/4561-150x150.png">
			</a>
			</li>
            
            <li class="post-43 product type-product status-publish has-post-thumbnail first sale downloadable virtual shipping-taxable purchasable product-type-simple instock">

	
			<a href="http://localhost/vendor/?product=cartoon">
			<img width="150" height="150" alt="456" class="attachment-shop_catalog wp-post-image" src="http://localhost/vendor/wp-content/uploads/2015/03/4561-150x150.png">
			</a>
			</li>
				
			</ul>-->
			
		
	</div>
    
    
    
    
    </div>
    <div style="clear:both;">&nbsp;</div>
	<?php
	
	
	/*$myvals = get_post_meta($vendor_id);
	
	foreach($myvals as $key=>$val)
	{
		echo $key . ' : ' . $val[0] . '<br/>';
	}*/
}

function third_party_tab( $tabs ) {

//echo '<br>-->'.$vendor_id;
/*$some_check = $product ? third_party_check( $product->id ) : null;
if ( $product && ! $some_check ) {
return $tabs;
}*/
global $post;
//echo $post->ID;
$vendor_id = get_post_meta( $post->ID, '_vendor_select', true );
	$tabs['third_party_tab'] = array(
	'title' => __( WC_PM, 'wc_third_party' ),
	'priority' => 20,
	'callback' => 'wvs_third_party_tab_content'
	);
	return $tabs;
	}
add_filter( 'woocommerce_product_tabs', 'third_party_tab' ); 



/*function theme_slug_filter_the_title( $title ) {
    $custom_title = 'YOUR CONTENT GOES HERE';
    $title .= $custom_title;
    return $title;
}
add_filter( 'the_title', 'theme_slug_filter_the_title' );*/

/*add_filter( 'wp_title', 'mytest_add_sitename_to_title', 10, 2 );

function mytest_add_sitename_to_title( $title, $sep ) {
$name = get_bloginfo( 'name' );
$title .= 'ABCD';
return $title;
}*/
/*function wpr_after_post_title() {
echo "eeeeeee";
}
add_action('wpr_after_post_title','wpr_after_post_title');*/
/*function theme_post_end() {
echo '<div class="custom-text">Custom Text at the end of the post</div>';
};
add_action('themify_post_end', 'theme_post_end');*/
/*add_action('init', 'init_theme_method');
 
function init_theme_method() {
   add_thickbox();
}*/
add_filter('woocommerce_prevent_admin_access', '_wc_prevent_admin_access', 10, 1);
 
function _wc_prevent_admin_access($prevent_admin_access) { 
    return false;
}

add_action( 'plugins_loaded', 'vendor_plugin_textdomain' );
add_action('init', 'vendor_ob_start_function');
add_action('init', 'vendor_session', 1);
add_action('init', 'wvs_vendor_add_front_sub_menu_function');
add_action('wp_logout', 'vendor_session_end');
add_action('wp_login', 'vendor_session', 1);
add_filter( 'wp_mail_content_type','vendor_set_email_content_type' );
add_action( 'admin_enqueue_scripts', 'wvs_add_admin_additional_script' );
add_action( 'wp_enqueue_scripts', 'add_frontend_additional_script' );
