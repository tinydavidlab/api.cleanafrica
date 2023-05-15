<?php

namespace App\Events;

use App\Models\Customer;

class NewUserRegistered extends Event
{
    /**
     * @var Customer
     */
    public $customer;

    /**
     * Create a new event instance.
     *
     * @param Customer $customer
     */
    public function __construct( Customer $customer )
    {
        $this->customer = $customer;
    }
}
