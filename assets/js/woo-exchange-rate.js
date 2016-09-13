jQuery(function($) {

    var url = window.location.href;
    var currency_code = $('#currency_code').val();
    var data = {
        'action': 'change_currency_action',
        'currency_code': currency_code
    };
    
    //global wc_cart_fragments_params
    var storage_key = wc_cart_fragments_params.fragment_name;

    $('#currency_code').change(function () {
        data.currency_code = $('#currency_code').val();
        // woo_exchange_rate - global plugin object, defined in frontend header 
        jQuery.post(woo_exchange_rate.ajax_url, data, function (response) {
            console.log('WC exchange rate plugin log: ' + response);
            //refresh mini cart
            window.sessionStorage.removeItem(storage_key);
            //redirect
            window.location.href = url;
        });
    });
});
