<?php

namespace App\Http\Controllers;

use App\Billing\FakePaymentGateway;
use App\Billing\NotEnoughTicketsException;
use App\Billing\PaymentFailedException;
use App\Billing\PaymentGateway;
use App\Concert;
use App\Order;
use App\Reservation;

class ConcertOrdersController extends Controller
{
    /**
     * @var PaymentGateway
     */
    private $paymentGateway;

    /**
     * ConcertOrdersController constructor.
     * @param FakePaymentGateway $paymentGateway
     */
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    /**
     * Store.
     *
     * @param $concertId
     * @return mixed
     */
    public function store($concertId)
    {
        $concert = Concert::published()->findOrFail($concertId);

        $this->validate(request(), [
            'email' => ['required', 'email'],
            'ticket_quantity' => ['required', 'integer', 'min:1'],
            'payment_token' => ['required'],
        ]);

        try {
            $reservation = $concert->reserveTickets(request('ticket_quantity'), request('email'));
            $order = $reservation->complete($this->paymentGateway, request('payment_token'));

            return response()->json($order, 201);
        } catch (PaymentFailedException $e) {
            $reservation->cancel();
            return response()->json([], 422);
        } catch (NotEnoughTicketsException $e) {
            return response()->json([], 422);
        }
    }
}
