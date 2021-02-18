<?php

namespace App\Listeners;

use App\Enums\TicketStatus;
use App\Events\UserNewTicket;
use App\Notifications\UserComplaint;
use App\Notifications\UserFeedback;
use Illuminate\Support\Facades\Notification;

class NotifyManagementTicket
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
     * @param UserNewTicket $event
     * @return void
     */
    public function handle( UserNewTicket $event )
    {
        $company = $event->customer->company;

        $notification = new UserComplaint( $event->customer, $event->ticket );
        if ( $event->ticketStatus === TicketStatus::CLOSED() ) {
            $notification = new UserFeedback( $event->customer, $event->ticket );
        }

        Notification::send(
            $company->admins, $notification,
        );
    }
}
