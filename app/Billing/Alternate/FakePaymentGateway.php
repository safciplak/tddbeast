<?php

namespace App\Billing\Alternate;

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
     * Charge.
     *
     * @param $amount
     * @param $token
     */
    public function charge($amount, $token)
    {
        if($this->beforeFirstChargeCallback !== null){
            $callback = $this->beforeFirstChargeCallback;
            $this->beforeFirstChargeCallback = null;
            $callback($this);
        }
        if($token !== $this->getValidTestToken()){
            throw new PaymentFailedException;
        }
        $this->charges[] = $amount;
    }

    /**
     * Total Charges.
     *
     * @return mixed
     */
    public function totalCharges()
    {
        return $this->charges->sum();
    }

    /**
     * Before First Charge.
     *
     * @param $callback
     */
    public function beforeFirstCharge($callback)
    {
        $this->beforeFirstChargeCallback = $callback;
    }
}
