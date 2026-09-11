<?php
defined('ABSPATH') || exit;

/** Public presentation data only: never send the gateway settings to the browser. */
final class BML_MPOS_Blocks_Payment_Method extends \Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType
{
    protected $name = 'woocommerce_bml_mpos_integration';
    private $gateway;

    public function initialize()
    {
        $gateways = WC()->payment_gateways()->payment_gateways();
        $this->gateway = isset($gateways[$this->name]) ? $gateways[$this->name] : null;
    }

    public function is_active()
    {
        return $this->gateway && $this->gateway->is_available();
    }

    public function get_payment_method_script_handles()
    {
        wp_register_script(
            'bml-mpos-blocks',
            plugins_url('assets/js/blocks.js', BML_MPOS_PLUGIN_FILE),
            array('wc-blocks-registry', 'wc-settings', 'wp-element', 'wp-html-entities', 'wp-i18n'),
            BML_MPOS_VERSION,
            true
        );
        return array('bml-mpos-blocks');
    }

    public function get_payment_method_script_handles_for_admin()
    {
        return $this->get_payment_method_script_handles();
    }

    public function get_payment_method_data()
    {
        if (!$this->gateway) {
            return array();
        }
        return array(
            'title' => wp_strip_all_tags($this->gateway->get_title()),
            'description' => wp_strip_all_tags($this->gateway->get_description()),
            'icon' => esc_url_raw($this->gateway->icon),
            'isActive' => (bool) $this->is_active(),
            'supports' => array_values(array_filter($this->gateway->supports, array($this->gateway, 'supports'))),
        );
    }
}
