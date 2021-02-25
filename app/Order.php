<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed tickets
 * @property mixed email
 * @property mixed concert
 * @property mixed amount
 */
class Order extends Model
{
    protected $guarded = [];

    /**
     * For Tickets.
     *
     * @param $tickets
     * @param $email
     * @param $amount
     * @return mixed
     */
    public static function forTickets($tickets, $email, $amount)
    {
        $order = self::create([
            'email' => $email,
            'amount' => $amount,
        ]);

        foreach($tickets as $ticket){
            $order->tickets()->save($ticket);
        }

        return $order;
    }

    /**
     * Concert.
     *
     * @return mixed
     */
    public function concert()
    {
        return $this->belongsTo(Concert::class);
    }

    /**
     * Tickets.
     *
     * @return mixed
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Ticket Quantity.
     *
     * @return mixed
     */
    public function ticketQuantity()
    {
        return $this->tickets()->count();
    }

    /**
     * To Array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
          'email' => $this->email,
          'ticket_quantity' => $this->ticketQuantity(),
          'amount' => $this->amount,
        ];
    }
}


