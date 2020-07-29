<?php

//Add print receipt to final pages
function isa_woo_thankyou()
{

    $payment_gateway = WC()
        ->payment_gateways
        ->payment_gateways() ['woocommerce_bml_mpos_integration'];

    $showPrintReceipt = ($payment_gateway->print_receipt_button == "yes") ? 'TRUE' : 'FALSE';

    if ($showPrintReceipt) echo '<a href="javascript:window.print()" id="wc-print-button">Print receipt</a>';

}
add_action('woocommerce_thankyou', 'isa_woo_thankyou', 1);
add_action('woocommerce_view_order', 'isa_woo_thankyou', 8);

function isa_get_wc_print_receipt_link()
{

    return '<a href="javascript:window.print()" id="wc-print-button">Print receipt</a><br><br>';
}

/**
 * Add "Print receipt" link to WooCommerce View Order page
 */
function isa_woo_view_order_print_receipt()
{
    $payment_gateway = WC()
        ->payment_gateways
        ->payment_gateways() ['woocommerce_bml_mpos_integration'];

    $showPrintReceipt = ($payment_gateway->print_receipt_button == "yes") ? 'TRUE' : 'FALSE';

    if ($showPrintReceipt) echo isa_get_wc_print_receipt_link();
}
add_action('woocommerce_view_order', 'isa_woo_view_order_print_receipt', 8);

/**
 * Add "Print receipt" link to WooCommerce Order Received page TOP
 */
function isa_woo_order_print_receipt_top($text, $order)
{

    global $woocommerce;

    $payment_gateway = $woocommerce
        ->payment_gateways
        ->payment_gateways() ['woocommerce_bml_mpos_integration'];

    $showPrintReceipt = ($payment_gateway->print_receipt_button == "yes") ? 'TRUE' : 'FALSE';

    $out = isa_get_wc_print_receipt_link();

    if ($showPrintReceipt) return $out . ' ' . $text;
    else return '';
}

add_filter('woocommerce_thankyou_order_received_text', 'isa_woo_order_print_receipt_top', 999, 2);

?>
