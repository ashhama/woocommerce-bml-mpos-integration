<?php
/**
 * Method which initializes the payment gateway
 * @param void no input
 * @return void no output
 */
function woocommerce_bml_mpos_integration_init()
{
    //Do not load the gateway if woocommerce is not installed
    if (!class_exists('WC_Payment_Gateway')) return;
    include_once ('wc-bml-mpos-gateway-class.php');
    // Add the classes to woocommerce
    add_filter('woocommerce_payment_gateways', 'add_woocommerce_bml_mpos_integration');
    function add_woocommerce_bml_mpos_integration($methods)
    {
        $methods[] = 'WOOCOMMERCE_BML_MPOS_INTEGRATION';
        return $methods;
    }
}
add_action('plugins_loaded', 'woocommerce_bml_mpos_integration_init', 0);


/**
 * Add the custom action links to show in the plugins section
 * @param array array of the core links of the plugin
 * @return array array of core links + added links
 */
function woocommerce_bml_mpos_integration_action_links($links)
{

    $plugin_links = array(
        '<a href="' . admin_url('admin.php?page=wc-settings&tab=checkout') . '">' . __('Settings', 'woocommerce_bml_mpos_integration') . '</a>',
    );
    return array_merge($plugin_links, $links);
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__) , 'woocommerce_bml_mpos_integration_action_links');


?>
