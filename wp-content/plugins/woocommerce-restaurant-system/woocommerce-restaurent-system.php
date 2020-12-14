<?php
/*
Plugin Name: WooCommerce Restaurant System
Plugin URI: http://www.solvercircle.com
Description: Restaurant food ordering and restaurant membership system.  
Version: 1.0.0
Author: SolverCircle
Author URI: http://www.solvercircle.com
Text Domain: woocommerce-vendor-setup
Domain Path: /i18n/languages/
*/
define('WP_CUSTOM_PRODUCT_URL', plugins_url('',__FILE__));
define('WP_CUSTOM_PRODUCT_PATH',plugin_dir_path( __FILE__ ));
define('WC_PM','Restaurant');
define('WC_PMS','restaurant');
$upload = wp_upload_dir();

//Upload Path
$get_upload_dir=$upload['basedir'].'/vendor_uploads/';
define('UPLOADS__BASE_PATH',$get_upload_dir);

//Upload URL
$get_upload_url=$upload['baseurl'].'/vendor_uploads/';
define('UPLOADS__BASE_URL',$get_upload_url);

include('vendor_core/vendor_core.php');
include('vendor_core/vendor_init.php');
include('vendor_admin/admin_init/vendor_product.php');

include('vendor_admin/admin_init/custom_product.php');
include('vendor_admin/admin_init/vendor_payment.php');
include('vendor_admin/admin_init/vendor_front_view_list.php');
include('vendor_admin/admin_init/vendor_orders.php');
include('vendor_admin/become_vendor/become_vendor.php');
include('vendor_frontend/vendor_frontend.php');
include('vendor_frontend/profile.php');


register_activation_hook(__FILE__, 'wvs_product_vendor_plugin_install');
register_deactivation_hook(__FILE__, 'wvs_product_vendor_plugin_uninstall');
//-------------

add_filter( 'post_row_actions', 'es_remove_row_actions', 10, 2 );

function es_remove_row_actions( $actions, $post )
{
 global $current_screen;
 if($current_screen->post_type != 'vendor_product') return $actions;
 //unset( $actions['edit'] );
 //unset( $actions['view'] );
 //unset($actions['trash']);
 unset($actions['inline hide-if-no-js']);
 //$actions['inline hide-if-no-js'] .= __( 'Quick&nbsp;Edit' );
 return $actions;
}
add_filter('bulk_actions-edit-vendor_product', '__return_empty_array');


//remove description from restaurant-------
function wrs_remove_post_type_support() {    
    remove_post_type_support( 'vendor_product', 'editor' );
}
add_action( 'init', 'wrs_remove_post_type_support' );

function wvs_create_vendor_approve_page() {
  add_submenu_page('edit.php?post_type=vendor_product', WC_PM . 'Approve', WC_PM . ' Approve', 'edit_theme_options', 'vendor_approve', 'wvs_approve_vendor_by_admin');
  add_submenu_page('edit.php?post_type=vendor_product', WC_PM . 'Payment Settings', 'Payment Settings', 'edit_theme_options', 'vendor_settings', 'wvs_vendor_global_settings');
  add_submenu_page('edit.php?post_type=vendor_product', WC_PM . 'Payout', 'Payout', 'edit_theme_options', 'vendor_orders', 'wvs_vendor_orders_details');
  
  add_submenu_page('edit.php?post_type=vendor_product', WC_PM . 'Statistics', 'Statistics', 'edit_theme_options', 'vendor_statistics', 'wvs_vendor_statistics_view_by_admin');
  add_submenu_page('edit.php?post_type=vendor_product', WC_PM . 'CSS Setting', 'CSS Setting', 'edit_theme_options', 'CSS Setting', 'wvs_restaurant_list_css_setting');
  //wfm-restaurent-shortcode.php
}
add_action('admin_menu', 'wvs_create_vendor_approve_page');
