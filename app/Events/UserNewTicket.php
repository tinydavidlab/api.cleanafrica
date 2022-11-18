<?php

namespace App\Events;

use App\Enums\TicketStatus;
use App\Models\Customer;
use App\Models\Ticket;

class UserNewTicket extends Event
{
    /**
     * Create a new event instance.
     *
     * @param Customer     $customer
     * @param Ticket       $ticket
     * @param TicketStatus $ticketStatus
     */
    public function __construct( public Customer     $customer,
                                 public Ticket       $ticket,
                                 public TicketStatus $ticketStatus )
    {
    }
}
