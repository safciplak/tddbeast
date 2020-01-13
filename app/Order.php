<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed tickets
 */
class Order extends Model
{
    protected $guarded = [];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function ticketQuantity()
    {
        return $this->tickets()->count();
    }

    public function cancel()
    {
        foreach($this->tickets as $ticket){
            $ticket->release();
        }

        $this->delete();
    }
}


