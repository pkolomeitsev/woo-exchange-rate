<?php

namespace WOOER;

if (!defined('ABSPATH')) {
    exit;
}

class Main {

    public function __construct() {
        // Load translations
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        // Register WP widget (Frontend)
        add_action('wp_enqueue_scripts', array($this, 'register_js'));
        add_action('wp_ajax_change_currency_action', array($this, 'change_currency_action'));
        add_action('wp_ajax_nopriv_change_currency_action', array($this, 'change_currency_action'));
        add_action('widgets_init', array($this, 'register_widgets'));
        // Init price manager with WooCommerce price hooks
        Price_Manager::init();
        // Init currency manager with WooCommerce currency hooks
        Currency_Manager::init();
        // Init admin panel manager with WooCommerce settings page hooks
        if (is_admin()) {
            AdminPanel_Manager::init();
        }
    }

    public function register_widgets() {
        register_widget('\\WOOER\\Currency_List_Widget');
    }

    public function register_js() {
        wp_enqueue_script('ajax-script', WOOER_PLUGIN_URL . 'assets/js/woo-exchange-rate.js', array('jquery'));
        // in JavaScript, object properties are accessed as woo-exchange-rate.ajax_url
        wp_localize_script('ajax-script', 'woo_exchange_rate', array('ajax_url' => admin_url('admin-ajax.php')));
    }

    /**
     * Ajax 'change_currency_action' action backend part
     */
    public function change_currency_action() {
        global $wp_widget_factory;

        //validate code
        $code = sanitize_text_field($_POST['currency_code']);

        //store in session new currency
        Currency_Manager::set_currency_code($code);

        //recalculate cart totals (refresh with new price)
        \WC()->cart->calculate_totals();

        //output JSON
        echo json_encode(array('currency_code' => $code));
        wp_die();
    }

    /**
     * Load plugin textdomain.
     */
    public function load_textdomain() {
        $file = 'woo-exchange-rate';
        load_plugin_textdomain('woo-exchange-rate', false, $file . '/languages/');
    }

}
