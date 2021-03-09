<?php

namespace App\Events;

use App\Models\Agent;
use App\Models\Trip;

class CollectorUpdatedTripEvent extends Event
{

    public $trip;

    /**
     * Create a new event instance.
     *
     * @param Trip $trip
     */
    public function __construct(Trip $trip)
    {
        $this->trip = $trip;
    }
}
