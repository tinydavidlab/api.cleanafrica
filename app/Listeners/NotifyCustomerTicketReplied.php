<?php

namespace App\Listeners;

use App\Events\AdminRepliedTicket;
use App\Notifications\TicketWasReplied;
use Illuminate\Support\Facades\Notification;

class NotifyCustomerTicketReplied
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param AdminRepliedTicket $event
     * @return void
     */
    public function handle( AdminRepliedTicket $event )
    {
        $customer = $event->reply->ticket->customer;
        $reply    = $event->reply;

        if ( is_null( $customer->device_token ) ) return;
        Notification::send( $customer, new TicketWasReplied( $reply ) );
    }
}
