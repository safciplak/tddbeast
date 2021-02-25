<?php

namespace App;

use Illuminate\Support\Collection;

class Reservation
{
    private $tickets;
    private $email;

    /**
     * Reservation constructor.
     * @param Collection $tickets
     * @param $email
     */
    public function __construct(Collection $tickets, $email)
    {
        $this->tickets = $tickets;
        $this->email = $email;
    }

    /**
     * Total Cost.
     *
     * @return mixed
     */
    public function totalCost()
    {
        return $this->tickets->sum('price');
    }

    /**
     * Tickets.
     *
     * @return Collection
     */
    public function tickets()
    {
        return $this->tickets;
    }

    /**
     * Email.
     *
     * @return mixed
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * Complete.
     *
     * @param $paymentGateway
     * @param $paymentToken
     * @return mixed
     */
    public function complete($paymentGateway, $paymentToken)
    {
        $paymentGateway->charge($this->totalCost(), $paymentToken);

        return Order::forTickets($this->tickets(), $this->email(), $this->totalCost());
    }

    /**
     * Cancel.
     */
    public function cancel()
    {
        foreach($this->tickets as $ticket){
            $ticket->release();
        }
    }
}


