<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Custom_Payment_Gateway extends WC_Payment_Gateway {

    public $instructions;

    public function __construct() {
        $this->id = 'custom_gateway';
        $this->method_title = 'Classic Bank Transfer';
        $this->method_description = 'Custom bank transfer or manual payment method';
        $this->has_fields = false;

        // Load admin settings
        $this->init_form_fields();
        $this->init_settings();

        // Get settings
        $this->enabled      = $this->get_option( 'enabled' );
        $this->title        = $this->get_option( 'title' );
        $this->description  = $this->get_option( 'description' );
        $this->instructions = $this->get_option( 'instructions' );

        // Hooks
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [ $this, 'process_admin_options' ] );
        add_action( 'woocommerce_thankyou_' . $this->id, [ $this, 'thank_you_page' ] );

        // Dokan compatibility: Mark as supported
        $this->supports = [
            'products',
            'dokan'
        ];
    }

    public function init_form_fields() {
        $this->form_fields = [
            'enabled' => [
                'title'   => __( 'Enable/Disable', 'woocommerce' ),
                'type'    => 'checkbox',
                'label'   => __( 'Enable Classic Bank Transfer', 'woocommerce' ),
                'default' => 'yes',
            ],
            'title' => [
                'title'       => __( 'Title', 'woocommerce' ),
                'type'        => 'text',
                'description' => __( 'This controls the title shown during checkout.', 'woocommerce' ),
                'default'     => __( 'Direct Bank Transfer', 'woocommerce' ),
                'desc_tip'    => true,
            ],
            'description' => [
                'title'       => __( 'Description', 'woocommerce' ),
                'type'        => 'textarea',
                'description' => __( 'Payment method description shown to customers at checkout.', 'woocommerce' ),
                'default'     => __( 'Pay via bank transfer; details will be provided after placing the order.', 'woocommerce' ),
            ],
            'instructions' => [
                'title'       => __( 'Instructions', 'woocommerce' ),
                'type'        => 'textarea',
                'description' => __( 'Instructions shown on the thank you page after checkout.', 'woocommerce' ),
                'default'     => __( 'Make your payment directly into our bank account. Please use your Order ID as the payment reference.', 'woocommerce' ),
            ],
        ];
    }

    public function process_payment( $order_id ) {
        $order = wc_get_order( $order_id );

        if ( ! $order ) {
            wc_add_notice( __( 'Error: Unable to load order.', 'woocommerce' ), 'error' );
            return;
        }

        // Set status to on-hold (waiting for payment)
        $order->update_status( 'on-hold', __( 'Awaiting manual payment.', 'woocommerce' ) );

        // Reduce stock levels
        wc_reduce_stock_levels( $order_id );

        // Empty the cart
        WC()->cart->empty_cart();

        // Return thank you page redirect
        return [
            'result'   => 'success',
            'redirect' => $order->get_checkout_order_received_url(),
        ];
    }

    public function thank_you_page( $order_id ) {
        if ( $this->instructions ) {
            echo wp_kses_post( wpautop( wptexturize( $this->instructions ) ) );
        }
    }

    /**
     * Ensure the gateway is available for Dokan vendors
     */
    public function is_available() {
        $is_available = ( 'yes' === $this->enabled );

        // Add Dokan-specific checks if needed
        if ( class_exists( 'WeDevs_Dokan' ) && function_exists( 'dokan_get_seller_id_by_order' ) ) {
            // Optionally restrict gateway availability for specific vendors
            // Example: Check if the order contains products from a specific vendor
            return $is_available;
        }

        return $is_available;
    }
}