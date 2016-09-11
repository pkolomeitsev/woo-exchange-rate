<?php

namespace WOOER;

if (!defined('ABSPATH')) {
    exit;
}

class AdminPanel_Manager {
    
    public static function init() {

        $self = new self();

        // Create the section beneath the products tab (Admin panel)
        add_filter('woocommerce_get_sections_' . Exchange_Rate_Settings_Page::TAB, array($self, 'setup_sections'));
        add_filter('woocommerce_get_settings_' . Exchange_Rate_Settings_Page::TAB, array($self, 'setup_settings'), 10, 2);

        // Create the section beneath the reports tab (Admin panel)
//        add_filter('woocommerce_get_sections_orders', array($this, 'get_section_reports'));
//        add_filter('woocommerce_get_settings_orders', array($this, 'get_settings_reports'), 10, 2);
        // WooCommerce Report tab improvements
        add_filter('woocommerce_reports_get_order_report_data_args', array($self, 'reports_get_order_report_data_args'));
    }

    public function setup_sections($sections) {
        $sections[Exchange_Rate_Settings_Page::SECTION] = __('Exchange Rates', 'woocommerce-exchange-rate');
        return $sections;
    }

    public function setup_settings($settings, $current_section = '') {
        
        // Check the current section is what we want
        if ($current_section == Exchange_Rate_Settings_Page::SECTION) {
            $wooer_settings = new Exchange_Rate_Settings_Page();
            $wooer_settings->page_output();
            //clean-up settings page fields
            return array();
        }

        //return standart settings
        return $settings;
    }

//    public function get_section_reports() {
//        $sections[Exchange_Rate_Settings_Page::SECTION] = __('Exchange rates', 'woocommerce-exchange-rate');
//        var_dump($sections);
//        return $sections;
//    }
//
//    public function get_settings_reports($settings, $current_section) {
//
//        //return standart settings
//        return $settings;
//    }

    /**
     * Filtering reports by selected currency
     * @param array $args
     * @return string
     */
    public function reports_get_order_report_data_args($args) {
        $currency = Currency_Manager::get_currency_code();
        $args['where']['_order_currency'] = [
            'type' => 'meta',
            'key' => 'meta__order_currency.meta_value',
            'value' => $currency,
            'operator' => '='
        ];
        //$args['debug'] = true;
        return $args;
    }

}
