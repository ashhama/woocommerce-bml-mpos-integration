<?php
/**
 * Adds 'transaction id' column header to 'Orders' page immediately after 'Total' column.
 *
 * @param string[] $columns
 * @return string[] $new_columns
 */
function woocommerce_bml_mpos_integration_transaction_id_column_header($columns)
{

    $new_columns = array();

    foreach ($columns as $column_name => $column_info)
    {

        $new_columns[$column_name] = $column_info;

        if ('order_total' === $column_name)
        {
            $new_columns['transaction_id'] = __('Transaction ID', 'woocommerce_bml_mpos_integration');
        }
    }

    return $new_columns;
}
add_filter('manage_edit-shop_order_columns', 'woocommerce_bml_mpos_integration_transaction_id_column_header', 20);

if (!function_exists('mpos_integration_get_transaction_id_meta')):

    /**
     * Helper function to get meta for an order.
     *
     * @param \WC_Order $order the order object
     * @param string $key the meta key
     * @param bool $single whether to get the meta as a single item. Defaults to `true`
     * @param string $context if 'view' then the value will be filtered
     * @return mixed the order property
     */
    function mpos_integration_get_transaction_id_meta($order, $key = '', $single = true, $context = 'edit')
    {

        // WooCommerce > 3.0
        if (defined('WC_VERSION') && WC_VERSION && version_compare(WC_VERSION, '3.0', '>='))
        {

            $value = $order->get_meta($key, $single, $context);

        }
        else
        {

            // have the $order->get_id() check here just in case the WC_VERSION isn't defined correctly
            $order_id = is_callable(array(
                $order,
                'get_id'
            )) ? $order->get_id() : $order->id;
            $value = get_post_meta($order_id, $key, $single);
        }

        return $value;
    }

endif;

/**
 * Adds 'Transaction ID' column content to 'Orders' page immediately after 'Total' column.
 *
 * @param string[] $column name of column being displayed
 */
function mpos_integration_add_transaction_id_column_content($column)
{
    global $post;

    if ('transaction_id' === $column)
    {

        $order = wc_get_order($post->ID);
        $transactionID = $order->get_transaction_id();
        $allTransactionIDs = mpos_integration_get_transaction_id_meta($order, 'all_transaction_ids');
        $displayedTransactionID = '';
        // don't check for empty() since cost can be '0'
        if ('' !== $transactionID || false !== $transactionID)
        {

            $displayedTransactionID = $transactionID;

        }
        else
        {

            $displayedTransactionID = $allTransactionIDs;

        }

        echo $displayedTransactionID;
    }
}
add_action('manage_shop_order_posts_custom_column', 'mpos_integration_add_transaction_id_column_content');
?>
