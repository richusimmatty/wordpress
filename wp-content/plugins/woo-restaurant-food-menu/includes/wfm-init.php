<?php
function wfm_admin_menue(){
  $wfm_icon_url= WFM_BASE_URL . '/images/logo.jpg';
	add_menu_page('Food Menu', 'Food Menu', 'edit_theme_options', __FILE__, 'wfm_setting',$wfm_icon_url);
    add_submenu_page( __FILE__, 'Food Menu','Food Menu', 'edit_theme_options', __FILE__,'wfm_setting');  
}

function wfm_install(){
  wfm_setting_reset();
}
function wfm_uninstall(){}

add_action('admin_menu', 'wfm_admin_menue');