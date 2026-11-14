<?php
/**
 * Copyright © Lyra Network and contributors.
 * This file is part of Izipay plugin for WooCommerce. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @author    Geoffrey Crofte, Alsacréations (https://www.alsacreations.fr/)
 * @copyright Lyra Network and contributors
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL v2)
 */

/**
 * Plugin Name: Izipay for WooCommerce
 * Description: This plugin links your WordPress WooCommerce shop to the payment gateway.
 * Author: Lyra Network
 * Contributors: Alsacréations (Geoffrey Crofte http://alsacreations.fr/a-propos#geoffrey)
 * Version: 1.18.3
 * Author URI: https://www.lyra.com/
 * License: GPLv2 or later
 * Requires at least: 3.5
 * Tested up to: 7.0
 * WC requires at least: 2.0
 * WC tested up to: 10.9
 *
 * Text Domain: woo-micuentaweb-payment
 * Domain Path: /languages/
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry;
use Lyranetwork\Micuentaweb\Sdk\Refund\Api as MicuentawebRefundApi;
use Lyranetwork\Micuentaweb\Sdk\Refund\OrderInfo as MicuentawebOrderInfo;

define('WC_MICUENTAWEB_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WC_MICUENTAWEB_PLUGIN_PATH', untrailingslashit(plugin_dir_path(__FILE__)));

/* A global var to easily enable/disable features. */
global $micuentaweb_plugin_features;

$micuentaweb_plugin_features = array(
    'qualif' => false,
    'prodfaq' => true,
    'restrictmulti' => false,
    'shatwo' => true,
    'subscr' => true,

    'multi' => false,
    'klarna' => false,
    'franfinance' => false,
    'sepa' => false,

    'whitelabelall' => false
);

/* Check requirements. */
function woocommerce_micuentaweb_activation()
{
    $all_active_plugins = get_option('active_plugins');
    if (is_multisite()) {
        $all_active_plugins = array_merge($all_active_plugins, wp_get_active_network_plugins());
    }

    $all_active_plugins = apply_filters('active_plugins', $all_active_plugins);

    if (! stripos(implode('', $all_active_plugins), '/woocommerce.php')) {
        deactivate_plugins(plugin_basename(__FILE__)); // Deactivate ourself.

        // Load translation files.
        load_plugin_textdomain('woo-micuentaweb-payment', false, plugin_basename(dirname(__FILE__)) . '/languages');

        $message = sprintf(__('Sorry ! In order to use WooCommerce %s Payment plugin, you need to install and activate the WooCommerce plugin.', 'woo-micuentaweb-payment'), 'Izipay');
        wp_die($message, 'Izipay for WooCommerce', array('back_link' => true));
    }
}
register_activation_hook(__FILE__, 'woocommerce_micuentaweb_activation');

/* Delete all data when uninstalling plugin. */
function woocommerce_micuentaweb_uninstallation()
{
    delete_option('woocommerce_micuentaweb_settings');
    delete_option('woocommerce_micuentawebstd_settings');
    delete_option('woocommerce_micuentawebmulti_settings');
    delete_option('woocommerce_micuentawebklarna_settings');
    delete_option('woocommerce_micuentawebfranfinance_settings');
    delete_option('woocommerce_micuentawebsepa_settings');
    delete_option('woocommerce_micuentawebregroupedother_settings');
    delete_option('woocommerce_micuentawebsubscription_settings');
    delete_option('woocommerce_micuentawebwcssubscription_settings');
}
register_uninstall_hook(__FILE__, 'woocommerce_micuentaweb_uninstallation');

