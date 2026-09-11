<?php
/** Checkout Blocks adapter; the existing gateway remains the payment processor. */
defined('ABSPATH') || exit;

add_action('before_woocommerce_init', function () {
    if (class_exists('Automattic\\WooCommerce\\Utilities\\FeaturesUtil')) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
            'cart_checkout_blocks', BML_MPOS_PLUGIN_FILE, true
        );
    }
});

add_action('woocommerce_blocks_loaded', function () {
    if (!class_exists('Automattic\\WooCommerce\\Blocks\\Payments\\Integrations\\AbstractPaymentMethodType')) {
        return;
    }
    require_once __DIR__ . '/class-bml-blocks-payment-method.php';
    add_action('woocommerce_blocks_payment_method_type_registration', function ($registry) {
        $registry->register(new BML_MPOS_Blocks_Payment_Method());
    });
});

add_action('wp_enqueue_scripts', function () {
    if (function_exists('is_checkout') && is_checkout()) {
        wp_enqueue_style('bml-mpos-payment', plugins_url('assets/css/payment.css', BML_MPOS_PLUGIN_FILE), array(), BML_MPOS_VERSION);
    }
});
