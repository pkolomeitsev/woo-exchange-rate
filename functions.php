<?php

use WOOER\Exchange_Rate_Model;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Custom functions, utils, etc.
 */
function wooer_currencies_list() {
    $currencies = get_woocommerce_currencies();
    asort($currencies);

    foreach ($currencies as $code => $name) {
        $currencies[$code] = $name . ' (' . get_woocommerce_currency_symbol($code) . ')';
    }

    return $currencies;
}

function wooer_startup_error() {
    $class = 'notice notice-error';
    $message = 'WooCommerce Exchange Rate plugin error: ' . __('WooCommerce plugin is not activated!', 'woo-exchange-rate');

    printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);
}

/**
 * Installation function
 */
function wooer_install() {
    global $wpdb;
    $table_name = Exchange_Rate_Model::get_instance()->get_table_name();

    $sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
	  `id` mediumint(9) AUTO_INCREMENT PRIMARY KEY,
	  `currency_code` varchar(3) NOT NULL,
	  `currency_exchange_rate` decimal(6,2) NOT NULL
	);";

    $wpdb->query($sql);
}

/**
 * Uninstall function
 */
function wooer_uninstall() {
    global $wpdb;
    $table_name = Exchange_Rate_Model::get_instance()->get_table_name();

    $sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);
}
