<?php

namespace App\Events;

use App\Models\Trip;

class CollectorCanceledTripEvent extends Event
{
    /**
     * @var Trip
     */
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
