<?php

namespace WOOER;

if (!defined('ABSPATH')) {
    exit;
}

class Currency_Manager {

    const SESSION_KEY = 'currency_code';

    public static function init() {

        if (!session_id()) {
            session_start();
        }
        
        $self = new self();

        add_filter('woocommerce_currency_symbol', array($self, 'change_currency_symbol'), 10, 2);
        add_action('woocommerce_checkout_update_order_meta', array($self, 'checkout_update_order_meta'), 10, 2);
    }

    /**
     * 
     * @param string $currency_symbol
     * @param string $currency
     * @return string
     */
    public function change_currency_symbol($currency_symbol, $currency) {
        switch ($currency) {
            case 'UAH':
                return 'грн.';
            case 'RUB':
                return 'руб.';
        }
        return $currency_symbol;
    }

    /**
     * 
     * @param int $order_id
     * @param array $posted Array of posted form data
     */
    public function checkout_update_order_meta($order_id, $posted) {
        update_post_meta($order_id, '_order_currency', self::get_currency_code());
    }

    /**
     * Get currency code from session
     * @return string
     */
    public static function get_currency_code() {
        return isset($_SESSION[self::SESSION_KEY]) ? $_SESSION[self::SESSION_KEY] : get_woocommerce_currency();
    }

    /**
     * Set currency code in session
     * @param string $code
     */
    public static function set_currency_code($code) {
        $_SESSION[self::SESSION_KEY] = $code;
    }

}
