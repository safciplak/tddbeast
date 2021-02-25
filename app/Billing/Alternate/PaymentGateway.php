<?php

namespace App\Billing\Alternate;

interface PaymentGateway{
    public function charge($amount, $token);
}