/* Include gateway classes. */
function woocommerce_micuentaweb_init()
{
    // Load translation files.
    load_plugin_textdomain('woo-micuentaweb-payment', false, plugin_basename(dirname(__FILE__)) . '/languages');

    if (! class_exists('Micuentaweb_Subscriptions_Loader')) { // Load subscriptions processing mecanism.
        require_once 'includes/subscriptions/micuentaweb-subscriptions-loader.php';
    }

    if (! class_exists('WC_Gateway_Micuentaweb')) {
        require_once 'class-wc-gateway-micuentaweb.php';
    }

    if (! class_exists('WC_Gateway_MicuentawebStd')) {
        require_once 'class-wc-gateway-micuentawebstd.php';
    }

    if (MicuentawebTools::get_white_label_feature('multi') && ! class_exists('WC_Gateway_MicuentawebMulti')) {
        require_once 'class-wc-gateway-micuentawebmulti.php';
    }

    if (MicuentawebTools::get_white_label_feature('klarna') && ! class_exists('WC_Gateway_MicuentawebKlarna')) {
        require_once 'class-wc-gateway-micuentawebklarna.php';
    }

    if (MicuentawebTools::get_white_label_feature('franfinance') && ! class_exists('WC_Gateway_MicuentawebFranfinance')) {
        require_once 'class-wc-gateway-micuentawebfranfinance.php';
    }

    if (MicuentawebTools::get_white_label_feature('sepa') && ! class_exists('WC_Gateway_MicuentawebSepa')) {
        require_once 'class-wc-gateway-micuentawebsepa.php';
    }

    if (! class_exists('WC_Gateway_MicuentawebRegroupedOther')) {
        require_once 'class-wc-gateway-micuentawebregroupedother.php';
    }

    if (! class_exists('WC_Gateway_MicuentawebOther')) {
        require_once 'class-wc-gateway-micuentawebother.php';
    }

    if (MicuentawebTools::get_white_label_feature('subscr') && ! class_exists('WC_Gateway_MicuentawebSubscription')) {
        require_once 'class-wc-gateway-micuentawebsubscription.php';
    }

    if (! class_exists('WC_Gateway_MicuentawebWcsSubscription')) {
        require_once 'class-wc-gateway-micuentawebwcssubscription.php';
    }

    require_once 'includes/sdk-autoload.php';
    require_once 'includes/MicuentawebRestTools.php';
    require_once 'includes/MicuentawebSubscriptionTools.php';
    require_once 'includes/class-wc-micuentaweb-sepa-payment-token.php';
    require_once 'includes/MicuentawebTools.php';

    // Restore WC notices in case of POST as return mode.
    WC_Gateway_Micuentaweb::restore_wc_notices();
}
add_action('woocommerce_init', 'woocommerce_micuentaweb_init');

function woocommerce_micuentaweb_woocommerce_block_support()
{
    if (class_exists('Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType')) {
        require_once 'includes/MicuentawebTools.php';
        if (! MicuentawebTools::has_checkout_block()) {
            return;
        }

        if (! class_exists('WC_Gateway_Micuentaweb_Blocks_Support')) {
            require_once 'includes/class-wc-gateway-micuentaweb-blocks-support.php';
        }

        add_action(
            'woocommerce_blocks_payment_method_type_registration',
            function(PaymentMethodRegistry $payment_method_registry) {
                $payment_method_registry->register(new WC_Gateway_Micuentaweb_Blocks_Support('micuentawebstd'));

                if (MicuentawebTools::get_white_label_feature('multi')) {
                    $payment_method_registry->register(new WC_Gateway_Micuentaweb_Blocks_Support('micuentawebmulti'));
                }

                if (MicuentawebTools::get_white_label_feature('franfinance')) {
                    $payment_method_registry->register(new WC_Gateway_Micuentaweb_Blocks_Support('micuentawebfranfinance'));
                }

                if (MicuentawebTools::get_white_label_feature('klarna')) {
                    $payment_method_registry->register(new WC_Gateway_Micuentaweb_Blocks_Support('micuentawebklarna'));
                }

                if (MicuentawebTools::get_white_label_feature('sepa')) {
                    $payment_method_registry->register(new WC_Gateway_Micuentaweb_Blocks_Support('micuentawebsepa'));
                }

                if (MicuentawebTools::get_white_label_feature('subscr')) {
                    $payment_method_registry->register(new WC_Gateway_Micuentaweb_Blocks_Support('micuentawebsubscription'));
                }

                $payment_method_registry->register(new WC_Gateway_Micuentaweb_Blocks_Support('micuentawebwcssubscription'));

                $payment_method_registry->register(new WC_Gateway_Micuentaweb_Blocks_Support('micuentawebregroupedother'));

                if (get_transient('micuentaweb_other_methods')) {
                    $methods = json_decode(get_transient('micuentaweb_other_methods'), true);

                    // Virtual method to display non regrouped other payment means.
                    $payment_method_registry->register(
                        new WC_Gateway_Micuentaweb_Blocks_Support('micuentawebother_lyranetwork')
                    );

                    foreach ($methods as $code => $label) {
                        $payment_method_registry->register(
                            new WC_Gateway_Micuentaweb_Blocks_Support('micuentawebother_' . $code, $label)
                        );
                    }
                }
             }
        );
    }
}

