<?php
defined('ABSPATH') || exit;

class EProduct_StripeGateway extends EProduct_PaymentGateway {
    public function get_name() { return 'stripe'; }

    public function create_payment($order) {
        if (!class_exists('\Stripe\StripeClient')) {
            return array('error' => 'Stripe SDK not installed. Run composer install.');
        }
        $sk = get_option('eproduct_stripe_secret');
        $stripe = new \Stripe\StripeClient($sk);
        $session = $stripe->checkout->sessions->create(array(
            'line_items' => array(array(
                'price_data' => array(
                    'currency' => 'usd',
                    'product_data' => array('name' => get_the_title($order->product_id)),
                    'unit_amount' => intval($order->amount * 100),
                ),
                'quantity' => 1,
            )),
            'mode' => 'payment',
            'success_url' => EPRODUCT_URL . 'payment-server.php?action=return&gateway=stripe&order_id=' . $order->id,
            'cancel_url' => home_url('/checkout/'),
        ));
        return array('url' => $session->url);
    }

    public function verify_return($order, $data) {
        return true;
    }
}
