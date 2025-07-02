<?php

/**
 * Plugin Name: Custom Payment Gateway
 * Description: A custom payment gateway for woocommerce
 * Version: 1.0
 * Author: Wasi Ur Rahman
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'plugins_loaded', 'init_custom_payment_gateway' );

function init_custom_payment_gateway() {
    if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
        return;
    }

    require_once plugin_dir_path( __FILE__ ) . 'includes/class-custom-payment-gateway.php';

    add_filter( 'woocommerce_payment_gateways', function ( $gateways ) {
        $gateways[] = 'Custom_Payment_Gateway';
        return $gateways;
    } );
}