add_action('woocommerce_blocks_loaded', 'woocommerce_micuentaweb_woocommerce_block_support');

/* Add our payment methods to WooCommerce methods. */
function woocommerce_micuentaweb_add_method($methods)
{
    global $woocommerce;

    $methods[] = 'WC_Gateway_Micuentaweb';
    $methods[] = 'WC_Gateway_MicuentawebStd';

    if (MicuentawebTools::get_white_label_feature('multi')) {
        $methods[] = 'WC_Gateway_MicuentawebMulti';
    }

    if (MicuentawebTools::get_white_label_feature('klarna')) {
        $methods[] = 'WC_Gateway_MicuentawebKlarna';
    }

    if (MicuentawebTools::get_white_label_feature('franfinance')) {
        $methods[] = 'WC_Gateway_MicuentawebFranfinance';
    }

    if (MicuentawebTools::get_white_label_feature('sepa')) {
        $methods[] = 'WC_Gateway_MicuentawebSepa';
    }

    $methods[] = 'WC_Gateway_MicuentawebWcsSubscription';

    if (MicuentawebTools::get_white_label_feature('subscr')) {
        $methods[] = 'WC_Gateway_MicuentawebSubscription';
    }

    $methods[] = 'WC_Gateway_MicuentawebRegroupedOther';

    if (get_transient('micuentaweb_other_methods')) {
        $other_methods = json_decode(get_transient('micuentaweb_other_methods'), true);

        foreach ($other_methods ?? [] as $code => $label) {
            $methods[] = new WC_Gateway_MicuentawebOther($code, $label);
        }
    }

    // Since 2.3.0, we can display other payment means as submodules.
    if (version_compare($woocommerce->version, '2.3.0', '>=') && $woocommerce->cart) {
        $regrouped_other_payments = new WC_Gateway_MicuentawebRegroupedOther(false);

        if (! $regrouped_other_payments->regroup_other_payment_means()) {
            $micuentaweb_other_methods = array();
            $payment_means = $regrouped_other_payments->get_available_options();

            if (is_array($payment_means) && ! empty($payment_means)) {
                foreach ($payment_means as $option) {
                    $micuentaweb_other_methods[$option['payment_mean']] =  $option['label'];
                }
            }

            set_transient('micuentaweb_other_methods', json_encode($micuentaweb_other_methods));
        } else {
            delete_transient('micuentaweb_other_methods');
        }
    }

    return $methods;
}
add_filter('woocommerce_payment_gateways', 'woocommerce_micuentaweb_add_method');

/* Add a link to plugin settings page from plugins list. */
function woocommerce_micuentaweb_add_link($links, $file)
{
    $links[] = '<a href="' . micuentaweb_admin_url('Micuentaweb') . '">' . __('General configuration', 'woo-micuentaweb-payment') . '</a>';
    $links[] = '<a href="' . micuentaweb_admin_url('MicuentawebStd') . '">' . __('Standard payment', 'woo-micuentaweb-payment') . '</a>';

    if (MicuentawebTools::get_white_label_feature('multi')) {
        $links[] = '<a href="' . micuentaweb_admin_url('MicuentawebMulti') . '">' . __('Payment in installments', 'woo-micuentaweb-payment')
            . '</a>';
    }

    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'woocommerce_micuentaweb_add_link', 10, 2);

function micuentaweb_admin_url($id)
{
    global $woocommerce;

    $base_url = 'admin.php?page=wc-settings&tab=checkout&section=';
    $section = strtolower($id); // Method id in lower case.

    // Backward compatibility.
    if (version_compare($woocommerce->version, '2.1.0', '<')) {
        $base_url = 'admin.php?page=woocommerce_settings&tab=payment_gateways&section=';
        $section = 'WC_Gateway_' . $id; // Class name as it is.
    } elseif (version_compare($woocommerce->version, '2.6.2', '<')) {
        $section = 'wc_gateway_' . $section; // Class name in lower case.
    }

    return admin_url($base_url . $section);
}

