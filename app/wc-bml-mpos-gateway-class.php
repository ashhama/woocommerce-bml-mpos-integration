<?php

/**
 * Woocommerce Payment Gateway Object
 */


use BMLConnect\Client;

class WOOCOMMERCE_BML_MPOS_INTEGRATION extends WC_Payment_Gateway
{

    function __construct()
    {

        // global ID
        $this->id = "woocommerce_bml_mpos_integration";
        // Title of Gateway
        $this->method_title = __("BML mPOS Payment", 'woocommerce_bml_mpos_integration');
        // Gateway Description
        $this->method_description = __("BML mPOS Payment Gateway Plug-in for WooCommerce (This Plugin is developed by a 3rd party and is in no way related to Bank of Maldives)", 'woocommerce_bml_mpos_integration');
        // Title in the vertical tab
        $this->title = __("BML mPOS", 'woocommerce_bml_mpos_integration');
        //icon
        $this->icon = null;
        //Will not have fields on user side, routed to gateway for payment
        $this->has_fields = false;

        // Code to add support form if required
        // $this->supports = array( 'default_credit_card_form' );

        // initialize form fields
        $this->init_form_fields();
        // create the settings object
        $this->init_settings();

        // Add all settings into the vairables of the object
        foreach ($this->settings as $setting_key => $value)
        {
            $this->$setting_key = $value;
        }

        // Code if doing ssl checks in future
        //add_action( 'admin_notices', array( $this,  'do_ssl_check' ) );

        //Add the EventListener to listen for redirect urls comming from the bml gateway
        add_action('woocommerce_api_' . strtolower(get_class($this)) , array(
            $this,
            'check_bml_response_message'
        ));

        // Save settings
        if (is_admin())
        {
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array(
                $this,
                'process_admin_options'
            ));
        }

    }


    /**
     * Administration fields for the gateway
     * @param void no input
     * @return void no input
     */
    public function init_form_fields()
    {
        $this->form_fields = array(
            'enabled' => array(
                'title' => __('Enable / Disable', 'woocommerce_bml_mpos_integration') ,
                'label' => __('Enable this payment gateway', 'woocommerce_bml_mpos_integration') ,
                'type' => 'checkbox',
                'default' => 'no',
            ) ,
            'title' => array(
                'title' => __('Title', 'woocommerce_bml_mpos_integration') ,
                'type' => 'text',
                'desc_tip' => __('Payment title of checkout process.', 'woocommerce_bml_mpos_integration') ,
                'default' => __('Credit Card', 'woocommerce_bml_mpos_integration') ,
            ) ,
            'description' => array(
                'title' => __('Description', 'woocommerce_bml_mpos_integration') ,
                'type' => 'textarea',
                'desc_tip' => __('Payment title of checkout process.', 'woocommerce_bml_mpos_integration') ,
                'default' => __('Payment via credit card.', 'woocommerce_bml_mpos_integration') ,
                'css' => 'max-width:450px;'
            ) ,
            'api_login' => array(
                'title' => __('BML mPOS API Login', 'woocommerce_bml_mpos_integration') ,
                'type' => 'text',
                'desc_tip' => __('This is the API Login provided for BML mPOS when you signed up for an account.', 'woocommerce_bml_mpos_integration') ,
            ) ,
            'api_key' => array(
                'title' => __('BML mPOS API Key', 'woocommerce_bml_mpos_integration') ,
                'type' => 'password',
                'desc_tip' => __('This is the API Key provided by BML when you signed up for the account.', 'woocommerce_bml_mpos_integration') ,
            ) ,
            'security' => array(
                'title' => __('Security Level', 'woocommerce_bml_mpos_integration') ,
                'label' => __('Set Security Level', 'woocommerce_bml_mpos_integration') ,
                'type' => 'select',
                'options' => array(
                    0 => __('Standard', 'woocommerce_bml_mpos_integration') ,
                    1 => __('Strong', 'woocommerce_bml_mpos_integration') ,
                    2 => __('Strongest (Recommended)', 'woocommerce_bml_mpos_integration') ,
                ) ,
                'description' => __('Set the security Level of the Gateway.', 'woocommerce_bml_mpos_integration') ,
                'default' => 2,
            ) ,
            'print_receipt_button' => array(
                'title' => __('Print Receipt Button (Experimental)', 'woocommerce_bml_mpos_integration') ,
                'label' => __('Show Print Receipt Button', 'woocommerce_bml_mpos_integration') ,
                'type' => 'checkbox',
                'description' => __('Show Print Receipt Button in Order Page and Thankyou Page', 'woocommerce_bml_mpos_integration') ,
                'default' => 'no',
            ) ,
            'environment' => array(
                'title' => __('BML mPOS Test Mode', 'woocommerce_bml_mpos_integration') ,
                'label' => __('Enable Test Mode', 'woocommerce_bml_mpos_integration') ,
                'type' => 'checkbox',
                'description' => __('This is the test mode of gateway.', 'woocommerce_bml_mpos_integration') ,
                'default' => 'no',
            )
        );
    }

    /**
     * Method to process payment when the user clicks the Pay button
     * @param integer $order_id the order ID
     * @return array an array of the result of the function and redirect url
     */
    public function process_payment($order_id)
    {

        /**
        * Method Steps
        * 1.Initialization
        * 2. If Transaction exists and conditions fulfilled, redirect the user to that transaction
        * 3. Otherwise, create a new transaction
        * 4. Save important details of the transaction
        * 5. Direct the user to the new payment
        */

        /**
        * 1. Initialization
        */

        global $woocommerce;

        $api_key = $this->api_key;
        $app_id = $this->api_login;

        $customer_order = new WC_Order($order_id);

        $amount = round(($customer_order->get_subtotal() * 100) , 0);
        $currency = $customer_order->get_currency();
        $signature = sha1('amount=' . $amount . '&currency=' . $currency . '&apiKey=' . $api_key, false);
        $securitysignature = sha1($customer_order->get_data() ['cart_hash'] . wp_salt() , false);
        $validationSignature = sha1($customer_order->get_id() . $signature . $api_key, false);

        // check if environment is sandbox or production
        $environment = ($this->environment == "yes") ? 'TRUE' : 'FALSE';
        $environmentType = ("FALSE" == $environment) ? 'production' : 'sandbox';

        /**
        * 2. If Transaction exists and conditions fulfilled, redirect the user to that transaction
        */
        if (!empty($customer_order->get_transaction_id()))
        {

            $client = new Client($api_key, $app_id, $environmentType);
            $transactionVerification = $client
                ->transactions
                ->get($customer_order->get_transaction_id());
            $verificationAmount = round(($customer_order->get_subtotal() * 100) , 0);

            if ($transactionVerification->amount == $amount && $currency == $transactionVerification->currency && ($transactionVerification->state == "INITIATED" || $transactionVerification->state == 'QR_CODE_GENERATED'))
            {
                return array(
                    'result' => 'success',
                    'redirect' => $transactionVerification->url,
                );
            }

        }

        /**
        * 3. Otherwise, create a new transaction
        */

        //create the redirect url
        $checkOutPaymentUrl = add_query_arg(array(
            'wc-api' => strtolower(get_class($this)) ,
            'orderId' => $order_id,
            'securitySignature' => $securitysignature,
        ) , home_url('/'));

        //initialize the client
        $client = new Client($api_key, $app_id, $environmentType);

        $json = ["currency" => $currency, "amount" => $amount, // 10.00 MVR
        "redirectUrl" => $checkOutPaymentUrl, // Optional redirect after payment completion
        "localId" => $customer_order->get_id() , // Optional redirect after payment completion
        "customerReference" => $customer_order->get_id() . "_" . $validationSignature, // Optional redirect after payment completion
        "redirectUrl" => $checkOutPaymentUrl
        // Optional redirect after payment completion
        ];

        //create the transaction
        $transaction = $client
            ->transactions
            ->create($json);

        /**
        * 4. Save important details of the transaction
        */
        $currentTransactionList = $customer_order->get_meta('all_transaction_ids', true, 'view');

        if (empty($currentTransactionList))
        {
            $customer_order->add_meta_data('all_transaction_ids', $transaction->id, true);
        }
        else
        {
            $customer_order->add_meta_data('all_transaction_ids', $currentTransactionList . ', ' . $transaction->id, true);
        }

        $customer_order->set_transaction_id($transaction->id);

        $customer_order->save();

        /**
        * 5. Direct the user to the new payment
        */
        return array(
            'result' => 'success',
            'redirect' => $transaction->url,
        );

    }

    /**
     * Listener Method that will process the return url from the payment gateway
     * @param void no input
     * @return void no output
     */
    function check_bml_response_message()
    {
        /**
        * Method Steps
        * 1. Initialize the required fields and get the Url queries
        * 2. Define the function that checks the url integrity
        * 3. Check url integrity, redirect to home page if integrity is not verified
        * 4. Once integrity is verified, then proceed with processing the order
        */

        /**
        * 1. Initialize the required fields and get the Url queries
        */
        global $woocommerce;

        $payment_gateway = WC()
            ->payment_gateways
            ->payment_gateways() ['woocommerce_bml_mpos_integration'];

        $paymentGatewaySecurityLevel = $payment_gateway->security;

        $queryData = $_GET;

        /**
        * 2. Define the function that checks the url integrity
        */
        if (!function_exists("bmlUrlTampered"))
        {

            function bmlUrlTampered($queryData, $localWooCommerceOrder)
            {
                //initialize
                $api_key = $payment_gateway->api_key;
                $app_id = $payment_gateway->api_login;

                $amount = round(($localWooCommerceOrder->get_subtotal() * 100) , 0);
                $currency = $localWooCommerceOrder->get_currency();

                $signature = sha1('amount=' . $amount . '&currency=' . $currency . '&apiKey=' . $api_key, false);
                $securitysignature = sha1($localWooCommerceOrder->get_data() ['cart_hash'] . wp_salt() , false);
                $validationSignature = sha1($localWooCommerceOrder->get_id() . $signature . $api_key, false);

                $orderStatus = strtolower($localWooCommerceOrder->get_data() ['status']);

                /* --------------------- Verification Process -------------------------*/
                // Verify if the BML standard signatures match
                if ($queryData['signature'] <> $signature)
                {

                    return true;
                }

                //Verify the custom signatures produced by this plugin if plugin security is set to Strong
                if ($queryData['securitySignature'] <> $securitysignature && $paymentGatewaySecurityLevel > 0)
                {

                    return true;

                }

                //Query the gateway to confirm the payment status, if plugin security is set to Strongest
                if ($paymentGatewaySecurityLevel > 1)
                {


                    $environment = ($payment_gateway->environment == "yes") ? 'TRUE' : 'FALSE';
                    $environmentType = ("FALSE" == $environment) ? 'production' : 'sandbox';

                    //Cross verify with BML if the payment was actually made
                    $client = new Client($api_key, $app_id, $environmentType);

                    $transaction = $client
                        ->transactions
                        ->get($queryData['transactionId']);

                    $urltransactionState = strtolower($queryData['state']);
                    $endpointTransactionState = strtolower($transaction->state);

                    $transactionValidationSignature = '';

                    if (!empty($transaction->customerReference))
                    {
                        //Get the first occurrence of a character.
                        $strpos = strpos($transaction->customerReference, '_');

                        $transactionValidationSignature = substr($transaction->customerReference, ($strpos));

                    }

                    //Verify if signature on both sides match, also verify if current state on both sides match
                    if ($transactionValidationSignature != $validationSignature || $urltransactionState != $endpointTransactionState)
                    {
                        return true;
                    }
                }

                return false;

            }
        }

        /**
        * 3. Check url integrity, redirect to home page if integrity is not verified
        */
        $order = new WC_Order($queryData['orderId']);

        if (empty($queryData['orderId']) || empty($order))
        {
            wp_redirect(home_url('/'));
        }

        if (bmlUrlTampered($queryData, $order))
        {
            wp_redirect(home_url('/'));
        }

        /**
        * 4. Once integrity is verified, then proceed with processing the order
        */

        switch (strtolower($queryData['state']))
        {
            case 'cancelled':
                write_log('the order has been cancelled');
                wp_redirect($order->get_cancel_order_url());
            break;
            case 'confirmed':
                write_log('the order has been confirmed');

                //set the order to payment complete and enpty the cart
                $order->payment_complete($queryData['transactionId']);
                $woocommerce
                    ->cart
                    ->empty_cart();

                wp_redirect($order->get_checkout_order_received_url());
            break;
            default:
                write_log('the order has another response');
                //if any other status code, redirect to cancel order page only if the order is not paid as of yet
                if ($order->get_data() ['status'] != 'processing' && $order->get_data() ['status'] != 'completed')
                {
                    //  $order->update_status('failed');
                    wp_redirect($order->get_cancel_order_url());
                }
                else
                {
                    wp_redirect(home_url('/'));
                }
            break;
        }

        die('OK');

    }

    // SSL check is will be included but not used at the moment
    /*
     * @param void no input
     * @return void no input
     */

    public function do_ssl_check()
    {
        if ($this->enabled == "yes")
        {
            if (get_option('woocommerce_force_ssl_checkout') == "no")
            {
                echo "<div class=\"error\"><p>" . sprintf(__("<strong>%s</strong> is enabled and WooCommerce is not forcing the SSL certificate on your checkout page. Please ensure that you have a valid SSL certificate and that you are <a href=\"%s\">forcing the checkout pages to be secured.</a>") , $this->method_title, admin_url('admin.php?page=wc-settings&tab=checkout')) . "</p></div>";
            }
        }
    }

}
?>
