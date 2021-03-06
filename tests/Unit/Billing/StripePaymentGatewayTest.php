<?php

use App\Billing\FakePaymentGateway;
use App\Billing\PaymentFailedException;
use App\Billing\StripePaymentGateway;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Stripe\Stripe;
use Tests\TestCase;

/**
 * Class StripePaymentGatewayTest
 * @group integration
 *
 */
class StripePaymentGatewayTest extends TestCase
{
    private $lastCharge;

    protected function setUp(): void
    {
        parent::setUp();
        $this->lastCharge = $this->lastCharge();
    }

    /** @test */
    public function charges_with_a_valid_payment_token_are_successful()
    {
        // Create a new StripePaymentGateway
        $paymentGateway = new StripePaymentGateway(config('services.stripe.secret'));

        // Create a new charge for some amount using a valid token
        $paymentGateway->charge(2500, $this->validToken());

        // Verify that the charge was completed successfully
        $this->assertCount(1, $this->newCharges($this->lastCharge));
        $this->assertEquals(2500, $this->lastCharge->amount);
    }

    /** @test */
    public function charges_with_an_invalid_payment_token_fail()
    {
        try {
            $paymentGateway = new StripePaymentGateway(config('services.stripe.secret'));
            $paymentGateway->charge(2500, 'invalid-payment-token');
        } catch (PaymentFailedException $e) {
            $this->assertCount(0, $this->newCharges($this->lastCharge));
            return;
        }

        $this->fail("Charging with and invalid payment token did not throw a PaymentFailedException.");
    }

    /**
     * Last Charge.
     *
     * @return mixed
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function lastCharge()
    {
        return \Stripe\Charge::all(
            ['limit' => 1],
            ['api_key' => config('services.stripe.secret')]
        )
        ['data'][0];
    }

    /**
     * Valid Token.
     *
     * @return string
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function validToken(): string
    {
        $token = \Stripe\Token::create([
            'card' => [
                'number' => '4242424242424242',
                'exp_month' => 1,
                'exp_year' => date('Y') + 1,
                'cvc' => '123',
            ],
        ], ['api_key' => config('services.stripe.secret')])->id;
        return $token;
    }

    /**
     * New Charges.
     *
     * @param $endingBefore
     * @return mixed
     */
    public function newCharges($endingBefore)
    {
        return \Stripe\Charge::all(
            [
                'limit' => 1,
                'ending_before' => $endingBefore->id,
            ],
            ['api_key' => config('services.stripe.secret')]
        )
        ['data'];
    }
}