function woocommerce_micuentaweb_order_payment_gateways($available_gateways)
{
    global $woocommerce;
    $index_other_not_grouped_gateways_ids = array();
    $index_other_grouped_gateway_id = null;
    $gateways_ids = array();
    $index_gateways_ids = 0;
    foreach ($woocommerce->payment_gateways()->payment_gateways as $gateway) {
        if ($gateway->id === 'micuentawebregroupedother') {
            $index_other_grouped_gateway_id = $index_gateways_ids;
        } elseif (strpos($gateway->id, 'micuentawebother_') === 0) {
            $index_other_not_grouped_gateways_ids[] = $index_gateways_ids;
        }

        $gateways_ids[] = $gateway->id;
        $index_gateways_ids ++;
    }

    // Reorder custom Izipay non-grouped other payment means as they appear in WooCommerce backend.
    // And if only they are not already in last position.
    if (! empty($index_other_not_grouped_gateways_ids) && ($index_other_grouped_gateway_id !== reset($index_other_not_grouped_gateways_ids) - 1)) {
        $ordered_gateways_ids = array();
        for ($i = 0; $i < $index_other_grouped_gateway_id; $i++) {
            $ordered_gateways_ids[] = $gateways_ids[$i];
        }

        foreach ($index_other_not_grouped_gateways_ids as $index_not_grouped_other_id) {
            $ordered_gateways_ids[] = $gateways_ids[$index_not_grouped_other_id];
        }

        for ($i = $index_other_grouped_gateway_id + 1; $i < count($gateways_ids); $i++) {
            if (! in_array($i, $index_other_not_grouped_gateways_ids)) {
                $ordered_gateways_ids[] = $gateways_ids[$i];
            }
        }

        $ordered_gateways = array();
        foreach ($ordered_gateways_ids as $gateway_id) {
            if (isset($available_gateways[$gateway_id])) {
                $ordered_gateways[$gateway_id] = $available_gateways[$gateway_id];
            }
        }

        return $ordered_gateways;
    }

    return $available_gateways;
}
add_filter('woocommerce_available_payment_gateways', 'woocommerce_micuentaweb_order_payment_gateways');

function micuentaweb_subscription_messages_on_order($order)
{
    $micuentaweb_update_subscription_error_msg = get_transient('micuentaweb_update_subscription_error_msg');
    $micuentaweb_renewal_error_msg = get_transient('micuentaweb_renewal_error_msg');
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function() {
        <?php
        if ($micuentaweb_update_subscription_error_msg) {
            delete_transient('micuentaweb_update_subscription_error_msg');
        ?>
            var element = jQuery('#lost-connection-notice');
            if (! element.length) {
                element = jQuery("#order_data");
            }

            if (element.length) {
                element.after('<div class="error notice is-dismissible"><p><?php echo addslashes($micuentaweb_update_subscription_error_msg); ?></p><button type="button" class="notice-dismiss" onclick="this.parentElement.remove()"><span class="screen-reader-text"><?php echo esc_html__('Dismiss this notice.', 'woocommerce') ?></span></button></div>');
            }
        <?php
        }

        if ($micuentaweb_renewal_error_msg) {
            delete_transient('micuentaweb_renewal_error_msg');
        ?>
            var element = jQuery('#lost-connection-notice');
            if (! element.length) {
                element = jQuery("#order_data");
            }

            if (element.length) {
                element.after('<div class="error notice is-dismissible"><p><?php echo addslashes($micuentaweb_renewal_error_msg); ?></p><button type="button" class="notice-dismiss" onclick="this.parentElement.remove()"><span class="screen-reader-text"><?php echo esc_html__('Dismiss this notice.', 'woocommerce') ?></span></button></div>');
            }
        <?php
        }
        ?>
        });
    </script>
    <?php
}
// Display subscriptions error messages.
add_action('woocommerce_admin_order_data_after_billing_address', 'micuentaweb_subscription_messages_on_order');

/* Retrieve blog_id from POST when this is an IPN URL call. */
require_once 'includes/sdk-autoload.php';
require_once 'includes/MicuentawebRestTools.php';

if (MicuentawebRestTools::checkResponse($_POST)) {
    $answer = json_decode($_POST['kr-answer'], true);
    $data = MicuentawebRestTools::convertRestResult($answer);
    $is_valid_ipn = isset($data['vads_ext_info_blog_id']);
} else {
    $is_valid_ipn = isset($_POST['vads_hash']) && isset($_POST['vads_ext_info_blog_id']);
}

