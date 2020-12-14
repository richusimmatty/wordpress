<?php

function wvs_product_vendor_plugin_install() {
  global $wpdb;
  global $plugin_custom_table_prefix;
  $plugin_custom_table_prefix = "woocommerce_";

  $table_name = $wpdb->prefix . $plugin_custom_table_prefix . "vendor";
  //die($table_name);
  $sql = "CREATE TABLE IF NOT EXISTS $table_name (
  `vendor_id` int(15) NOT NULL AUTO_INCREMENT,
  `vendor_vendor_id` int(25) DEFAULT NULL,
  `vendor_product_id` int(25) DEFAULT NULL,
  `vendor_product_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vendor_product_qty` int(25) DEFAULT NULL,
  `vendor_product_unit_price` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vendor_product_amount` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vendor_percent` int(25) DEFAULT NULL,
  `vendor_amount` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vendor_order_id` int(25) DEFAULT NULL,
  `vendor_order_date` date NOT NULL,
  `vendor_send_money_status` enum('Pending','Success','Fail') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Pending',
  `vendor_send_money_date` date DEFAULT NULL,
  `vendor_product_delivared` enum('Pending','Delivered') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`vendor_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=0;";
  $wpdb->query($sql);
}

function wvs_product_vendor_plugin_uninstall() {
  
}
