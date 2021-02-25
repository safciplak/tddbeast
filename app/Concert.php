<?php

namespace App;

use App\Billing\NotEnoughTicketsException;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed date
 * @property mixed ticket_price
 * @property mixed id
 */
class Concert extends Model
{
    protected $guarded = [];
    protected $dates = ['date'];

    /**
     * @return mixed
     */
    public function getFormattedDateAttribute()
    {
          return $this->date->format('F j, Y');
    }

    /**
     * @return mixed
     */
    public function getFormattedStartTimeAttribute()
    {
        return $this->date->format('g:ia');
    }

    /**
     * @return string
     */
    public function getTicketPriceInDollarsAttribute()
    {
        return number_format($this->ticket_price / 100,2);
    }

    /**
     * Scope Published.
     *
     * @param $query
     * @return mixed
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }

    /**
     * Orders.
     *
     * @return mixed
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'tickets');
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
     * Order Tickets.
     *
     * @param $email
     * @param $ticketQuantity
     * @return mixed
     */
    public function orderTickets($email, $ticketQuantity)
    {
        $tickets = $this->findTickets($ticketQuantity);

        return $this->createOrder($email, $tickets);
    }

    /**
     * Reserve Tickets.
     *
     * @param $quantity
     * @param $email
     * @return Reservation
     */
    public function reserveTickets($quantity, $email)
    {
        $tickets = $this->findTickets($quantity)->each(function($ticket){
            $ticket->reserve();
        });

        return new Reservation($tickets, $email);
    }

    /**
     * Find Tickets.
     *
     * @param $quantity
     * @return mixed
     */
    public function findTickets($quantity)
    {
        $tickets = $this->tickets()->available()->take($quantity)->get();
        if($tickets->count() < $quantity){
            throw new NotEnoughTicketsException;
        }

        return $tickets;
    }

    /**
     * Create Order.
     *
     * @param $email
     * @param $tickets
     * @return mixed
     */
    public function createOrder($email, $tickets)
    {
       return Order::forTickets($tickets, $email, $tickets->sum('price'));
    }

    /**
     * Add Tickets.
     *
     * @param $quantity
     * @return $this
     */
    public function addTickets($quantity)
    {
        foreach(range(1, $quantity) as $i){
            $this->tickets()->create([]);
        }

        return $this;
    }

    /**
     * Tickets Remaining.
     *
     * @return mixed
     */
    public function ticketsRemaining()
    {
        return $this->tickets()->available()->count();
    }

    /**
     * Has Order For.
     *
     * @param $customerEmail
     * @return bool
     */
    public function hasOrderFor($customerEmail)
    {
        return $this->orders()->where('email', $customerEmail)->count() > 0;
    }

    /**
     * Orders For.
     *
     * @param $customerEmail
     * @return mixed
     */
    public function ordersFor($customerEmail)
    {
        return $this->orders()->where('email', $customerEmail)->get();
    }
}