if (is_multisite() && $is_valid_ipn) {
    global $wpdb, $current_blog, $current_site;

    $blog = isset($_POST['vads_ext_info_blog_id']) ? $_POST['vads_ext_info_blog_id'] : $data['vads_ext_info_blog_id'];
    switch_to_blog((int) $blog);

    // Set current_blog global var.
    $current_blog = $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM $wpdb->blogs WHERE blog_id = %s", $blog)
    );

    // Set current_site global var.
    $network_fnc = function_exists('get_network') ? 'get_network' : 'wp_get_network';
    $current_site = $network_fnc($current_blog->site_id);
    $current_site->blog_id = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT blog_id FROM $wpdb->blogs WHERE domain = %s AND path = %s",
            $current_site->domain,
            $current_site->path
        )
    );

    $current_site->site_name = get_site_option('site_name');
    if (! $current_site->site_name) {
        $current_site->site_name = ucfirst($current_site->domain);
    }
}

function micuentaweb_launch_online_refund($order, $refund_amount, $refund_currency)
{
    if (! $order || ! $order->get_id()) {
        return;
    }

    // Prepare order information for refund.
    require_once 'includes/sdk-autoload.php';
    require_once 'includes/MicuentawebRefundProcessor.php';

    $order_info = new MicuentawebOrderInfo();
    $order_info->setOrderRemoteId($order->get_id());
    $order_info->setOrderId($order->get_id());
    $order_info->setOrderReference($order->get_order_number());
    $order_info->setOrderCurrencyIsoCode($refund_currency);
    $order_info->setOrderCurrencySign(html_entity_decode(get_woocommerce_currency_symbol($refund_currency)));
    $order_info->setOrderUserInfo(MicuentawebTools::get_user_info());
    $refund_processor = new MicuentawebRefundProcessor();

    $std_payment_method = new WC_Gateway_MicuentawebStd();

    $test_mode = $std_payment_method->get_general_option('ctx_mode') == 'TEST';
    $key = $test_mode ? $std_payment_method->get_general_option('test_private_key') : $std_payment_method->get_general_option('prod_private_key');

    $refund_api = new MicuentawebRefundApi(
        $refund_processor,
        $key,
        MicuentawebTools::get_white_label_url(WC_Gateway_Micuentaweb::REST_URL),
        $std_payment_method->get_general_option('site_id'),
        'WooCommerce'
    );

    // Do online refund.
    $refund_api->refund($order_info, $refund_amount, MicuentawebTools::get_white_label());
}

function micuentaweb_display_refund_result_message($order_id)
{
    $micuentaweb_online_refund_result = get_transient('micuentaweb_online_refund_result');

    if ($micuentaweb_online_refund_result) {
        echo $micuentaweb_online_refund_result;

        delete_transient('micuentaweb_online_refund_result');
    }
}

// Display online refund result message.
add_action('woocommerce_admin_order_totals_after_discount', 'micuentaweb_display_refund_result_message', 10, 1);

function micuentaweb_features_compatibility()
{
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('cart_checkout_blocks', __FILE__, true);
    }
}

// Declaring HPOS compatibility.
add_action('before_woocommerce_init', 'micuentaweb_features_compatibility');

function micuentaweb_online_refund($order_id, $refund_id)
{
    // Check if order was passed with other payment means submodule.
    $order = new WC_Order((int) $order_id);
    if (! $order || substr($order->get_payment_method(), 0, strlen('micuentawebother_')) !== 'micuentawebother_') {
        return;
    }

    $refund = new WC_Order_Refund((int) $refund_id);
    if (! $refund) {
        return;
    }

    // Do online refund.
    micuentaweb_launch_online_refund($order, $refund->get_amount(), $refund->get_currency());
}

// Do online refund after local refund.
add_action('woocommerce_order_refunded', 'micuentaweb_online_refund', 10 , 2);

function micuentaweb_online_cancel($order_id)
{
    $order = new WC_Order((int) $order_id);
    if (! $order || substr($order->get_payment_method(), 0, strlen('micuentaweb')) !== 'micuentaweb') {
        return;
    }

    micuentaweb_launch_online_refund($order, $order->get_total(), $order->get_currency());
}

// Do online cancel after local cancellation.
add_action('woocommerce_cancelled_order', 'micuentaweb_online_cancel');

function micuentaweb_payment_token_deleted($token_id, $token)
{
    if (strpos($token->get_gateway_id(), 'micuentaweb') !== 0) {
        return;
    }

    $micuentaweb_gateway = new WC_Gateway_Micuentaweb();
    $micuentaweb_gateway->delete_identifier_online($token->get_token(), $token->get_user_id(),  $token->get_gateway_id(), false);
}

add_action('woocommerce_payment_token_deleted', 'micuentaweb_payment_token_deleted', 10, 2);
