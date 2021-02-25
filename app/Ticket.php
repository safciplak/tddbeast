<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed concert
 */
class Ticket extends Model
{
    protected $guarded = [];

    /**
     * Scope Available.
     *
     * @param $query
     * @return mixed
     */
    public function scopeAvailable($query)
    {
        return $query->whereNull('order_id')->whereNull('reserved_at');
    }

    /**
     * Reserve
     */
    public function reserve()
    {
        $this->update(['reserved_at' => Carbon::now()]);
    }

    /**
     * Release
     */
    public function release()
    {
        $this->update(['reserved_at' => null]);
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
     * Get Price Attribute.
     * @return mixed
     */
    public function getPriceAttribute()
    {
        return $this->concert->ticket_price;
    }
}
