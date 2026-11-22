<?php
require_once __DIR__ . '/../admin/inc/env_loader.php';

class Payhere {
    private $merchant_id;
    private $currency;
    private $merchant_secret;
    private $email;

    public function __construct() {
        $this->merchant_id     = getenv('PAYHERE_MERCHANT_ID');
        $this->currency        = getenv('PAYHERE_CURRENCY');
        $this->merchant_secret = getenv('PAYHERE_MERCHANT_SECRET');
        $this->email           = getenv('PAYHERE_EMAIL');
    }

    public function generateHash($order_id, $amount) {
        $hash = strtoupper(
            md5(
                $this->merchant_id . 
                $order_id . 
                number_format($amount, 2, '.', '') . 
                $this->currency .  
                strtoupper(md5($this->merchant_secret)) 
            ) 
        );
        
        return $hash;
    }

    public function getCurrancy(){
        return $this->currency;
    }

    public function getMerchantId() {
        return $this->merchant_id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function isSandbox() {
        $v = getenv('PAYHERE_SANDBOX');
        return $v === false || $v === '' || in_array(strtolower($v), ['1','true','yes','on'], true);
    }

    public function getCheckoutUrl() {
        return $this->isSandbox()
            ? 'https://sandbox.payhere.lk/pay/checkout'
            : 'https://www.payhere.lk/pay/checkout';
    }

    /**
     * Verify the md5sig PayHere sends to notify_url.
     */
    public function verifyNotification($merchant_id, $order_id, $payhere_amount, $payhere_currency, $status_code, $md5sig) {
        $local = strtoupper(md5(
            $merchant_id .
            $order_id .
            $payhere_amount .
            $payhere_currency .
            $status_code .
            strtoupper(md5($this->merchant_secret))
        ));
        return $merchant_id === $this->merchant_id && hash_equals($local, strtoupper((string)$md5sig));
    }
}
