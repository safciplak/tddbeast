<?php

namespace App\Billing\Alternate;

class StripePaymentGateway implements PaymentGateway
{
    private $stripeClient;

    /**
     * StripePaymentGateway constructor.
     *
     * @param \Stripe\ApiClient $stripeClient
     */
    public function __construct(\Stripe\ApiClient $stripeClient)
    {
        $this->stripeClient = $stripeClient;
    }

    /**
     * Charge.
     *
     * @param $amount
     * @param $token
     */
    public function charge($amount, $token)
    {
        $this->stripeClient->createCharge([
            'amount' => $amount,
            'source' => $token,
            'currency' => 'usd',
        ]);
    }
}
