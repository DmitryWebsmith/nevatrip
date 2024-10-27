<?php

namespace App\Services;

use Carbon\Carbon;
use Faker\Factory;

class EventService
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function createEvent()
    {
        $event = [
            'event_id' => rand(1, 5000),
            'event_date' => $this->faker->dateTimeThisYear()->format('Y-m-d H:i'),
            'ticket_adult_price' => rand(1000, 5000),
            'ticket_adult_quantity' => rand(1, 100),
            'ticket_kid_price' => rand(500, 2500),
            'ticket_kid_quantity' => rand(1, 100),
        ];

        $event['equal_price'] = $event['ticket_adult_price'] * $event['ticket_adult_quantity'] + $event['ticket_kid_price'] * $event['ticket_kid_quantity'];
        $event['created_at'] = Carbon::now();

        return $event;
    }
}
