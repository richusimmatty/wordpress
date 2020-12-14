<?php
function wpr_admin_menu(){
  $icon_url= WCF_BASE_URL . '/images/logo.png';
	add_menu_page('WC Custom Field', 'WC Custom Field', 'edit_theme_options', __FILE__, '',$icon_url);
	add_submenu_page( __FILE__, 'Settings','Settings', 'edit_theme_options', __FILE__,'wcf_tab_design' );
}
function wpr_install(){}
function wpr_uninstall(){}
add_action('admin_menu', 'wpr_admin_menu');
?>