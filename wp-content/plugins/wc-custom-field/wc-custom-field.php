<?php
/*
Plugin Name: WooCommerce Custom Field
Plugin URI: http://www.solvercircle.com
Description: This is a WordPress WooCommerce plugin which allow user to create dynamically custom field for each WooCommerce Product.
Version: 1.0
Author: SolverCircle
Author URI: http://www.solvercircle.com.
*/

define("WCF_BASE_URL", WP_PLUGIN_URL.'/'.plugin_basename(dirname(__FILE__)));

include ('includes/wcf-admin.php');
include ('includes/wcf-admin2.php');
include ('includes/wcf-view.php');
include ('includes/wcf-view2.php');
include ('includes/wcf-settings.php');
include ('includes/wcf-init.php');

function wpr_init(){
  wp_enqueue_style('wcf-normalize',WCF_BASE_URL.'/css/normalize.css');
  wp_enqueue_style('wcf-style',WCF_BASE_URL.'/css/style.css');
  wp_enqueue_style('wcf-colorbox-css',WCF_BASE_URL.'/css/colorbox.css'); 
  wp_enqueue_style('wcf-ddaccordion-css',WCF_BASE_URL.'/css/ddaccordion.css');
  
  wp_enqueue_script('jquery');
  wp_enqueue_script( 'wcf-modernizr', plugins_url( '/js/modernizr.js', __FILE__ ));
  wp_enqueue_script( 'wcf-js', plugins_url( '/js/wcf.js', __FILE__ ));
  wp_enqueue_script( 'wcf-jscolor', plugins_url( '/js/colorpicker/jscolor.js', __FILE__ ));
  wp_enqueue_script('wcf-ddaccordion', plugins_url( '/js/ddaccordion.js', __FILE__ ));
}
add_action('init','wpr_init');
register_activation_hook( __FILE__, 'wpr_install');
register_deactivation_hook( __FILE__, 'wpr_uninstall');
?>