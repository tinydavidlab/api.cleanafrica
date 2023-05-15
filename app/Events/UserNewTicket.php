<?php

namespace App\Events;

use App\Enums\TicketStatus;
use App\Models\Customer;
use App\Models\Ticket;

class UserNewTicket extends Event
{
    /**
     * @var Customer
     */
    public $customer;
    /**
     * @var Ticket
     */
    public $ticket;
    /**
     * @var TicketStatus
     */
    public $ticketStatus;

    /**
     * Create a new event instance.
     *
     * @param Customer $customer
     * @param Ticket $ticket
     * @param TicketStatus $ticketStatus
     */
    public function __construct( Customer $customer, Ticket $ticket, TicketStatus $ticketStatus )
    {
        $this->customer     = $customer;
        $this->ticket       = $ticket;
        $this->ticketStatus = $ticketStatus;
    }
}
