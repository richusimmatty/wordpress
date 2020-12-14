<?php
/*
Plugin Name: WooCommerce Restaurant Food Menu
Plugin URI: http://www.solvercircle.com
Description: This is a WooCommerce plugin that list all the products on one page and allow users to quickly order products.
Version: 1.0
Author: SolverCircle
Author URI: http://www.solvercircle.com
*/

define("WFM_BASE_URL", WP_PLUGIN_URL.'/'.plugin_basename(dirname(__FILE__)));

include ('includes/wfm-admin.php');
include ('includes/wfm-view.php');

include ('includes/wfm-restaurent.php');
include ('includes/wfm-restaurent-shortcode.php');
include ('includes/wfm-init.php');

function wfm_init(){
  wp_enqueue_style('wfm-css',WFM_BASE_URL.'/css/wfm.css');
  wp_enqueue_style('colorbox-css',WFM_BASE_URL.'/css/colorbox.css'); 
  wp_enqueue_style('ddaccordion-css',WFM_BASE_URL.'/css/ddaccordion.css'); 
  wp_enqueue_style('wfm-magnific-popup-css',WFM_BASE_URL.'/magnific-popup/magnific-popup.css'); 
  
  wp_enqueue_script('jquery');
  wp_enqueue_script('wfm-jscolor', plugins_url( '/js/colorpicker/jscolor.js', __FILE__ ));
  wp_enqueue_script('wfm-tooltip', plugins_url( '/js/wfm_tooltip.js', __FILE__ ));    
  wp_enqueue_script('jquery.colorbox', plugins_url( '/js/jquery.colorbox.js', __FILE__ ));
  
  wp_enqueue_script('wfm-ddaccordion', plugins_url( '/js/ddaccordion.js', __FILE__ ));
  wp_enqueue_script('wfm-ddaccordion2', plugins_url( '/js/ddaccordion2.js', __FILE__ ));
  
  wp_enqueue_script('wfm-magnific-popup', plugins_url( '/magnific-popup/jquery.magnific-popup.js', __FILE__ ));
  wp_enqueue_script('wrfm-js', plugins_url( '/js/wrfm.js', __FILE__ ));
}

add_action('init','wfm_init');
register_activation_hook( __FILE__, 'wfm_install');
register_deactivation_hook( __FILE__, 'wfm_uninstall');