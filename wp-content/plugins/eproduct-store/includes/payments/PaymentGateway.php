<?php
defined('ABSPATH') || exit;

abstract class EProduct_PaymentGateway {
    abstract public function get_name();
    abstract public function create_payment($order);
    abstract public function verify_return($order, $data);
}

function eproduct_gateway_instance($name) {
    switch ($name) {
        case 'stripe':   return new EProduct_StripeGateway();
        case 'paypal':   return new EProduct_PayPalGateway();
        case 'razorpay': return new EProduct_RazorpayGateway();
    }
    return null;
}
