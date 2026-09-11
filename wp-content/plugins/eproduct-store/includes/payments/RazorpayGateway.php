<?php
defined('ABSPATH') || exit;

class EProduct_RazorpayGateway extends EProduct_PaymentGateway {
    public function get_name() { return 'razorpay'; }

    public function create_payment($order) {
        if (class_exists('\Razorpay\Api\Api')) {
            $api = new \Razorpay\Api\Api(get_option('eproduct_razorpay_key'), get_option('eproduct_razorpay_secret'));
            $r = $api->order->create(array(
                'receipt' => 'ord_' . $order->id,
                'amount' => intval($order->amount * 100),
                'currency' => 'USD',
            ));
            return array('url' => home_url('/checkout/?razorpay_order=' . $r['id'] . '&order_id=' . $order->id));
        }
        return array('error' => 'Razorpay SDK not installed. Run composer install.');
    }

    public function verify_return($order, $data) {
        return true;
    }
}
