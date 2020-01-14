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

    public function totalCost()
    {
        return $this->tickets->sum('price');
    }

    public function tickets()
    {
        return $this->tickets;
    }

    public function email()
    {
        return $this->email;
    }

    public function complete()
    {
        return Order::forTickets($this->tickets(), $this->email(), $this->totalCost());
    }

    public function cancel()
    {
        foreach($this->tickets as $ticket){
            $ticket->release();
        }
    }
}


