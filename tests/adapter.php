<?php
/* Isolated adapter contract test, not a WooCommerce/BML transaction test. */
namespace Automattic\WooCommerce\Blocks\Payments\Integrations {
    abstract class AbstractPaymentMethodType {
        protected $name = '';
        public function get_name() { return $this->name; }
        abstract public function initialize();
    }
}
namespace Automattic\WooCommerce\Utilities {
    class FeaturesUtil {
        public static $declarations = array();
        public static function declare_compatibility($feature, $file, $compatible) {
            self::$declarations[] = array($feature, $file, $compatible);
        }
    }
}
namespace {
    define('ABSPATH', '/');
    define('BML_MPOS_PLUGIN_FILE', dirname(__DIR__) . '/wc-bml-mpos-gateway.php');
    define('BML_MPOS_VERSION', '1.1.0');
    function check($ok, $message) { if (!$ok) { throw new \Exception($message); } }
    $hooks = array();
    function add_action($hook, $callback) { $GLOBALS['hooks'][$hook][] = $callback; }
    function fire($hook, $arg = null) { foreach ($GLOBALS['hooks'][$hook] ?? array() as $callback) { $callback($arg); } }
    function wp_strip_all_tags($s) { return strip_tags($s); }
    function esc_url_raw($s) { return $s; }
    function plugins_url($path, $file) { return 'https://example.test/plugin/' . $path; }
    function wp_register_script($handle, $src, $deps, $version, $footer) { $GLOBALS['script'] = func_get_args(); }
    function wp_enqueue_style($handle, $src, $deps, $version) { $GLOBALS['style'] = func_get_args(); }
    function is_checkout() { return $GLOBALS['checkout']; }
    class FakeGateway {
        public $enabled = true;
        public $eligible = true;
        public $icon = 'https://example.test/bml.png';
        public $supports = array('products', 'refunds');
        public $api_key = 'SECRET-MUST-NOT-LEAK';
        public $api_login = 'PRIVATE-LOGIN';
        public function is_available() { return $this->enabled && $this->eligible; }
        public function get_title() { return '<b>Credit Card</b>'; }
        public function get_description() { return '<p>Pay with BML.</p>'; }
        public function supports($feature) { return $feature === 'products'; }
    }
    // WooCommerce uses different service and collection objects.
    class FakeRegistry {
        public $gateways = array();
        public function payment_gateways() { return $this->gateways; }
    }
    class FakeApp {
        public $registry;
        public function payment_gateways() { return $this->registry; }
    }
    function WC() { return $GLOBALS['wc']; }
    $wc = new FakeApp();
    $wc->registry = new FakeRegistry();
    $gateway = new FakeGateway();
    $wc->registry->gateways['woocommerce_bml_mpos_integration'] = $gateway;
    require dirname(__DIR__) . '/app/blocks.php';
    fire('before_woocommerce_init');
    check(\Automattic\WooCommerce\Utilities\FeaturesUtil::$declarations[0] === array('cart_checkout_blocks', BML_MPOS_PLUGIN_FILE, true), 'feature registration');
    fire('woocommerce_blocks_loaded');
    $registry = new class {
        public $method;
        public function register($method) { $this->method = $method; }
    };
    fire('woocommerce_blocks_payment_method_type_registration', $registry);
    $method = $registry->method;
    $method->initialize();
    check($method->get_name() === 'woocommerce_bml_mpos_integration', 'gateway ID');
    check($method->is_active() === true, 'enabled gateway');
    $data = $method->get_payment_method_data();
    check(array_keys($data) === array('title','description','icon','isActive','supports'), 'public data whitelist');
    check(strpos(json_encode($data), 'SECRET') === false && strpos(json_encode($data), 'PRIVATE') === false, 'no credentials');
    check($data['title'] === 'Credit Card' && $data['description'] === 'Pay with BML.', 'plain display text');
    check($data['supports'] === array('products'), 'features reflect gateway support');
    $gateway->enabled = false;
    check($method->is_active() === false && $method->get_payment_method_data()['isActive'] === false, 'disabled gateway');
    $gateway->enabled = true;
    $gateway->eligible = false;
    check($method->is_active() === false, 'gateway availability respected');
    $method->get_payment_method_script_handles_for_admin();
    check($script[2] === array('wc-blocks-registry','wc-settings','wp-element','wp-html-entities','wp-i18n'), 'script dependencies');
    $checkout = false; fire('wp_enqueue_scripts'); check(!isset($style), 'no CSS on unrelated pages');
    $checkout = true; fire('wp_enqueue_scripts'); check($style[0] === 'bml-mpos-payment', 'checkout CSS');
    $wc->registry->gateways = array(); $method->initialize();
    check($method->is_active() === false && $method->get_payment_method_data() === array(), 'missing gateway');
    echo "PASS: PHP adapter registration, availability, public data whitelist, features, dependencies and CSS scope.\n";
}
