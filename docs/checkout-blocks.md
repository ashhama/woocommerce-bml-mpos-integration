# Checkout Blocks support

The plugin registers BML with the WooCommerce Blocks payment registry using the
existing gateway ID, `woocommerce_bml_mpos_integration`. WooCommerce's legacy
payment bridge calls the existing `process_payment()` method and handles its
redirect. API requests and payment-return processing are unchanged.

Enable and configure BML in WooCommerce payment settings, then use WooCommerce
Cart and Checkout blocks on the assigned pages. The adapter respects the gateway's
availability, title, description, supported features and icon setting. It exports
only public presentation data; API credentials are never passed to JavaScript.
Older WooCommerce installations without the Blocks integration class retain the
classic gateway without loading the adapter. The classic banner also receives
scoped, proportional sizing.

## Tests

From the plugin directory:

```sh
node --check assets/js/blocks.js
node tests/blocks.test.cjs
php tests/adapter.php
```

The PHP test uses WordPress/WooCommerce test doubles. It checks registration,
availability, public-data filtering, script dependencies and CSS scope. The
JavaScript test checks registration, gateway routing, availability, dependency
guards and display rendering. These are not end-to-end payment tests.

Before deploying a change, test in staging with the configured BML environment:

- Confirm BML appears in Checkout Blocks when available and is hidden when disabled.
- Check title, description and icon settings in the editor and storefront.
- Verify the responsive layout and other enabled payment methods.
- Check amount/currency, the BML redirect and return to the correct order.
- Test success, cancellation and retries, verifying order status, transaction
  reference, stock and confirmation email.
- Confirm the existing classic checkout still works.

Reference: https://developer.woocommerce.com/docs/block-development/extensible-blocks/cart-and-checkout-blocks/checkout-payment-methods/payment-method-integration/
