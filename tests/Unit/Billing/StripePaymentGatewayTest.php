<?php

use App\Billing\FakePaymentGateway;
use App\Billing\PaymentFailedException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Stripe\Stripe;
use Tests\TestCase;

class StripePaymentGatewayTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function charges_with_a_valid_payment_token_are_successful()
    {
        // Create a new StripePaymentGateway
//        $paymentGateway = new StripePaymentGateway;

        $token = \Stripe\Token::create([
            'card' => [
                'number' => '4242424242424242',
                'exp_month' => 1,
                'exp_year' => date('Y') + 1,
                'cvc' => '123',
            ],
        ], ['api_key' => config('services.stripe.secret')])->id;

        dd($token);

        // Create a new charge for some amount using a valid token
        $paymentGateway->charge(2500, $token);

        // Verify that the charge was completed successfully
    }
}
