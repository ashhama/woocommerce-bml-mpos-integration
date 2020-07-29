<?php

//code to reduce stock when put to processing
function reduce_stock_processing($order_id) {
     wc_reduce_stock_levels($order_id);
}
add_action('woocommerce_order_status_processing', 'reduce_stock_processing');


//add logging function for Testing purposes
if (!function_exists('write_log')) {

    function write_log($log) {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }

}

 ?>
