<?php

namespace App\Events;

use App\Models\Customer;

class NewUserRegistered extends Event
{
    /**
     * Create a new event instance.
     *
     * @param Customer $customer
     */
    public function __construct( public Customer $customer )
    {
    }
}
