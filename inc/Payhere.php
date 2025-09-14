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
}
