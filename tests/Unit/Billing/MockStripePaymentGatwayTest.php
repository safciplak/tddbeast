<?php

use App\Billing\Alternate\FakePaymentGateway;
use App\Billing\Alternate\PaymentFailedException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MockStripePaymentGatewayTest extends TestCase
{
    /** @test */
    public function charges_with_a_valid_payment_token_are_successful()
    {
        $stripeClient = Mockery::spy(\Stripe\ApiClient::class);

        $paymentGateway = new \App\Billing\Alternate\StripePaymentGateway($stripeClient);

        $paymentGateway->charge(2500, 'valid-token');

        $stripeClient->shouldHaveReceived('createCharge')->with([
           'amount' => 2500,
           'source' => 'valid-token',
           'currency' => 'usd',
        ])->once();
    }
}
