<?php
defined('ABSPATH') || exit;

class EProduct_PayPalGateway extends EProduct_PaymentGateway {
    public function get_name() { return 'paypal'; }

    private function token() {
        $client = get_option('eproduct_paypal_client');
        $secret = get_option('eproduct_paypal_secret');
        $ch = curl_init('https://api-m.sandbox.paypal.com/v1/oauth2/token');
        curl_setopt_array($ch, array(
            CURLOPT_HTTPHEADER => array('Accept: application/json', 'Accept-Language: en_US'),
            CURLOPT_USERPWD => $client . ':' . $secret,
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
            CURLOPT_RETURNTRANSFER => true,
        ));
        $r = json_decode(curl_exec($ch));
        return $r->access_token;
    }

    public function create_payment($order) {
        $token = $this->token();
        $ch = curl_init('https://api-m.sandbox.paypal.com/v2/checkout/orders');
        curl_setopt_array($ch, array(
            CURLOPT_HTTPHEADER => array('Content-Type: application/json', 'Authorization: Bearer ' . $token),
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode(array(
                'intent' => 'CAPTURE',
                'purchase_units' => array(array('amount' => array('currency_code' => 'USD', 'value' => number_format($order->amount, 2)))),
            )),
            CURLOPT_RETURNTRANSFER => true,
        ));
        $r = json_decode(curl_exec($ch));
        return array('url' => 'https://www.sandbox.paypal.com/checkoutnow?token=' . $r->id);
    }

    public function verify_return($order, $data) {
        return true;
    }
}
