<?php

namespace Tests\Unit;

use App\Concert;
use App\Order;
use App\Reservation;
use App\Ticket;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Mockery;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    /** @test */
    public function calculation_the_total_cost()
    {
        $tickets = collect([
            (object)['price' => 1200],
            (object)['price' => 1200],
            (object)['price' => 1200],
        ]);

        $reservation = new Reservation($tickets);
        $this->assertEquals(3600, $reservation->totalCost());
    }

}
