<?php

/*
Plugin Name: mPOS Gateway Integration for BML and WooCommerce
Plugin URI:
Author: Ashham
WC tested up to: 4.3.1
Description: 3rd Party developed Plugin to connect Woocommerce with BML mPOS Gateway. (Note: This Plugin is developed by a 3rd party and the plugin and its developers are in no way related to Bank of Maldives)"
Version: 1.1.0
*/

defined('ABSPATH') || exit;
define('BML_MPOS_PLUGIN_FILE', __FILE__);
define('BML_MPOS_VERSION', '1.1.0');

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/wc-bml-mpos-gateway-init.php';
require_once __DIR__ . '/app/wc-bml-mpos-gateway-menu-column.php';
require_once __DIR__ . '/app/functions.php';

require_once __DIR__ . '/app/blocks.php';
