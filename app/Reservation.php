<?php

namespace App;

use Illuminate\Support\Collection;

class Reservation
{
    private $tickets;

    /**
     * Reservation constructor.
     * @param $tickets
     */
    public function __construct(Collection $tickets)
    {
        $this->tickets = $tickets;
    }

    public function totalCost()
    {
        return $this->tickets->sum('price');
    }

    public function cancel()
    {
        foreach($this->tickets as $ticket){
            $ticket->release();
        }
    }
}


