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
     * For some currecies it is better to use word instead of symbol 
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
     * Update customer checkout page
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
    
    /**
     * Returns currencies list (code->name)
     * @return array
     */
    public static function wooer_currencies_list()
    {
        $currencies = get_woocommerce_currencies();
        asort($currencies);

        foreach ($currencies as $code => $name) {
            $currencies[$code] = $name . ' (' . get_woocommerce_currency_symbol($code) . ')';
        }

        return $currencies;
    }

    /**
     * 
     * @return array
     */
    public static function wooer_currency_pos_list($currency_symbol = '')
    {
        $currency_symbol = $currency_symbol ?: get_woocommerce_currency_symbol();
        return [
            'left' => __('Left', 'woocommerce') . ' (' . $currency_symbol . '99.99)',
            'right' => __('Right', 'woocommerce') . ' (99.99' . $currency_symbol . ')',
            'left_space' => __('Left with space', 'woocommerce') . ' (' . $currency_symbol . ' 99.99)',
            'right_space' => __('Right with space', 'woocommerce') . ' (99.99 ' . $currency_symbol . ')'
        ];
    }
}
