<?php

namespace App\Billing;

class FakePaymentGateway implements PaymentGateway
{

    private $charges;
    private $beforeFirstChargeCallback;

    /**
     * FakePaymentGateway constructor.
     */
    public function __construct()
    {
        $this->charges = collect();
    }

    public function getValidTestToken()
    {
        return "valid-token";

    }

    /**
     * @param $amount
     * @param $token
     */
    public function charge($amount, $token)
    {
        if($this->beforeFirstChargeCallback !== null){
            $this->beforeFirstChargeCallback->__invoke($this);
        }
        if($token !== $this->getValidTestToken()){
            throw new PaymentFailedException;
        }
        $this->charges[] = $amount;
    }

    public function totalCharges()
    {
        return $this->charges->sum();
    }

    public function beforeFirstCharge($callback)
    {
        $this->beforeFirstChargeCallback = $callback;
    }

}