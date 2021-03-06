<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Concert;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Concert::class, function (Faker $faker) {
    return [
        'title' => 'Example Band',
        'subtitle' => 'with TheFake Openers',
        'date' => Carbon::parse('+2 weeks'),
        'ticket_price' => 2000,
        'venue' => 'The Example Theatret',
        'venue_address' => '123 Example Lane',
        'city' => 'Fakeville',
        'state' => 'ON',
        'zip' => '902120',
        'additional_information' => 'Some sample additional information.',
    ];
});


$factory->state(Concert::class, 'published', function(Faker $faker){
    return [
      'published_at' => Carbon::parse('-1 week')
    ];
});

$factory->state(Concert::class, 'unpublished', function(Faker $faker){
    return [
        'published_at' => null
    ];
});